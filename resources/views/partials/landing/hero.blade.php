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

        @include('partials.landing.search')

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