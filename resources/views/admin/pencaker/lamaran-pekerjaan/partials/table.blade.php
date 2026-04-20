<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-briefcase"></i> Data Lamaran Pekerjaan
        </h5>
    </div>

    <div class="card-body">

        <table id="table-1" class="table table-bordered table-striped table-hover">

            <thead class="text-center bg-light">
                <tr>
                    <th width="5%">No.</th>
                    <th>ID Lamaran</th>
                    <th>Lowongan</th>
                    <th>Pelamar</th>
                    <th>Tanggal Lamar</th>
                    <th>Status</th>
                    <th width="18%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($lamaran as $index => $item)
                <tr data-status="{{ $item->status_lamaran }}">

                    {{-- NO --}}
                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    {{-- ID LAMARAN --}}
                    <td class="text-center">
                        <code>{{ $item->id_lamaran }}</code>
                    </td>

                    {{-- LOWONGAN --}}
                    <td>
                        <strong>{{ $item->lowongan->judul_lowongan ?? '-' }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $item->lowongan->lokasi ?? '' }}
                        </small>
                    </td>

                    {{-- PELAMAR --}}
                    <td>
                        {{ $item->pencariKerja->nama ?? '-' }}
                        <br>
                        <small class="text-muted">
                            {{ $item->pencariKerja->email ?? '' }}
                        </small>
                    </td>

                    {{-- TANGGAL --}}
                    <td class="text-center">
                        {{ $item->tanggal_lamar ? \Carbon\Carbon::parse($item->tanggal_lamar)->format('d M Y') : '-' }}
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">

                        @php
                        $status = $item->status_lamaran;
                        @endphp

                        <span class="badge
                            {{ $status == 'diterima' ? 'badge-success' : '' }}
                            {{ $status == 'ditolak' ? 'badge-danger' : '' }}
                            {{ $status == 'diproses' ? 'badge-warning' : '' }}
                            {{ $status == 'dikirim' ? 'badge-primary' : '' }}">

                            <i class="fas fa-circle"></i>
                            {{ ucfirst($status) }}
                        </span>

                    </td>

                    {{-- AKSI --}}
                    <td class="text-center">

                        {{-- DETAIL --}}
                        <a href="{{ route('pencaker.lamaran.show', $item->id_lamaran) }}"
                            class="btn btn-info btn-sm"
                            data-toggle="tooltip"
                            title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>

                        {{-- EDIT STATUS (opsional HR) --}}
                        @php
                        $expired = $item->lowongan->tanggal_berakhir
                        && \Carbon\Carbon::parse($item->lowongan->tanggal_berakhir)->lt(now());
                        @endphp

                        @if(!$expired)
                        <a href="{{ route('pencaker.lamaran.edit', $item->id_lamaran) }}"
                            class="btn btn-warning btn-sm"
                            data-toggle="tooltip"
                            title="Edit Lamaran">
                            <i class="fas fa-edit"></i>
                        </a>
                        @else
                        <button class="btn btn-secondary btn-sm" disabled title="Lowongan sudah berakhir">
                            <i class="fas fa-lock"></i>
                        </button>
                        @endif

                        {{-- DELETE --}}
                        <button type="button"
                            class="btn btn-danger btn-sm btn-hapus"
                            data-url="{{ route('pencaker.lamaran.cancel', $item->id_lamaran) }}"
                            data-toggle="tooltip"
                            title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-folder-open fa-2x"></i>
                        <br>
                        Belum ada data lamaran
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>