<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\LowonganPekerjaan;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerusahaanLowonganExport;

class LaporanLowonganController extends Controller
{
    private function getPerusahaanId()
    {
        return auth()->user()->profilPerusahaan->id_perusahaan ?? null;
    }

    private function baseQuery()
    {
        $idPerusahaan = $this->getPerusahaanId();

        if (!$idPerusahaan) {
            abort(403, 'Akun belum terhubung dengan data perusahaan');
        }

        return LowonganPekerjaan::withTrashed()
            ->with('profilPerusahaan')
            ->where('id_perusahaan', $idPerusahaan);
    }

    public function index()
    {
        $baseQuery = $this->baseQuery();

        $lowongan = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->get();

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)
                ->whereNull('deleted_at')
                ->where('status', 'pending')
                ->count(),

            'disetujui' => (clone $baseQuery)
                ->whereNull('deleted_at')
                ->where('status', 'disetujui')
                ->count(),

            'ditolak' => (clone $baseQuery)
                ->whereNull('deleted_at')
                ->where('status', 'ditolak')
                ->count(),

            'deleted' => (clone $baseQuery)
                ->onlyTrashed()
                ->count(),
        ];

        return view('admin.perusahaan.laporan-lowongan.index', [
            'lowongan' => $lowongan,
            'counts' => $counts,
            'title' => 'Laporan Lowongan Pekerjaan'
        ]);
    }

    // ================= EXPORT EXCEL =================
    public function exportExcel()
    {
        $idPerusahaan = $this->getPerusahaanId();

        if (!$idPerusahaan) {
            abort(403, 'Akun belum terhubung dengan perusahaan');
        }

        return Excel::download(
            new PerusahaanLowonganExport($idPerusahaan),
            'laporan-lowongan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ================= EXPORT PDF =================
    public function exportPdf()
    {
        $lowongan = $this->baseQuery()
            ->orderByDesc('created_at')
            ->get();

        $pdf = PDF::loadView(
            'admin.perusahaan.laporan-lowongan.export.pdf',
            compact('lowongan')
        )->setPaper('A4', 'landscape');

        return $pdf->download('laporan-lowongan-' . now()->format('Y-m-d') . '.pdf');
    }

    // ================= PRINT VIEW =================
    public function print()
    {
        $lowongan = $this->baseQuery()
            ->orderByDesc('created_at')
            ->get();

        return view('admin.perusahaan.laporan-lowongan.export.pdf', [
            'lowongan' => $lowongan
        ]);
    }
}
