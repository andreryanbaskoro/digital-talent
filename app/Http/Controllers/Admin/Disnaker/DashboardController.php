<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use App\Models\HasilPerhitungan;
use App\Models\KartuAk1;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use App\Models\Pengguna;
use App\Models\ProfilPencariKerja;
use App\Models\ProfilPerusahaan;
use App\Models\VerifikasiAk1;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = now()->year;

        $bulanLabels = [
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
        ];

        $totalPengguna       = Pengguna::count();
        $totalPencaker       = ProfilPencariKerja::count();
        $totalPerusahaan     = ProfilPerusahaan::count();
        $totalLowongan       = LowonganPekerjaan::count();
        $lowonganAktif       = LowonganPekerjaan::whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_berakhir', '>=', now())
            ->count();

        $totalLamaran        = LamaranPekerjaan::count();
        $totalAK1            = KartuAk1::count();
        $ak1Pending          = KartuAk1::where('status', 'pending')->count();
        $ak1Disetujui        = KartuAk1::where('status', 'disetujui')->count();
        $ak1Revisi           = KartuAk1::where('is_revised', 1)->count();

        $totalHasil          = HasilPerhitungan::count();
        $verifikasiBulanIni  = VerifikasiAk1::whereYear('tanggal_verifikasi', $tahun)
            ->whereMonth('tanggal_verifikasi', now()->month)
            ->count();

        $hasilTerbaik = HasilPerhitungan::with(['lamaran.pencariKerja', 'lamaran.lowongan'])
            ->orderByDesc('nilai_total')
            ->first();

        $lowonganBulanan = $this->monthlyCount(LowonganPekerjaan::class, $tahun);
        $lamaranBulanan  = $this->monthlyCount(LamaranPekerjaan::class, $tahun);

        $data = [
            'title' => 'Dashboard Disnaker',

            'bulanLabels' => $bulanLabels,
            'lowonganBulanan' => $lowonganBulanan,
            'lamaranBulanan' => $lamaranBulanan,

            'totalPengguna' => $totalPengguna,
            'totalPencaker' => $totalPencaker,
            'totalPerusahaan' => $totalPerusahaan,
            'totalLowongan' => $totalLowongan,
            'lowonganAktif' => $lowonganAktif,
            'totalLamaran' => $totalLamaran,
            'totalAK1' => $totalAK1,
            'ak1Pending' => $ak1Pending,
            'ak1Disetujui' => $ak1Disetujui,
            'ak1Revisi' => $ak1Revisi,
            'totalHasil' => $totalHasil,
            'verifikasiBulanIni' => $verifikasiBulanIni,
            'hasilTerbaik' => $hasilTerbaik,

            'lowonganTerbaru' => LowonganPekerjaan::with('profilPerusahaan')
                ->latest()
                ->take(5)
                ->get(),

            'lamaranTerbaru' => LamaranPekerjaan::with(['lowongan.profilPerusahaan', 'pencariKerja'])
                ->latest()
                ->take(5)
                ->get(),

            'ak1Terbaru' => KartuAk1::with('profilPencariKerja')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.disnaker.dashboard.dashboard', $data);
    }

    private function monthlyCount(string $modelClass, int $tahun): array
    {
        $data = array_fill(0, 12, 0);

        $rows = $modelClass::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $tahun)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan');

        foreach ($rows as $bulan => $total) {
            $data[(int) $bulan - 1] = (int) $total;
        }

        return $data;
    }
}
