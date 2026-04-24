<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KeputusanSeleksiController extends Controller
{
    public function index()
    {
        $lowongan = LowonganPekerjaan::with('profilPerusahaan')
            ->withCount([
                'lamaran',
                'lamaran as jumlah_dihitung' => function ($q) {
                    $q->whereHas('hasilPerhitungan');
                },
                'lamaran as jumlah_diterima' => function ($q) {
                    $q->where('status_lamaran', 'diterima');
                },
                'lamaran as jumlah_ditolak' => function ($q) {
                    $q->where('status_lamaran', 'ditolak');
                },
                'lamaran as jumlah_diproses' => function ($q) {
                    $q->where('status_lamaran', 'diproses');
                },
            ])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item) {
                $item->sudah_dihitung = ($item->jumlah_dihitung ?? 0) > 0;
                return $item;
            });

        return view('admin.perusahaan.keputusan-seleksi.index', [
            'title' => 'Keputusan Seleksi',
            'lowongan' => $lowongan,
        ]);
    }

    public function show($idLowongan)
    {
        $lowongan = LowonganPekerjaan::with('profilPerusahaan')
            ->findOrFail($idLowongan);

        $lamaran = LamaranPekerjaan::with([
            'pencariKerja',
            'lowongan.profilPerusahaan',
            'dokumen',
            'hasilPerhitungan',
        ])
            ->where('id_lowongan', $idLowongan)
            ->get()
            ->sortBy(function ($item) {
                $rank = optional($item->hasilPerhitungan)->peringkat;
                if (!is_null($rank)) {
                    return (int) $rank;
                }

                $nilai = optional($item->hasilPerhitungan)->nilai_akhir;
                return $nilai !== null ? - ((float) $nilai) : PHP_INT_MAX;
            })
            ->values();

        $summary = [
            'total' => $lamaran->count(),
            'diproses' => $lamaran->where('status_lamaran', 'diproses')->count(),
            'diterima' => $lamaran->where('status_lamaran', 'diterima')->count(),
            'ditolak' => $lamaran->where('status_lamaran', 'ditolak')->count(),
            'belum_ada_hasil' => $lamaran->filter(function ($item) {
                return !$item->hasilPerhitungan;
            })->count(),
        ];

        return view('admin.perusahaan.keputusan-seleksi.show', [
            'title' => 'Keputusan Seleksi',
            'lowongan' => $lowongan,
            'lamaran' => $lamaran,
            'summary' => $summary,
        ]);
    }

    public function terima(Request $request, $idLamaran)
    {
        $request->validate([
            'catatan_perusahaan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan_perusahaan.required' => 'Catatan keputusan wajib diisi.',
        ]);

        $lamaran = LamaranPekerjaan::with(['pencariKerja', 'lowongan'])->findOrFail($idLamaran);

        DB::transaction(function () use ($lamaran, $request) {
            $catatan = $request->catatan_perusahaan;

            $lamaran->status_lamaran = 'diterima';
            $lamaran->catatan_perusahaan = $catatan;
            $lamaran->save();

            Notifikasi::create([
                'id_pengguna' => $lamaran->pencariKerja->id_pengguna,
                'judul' => 'Lamaran Diterima',
                'isi_pesan' => 'Selamat! Lamaran Anda pada lowongan "'
                    . ($lamaran->lowongan->judul_lowongan ?? '-') . '" diterima. '
                    . 'Catatan: ' . $catatan,
                'tipe' => 'lamaran',
                'status_baca' => false,
            ]);
        });

        return back()->with('success', 'Lamaran berhasil diterima.');
    }

    public function tolak(Request $request, $idLamaran)
    {
        $request->validate([
            'catatan_perusahaan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan_perusahaan.required' => 'Catatan keputusan wajib diisi.',
        ]);

        $lamaran = LamaranPekerjaan::with(['pencariKerja', 'lowongan'])->findOrFail($idLamaran);

        DB::transaction(function () use ($lamaran, $request) {
            $catatan = $request->catatan_perusahaan;

            $lamaran->status_lamaran = 'ditolak';
            $lamaran->catatan_perusahaan = $catatan;
            $lamaran->save();

            Notifikasi::create([
                'id_pengguna' => $lamaran->pencariKerja->id_pengguna,
                'judul' => 'Lamaran Ditolak',
                'isi_pesan' => 'Maaf, lamaran Anda pada lowongan "'
                    . ($lamaran->lowongan->judul_lowongan ?? '-') . '" belum berhasil. '
                    . 'Catatan: ' . $catatan,
                'tipe' => 'lamaran',
                'status_baca' => false,
            ]);
        });

        return back()->with('success', 'Lamaran berhasil ditolak.');
    }
}
