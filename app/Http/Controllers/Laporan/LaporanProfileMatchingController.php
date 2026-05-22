<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\HasilPerhitungan;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanProfileMatchingExport;
use Carbon\Carbon;

class LaporanProfileMatchingController extends Controller
{
    /**
     * Mode valid:
     * - disnaker
     * - perusahaan
     */
    protected function normalizeMode($mode = null): string
    {
        return in_array($mode, ['disnaker', 'perusahaan']) ? $mode : 'disnaker';
    }

    /**
     * Ambil id_pengguna login.
     */
    protected function currentIdPengguna(): ?string
    {
        $user = Auth::user();

        return data_get($user, 'id_pengguna')
            ?? data_get($user, 'id')
            ?? null;
    }

    /**
     * Query dasar laporan profile matching.
     *
     * Mode disnaker  : semua hasil perhitungan dari semua perusahaan.
     * Mode perusahaan: hanya hasil perhitungan dari lowongan perusahaan login.
     */
    protected function baseQuery(Request $request, string $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $query = HasilPerhitungan::with([
            'lamaran.pencariKerja',
            'lamaran.lowongan.profilPerusahaan',
        ]);

        // ===================== MODE PERUSAHAAN =====================
        if ($mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();
            abort_if(!$idPengguna, 403, 'Akun perusahaan tidak valid.');

            $query->whereHas('lamaran.lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                $q->where('id_pengguna', $idPengguna);
            });
        }

        // ===================== FILTER NAMA PEKERJAAN =====================
        if ($request->filled('nama_pekerjaan')) {
            $query->whereHas('lamaran.lowongan', function ($q) use ($request) {
                $q->where('judul_lowongan', 'like', '%' . $request->nama_pekerjaan . '%');
            });
        }

        // ===================== FILTER JENIS PEKERJAAN (hanya disnaker) =====================
        if ($mode === 'disnaker' && $request->filled('jenis_pekerjaan')) {
            $query->whereHas('lamaran.lowongan', function ($q) use ($request) {
                $q->where('jenis_pekerjaan', $request->jenis_pekerjaan);
            });
        }

        // ===================== FILTER PERIODE SELEKSI =====================
        if ($request->filled('periode_ke')) {
            $query->whereHas('lamaran', function ($q) use ($request) {
                $q->where('periode_ke', $request->periode_ke);
            });
        }

        // ===================== FILTER KESIMPULAN =====================
        if ($request->filled('kesimpulan')) {
            $query->where('rekomendasi', 'like', '%' . $request->kesimpulan . '%');
        }

