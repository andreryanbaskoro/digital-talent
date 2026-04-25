@extends('layouts.app-admin')

@section('content')
@php
$statusBadge = function ($status) {
return match (strtolower($status ?? '')) {
'diterima', 'disetujui' => 'success',
'ditolak' => 'danger',
'pending', 'menunggu' => 'warning',
default => 'secondary',
};
};
@endphp

<div class="content-wrapper">

  <section class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h1 class="mb-1 font-weight-bold">{{ $title ?? 'Dashboard Perusahaan' }}</h1>
          <small class="text-muted">
            Ringkasan aktivitas perusahaan dan akses cepat
          </small>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">

      @if(!$profilPerusahaan)
      <div class="alert alert-warning shadow-sm">
        Profil perusahaan belum ditemukan. Silakan lengkapi profil terlebih dahulu.
        <a href="{{ route('perusahaan.profil.index') }}" class="btn btn-sm btn-dark ml-2">
          Buka Profil
        </a>
      </div>
      @else
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
          <div class="mb-2 mb-md-0">
            <h4 class="mb-1">{{ $profilPerusahaan->nama_perusahaan }}</h4>
            <div class="text-muted">
              {{ $profilPerusahaan->kab_kota ?? '-' }} , {{ $profilPerusahaan->provinsi ?? '-' }}
            </div>
          </div>
          <div class="text-md-right">
            <a href="{{ route('perusahaan.profil.index') }}" class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-id-card mr-1"></i> Profil
            </a>
            <a href="{{ route('perusahaan.lowongan.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus mr-1"></i> Buat Lowongan
            </a>
          </div>
        </div>
      </div>
      @endif

      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary shadow-sm">
            <div class="inner">
              <h3>{{ number_format($totalLowongan, 0, ',', '.') }}</h3>
              <p>Total Lowongan</p>
            </div>
            <div class="icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('perusahaan.lowongan.index') }}" class="small-box-footer">
              Lihat lowongan <i class="fas fa-arrow-circle-right"></i>
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
            <a href="{{ route('perusahaan.lowongan.index') }}" class="small-box-footer">
              Pantau lowongan <i class="fas fa-arrow-circle-right"></i>
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
            <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="small-box-footer">
              Lihat lamaran <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger shadow-sm">
            <div class="inner">
              <h3>{{ number_format($lamaranBulanIni, 0, ',', '.') }}</h3>
              <p>Lamaran Bulan Ini</p>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="small-box-footer">
              Detail lamaran <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info shadow-sm">
            <div class="inner">
              <h3>{{ number_format($lowonganSelesai, 0, ',', '.') }}</h3>
              <p>Lowongan Selesai</p>
            </div>
            <div class="icon">
              <i class="fas fa-flag-checkered"></i>
            </div>
            <a href="{{ route('perusahaan.lowongan.index') }}" class="small-box-footer">
              Arsip lowongan <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary shadow-sm">
            <div class="inner">
              <h3>{{ count($lamaranStatus) }}</h3>
              <p>Status Lamaran</p>
            </div>
            <div class="icon">
              <i class="fas fa-layer-group"></i>
            </div>
            <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="small-box-footer">
              Lihat status <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-dark shadow-sm">
            <div class="inner">
              <h3>Menu</h3>
              <p>Keputusan Seleksi</p>
            </div>
            <div class="icon">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <a href="{{ route('perusahaan.keputusan-seleksi.index') }}" class="small-box-footer">
              Buka halaman <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-indigo shadow-sm">
            <div class="inner">
              <h3>Rank</h3>
              <p>Hasil Ranking</p>
            </div>
            <div class="icon">
              <i class="fas fa-trophy"></i>
            </div>
            <a href="{{ route('perusahaan.ranking.index') }}" class="small-box-footer">
              Lihat ranking <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

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
              <a href="{{ route('perusahaan.lowongan.index') }}" class="btn btn-sm btn-outline-primary">
                Detail lowongan
              </a>
              <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="btn btn-sm btn-outline-secondary ml-1">
                Detail lamaran
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title mb-0">
                <i class="fas fa-chart-pie mr-1"></i> Status Lamaran
              </h3>
            </div>
            <div class="card-body">
              <div style="height: 320px;">
                <canvas id="statusChart"></canvas>
              </div>
            </div>
            <div class="card-footer bg-white border-0">
              <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="btn btn-sm btn-outline-info">
                Buka daftar lamaran
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <h3 class="card-title mb-0">
                <i class="fas fa-briefcase mr-1"></i> Lowongan Terbaru
              </h3>
              <a href="{{ route('perusahaan.lowongan.index') }}" class="btn btn-sm btn-outline-primary">
                Semua
              </a>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Judul</th>
                    <th>Periode</th>
                    <th class="text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($lowonganTerbaru as $item)
                  <tr>
                    <td class="align-middle">
                      <div class="font-weight-bold">{{ $item->judul_lowongan }}</div>
                      <small class="text-muted">{{ $item->lokasi ?? '-' }}</small>
                    </td>
                    <td class="align-middle">
                      <small class="text-muted">
                        {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') : '-' }}
                        s/d
                        {{ $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y') : '-' }}
                      </small>
                    </td>
                    <td class="align-middle text-right">
                      <a href="{{ route('perusahaan.lowongan.index') }}" class="btn btn-sm btn-outline-info">
                        Detail
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
              <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="btn btn-sm btn-outline-success">
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
                      <span class="badge badge-{{ $statusBadge($item->status_lamaran) }}">
                        {{ $item->status_lamaran ?? 'Unknown' }}
                      </span>
                    </td>
                    <td class="align-middle text-right">
                      <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="btn btn-sm btn-outline-primary">
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

      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header border-0">
              <h3 class="card-title mb-0">
                <i class="fas fa-layer-group mr-1"></i> Ringkasan Status Lamaran
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                @forelse($lamaranStatus as $status => $total)
                <div class="col-md-3 col-6 mb-3">
                  <div class="card border shadow-sm mb-0">
                    <div class="card-body py-3">
                      <div class="text-muted small">Status</div>
                      <div class="font-weight-bold text-capitalize">{{ $status ?? 'Unknown' }}</div>
                      <div class="mt-2">
                        <span class="badge badge-{{ $statusBadge($status) }}">
                          {{ number_format($total, 0, ',', '.') }} data
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                @empty
                <div class="col-12 text-muted">Belum ada data status lamaran.</div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>

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
                  <a href="{{ route('perusahaan.lowongan.create') }}" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-plus mr-1"></i> Buat Lowongan
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-2">
                  <a href="{{ route('perusahaan.lowongan.index') }}" class="btn btn-outline-success btn-block">
                    <i class="fas fa-briefcase mr-1"></i> Data Lowongan
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-2">
                  <a href="{{ route('perusahaan.lamaran-pekerjaan.index') }}" class="btn btn-outline-warning btn-block">
                    <i class="fas fa-file-signature mr-1"></i> Lamaran Masuk
                  </a>
                </div>
                <div class="col-md-3 col-6 mb-2">
                  <a href="{{ route('perusahaan.keputusan-seleksi.index') }}" class="btn btn-outline-dark btn-block">
                    <i class="fas fa-clipboard-check mr-1"></i> Keputusan Seleksi
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

    const statusLabels = @json(array_keys($lamaranStatus));
    const statusValues = @json(array_values($lamaranStatus));

    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
      new Chart(statusCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: statusLabels.length ? statusLabels : ['Belum ada data'],
          datasets: [{
            data: statusValues.length ? statusValues : [1],
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