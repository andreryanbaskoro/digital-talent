<form action="/" method="GET" class="animate-fade-in-up">

    <div class="glass max-w-6xl mx-auto rounded-2xl p-3 flex flex-col lg:flex-row gap-2">

        {{-- 🔍 KEYWORD --}}
        <div class="flex-1 bg-white/10 rounded-xl px-4 py-2.5">
            <input list="keyword-list"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="Cari pekerjaan / perusahaan..."
                class="w-full bg-transparent text-white placeholder-white/50 text-sm focus:outline-none">

            <datalist id="keyword-list">
                <option value="Semua Pekerjaan">
                    @foreach(($daftarKeyword ?? []) as $item)
                <option value="{{ $item }}">
                    @endforeach
            </datalist>
        </div>

        {{-- 📍 LOKASI --}}
        <div class="flex-1 bg-white/10 rounded-xl px-4 py-2.5">
            <input list="lokasi-list"
                name="lokasi"
                value="{{ request('lokasi') }}"
                placeholder="Lokasi kerja..."
                class="w-full bg-transparent text-white placeholder-white/50 text-sm focus:outline-none">

            <datalist id="lokasi-list">
                <option value="Semua Lokasi">
                    @foreach(($daftarLokasi ?? []) as $item)
                <option value="{{ $item }}">
                    @endforeach
            </datalist>
        </div>

        {{-- 💼 JENIS PEKERJAAN --}}
        <div class="flex-1 bg-white/10 rounded-xl px-4 py-2.5">
            <input list="jenis-list"
                name="jenis_pekerjaan"
                value="{{ request('jenis_pekerjaan') }}"
                placeholder="Jenis pekerjaan..."
                class="w-full bg-transparent text-white placeholder-white/50 text-sm focus:outline-none">

            <datalist id="jenis-list">
                <option value="Semua Jenis">

                    @foreach([
                    'fulltime' => 'Full Time',
                    'parttime' => 'Part Time',
                    'freelance' => 'Freelance',
                    'internship' => 'Internship'
                    ] as $value => $label)

                <option value="{{ $value }}">{{ $label }}</option>

                @endforeach
            </datalist>
        </div>

        {{-- 🏠 SISTEM KERJA --}}
        <div class="flex-1 bg-white/10 rounded-xl px-4 py-2.5">
            <input list="sistem-list"
                name="sistem_kerja"
                value="{{ request('sistem_kerja') }}"
                placeholder="Sistem kerja..."
                class="w-full bg-transparent text-white placeholder-white/50 text-sm focus:outline-none">

            <datalist id="sistem-list">
                <option value="Semua Sistem">

                    @foreach([
                    'onsite' => 'Onsite',
                    'remote' => 'Remote',
                    'hybrid' => 'Hybrid'
                    ] as $value => $label)

                <option value="{{ $value }}">{{ $label }}</option>

                @endforeach
            </datalist>
        </div>

        {{-- 🔘 BUTTON --}}
        <button type="submit"
            class="bg-gradient-to-r from-primary to-accent text-white px-7 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition">
            Cari
        </button>

    </div>

</form>