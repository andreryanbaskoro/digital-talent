<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\KartuAk1;
use App\Models\ProfilPencariKerja;
use App\Models\LogAktivitas;
use App\Models\PengalamanKerjaAk1;
use App\Models\RiwayatPendidikanAk1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KartuAk1Controller extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $profil = ProfilPencariKerja::where('id_pengguna', $user->id_pengguna)->first();

        if (!$profil) {
            return redirect('/profil')->with('error', 'Profil belum diisi.');
        }

        $requiredFields = [
            'nik',
            'nomor_kk',
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'status_perkawinan',
            'alamat',
            'rt',
            'rw',
            'kelurahan',
            'kecamatan',
            'kabupaten',
            'provinsi',
            'kode_pos',
            'nomor_hp',
            'email',
            'foto',
        ];

        $kekuranganProfil = [];

        foreach ($requiredFields as $field) {
            if (empty($profil->$field)) {
                $kekuranganProfil[] = $field;
            }
        }

        $profilLengkap = empty($kekuranganProfil);

        $daftarAk1 = KartuAk1::where('id_pencari_kerja', $profil->id_pencari_kerja)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pencaker.kartu-ak1.index', compact(
            'profil',
            'daftarAk1',
            'profilLengkap'
        ))->with('title', 'Status & Riwayat AK1');
    }

    // Halaman untuk mengisi formulir
    public function formulir()
    {
        // Ambil data profil pencari kerja
        $profil = ProfilPencariKerja::where('id_pengguna', Auth::user()->id_pengguna)->first();

        // Cek apakah profil sudah lengkap
        $isProfilLengkap = true;
        $requiredFields = [
            'nik',
            'nomor_kk',
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'status_perkawinan',
            'alamat',
            'rt',
            'rw',
            'kelurahan',
            'kecamatan',
            'kabupaten',
            'provinsi',
            'kode_pos',
            'nomor_hp',
            'email',
            'foto'
        ];

        foreach ($requiredFields as $field) {
            if (empty($profil->{$field})) {
                $isProfilLengkap = false;
                break;
            }
        }

        return view('admin.pencaker.kartu-ak1.formulir.index', [
            'title' => 'Formulir AK1',
            'profil' => $profil,
            'isProfilLengkap' => $isProfilLengkap, // Kirim variabel ini ke tampilan
        ]);
    }

    public function uploadDokumen(Request $request, $type)
    {
        $allowedFields = [
            'foto_pas',
            'scan_ktp',
            'scan_ijazah',
            'scan_kk',
        ];

        if (!in_array($type, $allowedFields)) {
            abort(404);
        }

        $request->validate([
            $type => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        $profil = ProfilPencariKerja::where('id_pengguna', $user->id_pengguna)->first();

        if (!$profil) {
            return redirect('/profil')->with('error', 'Profil belum diisi.');
        }

        $kartuAk1 = KartuAk1::firstOrCreate(
            ['id_pencari_kerja' => $profil->id_pencari_kerja],
            ['status' => 'draft']
        );

        $file = $request->file($type);
        $filePath = $file->storeAs(
            'dokumen_pribadi',
            uniqid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        if ($kartuAk1->$type) {
            Storage::disk('public')->delete($kartuAk1->$type);
        }

        $kartuAk1->$type = $filePath;
        $kartuAk1->save();

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }




    // Halaman Dokumen Pribadi
    // In the dokumenPribadi method:
    public function dokumenPribadi()
    {
        $user = Auth::user();
        $profil = ProfilPencariKerja::where('id_pengguna', $user->id_pengguna)->first();

        if (!$profil) {
            return redirect('/profil')->with('error', 'Profil belum diisi.');
        }

        // Ambil data kartu AK1 yang sudah ada
        $kartuAk1 = KartuAk1::where('id_pencari_kerja', $profil->id_pencari_kerja)->first();

        return view('admin.pencaker.kartu-ak1.formulir._form_dokumen_pribadi', [
            'title' => 'Dokumen Pribadi',
            'kartuAk1' => $kartuAk1,
            'foto_pas' => $kartuAk1 ? $kartuAk1->foto_pas : null,
            'scan_ktp' => $kartuAk1 ? $kartuAk1->scan_ktp : null,
            'scan_ijazah' => $kartuAk1 ? $kartuAk1->scan_ijazah : null,
            'scan_kk' => $kartuAk1 ? $kartuAk1->scan_kk : null,
        ]);
    }

    public function submit($id)
    {
        $user = Auth::user();

        $profil = ProfilPencariKerja::where('id_pengguna', $user->id_pengguna)
            ->firstOrFail();

        $kartuAk1 = KartuAk1::where('id_kartu_ak1', $id)
            ->where('id_pencari_kerja', $profil->id_pencari_kerja)
            ->firstOrFail();

        // =====================
        // VALIDASI STATUS
        // =====================
        if ($kartuAk1->status === 'pending') {
            return back()->with('warning', 'AK1 sudah dalam proses verifikasi.');
        }

        if ($kartuAk1->status === 'disetujui') {
            return back()->with('error', 'AK1 sudah disetujui dan tidak bisa diajukan ulang.');
        }

        // =====================
        // UPDATE SAJA
        // =====================
        $kartuAk1->update([
            'status' => 'pending',
        ]);

        return redirect()->route('ak1.index')
            ->with('success', 'AK1 berhasil diajukan dan menunggu verifikasi.');
    }
}
