<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\KartuAk1;
use App\Models\ProfilPencariKerja;
use App\Models\RiwayatPendidikanAk1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPendidikanAk1Controller extends Controller
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

        $pendidikan = collect();

        if ($kartuAk1) {
            $pendidikan = RiwayatPendidikanAk1::where(
                'id_kartu_ak1',
                $kartuAk1->id_kartu_ak1
            )
                ->orderByDesc('created_at')
                ->get();
        }

        $editData = null;

        if (request()->has('edit') && $kartuAk1) {
            $editData = RiwayatPendidikanAk1::where(
                'id_kartu_ak1',
                $kartuAk1->id_kartu_ak1
            )->where(
                'id_riwayat_pendidikan',
                request('edit')
            )->first();
        }

        return view(
            'admin.pencaker.kartu-ak1.formulir._form_riwayat_pendidikan',
            [
                'title' => 'Riwayat Pendidikan',
                'kartuAk1' => $kartuAk1,
                'pendidikan' => $pendidikan,
                'editData' => $editData,
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
            'jenjang' => 'required|string|max:100',
            'nama_sekolah' => 'required|string|max:150',
            'jurusan' => 'nullable|string|max:150',
            'tahun_masuk' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'tahun_lulus' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = Auth::user();

        $profil = ProfilPencariKerja::where(
            'id_pengguna',
            $user->id_pengguna
        )->first();

        if (!$profil) {
            return back()->with('error', 'Profil pencaker belum tersedia.');
        }

        // AUTO CREATE AK1 (DRAFT)
        $kartuAk1 = KartuAk1::firstOrCreate(
            ['id_pencari_kerja' => $profil->id_pencari_kerja],
            [
                'status' => 'draft',
                'tanggal_daftar' => now(),
            ]
        );

        // Safety: maksimal 10 riwayat pendidikan
        $total = RiwayatPendidikanAk1::where(
            'id_kartu_ak1',
            $kartuAk1->id_kartu_ak1
        )->count();

        if ($total >= 10) {
            return back()->with(
                'error',
                'Maksimal 10 riwayat pendidikan.'
            );
        }

        RiwayatPendidikanAk1::create([
            'id_kartu_ak1' => $kartuAk1->id_kartu_ak1,
            'jenjang' => $request->jenjang,
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan' => $request->jurusan,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_lulus' => $request->tahun_lulus,
            'nilai_akhir' => $request->nilai_akhir,
        ]);

        return back()->with(
            'success',
            'Riwayat pendidikan berhasil disimpan.'
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
            'jenjang' => 'required|string|max:100',
            'nama_sekolah' => 'required|string|max:150',
            'jurusan' => 'nullable|string|max:150',
            'tahun_masuk' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'tahun_lulus' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = RiwayatPendidikanAk1::findOrFail($id);

        $data->update([
            'jenjang' => $request->jenjang,
            'nama_sekolah' => $request->nama_sekolah,
            'jurusan' => $request->jurusan,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_lulus' => $request->tahun_lulus,
            'nilai_akhir' => $request->nilai_akhir,
        ]);

        return back()->with(
            'success',
            'Riwayat pendidikan berhasil diperbarui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = RiwayatPendidikanAk1::findOrFail($id);

        $data->delete();

        return back()->with(
            'success',
            'Riwayat pendidikan berhasil dihapus.'
        );
    }
}
