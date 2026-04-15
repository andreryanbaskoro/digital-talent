<table id="table-1" class="table table-responsive table-bordered table-striped table-hover">
    <thead class="text-center">
        <tr>
            <th width="5%">No.</th>
            <th>ID</th>
            <th>Nama Lengkap</th>
            <th>NIK</th>
            <th>Nomor KK</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>Status Perkawinan</th>
            <th>Alamat</th>
            <th>Nomor HP</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($pencariKerja as $kerja)
        <tr data-status="{{ $kerja->status }}" data-deleted="{{ $kerja->deleted_at ? '1' : '0' }}">
            <td class="text-center"></td>
            <td>{{ $kerja->id_pencari_kerja }}</td>
            <td>{{ $kerja->nama_lengkap }}</td>
            <td>{{ $kerja->nik }}</td>
            <td>{{ $kerja->nomor_kk }}</td>
            <td>{{ $kerja->tempat_lahir }}</td>
            <td>{{ $kerja->tanggal_lahir->format('d/m/Y') }}</td>
            <td>{{ ucfirst($kerja->jenis_kelamin) }}</td>
            <td>{{ $kerja->agama }}</td>
            <td>{{ $kerja->status_perkawinan }}</td>
            <td>{{ $kerja->alamat }}</td>
            <td>{{ $kerja->nomor_hp }}</td>
            <td>{{ $kerja->email }}</td>

            <td class="text-center">
                @if($kerja->deleted_at)
                <form action="{{ route('pencari_kerja.restore', $kerja->id_pencari_kerja) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button" class="btn btn-success btn-sm btn-restore" data-toggle="tooltip" title="Pulihkan Data" data-url="{{ route('pencari_kerja.restore', $kerja->id_pencari_kerja) }}">
                        <i class="fas fa-undo"></i>
                    </button>
                </form>

                <form action="{{ route('pencari_kerja.forceDelete', $kerja->id_pencari_kerja) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm btn-force-delete" data-toggle="tooltip" title="Hapus Permanen" data-url="{{ route('pencari_kerja.forceDelete', $kerja->id_pencari_kerja) }}">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
                @else
                <a href="{{ route('pencari_kerja.edit', $kerja->id_pencari_kerja) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Data">
                    <i class="fas fa-edit"></i>
                </a>

                <form action="{{ route('pencari_kerja.delete', $kerja->id_pencari_kerja) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm btn-hapus" data-toggle="tooltip" title="Hapus Data" data-url="{{ route('pencari_kerja.delete', $kerja->id_pencari_kerja) }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="14" class="text-center text-muted">
                <i class="fas fa-database"></i> Tidak ada data pencari kerja
            </td>
        </tr>
        @endforelse
    </tbody>
</table>