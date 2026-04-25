<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use App\Models\KartuAk1;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profil = $user?->profilPencariKerja;

        $idPencaker = $profil?->id_pencari_kerja;

        $lamaranQuery = LamaranPekerjaan::query()
            ->where('id_pencari_kerja', $idPencaker);

        $kartuAk1 = KartuAk1::where('id_pencari_kerja', $idPencaker)->first();

        $data = [
            'title' => 'Dashboard Pencari Kerja',

            // AK1
            'ak1' => $kartuAk1,

            // Lamaran
            'totalLamaran' => (clone $lamaranQuery)->count(),
            'lamaranPending' => (clone $lamaranQuery)->where('status_lamaran', 'pending')->count(),
            'lamaranDiterima' => (clone $lamaranQuery)->where('status_lamaran', 'diterima')->count(),
            'lamaranDitolak' => (clone $lamaranQuery)->where('status_lamaran', 'ditolak')->count(),

            // terbaru
            'lamaranTerbaru' => (clone $lamaranQuery)
                ->with('lowongan')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.pencaker.dashboard.dashboard', $data);
    }
}
