{{-- ==================== HERO SECTION ==================== --}}
<section class="hero-gradient relative overflow-hidden pt-24 pb-20">
    {{-- Decorative blobs --}}
    <div class="blob w-[600px] h-[600px] bg-purple-600 top-[-200px] left-[-100px]"></div>
    <div class="blob w-[400px] h-[400px] bg-indigo-500 bottom-[-150px] right-[-50px]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        {{-- Badge --}}
        <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white/90 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 backdrop-blur-sm animate-fade-in-up">
            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse-slow"></span>
            Sistem Dinas Ketenagakerjaan Kota Jayapura – Profile Matching Recruitment System
        </span>

        {{-- Headline --}}
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-5 animate-fade-in-up" style="animation-delay:.1s">
            Penempatan Kerja<br>
            <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">
                Berbasis Kompetensi
            </span>
        </h1>

        <p class="text-white/70 text-lg sm:text-xl max-w-2xl mx-auto mb-10 animate-fade-in-up" style="animation-delay:.2s">
            Sistem ini menghubungkan pencari kerja, perusahaan, dan Dinas Ketenagakerjaan Kota Jayapura menggunakan metode
            <span class="font-semibold text-white">Profile Matching</span>
            untuk menghasilkan seleksi yang objektif, terukur, dan transparan.
        </p>

        @include('partials.landing.search')

        {{-- Stats --}}
        <div class="mt-10 flex flex-wrap justify-center gap-6 animate-fade-in-up" style="animation-delay:.4s">

            {{-- PERUSAHAAN --}}
            <div class="text-center">
                <p class="text-2xl font-bold text-white">
                    {{ number_format($totalPerusahaan) }}+
                </p>
                <p class="text-white/60 text-sm">Perusahaan Mitra</p>
            </div>

            <div class="h-10 w-px bg-white/20 self-center hidden sm:block"></div>

            {{-- LOWONGAN --}}
            <div class="text-center">
                <p class="text-2xl font-bold text-white">
                    {{ number_format($totalLowongan) }}+
                </p>
                <p class="text-white/60 text-sm">Lowongan Tersedia</p>

                <div class="flex items-center justify-center gap-1 mt-2 text-xs text-white/60">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    <span>{{ $totalHariIni }} lowongan terbaru hari ini</span>
                </div>
            </div>

            <div class="h-10 w-px bg-white/20 self-center hidden sm:block"></div>

            {{-- SISTEM --}}
            <div class="text-center">
                <p class="text-2xl font-bold text-white">SPK</p>
                <p class="text-white/60 text-sm">Profile Matching System</p>
            </div>

        </div>
    </div>
</section>