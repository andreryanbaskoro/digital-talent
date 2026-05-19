<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

// nanti bikin class export ini
use App\Exports\LaporanPelamarPerusahaanExport;

class LaporanPelamarPerusahaanController extends Controller
{
    /**
     * Ambil query dasar biar dipakai di index / pdf / print / excel
     */
    protected function baseQuery(Request $request)
    {
        return LamaranPekerjaan::withTrashed()
            ->with([
                'lowongan' => function ($q) {
                    $q->withTrashed()
                        ->with([
                            'profilPerusahaan' => function ($qq) {
                                $qq->withTrashed();
                            }
                        ]);
                },
                'pencariKerja' => function ($q) {
                    $q->withTrashed();
                }
            ])
            ->when($request->filled('nama_perusahaan'), function ($query) use ($request) {
                $query->whereHas('lowongan.profilPerusahaan', function ($q) use ($request) {
                    $q->where('nama_perusahaan', 'like', '%' . $request->nama_perusahaan . '%');
                });
            })
            ->when($request->filled('jenis_pekerjaan'), function ($query) use ($request) {
                $query->whereHas('lowongan', function ($q) use ($request) {
                    $q->where('jenis_pekerjaan', $request->jenis_pekerjaan);
                });
            })
            ->when($request->filled('nama_pekerjaan'), function ($query) use ($request) {
                $query->whereHas('lowongan', function ($q) use ($request) {
                    $q->where('judul_lowongan', 'like', '%' . $request->nama_pekerjaan . '%');
                });
            })
            ->when($request->filled('tanggal_posting'), function ($query) use ($request) {
                $query->whereHas('lowongan', function ($q) use ($request) {
                    $q->whereDate('created_at', $request->tanggal_posting);
                });
            });
    }

    public function index(Request $request)
    {
        $lamaran = $this->baseQuery($request)
            ->orderByDesc('tanggal_lamar')
            ->get();

        $perusahaan = ProfilPerusahaan::withTrashed()
            ->orderBy('nama_perusahaan')
            ->get(['id_perusahaan', 'nama_perusahaan']);

        $jenisPekerjaan = LowonganPekerjaan::withTrashed()
            ->select('jenis_pekerjaan')
            ->distinct()
            ->orderBy('jenis_pekerjaan')
            ->get();

        $namaPekerjaan = LowonganPekerjaan::withTrashed()
            ->select('judul_lowongan')
            ->distinct()
            ->orderBy('judul_lowongan')
            ->get();

        return view('admin.disnaker.laporan-pelamar-perusahaan.index', [
            'title' => 'Laporan Data Pelamar Perusahaan',
            'lamaran' => $lamaran,
            'perusahaan' => $perusahaan,
            'jenisPekerjaan' => $jenisPekerjaan,
            'namaPekerjaan' => $namaPekerjaan,
            'request' => $request,
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LaporanPelamarPerusahaanExport($request->all()),
            'laporan-data-pelamar-perusahaan-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $lamaran = $this->baseQuery($request)
            ->orderByDesc('tanggal_lamar')
            ->get();

        $pdf = PDF::loadView(
            'admin.disnaker.laporan-pelamar-perusahaan.export.pdf',
            compact('lamaran')
        )->setPaper('A4', 'landscape');

        return $pdf->download('laporan-data-pelamar-perusahaan-' . date('Y-m-d') . '.pdf');
    }

    public function print(Request $request)
    {
        $lamaran = $this->baseQuery($request)
            ->orderByDesc('tanggal_lamar')
            ->get();

        return view('admin.disnaker.laporan-pelamar-perusahaan.export.pdf', compact('lamaran'));
    }
}
