<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use App\Models\ProfilPencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPencariKerjaExport;

class LaporanPencariKerjaController extends Controller
{
    /**
     * Mode valid:
     * - disnaker
     * - perusahaan
     */
    protected function normalizeMode($mode = null)
    {
        return in_array($mode, ['disnaker', 'perusahaan']) ? $mode : 'disnaker';
    }

    /**
     * Ambil id_pengguna login.
     */
    protected function currentIdPengguna()
    {
        $user = Auth::user();

        return data_get($user, 'id_pengguna')
            ?? data_get($user, 'id')
            ?? null;
    }

    /**
     * Query dasar laporan pencari kerja.
     *
     * Mode disnaker : semua pencari kerja yang pernah melamar (atau semua profil).
     * Mode perusahaan: hanya pencari kerja yang melamar ke lowongan perusahaan tsb.
     */
    protected function baseQuery(Request $request, string $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        // ===================== MODE PERUSAHAAN =====================
        // Ambil via LamaranPekerjaan → filter ke perusahaan login → distinct pencariKerja
        if ($mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();

            abort_if(!$idPengguna, 403, 'Akun perusahaan tidak valid.');

            // Ambil id_pencari_kerja yang melamar ke lowongan perusahaan ini
            $idPencariKerjaList = LamaranPekerjaan::withTrashed()
                ->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                    $q->withTrashed()->where('id_pengguna', $idPengguna);
                })
                ->when($request->filled('nama_pekerjaan'), function ($q) use ($request) {
                    $q->whereHas('lowongan', function ($qq) use ($request) {
                        $qq->withTrashed()->where('judul_lowongan', 'like', '%' . $request->nama_pekerjaan . '%');
                    });
                })
                ->when($request->filled('jenis_pekerjaan'), function ($q) use ($request) {
                    $q->whereHas('lowongan', function ($qq) use ($request) {
                        $qq->withTrashed()->where('jenis_pekerjaan', $request->jenis_pekerjaan);
                    });
                })
                ->when($request->filled('tanggal_pendaftaran'), function ($q) use ($request) {
                    $q->whereDate('tanggal_lamar', $request->tanggal_pendaftaran);
                })
                ->pluck('id_pencari_kerja')
                ->unique();

            $query = ProfilPencariKerja::withTrashed()
                ->with(['pengguna', 'kartuAk1.keterampilan'])
                ->whereIn('id_pencari_kerja', $idPencariKerjaList);

            // ===================== MODE DISNAKER =====================
        } else {
            $query = ProfilPencariKerja::withTrashed()
                ->with(['pengguna', 'kartuAk1.keterampilan']);

            // Jika ada filter nama_pekerjaan / jenis_pekerjaan / tanggal_pendaftaran (lamar)
            // maka scope via lamaran
            $hasLamaranFilter = $request->filled('nama_pekerjaan')
                || $request->filled('jenis_pekerjaan')
                || $request->filled('tanggal_pendaftaran');

            if ($hasLamaranFilter) {
                $query->whereHas('lamaranPekerjaan', function ($q) use ($request) {
                    $q->withTrashed()
                        ->when($request->filled('nama_pekerjaan'), function ($qq) use ($request) {
                            $qq->whereHas('lowongan', function ($qqq) use ($request) {
                                $qqq->withTrashed()->where('judul_lowongan', 'like', '%' . $request->nama_pekerjaan . '%');
                            });
                        })
                        ->when($request->filled('jenis_pekerjaan'), function ($qq) use ($request) {
                            $qq->whereHas('lowongan', function ($qqq) use ($request) {
                                $qqq->withTrashed()->where('jenis_pekerjaan', $request->jenis_pekerjaan);
                            });
                        })
                        ->when($request->filled('tanggal_pendaftaran'), function ($qq) use ($request) {
                            $qq->whereDate('tanggal_lamar', $request->tanggal_pendaftaran);
                        });
                });
            }
        }

        // ===================== FILTER BERSAMA =====================
        $query->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
            $q->where('jenis_kelamin', $request->jenis_kelamin);
        });

        return $query;
    }

    /**
     * Data dropdown filter
     */
    protected function filterData(string $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $jenisPekerjaan = LowonganPekerjaan::withTrashed()
            ->select('jenis_pekerjaan')
            ->whereNotNull('jenis_pekerjaan')
            ->distinct()
            ->orderBy('jenis_pekerjaan')
            ->get();

        $namaPekerjaan = LowonganPekerjaan::withTrashed()
            ->select('judul_lowongan')
            ->whereNotNull('judul_lowongan')
            ->distinct()
            ->orderBy('judul_lowongan')
            ->get();

        return compact('jenisPekerjaan', 'namaPekerjaan');
    }

    // =========================================================
    // INDEX
    // =========================================================
    public function index(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)
            ->orderByDesc('created_at')
            ->get();

        $filters = $this->filterData($mode);

        return view('laporan.pencari-kerja.index', [
            'title' => $mode === 'disnaker'
                ? 'Laporan Data Pencari Kerja - DISNAKER'
                : 'Laporan Data Pencari Kerja - PERUSAHAAN',
            'mode'          => $mode,
            'data'          => $data,
            'request'       => $request,
            'jenisPekerjaan' => $filters['jenisPekerjaan'],
            'namaPekerjaan'  => $filters['namaPekerjaan'],
        ]);
    }

    // =========================================================
    // EXPORT EXCEL
    // =========================================================
    public function exportExcel(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        return Excel::download(
            new LaporanPencariKerjaExport($request->all(), $mode),
            'laporan-pencari-kerja-' . $mode . '-' . date('Y-m-d') . '.xlsx'
        );
    }

    // =========================================================
    // EXPORT PDF
    // =========================================================
    public function exportPdf(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)
            ->orderByDesc('created_at')
            ->get();

        $pdf = PDF::loadView('laporan.pencari-kerja.export.pdf', [
            'mode'  => $mode,
            'data'  => $data,
            'title' => $mode === 'disnaker'
                ? 'Laporan Data Pencari Kerja - DISNAKER'
                : 'Laporan Data Pencari Kerja - PERUSAHAAN',
        ])->setPaper('A4', 'landscape');

        return $pdf->download('laporan-pencari-kerja-' . $mode . '-' . date('Y-m-d') . '.pdf');
    }

    // =========================================================
    // PRINT
    // =========================================================
    public function print(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)
            ->orderByDesc('created_at')
            ->get();

        return view('laporan.pencari-kerja.export.pdf', [
            'mode'  => $mode,
            'data'  => $data,
            'title' => $mode === 'disnaker'
                ? 'Laporan Data Pencari Kerja - DISNAKER'
                : 'Laporan Data Pencari Kerja - PERUSAHAAN',
        ]);
    }
}
