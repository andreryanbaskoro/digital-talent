<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPelamarPerusahaanExport;

class LaporanPelamarPerusahaanController extends Controller
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
     * Kalau auth kamu bukan default guard, tinggal sesuaikan di sini.
     */
    protected function currentIdPengguna()
    {
        $user = Auth::user();

        return data_get($user, 'id_pengguna')
            ?? data_get($user, 'id')
            ?? null;
    }

    /**
     * Query dasar laporan pelamar
     */
    protected function baseQuery(Request $request, string $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $query = LamaranPekerjaan::withTrashed()
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
            ]);

        // ===================== SCOPE PERUSAHAAN =====================
        // Perusahaan hanya boleh lihat data perusahaannya sendiri
        if ($mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();

            abort_if(!$idPengguna, 403, 'Akun perusahaan tidak valid.');

            $query->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                $q->where('id_pengguna', $idPengguna);
            });
        }

        // ===================== FILTER DISNAKER =====================
        if ($mode === 'disnaker') {
            $query->when($request->filled('nama_perusahaan'), function ($q) use ($request) {
                $q->whereHas('lowongan.profilPerusahaan', function ($qq) use ($request) {
                    $qq->where('nama_perusahaan', 'like', '%' . $request->nama_perusahaan . '%');
                });
            });
        }

        // ===================== FILTER BERSAMA =====================
        $query->when($request->filled('jenis_pekerjaan'), function ($q) use ($request) {
            $q->whereHas('lowongan', function ($qq) use ($request) {
                $qq->where('jenis_pekerjaan', $request->jenis_pekerjaan);
            });
        });

        $query->when($request->filled('nama_pekerjaan'), function ($q) use ($request) {
            $q->whereHas('lowongan', function ($qq) use ($request) {
                $qq->where('judul_lowongan', 'like', '%' . $request->nama_pekerjaan . '%');
            });
        });

        $query->when($request->filled('tanggal_posting'), function ($q) use ($request) {

            $tanggal = explode('-', $request->tanggal_posting);

            $tahun = $tanggal[0] ?? null;
            $bulan = $tanggal[1] ?? null;

            $q->whereHas('lowongan', function ($qq) use ($tahun, $bulan) {

                if ($tahun) {
                    $qq->whereYear('created_at', $tahun);
                }

                if ($bulan) {
                    $qq->whereMonth('created_at', $bulan);
                }
            });
        });

        // ===================== FILTER PERUSAHAAN =====================
        if ($mode === 'perusahaan') {
            $query->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
                $q->whereHas('pencariKerja', function ($qq) use ($request) {
                    $qq->where('jenis_kelamin', $request->jenis_kelamin);
                });
            });
        }

        return $query;
    }

    /**
     * Data dropdown filter
     */
    protected function filterData(string $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $perusahaan = collect();
        $jenisPekerjaan = collect();
        $namaPekerjaan = collect();
        $jenisKelamin = collect();

        if ($mode === 'disnaker') {
            $perusahaan = ProfilPerusahaan::withTrashed()
                ->orderBy('nama_perusahaan')
                ->get(['id_perusahaan', 'nama_perusahaan']);
        }

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

        if ($mode === 'perusahaan') {
            $jenisKelamin = \App\Models\ProfilPencariKerja::withTrashed()
                ->select('jenis_kelamin')
                ->whereNotNull('jenis_kelamin')
                ->distinct()
                ->orderBy('jenis_kelamin')
                ->get();
        }

        return compact('perusahaan', 'jenisPekerjaan', 'namaPekerjaan', 'jenisKelamin');
    }

    public function index(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)
            ->orderByDesc('tanggal_lamar')
            ->get();

        $filters = $this->filterData($mode);

        return view('laporan.pelamar-perusahaan.index', [
            'title' => $mode === 'disnaker'
                ? 'Laporan Data Pelamar Perusahaan - DISNAKER'
                : 'Laporan Data Pelamar Perusahaan - PERUSAHAAN',
            'mode' => $mode,
            'data' => $data,
            'request' => $request,
            'perusahaan' => $filters['perusahaan'],
            'jenisPekerjaan' => $filters['jenisPekerjaan'],
            'namaPekerjaan' => $filters['namaPekerjaan'],
            'jenisKelamin' => $filters['jenisKelamin'],
        ]);
    }

    public function exportExcel(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        return Excel::download(
            new LaporanPelamarPerusahaanExport($request->all(), $mode),
            'laporan-data-pelamar-perusahaan-' . $mode . '-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)
            ->orderByDesc('tanggal_lamar')
            ->get();

        $pdf = PDF::loadView('laporan.pelamar-perusahaan.export.pdf', [
            'mode' => $mode,
            'data' => $data,
            'title' => $mode === 'disnaker'
                ? 'Laporan Data Pelamar Perusahaan - DISNAKER'
                : 'Laporan Data Pelamar Perusahaan - PERUSAHAAN',
        ])->setPaper('A4', 'landscape');

        return $pdf->download('laporan-data-pelamar-perusahaan-' . $mode . '-' . date('Y-m-d') . '.pdf');
    }

    public function print(Request $request, $mode = 'disnaker')
    {
        $mode = $this->normalizeMode($mode);

        $data = $this->baseQuery($request, $mode)
            ->orderByDesc('tanggal_lamar')
            ->get();

        return view('laporan.pelamar-perusahaan.export.pdf', [
            'mode' => $mode,
            'data' => $data,
            'title' => $mode === 'disnaker'
                ? 'Laporan Data Pelamar Perusahaan - DISNAKER'
                : 'Laporan Data Pelamar Perusahaan - PERUSAHAAN',
        ]);
    }
}
