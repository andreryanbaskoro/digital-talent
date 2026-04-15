<?php

namespace App\Http\Controllers\Admin\Disnaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\KartuAk1;
use App\Models\Pengguna;

class Ak1Controller extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $item = KartuAk1::findOrFail($id);
        $user = Auth::user();

        // ================= VALIDASI DINAMIS =================
        $rules = [
            'status' => 'required|in:draft,pending,disetujui,ditolak',
        ];

        // Kalau disetujui atau ditolak → catatan wajib
        if (in_array($request->status, ['disetujui', 'ditolak'])) {
            $rules['catatan_petugas'] = 'required|string|min:5|max:1000';
        } else {
            $rules['catatan_petugas'] = 'nullable|string|max:1000';
        }

        $request->validate($rules, [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'catatan_petugas.required' => 'Catatan wajib diisi untuk status ini.',
            'catatan_petugas.min' => 'Catatan minimal 5 karakter.',
            'catatan_petugas.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        // ================= SIAPKAN DATA =================
        $data = [
            'status' => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
            'nama_petugas' => $user?->nama ?? '-',
            'nip_petugas'  => $user?->nip ?? $user?->id_pengguna ?? '-',
            'berlaku_mulai' => null,
            'berlaku_sampai' => null,
        ];

        // ================= LOGIC KHUSUS =================
        if ($request->status === 'disetujui') {
            $data['berlaku_mulai'] = now();
            $data['berlaku_sampai'] = now()->addYears(2);
        }

        if ($request->status === 'disetujui' && !$item->profilPencariKerja) {
            return back()->with('error', 'Profil pencari kerja belum lengkap.');
        }

        $item->update($data);

        return back()->with('success', 'Status AK1 berhasil diperbarui.');
    }

    // MODAL DETAIL
    public function detailJson($id)
    {
        $item = $this->baseWithTrash()->findOrFail($id);
        $profil = $item->profilPencariKerja;

        $profilLengkap = $this->cekProfilLengkap($profil);
        $dokumenLengkap = $this->cekDokumenLengkap($item);


        return response()->json([
            'id' => $item->id_kartu_ak1,
            'nama' => $profil->nama_lengkap ?? '-',
            'nik' => $profil->nik ?? '-',
            'no' => $item->nomor_ak1 ?? $item->nomor_pendaftaran ?? $item->id_kartu_ak1 ?? '-',
            'tanggal' => optional($item->created_at)->format('d-m-Y'),
            'status' => $item->status,

            'profil_lengkap' => $profilLengkap,
            'dokumen_lengkap' => $dokumenLengkap,

            // ✅ INI TAMBAHAN PENTING (URL FILE)
            'foto_pas_url' => $item->foto_pas ? asset('storage/' . $item->foto_pas) : null,
            'ktp_url' => $item->scan_ktp ? asset('storage/' . $item->scan_ktp) : null,
            'ijazah_url' => $item->scan_ijazah ? asset('storage/' . $item->scan_ijazah) : null,
            'kk_url' => $item->scan_kk ? asset('storage/' . $item->scan_kk) : null,
            'foto_url' => $profil->foto
                ? asset('storage/' . $profil->foto)
                : null,

            'nama_petugas' => $item->nama_petugas ?? '-',
            'nip_petugas' => $item->nip_petugas ?? '-',
            'berlaku_mulai' => $item->berlaku_mulai
                ? \Carbon\Carbon::parse($item->berlaku_mulai)->format('d-m-Y')
                : null,

            'berlaku_sampai' => $item->berlaku_sampai
                ? \Carbon\Carbon::parse($item->berlaku_sampai)->format('d-m-Y')
                : null,
        ]);
    }

    private function cekProfilLengkap($profil)
    {
        if (!$profil) {
            return false;
        }

        $required = [
            'nik',
            'nomor_kk',
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'status_perkawinan',
            'alamat',
            'kabupaten',
            'provinsi',
            'kode_pos',
            'nomor_hp',
            'email',
            'foto',
        ];

        foreach ($required as $field) {
            if (empty($profil->$field)) {
                return false;
            }
        }

        return true;
    }

    private function cekDokumenLengkap($item)
    {
        $required = [
            'foto_pas',
            'scan_ktp',
            'scan_ijazah',
            'scan_kk',
        ];

        foreach ($required as $field) {
            if (empty($item->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Base query (tanpa trash)
     */
    private function baseQuery()
    {
        return KartuAk1::with([
            'profilPencariKerja',
            'keterampilan',
            'pengalamanKerja',
            'riwayatPendidikan',
            'laporan',
            'verifikasi',
        ]);
    }

    /**
     * Include soft delete (untuk admin view)
     */
    private function baseWithTrash()
    {
        return $this->baseQuery()->withTrashed();
    }

    /**
     * ================= COUNT STATUS =================
     */
    private function statusCounts()
    {
        return [
            'draft'      => KartuAk1::where('status', 'draft')->count(),
            'pending'    => KartuAk1::where('status', 'pending')->count(),
            'disetujui'  => KartuAk1::where('status', 'disetujui')->count(),
            'ditolak'    => KartuAk1::where('status', 'ditolak')->count(),
            'trash'      => KartuAk1::onlyTrashed()->count(),
            'total'      => KartuAk1::count(),
        ];
    }

    /**
     * ================= INDEX =================
     * Semua data aktif
     */
    public function index()
    {
        $items = $this->baseQuery()->latest()->get();

        return view('admin.disnaker.ak1.index', [
            'title'  => 'Data AK1',
            'items'  => $items,
            'counts' => $this->statusCounts(),
            'viewMode' => 'active',
        ]);
    }
    /**
     * ================= VERIFIKASI =================
     */
    public function verifikasi()
    {
        $items = $this->baseQuery()
            ->whereIn('status', ['draft', 'pending'])
            ->latest()
            ->get();

        return view('admin.disnaker.ak1.verifikasi', [
            'title'  => 'Verifikasi AK1',
            'items'  => $items,
            'counts' => $this->statusCounts(),
        ]);
    }

    public function draft()
    {
        $items = $this->baseQuery()
            ->where('status', 'draft')
            ->latest()
            ->get();

        return view('admin.disnaker.ak1.draft', compact('items'));
    }

    public function pending()
    {
        $items = $this->baseQuery()
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.disnaker.ak1.pending', compact('items'));
    }

    public function disetujui()
    {
        $items = $this->baseQuery()
            ->where('status', 'disetujui')
            ->latest()
            ->get();

        return view('admin.disnaker.ak1.disetujui', compact('items'));
    }

    public function ditolak()
    {
        $items = $this->baseQuery()
            ->where('status', 'ditolak')
            ->latest()
            ->get();

        return view('admin.disnaker.ak1.ditolak', compact('items'));
    }

    /**
     * ================= DETAIL =================
     */
    public function show($id)
    {
        $item = $this->baseWithTrash()->findOrFail($id);

        return view('admin.disnaker.ak1.show', [
            'title' => 'Detail AK1',
            'item'  => $item,
        ]);
    }

}
