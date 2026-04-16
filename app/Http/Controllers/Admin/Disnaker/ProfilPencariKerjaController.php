<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfilPencariKerja;
use Illuminate\Support\Facades\Storage;

class ProfilPencariKerjaController extends Controller
{
    // ===================== LIST DATA =====================
    public function index()
    {
        $pencariKerja = ProfilPencariKerja::withTrashed()
            ->latest()
            ->get();

        return view('admin.disnaker.pencari-kerja.index', [
            'title' => 'Data Pencari Kerja',
            'pencariKerja' => $pencariKerja
        ]);
    }

    // ===================== FORM EDIT =====================
    public function edit($id)
    {
        $pencariKerja = ProfilPencariKerja::findOrFail($id);

        return view('admin.disnaker.pencari-kerja.edit', [
            'title' => 'Edit Pencari Kerja',
            'pencariKerja' => $pencariKerja
        ]);
    }

    // ===================== UPDATE =====================
    public function update(Request $request, $id)
    {
        $pencariKerja = ProfilPencariKerja::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:profil_pencari_kerja,email,' . $id . ',id_pencari_kerja',
            'nomor_hp' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ===================== UPLOAD FOTO =====================
        if ($request->hasFile('foto')) {

            // Hapus foto lama
            if ($pencariKerja->foto && Storage::disk('public')->exists($pencariKerja->foto)) {
                Storage::disk('public')->delete($pencariKerja->foto);
            }

            // Simpan foto baru
            $pencariKerja->foto = $request->file('foto')
                ->store('foto_pencaker', 'public');
        }

        // ===================== UPDATE DATA =====================
        $pencariKerja->update([
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
            'foto' => $pencariKerja->foto,
        ]);

        return redirect()->route('disnaker.pencari-kerja.index')
            ->with('success', 'Pencari kerja berhasil diperbarui');
    }

    // ===================== SOFT DELETE =====================
    public function destroy($id)
    {
        $pencariKerja = ProfilPencariKerja::findOrFail($id);

        // ❌ Jangan hapus file saat soft delete
        $pencariKerja->delete();

        return redirect()->route('disnaker.pencari-kerja.index')
            ->with('success', 'Pencari kerja berhasil dihapus');
    }

    // ===================== RESTORE =====================
    public function restore($id)
    {
        $pencariKerja = ProfilPencariKerja::onlyTrashed()
            ->findOrFail($id);

        $pencariKerja->restore();

        return redirect()->route('disnaker.pencari-kerja.index')
            ->with('success', 'Pencari kerja berhasil dipulihkan');
    }

    // ===================== FORCE DELETE =====================
    public function forceDelete($id)
    {
        $pencariKerja = ProfilPencariKerja::onlyTrashed()
            ->findOrFail($id);

        // ✅ Hapus file dari storage
        if ($pencariKerja->foto && Storage::disk('public')->exists($pencariKerja->foto)) {
            Storage::disk('public')->delete($pencariKerja->foto);
        }

        $pencariKerja->forceDelete();

        return redirect()->route('disnaker.pencari-kerja.index')
            ->with('success', 'Pencari kerja berhasil dihapus permanen');
    }
}
