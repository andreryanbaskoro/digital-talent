<?php

namespace App\Http\Controllers\Admin\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\LamaranPekerjaan;
use App\Models\LowonganPekerjaan;
use App\Models\SubKriteriaLamaran;
use App\Models\DokumenLamaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LamaranPekerjaanController extends Controller
{
    private function userId()
    {
        $user = Auth::user();

        if (!$user->profilPencariKerja) {
            abort(403, 'Profil pencari kerja belum dibuat');
        }

        return $user->profilPencariKerja->id_pencari_kerja;
    }

    private function authorizeOwner($lamaran)
    {
        if ($lamaran->id_pencari_kerja !== $this->userId()) {
            abort(403, 'Akses ditolak');
        }
    }

    // ================= INDEX =================
    public function index()
    {
        $lamaran = LamaranPekerjaan::with('lowongan')
            ->where('id_pencari_kerja', $this->userId())
            ->latest()
            ->get();

        return view('admin.pencaker.lamaran-pekerjaan.index', [
            'title' => 'Lamaran Pekerjaan Saya',
            'lamaran' => $lamaran
        ]);
    }

    // ================= CREATE =================
    public function create($id_lowongan)
    {
        $lowongan = LowonganPekerjaan::with('subKriteriaLowongan.subKriteria')
            ->findOrFail($id_lowongan);

        $previewId = LamaranPekerjaan::generateId($id_lowongan);

        return view('admin.pencaker.lamaran-pekerjaan.create', [
            'title' => 'Kirim Lamaran Pekerjaan',
            'lowongan' => $lowongan,
            'previewId' => $previewId
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'id_lowongan' => 'required|exists:lowongan_pekerjaan,id_lowongan',

            'sub_kriteria' => 'required|array|min:1',
            'sub_kriteria.*.id_sub_kriteria' => 'required|exists:sub_kriteria,id_sub_kriteria',
            'sub_kriteria.*.nilai' => 'required|integer|between:1,5',

            'jenis_dokumen.*' => 'nullable|string|max:100',
            'jenis_dokumen_custom.*' => 'nullable|string|max:100',
            'lokasi_file.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $userId = $this->userId();

        $exists = LamaranPekerjaan::where('id_lowongan', $request->id_lowongan)
            ->where('id_pencari_kerja', $userId)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Sudah melamar');
        }

        $id = LamaranPekerjaan::generateId($request->id_lowongan);

        DB::transaction(function () use ($request, $userId, $id) {
            LamaranPekerjaan::create([
                'id_lamaran' => $id,
                'id_lowongan' => $request->id_lowongan,
                'id_pencari_kerja' => $userId,
                'tanggal_lamar' => now(),
                'status_lamaran' => 'dikirim'
            ]);

            foreach ($request->sub_kriteria as $item) {
                SubKriteriaLamaran::create([
                    'id_lamaran' => $id,
                    'id_sub_kriteria' => $item['id_sub_kriteria'],
                    'nilai' => $item['nilai']
                ]);
            }

            if ($request->hasFile('lokasi_file')) {
                foreach ($request->file('lokasi_file') as $i => $file) {
                    if (!$file) continue;

                    $jenis = $request->jenis_dokumen[$i] ?? '';

                    if ($jenis === 'lainnya') {
                        $custom = trim($request->jenis_dokumen_custom[$i] ?? '');
                        $jenis = $custom !== '' ? $custom : 'Lainnya';
                    }

                    if ($jenis === '') {
                        $jenis = 'Lainnya';
                    }

                    $path = $file->store('lamaran', 'public');

                    DokumenLamaran::create([
                        'id_lamaran' => $id,
                        'jenis_dokumen' => $jenis,
                        'lokasi_file' => $path
                    ]);
                }
            }
        });

        return redirect()->route('pencaker.lamaran.index')
            ->with('success', 'Lamaran berhasil dikirim');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $lamaran = LamaranPekerjaan::with([
            'lowongan.subKriteriaLowongan.subKriteria',
            'dokumen',
            'subKriteriaLamaran'
        ])->findOrFail($id);

        $this->authorizeOwner($lamaran);

        // 🔥 CEK EXPIRED LOWONGAN
        if ($this->isLowonganExpired($lamaran->lowongan)) {
            return back()->with('error', 'Lowongan sudah berakhir, lamaran tidak bisa diedit');
        }

        return view('admin.pencaker.lamaran-pekerjaan.edit', [
            'title' => 'Edit Lamaran Pekerjaan',
            'lamaran' => $lamaran,
            'lowongan' => $lamaran->lowongan
        ]);
    }
    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $lamaran = LamaranPekerjaan::with([
            'lowongan',
            'dokumen',
            'subKriteriaLamaran'
        ])->findOrFail($id);

        $this->authorizeOwner($lamaran);

        // 🔥 CEK EXPIRED HARUS DI AWAL
        if ($this->isLowonganExpired($lamaran->lowongan)) {
            abort(403, 'Lowongan sudah berakhir, lamaran tidak bisa diedit');
        }

        $request->validate([
            'sub_kriteria' => 'required|array|min:1',
            'sub_kriteria.*.id_sub_kriteria' => 'required|exists:sub_kriteria,id_sub_kriteria',
            'sub_kriteria.*.nilai' => 'required|integer|between:1,5',

            'id_dokumen_existing.*' => 'nullable|string',
            'jenis_dokumen_existing.*' => 'nullable|string|max:100',
            'jenis_dokumen_custom_existing.*' => 'nullable|string|max:100',

            'jenis_dokumen.*' => 'nullable|string|max:100',
            'jenis_dokumen_custom.*' => 'nullable|string|max:100',
            'lokasi_file.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request, $lamaran) {

            // ================= UPDATE SKILL =================
            foreach ($request->sub_kriteria as $item) {
                SubKriteriaLamaran::updateOrCreate(
                    [
                        'id_lamaran' => $lamaran->id_lamaran,
                        'id_sub_kriteria' => $item['id_sub_kriteria'],
                    ],
                    [
                        'nilai' => $item['nilai']
                    ]
                );
            }

            // ================= UPDATE DOKUMEN LAMA =================
            $existingDocs = $lamaran->dokumen()->orderBy('created_at')->get();

            foreach ($existingDocs as $i => $dok) {
                $jenis = $request->jenis_dokumen_existing[$i] ?? $dok->jenis_dokumen;

                if ($jenis === 'lainnya') {
                    $custom = trim($request->jenis_dokumen_custom_existing[$i] ?? '');
                    $jenis = $custom !== '' ? $custom : 'Lainnya';
                }

                $dok->update([
                    'jenis_dokumen' => $jenis,
                ]);
            }

            // ================= TAMBAH DOKUMEN BARU =================
            if ($request->hasFile('lokasi_file')) {
                foreach ($request->file('lokasi_file') as $i => $file) {
                    if (!$file) continue;

                    $jenis = $request->jenis_dokumen[$i] ?? '';

                    if ($jenis === 'lainnya') {
                        $custom = trim($request->jenis_dokumen_custom[$i] ?? '');
                        $jenis = $custom !== '' ? $custom : 'Lainnya';
                    }

                    if ($jenis === '') {
                        $jenis = 'Lainnya';
                    }

                    $path = $file->store('lamaran', 'public');

                    DokumenLamaran::create([
                        'id_lamaran' => $lamaran->id_lamaran,
                        'jenis_dokumen' => $jenis,
                        'lokasi_file' => $path
                    ]);
                }
            }
        });

        return back()->with('success', 'Lamaran berhasil diperbarui');
    }

    public function show($id)
    {
        $lamaran = LamaranPekerjaan::with([
            'lowongan.profilPerusahaan',
            'dokumen',
            'subKriteriaLamaran.subKriteria'
        ])->findOrFail($id);

        $this->authorizeOwner($lamaran);

        $lowongan = $lamaran->lowongan;
        $now = now();

        $isExpired = $lowongan->tanggal_berakhir
            ? \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->lt($now)
            : false;

        $isNotStarted = $lowongan->tanggal_mulai
            ? \Carbon\Carbon::parse($lowongan->tanggal_mulai)->gt($now)
            : false;

        $canModify = !$isExpired && !$isNotStarted;

        return view('admin.pencaker.lamaran-pekerjaan.show', [
            'title' => 'Detail Lamaran',
            'lamaran' => $lamaran,
            'isExpired' => $isExpired,
            'isNotStarted' => $isNotStarted,
            'canModify' => $canModify,
        ]);
    }

    // ================= CANCEL =================
    public function cancel($id)
    {
        $lamaran = LamaranPekerjaan::findOrFail($id);

        $this->authorizeOwner($lamaran);

        $lamaran->delete();

        return back()->with('success', 'Lamaran dibatalkan');
    }

    // ================= FORCE DELETE =================
    public function forceDelete($id)
    {
        $lamaran = LamaranPekerjaan::with('dokumen')->findOrFail($id);

        $this->authorizeOwner($lamaran);

        foreach ($lamaran->dokumen as $dok) {
            if ($dok->lokasi_file && Storage::disk('public')->exists($dok->lokasi_file)) {
                Storage::disk('public')->delete($dok->lokasi_file);
            }

            $dok->forceDelete();
        }

        $lamaran->forceDelete();

        return back()->with('success', 'Lamaran dihapus permanen');
    }

    private function isLowonganExpired($lowongan)
    {
        return $lowongan->tanggal_berakhir
            && \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->lt(now());
    }
}
