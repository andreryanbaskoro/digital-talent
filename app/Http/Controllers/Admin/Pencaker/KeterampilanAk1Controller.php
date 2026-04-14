<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\KartuAk1;
use App\Models\KeterampilanAk1;
use App\Models\ProfilPencariKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KeterampilanAk1Controller extends Controller
{
    public function index()
    {
        $profil = ProfilPencariKerja::where('id_pengguna', Auth::user()->id_pengguna)->first();

        if (!$profil) {
            return redirect('/profil')->with('error', 'Profil belum diisi.');
        }

        $kartuAk1 = KartuAk1::where('id_pencari_kerja', $profil->id_pencari_kerja)->first();

        $keterampilan = collect();

        if ($kartuAk1) {
            $keterampilan = KeterampilanAk1::where('id_kartu_ak1', $kartuAk1->id_kartu_ak1)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('admin.pencaker.kartu-ak1.formulir._form_keterampilan', [
            'title' => 'Keterampilan',
            'kartuAk1' => $kartuAk1,
            'keterampilan' => $keterampilan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_keterampilan' => 'required|string|max:255',
            'tingkat' => 'required|in:Pemula,Menengah,Mahir',
            'sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();

        $profil = ProfilPencariKerja::where('id_pengguna', $user->id_pengguna)->first();

        if (!$profil) {
            return back()->with('error', 'Profil pencaker belum tersedia.');
        }

        // 🔥 AUTO CREATE AK1 (DRAFT)
        $kartuAk1 = KartuAk1::firstOrCreate(
            ['id_pencari_kerja' => $profil->id_pencari_kerja],
            [
                'status' => 'draft',
                'tanggal_daftar' => now(),
            ]
        );

        $filePath = null;

        if ($request->hasFile('sertifikat')) {

            $file = $request->file('sertifikat');

            $filePath = $file->storeAs(
                'dokumen_pribadi', // 🔥 SAMA FOLDER
                uniqid() . '.' . $file->getClientOriginalExtension(),
                'public'
            );
        }

        KeterampilanAk1::create([
            'id_kartu_ak1' => $kartuAk1->id_kartu_ak1,
            'nama_keterampilan' => $request->nama_keterampilan,
            'tingkat' => $request->tingkat,
            'sertifikat' => $filePath,
        ]);

        return back()->with('success', 'Keterampilan berhasil disimpan. Status AK1: Draft.');
    }

    public function destroy($id)
    {
        $data = KeterampilanAk1::findOrFail($id);

        if ($data->sertifikat) {
            Storage::disk('public')->delete($data->sertifikat);
        }

        $data->delete();

        return back()->with('success', 'Keterampilan berhasil dihapus.');
    }
}
