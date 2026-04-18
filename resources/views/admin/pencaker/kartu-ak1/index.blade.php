@extends('layouts.app-admin')

@section('content')
<div class="content-wrapper">

    {{-- ================= HEADER ================= --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $title ?? 'Kartu AK1' }}</h1>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="content">
        <div class="container-fluid">

            @php
            $totalAk1 = $daftarAk1->count();
            $draftCount = $daftarAk1->where('status', 'draft')->count();
            $pendingCount = $daftarAk1->where('status', 'pending')->count();
            $approvedCount = $daftarAk1->where('status', 'disetujui')->count();
            $rejectedCount = $daftarAk1->where('status', 'ditolak')->count();
            $finishedCount = $approvedCount + $rejectedCount;
            @endphp

            {{-- ================= PROFIL RINGKAS ================= --}}
            @if($profil)
            <div class="card shadow-sm border-0 col-8 mb-4 justify-content-center mx-auto">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if($profil->foto)
                            <img src="{{ asset('storage/'.$profil->foto) }}"
                                class="rounded-circle border"
                                style="width:78px;height:78px;object-fit:cover;">
                            @else
                            <i class="fas fa-user-circle fa-5x text-secondary"></i>
                            @endif
                        </div>

                        <div class="col">
                            <h5 class="mb-1">{{ $profil->nama_lengkap }}</h5>
                            <div class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $profil->kab_kota ?? '-' }}, {{ $profil->provinsi ?? '-' }}
                            </div>

                            <div class="d-flex flex-wrap">
                                <span class="badge badge-light border mr-1 mb-1 p-2">
                                    <i class="fas fa-id-card mr-1"></i>
                                    {{ $profil->id_pencari_kerja }}
                                </span>
                                <!-- <span class="badge badge-light border mr-1 mb-1 p-2">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    {{ $totalAk1 }} Pengajuan
                                </span> -->
                                <span class="badge badge-light border mr-1 mb-1 p-2">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ $profil->nomor_hp ?? '-' }}
                                </span>
                                <span class="badge badge-light border mb-1 p-2">
                                    <i class="fas fa-envelope mr-1"></i>
                                    {{ $profil->email ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-auto mt-3 mt-md-0">
                            <a href="{{ route('pencaker.profil.edit') }}" class="btn btn-primary">
                                <i class="fas fa-user-edit mr-1"></i>
                                Perbarui Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ================= STATISTIK RINGKAS ================= --}}
            <!-- <div class="row mb-2">
                <div class="col-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <small class="text-muted d-block">Total Pengajuan</small>
                            <h4 class="mb-0">{{ $totalAk1 }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <small class="text-muted d-block">Draft</small>
                            <h4 class="mb-0">{{ $draftCount }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <small class="text-muted d-block">Pending</small>
                            <h4 class="mb-0">{{ $pendingCount }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body py-3">
                            <small class="text-muted d-block">Selesai</small>
                            <h4 class="mb-0">{{ $finishedCount }}</h4>
                            <small class="text-muted">
                                Disetujui: {{ $approvedCount }} | Ditolak: {{ $rejectedCount }}
                            </small>
                        </div>
                    </div>
                </div>
            </div> -->

            {{-- ================= LIST AK1 ================= --}}
            <div class="row">

                @forelse($daftarAk1 as $item)

                @php
                $badge = match($item->status) {
                'draft' => ['color' => 'secondary', 'icon' => 'fa-file-alt', 'label' => 'Draft'],
                'pending' => ['color' => 'warning', 'icon' => 'fa-clock', 'label' => 'Pending'],
                'disetujui' => ['color' => 'success', 'icon' => 'fa-check-circle', 'label' => 'Disetujui'],
                'ditolak' => ['color' => 'danger', 'icon' => 'fa-times-circle', 'label' => 'Ditolak'],
                default => ['color' => 'dark', 'icon' => 'fa-question-circle', 'label' => strtoupper($item->status)]
                };

                $dokumen = [
                'Foto Pas' => $item->foto_pas ?? null,
                'Scan KTP' => $item->scan_ktp ?? null,
                'Scan Ijazah'=> $item->scan_ijazah ?? null,
                'Scan KK' => $item->scan_kk ?? null,
                ];

                $dokumenFilled = collect($dokumen)->filter()->count();
                $dokumenPercent = round(($dokumenFilled / 4) * 100);
                @endphp

                <div class="col-8 mb-4 mx-auto">
                    <div class="card border-0 shadow-sm h-100">

                        {{-- HEADER --}}
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">

                                {{-- LEFT: NOMOR AK1 --}}
                                <div>
                                    <small class="text-muted d-block mb-1">
                                        Nomor AK1
                                    </small>

                                    <h5 class="mb-0 font-weight-bold text-dark">
                                        {{ $item->id_kartu_ak1 }}
                                    </h5>
                                </div>

                                {{-- RIGHT: STATUS --}}
                                <div class="text-right">

                                    <small class="text-muted d-block mb-1">
                                        Status
                                    </small>

                                    <span class="badge badge-{{ $badge['color'] }} px-3 py-2">
                                        <i class="fas {{ $badge['icon'] }} mr-1"></i>
                                        {{ $badge['label'] }}
                                    </span>

                                </div>

                            </div>
                        </div>

                        <div class="card-body">

                            {{-- INFORMASI UTAMA --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Nomor Pendaftaran</small>
                                    <div class="font-weight-bold">
                                        {{ $item->nomor_pendaftaran ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Tanggal Daftar</small>
                                    <div class="font-weight-bold">
                                        <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                        {{ $item->tanggal_daftar
                                                ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d M Y')
                                                : '-' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Berlaku Mulai</small>
                                    <div class="font-weight-bold">
                                        {{ $item->berlaku_mulai
                                                ? \Carbon\Carbon::parse($item->berlaku_mulai)->format('d M Y')
                                                : '-' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Berlaku Sampai</small>
                                    <div class="font-weight-bold">
                                        {{ $item->berlaku_sampai
                                                ? \Carbon\Carbon::parse($item->berlaku_sampai)->format('d M Y')
                                                : '-' }}
                                    </div>
                                </div>
                            </div>

                            {{-- PROGRESS DOKUMEN --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Kelengkapan Dokumen</small>
                                    <small class="font-weight-bold text-primary">
                                        {{ $dokumenFilled }}/4
                                    </small>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: {{ $dokumenPercent }}%;">
                                    </div>
                                </div>
                            </div>

                            {{-- DOKUMEN CHIP --}}
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Dokumen Terlampir</small>
                                <div class="d-flex flex-wrap">
                                    @foreach($dokumen as $label => $value)
                                    @if($value)
                                    <span class="badge badge-success mr-1 mb-1 p-2">
                                        <i class="fas fa-check mr-1"></i>{{ $label }}
                                    </span>
                                    @else
                                    <span class="badge badge-secondary mr-1 mb-1 p-2">
                                        <i class="fas fa-minus mr-1"></i>{{ $label }}
                                    </span>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            @if($item->catatan_petugas)
                            <div class="bg-white border-left border-warning rounded p-2 shadow-sm">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>

                                        <div class="font-weight-bold text-warning mb-1" style="font-size: 13px;">
                                            📝 Catatan Petugas
                                        </div>

                                        <div class="text-secondary" style="font-size: 12.5px; line-height: 1.4;">
                                            {{ $item->catatan_petugas }}
                                        </div>

                                    </div>

                                </div>

                                @if($item->nama_petugas)
                                <div class="text-muted mt-2" style="font-size: 11.5px;">
                                    — {{ $item->nama_petugas }}
                                    @if($item->nip_petugas)
                                    ({{ $item->nip_petugas }})
                                    @endif
                                </div>
                                @endif

                            </div>
                            @endif

                        </div>

                        {{-- ACTION --}}
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="row justify-content-center">
                                <!-- <div class="col-12 col-md-4 mb-2 mb-md-0">
                                    <a href="{{ url('/ak1/'.$item->id_kartu_ak1) }}"
                                        class="btn btn-outline-info btn-sm btn-block">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </div> -->

                                <div class="col-12 col-md-3 mb-2 mb-md-0">
                                    <a href="{{ route('pencaker.ak1.formulir', $item->id_kartu_ak1) }}"
                                        class="btn btn-outline-primary btn-sm btn-block">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                </div>

                                @php
                                $pendidikanCount = \App\Models\RiwayatPendidikanAk1::where(
                                'id_kartu_ak1',
                                $item->id_kartu_ak1
                                )->count();

                                $profilLengkap = $profilLengkap ?? false;

                                $kekurangan = [];

                                if (!$profilLengkap) {
                                $kekurangan[] = 'Profil belum lengkap';
                                }

                                if ($dokumenFilled < 4) {
                                    $kekurangan[]='Dokumen belum lengkap (' . $dokumenFilled . '/4)' ;
                                    }

                                    if ($pendidikanCount==0) {
                                    $kekurangan[]='Riwayat pendidikan belum diisi' ;
                                    }

                                    $bolehAjukan=empty($kekurangan);
                                    @endphp

                                    <div class="col-12 col-md-3">

                                    {{-- ❌ BELUM LENGKAP --}}
                                    @if(!$bolehAjukan)

                                    <button class="btn btn-secondary btn-sm btn-block" disabled>
                                        <i class="fas fa-ban mr-1"></i> Belum Bisa Ajukan
                                    </button>

                                    <div class="mt-2">
                                        @foreach($kekurangan as $itemKurang)
                                        <small class="text-danger d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $itemKurang }}
                                        </small>
                                        @endforeach
                                    </div>

                                    {{-- ✅ SUDAH LENGKAP --}}
                                    @else

                                    @switch($item->status)

                                    @case('pending')
                                    @if($item->status === 'pending' && $item->is_revised)
                                    <span class="badge badge-info">
                                        Menunggu Revisi
                                    </span>
                                    @elseif($item->status === 'pending')
                                    <span class="badge badge-warning">
                                        Menunggu Verifikasi
                                    </span>
                                    @endif
                                    @break

                                    @case('disetujui')
                                    <span class="badge badge-success p-2 d-block">
                                        <i class="fas fa-check mr-1"></i> Telah Disetujui
                                    </span>
                                    @break

                                    @case('ditolak')
                                    <button
                                        class="btn btn-danger btn-sm btn-block"
                                        onclick="handleAjukan('{{ url('/ak1/'.$item->id_kartu_ak1.'/submit') }}')">
                                        <i class="fas fa-redo mr-1"></i> Ajukan Ulang
                                    </button>
                                    @break

                                    @default
                                    <button
                                        class="btn btn-success btn-sm btn-block"
                                        onclick="handleAjukan('{{ url('/ak1/'.$item->id_kartu_ak1.'/submit') }}')">
                                        <i class="fas fa-paper-plane mr-1"></i> Ajukan
                                    </button>

                                    @endswitch

                                    @endif

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">Belum ada pengajuan AK1</h5>
                        <p class="text-muted mb-3">
                            Silakan buat pengajuan baru untuk mulai mengisi data AK1.
                        </p>
                        <a href="{{ route('ak1.formulir') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Buat AK1 Baru
                        </a>
                    </div>
                </div>
            </div>
            @endforelse

        </div>

</div>
</section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
@endpush