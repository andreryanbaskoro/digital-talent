<div class="table-responsive">
    <table id="table-1" class="table table-bordered table-hover align-middle">
        <thead class="text-center table-light">
            <tr>
                <th width="5%">No</th>
                <th>Identitas</th>
                <th>Nama Perusahaan</th>
                <th>Legalitas</th>
                <th>Lokasi</th>
                <th>Kontak</th>
                <th>Logo</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($perusahaan as $item)
            <tr data-deleted="{{ $item->deleted_at ? 1 : 0 }}">
                <td class="text-center"></td>

                {{-- IDENTITAS --}}
                <td>
                    <div><strong>ID:</strong> {{ $item->id_perusahaan }}</div>
                    <small class="text-muted">
                        User: {{ $item->id_pengguna }}
                    </small>
                </td>

                {{-- NAMA --}}
                <td class="fw-semibold">
                    {{ $item->nama_perusahaan }}
                </td>

                {{-- LEGALITAS --}}
                <td>
                    <div><strong>NIB:</strong> {{ $item->nib ?? '-' }}</div>
                    <small class="text-muted">
                        NPWP: {{ $item->npwp ?? '-' }}
                    </small>
                </td>

                {{-- LOKASI --}}
                <td>
                    <div>{{ $item->kabupaten ?? '-' }}</div>
                    <small class="text-muted">
                        {{ $item->provinsi ?? '-' }}
                    </small>
                </td>

                {{-- KONTAK --}}
                <td style="max-width:180px;">
                    <div>{{ $item->nomor_telepon ?? '-' }}</div>
                    @if($item->website)
                    <small>
                        <a href="{{ $item->website }}"
                            target="_blank"
                            class="text-primary text-decoration-none text-truncate d-inline-block w-100">
                            {{ $item->website }}
                        </a>
                    </small>
                    @endif
                </td>

                {{-- LOGO --}}
                <td class="text-center">
                    @if($item->logo)
                    <a href="{{ asset('storage/'.$item->logo) }}"
                        target="_blank"
                        class="btn btn-outline-info btn-sm">
                        <i class="fas fa-image"></i>
                    </a>
                    @else
                    <span class="text-muted small">-</span>
                    @endif
                </td>

                {{-- AKSI --}}
                <td class="text-center">
                    @if($item->deleted_at)

                    <form action="{{ route('disnaker.perusahaan.restore', $item->id_perusahaan) }}"
                        method="POST" class="d-inline">
                        @csrf
                        <button type="button"
                            class="btn btn-success btn-sm btn-restore"
                            data-url="{{ route('disnaker.perusahaan.restore', $item->id_perusahaan) }}">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>

                    <form action="{{ route('disnaker.perusahaan.forceDelete', $item->id_perusahaan) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="btn btn-danger btn-sm btn-force-delete"
                            data-url="{{ route('disnaker.perusahaan.forceDelete', $item->id_perusahaan) }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>

                    @else

                    <a href="{{ route('disnaker.perusahaan.edit', $item->id_perusahaan) }}"
                        class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('disnaker.perusahaan.destroy', $item->id_perusahaan) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="btn btn-danger btn-sm btn-hapus"
                            data-url="{{ route('disnaker.perusahaan.destroy', $item->id_perusahaan) }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    Tidak ada data perusahaan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>