@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

  <section class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h1 class="mb-1 font-weight-bold">{{ $title }}</h1>
          <small class="text-muted">Ringkasan aktivitas pencari kerja</small>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">

      {{-- QUICK PROFILE --}}
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
          <div>
            <h4 class="mb-1">{{ auth()->user()->profilPencariKerja->nama_lengkap ?? '-' }}</h4>
            <small class="text-muted">
              {{ auth()->user()->email }}
            </small>
          </div>

          <div class="text-right">
            <a href="{{ route('pencaker.profil.index') }}" class="btn btn-outline-primary btn-sm">
              <i class="fas fa-user"></i> Profil
            </a>

            <a href="{{ route('pencaker.lamaran.index') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-briefcase"></i> Lamaran Saya
            </a>
          </div>
        </div>
      </div>

      {{-- STATS --}}
      <div class="row">

        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary shadow-sm">
            <div class="inner">
              <h3>{{ $totalLamaran }}</h3>
              <p>Total Lamaran</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-signature"></i>
            </div>
            <a href="{{ route('pencaker.lamaran.index') }}" class="small-box-footer">
              Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning shadow-sm">
            <div class="inner">
              <h3>{{ $lamaranPending }}</h3>
              <p>Menunggu</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('pencaker.lamaran.index') }}" class="small-box-footer">
              Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success shadow-sm">
            <div class="inner">
              <h3>{{ $lamaranDiterima }}</h3>
              <p>Diterima</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('pencaker.lamaran.index') }}" class="small-box-footer">
              Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger shadow-sm">
            <div class="inner">
              <h3>{{ $lamaranDitolak }}</h3>
              <p>Ditolak</p>
            </div>
            <div class="icon">
              <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('pencaker.lamaran.index') }}" class="small-box-footer">
              Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

      </div>

      {{-- AK1 STATUS --}}
      <div class="card shadow-sm">
        <div class="card-header border-0">
          <h3 class="card-title mb-0">
            <i class="fas fa-id-card mr-1"></i> Status AK1
          </h3>
        </div>

        <div class="card-body">

          @if(!$ak1)
          <div class="alert alert-warning mb-0">
            Kamu belum memiliki AK1.
            <a href="{{ route('pencaker.ak1.formulir') }}" class="btn btn-sm btn-dark ml-2">
              Buat AK1
            </a>
          </div>
          @else
          <div class="row">

            <div class="col-md-4">
              <div class="info-box">
                <span class="info-box-icon bg-info">
                  <i class="fas fa-id-badge"></i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text">Status</span>
                  <span class="info-box-number text-uppercase">
                    {{ $ak1->status }}
                  </span>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="info-box">
                <span class="info-box-icon bg-success">
                  <i class="fas fa-calendar"></i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text">Tanggal Daftar</span>
                  <span class="info-box-number">
                    {{ optional($ak1->tanggal_daftar)->format('d-m-Y') }}
                  </span>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <a href="{{ route('pencaker.ak1.index') }}" class="btn btn-outline-primary btn-block h-100 d-flex align-items-center justify-content-center">
                <i class="fas fa-eye mr-2"></i> Lihat AK1
              </a>
            </div>

          </div>
          @endif

        </div>
      </div>

      {{-- QUICK ACTION --}}
      <div class="card shadow-sm">
        <div class="card-header border-0">
          <h3 class="card-title mb-0">
            <i class="fas fa-bolt mr-1"></i> Akses Cepat
          </h3>
        </div>

        <div class="card-body">
          <div class="row">

            <div class="col-md-3 col-6 mb-2">
              <a href="{{ route('pencaker.lamaran.index') }}" class="btn btn-outline-primary btn-block">
                <i class="fas fa-briefcase"></i><br> Lamaran
              </a>
            </div>

            <div class="col-md-3 col-6 mb-2">
              <a href="{{ route('pencaker.ak1.formulir') }}" class="btn btn-outline-success btn-block">
                <i class="fas fa-id-card"></i><br> AK1
              </a>
            </div>

            <div class="col-md-3 col-6 mb-2">
              <a href="/" class="btn btn-outline-warning btn-block">
                <i class="fas fa-search"></i><br> Cari Lowongan
              </a>
            </div>

            <div class="col-md-3 col-6 mb-2">
              <a href="{{ route('pencaker.profil.index') }}" class="btn btn-outline-dark btn-block">
                <i class="fas fa-user"></i><br> Profil
              </a>
            </div>

          </div>
        </div>
      </div>

      {{-- LAMARAN TERBARU --}}
      <div class="card shadow-sm">
        <div class="card-header border-0 d-flex justify-content-between">
          <h3 class="card-title mb-0">
            <i class="fas fa-history mr-1"></i> Lamaran Terbaru
          </h3>

          <a href="{{ route('pencaker.lamaran.index') }}" class="btn btn-sm btn-outline-primary">
            Semua
          </a>
        </div>

        <div class="card-body p-0 table-responsive">
          <table class="table table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th>Lowongan</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              @forelse($lamaranTerbaru as $item)
              <tr>
                <td>
                  {{ $item->lowongan->judul_lowongan ?? '-' }}
                </td>
                <td>
                  <span class="badge badge-secondary">
                    {{ $item->status_lamaran }}
                  </span>
                </td>
                <td>
                  {{ optional($item->tanggal_lamar)->format('d-m-Y') }}
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" class="text-center text-muted py-3">
                  Belum ada lamaran
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </section>
</div>

@endsection