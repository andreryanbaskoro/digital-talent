<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table id="table-1" class="table table-hover table-striped mb-0">

                <thead class="bg-light text-center">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Lokasi</th>
                        <th>Jenis</th>
                        <th>Gaji</th>
                        <th>Pendidikan</th>
                        <th>Pengalaman</th>
                        <th>Kuota</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($lowongan as $i => $item)
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

                        {{-- Judul --}}
                        <td>
                            <div class="font-weight-bold">
                                {{ $item->judul_lowongan }}
                            </div>

                            <small class="text-muted d-block">
                                {{ \Illuminate\Support\Str::limit($item->deskripsi, 40) }}
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

                        {{-- Pendidikan --}}
                        <td>{{ $item->pendidikan_minimum ?? '-' }}</td>

                        {{-- Pengalaman --}}
                        <td>{{ $item->pengalaman_minimum ?? '-' }}</td>

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
                                <i class="fas fa-play"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                            </small>
                            <small class="text-danger">
                                <i class="fas fa-stop"></i>
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

                        {{-- Catatan --}}
                        <td>
                            <small>
                                {{ $item->catatan ?? '-' }}
                            </small>
                        </td>

                        {{-- Aksi --}}
                        <td class="text-center">

                            {{-- View --}}
                            <!-- <a href="#"
                                class="btn btn-outline-info btn-sm"
                                title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a> -->

                            {{-- Approve / Reject --}}
                            @if($item->status == 'pending')

                            <button type="button"
                                class="btn btn-success btn-sm btn-approve"
                                data-url="{{ route('disnaker.verifikasi-lowongan.approve', $item->id_lowongan) }}"
                                title="Approve">
                                <i class="fas fa-check"></i>
                            </button>

                            <button type="button"
                                class="btn btn-danger btn-sm btn-reject"
                                data-url="{{ route('disnaker.verifikasi-lowongan.reject', $item->id_lowongan) }}"
                                title="Reject">
                                <i class="fas fa-times"></i>
                            </button>

                            @endif

                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="13" class="text-center text-muted py-4">
                            Tidak ada data lowongan
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>