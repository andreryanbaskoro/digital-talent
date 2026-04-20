<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Landing\LandingPageController;

// DISNAKER
use App\Http\Controllers\Admin\Disnaker\DashboardController as DisnakerDashboardController;
use App\Http\Controllers\Admin\Disnaker\VerifikasiLowonganController;
use App\Http\Controllers\Admin\Disnaker\PenggunaController;
use App\Http\Controllers\Admin\Disnaker\Ak1Controller;
use App\Http\Controllers\Admin\Disnaker\ProfilPencariKerjaController as DisnakerProfilPencariKerjaController;
use App\Http\Controllers\Admin\Disnaker\ProfilPerusahaanController as DisnakerProfilPerusahaanController;

// PERUSAHAAN
use App\Http\Controllers\Admin\Perusahaan\DashboardController as PerusahaanDashboardController;
use App\Http\Controllers\Admin\Perusahaan\ProfilPerusahaanController as PerusahaanProfilPerusahaanController;
use App\Http\Controllers\Admin\Perusahaan\LowonganPekerjaanController;

// PENCAKER
use App\Http\Controllers\Admin\Pencaker\DashboardController as PencakerDashboardController;
use App\Http\Controllers\Admin\Pencaker\ProfilPencariKerjaController as PencakerProfilPencariKerjaController;
use App\Http\Controllers\Admin\Pencaker\KartuAk1Controller;
use App\Http\Controllers\Admin\Pencaker\KeterampilanAk1Controller;
use App\Http\Controllers\Admin\Pencaker\PengalamanKerjaAk1Controller;
use App\Http\Controllers\Admin\Pencaker\RiwayatPendidikanAk1Controller;
use App\Http\Controllers\Admin\Pencaker\LamaranPekerjaanController;

