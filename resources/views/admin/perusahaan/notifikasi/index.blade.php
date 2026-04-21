@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center mb-2">
                <div>
                    <h1 class="mb-0">Notifikasi</h1>
                    <small class="text-muted">Pemberitahuan lamaran masuk dan aktivitas terkait lowongan perusahaan.</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">

            {{-- Alert --}}
            @include('admin.perusahaan.lowongan.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- HEADER -->
                <div class="card-header p-0 border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        <!-- TABS FILTER -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $tab === 'all' ? 'active' : '' }}"
                                    href="{{ route('perusahaan.notifikasi.index', ['tab' => 'all']) }}">
                                    <i class="fas fa-list"></i> Semua
                                    <span class="badge badge-light ml-1">{{ $countAll }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $tab === 'unread' ? 'active' : '' }}"
                                    href="{{ route('perusahaan.notifikasi.index', ['tab' => 'unread']) }}">
                                    <i class="fas fa-envelope"></i> Belum Dibaca
                                    <span class="badge badge-light ml-1">{{ $countUnread }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $tab === 'deleted' ? 'active' : '' }}"
                                    href="{{ route('perusahaan.notifikasi.index', ['tab' => 'deleted']) }}">
                                    <i class="fas fa-trash text-danger"></i> Terhapus
                                    <span class="badge badge-light ml-1">{{ $countDeleted }}</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>

                <!-- BODY -->
                <div class="card-body p-0">

                    <!-- BULK ACTION BAR -->
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
                        <div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkAll">
                                <label class="custom-control-label font-weight-bold" for="checkAll">
                                    Pilih Semua
                                </label>
                            </div>
                        </div>

                        <div class="btn-group btn-group-sm">
                            @if($tab === 'deleted')
                            <button class="btn btn-outline-success" id="btnRestoreSelected">
                                <i class="fas fa-trash-restore mr-1"></i> Pulihkan Terpilih
                            </button>

                            <button class="btn btn-outline-danger" id="btnForceDeleteSelected">
                                <i class="fas fa-skull-crossbones mr-1"></i> Hapus Permanen Terpilih
                            </button>

                            <button class="btn btn-danger" id="btnForceDeleteAll">
                                <i class="fas fa-skull-crossbones mr-1"></i> Hapus Permanen Semua
                            </button>
                            @else
                            <button class="btn btn-outline-success" id="btnMarkSelected">
                                <i class="fas fa-envelope-open mr-1"></i> Tandai Dibaca
                            </button>

                            <button class="btn btn-outline-primary" id="btnMarkAll">
                                <i class="fas fa-check-double mr-1"></i> Baca Semua
                            </button>

                            <button class="btn btn-outline-danger" id="btnDeleteSelected">
                                <i class="fas fa-trash mr-1"></i> Hapus Terpilih
                            </button>

                            <button class="btn btn-danger" id="btnDeleteAll">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus Semua
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table-1" class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:40px;"></th>
                                    <th style="width:60px;">No</th>
                                    <th>Judul</th>
                                    <th>Pesan</th>
                                    <th style="width:120px;">Tipe</th>
                                    <th style="width:120px;">Status</th>
                                    <th style="width:160px;">Waktu</th>
                                    <th style="width:180px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifikasi as $item)
                                <tr
                                    data-id="{{ $item->id_notifikasi }}"
                                    data-status="{{ $item->status_baca ? 'read' : 'unread' }}"
                                    data-deleted="{{ $item->deleted_at ? 1 : 0 }}"
                                    class="{{ !$item->status_baca ? 'table-primary' : '' }}">

                                    <!-- CHECKBOX -->
                                    <td>
                                        <input type="checkbox" class="row-check" value="{{ $item->id_notifikasi }}">
                                    </td>

                                    <!-- AUTO NUMBER -->
                                    <td></td>

                                    <td>
                                        <div class="font-weight-{{ $item->status_baca ? 'normal' : 'bold' }}">
                                            {{ $item->judul }}
                                        </div>
                                        <small class="text-muted">
                                            ID: {{ $item->id_notifikasi }}
                                        </small>
                                    </td>

                                    <td class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($item->isi_pesan, 90) }}
                                    </td>

                                    <td>
                                        <span class="badge badge-info text-uppercase">
                                            {{ $item->tipe ?? 'umum' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($item->deleted_at)
                                        <span class="badge badge-dark">Terhapus</span>
                                        @elseif($item->status_baca)
                                        <span class="badge badge-success">Dibaca</span>
                                        @else
                                        <span class="badge badge-warning">Baru</span>
                                        @endif
                                    </td>

                                    <td>
                                        <small class="text-muted">
                                            {{ optional($item->created_at)->format('d M Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($tab === 'deleted')
                                        <button type="button"
                                            class="btn btn-sm btn-success btn-restore-single"
                                            data-url="{{ route('perusahaan.notifikasi.restore', $item->id_notifikasi) }}">
                                            <i class="fas fa-trash-restore"></i> Pulihkan
                                        </button>

                                        <button type="button"
                                            class="btn btn-sm btn-danger btn-force-delete-single"
                                            data-url="{{ route('perusahaan.notifikasi.forceDelete', $item->id_notifikasi) }}">
                                            <i class="fas fa-skull-crossbones"></i> Permanen
                                        </button>
                                        @else
                                        <a href="{{ route('perusahaan.notifikasi.show', $item->id_notifikasi) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Baca
                                        </a>

                                        <button type="button"
                                            class="btn btn-sm btn-danger btn-delete-single"
                                            data-url="{{ route('perusahaan.notifikasi.destroy', $item->id_notifikasi) }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        Tidak ada notifikasi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

        </div>
    </section>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-css/table.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('admin-js/alerts.js') }}"></script>
<script src="{{ asset('admin-js/modal.js') }}"></script>

<script>
    const csrfToken = "{{ csrf_token() }}";
    const routeMarkSelected = "{{ route('perusahaan.notifikasi.markSelected') }}";
    const routeMarkAll = "{{ route('perusahaan.notifikasi.markAll') }}";
    const routeDeleteSelected = "{{ route('perusahaan.notifikasi.deleteSelected') }}";
    const routeDeleteAll = "{{ route('perusahaan.notifikasi.deleteAll') }}";

    const routeRestoreSelected = "{{ route('perusahaan.notifikasi.restoreSelected') }}";
    const routeForceDeleteSelected = "{{ route('perusahaan.notifikasi.forceDeleteSelected') }}";
    const routeForceDeleteAll = "{{ route('perusahaan.notifikasi.forceDeleteAll') }}";
</script>

<script src="{{ asset('admin-js/perusahaan-notifikasi.js') }}"></script>
@endpush