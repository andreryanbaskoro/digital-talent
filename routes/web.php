<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ================= CONTROLLERS =================
use App\Http\Controllers\Auth\LoginController;

// DISNAKER
use App\Http\Controllers\Admin\Disnaker\DashboardController as DisnakerDashboardController;
use App\Http\Controllers\Admin\Disnaker\VerifikasiLowonganController;
use App\Http\Controllers\Admin\Disnaker\PenggunaController;

// PERUSAHAAN
use App\Http\Controllers\Admin\Perusahaan\DashboardController as PerusahaanDashboardController;
use App\Http\Controllers\Admin\Perusahaan\ProfilPerusahaanController;
use App\Http\Controllers\Admin\Perusahaan\LowonganPekerjaanController;

// PENCAKER
use App\Http\Controllers\Admin\Pencaker\DashboardController as PencakerDashboardController;
use App\Http\Controllers\Admin\Pencaker\ProfilPencariKerjaController;
use App\Http\Controllers\Admin\Pencaker\KeterampilanAk1Controller;
use App\Http\Controllers\Admin\Pencaker\KartuAk1Controller;
use App\Http\Controllers\Admin\Pencaker\PengalamanKerjaAk1Controller;
use App\Http\Controllers\Admin\Pencaker\RiwayatPendidikanAk1Controller;



// =================== HELPER ===================
function routeByRole(array $map, $method = 'index')
{
    $role = Auth::user()->peran;

    if (!isset($map[$role])) {
        abort(403, 'Akses ditolak');
    }

    return app($map[$role])->$method(request());
}


// =================== HOME ===================
Route::get('/', fn() => redirect()->route('login'));


// =================== AUTH ===================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// =================== DASHBOARD ===================
Route::get('/dashboard', function () {
    return routeByRole([
        'disnaker' => DisnakerDashboardController::class,
        'perusahaan' => PerusahaanDashboardController::class,
        'pencaker' => PencakerDashboardController::class,
    ]);
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    // ================= PROFIL =================
    Route::get('/profil', function () {
        return match (Auth::user()->peran) {
            'perusahaan' => app(App\Http\Controllers\Admin\Perusahaan\ProfilPerusahaanController::class)->index(),
            'pencaker' => app(App\Http\Controllers\Admin\Pencaker\ProfilPencariKerjaController::class)->index(),
            default => abort(403),
        };
    })->name('profil.index');

    // ================= EDIT =================
    Route::get('/profil/edit', function () {
        return match (Auth::user()->peran) {
            'perusahaan' => app(App\Http\Controllers\Admin\Perusahaan\ProfilPerusahaanController::class)->edit(),
            'pencaker' => app(App\Http\Controllers\Admin\Pencaker\ProfilPencariKerjaController::class)->edit(),
            default => abort(403),
        };
    })->name('profil.edit');

    // ================= UPDATE =================
    Route::put('/profil/update', function (\Illuminate\Http\Request $request) {
        return match (Auth::user()->peran) {
            'perusahaan' => app(App\Http\Controllers\Admin\Perusahaan\ProfilPerusahaanController::class)->update($request),
            'pencaker' => app(App\Http\Controllers\Admin\Pencaker\ProfilPencariKerjaController::class)->update($request),
            default => abort(403),
        };
    })->name('profil.update');
});

// =================== LOWONGAN ===================
Route::get('/lowongan', function () {
    return routeByRole([
        'perusahaan' => LowonganPekerjaanController::class,
        'pencaker' => LowonganPekerjaanController::class, // nanti beda method kalau perlu
    ]);
})->middleware('auth');

Route::get('/lowongan/create', function () {
    if (Auth::user()->peran !== 'perusahaan') {
        abort(403);
    }

    return app(LowonganPekerjaanController::class)->create();
})->middleware('auth');

Route::post('/lowongan', function () {
    if (Auth::user()->peran !== 'perusahaan') {
        abort(403);
    }

    return app(LowonganPekerjaanController::class)->store(request());
})->middleware('auth');


// =================== LAMARAN ===================
Route::get('/lamaran', function () {
    if (Auth::user()->peran !== 'pencaker') {
        abort(403);
    }

    return 'Lamaran Saya';
})->middleware('auth');

// =================== DISNAKER ===================
Route::get('/verifikasi-lowongan', function () {
    if (Auth::user()->peran !== 'disnaker') {
        abort(403);
    }

    return app(VerifikasiLowonganController::class)->index();
})->middleware('auth');


Route::post('/verifikasi-lowongan/{id}/approve', function ($id) {
    if (Auth::user()->peran !== 'disnaker') {
        abort(403);
    }

    return app(VerifikasiLowonganController::class)->approve($id);
})->middleware('auth');

Route::post('/verifikasi-lowongan/{id}/reject', function ($id) {
    if (Auth::user()->peran !== 'disnaker') {
        abort(403);
    }

    return app(VerifikasiLowonganController::class)->reject($id);
})->middleware('auth');


