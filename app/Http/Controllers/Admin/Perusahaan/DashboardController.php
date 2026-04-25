<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profilPerusahaan = $user?->profilPerusahaan;
        $perusahaanId = $profilPerusahaan?->id_perusahaan;

        $lowonganBaseQuery = LowonganPekerjaan::query()
            ->when($perusahaanId, function ($q) use ($perusahaanId) {
                $q->where('id_perusahaan', $perusahaanId);
            });

        $lamaranBaseQuery = LamaranPekerjaan::query()
            ->whereHas('lowongan', function ($q) use ($perusahaanId) {
                $q->when($perusahaanId, function ($qq) use ($perusahaanId) {
                    $qq->where('id_perusahaan', $perusahaanId);
                });
            });

        $tahun = now()->year;

        $lowonganBulanan = $this->monthlyCount(
            fn() => clone $lowonganBaseQuery,
            $tahun,
            'created_at'
        );

        $lamaranBulanan = $this->monthlyCount(
            fn() => clone $lamaranBaseQuery,
            $tahun,
            'created_at'
        );

        $lamaranStatus = (clone $lamaranBaseQuery)
            ->selectRaw('status_lamaran, COUNT(*) as total')
            ->groupBy('status_lamaran')
            ->pluck('total', 'status_lamaran')
            ->toArray();

        $data = [
            'title' => 'Dashboard Perusahaan',
            'profilPerusahaan' => $profilPerusahaan,

            'totalLowongan' => (clone $lowonganBaseQuery)->count(),
            'lowonganAktif' => (clone $lowonganBaseQuery)
                ->whereDate('tanggal_mulai', '<=', now())
                ->whereDate('tanggal_berakhir', '>=', now())
                ->count(),
            'lowonganSelesai' => (clone $lowonganBaseQuery)
                ->whereDate('tanggal_berakhir', '<', now())
                ->count(),

            'totalLamaran' => (clone $lamaranBaseQuery)->count(),
            'lamaranBulanIni' => (clone $lamaranBaseQuery)
                ->whereYear('tanggal_lamar', $tahun)
                ->whereMonth('tanggal_lamar', now()->month)
                ->count(),

            'lamaranStatus' => $lamaranStatus,

            'bulanLabels' => [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des'
            ],
            'lowonganBulanan' => $lowonganBulanan,
            'lamaranBulanan' => $lamaranBulanan,

            'lowonganTerbaru' => (clone $lowonganBaseQuery)
                ->latest()
                ->take(5)
                ->get(),

            'lamaranTerbaru' => (clone $lamaranBaseQuery)
                ->with(['pencariKerja', 'lowongan'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.perusahaan.dashboard.dashboard', $data);
    }

    private function monthlyCount(callable $queryFactory, int $tahun, string $dateColumn = 'created_at'): array
    {
        $data = array_fill(0, 12, 0);

        $query = $queryFactory();

        $rows = $query->selectRaw("MONTH($dateColumn) as bulan, COUNT(*) as total")
            ->whereYear($dateColumn, $tahun)
            ->groupBy(DB::raw("MONTH($dateColumn)"))
            ->pluck('total', 'bulan');

        foreach ($rows as $bulan => $total) {
            $data[(int) $bulan - 1] = (int) $total;
        }

        return $data;
    }
}
