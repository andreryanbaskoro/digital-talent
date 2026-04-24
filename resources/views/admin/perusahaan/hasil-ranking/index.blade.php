@extends('layouts.app-admin')

@section('title', $title ?? 'Hasil Ranking Profile Matching')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header mb-4">
        <div class="container-fluid">

            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h3 class="mb-1 font-weight-bold">
                            Hasil Ranking Profile Matching
                        </h3>
                        <small class="text-muted">
                            Pilih lowongan untuk menghitung atau melihat hasil ranking kandidat.
                        </small>
                    </div>

                    <div>
                        <span class="badge bg-primary px-3 py-2">
                            {{ count($lowongan) }} Lowongan
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </section>


    <section class="content">
        <div class="container-fluid">

            @include('admin.perusahaan.hasil-ranking.partials.alerts')

            <div class="row">

                @forelse($lowongan as $item)
                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card border-0 shadow-sm h-100 rounded-3 hover-shadow"
                        style="transition: all .2s ease-in-out;">

                        <div class="card-body d-flex flex-column p-4">

                            {{-- TITLE --}}
                            <div class="mb-2">
                                <h5 class="font-weight-bold mb-1">
                                    {{ $item->judul_lowongan ?? '-' }}
                                </h5>
                                <div class="text-muted small">
                                    {{ $item->profilPerusahaan->nama_perusahaan ?? '-' }}
                                </div>
                            </div>

                            <hr>

                            {{-- STATS --}}
                            <div class="mb-3">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Jumlah Lamaran</span>
                                    <span class="badge bg-info text-white px-3 py-2">
                                        {{ $item->jumlah_lamaran ?? 0 }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Status Perhitungan</span>

                                    @if($item->sudah_dihitung)
                                    <span class="badge bg-success px-3 py-2">
                                        ✔ Sudah Dihitung
                                    </span>
                                    @else
                                    <span class="badge bg-secondary px-3 py-2">
                                        ⏳ Belum Dihitung
                                    </span>
                                    @endif
                                </div>

                            </div>

                            {{-- ACTION BUTTONS --}}
                            <div class="mt-auto">

                                <a href="{{ route('perusahaan.ranking.show', $item->id_lowongan) }}"
                                    class="btn btn-outline-primary btn-sm w-100 mb-2">
                                    📊 Lihat Hasil Ranking
                                </a>

                                <form action="{{ route('perusahaan.ranking.calculate', $item->id_lowongan) }}"
                                    method="POST"
                                    class="form-hitung-ranking">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary btn-sm w-100">
                                        🔄 Hitung / Perbarui Ranking
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>

                </div>
                @empty

                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <h5 class="text-muted mb-2">Belum Ada Lowongan</h5>
                            <small class="text-muted">
                                Silakan buat lowongan terlebih dahulu untuk melakukan perhitungan ranking.
                            </small>
                        </div>
                    </div>
                </div>

                @endforelse

            </div>

        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".form-hitung-ranking").forEach(form => {

            form.addEventListener("submit", function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Hitung Ranking?",
                    text: "Sistem akan menghitung atau memperbarui hasil ranking kandidat.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#0d6efd",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, Hitung Sekarang",
                    cancelButtonText: "Batal",
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            title: "Memproses...",
                            text: "Sedang menghitung ranking kandidat",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });

        });

    });
</script>
@endpush