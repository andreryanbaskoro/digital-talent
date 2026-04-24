@extends('layouts.app-admin')

@section('title', $title ?? 'Hasil Ranking')

@section('content')
<div class="content-wrapper">

    {{-- HEADER --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h1 class="mb-0">Hasil Ranking Profile Matching</h1>
                    <small class="text-muted">
                        {{ $lowongan->judul_lowongan ?? '-' }}
                        - {{ $lowongan->profilPerusahaan->nama_perusahaan ?? '-' }}
                    </small>
                </div>

                <form id="form-hitung"
                    action="{{ route('perusahaan.ranking.calculate', $lowongan->id_lowongan) }}"
                    method="POST">
                    @csrf
                    <button type="button" id="btn-hitung" class="btn btn-primary shadow-sm">
                        🔄 Hitung Ulang
                    </button>
                </form>
            </div>
        </div>
    </section>


    <section class="content">
        <div class="container-fluid">
            {{-- Alert --}}
            @include('admin.perusahaan.lowongan.partials.alerts')

            {{-- ALUR METODE --}}
            <!-- <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body text-center bg-light rounded">
                    <strong>Alur Perhitungan:</strong><br>
                    Target → Hitung GAP → Konversi ke Skor Profil →
                    Hitung Nilai Kriteria → Total Nilai → Persentase → Ranking
                </div>
            </div> -->


            @if(empty($ranking))

            <div class="alert alert-warning shadow-sm">
                Belum ada hasil perhitungan.
            </div>

            @else

            {{-- RINGKASAN METODE --}}
            <!-- <div class="alert alert-info shadow-sm">
                <b>Ringkasan Metode:</b><br>
                Total Nilai =
                (Skill × 0.4) +
                (Pengalaman × 0.3) +
                (Pendidikan × 0.2) +
                (Lokasi × 0.1)
                <br>
                Persentase Matching = (Total Nilai / 5) × 100%
            </div> -->


            {{-- TABEL RANKING --}}
            <div class="card shadow-sm border-0">
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="70">Rank</th>
                                <th>Nama Kandidat</th>
                                <th>Skill</th>
                                <th>Pengalaman</th>
                                <th>Pendidikan</th>
                                <th>Lokasi</th>
                                <th>Total Nilai</th>
                                <th>Persentase</th>
                                <th>Rekomendasi</th>
                                <th width="120">Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ranking as $row)
                            <tr @if($row['ranking']==1) class="table-success" @endif>

                                {{-- Rank --}}
                                <td>
                                    @if($row['ranking'] == 1)
                                    🥇 <b>1</b>
                                    @elseif($row['ranking'] == 2)
                                    🥈 2
                                    @elseif($row['ranking'] == 3)
                                    🥉 3
                                    @else
                                    {{ $row['ranking'] }}
                                    @endif
                                </td>

                                {{-- Nama --}}
                                <td class="text-left">
                                    {{ $row['nama'] }}
                                </td>

                                {{-- Nilai Per Kriteria --}}
                                <td>{{ number_format($row['skill'], 2) }}</td>
                                <td>{{ number_format($row['pengalaman'], 2) }}</td>
                                <td>{{ number_format($row['pendidikan'], 2) }}</td>
                                <td>{{ number_format($row['lokasi'], 2) }}</td>

                                {{-- Total --}}
                                <td>
                                    <b>{{ number_format($row['total_nilai'], 2) }}</b>
                                </td>

                                {{-- Persentase --}}
                                <td>
                                    <b>{{ number_format($row['persentase'], 2) }}%</b>
                                </td>

                                {{-- Rekomendasi --}}
                                <td>
                                    @if($row['persentase'] >= 85)
                                    <span class="badge bg-success">
                                        ⭐ Sangat Cocok
                                    </span>
                                    @elseif($row['persentase'] >= 70)
                                    <span class="badge bg-primary">
                                        👍 Cocok
                                    </span>
                                    @else
                                    <span class="badge bg-danger">
                                        ❗ Kurang Cocok
                                    </span>
                                    @endif
                                </td>

                                {{-- Detail --}}
                                <td>
                                    <a href="{{ route('perusahaan.ranking.detail', [$lowongan->id_lowongan, $row['id_lamaran']]) }}"
                                        class="btn btn-sm btn-outline-info">
                                        Lihat Detail
                                    </a>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>

            @endif
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>

<script>
    document.getElementById('btn-hitung').addEventListener('click', function() {

        Swal.fire({
            title: 'Hitung Ulang Ranking?',
            text: "Semua data ranking akan diperbarui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hitung!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Menghitung...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                document.getElementById('form-hitung').submit();
            }
        });

    });
</script>
@endpush

{{-- INDEX --}}