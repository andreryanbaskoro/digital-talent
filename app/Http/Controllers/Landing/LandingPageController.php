<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\LowonganPekerjaan;
use App\Models\ProfilPerusahaan; // tambah ini
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $query = LowonganPekerjaan::with('profilPerusahaan')
            ->where('status', 'disetujui')
            ->whereNull('deleted_at');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('judul_lowongan', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%")
                    ->orWhereHas('profilPerusahaan', function ($q2) use ($keyword) {
                        $q2->where('nama_perusahaan', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        if ($request->filled('jenis_pekerjaan')) {
            $query->where('jenis_pekerjaan', $request->jenis_pekerjaan);
        }

        if ($request->filled('sistem_kerja')) {
            $query->where('sistem_kerja', $request->sistem_kerja);
        }

        $lowongan = $query->latest()->paginate(9)->withQueryString();

        $daftarLokasi = LowonganPekerjaan::where('status', 'disetujui')
            ->whereNotNull('lokasi')
            ->distinct()
            ->orderBy('lokasi')
            ->pluck('lokasi');

        $daftarJenis = LowonganPekerjaan::where('status', 'disetujui')
            ->whereNotNull('jenis_pekerjaan')
            ->distinct()
            ->orderBy('jenis_pekerjaan')
            ->pluck('jenis_pekerjaan');

        $daftarSistemKerja = LowonganPekerjaan::where('status', 'disetujui')
            ->whereNotNull('sistem_kerja')
            ->distinct()
            ->orderBy('sistem_kerja')
            ->pluck('sistem_kerja');

        $daftarKeyword = LowonganPekerjaan::where('status', 'disetujui')
            ->whereNotNull('judul_lowongan')
            ->distinct()
            ->orderBy('judul_lowongan')
            ->pluck('judul_lowongan');

        $totalLowongan = LowonganPekerjaan::where('status', 'disetujui')
            ->whereNull('deleted_at')
            ->count();

        $totalPerusahaan = ProfilPerusahaan::whereNull('deleted_at')->count();

        $totalHariIni = LowonganPekerjaan::where('status', 'disetujui')
            ->whereDate('created_at', today())
            ->count();

        return view('landing.index', compact(
            'lowongan',
            'daftarLokasi',
            'daftarJenis',
            'daftarSistemKerja',
            'daftarKeyword',
            'totalLowongan',
            'totalPerusahaan',
            'totalHariIni'
        ));
    }

    public function detail($id_lowongan)
    {
        $lowongan = LowonganPekerjaan::with([
            'profilPerusahaan',
            'kriteria',
            'subKriteriaLowongan.subKriteria'
        ])
            ->where('id_lowongan', $id_lowongan)
            ->where('status', 'disetujui')
            ->whereNull('deleted_at')
            ->firstOrFail();

        // ✅ TAMBAHKAN INI
        $totalLowongan = LowonganPekerjaan::where('status', 'disetujui')
            ->whereNull('deleted_at')
            ->count();

        $totalPerusahaan = \App\Models\ProfilPerusahaan::whereNull('deleted_at')->count();

        $totalHariIni = LowonganPekerjaan::where('status', 'disetujui')
            ->whereDate('created_at', today())
            ->count();

        return view('landing.detail-lowongan', compact(
            'lowongan',
            'totalLowongan',
            'totalPerusahaan',
            'totalHariIni'
        ));
    }
}
