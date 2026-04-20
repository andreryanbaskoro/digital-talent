{{-- ==================== ABOUT SECTION ==================== --}}
<section id="tentang" class="bg-white border-t border-gray-100 py-16 scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- ================= TEKS ================= --}}
            <div>

                <span class="text-xs font-bold uppercase tracking-widest text-primary mb-3 block">
                    Tentang Digital Talent Hub
                </span>

                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">
                    Sistem Rekomendasi Kerja Berbasis Profile Matching
                </h2>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Digital Talent Hub merupakan sistem informasi ketenagakerjaan yang dikembangkan oleh Dinas Ketenagakerjaan Kota Jayapura
                    untuk membantu proses penempatan kerja secara lebih objektif, terukur, dan transparan menggunakan metode
                    <b>Profile Matching</b>.
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Sistem ini mencocokkan kompetensi pencari kerja dengan kebutuhan lowongan berdasarkan kriteria yang telah ditentukan,
                    sehingga menghasilkan rekomendasi kandidat yang paling sesuai dengan kebutuhan perusahaan.
                </p>

                {{-- ================= FITUR ================= --}}
                <div class="grid grid-cols-2 gap-4">

                    @foreach([
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Seleksi Berbasis Profile Matching'],

                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'label' => 'Penilaian Objektif & Terukur'],

                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'Proses Seleksi Lebih Efisien'],

                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Kolaborasi Disnaker & Perusahaan Terverifikasi'],
                    ] as $feat)

                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 hover:bg-primary/5 transition-colors">

                        <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $feat['icon'] }}" />
                        </svg>

                        <span class="text-sm font-medium text-gray-700">
                            {{ $feat['label'] }}
                        </span>

                    </div>

                    @endforeach

                </div>
            </div>

            {{-- ================= ILLUSTRATION ================= --}}
            <div class="flex justify-center">

                <div class="relative">

                    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-accent/30 rounded-3xl blur-2xl"></div>

                    <div class="relative h-72 w-72 rounded-3xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-2xl">

                        <svg class="w-36 h-36 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>