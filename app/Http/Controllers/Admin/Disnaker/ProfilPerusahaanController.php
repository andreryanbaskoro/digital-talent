<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan;
use Illuminate\Support\Facades\Storage;

class ProfilPerusahaanController extends Controller
{
    // ===================== LIST DATA =====================
    public function index()
    {
        $perusahaan = ProfilPerusahaan::withTrashed()
            ->latest()
            ->get();

        return view('admin.disnaker.perusahaan.index', [
            'title' => 'Data Perusahaan',
            'perusahaan' => $perusahaan
        ]);
    }

    // ===================== DETAIL =====================
    public function show($id)
    {
        $perusahaan = ProfilPerusahaan::withTrashed()->findOrFail($id);

        return view('admin.disnaker.perusahaan.show', [
            'title' => 'Detail Perusahaan',
            'perusahaan' => $perusahaan
        ]);
    }

    // ===================== FORM EDIT =====================
    public function edit($id)
    {
        $perusahaan = ProfilPerusahaan::findOrFail($id);

        return view('admin.disnaker.perusahaan.edit', [
            'title' => 'Edit Perusahaan',
            'perusahaan' => $perusahaan
        ]);
    }

    // ===================== UPDATE =====================
    // ===================== UPDATE =====================
    public function update(Request $request, $id)
    {
        $perusahaan = ProfilPerusahaan::findOrFail($id);

        $request->validate([
            'id_pengguna' => 'required|string|max:20',
            'nama_perusahaan' => 'required|string|max:200',
            'nib' => 'nullable|string|max:30',
            'npwp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'nomor_telepon' => 'nullable|string|max:15',
            'website' => 'nullable|string|max:150',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // ===================== HANDLE LOGO =====================
        $logoPath = $perusahaan->logo;

        if ($request->hasFile('logo')) {

            // Hapus logo lama
            if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
                Storage::disk('public')->delete($perusahaan->logo);
            }

            // Simpan logo baru
            $logoPath = $request->file('logo')
                ->store('logo_perusahaan', 'public');
        }

        // ===================== UPDATE DATA =====================
        $perusahaan->update([
            'id_pengguna' => $request->id_pengguna,
            'nama_perusahaan' => $request->nama_perusahaan,
            'nib' => $request->nib,
            'npwp' => $request->npwp,
            'alamat' => $request->alamat,
            'kabupaten' => $request->kabupaten,
            'provinsi' => $request->provinsi,
            'nomor_telepon' => $request->nomor_telepon,
            'website' => $request->website,
            'deskripsi' => $request->deskripsi,
            'logo' => $logoPath,
        ]);

       return redirect()->route('disnaker.perusahaan.index')
            ->with('success', 'Perusahaan berhasil diperbarui');
    }

    // ===================== SOFT DELETE =====================
    public function destroy($id)
    {
        $perusahaan = ProfilPerusahaan::findOrFail($id);
        $perusahaan->delete();

       return redirect()->route('disnaker.perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus');
    }

    // ===================== RESTORE =====================
    public function restore($id)
    {
        $perusahaan = ProfilPerusahaan::onlyTrashed()->findOrFail($id);
        $perusahaan->restore();

       return redirect()->route('disnaker.perusahaan.index')
            ->with('success', 'Perusahaan berhasil dipulihkan');
    }

    // ===================== FORCE DELETE =====================
    public function forceDelete($id)
    {
        $perusahaan = ProfilPerusahaan::onlyTrashed()->findOrFail($id);

        if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
            Storage::disk('public')->delete($perusahaan->logo);
        }

        $perusahaan->forceDelete();

       return redirect()->route('disnaker.perusahaan.index')
            ->with('success', 'Perusahaan berhasil dihapus secara permanen');
    }
}
