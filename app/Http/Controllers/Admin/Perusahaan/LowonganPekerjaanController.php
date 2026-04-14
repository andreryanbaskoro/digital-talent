<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LowonganPekerjaan;
use App\Models\ProfilPerusahaan;
use Illuminate\Support\Facades\Auth;

class LowonganPekerjaanController extends Controller
{
    /**
     * Query dasar: hanya lowongan milik perusahaan yang login.
     */
    private function ownedQuery()
    {
        return LowonganPekerjaan::whereHas('profilPerusahaan', function ($q) {
            $q->where('id_pengguna', Auth::id());
        });
    }

    /**
     * Ambil satu lowongan milik perusahaan yang login.
     */
    private function findOwnedLowongan($id, $withTrashed = false)
    {
        $query = $this->ownedQuery();

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->findOrFail($id);
    }

    // ===================== LIST DATA =====================
    public function index()
    {
        $lowongan = $this->ownedQuery()
            ->with('profilPerusahaan')
            ->withTrashed()
            ->latest()
            ->get();

        return view('admin.perusahaan.lowongan.index', [
            'title' => 'Kelola Lowongan',
            'lowongan' => $lowongan
        ]);
    }

    // ===================== FORM TAMBAH =====================
    public function create()
    {
        $year = now()->format('Y');

        $last = LowonganPekerjaan::withTrashed()
            ->where('id_lowongan', 'like', "LOW-$year-%")
            ->orderBy('id_lowongan', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->id_lowongan, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $newId = "LOW-$year-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return view('admin.perusahaan.lowongan.create', [
            'title' => 'Tambah Lowongan',
            'generatedId' => $newId
        ]);
    }

    // ===================== SIMPAN =====================
    public function store(Request $request)
    {
        $profil = ProfilPerusahaan::where('id_pengguna', Auth::id())->first();

        if (!$profil) {
            return back()
                ->withInput()
                ->with('error', 'Profil perusahaan belum dibuat. Silakan lengkapi profil terlebih dahulu.');
        }

        $messages = [
            'judul_lowongan.required' => 'Judul lowongan wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib dipilih',
            'sistem_kerja.required' => 'Sistem kerja wajib dipilih',
            'kuota.required' => 'Kuota wajib diisi',
            'kuota.integer' => 'Kuota harus berupa angka',
            'kuota.min' => 'Kuota minimal 1',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai',
        ];

        $validated = $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'jenis_pekerjaan' => 'required|string|max:100',
            'sistem_kerja' => 'required|string|max:100',
            'gaji_minimum' => 'nullable|numeric',
            'gaji_maksimum' => 'nullable|numeric',
            'pendidikan_minimum' => 'nullable|string|max:100',
            'pengalaman_minimum' => 'nullable|string|max:100',
            'kuota' => 'required|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ], $messages);

        LowonganPekerjaan::create([
            'id_perusahaan' => $profil->id_perusahaan,
            'judul_lowongan' => $validated['judul_lowongan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'lokasi' => $validated['lokasi'],
            'jenis_pekerjaan' => $validated['jenis_pekerjaan'],
            'sistem_kerja' => $validated['sistem_kerja'],
            'gaji_minimum' => $validated['gaji_minimum'] ?? null,
            'gaji_maksimum' => $validated['gaji_maksimum'] ?? null,
            'pendidikan_minimum' => $validated['pendidikan_minimum'] ?? null,
            'pengalaman_minimum' => $validated['pengalaman_minimum'] ?? null,
            'kuota' => $validated['kuota'],
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_berakhir' => $validated['tanggal_berakhir'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan berhasil ditambahkan');
    }

    // ===================== DETAIL =====================
    public function show($id)
    {
        $lowongan = $this->findOwnedLowongan($id, true);

        return view('admin.perusahaan.lowongan.show', [
            'title' => 'Detail Lowongan',
            'lowongan' => $lowongan
        ]);
    }

    // ===================== FORM EDIT =====================
    public function edit($id)
    {
        $lowongan = $this->findOwnedLowongan($id);

        if ($lowongan->status === 'disetujui') {
            return redirect()->route('perusahaan.lowongan.index')
                ->with('error', 'Lowongan yang sudah disetujui tidak bisa diedit');
        }

        return view('admin.perusahaan.lowongan.edit', [
            'title' => 'Edit Lowongan',
            'lowongan' => $lowongan
        ]);
    }

    // ===================== UPDATE =====================
    public function update(Request $request, $id)
    {
        $lowongan = $this->findOwnedLowongan($id);

        if ($lowongan->status === 'disetujui') {
            return redirect()->route('perusahaan.lowongan.index')
                ->with('error', 'Lowongan yang sudah disetujui tidak bisa diubah');
        }

        $messages = [
            'judul_lowongan.required' => 'Judul lowongan wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib dipilih',
            'sistem_kerja.required' => 'Sistem kerja wajib dipilih',
            'kuota.required' => 'Kuota wajib diisi',
            'kuota.integer' => 'Kuota harus berupa angka',
            'kuota.min' => 'Kuota minimal 1',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai',
        ];

        $validated = $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'jenis_pekerjaan' => 'required|string|max:100',
            'sistem_kerja' => 'required|string|max:100',
            'gaji_minimum' => 'nullable|numeric',
            'gaji_maksimum' => 'nullable|numeric',
            'pendidikan_minimum' => 'nullable|string|max:100',
            'pengalaman_minimum' => 'nullable|string|max:100',
            'kuota' => 'required|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ], $messages);

        $lowongan->update([
            'judul_lowongan' => $validated['judul_lowongan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'lokasi' => $validated['lokasi'],
            'jenis_pekerjaan' => $validated['jenis_pekerjaan'],
            'sistem_kerja' => $validated['sistem_kerja'],
            'gaji_minimum' => $validated['gaji_minimum'] ?? null,
            'gaji_maksimum' => $validated['gaji_maksimum'] ?? null,
            'pendidikan_minimum' => $validated['pendidikan_minimum'] ?? null,
            'pengalaman_minimum' => $validated['pengalaman_minimum'] ?? null,
            'kuota' => $validated['kuota'],
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_berakhir' => $validated['tanggal_berakhir'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan berhasil diperbarui');
    }

    // ===================== HAPUS =====================
    public function destroy($id)
    {
        $lowongan = $this->findOwnedLowongan($id);
        $lowongan->delete();

        return redirect()->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan berhasil dihapus');
    }

    // ===================== RESTORE =====================
    public function restore($id)
    {
        $lowongan = $this->ownedQuery()
            ->onlyTrashed()
            ->findOrFail($id);

        $lowongan->restore();

        return redirect()->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan berhasil dipulihkan');
    }

    // ===================== FORCE DELETE =====================
    public function forceDelete($id)
    {
        $lowongan = $this->ownedQuery()
            ->onlyTrashed()
            ->findOrFail($id);

        $lowongan->forceDelete();

        return redirect()->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan dihapus permanen');
    }
}
