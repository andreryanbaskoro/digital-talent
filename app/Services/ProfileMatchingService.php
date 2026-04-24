<?php

namespace App\Services;

use App\Models\LamaranPekerjaan;
use App\Models\BobotSelisih;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfileMatchingService
{
    public function hitung($idLamaran)
    {
        $lamaran = LamaranPekerjaan::with([
            'lowongan.subKriteriaLowongan',
            'pencariKerja.kartuAk1',
            'subKriteriaLamaran',
            'lowongan.profilPerusahaan',
        ])->findOrFail($idLamaran);

        $pencaker = $lamaran->pencariKerja;
        $skill = $this->hitungSkill($lamaran);

        return [
            'skill'        => $skill['nilai'],
            'skill_detail' => $skill['detail'],
            'pengalaman'   => $this->hitungPengalaman(optional($pencaker->kartuAk1)->id_kartu_ak1),
            'pendidikan'   => $this->hitungPendidikan($pencaker, $lamaran),
            'lokasi'       => $this->hitungLokasi($pencaker, $lamaran->lowongan),
        ];
    }

    private function hitungSkill($lamaran)
    {
        $total = 0;
        $count = 0;
        $detail = [];

        foreach ($lamaran->lowongan->subKriteriaLowongan as $target) {

            $nilaiTarget = (int) $target->nilai_target;

            $pelamar = $lamaran->subKriteriaLamaran
                ->where('id_sub_kriteria', $target->id_sub_kriteria)
                ->first();

            $nilaiPelamar = $pelamar ? (int) $pelamar->nilai : 1;

            $selisih = $nilaiPelamar - $nilaiTarget;
            $bobot = $this->getBobotSelisih($selisih);

            $total += $bobot;
            $count++;

            $detail[] = [
                'id_sub_kriteria' => $target->id_sub_kriteria,
                'nama_kriteria'   => $target->nama_sub_kriteria ?? 'Skill',
                'nilai_pelamar'   => $nilaiPelamar,
                'nilai_target'    => $nilaiTarget,
                'selisih'         => $selisih,
                'bobot_selisih'   => $bobot,
            ];
        }

        return [
            'nilai'  => $count ? round($total / $count, 2) : 1,
            'detail' => $detail,
        ];
    }

    private function hitungPengalaman($idKartuAk1)
    {
        if (!$idKartuAk1) return 1;

        $data = DB::table('pengalaman_kerja_ak1')
            ->where('id_kartu_ak1', $idKartuAk1)
            ->whereNull('deleted_at')
            ->get();

        if ($data->isEmpty()) return 1;

        $bulan = 0;

        foreach ($data as $p) {
            if (!$p->mulai_bekerja) continue;

            $mulai = Carbon::parse($p->mulai_bekerja);
            $selesai = $p->selesai_bekerja
                ? Carbon::parse($p->selesai_bekerja)
                : Carbon::now();

            if ($selesai->lt($mulai)) continue;

            $bulan += $mulai->diffInMonths($selesai);
        }

        $tahun = $bulan / 12;

        return match (true) {
            $tahun > 5 => 5,
            $tahun >= 3 => 4,
            $tahun >= 1 => 3,
            $tahun > 0 => 2,
            default => 1,
        };
    }

    private function hitungPendidikan($pencaker, $lamaran)
    {
        $idKartuAk1 = optional($pencaker->kartuAk1)->id_kartu_ak1;

        if (!$idKartuAk1) return 1;

        $data = DB::table('riwayat_pendidikan_ak1')
            ->where('id_kartu_ak1', $idKartuAk1)
            ->whereNull('deleted_at')
            ->get();

        if ($data->isEmpty()) return 1;

        $lowongan = $lamaran->lowongan;
        $targetRaw = strtolower($lowongan->pendidikan_minimum ?? '');

        $terbaik = 1;

        foreach ($data as $p) {
            $skor = $this->rankJenjang($p->jenjang);
            $pelamarJurusan = strtolower($p->jurusan ?? '');

            if ($targetRaw && str_contains($pelamarJurusan, $targetRaw)) {
                $skor += 1;
            }

            $terbaik = max($terbaik, $skor);
        }

        return min($terbaik, 5);
    }

    private function rankJenjang($jenjang)
    {
        return match (strtoupper(trim($jenjang))) {
            'S2' => 5,
            'S1' => 5,
            'D4' => 4,
            'D3' => 3,
            'SMA', 'SMK' => 2,
            default => 1,
        };
    }

    private function hitungLokasi($pencaker, $lowongan)
    {
        $profil = $lowongan->profilPerusahaan;

        if (!$profil) return 1;

        $kabP = strtolower(trim($pencaker->kab_kota ?? ''));
        $provP = strtolower(trim($pencaker->provinsi ?? ''));

        $kabL = strtolower(trim($profil->kab_kota ?? ''));
        $provL = strtolower(trim($profil->provinsi ?? ''));

        if (!$kabP || !$provP || !$kabL || !$provL) return 1;

        if ($kabP === $kabL && $provP === $provL) return 5;
        if ($kabP === $kabL) return 4;
        if ($provP === $provL) return 3;

        return 2;
    }

    private function getBobotSelisih($selisih)
    {
        $selisih = (int) round($selisih);

        $data = BobotSelisih::where('selisih', $selisih)->first();

        if ($data) {
            return (float) $data->bobot_nilai;
        }

        return (float) BobotSelisih::create([
            'id_bobot_selisih' => BobotSelisih::generateId(),
            'selisih' => $selisih,
            'bobot_nilai' => $this->mappingBobotDefault($selisih),
            'keterangan' => 'Auto generate',
        ])->bobot_nilai;
    }

    private function mappingBobotDefault($selisih)
    {
        return match (true) {
            $selisih == 0 => 5,
            $selisih == 1 => 4.5,
            $selisih == -1 => 4,
            $selisih == 2 => 3.5,
            $selisih == -2 => 3,
            $selisih == 3 => 2.5,
            $selisih == -3 => 2,
            abs($selisih) >= 4 => 1,
            default => 1,
        };
    }

    public function hitungFinal($idLamaran)
    {
        $n = $this->hitung($idLamaran);

        $total =
            ($n['skill'] * 0.4) +
            ($n['pengalaman'] * 0.3) +
            ($n['pendidikan'] * 0.2) +
            ($n['lokasi'] * 0.1);

        $persentase = round(($total / 5) * 100, 2);

        return [
            'skill' => $n['skill'],
            'skill_detail' => $n['skill_detail'],
            'pengalaman' => $n['pengalaman'],
            'pendidikan' => $n['pendidikan'],
            'lokasi' => $n['lokasi'],
            'total_nilai' => round($total, 2),
            'persentase' => $persentase,
        ];
    }

    public function rankingLowongan($idLowongan)
    {
        $list = LamaranPekerjaan::with(['pencariKerja', 'lowongan'])
            ->where('id_lowongan', $idLowongan)
            ->get();

        $hasil = [];

        foreach ($list as $l) {
            $n = $this->hitungFinal($l->id_lamaran);

            $hasil[] = [
                'id_lamaran' => $l->id_lamaran,
                'nama' => $l->pencariKerja?->nama_lengkap ?? '-',
                'judul_lowongan' => $l->lowongan?->judul_lowongan ?? '-',
                'skill' => $n['skill'],
                'pengalaman' => $n['pengalaman'],
                'pendidikan' => $n['pendidikan'],
                'lokasi' => $n['lokasi'],
                'total_nilai' => $n['total_nilai'],
                'persentase' => $n['persentase'],
            ];
        }

        usort($hasil, fn($a, $b) => $b['total_nilai'] <=> $a['total_nilai']);

        foreach ($hasil as $i => $r) {
            $hasil[$i]['ranking'] = $i + 1;
            $hasil[$i]['tag'] = match (true) {
                $r['persentase'] >= 85 => '⭐ Sangat Cocok',
                $r['persentase'] >= 70 => '👍 Cocok',
                default => '❗ Kurang Cocok',
            };
        }

        return $hasil;
    }
}