// =================== PENGGUNA (DISNAKER) ===================
Route::get('/pengguna', function () {
    if (Auth::user()->peran !== 'disnaker') {
        abort(403);
    }

    return app(PenggunaController::class)->index();
})->middleware('auth');

Route::get('/pengguna/create', function () {
    if (Auth::user()->peran !== 'disnaker') {
        abort(403);
    }

    return app(PenggunaController::class)->create();
})->middleware('auth');


// =================== AK1 ===================
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AK1 MAIN
    |--------------------------------------------------------------------------
    */

    Route::get('/ak1', [KartuAk1Controller::class, 'index'])
        ->name('ak1.index');

    Route::get('/ak1/formulir', [KartuAk1Controller::class, 'formulir'])
        ->name('ak1.formulir');

    /*
    |--------------------------------------------------------------------------
    | FORMULIR SECTIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/ak1/formulir/dokumen-pribadi',
        [KartuAk1Controller::class, 'dokumenPribadi']
    )
        ->name('ak1.formulir.dokumen-pribadi');

    Route::get(
        '/ak1/formulir/pengalaman-kerja',
        [KartuAk1Controller::class, 'pengalamanKerja']
    )
        ->name('ak1.formulir.pengalaman-kerja');

    Route::get(
        '/ak1/formulir/riwayat-pendidikan',
        [KartuAk1Controller::class, 'riwayatPendidikan']
    )
        ->name('ak1.formulir.riwayat-pendidikan');


    /*
    |--------------------------------------------------------------------------
    | KETERAMPILAN (SEPARATE CONTROLLER)
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/ak1/formulir/keterampilan',
        [KeterampilanAk1Controller::class, 'index']
    )
        ->name('ak1.formulir.keterampilan');

    Route::post(
        '/ak1/keterampilan',
        [KeterampilanAk1Controller::class, 'store']
    )
        ->name('ak1.keterampilan.store');

    Route::delete(
        '/ak1/keterampilan/{id}',
        [KeterampilanAk1Controller::class, 'destroy']
    )
        ->name('ak1.keterampilan.destroy');


    /*
    |--------------------------------------------------------------------------
    | UPLOAD DOKUMEN
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/ak1/dokumen/{type}',
        [KartuAk1Controller::class, 'uploadDokumen']
    )
        ->name('dokumen.upload');


    /*
    |--------------------------------------------------------------------------
    | EDIT / UPDATE / DELETE AK1
    |--------------------------------------------------------------------------
    | Taruh PALING BAWAH supaya tidak bentrok
    */

    Route::get(
        '/ak1/{id}/edit',
        [KartuAk1Controller::class, 'edit']
    )
        ->name('ak1.edit');

    Route::put(
        '/ak1/{id}',
        [KartuAk1Controller::class, 'update']
    )
        ->name('ak1.update');

    Route::delete(
        '/ak1/{id}',
        [KartuAk1Controller::class, 'destroy']
    )
        ->name('ak1.destroy');

    /*
|--------------------------------------------------------------------------
| PENGALAMAN KERJA (SEPARATE CONTROLLER)
|--------------------------------------------------------------------------
*/

    Route::get(
        '/ak1/formulir/pengalaman-kerja',
        [PengalamanKerjaAk1Controller::class, 'index']
    )
        ->name('ak1.formulir.pengalaman-kerja');

    Route::post(
        '/ak1/pengalaman',
        [PengalamanKerjaAk1Controller::class, 'store']
    )
        ->name('ak1.pengalaman.store');

    Route::put(
        '/ak1/pengalaman/{id}',
        [PengalamanKerjaAk1Controller::class, 'update']
    )
        ->name('ak1.pengalaman.update');

    Route::delete(
        '/ak1/pengalaman/{id}',
        [PengalamanKerjaAk1Controller::class, 'destroy']
    )
        ->name('ak1.pengalaman.destroy');

    /*
|--------------------------------------------------------------------------
| RIWAYAT PENDIDIKAN (SEPARATE CONTROLLER)
|--------------------------------------------------------------------------
*/

    Route::get(
        '/ak1/formulir/riwayat-pendidikan',
        [RiwayatPendidikanAk1Controller::class, 'index']
    )
        ->name('ak1.formulir.riwayat-pendidikan');

    Route::post(
        '/ak1/riwayat-pendidikan',
        [RiwayatPendidikanAk1Controller::class, 'store']
    )
        ->name('ak1.riwayat-pendidikan.store');

    Route::put(
        '/ak1/riwayat-pendidikan/{id}',
        [RiwayatPendidikanAk1Controller::class, 'update']
    )
        ->name('ak1.riwayat-pendidikan.update');

    Route::delete(
        '/ak1/riwayat-pendidikan/{id}',
        [RiwayatPendidikanAk1Controller::class, 'destroy']
    )
        ->name('ak1.riwayat-pendidikan.destroy');

    // AJUKAN AK1 PENDING (PENCAKER)
    Route::post('/ak1/{id}/submit', [KartuAk1Controller::class, 'submit'])
        ->name('ak1.submit');
});
