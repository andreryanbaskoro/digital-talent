{{-- ==================== FILTER BAR ==================== --}}
<div id="lowongan"
    class="scroll-mt-[60px] sticky top-16 z-40 bg-white border-b border-gray-200 shadow-sm">

    <form action="/" method="GET">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap gap-3 items-center">

            {{-- 🔒 Preserve keyword --}}
            <input type="hidden" name="keyword" value="{{ request('keyword') }}">

            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Filter:
            </span>

            {{-- 📍 Lokasi --}}
            <select name="lokasi"
                onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700">

                <option value="">Semua Lokasi</option>

                @foreach(($daftarLokasi ?? []) as $lokasi)
                <option value="{{ $lokasi }}"
                    {{ request('lokasi') == $lokasi ? 'selected' : '' }}>
                    {{ $lokasi }}
                </option>
                @endforeach

            </select>

            {{-- 💼 Jenis Pekerjaan --}}
            <select name="jenis_pekerjaan"
                onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700">

                <option value="">Semua Jenis</option>

                @foreach(($daftarJenis ?? []) as $jenis)
                <option value="{{ $jenis }}"
                    {{ request('jenis_pekerjaan') == $jenis ? 'selected' : '' }}>
                    {{ ucfirst($jenis) }}
                </option>
                @endforeach

            </select>

            {{-- 🏠 Sistem Kerja --}}
            <select name="sistem_kerja"
                onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700">

                <option value="">Semua Sistem</option>

                @foreach(($daftarSistemKerja ?? []) as $sistem)
                <option value="{{ $sistem }}"
                    {{ request('sistem_kerja') == $sistem ? 'selected' : '' }}>
                    {{ ucfirst($sistem) }}
                </option>
                @endforeach

            </select>

            {{-- 🧹 Reset --}}
            @if(request()->hasAny([
            'keyword',
            'lokasi',
            'jenis_pekerjaan',
            'sistem_kerja'
            ]))
            <a href="/"
                class="inline-flex items-center gap-1.5 text-sm text-red-500 hover:text-red-700 border border-red-200 rounded-xl px-3 py-2">
                X Reset Filter
            </a>
            @endif

            {{-- 📊 Total --}}
            <span class="ml-auto text-sm text-gray-500">
                <span class="font-semibold text-gray-800">
                    {{ $lowongan->total() }}
                </span>
                lowongan ditemukan
            </span>

        </div>

    </form>
</div>