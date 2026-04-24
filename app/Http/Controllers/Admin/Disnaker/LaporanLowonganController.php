<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use App\Models\LowonganPekerjaan;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LowonganExport;

class LaporanLowonganController extends Controller
{
    public function index()
    {
        $lowongan = LowonganPekerjaan::withTrashed()
            ->with('profilPerusahaan')
            ->orderByDesc('created_at')
            ->get();

        $counts = [
            'all' => LowonganPekerjaan::withTrashed()->count(),
            'pending' => LowonganPekerjaan::whereNull('deleted_at')->where('status', 'pending')->count(),
            'disetujui' => LowonganPekerjaan::whereNull('deleted_at')->where('status', 'disetujui')->count(),
            'ditolak' => LowonganPekerjaan::whereNull('deleted_at')->where('status', 'ditolak')->count(),
            'deleted' => LowonganPekerjaan::onlyTrashed()->count(),
        ];

        return view('admin.disnaker.laporan-lowongan.index', [
            'lowongan' => $lowongan,
            'counts' => $counts,
            'title' => 'Laporan Lowongan Pekerjaan'
        ]);
    }

    // ================= EXPORT EXCEL =================
    public function exportExcel()
    {
        return Excel::download(
            new LowonganExport,
            'laporan-lowongan-' . date('Y-m-d') . '.xlsx'
        );
    }

    // ================= EXPORT PDF =================
    public function exportPdf()
    {
        $lowongan = LowonganPekerjaan::withTrashed()
            ->with('profilPerusahaan')
            ->orderByDesc('created_at')
            ->get();

        $pdf = PDF::loadView(
            'admin.disnaker.laporan-lowongan.export.pdf',
            compact('lowongan')
        )->setPaper('A4', 'landscape');

        return $pdf->download('laporan-lowongan-' . date('Y-m-d') . '.pdf');
    }

    public function print()
    {
        $data = LowonganPekerjaan::withTrashed()
            ->with('profilPerusahaan')
            ->get();

        return view('admin.disnaker.laporan-lowongan.export.pdf', [
            'lowongan' => $data
        ]);
    }
}
