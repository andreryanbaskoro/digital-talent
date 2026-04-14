<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // ===================== LIST DATA =====================
    public function index()
    {
        $pengguna = Pengguna::withTrashed()
            ->latest()
            ->get();

        return view('admin.disnaker.pengguna.index', [
            'title' => 'Kelola Pengguna',
            'pengguna' => $pengguna
        ]);
    }

    // ===================== FORM TAMBAH =====================
    public function create()
    {
        $previewId = $this->generateId();

        return view('admin.disnaker.pengguna.create', [
            'title' => 'Tambah Pengguna',
            'previewId' => $previewId
        ]);
    }

    // ===================== SIMPAN DATA =====================
    public function store(Request $request)
    {
        $messages = [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'kata_sandi.required' => 'Kata sandi wajib diisi',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter',
            'peran.required' => 'Peran wajib dipilih',
            'status.required' => 'Status wajib dipilih',
        ];

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'kata_sandi' => 'required|min:6',
            'peran' => 'required',
            'status' => 'required',
        ], $messages);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'peran' => $request->peran,
            'status' => $request->status,
        ]);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    // ===================== DETAIL =====================
    public function show($id)
    {
        $pengguna = Pengguna::withTrashed()->findOrFail($id);

        return view('admin.disnaker.pengguna.show', [
            'title' => 'Detail Pengguna',
            'pengguna' => $pengguna
        ]);
    }

    // ===================== FORM EDIT =====================
    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        return view('admin.disnaker.pengguna.edit', [
            'title' => 'Edit Pengguna',
            'pengguna' => $pengguna
        ]);
    }

    // ===================== UPDATE =====================
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $messages = [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'peran.required' => 'Peran wajib dipilih',
            'status.required' => 'Status wajib dipilih',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter',
        ];

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id . ',id_pengguna',
            'peran' => 'required',
            'status' => 'required',
            'kata_sandi' => 'nullable|min:6',
        ], $messages);

        $dataUpdate = [
            'nama' => $request->nama,
            'email' => $request->email,
            'peran' => $request->peran,
            'status' => $request->status,
        ];

        if ($request->filled('kata_sandi')) {
            $dataUpdate['kata_sandi'] = Hash::make($request->kata_sandi);
        }

        $pengguna->update($dataUpdate);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    // ===================== HAPUS (SOFT DELETE) =====================
    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }

    // ===================== RESTORE =====================
    public function restore($id)
    {
        $pengguna = Pengguna::onlyTrashed()->findOrFail($id);
        $pengguna->restore();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dipulihkan');
    }

    // ===================== FORCE DELETE =====================
    public function forceDelete($id)
    {
        $pengguna = Pengguna::onlyTrashed()->findOrFail($id);
        $pengguna->forceDelete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna dihapus permanen');
    }

    private function generateId()
    {
        $tanggal = now()->format('ymd');

        $last = Pengguna::withTrashed()
            ->where('id_pengguna', 'like', 'USR-' . $tanggal . '-%')
            ->selectRaw("MAX(CAST(SUBSTRING(id_pengguna, 12) AS UNSIGNED)) as max_id")
            ->first();

        $number = $last && $last->max_id ? $last->max_id + 1 : 1;

        return 'USR-' . $tanggal . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
