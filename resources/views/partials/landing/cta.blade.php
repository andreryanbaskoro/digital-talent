{{-- ==================== CTA SECTION ==================== --}}
<section class="hero-gradient py-16 relative overflow-hidden">
    <div class="blob w-[400px] h-[400px] bg-purple-500 top-[-100px] right-0 opacity-20"></div>
    <div class="relative max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-extrabold text-white mb-4">Siap Memulai Karirmu?</h2>
        <p class="text-white/70 mb-8">Daftarkan dirimu sekarang dan temukan ribuan peluang karir yang menanti.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white text-primary font-bold text-sm px-8 py-3.5 rounded-2xl hover:shadow-xl transition-all shadow-lg" id="cta-daftar-sekarang">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Daftar Sekarang
            </a>
            <a href="#lowongan" class="inline-flex items-center gap-2 glass text-white font-semibold text-sm px-8 py-3.5 rounded-2xl hover:bg-white/20 transition-all">
                Lihat Lowongan
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </a>
        </div>
    </div>
</section>