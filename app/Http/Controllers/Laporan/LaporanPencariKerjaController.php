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

        $query = LamaranPekerjaan::withTrashed()
            ->with([
                'pencariKerja.pengguna',
                'pencariKerja.kartuAk1.keterampilan',
                'lowongan.profilPerusahaan',
            ]);

        // MODE PERUSAHAAN
        if ($mode === 'perusahaan') {

            $idPengguna = $this->currentIdPengguna();

            abort_if(!$idPengguna, 403, 'Akun perusahaan tidak valid.');

            $query->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                $q->withTrashed()
                    ->where('id_pengguna', $idPengguna);
            });
        }

        // FILTER NAMA PEKERJAAN
        if ($request->filled('nama_pekerjaan')) {
            $query->whereHas('lowongan', function ($q) use ($request) {
                $q->withTrashed()
                    ->where('judul_lowongan', 'like', '%' . $request->nama_pekerjaan . '%');
            });
        }

        // FILTER JENIS PEKERJAAN
        if ($request->filled('jenis_pekerjaan')) {
            $query->whereHas('lowongan', function ($q) use ($request) {
                $q->withTrashed()
                    ->where('jenis_pekerjaan', $request->jenis_pekerjaan);
            });
        }

        // FILTER BULAN PENDAFTARAN
        if ($request->filled('tanggal_pendaftaran')) {

            $start = \Carbon\Carbon::parse($request->tanggal_pendaftaran)
                ->startOfMonth();

            $end = \Carbon\Carbon::parse($request->tanggal_pendaftaran)
                ->endOfMonth();

            $query->whereBetween('tanggal_lamar', [$start, $end]);
        }

        // FILTER JENIS KELAMIN
        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('pencariKerja', function ($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        return $query->orderByDesc('tanggal_lamar');
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
