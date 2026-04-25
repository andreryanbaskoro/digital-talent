@extends('layouts.app-admin')

@section('content')
@php
$statusAk1Class = function ($status) {
return match (strtolower($status ?? '')) {
'disetujui' => 'success',
'revisi' => 'warning',
'ditolak' => 'danger',
default => 'secondary',
};
};
@endphp

<div class="content-wrapper">

  {{-- HEADER --}}
  <section class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h1 class="mb-1 font-weight-bold">{{ $title ?? 'Dashboard Disnaker' }}</h1>
          <small class="text-muted">Ringkasan data, aktivitas, dan akses cepat Disnaker</small>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">

      {{-- SUMMARY CARDS --}}
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info shadow-sm">
            <div class="inner">
              <h3>{{ number_format($totalLowongan, 0, ',', '.') }}</h3>
              <p>Total Lowongan</p>
            </div>
            <div class="icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('disnaker.laporan-lowongan.index') }}" class="small-box-footer">
              Lihat laporan <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success shadow-sm">
            <div class="inner">
              <h3>{{ number_format($lowonganAktif, 0, ',', '.') }}</h3>
              <p>Lowongan Aktif</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('disnaker.verifikasi-lowongan.index') }}" class="small-box-footer">
              Verifikasi lowongan <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning shadow-sm">
            <div class="inner">
              <h3>{{ number_format($totalLamaran, 0, ',', '.') }}</h3>
              <p>Total Lamaran</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-signature"></i>
            </div>
            <a href="{{ route('disnaker.laporan-penempatan.index') }}" class="small-box-footer">
              Lihat penempatan <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger shadow-sm">
            <div class="inner">
              <h3>{{ number_format($totalAK1, 0, ',', '.') }}</h3>
              <p>Total Kartu AK1</p>
            </div>
            <div class="icon">
              <i class="fas fa-id-card"></i>
            </div>
            <a href="{{ route('disnaker.ak1.index') }}" class="small-box-footer">
              Lihat AK1 <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary shadow-sm">
            <div class="inner">
              <h3>{{ number_format($totalPencaker, 0, ',', '.') }}</h3>
              <p>Pencari Kerja</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('disnaker.pencari-kerja.index') }}" class="small-box-footer">
              Lihat data <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-indigo shadow-sm">
            <div class="inner">
              <h3>{{ number_format($totalPerusahaan, 0, ',', '.') }}</h3>
              <p>Perusahaan</p>
            </div>
            <div class="icon">
              <i class="fas fa-building"></i>
            </div>
            <a href="{{ route('disnaker.perusahaan.index') }}" class="small-box-footer">
              Lihat data <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary shadow-sm">
            <div class="inner">
              <h3>{{ number_format($ak1Pending, 0, ',', '.') }}</h3>
              <p>AK1 Pending</p>
            </div>
            <div class="icon">
              <i class="fas fa-hourglass-half"></i>
            </div>
            <a href="{{ route('disnaker.ak1.index') }}" class="small-box-footer">
              Tinjau pending <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-dark shadow-sm">
            <div class="inner">
              <h3>{{ number_format($verifikasiBulanIni, 0, ',', '.') }}</h3>
              <p>Verifikasi Bulan Ini</p>
            </div>
            <div class="icon">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <a href="{{ route('disnaker.ak1.index') }}" class="small-box-footer">
              Halaman verifikasi <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- QUICK ACTIONS --}}
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title mb-0">
                <i class="fas fa-bolt mr-1"></i> Akses Cepat
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3 col-6 mb-2">
                  <a href="{{ route('disnaker.verifikasi-lowongan.index') }}" class="btn btn-outline-info btn-block">
                    <i class="fas fa-search mr-1"></i> Verifikasi Lowongan
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-2">
                  <a href="{{ route('disnaker.ak1.pending') }}" class="btn btn-outline-danger btn-block">
                    <i class="fas fa-id-card mr-1"></i> AK1 Pending
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-2">
                  <a href="{{ route('disnaker.laporan-lowongan.index') }}" class="btn btn-outline-success btn-block">
                    <i class="fas fa-file-export mr-1"></i> Laporan Lowongan
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-2">
                  <a href="{{ route('disnaker.laporan-pencari-kerja.index') }}" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-users mr-1"></i> Laporan Pencari Kerja
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- CHARTS --}}
      <div class="row">
        <div class="col-lg-8">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title mb-0">
                <i class="fas fa-chart-line mr-1"></i> Tren Lowongan & Lamaran
              </h3>
            </div>
            <div class="card-body">
              <div style="height: 320px;">
                <canvas id="trendChart"></canvas>
              </div>
            </div>
            <div class="card-footer bg-white border-0">
              <a href="{{ route('disnaker.laporan-lowongan.index') }}" class="btn btn-sm btn-outline-primary">
                Detail laporan lowongan
              </a>
              <a href="{{ route('disnaker.laporan-pencari-kerja.index') }}" class="btn btn-sm btn-outline-secondary ml-1">
                Detail laporan pencari kerja
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title mb-0">
                <i class="fas fa-chart-pie mr-1"></i> Status AK1
              </h3>
            </div>
            <div class="card-body">
              <div style="height: 320px;">
                <canvas id="ak1Chart"></canvas>
              </div>
            </div>
            <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center flex-wrap">
              <span class="mr-2">Disetujui: <strong>{{ number_format($ak1Disetujui, 0, ',', '.') }}</strong></span>
              <span>Revisi: <strong>{{ number_format($ak1Revisi, 0, ',', '.') }}</strong></span>
            </div>
          </div>
        </div>
      </div>

      {{-- HIGHLIGHT / RINGKASAN --}}
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm card-outline card-info">
            <div class="card-header border-0">
              <h3 class="card-title mb-0">
                <i class="fas fa-star mr-1"></i> Hasil Perhitungan Terbaik
              </h3>
            </div>
            <div class="card-body">
              @if($hasilTerbaik)
              <div class="row">
                <div class="col-md-3 mb-3 mb-md-0">
                  <div class="text-muted small">Nama Pencari Kerja</div>
                  <div class="font-weight-bold">
                    {{ $hasilTerbaik->lamaran->pencariKerja->nama_lengkap ?? '-' }}
                  </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                  <div class="text-muted small">Lowongan</div>
                  <div class="font-weight-bold">
                    {{ $hasilTerbaik->lamaran->lowongan->judul_lowongan ?? '-' }}
                  </div>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                  <div class="text-muted small">Nilai Total</div>
                  <div class="font-weight-bold">
                    {{ number_format($hasilTerbaik->nilai_total, 2, ',', '.') }}
                  </div>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                  <div class="text-muted small">Peringkat</div>
                  <div class="font-weight-bold">
                    #{{ $hasilTerbaik->peringkat }}
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="text-muted small">Rekomendasi</div>
                  <div class="font-weight-bold">
                    {{ $hasilTerbaik->rekomendasi ?? '-' }}
                  </div>
                </div>
              </div>
              @else
              <div class="text-muted">Belum ada data hasil perhitungan.</div>
              @endif
            </div>
            <div class="card-footer bg-white border-0">
              <a href="{{ route('disnaker.laporan-penempatan.index') }}" class="btn btn-sm btn-outline-info">
                Lihat laporan penempatan
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- LIST TERBARU --}}
      <div class="row">
        <div class="col-lg-6">
          <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <h3 class="card-title mb-0">
                <i class="fas fa-briefcase mr-1"></i> Lowongan Terbaru
              </h3>
              <a href="{{ route('disnaker.laporan-lowongan.index') }}" class="btn btn-sm btn-outline-primary">
                Semua
              </a>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Judul</th>
                    <th>Perusahaan</th>
                    <th class="text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($lowonganTerbaru as $item)
                  <tr>
                    <td class="align-middle">
                      <div class="font-weight-bold">{{ $item->judul_lowongan }}</div>
                      <small class="text-muted">
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                        s/d
                        {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y') }}
                      </small>
                    <td class="align-middle">
                      {{ $item->profilPerusahaan->nama_perusahaan ?? '-' }}
                    </td>
                    <td class="align-middle text-right">
                      <a href="{{ route('disnaker.verifikasi-lowongan.index') }}" class="btn btn-sm btn-outline-info">
                        Verifikasi
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">Belum ada lowongan.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <h3 class="card-title mb-0">
                <i class="fas fa-file-signature mr-1"></i> Lamaran Terbaru
              </h3>
              <a href="{{ route('disnaker.laporan-penempatan.index') }}" class="btn btn-sm btn-outline-success">
                Semua
              </a>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Pencari Kerja</th>
                    <th>Lowongan</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($lamaranTerbaru as $item)
                  <tr>
                    <td class="align-middle">
                      {{ $item->pencariKerja->nama_lengkap ?? '-' }}
                    </td>
                    <td class="align-middle">
                      {{ $item->lowongan->judul_lowongan ?? '-' }}
                    </td>
                    <td class="align-middle">
                      <span class="badge badge-info">
                        {{ $item->status_lamaran ?? 'Unknown' }}
                      </span>
                    </td>
                    <td class="align-middle text-right">
                      <a href="{{ route('disnaker.laporan-penempatan.index') }}" class="btn btn-sm btn-outline-primary">
                        Detail
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada lamaran.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- AK1 TERBARU --}}
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <h3 class="card-title mb-0">
                <i class="fas fa-id-card mr-1"></i> Kartu AK1 Terbaru
              </h3>
              <div>
                <a href="{{ route('disnaker.ak1.pending') }}" class="btn btn-sm btn-outline-secondary">Pending</a>
                <a href="{{ route('disnaker.ak1.disetujui') }}" class="btn btn-sm btn-outline-success">Disetujui</a>
                <a href="{{ route('disnaker.ak1.ditolak') }}" class="btn btn-sm btn-outline-danger">Ditolak</a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Nama</th>
                    <th>No. Pendaftaran</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                    <th class="text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($ak1Terbaru as $item)
                  <tr>
                    <td class="align-middle">
                      {{ $item->profilPencariKerja->nama_lengkap ?? '-' }}
                    </td>
                    <td class="align-middle">
                      {{ $item->nomor_pendaftaran ?? '-' }}
                    </td>
                    <td class="align-middle">
                      <span class="badge badge-{{ $statusAk1Class($item->status) }}">
                        {{ ucfirst($item->status ?? '-') }}
                      </span>
                    </td>
                    <td class="align-middle">
                      {{ optional($item->tanggal_daftar)->format('d-m-Y') }}
                    </td>
                    <td class="align-middle text-right">
                      <a href="{{ route('disnaker.ak1.show', $item->id_kartu_ak1) }}" class="btn btn-sm btn-outline-info">
                        Lihat
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada data AK1.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- LAPORAN QUICK LINK --}}
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title mb-0">
                <i class="fas fa-folder-open mr-1"></i> Menu Laporan
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 col-6 mb-2">
                  <a href="{{ route('disnaker.laporan-lowongan.index') }}" class="btn btn-outline-info btn-block">
                    <i class="fas fa-file-alt mr-1"></i> Laporan Lowongan
                  </a>
                </div>
                <div class="col-md-4 col-6 mb-2">
                  <a href="{{ route('disnaker.laporan-pencari-kerja.index') }}" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-file-alt mr-1"></i> Laporan Pencari Kerja
                  </a>
                </div>
                <div class="col-md-4 col-6 mb-2">
                  <a href="{{ route('disnaker.laporan-penempatan.index') }}" class="btn btn-outline-success btn-block">
                    <i class="fas fa-file-alt mr-1"></i> Laporan Penempatan
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const labels = @json($bulanLabels);
    const lowonganData = @json($lowonganBulanan);
    const lamaranData = @json($lamaranBulanan);

    const trendCanvas = document.getElementById('trendChart');
    if (trendCanvas) {
      new Chart(trendCanvas.getContext('2d'), {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: 'Lowongan',
              data: lowonganData,
              tension: 0.35,
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Lamaran',
              data: lamaranData,
              tension: 0.35,
              borderWidth: 2,
              fill: false
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          }
        }
      });
    }

    const ak1Canvas = document.getElementById('ak1Chart');
    if (ak1Canvas) {
      new Chart(ak1Canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: ['Pending', 'Disetujui', 'Revisi'],
          datasets: [{
            data: [
              @json($ak1Pending),
              @json($ak1Disetujui),
              @json($ak1Revisi)
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    }
  });
</script>
@endsection