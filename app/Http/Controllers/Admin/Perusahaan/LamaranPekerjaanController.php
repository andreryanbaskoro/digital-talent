<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use Illuminate\Support\Facades\Auth;

class LamaranPekerjaanController extends Controller
{
    // ================= PERUSAHAAN ID =================
    private function perusahaanId()
    {
        $user = Auth::user();

        return data_get($user, 'id_perusahaan')
            ?? data_get($user, 'profilPerusahaan.id_perusahaan')
            ?? data_get($user, 'profil_perusahaan.id_perusahaan')
            ?? data_get($user, 'perusahaan.id_perusahaan');
    }

    // ================= QUERY UTAMA =================
    private function baseQuery()
    {
        $idPerusahaan = $this->perusahaanId();

        abort_unless($idPerusahaan, 403, 'Data perusahaan tidak ditemukan.');

        return LamaranPekerjaan::withTrashed()
            ->with([
                'lowongan.profilPerusahaan',
                'pencariKerja',
                'dokumen',
                'subKriteriaLamaran.subKriteria',
                'hasilPerhitungan',
            ])
            ->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPerusahaan) {
                $q->where('id_perusahaan', $idPerusahaan);
            });
    }

    // ================= VALIDASI OWNERSHIP =================
    private function abortIfNotOwned(LamaranPekerjaan $lamaran)
    {
        $idPerusahaan = $this->perusahaanId();

        abort_unless(
            $lamaran->lowongan &&
                data_get($lamaran, 'lowongan.profilPerusahaan.id_perusahaan') == $idPerusahaan,
            403,
            'Tidak berhak mengakses data ini.'
        );
    }

    // ================= INDEX =================
    public function index()
    {
        $lamaran = $this->baseQuery()
            ->orderByRaw('CASE WHEN deleted_at IS NULL THEN 0 ELSE 1 END')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.perusahaan.lamaran-pekerjaan.index', [
            'title' => 'Data Lamaran Pencari Kerja',
            'lamaran' => $lamaran,
        ]);
    }

    // ================= SHOW DETAIL =================
    public function show($id)
    {
        $lamaran = $this->baseQuery()
            ->where('id_lamaran', $id)
            ->firstOrFail();

        $this->abortIfNotOwned($lamaran);

        return response()->json([
            'id_lamaran' => $lamaran->id_lamaran,
            'tanggal_lamar' => optional($lamaran->tanggal_lamar)->format('d-m-Y'),
            'status_lamaran' => $lamaran->status_lamaran,
            'catatan_perusahaan' => $lamaran->catatan_perusahaan,
            'deleted_at' => optional($lamaran->deleted_at)->format('d-m-Y H:i'),

            // PELAMAR
            'pencari_kerja' => [
                'nama_lengkap' => $lamaran->pencariKerja->nama_lengkap ?? '-',
                'nik' => $lamaran->pencariKerja->nik ?? '-',
                'email' => $lamaran->pencariKerja->email ?? '-',
                'nomor_hp' => $lamaran->pencariKerja->nomor_hp ?? '-',
                'alamat' => $lamaran->pencariKerja->alamat ?? '-',
                'tempat_lahir' => $lamaran->pencariKerja->tempat_lahir ?? '-',
                'tanggal_lahir' => optional($lamaran->pencariKerja->tanggal_lahir)->format('d-m-Y'),
                'jenis_kelamin' => $lamaran->pencariKerja->jenis_kelamin ?? '-',
                'agama' => $lamaran->pencariKerja->agama ?? '-',
                'status_perkawinan' => $lamaran->pencariKerja->status_perkawinan ?? '-',
                'foto' => $lamaran->pencariKerja->foto
                    ? asset('storage/' . $lamaran->pencariKerja->foto)
                    : null,
            ],

            // LOWONGAN
            'lowongan' => [
                'judul_lowongan' => $lamaran->lowongan->judul_lowongan ?? '-',
                'lokasi' => $lamaran->lowongan->lokasi ?? '-',
                'jenis_pekerjaan' => $lamaran->lowongan->jenis_pekerjaan ?? '-',
                'sistem_kerja' => $lamaran->lowongan->sistem_kerja ?? '-',
                'kuota' => $lamaran->lowongan->kuota ?? '-',
                'gaji_minimum' => $lamaran->lowongan->gaji_minimum ?? null,
                'gaji_maksimum' => $lamaran->lowongan->gaji_maksimum ?? null,
            ],

            // DOKUMEN
            'dokumen' => $lamaran->dokumen->map(fn($d) => [
                'jenis' => $d->jenis_dokumen,
                'url' => $d->lokasi_file ? asset('storage/' . $d->lokasi_file) : null,
            ])->values(),

            // SKILL
            'sub_kriteria' => $lamaran->subKriteriaLamaran
                ->filter(fn($s) => $s->subKriteria)
                ->map(fn($s) => [
                    'nama' => $s->subKriteria->nama_sub_kriteria ?? '-',
                    'nilai' => (int) $s->nilai,
                ])
                ->values(),

            // HASIL
            'hasil' => $lamaran->hasilPerhitungan ? [
                'nilai_akhir' => (float) $lamaran->hasilPerhitungan->nilai_akhir,
                'ranking' => (int) $lamaran->hasilPerhitungan->ranking,
                'rekomendasi' => $lamaran->hasilPerhitungan->status_rekomendasi,
            ] : null,
        ]);
    }

    // ================= SOFT DELETE =================
    public function destroy($id)
    {
        $lamaran = $this->baseQuery()->where('id_lamaran', $id)->firstOrFail();
        $this->abortIfNotOwned($lamaran);

        abort_if($lamaran->trashed(), 400, 'Sudah dihapus.');

        $lamaran->delete();

        return response()->json([
            'message' => 'Lamaran berhasil dihapus.'
        ]);
    }

    // ================= RESTORE =================
    public function restore($id)
    {
        $lamaran = $this->baseQuery()->onlyTrashed()
            ->where('id_lamaran', $id)
            ->firstOrFail();

        $this->abortIfNotOwned($lamaran);

        $lamaran->restore();

        return response()->json([
            'message' => 'Lamaran berhasil dipulihkan.'
        ]);
    }

    // ================= FORCE DELETE =================
    public function forceDelete($id)
    {
        $lamaran = $this->baseQuery()->onlyTrashed()
            ->where('id_lamaran', $id)
            ->firstOrFail();

        $this->abortIfNotOwned($lamaran);

        $lamaran->forceDelete();

        return response()->json([
            'message' => 'Lamaran berhasil dihapus permanen.'
        ]);
    }
}
