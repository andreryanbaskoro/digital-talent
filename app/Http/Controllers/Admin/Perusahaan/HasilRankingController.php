<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\LowonganPekerjaan;
use App\Models\HasilPerhitungan;
use App\Models\DetailPerhitungan;
use App\Models\LamaranPekerjaan;
use App\Services\ProfileMatchingService;
use Illuminate\Support\Facades\DB;

class HasilRankingController extends Controller
{
    protected $service;

    public function __construct(ProfileMatchingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $title = 'Hasil Ranking Profile Matching';

        $lowongan = LowonganPekerjaan::with('profilPerusahaan')
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->jumlah_lamaran = LamaranPekerjaan::where('id_lowongan', $item->id_lowongan)->count();

                $item->sudah_dihitung = HasilPerhitungan::whereIn('id_lamaran', function ($q) use ($item) {
                    $q->select('id_lamaran')
                        ->from('lamaran_pekerjaan')
                        ->where('id_lowongan', $item->id_lowongan);
                })->exists();

                return $item;
            });

        return view('admin.perusahaan.hasil-ranking.index', compact('title', 'lowongan'));
    }

    public function show($idLowongan)
    {
        $lowongan = LowonganPekerjaan::with('profilPerusahaan')->findOrFail($idLowongan);
        $ranking = $this->service->rankingLowongan($idLowongan);

        $title = 'Hasil Ranking - ' . ($lowongan->judul_lowongan ?? '-');

        return view('admin.perusahaan.hasil-ranking.show', compact('title', 'lowongan', 'ranking'));
    }

    public function detail($idLowongan, $idLamaran)
    {
        $lowongan = LowonganPekerjaan::with([
            'profilPerusahaan',
            'subKriteriaLowongan.subKriteria',
        ])->findOrFail($idLowongan);

        $lamaran = LamaranPekerjaan::with([
            'pencariKerja.kartuAk1',
            'subKriteriaLamaran',
            'lowongan.profilPerusahaan',
            'lowongan.subKriteriaLowongan.subKriteria',
        ])
            ->where('id_lamaran', $idLamaran)
            ->where('id_lowongan', $idLowongan)
            ->firstOrFail();

        $hasil = HasilPerhitungan::where('id_lamaran', $idLamaran)->first();

        $detailPerhitungan = collect();

        if ($hasil) {
            $detailPerhitungan = DetailPerhitungan::where('id_hasil', $hasil->id_hasil)
                ->orderByRaw("FIELD(jenis_kriteria, 'skill_detail', 'skill', 'pengalaman', 'pendidikan', 'lokasi', 'final')")
                ->orderBy('created_at')
                ->get();
        }

        $live = $this->service->hitungFinal($idLamaran);

        $skillRows = collect($lowongan->subKriteriaLowongan ?? [])->map(function ($target) use ($lamaran) {
            $pelamar = collect($lamaran->subKriteriaLamaran ?? [])->firstWhere('id_sub_kriteria', $target->id_sub_kriteria);

            $nilaiTarget = (float) ($target->nilai_target ?? 0);
            $nilaiPelamar = (float) ($pelamar->nilai ?? 0);
            $selisih = $nilaiPelamar - $nilaiTarget;

            return [
                'nama_skill'    => $target->subKriteria->nama_sub_kriteria ?? '-',
                'nilai_pelamar' => $nilaiPelamar,
                'nilai_target'  => $nilaiTarget,
                'selisih'       => $selisih,
                'bobot_selisih' => $this->getBobotDefault($selisih),
            ];
        });

        $title = 'Detail Perhitungan - ' . ($lamaran->pencariKerja->nama_lengkap ?? '-');

        return view('admin.perusahaan.hasil-ranking.detail', compact(
            'title',
            'lowongan',
            'lamaran',
            'hasil',
            'detailPerhitungan',
            'live',
            'skillRows'
        ));
    }

    public function calculate($idLowongan)
    {
        $ranking = $this->service->rankingLowongan($idLowongan);

        if (empty($ranking)) {
            return back()->with('error', 'Tidak ada data lamaran untuk dihitung.');
        }

        DB::transaction(function () use ($ranking) {

            foreach ($ranking as $data) {

                $hasil = HasilPerhitungan::firstOrNew([
                    'id_lamaran' => $data['id_lamaran'],
                ]);

                if (!$hasil->exists) {
                    $hasil->id_hasil = HasilPerhitungan::generateId();
                }

                $cf = ($data['skill'] + $data['pengalaman']) / 2;
                $sf = ($data['pendidikan'] + $data['lokasi']) / 2;

                $hasil->fill([
                    'id_lamaran' => $data['id_lamaran'],
                    'nilai_faktor_inti' => round($cf, 2),
                    'nilai_faktor_pendukung' => round($sf, 2),
                    'nilai_total' => $data['total_nilai'] ?? 0,
                    'peringkat' => $data['ranking'] ?? 0,
                    'rekomendasi' => $this->getRekomendasi($data['persentase'] ?? 0),
                ]);

                $hasil->save();

                DetailPerhitungan::where('id_hasil', $hasil->id_hasil)->forceDelete();

                foreach ($data['skill_detail'] ?? [] as $detail) {
                    DetailPerhitungan::create([
                        'id_hasil' => $hasil->id_hasil,
                        'nama_kriteria'  => $detail['nama_kriteria'] ?? 'Skill',
                        'nilai_pelamar'  => $detail['nilai_pelamar'] ?? 0,
                        'nilai_target'   => $detail['nilai_target'] ?? 0,
                        'selisih'        => $detail['selisih'] ?? 0,
                        'bobot_selisih'  => $detail['bobot_selisih'] ?? 0,
                        'jenis_kriteria' => 'skill_detail',
                    ]);
                }

                $this->insertKriteriaUtama($hasil->id_hasil, $data);
            }
        });

        return redirect()
            ->route('perusahaan.ranking.show', $idLowongan)
            ->with('success', 'Ranking berhasil dihitung & diperbarui.');
    }

    private function insertKriteriaUtama($idHasil, $data)
    {
        $kriteria = [
            'skill' => $data['skill'] ?? 0,
            'pengalaman' => $data['pengalaman'] ?? 0,
            'pendidikan' => $data['pendidikan'] ?? 0,
            'lokasi' => $data['lokasi'] ?? 0,
        ];

        foreach ($kriteria as $nama => $nilai) {
            $nilaiTarget = 5;
            $nilaiPelamar = (int) round($nilai);
            $selisih = $nilaiPelamar - $nilaiTarget;
            $bobot = $this->getBobotDefault($selisih);

            DetailPerhitungan::create([
                'id_hasil' => $idHasil,
                'nama_kriteria'  => strtoupper($nama),
                'nilai_pelamar'  => $nilaiPelamar,
                'nilai_target'   => $nilaiTarget,
                'selisih'        => $selisih,
                'bobot_selisih'  => $bobot,
                'jenis_kriteria' => $nama,
            ]);
        }
    }

    private function getRekomendasi($persentase)
    {
        if ($persentase >= 85) {
            return '⭐ Sangat Cocok';
        }

        if ($persentase >= 70) {
            return '👍 Cocok';
        }

        return '❗ Kurang Cocok';
    }

    private function getBobotDefault($selisih)
    {
        return match ((int) $selisih) {
            0 => 5,
            1 => 4.5,
            -1 => 4,
            2 => 3.5,
            -2 => 3,
            3 => 2.5,
            -3, 4, -4 => 1,
            default => 1,
        };
    }
}
