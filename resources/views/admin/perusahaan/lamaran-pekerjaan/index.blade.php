@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="d-flex align-items-center mb-2">

                <h1 class="mb-0">{{ $title ?? 'Daftar Pencari Kerja' }}</h1>

            </div>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="content">
        <div class="container-fluid">

            {{-- ALERT --}}
            @include('admin.perusahaan.lamaran-pekerjaan.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- HEADER TABS -->
                <div class="card-header p-0 border-bottom-0">

                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link filter-tab active" data-filter="all" href="#">
                                    <i class="fas fa-list"></i> Semua
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link filter-tab" data-filter="deleted" href="#">
                                    <i class="fas fa-trash text-danger"></i> Terhapus
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

                <!-- BODY -->
                <div class="card-body pt-3">

                    @include('admin.perusahaan.lamaran-pekerjaan.partials.table', [
                    'lamaran' => $lamaran
                    ])

                </div>

            </div>

        </div>
    </section>

</div>

@include('admin.perusahaan.lamaran-pekerjaan.show')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-css/table.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>
<script src="{{ asset('admin-js/perusahaan-lamaran.js') }}"></script>
<script>
    $(document).ready(function() {

        // 🔥 FORCE CLEAN STATE saat page load / back navigation
        $('#modalAI').modal('hide');

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                $('#modalAI').modal('hide');
            }
        });

        // RESET MODAL saat benar-benar tertutup
        $('#modalAI').on('hidden.bs.modal', function() {
            $('.step-title').text('');
            $('.step-desc').text('');
            $('.matrix-log').text('');
            $('.progress-bar').css('width', '0%');
        });

        $(document).on('click', '.btn-hit-detail', function() {

            let url = $(this).data('url');

            $('#modalAI').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            let steps = [{
                    title: "Tahap 1: Pengambilan Data",
                    desc: "Mengambil data lamaran dan profil kandidat dari database",
                    log: "Mengakses tabel: lamaran_pekerjaan, profil_pencaker...",
                    progress: 15,
                    time: 300
                },
                {
                    title: "Tahap 2: Perhitungan Core Factor (CF)",
                    desc: "Menghitung faktor utama berdasarkan kemampuan dan pengalaman",
                    log: "CF = (Nilai Skill + Pengalaman) / 2",
                    progress: 35,
                    time: 300
                },
                {
                    title: "Tahap 3: Perhitungan Secondary Factor (SF)",
                    desc: "Menghitung faktor pendukung seperti pendidikan dan lokasi",
                    log: "SF = (Pendidikan + Lokasi) / 2",
                    progress: 55,
                    time: 300
                },
                {
                    title: "Tahap 4: Analisis GAP Kompetensi",
                    desc: "Menganalisis selisih antara profil kandidat dan kebutuhan jabatan",
                    log: "GAP = Nilai Kandidat - Nilai Target",
                    progress: 75,
                    time: 600
                },
                {
                    title: "Tahap 5: Penentuan Hasil Akhir",
                    desc: "Menghitung nilai akhir dan menentukan peringkat kandidat",
                    log: "Menerapkan pembobotan dan normalisasi nilai...",
                    progress: 100,
                    time: 800
                }
            ];

            let i = 0;

            function runAI() {

                if (i >= steps.length) {

                    setTimeout(() => {

                        // 🔥 penting: tutup modal dulu sebelum pindah
                        $('#modalAI').modal('hide');

                        // kasih delay kecil biar animasi close jalan
                        setTimeout(() => {
                            window.location.href = url;
                        }, 200);

                    }, 300);

                    return;
                }

                let s = steps[i];

                $('.step-title').fadeOut(100, function() {
                    $(this).text(s.title).fadeIn(120);
                });

                $('.step-desc').fadeOut(100, function() {
                    $(this).text(s.desc).fadeIn(120);
                });

                $('.matrix-log').fadeOut(100, function() {
                    $(this).text(s.log).fadeIn(120);
                });

                $('.progress-bar').animate({
                    width: s.progress + "%"
                }, s.time);

                i++;

                setTimeout(runAI, s.time);
            }

            runAI();
        });

    });
</script>
@endpush