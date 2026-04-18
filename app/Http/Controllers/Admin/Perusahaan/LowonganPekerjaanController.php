<?php

namespace App\Http\Controllers\Admin\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LowonganPekerjaan;
use App\Models\ProfilPerusahaan;
use App\Models\KriteriaLowongan;
use App\Models\SubKriteria;
use App\Models\SubKriteriaLowongan;

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

    /**
     * Blueprint kriteria default.
     * Skill tetap ada sebagai kriteria utama, tetapi nilai_target skill disimpan NULL.
     */
    private function criteriaBlueprint()
    {
        return [
            'skill' => [
                'nama_kriteria' => 'skill',
                'jenis_kriteria' => 'core',
                'bobot' => 40,
                'nilai_target' => null,
            ],
            'pengalaman' => [
                'nama_kriteria' => 'pengalaman',
                'jenis_kriteria' => 'core',
                'bobot' => 30,
            ],
            'pendidikan' => [
                'nama_kriteria' => 'pendidikan',
                'jenis_kriteria' => 'secondary',
                'bobot' => 20,
            ],
            'lokasi' => [
                'nama_kriteria' => 'lokasi',
                'jenis_kriteria' => 'secondary',
                'bobot' => 10,
            ],
        ];
    }

    /**
     * Simpan / update kriteria utama lowongan.
     * Skill disimpan dengan nilai_target NULL.
     */
    private function syncCriteria(LowonganPekerjaan $lowongan, array $validated)
    {
        $blueprint = $this->criteriaBlueprint();

        foreach ($blueprint as $key => $meta) {
            $nilaiTarget = $meta['nilai_target'] ?? ($validated['kriteria'][$key]['nilai_target'] ?? null);

            $kriteria = KriteriaLowongan::withTrashed()->firstOrNew([
                'id_lowongan' => $lowongan->id_lowongan,
                'nama_kriteria' => $meta['nama_kriteria'],
            ]);

            $kriteria->id_lowongan = $lowongan->id_lowongan;
            $kriteria->nama_kriteria = $meta['nama_kriteria'];
            $kriteria->jenis_kriteria = $meta['jenis_kriteria'];
            $kriteria->bobot = $meta['bobot'];
            $kriteria->nilai_target = $nilaiTarget; // skill = NULL
            $kriteria->deleted_at = null;
            $kriteria->save();
        }
    }

    /**
     * Simpan / update sub kriteria skill lowongan.
     * Contoh: Laravel, PHP, MySQL.
     */
    private function syncSubKriteria(LowonganPekerjaan $lowongan, array $validated)
    {
        $items = $validated['sub_kriteria'] ?? [];

        $skillKriteria = KriteriaLowongan::where('id_lowongan', $lowongan->id_lowongan)
            ->where('nama_kriteria', 'skill')
            ->first();

        if (!$skillKriteria) {
            throw new \Exception('Kriteria skill untuk lowongan ini belum ditemukan.');
        }

        $usedSubIds = [];

        foreach ($items as $item) {
            $nama = trim($item['nama'] ?? '');
            $nilaiTarget = $item['nilai_target'] ?? null;

            if ($nama === '' || $nilaiTarget === null || $nilaiTarget === '') {
                continue;
            }

            // Master sub kriteria
            $sub = SubKriteria::withTrashed()->firstOrNew([
                'id_kriteria' => $skillKriteria->id_kriteria,
                'nama_sub_kriteria' => $nama,
            ]);

            $sub->id_kriteria = $skillKriteria->id_kriteria;
            $sub->nama_sub_kriteria = $nama;
            $sub->deleted_at = null;
            $sub->save();

            // Relasi lowongan - sub kriteria
            $row = SubKriteriaLowongan::firstOrNew([
                'id_lowongan' => $lowongan->id_lowongan,
                'id_sub_kriteria' => $sub->id_sub_kriteria,
            ]);

            $row->id_lowongan = $lowongan->id_lowongan;
            $row->id_sub_kriteria = $sub->id_sub_kriteria;
            $row->nilai_target = $nilaiTarget;
            $row->save();

            $usedSubIds[] = $sub->id_sub_kriteria;
        }

        // Hapus relasi yang tidak dipakai lagi
        $query = SubKriteriaLowongan::where('id_lowongan', $lowongan->id_lowongan);

        if (!empty($usedSubIds)) {
            $query->whereNotIn('id_sub_kriteria', $usedSubIds);
        }

        $query->delete();
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

            'kriteria.pengalaman.nilai_target.required' => 'Target pengalaman wajib diisi',
            'kriteria.pengalaman.nilai_target.integer' => 'Target pengalaman harus berupa angka',
            'kriteria.pengalaman.nilai_target.between' => 'Target pengalaman harus antara 1 sampai 5',

            'kriteria.pendidikan.nilai_target.required' => 'Target pendidikan wajib diisi',
            'kriteria.pendidikan.nilai_target.integer' => 'Target pendidikan harus berupa angka',
            'kriteria.pendidikan.nilai_target.between' => 'Target pendidikan harus antara 1 sampai 5',

            'kriteria.lokasi.nilai_target.required' => 'Target lokasi wajib diisi',
            'kriteria.lokasi.nilai_target.integer' => 'Target lokasi harus berupa angka',
            'kriteria.lokasi.nilai_target.between' => 'Target lokasi harus antara 1 sampai 5',

            'sub_kriteria' => 'nullable|array',
            'sub_kriteria.*.nama.required' => 'Nama skill wajib diisi',
            'sub_kriteria.*.nilai_target.required' => 'Target skill wajib diisi',
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

            'kriteria.pengalaman.nilai_target' => 'required|integer|between:1,5',
            'kriteria.pendidikan.nilai_target' => 'required|integer|between:1,5',
            'kriteria.lokasi.nilai_target' => 'required|integer|between:1,5',

            'sub_kriteria' => 'nullable|array',
            'sub_kriteria.*.nama' => 'required|string|max:150',
            'sub_kriteria.*.nilai_target' => 'required|integer|between:1,5',
        ], $messages);

        DB::transaction(function () use ($profil, $validated) {
            $lowongan = LowonganPekerjaan::create([
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

            $this->syncCriteria($lowongan, $validated);
            $this->syncSubKriteria($lowongan, $validated);
        });

        return redirect()->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan dan kriteria berhasil ditambahkan');
    }

    // ===================== DETAIL =====================
    public function show($id)
    {
        $lowongan = $this->findOwnedLowongan($id, true)
            ->load('kriteria', 'subKriteriaLowongan.subKriteria');

        return response()->json([
            'id' => $lowongan->id_lowongan,
            'judul_lowongan' => $lowongan->judul_lowongan,
            'deskripsi' => $lowongan->deskripsi,
            'lokasi' => $lowongan->lokasi,
            'jenis_pekerjaan' => $lowongan->jenis_pekerjaan,
            'sistem_kerja' => $lowongan->sistem_kerja,
            'gaji_minimum' => $lowongan->gaji_minimum,
            'gaji_maksimum' => $lowongan->gaji_maksimum,
            'pendidikan_minimum' => $lowongan->pendidikan_minimum,
            'pengalaman_minimum' => $lowongan->pengalaman_minimum,
            'kuota' => $lowongan->kuota,
            'tanggal_mulai' => optional($lowongan->tanggal_mulai)->format('d-m-Y'),
            'tanggal_berakhir' => optional($lowongan->tanggal_berakhir)->format('d-m-Y'),
            'status' => $lowongan->status,
            'deleted_at' => $lowongan->deleted_at ? 1 : 0,
            'kriteria' => $lowongan->kriteria->map(function ($item) {
                return [
                    'id_kriteria' => $item->id_kriteria,
                    'nama_kriteria' => $item->nama_kriteria,
                    'jenis_kriteria' => $item->jenis_kriteria,
                    'bobot' => $item->bobot,
                    'nilai_target' => $item->nilai_target,
                ];
            })->values(),
            'sub_kriteria ' => $lowongan->subKriteriaLowongan->map(function ($item) {
                return [
                    ' id ' => $item->id_sub_kriteria_lowongan,
                    ' nama_sub_kriteria ' => optional($item->subKriteria)->nama_sub_kriteria,
                    ' nilai_target ' => $item->nilai_target,
                ];
            })->values(),
        ]);
    }

    // ===================== FORM EDIT =====================
    public function edit($id)
    {
        $lowongan = $this->findOwnedLowongan($id);

        // if ($lowongan->status === 'disetujui') {
        //     return redirect()->route('perusahaan.lowongan.index')
        //         ->with('error', 'Lowongan yang sudah disetujui tidak bisa diedit');
        // }

        $lowongan->load('kriteria', 'subKriteriaLowongan.subKriteria');

        return view('admin.perusahaan.lowongan.edit', [
            'title' => 'Edit Lowongan',
            'lowongan' => $lowongan,
            'subKriteria' => $lowongan->subKriteriaLowongan
        ]);
    }

    // ===================== UPDATE =====================
    public function update(Request $request, $id)
    {
        $lowongan = $this->findOwnedLowongan($id);

        // if ($lowongan->status === 'disetujui') {
        //     return redirect()->route('perusahaan.lowongan.index')
        //         ->with('error', 'Lowongan yang sudah disetujui tidak bisa diubah');
        // }

        $messages = [
            'judul_lowongan.required' => 'Judul lowongan wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib dipilih',
            'sistem_kerja.required' => 'Sistem kerja wajib dipilih',
            'kuota.required' => 'Kuota wajib diisi',
            'kuota.integer' => 'Kuota harus berupa angka',
            'kuota.min' => 'Kuota minimal 1',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai',

            'kriteria.pengalaman.nilai_target.required' => 'Target pengalaman wajib diisi',
            'kriteria.pengalaman.nilai_target.integer' => 'Target pengalaman harus berupa angka',
            'kriteria.pengalaman.nilai_target.between' => 'Target pengalaman harus antara 1 sampai 5',

            'kriteria.pendidikan.nilai_target.required' => 'Target pendidikan wajib diisi',
            'kriteria.pendidikan.nilai_target.integer' => 'Target pendidikan harus berupa angka',
            'kriteria.pendidikan.nilai_target.between' => 'Target pendidikan harus antara 1 sampai 5',

            'kriteria.lokasi.nilai_target.required' => 'Target lokasi wajib diisi',
            'kriteria.lokasi.nilai_target.integer' => 'Target lokasi harus berupa angka',
            'kriteria.lokasi.nilai_target.between' => 'Target lokasi harus antara 1 sampai 5',

            'sub_kriteria' => 'nullable|array',
            'sub_kriteria.*.nama.required' => 'Nama skill wajib diisi',
            'sub_kriteria.*.nilai_target.required' => 'Target skill wajib diisi',
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

            'kriteria.pengalaman.nilai_target' => 'required|integer|between:1,5',
            'kriteria.pendidikan.nilai_target' => 'required|integer|between:1,5',
            'kriteria.lokasi.nilai_target' => 'required|integer|between:1,5',

            'sub_kriteria' => 'nullable|array',
            'sub_kriteria.*.nama' => 'required|string|max:150',
            'sub_kriteria.*.nilai_target' => 'required|integer|between:1,5',
        ], $messages);

        DB::transaction(function () use ($lowongan, $validated) {
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

            $this->syncCriteria($lowongan, $validated);
            $this->syncSubKriteria($lowongan, $validated);
        });

        return redirect()->route('perusahaan.lowongan.index')
            ->with('success', 'Lowongan dan kriteria berhasil diperbarui');
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
