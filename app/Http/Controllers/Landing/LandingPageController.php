<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\LowonganPekerjaan;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // QUERY UTAMA LOWONGAN
        // =========================
        $query = LowonganPekerjaan::with('profilPerusahaan')
            ->where('status', 'disetujui')
            ->whereNull('deleted_at');

        // 🔍 Keyword search
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

        // 📍 Lokasi filter
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        // 💼 Jenis pekerjaan filter
        if ($request->filled('jenis_pekerjaan')) {
            $query->where('jenis_pekerjaan', $request->jenis_pekerjaan);
        }

        // 🏠 Sistem kerja filter
        if ($request->filled('sistem_kerja')) {
            $query->where('sistem_kerja', $request->sistem_kerja);
        }

        // =========================
        // DATA LOWONGAN
        // =========================
        $lowongan = $query
            ->latest()
            ->paginate(9)
            ->withQueryString();

        // =========================
        // DROPDOWN DATA (DARI DB)
        // =========================

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

        return view('landing.index', compact(
            'lowongan',
            'daftarLokasi',
            'daftarJenis',
            'daftarSistemKerja',
            'daftarKeyword'
        ));
    }
}
