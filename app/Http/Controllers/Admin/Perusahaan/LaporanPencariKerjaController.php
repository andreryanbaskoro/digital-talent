<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\ProfilPencariKerja;
use App\Models\ProfilPerusahaan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerusahaanPencariKerjaExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPencariKerjaController extends Controller
{
    /**
     * =========================
     * AMBIL DATA PERUSAHAAN
     * =========================
     */
    private function perusahaan()
    {
        $user = auth()->user();

        if (!$user || !$user->profilPerusahaan) {
            abort(403, 'Akun belum terhubung dengan perusahaan');
        }

        return $user->profilPerusahaan;
    }

    /**
     * =========================
     * BASE QUERY
     * =========================
     */
    private function baseQuery()
    {
        $perusahaan = $this->perusahaan();

        return ProfilPencariKerja::withTrashed()
            ->with([
                'kartuAk1',
                'lamaranPekerjaan.lowongan',
            ])
            ->whereHas('lamaranPekerjaan', function ($q) use ($perusahaan) {

                $q->whereNull('deleted_at')
                    ->whereHas('lowongan', function ($lowongan) use ($perusahaan) {

                        $lowongan->where(
                            'id_perusahaan',
                            $perusahaan->id_perusahaan
                        );
                    });
            });
    }

    /**
     * =========================
     * INDEX
     * =========================
     */
    public function index()
    {
        $baseQuery = $this->baseQuery();

        $pencariKerja = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->get();

        $counts = [
            'all' => (clone $baseQuery)->count(),

            'aktif' => (clone $baseQuery)
                ->whereNull('deleted_at')
                ->count(),

            'deleted' => (clone $baseQuery)
                ->onlyTrashed()
                ->count(),
        ];

        return view(
            'admin.perusahaan.laporan-pencari-kerja.index',
            [
                'title' => 'Laporan Pencari Kerja',
                'pencariKerja' => $pencariKerja,
                'counts' => $counts,
                'perusahaan' => $this->perusahaan(),
            ]
        );
    }

    /**
     * =========================
     * EXPORT EXCEL
     * =========================
     */
    public function exportExcel()
    {
        $perusahaan = $this->perusahaan();

        return Excel::download(
            new PerusahaanPencariKerjaExport(
                $perusahaan->id_perusahaan
            ),
            'laporan-pencari-kerja-' .
                now()->format('Y-m-d') .
                '.xlsx'
        );
    }

    /**
     * =========================
     * EXPORT PDF
     * =========================
     */
    public function exportPdf()
    {
        $perusahaan = $this->perusahaan();

        $pencariKerja = $this->baseQuery()
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView(
            'admin.perusahaan.laporan-pencari-kerja.export.pdf',
            [
                'pencariKerja' => $pencariKerja,
                'perusahaan' => $perusahaan,
            ]
        )->setPaper('A4', 'landscape');

        return $pdf->download(
            'laporan-pencari-kerja-' .
                now()->format('Y-m-d') .
                '.pdf'
        );
    }

    /**
     * =========================
     * PRINT
     * =========================
     */
    public function print()
    {
        $perusahaan = $this->perusahaan();

        $pencariKerja = $this->baseQuery()
            ->orderByDesc('created_at')
            ->get();

        return view(
            'admin.perusahaan.laporan-pencari-kerja.export.pdf',
            [
                'pencariKerja' => $pencariKerja,
                'perusahaan' => $perusahaan,
            ]
        );
    }
}
