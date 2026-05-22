<?php

namespace App\Exports;

use App\Models\LamaranPekerjaan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;

class LaporanPencariKerjaExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    WithEvents,
    ShouldAutoSize
{
    protected array $filters;
    protected string $mode;
    protected int $rowNumber = 0;

    public function __construct(array $filters = [], string $mode = 'disnaker')
    {
        $this->filters = $filters;
        $this->mode    = $mode;
    }

    protected function currentIdPengguna(): ?string
    {
        $user = Auth::user();

        return data_get($user, 'id_pengguna')
            ?? data_get($user, 'id')
            ?? null;
    }

    protected function buildQuery()
    {
        $query = LamaranPekerjaan::withTrashed()
            ->with([
                'pencariKerja.pengguna',
                'pencariKerja.kartuAk1.keterampilan',
                'pencariKerja.kartuAk1.riwayatPendidikan',
                'lowongan',
            ]);

        // Mode perusahaan
        if ($this->mode === 'perusahaan') {

            $idPengguna = $this->currentIdPengguna();

            if (!$idPengguna) {
                abort(403, 'Akun perusahaan tidak valid.');
            }

            $query->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                $q->withTrashed()->where('id_pengguna', $idPengguna);
            });
        }

        // Filter nama pekerjaan
        if (!empty($this->filters['nama_pekerjaan'])) {
            $query->whereHas('lowongan', function ($q) {
                $q->withTrashed()->where(
                    'judul_lowongan',
                    'like',
                    '%' . $this->filters['nama_pekerjaan'] . '%'
                );
            });
        }

        // Filter jenis pekerjaan
        if (!empty($this->filters['jenis_pekerjaan'])) {
            $query->whereHas('lowongan', function ($q) {
                $q->withTrashed()->where(
                    'jenis_pekerjaan',
                    $this->filters['jenis_pekerjaan']
                );
            });
        }

        // Filter tanggal pendaftaran
        if (!empty($this->filters['tanggal_pendaftaran'])) {

            $start = \Carbon\Carbon::parse(
                $this->filters['tanggal_pendaftaran']
            )->startOfMonth();

            $end = \Carbon\Carbon::parse(
                $this->filters['tanggal_pendaftaran']
            )->endOfMonth();

            $query->whereBetween('tanggal_lamar', [$start, $end]);
        }

        // Filter jenis kelamin
        if (!empty($this->filters['jenis_kelamin'])) {
            $query->whereHas('pencariKerja', function ($q) {
                $q->where(
                    'jenis_kelamin',
                    $this->filters['jenis_kelamin']
                );
            });
        }

        return $query
            ->orderByDesc('tanggal_lamar')
            ->get();
    }

    public function collection()
    {
        $this->rowNumber = 0;
        return $this->buildQuery();
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pencari Kerja',
            'Email',
            'No. Telepon',
            'Domisili',
            'Jenis Kelamin',
            'Pendidikan Terakhir',
            'Keahlian',
            'Nama Pekerjaan Dilamar',
            'Tanggal Mendaftar',
            'Status Akun',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;

        $profil = $item->pencariKerja;

        // Pendidikan terakhir
        $pendidikan = optional($profil?->kartuAk1)
            ->riwayatPendidikan
            ?->sortByDesc('tahun_lulus')
            ->first()
            ?->jenjang
            ?? $profil?->pendidikan
            ?? $profil?->pendidikan_terakhir
            ?? '-';

        // Keahlian
        $keahlian = optional($profil?->kartuAk1)
            ->keterampilan
            ?->pluck('nama_keterampilan')
            ->filter()
            ->implode(', ')
            ?? '-';

        // Domisili
        $domisili = collect([
            $profil?->kelurahan,
            $profil?->kecamatan,
            $profil?->kab_kota,
        ])->filter()->implode(', ') ?: '-';

        return [
            $this->rowNumber,
            $profil?->nama_lengkap ?? '-',
            $profil?->email ?? '-',
            $profil?->nomor_hp ?? '-',
            $domisili,
            $profil?->jenis_kelamin ?? '-',
            $pendidikan,
            $keahlian,
            $item?->lowongan?->judul_lowongan ?? '-',
            $item->tanggal_lamar
                ? \Carbon\Carbon::parse($item->tanggal_lamar)->format('d-m-Y')
                : '-',
            optional($profil?->pengguna)->status ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $totalCols  = 11;
                $lastColumn = Coordinate::stringFromColumnIndex($totalCols);
                $lastRow    = $sheet->getHighestRow();

                // HEADER ATAS
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->mergeCells("A2:{$lastColumn}2");

                $modeLabel = $this->mode === 'disnaker' ? 'DISNAKER' : 'PERUSAHAAN';
                $sheet->setCellValue('A1', 'LAPORAN DATA PENCARI KERJA - ' . $modeLabel);
                $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d-m-Y H:i'));

                $sheet->getStyle("A1:{$lastColumn}2")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A1')->getFont()->setSize(14);
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(6);

                // HEADING TABEL
                $sheet->getStyle("A4:{$lastColumn}4")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'name' => 'Arial',
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9EAF7'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '4472C4'],
                        ],
                    ],
                ]);

                $sheet->getRowDimension(4)->setRowHeight(28);

                // ISI TABEL
                if ($lastRow >= 5) {
                    $sheet->getStyle("A5:{$lastColumn}{$lastRow}")->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'size' => 10,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color'       => ['rgb' => 'B8C9E0'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    // Zebra stripe
                    for ($r = 5; $r <= $lastRow; $r++) {
                        if ($r % 2 === 0) {
                            $sheet->getStyle("A{$r}:{$lastColumn}{$r}")
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('EEF4FB');
                        }
                    }

                    // Center kolom
                    foreach (['A', 'F', 'J', 'K'] as $col) {
                        $sheet->getStyle("{$col}5:{$col}{$lastRow}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // FOOTER
                $footerStartColIndex = $totalCols - 2;
                $footerStartCol      = Coordinate::stringFromColumnIndex($footerStartColIndex);

                $r1 = $lastRow + 3;
                $r2 = $lastRow + 4;
                $r3 = $lastRow + 7;
                $r4 = $lastRow + 8;

                foreach ([$r1, $r2, $r3, $r4] as $fr) {
                    $sheet->mergeCells("{$footerStartCol}{$fr}:{$lastColumn}{$fr}");
                }

                $sheet->setCellValue("{$footerStartCol}{$r1}", 'Mengetahui,');
                $sheet->setCellValue(
                    "{$footerStartCol}{$r2}",
                    $this->mode === 'disnaker' ? 'Kepala Dinas Tenaga Kerja' : 'Pimpinan Perusahaan'
                );
                $sheet->setCellValue("{$footerStartCol}{$r4}", '(...................................)');

                $sheet->getStyle("{$footerStartCol}{$r1}:{$lastColumn}{$r4}")->applyFromArray([
                    'font'      => ['name' => 'Arial', 'size' => 11],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension($r3)->setRowHeight(36);

                // LEBAR KOLOM
                $widths = [
                    'A' => 5,
                    'B' => 26,
                    'C' => 26,
                    'D' => 16,
                    'E' => 24,
                    'F' => 14,
                    'G' => 18,
                    'H' => 28,
                    'I' => 28,
                    'J' => 16,
                    'K' => 14,
                ];

                foreach ($widths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
