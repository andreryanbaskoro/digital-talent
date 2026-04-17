{{-- ==================== FOOTER ==================== --}}
<footer class="bg-gray-900 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
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