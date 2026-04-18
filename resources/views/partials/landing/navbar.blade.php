{{-- ==================== NAVBAR ==================== --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/60 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="font-bold text-lg text-gray-900">
                    Digital <span class="text-primary">Talent Hub</span>
                </span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="#lowongan" class="hover:text-primary transition-colors">Lowongan</a>
                <a href="#tentang" class="hover:text-primary transition-colors">Tentang</a>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-3">

                @auth
                @php $user = Auth::user(); @endphp

                {{-- ================= PENCARI KERJA ================= --}}
                @if($user->peran === 'pencaker')

                <a href="{{ route('pencaker.dashboard') }}"
                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-all shadow hover:shadow-primary/40">

                    {{-- ICON --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
                    </svg>

                    Dashboard Saya
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="from" value="landing">

                    <button class="text-sm text-gray-500 hover:text-red-500 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                        </svg>
                        Logout
                    </button>
                </form>

                @endif


                {{-- ================= PERUSAHAAN ================= --}}
                @if($user->peran === 'perusahaan')

                <a href="{{ route('perusahaan.dashboard') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 17l5-5-5-5M15 12H3" />
                    </svg>

                    Dashboard Perusahaan
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="from" value="landing">

                    <button class="text-sm text-gray-500 hover:text-red-500 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                        </svg>
                        Logout
                    </button>
                </form>

                @endif


                {{-- ================= ADMIN ================= --}}
                @if($user->peran === 'disnaker')

                <a href="{{ route('disnaker.dashboard') }}"
                    class="inline-flex items-center gap-2 bg-gray-800 hover:bg-black text-white text-sm font-semibold px-4 py-2 rounded-xl shadow">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
                    </svg>

                    Dashboard Disnaker
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="from" value="landing">

                    <button class="text-sm text-gray-500 hover:text-red-500 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                        </svg>
                        Logout
                    </button>
                </form>

                @endif


                @else
                {{-- ================= BELUM LOGIN ================= --}}

                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-indigo-500 hover:from-primary-600 hover:to-indigo-600 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    Masuk / Daftar
                </a>

                @endauth

            </div>
        </div>
    </div>
</nav>