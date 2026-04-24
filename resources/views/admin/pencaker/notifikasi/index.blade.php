@extends('layouts.app-admin')

@section('content')

<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center mb-2">
                <div>
                    <h1 class="mb-0">Notifikasi</h1>
                    <small class="text-muted">
                        Informasi hasil lamaran dan aktivitas terkait akun Anda.
                    </small>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">

            @include('admin.perusahaan.lowongan.partials.alerts')

            <div class="card card-primary card-outline card-outline-tabs shadow-sm">

                <!-- HEADER -->
                <div class="card-header p-0 border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center px-3 pt-3">

                        <!-- TABS -->
                        <ul class="nav nav-tabs">

                            <li class="nav-item">
                                <a class="nav-link {{ $tab === 'all' ? 'active' : '' }}"
                                    href="{{ route('pencaker.notifikasi.index', ['tab' => 'all']) }}">
                                    Semua
                                    <span class="badge badge-light ml-1">{{ $countAll }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $tab === 'unread' ? 'active' : '' }}"
                                    href="{{ route('pencaker.notifikasi.index', ['tab' => 'unread']) }}">
                                    Belum Dibaca
                                    <span class="badge badge-light ml-1">{{ $countUnread }}</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $tab === 'deleted' ? 'active' : '' }}"
                                    href="{{ route('pencaker.notifikasi.index', ['tab' => 'deleted']) }}">
                                    <i class="fas fa-trash text-danger"></i>
                                    Terhapus
                                    <span class="badge badge-light ml-1">{{ $countDeleted }}</span>
                                </a>
                            </li>

                        </ul>

                    </div>
                </div>

                <!-- BODY -->
                <div class="card-body p-0">

                    <!-- BULK ACTION -->
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">

                        <div>
                            <input type="checkbox" id="checkAll">
                            <label for="checkAll" class="font-weight-bold ml-1">Pilih Semua</label>
                        </div>

                        <div class="btn-group btn-group-sm">

                            @if($tab === 'deleted')
                            <button class="btn btn-success" id="btnRestoreSelected">
                                Pulihkan
                            </button>

                            <button class="btn btn-danger" id="btnForceDeleteSelected">
                                Hapus Permanen
                            </button>

                            <button class="btn btn-dark" id="btnForceDeleteAll">
                                Hapus Semua Permanen
                            </button>
                            @else
                            <button class="btn btn-outline-success" id="btnMarkSelected">
                                Tandai Dibaca
                            </button>

                            <button class="btn btn-outline-primary" id="btnMarkAll">
                                Baca Semua
                            </button>

                            <button class="btn btn-outline-danger" id="btnDeleteSelected">
                                Hapus
                            </button>

                            <button class="btn btn-danger" id="btnDeleteAll">
                                Hapus Semua
                            </button>
                            @endif

                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table id="table-1" class="table table-hover mb-0">

                            <thead class="thead-light">
                                <tr>
                                    <th width="40"></th>
                                    <th width="60">No</th>
                                    <th>Judul</th>
                                    <th>Pesan</th>
                                    <th width="120">Status</th>
                                    <th width="160">Waktu</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($notifikasi as $item)
                                <tr class="{{ !$item->status_baca ? 'table-primary' : '' }}"
                                    data-id="{{ $item->id_notifikasi }}">

                                    <td>
                                        <input type="checkbox" class="row-check" value="{{ $item->id_notifikasi }}">
                                    </td>

                                    <!-- nomor auto dari JS -->
                                    <td></td>

                                    <td>
                                        <strong>{{ $item->judul }}</strong><br>
                                        <small class="text-muted">{{ $item->id_notifikasi }}</small>
                                    </td>

                                    <td class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($item->isi_pesan, 100) }}
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
                                        <small>{{ optional($item->created_at)->format('d M Y H:i') }}</small>
                                    </td>

                                    <td>
                                        @if($tab === 'deleted')

                                        <button class="btn btn-success btn-sm btn-restore-single"
                                            data-url="{{ route('pencaker.notifikasi.restore', $item->id_notifikasi) }}">
                                            Pulihkan
                                        </button>

                                        <button class="btn btn-danger btn-sm btn-force-delete-single"
                                            data-url="{{ route('pencaker.notifikasi.forceDelete', $item->id_notifikasi) }}">
                                            Hapus
                                        </button>

                                        @else

                                        <a href="{{ route('pencaker.notifikasi.show', $item->id_notifikasi) }}"
                                            class="btn btn-info btn-sm">
                                            Baca
                                        </a>

                                        <button class="btn btn-danger btn-sm btn-delete-single"
                                            data-url="{{ route('pencaker.notifikasi.destroy', $item->id_notifikasi) }}">
                                            Hapus
                                        </button>

                                        @endif
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Tidak ada notifikasi
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
    const routeMarkSelected = "{{ route('pencaker.notifikasi.markSelected') }}";
    const routeMarkAll = "{{ route('pencaker.notifikasi.markAll') }}";
    const routeDeleteSelected = "{{ route('pencaker.notifikasi.deleteSelected') }}";
    const routeDeleteAll = "{{ route('pencaker.notifikasi.deleteAll') }}";

    const routeRestoreSelected = "{{ route('pencaker.notifikasi.restoreSelected') }}";
    const routeForceDeleteSelected = "{{ route('pencaker.notifikasi.forceDeleteSelected') }}";
    const routeForceDeleteAll = "{{ route('pencaker.notifikasi.forceDeleteAll') }}";
</script>

<script src="{{ asset('admin-js/pencaker-notifikasi.js') }}"></script>
@endpush