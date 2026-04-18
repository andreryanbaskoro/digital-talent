<table id="table-1" class="table table-bordered table-striped table-hover">
    <thead class="text-center">
        <tr>
            <th width="5%">No.</th>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Peran</th>
            <th>Status</th>
            <th width="20%">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($pengguna as $user)
        <tr
            data-status="{{ $user->status }}"
            data-deleted="{{ $user->deleted_at ? '1' : '0' }}">
            <td class="text-center"></td>
            <td>#{{ $user->id_pengguna }}</td>
            <td>{{ $user->nama }}</td>

            <td>{{ $user->email }}</td>

            <td class="text-center">
                {{ ucfirst($user->peran) }}
            </td>

            <td class="text-center">
                @if($user->deleted_at)
                <span class="badge badge-danger">
                    <i class="fas fa-trash"></i> Terhapus
                </span>
                @else
                <span class="badge badge-{{ $user->status == 'aktif' ? 'success' : 'secondary' }}">
                    <i class="fas fa-circle"></i> {{ ucfirst($user->status) }}
                </span>
                @endif
            </td>

            <td class="text-center">

                <a href="{{ route('disnaker.pengguna.show', $user->id_pengguna) }}"
                    class="btn btn-info btn-sm"
                    data-toggle="tooltip"
                    title="Detail">
                    <i class="fas fa-eye"></i>
                </a>

                @if($user->deleted_at)

                <form action="{{ route('disnaker.pengguna.restore', $user->id_pengguna) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button"
                        class="btn btn-success btn-sm btn-restore" data-toggle="tooltip"
                        title="Pulihkan Data"
                        data-url="{{ route('disnaker.pengguna.restore', $user->id_pengguna) }}">
                        <i class="fas fa-undo"></i>
                    </button>
                </form>

                <form action="{{ route('disnaker.pengguna.forceDelete', $user->id_pengguna) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        class="btn btn-danger btn-sm btn-force-delete" data-toggle="tooltip"
                        title="Hapus Permanen"
                        data-url="{{ route('disnaker.pengguna.forceDelete', $user->id_pengguna) }}">
                        <i class="fas fa-times"></i>
                    </button>
                </form>

                @else

                <a href="{{ route('disnaker.pengguna.edit', $user->id_pengguna) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Data">
                    <i class="fas fa-edit"></i>
                </a>

                <form action="{{ route('disnaker.pengguna.destroy', $user->id_pengguna) }}"
                    method="POST"
                    class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button type="button"
                        class="btn btn-danger btn-sm btn-hapus" data-toggle="tooltip" title="Hapus Data"
                        data-url="{{ route('disnaker.pengguna.destroy', $user->id_pengguna) }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>

                @endif

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">
                <i class="fas fa-database"></i> Tidak ada data pengguna
            </td>
        </tr>
        @endforelse
    </tbody>
</table>