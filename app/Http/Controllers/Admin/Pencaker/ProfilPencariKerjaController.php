<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfilPencariKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilPencariKerjaController extends Controller
{
    // ===================== LIST DATA =====================
    public function index()
    {
        $profil = ProfilPencariKerja::where('id_pengguna', Auth::user()->id_pengguna)->first();

        return view('admin.pencaker.profil-pencaker.index', [
            'title' => 'Profil Pencari Kerja',
            'profil' => $profil
        ]);
    }

    // ===================== DETAIL =====================
    public function show()
    {
        $profil = ProfilPencariKerja::where('id_pengguna', Auth::user()->id_pengguna)->firstOrFail();

        return view('admin.pencaker.profil-pencaker.show', [
            'title' => 'Detail Profil',
            'profil' => $profil
        ]);
    }

    // ===================== FORM EDIT =====================
    public function edit()
    {
        $profil = ProfilPencariKerja::where('id_pengguna', Auth::user()->id_pengguna)->first();

        return view('admin.pencaker.profil-pencaker.edit', [
            'title' => 'Edit Profil',
            'profil' => $profil
        ]);
    }

    // ===================== UPDATE / CREATE =====================
    public function update(Request $request)
    {
        $profil = ProfilPencariKerja::firstOrNew([
            'id_pengguna' => Auth::user()->id_pengguna
        ]);

        $request->validate([
            'nama_lengkap' => 'required|max:150',
            'nik' => 'nullable|size:16',
            'nomor_kk' => 'nullable|size:16',
            'tanggal_lahir' => 'required|date', // 🔥 wajib untuk generate ID
            'email' => 'nullable|email',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ===================== UPLOAD FOTO =====================
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if ($profil->foto && Storage::disk('public')->exists($profil->foto)) {
                Storage::disk('public')->delete($profil->foto);
            }

            $profil->foto = $request->file('foto')->store('foto_pencaker', 'public');
        }

        // ===================== SIMPAN DATA =====================
        $profil->fill([
            'nik' => $request->nik,
            'nomor_kk' => $request->nomor_kk,
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_perkawinan' => $request->status_perkawinan,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kabupaten' => $request->kabupaten,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
        ]);

        $profil->id_pengguna = Auth::user()->id_pengguna;
        $profil->save();

        return redirect()->back()
            ->with('success', 'Profil berhasil disimpan');
    }

    // ===================== HAPUS =====================
    public function destroy()
    {
        $profil = ProfilPencariKerja::where('id_pengguna', Auth::user()->id_pengguna)
            ->firstOrFail();

        // ❌ Jangan hapus file saat soft delete
        $profil->delete();

        return redirect()->back()
            ->with('success', 'Profil berhasil dihapus');
    }

    // ===================== RESTORE =====================
    public function restore()
    {
        $profil = ProfilPencariKerja::onlyTrashed()
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->firstOrFail();

        $profil->restore();

        return redirect()->back()
            ->with('success', 'Profil berhasil dipulihkan');
    }

    // ===================== FORCE DELETE =====================
    public function forceDelete()
    {
        $profil = ProfilPencariKerja::onlyTrashed()
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->firstOrFail();

        if ($profil->foto && Storage::exists($profil->foto)) {
            Storage::delete($profil->foto);
        }

        $profil->forceDelete();

        return redirect()->back()
            ->with('success', 'Profil dihapus permanen');
    }
}
