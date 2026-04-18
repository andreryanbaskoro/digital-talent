@extends('layouts.app-landing')

@include('partials.landing.hero')

@section('content')

{{-- ==================== JOB LISTING ==================== --}}
{{-- Active Filters Display --}}
@if(request()->hasAny(['keyword','lokasi','jenis_pekerjaan']))
<div class="flex flex-wrap gap-2 mb-6">
    @if(request('keyword'))
    <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary text-xs font-medium px-3 py-1.5 rounded-full">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        {{ request('keyword') }}
    </span>
    @endif
    @if(request('lokasi'))
    <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-700 text-xs font-medium px-3 py-1.5 rounded-full">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
        </svg>
        {{ request('lokasi') }}
    </span>
    @endif
    @if(request('jenis_pekerjaan'))
    <span class="inline-flex items-center gap-1.5 bg-orange-100 text-orange-700 text-xs font-medium px-3 py-1.5 rounded-full">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        {{ request('jenis_pekerjaan') }}
    </span>
    @endif
</div>
@endif

{{-- Grid --}}
@if($lowongan->isNotEmpty())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($lowongan as $item)
    @php
    $perusahaan = $item->profilPerusahaan;

    // Badge colors
    $jenisColor = match(strtolower($item->jenis_pekerjaan ?? '')) {
    'full-time', 'full time' => 'bg-blue-100 text-blue-700',
    'part-time', 'part time' => 'bg-purple-100 text-purple-700',
    'freelance' => 'bg-amber-100 text-amber-700',
    'kontrak' => 'bg-orange-100 text-orange-700',
    'magang', 'internship' => 'bg-teal-100 text-teal-700',
    default => 'bg-gray-100 text-gray-600',
    };
    $sistemColor = match(strtolower($item->sistem_kerja ?? '')) {
    'remote' => 'bg-emerald-100 text-emerald-700',
    'hybrid' => 'bg-violet-100 text-violet-700',
    'on-site', 'onsite', 'wfo' => 'bg-rose-100 text-rose-700',
    default => 'bg-gray-100 text-gray-600',
    };
    @endphp

    <article class="job-card bg-white rounded-2xl border border-gray-200 p-5 flex flex-col gap-4 hover:border-primary/40">

        {{-- Company Header --}}
        <div class="flex items-start gap-3">
            {{-- Logo / Inisial --}}
            @if($perusahaan && $perusahaan->logo)
            <img src="{{ asset('storage/' . $perusahaan->logo) }}"
                alt="{{ $perusahaan->nama_perusahaan }}"
                class="h-12 w-12 rounded-xl object-cover border border-gray-100 flex-shrink-0">
            @else
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary/20 to-accent/20 flex items-center justify-center flex-shrink-0">
                <span class="text-primary font-bold text-lg">
                    {{ strtoupper(substr($perusahaan->nama_perusahaan ?? 'P', 0, 1)) }}
                </span>
            </div>
            @endif

            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2">
                    {{ $item->judul_lowongan }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 truncate">
                    {{ $perusahaan->nama_perusahaan ?? '–' }}
                </p>
            </div>
        </div>

        {{-- Badges --}}
        <div class="flex flex-wrap gap-2">

            @if($item->jenis_pekerjaan)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $jenisColor }} transition hover:scale-105">

                {{-- ICON BRIEFCASE --}}
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V7a2 2 0 00-2-2h-3V4a2 2 0 00-2-2h-2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v6m16 0a22 22 0 01-16 0m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5" />
                </svg>

                {{ $item->jenis_pekerjaan }}
            </span>
            @endif


            @if($item->sistem_kerja)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $sistemColor }} transition hover:scale-105">

                {{-- ICON MONITOR / REMOTE --}}
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2" />
                </svg>

                {{ $item->sistem_kerja }}
            </span>
            @endif

        </div>

        {{-- Details --}}
        <div class="space-y-1.5 text-xs text-gray-500">
            {{-- Lokasi --}}
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                <span class="truncate">{{ $item->lokasi ?? '–' }}</span>
            </div>
            {{-- Gaji --}}
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @if($item->gaji_minimum && $item->gaji_maksimum)
                <span class="font-medium text-gray-700">
                    Rp {{ number_format($item->gaji_minimum, 0, ',', '.') }}
                    – Rp {{ number_format($item->gaji_maksimum, 0, ',', '.') }}
                </span>
                @elseif($item->gaji_minimum)
                <span class="font-medium text-gray-700">Rp {{ number_format($item->gaji_minimum, 0, ',', '.') }}+</span>
                @else
                <span class="italic">Negosiasi</span>
                @endif
            </div>
            {{-- Tanggal --}}
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ $item->created_at ? $item->created_at->diffForHumans() : '–' }}</span>
            </div>
        </div>

        {{-- CTA --}}
        <div class="mt-auto pt-2 border-t border-gray-100">
            <a href="{{ route('landing.lowongan.detail', $item->id_lowongan) }}"
                class="w-full inline-flex items-center justify-center gap-1.5 bg-primary/5 hover:bg-primary text-primary hover:text-white border border-primary/20 hover:border-transparent text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200"
                id="btn-lihat-detail-{{ $item->id_lowongan }}">
                Lihat Detail
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </article>
    @endforeach
</div>

{{-- Pagination --}}
@if($lowongan->hasPages())
<div class="mt-10 flex justify-center">
    <nav class="flex items-center gap-1" aria-label="Pagination">
        {{-- Previous --}}
        @if($lowongan->onFirstPage())
        <span class="px-3 py-2 rounded-xl text-sm text-gray-400 cursor-not-allowed select-none">&lsaquo;</span>
        @else
        <a href="{{ $lowongan->previousPageUrl() }}" class="px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-primary hover:text-white transition-all" id="pagination-prev">&lsaquo;</a>
        @endif

        @foreach($lowongan->getUrlRange(max(1, $lowongan->currentPage()-2), min($lowongan->lastPage(), $lowongan->currentPage()+2)) as $page => $url)
        @if($page == $lowongan->currentPage())
        <span class="px-3.5 py-2 rounded-xl text-sm font-bold bg-primary text-white shadow">{{ $page }}</span>
        @else
        <a href="{{ $url }}" class="px-3.5 py-2 rounded-xl text-sm text-gray-600 hover:bg-gray-100 transition-all">{{ $page }}</a>
        @endif
        @endforeach

        {{-- Next --}}
        @if($lowongan->hasMorePages())
        <a href="{{ $lowongan->nextPageUrl() }}" class="px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-primary hover:text-white transition-all" id="pagination-next">&rsaquo;</a>
        @else
        <span class="px-3 py-2 rounded-xl text-sm text-gray-400 cursor-not-allowed select-none">&rsaquo;</span>
        @endif
    </nav>
</div>
@endif

@else

{{-- ==================== EMPTY STATE ==================== --}}
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="h-24 w-24 rounded-3xl bg-gray-100 flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h3 class="text-xl font-bold text-gray-800 mb-2">Lowongan Tidak Ditemukan</h3>
    <p class="text-gray-500 max-w-sm mb-6">
        @if(request()->hasAny(['keyword','lokasi','jenis_pekerjaan']))
        Tidak ada lowongan yang cocok dengan filter pencarian kamu saat ini.
        @else
        Belum ada lowongan tersedia saat ini. Silakan cek kembali nanti.
        @endif
    </p>
    @if(request()->hasAny(['keyword','lokasi','jenis_pekerjaan']))
    <a href="/" class="inline-flex items-center gap-2 bg-primary text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-primary-600 transition-all shadow">
        Lihat Semua Lowongan
    </a>
    @endif
</div>

@endif

@endsection