        return $query->orderByDesc('created_at');
    }

    /**
     * Data dropdown filter.
     */
    protected function filterData(string $mode = 'disnaker'): array
    {
        $mode = $this->normalizeMode($mode);

        $namaPekerjaanQuery = LowonganPekerjaan::withTrashed()
            ->select('judul_lowongan')
            ->whereNotNull('judul_lowongan')
            ->distinct()
            ->orderBy('judul_lowongan');

        $jenisPekerjaanQuery = LowonganPekerjaan::withTrashed()
            ->select('jenis_pekerjaan')
            ->whereNotNull('jenis_pekerjaan')
            ->distinct()
            ->orderBy('jenis_pekerjaan');

        if ($mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();

            if ($idPengguna) {
                $idPerusahaan = ProfilPerusahaan::where('id_pengguna', $idPengguna)
                    ->value('id_perusahaan');

                if ($idPerusahaan) {
                    $namaPekerjaanQuery->where('id_perusahaan', $idPerusahaan);
                    $jenisPekerjaanQuery->where('id_perusahaan', $idPerusahaan);
                }
            }
        }

        $kesimpulanOptions = [
            '⭐ Sangat Cocok',
            '👍 Cocok',
            '❗ Kurang Cocok',
        ];

        $periodeQuery = LamaranPekerjaan::withTrashed()
            ->select('periode_ke', 'nama_periode')
            ->whereNotNull('periode_ke')
            ->distinct()
            ->orderBy('periode_ke');

        if ($mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();

            if ($idPengguna) {
                $periodeQuery->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                    $q->where('id_pengguna', $idPengguna);
                });
            }
        }

        return [
            'namaPekerjaan'    => $namaPekerjaanQuery->get(),
            'jenisPekerjaan'   => $jenisPekerjaanQuery->get(),
            'periodeOptions'   => $periodeQuery->get(),
            'kesimpulanOptions' => $kesimpulanOptions,
        ];
    }

    // =========================================================
    // INDEX
    // =========================================================
    public function index(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)->get();

        // Statistik kategori — exact match sesuai getRekomendasi()
        $totalSangatCocok  = $data->filter(fn($d) => $d->rekomendasi === '⭐ Sangat Cocok')->count();
        $totalCocok        = $data->filter(fn($d) => $d->rekomendasi === '👍 Cocok')->count();
        $totalKurangCocok  = $data->filter(fn($d) => $d->rekomendasi === '❗ Kurang Cocok')->count();

        $filters = $this->filterData($mode);

        return view('laporan.profile-matching.index', [
            'title' => $mode === 'disnaker'
                ? 'Laporan Rekapitulasi Profile Matching - DISNAKER'
                : 'Laporan Rekapitulasi Profile Matching - PERUSAHAAN',
            'mode'              => $mode,
            'data'              => $data,
            'request'           => $request,
            'totalSangatCocok'  => $totalSangatCocok,
            'totalCocok'        => $totalCocok,
            'totalKurangCocok'  => $totalKurangCocok,
            'namaPekerjaan'     => $filters['namaPekerjaan'],
            'jenisPekerjaan'    => $filters['jenisPekerjaan'],
            'kesimpulanOptions' => $filters['kesimpulanOptions'],
            'periodeOptions' => $filters['periodeOptions'],
        ]);
    }

    // =========================================================
    // EXPORT EXCEL
    // =========================================================
    public function exportExcel(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        return Excel::download(
            new LaporanProfileMatchingExport($request->all(), $mode),
            'laporan-profile-matching-' . $mode . '-' . date('Y-m-d') . '.xlsx'
        );
    }

    // =========================================================
    // EXPORT PDF
    // =========================================================
    public function exportPdf(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)->get();

        $totalSangatCocok = $data->filter(fn($d) => $d->rekomendasi === '⭐ Sangat Cocok')->count();
        $totalCocok       = $data->filter(fn($d) => $d->rekomendasi === '👍 Cocok')->count();
        $totalKurangCocok = $data->filter(fn($d) => $d->rekomendasi === '❗ Kurang Cocok')->count();

        $pdf = PDF::loadView('laporan.profile-matching.export.pdf', [
            'mode'             => $mode,
            'data'             => $data,
            'title'            => $mode === 'disnaker'
                ? 'Laporan Rekapitulasi Profile Matching - DISNAKER'
                : 'Laporan Rekapitulasi Profile Matching - PERUSAHAAN',
            'totalSangatCocok' => $totalSangatCocok,
            'totalCocok'       => $totalCocok,
            'totalKurangCocok' => $totalKurangCocok,
        ])->setPaper('A4', 'landscape');

        return $pdf->download('laporan-profile-matching-' . $mode . '-' . date('Y-m-d') . '.pdf');
    }

    // =========================================================
    // PRINT
    // =========================================================
    public function print(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)->get();

        $totalSangatCocok = $data->filter(fn($d) => $d->rekomendasi === '⭐ Sangat Cocok')->count();
        $totalCocok       = $data->filter(fn($d) => $d->rekomendasi === '👍 Cocok')->count();
        $totalKurangCocok = $data->filter(fn($d) => $d->rekomendasi === '❗ Kurang Cocok')->count();

        return view('laporan.profile-matching.export.pdf', [
            'mode'             => $mode,
            'data'             => $data,
            'title'            => $mode === 'disnaker'
                ? 'Laporan Rekapitulasi Profile Matching - DISNAKER'
                : 'Laporan Rekapitulasi Profile Matching - PERUSAHAAN',
            'totalSangatCocok' => $totalSangatCocok,
            'totalCocok'       => $totalCocok,
            'totalKurangCocok' => $totalKurangCocok,
        ]);
    }
}
