<table id="table-1" class="table table-bordered table-striped table-hover">
    <thead class="text-center">
        <tr>
            <th width="5%">No.</th>
            <th>ID AK1</th>
            <th>No Pendaftaran</th>
            <th>Nama Pemohon</th>
            <th>Tanggal Daftar</th>
            <th>Periode</th>
            <th>Status</th>
            <th>Catatan</th>
            <th width="20%">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($items as $item)

        <tr data-status="{{ $item->status }}">

            <td class="text-center"></td>
            <td>{{ $item->id_kartu_ak1 }}</td>
            <td>{{ $item->nomor_pendaftaran }}</td>
            <td>{{ $item->profilPencariKerja->nama_lengkap ?? '-' }}</td>
            <td>{{ $item->tanggal_daftar ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d-m-Y') : '-' }}</td>
            <td>
                @if($item->status === 'disetujui' && $item->berlaku_mulai && $item->berlaku_sampai)
                {{ \Carbon\Carbon::parse($item->berlaku_mulai)->format('d-m-Y') }}
                -
                {{ \Carbon\Carbon::parse($item->berlaku_sampai)->format('d-m-Y') }}
                @else
                -
                @endif
            </td>

            <td class="text-center">
                @switch($item->status)
                @case('draft')
                <span class="badge badge-secondary">Draft</span>
                @break
                @case('pending')
                <span class="badge badge-warning text-white">Pending</span>
                @break
                @case('disetujui')
                <span class="badge badge-success">Disetujui</span>
                @break
                @case('ditolak')
                <span class="badge badge-danger">Ditolak</span>
                @break
                @default
                <span class="badge badge-light">Unknown</span>
                @endswitch
            </td>
            <td class="text-center">
                {{ $item->catatan_petugas ?? '-' }}
            </td>

            <td class="text-center">

                <!-- DETAIL -->
                <button type="button"
                    class="btn btn-info btn-sm btn-detail"
                    data-id="{{ $item->id_kartu_ak1 }}"
                    title="Detail">
                    <i class="fas fa-eye"></i>
                </button>

                @if($item->status !== 'disetujui')
                <button type="button"
                    class="btn btn-primary btn-sm btn-status"
                    data-id="{{ $item->id_kartu_ak1 }}"
                    data-status="{{ $item->status }}"
                    title="Verifikasi">
                    <i class="fas fa-check"></i>
                </button>
                @endif

            </td>

        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted">
                <i class="fas fa-database"></i> Tidak ada data AK1
            </td>
        </tr>
        @endforelse
    </tbody>
</table>