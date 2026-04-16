<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table id="table-1" class="table table-hover table-striped mb-0">

                <thead class="bg-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>ID</th>
                        <th>Identitas</th>
                        <th>TTL</th>
                        <th>Personal</th>
                        <th>Kontak</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pencariKerja as $kerja)
                    <tr data-status="{{ $kerja->status }}"
                        data-deleted="{{ $kerja->deleted_at ? '1' : '0' }}">

                        {{-- No --}}
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        {{-- ID --}}
                        <td class="text-muted">
                            {{ $kerja->id_pencari_kerja }}
                        </td>

                        {{-- Identitas --}}
                        <td>
                            <div class="font-weight-bold">
                                {{ $kerja->nama_lengkap }}
                            </div>
                            <small class="text-muted">
                                NIK: {{ $kerja->nik }} <br>
                                KK: {{ $kerja->nomor_kk }}
                            </small>
                        </td>

                        {{-- TTL --}}
                        <td>
                            {{ $kerja->tempat_lahir }},
                            <br>
                            <small class="text-muted">
                                {{ $kerja->tanggal_lahir->format('d M Y') }}
                            </small>
                        </td>

                        {{-- Personal --}}
                        <td>
                            <span class="badge badge-secondary">
                                {{ $kerja->jenis_kelamin === 'L' ? 'Laki-Laki' : ($kerja->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                            </span> <br>
                            <span class="badge badge-light border">
                                {{ $kerja->status_perkawinan }}
                            </span>
                            <br>
                            <small class="text-muted">
                                {{ $kerja->agama }}
                            </small>
                        </td>

                        {{-- Kontak --}}
                        <td>
                            <small>
                                <i class="fas fa-phone"></i>
                                {{ $kerja->nomor_hp }} <br>
                                <i class="fas fa-envelope"></i>
                                {{ $kerja->email }}
                            </small>
                        </td>

                        {{-- Aksi --}}
                        <td class="text-center">

                            @if($kerja->deleted_at)

                            {{-- Restore --}}
                            <button type="button"
                                class="btn btn-success btn-sm btn-restore"
                                title="Pulihkan"
                                data-url="{{ route('disnaker.pencari-kerja.restore', $kerja->id_pencari_kerja) }}">
                                <i class="fas fa-undo"></i>
                            </button>

                            {{-- Force Delete --}}
                            <button type="button"
                                class="btn btn-danger btn-sm btn-force-delete"
                                title="Hapus Permanen"
                                data-url="{{ route('disnaker.pencari-kerja.forceDelete', $kerja->id_pencari_kerja) }}">
                                <i class="fas fa-times"></i>
                            </button>

                            @else

                            {{-- Edit --}}
                            <a href="{{ route('disnaker.pencari-kerja.edit', $kerja->id_pencari_kerja) }}"
                                class="btn btn-warning btn-sm"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Delete --}}
                            <button type="button"
                                class="btn btn-danger btn-sm btn-hapus"
                                title="Hapus"
                                data-url="{{ route('disnaker.pencari-kerja.destroy', $kerja->id_pencari_kerja) }}">
                                <i class="fas fa-trash"></i>
                            </button>

                            @endif

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-database"></i>
                            Tidak ada data pencari kerja
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>