// Landing Page (public)
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/lowongan/{id_lowongan}', [LandingPageController::class, 'detail'])->name('landing.lowongan.detail');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->get('/dashboard', function () {
    return match (Auth::user()->peran) {
        'disnaker' => redirect()->route('disnaker.dashboard'),
        'perusahaan' => redirect()->route('perusahaan.dashboard'),
        'pencaker' => redirect()->route('pencaker.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

Route::middleware(['auth', 'cekrole:disnaker'])
    ->prefix('admin/disnaker')
    ->name('disnaker.')
    ->group(function () {

        Route::get('/dashboard', [DisnakerDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('verifikasi-lowongan')->name('verifikasi-lowongan.')->group(function () {
            Route::get('/', [VerifikasiLowonganController::class, 'index'])->name('index');
            Route::post('/{id}/approve', [VerifikasiLowonganController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [VerifikasiLowonganController::class, 'reject'])->name('reject');
        });

        Route::prefix('pengguna')->name('pengguna.')->group(function () {
            Route::get('/', [PenggunaController::class, 'index'])->name('index');
            Route::get('/create', [PenggunaController::class, 'create'])->name('create');
            Route::post('/', [PenggunaController::class, 'store'])->name('store');
            Route::get('/{id}', [PenggunaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PenggunaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PenggunaController::class, 'update'])->name('update');
            Route::delete('/{id}', [PenggunaController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [PenggunaController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [PenggunaController::class, 'forceDelete'])->name('forceDelete');
        });

        Route::prefix('ak1')->name('ak1.')->group(function () {
            Route::get('/', [Ak1Controller::class, 'index'])->name('index');
            Route::get('/verifikasi', [Ak1Controller::class, 'verifikasi'])->name('verifikasi');
            Route::get('/draft', [Ak1Controller::class, 'draft'])->name('draft');
            Route::get('/pending', [Ak1Controller::class, 'pending'])->name('pending');
            Route::get('/disetujui', [Ak1Controller::class, 'disetujui'])->name('disetujui');
            Route::get('/ditolak', [Ak1Controller::class, 'ditolak'])->name('ditolak');

            Route::get('/{id}', [Ak1Controller::class, 'show'])->name('show');
            Route::get('/{id}/json', [Ak1Controller::class, 'detailJson'])->name('json');
            Route::put('/{id}/status', [Ak1Controller::class, 'updateStatus'])->name('status.update');
            Route::delete('/{id}', [Ak1Controller::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [Ak1Controller::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [Ak1Controller::class, 'forceDelete'])->name('forceDelete');
        });

        Route::prefix('pencari-kerja')->name('pencari-kerja.')->group(function () {
            Route::get('/', [DisnakerProfilPencariKerjaController::class, 'index'])->name('index');
            Route::get('/{id}', [DisnakerProfilPencariKerjaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [DisnakerProfilPencariKerjaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DisnakerProfilPencariKerjaController::class, 'update'])->name('update');
            Route::delete('/{id}', [DisnakerProfilPencariKerjaController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [DisnakerProfilPencariKerjaController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [DisnakerProfilPencariKerjaController::class, 'forceDelete'])->name('forceDelete');
        });

        Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
            Route::get('/', [DisnakerProfilPerusahaanController::class, 'index'])->name('index');
            Route::get('/{id}', [DisnakerProfilPerusahaanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [DisnakerProfilPerusahaanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DisnakerProfilPerusahaanController::class, 'update'])->name('update');
            Route::delete('/{id}', [DisnakerProfilPerusahaanController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [DisnakerProfilPerusahaanController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [DisnakerProfilPerusahaanController::class, 'forceDelete'])->name('forceDelete');
        });
    });
Route::middleware(['auth', 'cekrole:perusahaan'])
    ->prefix('admin/perusahaan')
    ->name('perusahaan.')
    ->group(function () {

        // ================= DASHBOARD =================
        Route::get('/dashboard', [PerusahaanDashboardController::class, 'index'])
            ->name('dashboard');

        // ================= PROFIL =================
        Route::prefix('profil')->name('profil.')->group(function () {
            Route::get('/', [PerusahaanProfilPerusahaanController::class, 'index'])->name('index');
            Route::get('/show', [PerusahaanProfilPerusahaanController::class, 'show'])->name('show');
            Route::get('/edit', [PerusahaanProfilPerusahaanController::class, 'edit'])->name('edit');
            Route::put('/', [PerusahaanProfilPerusahaanController::class, 'update'])->name('update');

            // soft delete
            Route::delete('/', [PerusahaanProfilPerusahaanController::class, 'destroy'])->name('destroy');

            // restore & force delete
            Route::post('/restore', [PerusahaanProfilPerusahaanController::class, 'restore'])->name('restore');
            Route::delete('/force', [PerusahaanProfilPerusahaanController::class, 'forceDelete'])->name('forceDelete');
        });

        // ================= LOWONGAN =================
        Route::prefix('lowongan')->name('lowongan.')->group(function () {

            // penting: create HARUS di atas {id}
            Route::get('/create', [LowonganPekerjaanController::class, 'create'])->name('create');

            Route::get('/', [LowonganPekerjaanController::class, 'index'])->name('index');
            Route::post('/', [LowonganPekerjaanController::class, 'store'])->name('store');

            Route::get('/{id}', [LowonganPekerjaanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [LowonganPekerjaanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LowonganPekerjaanController::class, 'update'])->name('update');

            // soft delete
            Route::delete('/{id}', [LowonganPekerjaanController::class, 'destroy'])->name('destroy');

            // restore & force delete
            Route::post('/{id}/restore', [LowonganPekerjaanController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [LowonganPekerjaanController::class, 'forceDelete'])->name('forceDelete');
        });
    });
    
Route::middleware(['auth', 'cekrole:pencaker'])
    ->prefix('pencaker')
    ->name('pencaker.')
    ->group(function () {

        // ================= DASHBOARD =================
        Route::get('/dashboard', [PencakerDashboardController::class, 'index'])
            ->name('dashboard');

        // ================= PROFIL =================
        Route::prefix('profil')->name('profil.')->group(function () {
            Route::get('/', [PencakerProfilPencariKerjaController::class, 'index'])->name('index');
            Route::get('/show', [PencakerProfilPencariKerjaController::class, 'show'])->name('show');
            Route::get('/edit', [PencakerProfilPencariKerjaController::class, 'edit'])->name('edit');
            Route::put('/', [PencakerProfilPencariKerjaController::class, 'update'])->name('update');
            Route::delete('/', [PencakerProfilPencariKerjaController::class, 'destroy'])->name('destroy');
            Route::post('/restore', [PencakerProfilPencariKerjaController::class, 'restore'])->name('restore');
            Route::delete('/force', [PencakerProfilPencariKerjaController::class, 'forceDelete'])->name('forceDelete');
        });

        // ================= AK1 =================
        Route::prefix('ak1')->name('ak1.')->group(function () {

            Route::get('/', [KartuAk1Controller::class, 'index'])->name('index');

            Route::get('/formulir', [KartuAk1Controller::class, 'formulir'])->name('formulir');

            Route::get('/dokumen-pribadi', [KartuAk1Controller::class, 'dokumenPribadi'])->name('dokumen-pribadi');

            Route::post('/dokumen/upload/{type}', [KartuAk1Controller::class, 'uploadDokumen'])
                ->name('dokumen.upload');

            // pengalaman
            Route::get('/pengalaman', [PengalamanKerjaAk1Controller::class, 'index'])->name('pengalaman.index');
            Route::post('/pengalaman', [PengalamanKerjaAk1Controller::class, 'store'])->name('pengalaman.store');
            Route::delete('/pengalaman/{id}', [PengalamanKerjaAk1Controller::class, 'destroy'])->name('pengalaman.destroy');

            // pendidikan
            Route::get('/pendidikan', [RiwayatPendidikanAk1Controller::class, 'index'])->name('pendidikan.index');
            Route::post('/pendidikan', [RiwayatPendidikanAk1Controller::class, 'store'])->name('pendidikan.store');
            Route::delete('/pendidikan/{id}', [RiwayatPendidikanAk1Controller::class, 'destroy'])->name('pendidikan.destroy');

            // keterampilan
            Route::get('/keterampilan', [KeterampilanAk1Controller::class, 'index'])->name('keterampilan.index');
            Route::post('/keterampilan', [KeterampilanAk1Controller::class, 'store'])->name('keterampilan.store');
            Route::delete('/keterampilan/{id}', [KeterampilanAk1Controller::class, 'destroy'])->name('keterampilan.destroy');

            Route::get('/{id}', [KartuAk1Controller::class, 'show'])->name('show');
            Route::post('/{id}/submit', [KartuAk1Controller::class, 'submit'])->name('submit');
            Route::put('/{id}', [KartuAk1Controller::class, 'update'])->name('update');
            Route::delete('/{id}', [KartuAk1Controller::class, 'destroy'])->name('destroy');
        });

    Route::prefix('lamaran')->name('lamaran.')->group(function () {

        Route::get('/', [LamaranPekerjaanController::class, 'index'])->name('index');

        Route::get('/{id_lowongan}/create', [LamaranPekerjaanController::class, 'create'])->name('create');

        Route::post('/', [LamaranPekerjaanController::class, 'store'])->name('store');

        Route::get('/{id}', [LamaranPekerjaanController::class, 'show'])->name('show');

        Route::get('/{id}/edit', [LamaranPekerjaanController::class, 'edit'])->name('edit');

        Route::put('/{id}', [LamaranPekerjaanController::class, 'update'])->name('update');

        // ================= ACTION =================
        Route::delete('/{id}/cancel', [LamaranPekerjaanController::class, 'cancel'])->name('cancel');

        Route::delete('/{id}/force', [LamaranPekerjaanController::class, 'forceDelete'])->name('forceDelete');

        Route::post('/{id}/restore', [LamaranPekerjaanController::class, 'restore'])->name('restore');
    });
    });
