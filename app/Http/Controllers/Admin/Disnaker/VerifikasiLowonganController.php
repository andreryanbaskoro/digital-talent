<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LowonganPekerjaan;

class VerifikasiLowonganController extends Controller
{
    // ================= LIST =================
    public function index()
    {
        $lowongan = LowonganPekerjaan::latest()->get();

        return view('admin.disnaker.verifikasi-lowongan.index', [
            'title' => 'Verifikasi Lowongan',
            'lowongan' => $lowongan
        ]);
    }

    // ================= APPROVE =================
    public function approve(Request $request, $id)
    {
        $lowongan = LowonganPekerjaan::findOrFail($id);

        if ($lowongan->status !== 'pending') {
            return back()->with('error', 'Lowongan sudah diverifikasi');
        }

        $lowongan->update([
            'status' => 'disetujui',
            'catatan' => $request->catatan // opsional
        ]);

        return back()->with('success', 'Lowongan disetujui');
    }

    // ================= REJECT =================
    public function reject(Request $request, $id)
    {
        $lowongan = LowonganPekerjaan::findOrFail($id);

        if ($lowongan->status !== 'pending') {
            return back()->with('error', 'Lowongan sudah diverifikasi');
        }

        $request->validate([
            'catatan' => 'required|string|max:255'
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi'
        ]);

        $lowongan->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan
        ]);

        return back()->with('success', 'Lowongan ditolak');
    }
}
