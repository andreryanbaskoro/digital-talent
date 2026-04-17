<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Digital Talent Hub – Temukan ribuan lowongan pekerjaan terbaik di seluruh Indonesia. Cari pekerjaan impianmu sekarang.">
    <title>Digital Talent Hub – Temukan Pekerjaan Impianmu</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via CDN (aman untuk landing page tanpa build step) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { inter: ['Inter', 'sans-serif'] },
                    colors: {
                        primary:  { DEFAULT: '#4F46E5', 50: '#F0F0FE', 100: '#E0E0FD', 200: '#C9C8FC', 300: '#A9A7F9', 400: '#8582F5', 500: '#4F46E5', 600: '#4338CA', 700: '#3730A3', 800: '#312E81', 900: '#1E1B4B' },
                        accent:   { DEFAULT: '#7C3AED', light: '#A78BFA' },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp .6s ease both',
                        'pulse-slow': 'pulse 3s infinite',
                    },
                    keyframes: {
                        fadeInUp: { '0%': { opacity: '0', transform: 'translateY(24px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Gradient blobs */
        .blob { position: absolute; border-radius: 9999px; filter: blur(80px); opacity: .35; pointer-events: none; }

        /* hero gradient */
        .hero-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #312e81 100%); }

        /* glass card */
        .glass { background: rgba(255,255,255,.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,.15); }

        /* job card hover */
        .job-card { transition: transform .25s ease, box-shadow .25s ease; }
        .job-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(79,70,229,.15); }

        /* badge */
        .badge { @apply inline-flex items-center gap-1 text-xs font-medium px-2.5 py-0.5 rounded-full; }

        /* scrollbar thin */
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: #f1f1f1; } ::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

{{-- ==================== NAVBAR ==================== --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/60 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <span class="font-bold text-lg text-gray-900">Digital <span class="text-primary">Talent Hub</span></span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="#lowongan" class="hover:text-primary transition-colors">Lowongan</a>
                <a href="#tentang" class="hover:text-primary transition-colors">Tentang</a>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                    Masuk
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all shadow hover:shadow-primary/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Daftar / Masuk
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- ==================== HERO SECTION ==================== --}}
<section class="hero-gradient relative overflow-hidden pt-24 pb-20">
    {{-- Decorative blobs --}}
    <div class="blob w-[600px] h-[600px] bg-purple-600 top-[-200px] left-[-100px]"></div>
    <div class="blob w-[400px] h-[400px] bg-indigo-500 bottom-[-150px] right-[-50px]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        {{-- Badge --}}
        <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white/90 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 backdrop-blur-sm animate-fade-in-up">
            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse-slow"></span>
            Platform Karir #1 di Indonesia
        </span>

        {{-- Headline --}}
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-5 animate-fade-in-up" style="animation-delay:.1s">
            Temukan Pekerjaan<br>
            <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">Impianmu</span>
        </h1>

        <p class="text-white/70 text-lg sm:text-xl max-w-2xl mx-auto mb-10 animate-fade-in-up" style="animation-delay:.2s">
            Ribuan lowongan dari perusahaan terpercaya menunggu kamu. Temukan peluang karir terbaik, apply sekarang.
        </p>

        {{-- Search Bar --}}
        <form action="/" method="GET" class="animate-fade-in-up" style="animation-delay:.3s">
            <div class="glass max-w-3xl mx-auto rounded-2xl p-2 flex flex-col sm:flex-row gap-2">
                {{-- Keyword --}}
                <div class="flex-1 flex items-center gap-2 bg-white/10 rounded-xl px-4 py-2.5">
                    <svg class="w-5 h-5 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="Judul pekerjaan, posisi..."
                        class="flex-1 bg-transparent text-white placeholder-white/50 text-sm focus:outline-none"
                        id="landing-search-keyword"
                    >
                </div>

                {{-- Lokasi --}}
                <div class="flex-1 flex items-center gap-2 bg-white/10 rounded-xl px-4 py-2.5">
                    <svg class="w-5 h-5 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input
                        type="text"
                        name="lokasi"
                        value="{{ request('lokasi') }}"
                        placeholder="Lokasi kerja..."
                        class="flex-1 bg-transparent text-white placeholder-white/50 text-sm focus:outline-none"
                        id="landing-search-lokasi"
                    >
                </div>

                {{-- Submit --}}
                <button type="submit" id="btn-cari-lowongan" class="bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-semibold text-sm px-7 py-2.5 rounded-xl transition-all shadow-lg shadow-primary/30 whitespace-nowrap">
                    Cari Lowongan
                </button>
            </div>
        </form>

        {{-- Stats --}}
        <div class="mt-10 flex flex-wrap justify-center gap-6 animate-fade-in-up" style="animation-delay:.4s">
            @php $totalAktif = \App\Models\LowonganPekerjaan::where('status','aktif')->whereNull('deleted_at')->count(); @endphp
            <div class="text-center">
                <p class="text-2xl font-bold text-white">{{ number_format($totalAktif) }}+</p>
                <p class="text-white/60 text-sm">Lowongan Aktif</p>
            </div>
            <div class="h-10 w-px bg-white/20 self-center hidden sm:block"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-white">{{ number_format(\App\Models\ProfilPerusahaan::whereNull('deleted_at')->count()) }}+</p>
                <p class="text-white/60 text-sm">Perusahaan</p>
            </div>
            <div class="h-10 w-px bg-white/20 self-center hidden sm:block"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-white">100%</p>
                <p class="text-white/60 text-sm">Gratis Digunakan</p>
            </div>
        </div>
    </div>
</section>

{{-- ==================== FILTER BAR ==================== --}}
<div id="lowongan" class="sticky top-16 z-40 bg-white border-b border-gray-200 shadow-sm">
    <form action="/" method="GET">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap gap-3 items-center">
            {{-- Keep keyword & lokasi from hero search --}}
            @if(request('keyword'))
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            @endif
            @if(request('lokasi'))
                <input type="hidden" name="lokasi" value="{{ request('lokasi') }}">
            @endif

            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Filter:</span>

            {{-- Filter Jenis Pekerjaan --}}
            <select name="jenis_pekerjaan" id="filter-jenis-pekerjaan" onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer">
                <option value="">Semua Jenis</option>
                @foreach($daftarJenis as $jenis)
                    <option value="{{ $jenis }}" {{ request('jenis_pekerjaan') == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Lokasi (dropdown) --}}
            @if(request()->hasAny(['keyword','jenis_pekerjaan','lokasi']))
                <a href="/" class="inline-flex items-center gap-1.5 text-sm text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 rounded-xl px-3 py-2 transition-colors" id="btn-reset-filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset Filter
                </a>
            @endif

            <span class="ml-auto text-sm text-gray-500">
                <span class="font-semibold text-gray-800">{{ $lowongan->total() }}</span> lowongan ditemukan
            </span>
        </div>
    </form>
</div>


{{-- ==================== ABOUT SECTION ==================== --}}
<section id="tentang" class="bg-white border-t border-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-primary mb-3 block">Tentang Kami</span>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Jembatan Antara Talenta &amp; Perusahaan</h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Digital Talent Hub adalah platform rekrutmen yang menghubungkan pencari kerja berbakat dengan perusahaan-perusahaan terkemuka. Kami berkomitmen untuk mempermudah proses rekrutmen yang transparan, efisien, dan inklusif.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Lowongan Terverifikasi'],
                        ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'label' => 'Data Aman & Terlindungi'],
                        ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'Proses Cepat & Mudah'],
                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Komunitas Profesional'],
                    ] as $feat)
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-primary/5 transition-colors">
                        <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feat['icon'] }}"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">{{ $feat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-accent/30 rounded-3xl blur-2xl"></div>
                    <div class="relative h-72 w-72 rounded-3xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-2xl">
                        <svg class="w-36 h-36 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== CTA SECTION ==================== --}}
<section class="hero-gradient py-16 relative overflow-hidden">
    <div class="blob w-[400px] h-[400px] bg-purple-500 top-[-100px] right-0 opacity-20"></div>
    <div class="relative max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-extrabold text-white mb-4">Siap Memulai Karirmu?</h2>
        <p class="text-white/70 mb-8">Daftarkan dirimu sekarang dan temukan ribuan peluang karir yang menanti.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white text-primary font-bold text-sm px-8 py-3.5 rounded-2xl hover:shadow-xl transition-all shadow-lg" id="cta-daftar-sekarang">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Daftar Sekarang
            </a>
            <a href="#lowongan" class="inline-flex items-center gap-2 glass text-white font-semibold text-sm px-8 py-3.5 rounded-2xl hover:bg-white/20 transition-all">
                Lihat Lowongan
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ==================== FOOTER ==================== --}}
<footer class="bg-gray-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <span class="font-bold text-white">Digital Talent Hub</span>
            </div>
            <p class="text-gray-500 text-sm text-center">
                &copy; {{ date('Y') }} Digital Talent Hub. All rights reserved.
            </p>
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a>
                <span class="text-gray-700">·</span>
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Daftar</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
