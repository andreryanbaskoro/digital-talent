<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\KartuAk1;
use App\Models\PengalamanKerjaAk1;
use App\Models\ProfilPencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengalamanKerjaAk1Controller extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $profil = ProfilPencariKerja::where(
            'id_pengguna',
            Auth::user()->id_pengguna
        )->first();

        if (!$profil) {
            return redirect('/profil')
                ->with('error', 'Profil belum diisi.');
        }

        $kartuAk1 = KartuAk1::where(
            'id_pencari_kerja',
            $profil->id_pencari_kerja
        )->first();

        $pengalaman = collect();

        if ($kartuAk1) {
            $pengalaman = PengalamanKerjaAk1::where(
                'id_kartu_ak1',
                $kartuAk1->id_kartu_ak1
            )
                ->orderByDesc('created_at')
                ->get();
        }

        $editData = null;

        if (request()->has('edit') && $kartuAk1) {
            $editData = PengalamanKerjaAk1::where(
                'id_kartu_ak1',
                $kartuAk1->id_kartu_ak1
            )->where(
                'id_pengalaman_kerja',
                request('edit')
            )->first();
        }

        return view(
            'admin.pencaker.kartu-ak1.formulir._form_pengalaman',
            [
                'title' => 'Pengalaman Kerja',
                'kartuAk1' => $kartuAk1,
                'pengalaman' => $pengalaman,
                'editData' => $editData, // 🔥 tambah ini
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:150',
            'jabatan' => 'required|string|max:150',
            'mulai_bekerja' => 'required|date',
            'selesai_bekerja' => 'nullable|date|after_or_equal:mulai_bekerja',
            'deskripsi' => 'nullable|string',
        ]);

        $user = Auth::user();

        $profil = ProfilPencariKerja::where(
            'id_pengguna',
            $user->id_pengguna
        )->first();

        if (!$profil) {
            return back()
                ->with('error', 'Profil pencaker belum tersedia.');
        }

        // 🔥 AUTO CREATE AK1 (DRAFT)
        $kartuAk1 = KartuAk1::firstOrCreate(
            ['id_pencari_kerja' => $profil->id_pencari_kerja],
            [
                'status' => 'draft',
                'tanggal_daftar' => now(),
            ]
        );

        // 🔒 Maksimal 10 pengalaman (opsional safety)
        $total = PengalamanKerjaAk1::where(
            'id_kartu_ak1',
            $kartuAk1->id_kartu_ak1
        )->count();

        if ($total >= 10) {
            return back()->with(
                'error',
                'Maksimal 10 pengalaman kerja.'
            );
        }

        PengalamanKerjaAk1::create([
            'id_kartu_ak1' => $kartuAk1->id_kartu_ak1,
            'nama_perusahaan' => $request->nama_perusahaan,
            'jabatan' => $request->jabatan,
            'mulai_bekerja' => $request->mulai_bekerja,
            'selesai_bekerja' => $request->selesai_bekerja,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with(
            'success',
            'Pengalaman kerja berhasil disimpan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:150',
            'jabatan' => 'required|string|max:150',
            'mulai_bekerja' => 'required|date',
            'selesai_bekerja' => 'nullable|date|after_or_equal:mulai_bekerja',
            'deskripsi' => 'nullable|string',
        ]);

        $data = PengalamanKerjaAk1::findOrFail($id);

        $data->update([
            'nama_perusahaan' => $request->nama_perusahaan,
            'jabatan' => $request->jabatan,
            'mulai_bekerja' => $request->mulai_bekerja,
            'selesai_bekerja' => $request->selesai_bekerja,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with(
            'success',
            'Pengalaman kerja berhasil diperbarui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = PengalamanKerjaAk1::findOrFail($id);

        $data->delete(); // SoftDelete

        return back()->with(
            'success',
            'Pengalaman kerja berhasil dihapus.'
        );
    }
}
