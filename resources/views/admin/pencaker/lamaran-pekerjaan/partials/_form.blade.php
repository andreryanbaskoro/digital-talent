@php
$isEdit = isset($lamaran);

// SATUKAN SUMBER DATA
$lowonganData = $lowongan ?? ($lamaran->lowongan ?? null);

// supaya select skill tetap terisi
$existingSkillValues = collect();
if ($isEdit && isset($lamaran->subKriteriaLamaran)) {
$existingSkillValues = $lamaran->subKriteriaLamaran->keyBy('id_sub_kriteria');
}

$levelText = [
1 => 'Tidak bisa',
2 => 'Dasar',
3 => 'Cukup',
4 => 'Mahir',
5 => 'Sangat ahli',
];
@endphp

@push('styles')
<style>
    .file-input-small {
        height: calc(1.5em + .55rem + 2px);
        padding: .25rem .5rem;
        font-size: .875rem;
    }
</style>
@endpush

<div class="card shadow-sm border-0 rounded-lg">

    <form action="{{ $isEdit ? route('pencaker.lamaran.update', $lamaran->id_lamaran) : route('pencaker.lamaran.store') }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="card-body pt-4">
            <div class="row">

                <div class="col-md-12 mb-3">
                    <div class="p-3 bg-light rounded border">
                        <h5 class="mb-1 text-dark font-weight-bold">
                            <i class="fas fa-info-circle text-primary mr-1"></i>
                            Informasi Lamaran
                        </h5>
                        <small class="text-muted">
                            Data di bawah bersifat otomatis dan tidak bisa diedit.
                        </small>
                    </div>
                </div>

                {{-- ================= ID LAMARAN ================= --}}
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label class="text-muted small mb-1">ID Lamaran</label>
                        <input type="text"
                            class="form-control shadow-sm rounded-lg bg-white font-weight-bold text-primary"
                            value="{{ $isEdit ? $lamaran->id_lamaran : ($previewId ?? '-') }}"
                            readonly>
                    </div>
                </div>

                {{-- ================= LOWONGAN ================= --}}
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label class="text-muted small mb-1">Lowongan</label>
                        <input type="text"
                            class="form-control shadow-sm rounded-lg bg-white"
                            value="{{ $lowonganData->judul_lowongan ?? ($lamaran->lowongan->judul_lowongan ?? '-') }}"
                            readonly>

                        <input type="hidden"
                            name="id_lowongan"
                            value="{{ $lowonganData->id_lowongan ?? ($lamaran->id_lowongan ?? '') }}">
                    </div>
                </div>

                {{-- ================= TANGGAL ================= --}}
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label class="text-muted small mb-1">Tanggal Lamar</label>
                        <input type="text"
                            class="form-control shadow-sm rounded-lg bg-white"
                            value="{{ $isEdit && $lamaran->tanggal_lamar
                                ? $lamaran->tanggal_lamar->format('d-m-Y')
                                : now()->format('d-m-Y') }}"
                            readonly>
                    </div>
                </div>

                {{-- ================= STATUS ================= --}}
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label class="text-muted small mb-1">Status Lamaran</label>
                        <div class="pt-1">
                            @if($isEdit)
                            @php
                            $statusColor = match($lamaran->status_lamaran) {
                            'dikirim' => 'primary',
                            'diproses' => 'warning',
                            'diterima' => 'success',
                            'ditolak' => 'danger',
                            default => 'secondary'
                            };
                            @endphp

                            <span class="badge badge-{{ $statusColor }} px-3 py-2 rounded-pill">
                                {{ ucfirst($lamaran->status_lamaran) }}
                            </span>
                            @else
                            <span class="badge badge-secondary px-3 py-2 rounded-pill">
                                Belum Dikirim
                            </span>
                            <small class="d-block text-muted mt-2">
                                Status akan berubah menjadi <b>Dikirim</b> setelah lamaran dikirim.
                            </small>
                            @endif
                        </div>
                    </div>
                </div>


                {{-- ================= WRAPPER 2 KOLOM ================= --}}
                <div class="col-md-12">
                    <div class="row">

                        {{-- ================= KIRI: SKILL ================= --}}
                        <div class="col-lg-6 mt-3">
                            <div class="card shadow-sm border-0 h-100 rounded-lg">
                                <div class="card-header bg-white border-bottom">
                                    <strong class="text-dark">
                                        <i class="fas fa-brain text-primary mr-1"></i>
                                        Skill Kamu
                                    </strong>
                                </div>

                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead class="text-center bg-light">
                                                <tr>
                                                    <th>Skill</th>
                                                    <th width="40%">Level</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse($lowonganData->subKriteriaLowongan as $i => $item)
                                                @php
                                                $selectedNilai = old(
                                                "sub_kriteria.$i.nilai",
                                                $isEdit ? optional($existingSkillValues->get($item->id_sub_kriteria))->nilai : null
                                                );
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <strong>{{ $item->subKriteria->nama_sub_kriteria ?? '-' }}</strong>
                                                        <input type="hidden"
                                                            name="sub_kriteria[{{ $i }}][id_sub_kriteria]"
                                                            value="{{ $item->id_sub_kriteria }}">
                                                    </td>

                                                    <td>
                                                        <select name="sub_kriteria[{{ $i }}][nilai]"
                                                            class="form-control form-control-sm shadow-sm rounded-lg"
                                                            required>
                                                            <option value="">-- Pilih --</option>
                                                            @for ($j = 1; $j <= 5; $j++)
                                                                <option value="{{ $j }}"
                                                                {{ (string)$selectedNilai === (string)$j ? 'selected' : '' }}>
                                                                {{ $j }} - {{ $levelText[$j] }}
                                                                </option>
                                                                @endfor
                                                        </select>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted py-4">
                                                        Tidak ada skill pada lowongan ini.
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ================= KANAN: DOKUMEN ================= --}}
                        <div class="col-lg-6 mt-3">
                            <div class="card shadow-sm border-0 h-100 rounded-lg">
                                <div class="card-header bg-white border-bottom">
                                    <strong class="text-dark">
                                        <i class="fas fa-folder-open text-primary mr-1"></i>
                                        Dokumen Lamaran
                                    </strong>
                                </div>

                                <div class="card-body p-3">

                                    <div class="alert alert-info py-2 mb-3">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Upload dokumen sesuai persyaratan lowongan.
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead class="text-center bg-light">
                                                <tr>
                                                    <th>Jenis Dokumen</th>
                                                    <th>File</th>
                                                    <th style="width: 90px;">Status</th>
                                                </tr>
                                            </thead>

                                            <tbody id="dokumen-wrapper">

                                                {{-- EDIT MODE --}}
                                                @if($isEdit && $lamaran->dokumen->count())
                                                @foreach($lamaran->dokumen as $dok)
                                                @php
                                                $standardDocs = ['cv','surat_lamaran','ktp','ijazah','transkrip','sertifikat','foto'];
                                                $isCustom = !in_array($dok->jenis_dokumen, $standardDocs);
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="id_dokumen_existing[]" value="{{ $dok->id_dokumen }}">

                                                        <select name="jenis_dokumen_existing[]" class="form-control form-control-sm rounded-lg doc-type-select">
                                                            <option value="" disabled>-- Pilih Dokumen --</option>

                                                            @foreach($standardDocs as $jenis)
                                                            <option value="{{ $jenis }}"
                                                                {{ !$isCustom && $dok->jenis_dokumen == $jenis ? 'selected' : '' }}>
                                                                {{ strtoupper(str_replace('_', ' ', $jenis)) }}
                                                            </option>
                                                            @endforeach

                                                            <option value="lainnya" {{ $isCustom ? 'selected' : '' }}>
                                                                LAINNYA
                                                            </option>
                                                        </select>

                                                        <input type="text"
                                                            name="jenis_dokumen_custom_existing[]"
                                                            class="form-control form-control-sm mt-2 custom-doc-input"
                                                            placeholder="Nama dokumen custom..."
                                                            value="{{ $isCustom ? $dok->jenis_dokumen : '' }}"
                                                            style="{{ $isCustom ? 'display:block;' : 'display:none;' }}">
                                                    </td>

                                                    <td>
                                                        <a href="{{ asset('storage/'.$dok->lokasi_file) }}"
                                                            target="_blank"
                                                            class="btn btn-link btn-sm p-0">
                                                            <i class="fas fa-eye mr-1"></i>Lihat
                                                        </a>
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="badge badge-success rounded-pill px-3 py-2">OK</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif

                                                {{-- INPUT UPLOAD BARU --}}
                                                <tr class="dokumen-row">
                                                    <td>
                                                        <select name="jenis_dokumen[]" class="form-control form-control-sm rounded-lg doc-type-select">
                                                            <option value="">-- Pilih Dokumen --</option>
                                                            <option value="cv">CV</option>
                                                            <option value="surat_lamaran">Surat Lamaran</option>
                                                            <option value="ktp">KTP</option>
                                                            <option value="ijazah">Ijazah</option>
                                                            <option value="transkrip">Transkrip</option>
                                                            <option value="sertifikat">Sertifikat</option>
                                                            <option value="foto">Pas Foto</option>
                                                            <option value="lainnya">LAINNYA</option>
                                                        </select>

                                                        <input type="text"
                                                            name="jenis_dokumen_custom[]"
                                                            class="form-control form-control-sm mt-2 custom-doc-input"
                                                            placeholder="Nama dokumen custom..."
                                                            style="display:none;">
                                                    </td>

                                                    <td>
                                                        <input type="file"
                                                            name="lokasi_file[]"
                                                            class="form-control form-control-sm file-input-small rounded-lg"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="text-muted small">baru</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-right mt-3">
                                        <button type="button"
                                            class="btn btn-success btn-sm px-3 rounded-pill"
                                            id="add-row">
                                            <i class="fas fa-plus mr-1"></i> Tambah Dokumen
                                        </button>
                                    </div>

                                    <small class="text-muted d-block mt-2">
                                        * Kamu bisa upload lebih dari 1 dokumen
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= FOOTER ACTION ================= --}}
        <div class="card-footer bg-white border-0 pb-4">
            <div class="d-flex justify-content-end align-items-center flex-wrap">
                <a href="{{ route('pencaker.lamaran.index') }}"
                    class="btn btn-outline-secondary btn-kembali btn-sm mr-2">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>

                <button type="button"
                    class="btn btn-primary btn-submit btn-sm">
                    <i class="fas fa-paper-plane mr-1"></i>
                    {{ $isEdit ? 'Update Lamaran' : 'Kirim Lamaran' }}
                </button>
            </div>
        </div>
    </form>
</div>