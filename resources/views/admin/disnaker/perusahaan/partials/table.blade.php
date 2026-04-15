<table id="table-1" class="table table-responsive table-bordered table-striped table-hover">
    <thead class="text-center">
        <tr>
            <th width="5%">No.</th>
            <th>ID Perusahaan</th>
            <th>ID Pengguna</th>
            <th>Nama Perusahaan</th>
            <th>NIB</th>
            <th>NPWP</th>
            <th>Kabupaten</th>
            <th>Provinsi</th>
            <th>Telepon</th>
            <th>Website</th>
            <th>Logo</th>
            <th width="120">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($perusahaan as $item)
        <tr data-deleted="{{ $item->deleted_at ? 1 : 0 }}">
            <td class="text-center"></td>

            <td>{{ $item->id_perusahaan }}</td>
            <td>{{ $item->id_pengguna }}</td>
            <td>{{ $item->nama_perusahaan }}</td>
            <td>{{ $item->nib ?? '-' }}</td>
            <td>{{ $item->npwp ?? '-' }}</td>
            <td>{{ $item->kabupaten ?? '-' }}</td>
            <td>{{ $item->provinsi ?? '-' }}</td>
            <td>{{ $item->nomor_telepon ?? '-' }}</td>

            <td>
                @if($item->website)
                <a href="{{ $item->website }}" target="_blank">
                    {{ $item->website }}
                </a>
                @else
                -
                @endif
            </td>
            <td>
                @if($item->logo)
                <a href="{{ asset('storage/'.$item->logo) }}"
                    target="_blank"
                    class="btn btn-outline-info btn-sm">
                    <i class="fas fa-image"></i> Lihat
                </a>
                @else
                <span class="text-muted">Tidak ada</span>
                @endif
            </td>

            <td class="text-center">
                @if($item->deleted_at)

                {{-- Restore --}}
                <form action="{{ route('perusahaan.restore', $item->id_perusahaan) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button"
                        class="btn btn-success btn-sm btn-restore"
                        data-url="{{ route('perusahaan.restore', $item->id_perusahaan) }}"
                        title="Pulihkan">
                        <i class="fas fa-undo"></i>
                    </button>
                </form>

                {{-- Force Delete --}}
                <form action="{{ route('perusahaan.forceDelete', $item->id_perusahaan) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        class="btn btn-danger btn-sm btn-force-delete"
                        data-url="{{ route('perusahaan.forceDelete', $item->id_perusahaan) }}"
                        title="Hapus Permanen">
                        <i class="fas fa-times"></i>
                    </button>
                </form>

                @else

                {{-- Edit --}}
                <a href="{{ route('perusahaan.edit', $item->id_perusahaan) }}"
                    class="btn btn-warning btn-sm"
                    title="Edit">
                    <i class="fas fa-edit"></i>
                </a>

                {{-- Soft Delete --}}
                <form action="{{ route('perusahaan.delete', $item->id_perusahaan) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        class="btn btn-danger btn-sm btn-hapus"
                        data-url="{{ route('perusahaan.delete', $item->id_perusahaan) }}"
                        title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>

                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="11" class="text-center text-muted">
                <i class="fas fa-database"></i> Tidak ada data perusahaan
            </td>
        </tr>
        @endforelse
    </tbody>
</table>