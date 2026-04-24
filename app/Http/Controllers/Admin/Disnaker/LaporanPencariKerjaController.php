<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use App\Models\ProfilPencariKerja;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PencariKerjaExport;

class LaporanPencariKerjaController extends Controller
{
    public function index()
    {
        $pencariKerja = ProfilPencariKerja::withTrashed()
            ->with([
                'kartuAk1',
                'lamaranPekerjaan.lowongan'
            ])
            ->orderByDesc('created_at')
            ->get();

        $counts = [
            'all' => ProfilPencariKerja::withTrashed()->count(),
            'aktif' => ProfilPencariKerja::whereNull('deleted_at')->count(),
            'punya_ak1' => ProfilPencariKerja::whereHas('kartuAk1')->count(),
            'punya_lamaran' => ProfilPencariKerja::whereHas('lamaranPekerjaan')->count(),
            'deleted' => ProfilPencariKerja::onlyTrashed()->count(),
        ];

        return view('admin.disnaker.laporan-pencari-kerja.index', [
            'pencariKerja' => $pencariKerja,
            'counts' => $counts,
            'title' => 'Laporan Pencari Kerja'
        ]);
    }

    // ================= EXPORT EXCEL =================
    public function exportExcel()
    {
        return Excel::download(
            new PencariKerjaExport,
            'laporan-pencari-kerja-' . date('Y-m-d') . '.xlsx'
        );
    }

    // ================= EXPORT PDF =================
    public function exportPdf()
    {
        $pencariKerja = ProfilPencariKerja::withTrashed()
            ->with([
                'kartuAk1',
                'lamaranPekerjaan.lowongan'
            ])
            ->orderByDesc('created_at')
            ->get();

        $pdf = PDF::loadView(
            'admin.disnaker.laporan-pencari-kerja.export.pdf',
            compact('pencariKerja')
        )->setPaper('A4', 'landscape');

        return $pdf->download('laporan-pencari-kerja-' . date('Y-m-d') . '.pdf');
    }

    public function print()
    {
        $data = ProfilPencariKerja::withTrashed()
            ->with([
                'kartuAk1',
                'lamaranPekerjaan.lowongan'
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.disnaker.laporan-pencari-kerja.export.pdf', [
            'pencariKerja' => $data
        ]);
    }
}
