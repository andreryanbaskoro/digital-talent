<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenempatanExport;

class LaporanPenempatanController extends Controller
{
    public function index()
    {
        // DATA TABLE (khusus penempatan)
        $penempatan = LamaranPekerjaan::withTrashed()
            ->with([
                'pencariKerja',
                'lowongan.profilPerusahaan'
            ])
            ->where('status_lamaran', 'diterima')
            ->orderByDesc('tanggal_lamar')
            ->get();

        // STATISTIK GLOBAL
        $counts = [
            'all' => LamaranPekerjaan::withTrashed()->count(),

            'diterima' => LamaranPekerjaan::where('status_lamaran', 'diterima')->count(),

            'diproses' => LamaranPekerjaan::where('status_lamaran', 'diproses')->count(),

            'ditolak' => LamaranPekerjaan::where('status_lamaran', 'ditolak')->count(),

            'deleted' => LamaranPekerjaan::onlyTrashed()->count(),
        ];

        return view('admin.disnaker.laporan-penempatan.index', [
            'penempatan' => $penempatan,
            'counts' => $counts,
            'title' => 'Laporan Penempatan Tenaga Kerja'
        ]);
    }

    // ================= EXPORT EXCEL =================
    public function exportExcel()
    {
        return Excel::download(
            new PenempatanExport,
            'laporan-penempatan-' . date('Y-m-d') . '.xlsx'
        );
    }

    // ================= EXPORT PDF =================
    public function exportPdf()
    {
        $penempatan = LamaranPekerjaan::withTrashed()
            ->with([
                'pencariKerja',
                'lowongan.profilPerusahaan'
            ])
            ->where('status_lamaran', 'diterima')
            ->orderByDesc('tanggal_lamar')
            ->get();

        $pdf = PDF::loadView(
            'admin.disnaker.laporan-penempatan.export.pdf',
            compact('penempatan')
        )->setPaper('A4', 'landscape');

        return $pdf->download('laporan-penempatan-' . date('Y-m-d') . '.pdf');
    }

    public function print()
    {
        $data = LamaranPekerjaan::withTrashed()
            ->with([
                'pencariKerja',
                'lowongan.profilPerusahaan'
            ])
            ->where('status_lamaran', 'diterima')
            ->orderByDesc('tanggal_lamar')
            ->get();

        return view('admin.disnaker.laporan-penempatan.export.pdf', [
            'penempatan' => $data
        ]);
    }
}
