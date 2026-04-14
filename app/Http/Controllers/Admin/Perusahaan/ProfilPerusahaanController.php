<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilPerusahaanController extends Controller
{
    // ===================== LIST DATA =====================
    public function index()
    {
        $profil = ProfilPerusahaan::where('id_pengguna', Auth::id())->first();

        return view('admin.perusahaan.profil-perusahaan.index', [
            'title' => 'Profil Perusahaan',
            'profil' => $profil
        ]);
    }


    // ===================== DETAIL =====================
    public function show()
    {
        $profil = ProfilPerusahaan::where('id_pengguna', Auth::id())->firstOrFail();

        return view('admin.perusahaan.profil-perusahaan.show', [
            'title' => 'Detail Profil Perusahaan',
            'profil' => $profil
        ]);
    }

    // ===================== FORM EDIT =====================
    public function edit()
    {
        $profil = ProfilPerusahaan::where('id_pengguna', Auth::id())->first();

        return view('admin.perusahaan.profil-perusahaan.edit', [
            'title' => 'Profil Perusahaan',
            'profil' => $profil
        ]);
    }

    // ===================== UPDATE =====================
    public function update(Request $request)
    {
        $profil = ProfilPerusahaan::firstOrNew([
            'id_pengguna' => Auth::id()
        ]);

        $request->validate([
            'nama_perusahaan' => 'required|max:200',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // upload logo
        if ($request->hasFile('logo')) {
            if ($profil->logo) {
                Storage::delete($profil->logo);
            }

            $profil->logo = $request->file('logo')->store('logo_perusahaan');
        }

        $profil->fill([
            'nama_perusahaan' => $request->nama_perusahaan,
            'nib' => $request->nib,
            'npwp' => $request->npwp,
            'alamat' => $request->alamat,
            'kabupaten' => $request->kabupaten,
            'provinsi' => $request->provinsi,
            'nomor_telepon' => $request->nomor_telepon,
            'website' => $request->website,
            'deskripsi' => $request->deskripsi,
        ]);

        $profil->id_pengguna = Auth::id();
        $profil->save();

        return redirect()->route('perusahaan.profil.index')
            ->with('success', 'Profil berhasil disimpan');
    }

    // ===================== HAPUS =====================
    public function destroy()
    {
        $profil = ProfilPerusahaan::where('id_pengguna', Auth::id())->firstOrFail();

        if ($profil->logo) {
            Storage::delete($profil->logo);
        }

        $profil->delete();

        return redirect()->route('perusahaan.profil.index')
            ->with('success', 'Profil berhasil dihapus');
    }

    // ===================== RESTORE =====================
    public function restore()
    {
        $profil = ProfilPerusahaan::onlyTrashed()
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();

        $profil->restore();

        return redirect()->route('perusahaan.profil.index')
            ->with('success', 'Profil berhasil dipulihkan');
    }

    // ===================== FORCE DELETE =====================
    public function forceDelete()
    {
        $profil = ProfilPerusahaan::onlyTrashed()
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();

        if ($profil->logo) {
            Storage::delete($profil->logo);
        }

        $profil->forceDelete();

        return redirect()->route('perusahaan.profil.index')
            ->with('success', 'Profil dihapus permanen');
    }
}
