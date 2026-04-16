<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table id="table-1" class="table table-hover table-striped mb-0">

                <thead class="bg-light text-center">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Lowongan</th>
                        <th>Lokasi</th>
                        <th>Jenis</th>
                        <th>Gaji</th>
                        <th>Kuota</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($lowongan as $item)
                    <tr data-status="{{ $item->status }}"
                        data-deleted="{{ $item->deleted_at ? 1 : 0 }}">

                        {{-- No --}}
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        {{-- ID --}}
                        <td class="text-muted">
                            {{ $item->id_lowongan }}
                        </td>

                        {{-- Judul + Deskripsi --}}
                        <td>
                            <div class="font-weight-bold">
                                {{ $item->judul_lowongan }}
                            </div>
                            <small class="text-muted d-block">
                                {{ \Illuminate\Support\Str::limit($item->deskripsi, 50) ?? '-' }}
                            </small>
                        </td>

                        {{-- Lokasi --}}
                        <td>{{ $item->lokasi ?? '-' }}</td>

                        {{-- Jenis --}}
                        <td>
                            <span class="badge badge-secondary">
                                {{ ucfirst($item->jenis_pekerjaan) }}
                            </span>
                            <span class="badge badge-light border">
                                {{ ucfirst($item->sistem_kerja) }}
                            </span>
                        </td>

                        {{-- Gaji --}}
                        <td>
                            @if($item->gaji_minimum && $item->gaji_maksimum)
                            <span class="text-success font-weight-bold">
                                Rp{{ number_format($item->gaji_minimum) }}
                            </span>
                            -
                            <span class="text-success font-weight-bold">
                                Rp{{ number_format($item->gaji_maksimum) }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Kuota --}}
                        <td class="text-center">
                            <span class="badge badge-info">
                                {{ $item->kuota }}
                            </span>
                        </td>

                        {{-- Periode --}}
                        <td>
                            @if($item->tanggal_mulai && $item->tanggal_berakhir)
                            <small class="text-success d-block">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                            </small>
                            <small class="text-danger">
                                {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y') }}
                            </small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="text-center">
                            @php
                            $badge = [
                            'pending' => 'warning',
                            'disetujui' => 'success',
                            'ditolak' => 'danger',
                            ];
                            @endphp

                            @if($item->deleted_at)
                            <span class="badge badge-dark">Terhapus</span>
                            @else
                            <span class="badge badge-{{ $badge[$item->status] ?? 'secondary' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                            @endif
                        </td>

                        {{-- AKSI PERUSAHAAN --}}
                        <td class="text-center">

                            {{-- VIEW --}}
                            <button
                                type="button"
                                class="btn btn-sm btn-info btn-show"
                                data-url="{{ route('perusahaan.lowongan.show', $item->id_lowongan) }}">
                                <i class="fas fa-eye"></i>
                            </button>

                            @if($item->deleted_at)

                            {{-- RESTORE --}}
                            <button type="button"
                                class="btn btn-success btn-sm btn-restore"
                                data-url="{{ route('perusahaan.lowongan.restore', $item->id_lowongan) }}"
                                title="Restore">
                                <i class="fas fa-undo"></i>
                            </button>

                            {{-- FORCE DELETE --}}
                            <button type="button"
                                class="btn btn-danger btn-sm btn-force-delete"
                                data-url="{{ route('perusahaan.lowongan.forceDelete', $item->id_lowongan) }}"
                                title="Hapus Permanen">
                                <i class="fas fa-times"></i>
                            </button>

                            @else

                            {{-- EDIT (kecuali sudah disetujui) --}}
                            @if($item->status != 'disetujui')
                            <a href="{{ route('perusahaan.lowongan.edit', $item->id_lowongan) }}"
                                class="btn btn-warning btn-sm"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif

                            {{-- DELETE --}}
                            <button type="button"
                                class="btn btn-danger btn-sm btn-hapus"
                                data-url="{{ route('perusahaan.lowongan.destroy', $item->id_lowongan) }}"
                                title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>

                            @endif

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            Tidak ada data lowongan
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>