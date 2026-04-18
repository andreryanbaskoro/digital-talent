@extends('layouts.app-landing')

@section('content')

@php
$levelText = [
1 => 'Tidak bisa',
2 => 'Dasar',
3 => 'Cukup',
4 => 'Mahir',
5 => 'Sangat ahli'
];
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('landing') }}" class="hover:text-primary transition">Beranda</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Detail Lowongan</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                @php
                $perusahaan = $lowongan->profilPerusahaan;
                $jenisColor = match(strtolower($lowongan->jenis_pekerjaan ?? '')) {
                'full-time', 'full time' => 'bg-blue-100 text-blue-700',
                'part-time', 'part time' => 'bg-purple-100 text-purple-700',
                'freelance' => 'bg-amber-100 text-amber-700',
                'kontrak' => 'bg-orange-100 text-orange-700',
                'magang', 'internship' => 'bg-teal-100 text-teal-700',
                default => 'bg-gray-100 text-gray-600',
                };
                $sistemColor = match(strtolower($lowongan->sistem_kerja ?? '')) {
                'remote' => 'bg-emerald-100 text-emerald-700',
                'hybrid' => 'bg-violet-100 text-violet-700',
                'on-site', 'onsite', 'wfo' => 'bg-rose-100 text-rose-700',
                default => 'bg-gray-100 text-gray-600',
                };
                @endphp

                <div class="flex items-start gap-4">
                    @if($perusahaan && $perusahaan->logo)
                    <img src="{{ asset('storage/' . $perusahaan->logo) }}"
                        alt="{{ $perusahaan->nama_perusahaan }}"
                        class="h-16 w-16 rounded-2xl object-cover border border-gray-100 flex-shrink-0">
                    @else
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-primary/20 to-accent/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-primary font-bold text-2xl">
                            {{ strtoupper(substr($perusahaan->nama_perusahaan ?? 'P', 0, 1)) }}
                        </span>
                    </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900 leading-tight">
                            {{ $lowongan->judul_lowongan }}
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $perusahaan->nama_perusahaan ?? '-' }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-4">

                            {{-- JENIS PEKERJAAN --}}
                            @if($lowongan->jenis_pekerjaan)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $jenisColor }} shadow-sm hover:shadow transition">

                                {{-- ICON BRIEFCASE --}}
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5z"
                                        clip-rule="evenodd" />
                                </svg>

                                {{ $lowongan->jenis_pekerjaan }}
                            </span>
                            @endif


                            {{-- SISTEM KERJA --}}
                            @if($lowongan->sistem_kerja)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $sistemColor }} shadow-sm hover:shadow transition">

                                {{-- ICON MONITOR --}}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2" />
                                </svg>

                                {{ $lowongan->sistem_kerja }}
                            </span>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Deskripsi Pekerjaan</h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($lowongan->deskripsi ?? 'Belum ada deskripsi lowongan.')) !!}
                </div>
            </div>

            {{-- Kriteria / Persyaratan --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">
                        Kriteria / Persyaratan
                    </h2>

                    {{-- badge jumlah skill --}}
                    @if($lowongan->subKriteriaLowongan)
                    <span class="text-xs px-2.5 py-1 rounded-full bg-primary/10 text-primary font-semibold">
                        {{ $lowongan->subKriteriaLowongan->count() }} Skill
                    </span>
                    @endif
                </div>

                {{-- ================= SKILL ================= --}}
                @if($lowongan->subKriteriaLowongan && $lowongan->subKriteriaLowongan->count() > 0)

                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    {{-- icon --}}
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                    Skill yang Dibutuhkan
                </h3>

                <div class="grid sm:grid-cols-2 gap-3">

                    @foreach($lowongan->subKriteriaLowongan as $skill)

                    <div class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-primary/30 hover:shadow transition-all duration-200">

                        {{-- LEFT --}}
                        <div class="flex items-center gap-3">

                            {{-- ICON BOX --}}
                            <div class="h-8 w-8 flex items-center justify-center rounded-lg bg-green-100 text-green-600 group-hover:scale-110 transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11.3 1L1 11h6l-1 8 10.3-10h-6l1-8z" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-800 leading-tight">
                                    {{ $skill->subKriteria->nama_sub_kriteria ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- RIGHT (LEVEL) --}}
                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                @switch($skill->nilai_target)
                    @case(5) bg-green-100 text-green-700 @break
                    @case(4) bg-blue-100 text-blue-700 @break
                    @case(3) bg-yellow-100 text-yellow-700 @break
                    @case(2) bg-orange-100 text-orange-700 @break
                    @default bg-gray-100 text-gray-600
                @endswitch
            ">
                            {{ $levelText[$skill->nilai_target] ?? '-' }}
                        </span>

                    </div>

                    @endforeach

                </div>

                @else

                {{-- EMPTY STATE (lebih bagus dari text doang) --}}
                <div class="text-center py-6">
                    <div class="mx-auto w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 mb-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 9h.008v.008H9.75V9zm4.5 0h.008v.008h-.008V9zM12 17c-3.866 0-7-1.567-7-3.5S8.134 10 12 10s7 1.567 7 3.5S15.866 17 12 17z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada skill yang ditambahkan</p>
                </div>

                @endif

            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">

                {{-- HEADER --}}
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                    Informasi Lowongan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    {{-- LOKASI --}}
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-white border border-transparent hover:border-primary/20 hover:shadow transition">
                        <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-red-100 text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Lokasi</p>
                            <p class="font-semibold text-gray-800">{{ $lowongan->lokasi ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- PENDIDIKAN --}}
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-white border border-transparent hover:border-primary/20 hover:shadow transition">
                        <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zM12 14v7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Pendidikan Minimum</p>
                            <p class="font-semibold text-gray-800">{{ $lowongan->pendidikan_minimum ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- PENGALAMAN --}}
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-white border border-transparent hover:border-primary/20 hover:shadow transition">
                        <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-green-100 text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Pengalaman Minimum</p>
                            <p class="font-semibold text-gray-800">{{ $lowongan->pengalaman_minimum ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- KUOTA --}}
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-white border border-transparent hover:border-primary/20 hover:shadow transition">
                        <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Kuota</p>
                            <p class="font-semibold text-gray-800">
                                {{ $lowongan->kuota ? $lowongan->kuota . ' orang' : '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- TANGGAL MULAI --}}
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-white border border-transparent hover:border-primary/20 hover:shadow transition">
                        <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Tanggal Mulai</p>
                            <p class="font-semibold text-gray-800">
                                {{ $lowongan->tanggal_mulai ? \Carbon\Carbon::parse($lowongan->tanggal_mulai)->format('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- TANGGAL BERAKHIR --}}
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-white border border-transparent hover:border-primary/20 hover:shadow transition">
                        <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Tanggal Berakhir</p>
                            <p class="font-semibold text-gray-800">
                                {{ $lowongan->tanggal_berakhir ? \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->format('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Ringkasan --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan</h2>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Gaji</p>
                        <p class="font-semibold text-gray-800">
                            @if($lowongan->gaji_minimum && $lowongan->gaji_maksimum)
                            Rp {{ number_format($lowongan->gaji_minimum, 0, ',', '.') }}
                            - Rp {{ number_format($lowongan->gaji_maksimum, 0, ',', '.') }}
                            @elseif($lowongan->gaji_minimum)
                            Rp {{ number_format($lowongan->gaji_minimum, 0, ',', '.') }}+
                            @else
                            Negosiasi
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Perusahaan</p>
                        <p class="font-semibold text-gray-800">{{ $perusahaan->nama_perusahaan ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Lokasi</p>
                        <p class="font-semibold text-gray-800">{{ $lowongan->lokasi ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Diposting</p>
                        <p class="font-semibold text-gray-800">
                            {{ $lowongan->created_at ? $lowongan->created_at->diffForHumans() : '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">

                    @auth
                    @php $user = Auth::user(); @endphp

                    {{-- ================= PENCARI KERJA ================= --}}
                    @if($user->peran === 'pencaker')
                    <a href="#"
                        class="w-full inline-flex items-center justify-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-3 rounded-xl hover:bg-primary-600 transition-all shadow">

                        {{-- ICON LAMAR --}}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>

                        Lamar Sekarang
                    </a>
                    @else
                    {{-- selain pencaker --}}
                    <div class="w-full text-center text-sm text-gray-400 italic">
                        Hanya pencari kerja yang dapat melamar
                    </div>
                    @endif

                    @else
                    {{-- BELUM LOGIN --}}
                    <a href="{{ route('login') }}"
                        class="w-full inline-flex items-center justify-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-3 rounded-xl hover:bg-primary-600 transition-all shadow">

                        {{-- ICON LOGIN --}}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        Login untuk Melamar
                    </a>
                    @endauth

                    {{-- BACK --}}
                    <a href="{{ route('landing') }}"
                        class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-3 rounded-xl hover:bg-gray-200 transition-all">

                        {{-- ICON BACK --}}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>

                        Kembali ke Daftar Lowongan
                    </a>

                </div>
            </div>

            {{-- Info Perusahaan --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Profil Perusahaan</h2>
                <div class="space-y-3 text-sm text-gray-600">
                    <p><span class="font-semibold text-gray-800">Nama:</span> {{ $perusahaan->nama_perusahaan ?? '-' }}</p>
                    <p><span class="font-semibold text-gray-800">Alamat:</span> {{ $perusahaan->alamat ?? '-' }}</p>
                    <p><span class="font-semibold text-gray-800">Telepon:</span> {{ $perusahaan->telepon ?? '-' }}</p>
                    <p><span class="font-semibold text-gray-800">Email:</span> {{ $perusahaan->email ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection