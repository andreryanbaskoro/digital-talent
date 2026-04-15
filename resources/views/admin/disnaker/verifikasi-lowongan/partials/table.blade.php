<table id="table-1" class="table table-bordered table-responsive table-striped table-hover">
    <thead class="text-center">
        <tr>
            <th>No.</th>
            <th>ID</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Lokasi</th>
            <th>Jenis</th>
            <th>Gaji</th>
            <th>Pendidikan</th>
            <th>Pengalaman</th>
            <th>Kuota</th>
            <th>Periode</th>
            <th>Status</th>
            <th>Catatan</th>
            <th width="20%">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($lowongan as $item)
        <tr
            data-status="{{ $item->status }}"
            data-deleted="{{ $item->deleted_at ? 1 : 0 }}">

            <td class="text-center"></td>

            {{-- ID --}}
            <td>{{ $item->id_lowongan }}</td>

            {{-- Judul --}}
            <td>{{ $item->judul_lowongan }}</td>

            {{-- Deskripsi --}}
            <td style="max-width:200px;">
                {{ \Illuminate\Support\Str::limit($item->deskripsi, 50) ?? '-' }}
            </td>

            {{-- Lokasi --}}
            <td>{{ $item->lokasi }}</td>

            {{-- Jenis --}}
            <td>{{ ucfirst($item->jenis_pekerjaan) }} {{ ucfirst($item->sistem_kerja) }}</td>

            {{-- Gaji --}}
            <td>
                @if($item->gaji_minimum && $item->gaji_maksimum)
                Rp{{ number_format($item->gaji_minimum) }} -
                Rp{{ number_format($item->gaji_maksimum) }}
                @else
                -
                @endif
            </td>

            {{-- Pendidikan --}}
            <td>{{ $item->pendidikan_minimum ?? '-' }}</td>

            {{-- Pengalaman --}}
            <td>{{ $item->pengalaman_minimum ?? '-' }}</td>

            {{-- Kuota --}}
            <td class="text-center">{{ $item->kuota }}</td>

            <td>
                @if($item->tanggal_mulai && $item->tanggal_berakhir)
                <small class="text-success">
                    <i class="fas fa-play"></i>
                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                </small>
                <br>
                <small class="text-danger">
                    <i class="fas fa-stop"></i>
                    {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y') }}
                </small>
                @else
                -
                @endif
            </td>

            @php
            $status = $item->status;
            $badge = [
            'pending' => 'warning',
            'disetujui' => 'success',
            'ditolak' => 'danger',
            ];
            @endphp

            <td class="text-center">
                @if($item->deleted_at)
                <span class="badge badge-danger">Terhapus</span>
                @else
                <span class="badge badge-{{ $badge[$status] ?? 'secondary' }}">
                    {{ ucfirst($status) }}
                </span>
                @endif
            </td>

            <td>
                {{ $item->catatan ?? '-' }}
            </td>

            {{-- Aksi --}}
            <td class="text-center">

                {{-- Lihat --}}
                <a href="#"
                    class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                </a>

                {{-- Hanya tampil jika pending --}}
                @if($item->status == 'pending')

                {{-- Approve --}}
                <button type="button"
                    class="btn btn-success btn-sm btn-approve"
                    data-url="{{ route('disnaker.verifikasi.approve', $item->id_lowongan) }}">
                    <i class="fas fa-check"></i>
                </button>

                {{-- Reject --}}
                <button type="button"
                    class="btn btn-danger btn-sm btn-reject"
                    data-url="{{ route('disnaker.verifikasi.reject', $item->id_lowongan) }}">
                    <i class="fas fa-times"></i>
                </button>

                @endif

            </td>
        </tr>

        @empty
        <tr>
            <td colspan="17" class="text-center text-muted">
                Tidak ada data lowongan
            </td>
        </tr>
        @endforelse
    </tbody>
</table>