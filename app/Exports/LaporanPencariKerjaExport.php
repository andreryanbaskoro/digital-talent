<?php

namespace App\Exports;

use App\Models\LamaranPekerjaan;
use App\Models\ProfilPencariKerja;
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
        if ($this->mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();

            if (!$idPengguna) {
                abort(403, 'Akun perusahaan tidak valid.');
            }

            $idPencariKerjaList = LamaranPekerjaan::withTrashed()
                ->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                    $q->withTrashed()->where('id_pengguna', $idPengguna);
                })
                ->when(!empty($this->filters['nama_pekerjaan']), function ($q) {
                    $q->whereHas('lowongan', function ($qq) {
                        $qq->withTrashed()->where('judul_lowongan', 'like', '%' . $this->filters['nama_pekerjaan'] . '%');
                    });
                })
                ->when(!empty($this->filters['jenis_pekerjaan']), function ($q) {
                    $q->whereHas('lowongan', function ($qq) {
                        $qq->withTrashed()->where('jenis_pekerjaan', $this->filters['jenis_pekerjaan']);
                    });
                })
                ->when(!empty($this->filters['tanggal_pendaftaran']), function ($q) {
                    $q->whereDate('tanggal_lamar', $this->filters['tanggal_pendaftaran']);
                })
                ->pluck('id_pencari_kerja')
                ->unique();

            $query = ProfilPencariKerja::withTrashed()
                ->with(['pengguna', 'kartuAk1.keterampilan'])
                ->whereIn('id_pencari_kerja', $idPencariKerjaList);
        } else {
            $query = ProfilPencariKerja::withTrashed()
                ->with(['pengguna', 'kartuAk1.keterampilan']);

            $hasLamaranFilter = !empty($this->filters['nama_pekerjaan'])
                || !empty($this->filters['jenis_pekerjaan'])
                || !empty($this->filters['tanggal_pendaftaran']);

            if ($hasLamaranFilter) {
                $query->whereHas('lamaranPekerjaan', function ($q) {
                    $q->withTrashed()
                        ->when(!empty($this->filters['nama_pekerjaan']), function ($qq) {
                            $qq->whereHas('lowongan', function ($qqq) {
                                $qqq->withTrashed()->where('judul_lowongan', 'like', '%' . $this->filters['nama_pekerjaan'] . '%');
                            });
                        })
                        ->when(!empty($this->filters['jenis_pekerjaan']), function ($qq) {
                            $qq->whereHas('lowongan', function ($qqq) {
                                $qqq->withTrashed()->where('jenis_pekerjaan', $this->filters['jenis_pekerjaan']);
                            });
                        })
                        ->when(!empty($this->filters['tanggal_pendaftaran']), function ($qq) {
                            $qq->whereDate('tanggal_lamar', $this->filters['tanggal_pendaftaran']);
                        });
                });
            }
        }

        // Filter jenis kelamin
        if (!empty($this->filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $this->filters['jenis_kelamin']);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function collection()
    {
        $this->rowNumber = 0;

        return $this->buildQuery();
    }

    /**
     * Row 1 = Judul
     * Row 2 = Tanggal export
     * Row 3 = Spacer
     * Row 4 = Heading
     * Row 5+ = Data
     */
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

        // Pendidikan terakhir: dari kartuAk1.riwayatPendidikan atau field pendidikan
        $pendidikan = optional($item->kartuAk1)
            ->riwayatPendidikan
            ?->sortByDesc('tahun_lulus')
            ->first()
            ?->jenjang
            ?? optional($item)->pendidikan
            ?? optional($item)->pendidikan_terakhir
            ?? '-';

        // Keahlian dari kartuAk1.keterampilan
        $keahlian = optional($item->kartuAk1)
            ->keterampilan
            ?->pluck('nama_keterampilan')
            ->filter()
            ->implode(', ')
            ?? '-';

        // Pekerjaan yang dilamar (ambil judul lowongan terakhir)
        $namaPekerjaan = $item->lamaranPekerjaan()
            ->withTrashed()
            ->with('lowongan')
            ->latest('tanggal_lamar')
            ->first()
            ?->lowongan
            ?->judul_lowongan
            ?? '-';

        // Tanggal daftar
        $tanggalDaftar = $item->created_at
            ? $item->created_at->format('d-m-Y')
            : '-';

        // Status akun via relasi pengguna
        $statusAkun = optional($item->pengguna)->status ?? '-';

        // Domisili
        $domisili = collect([
            $item->kelurahan,
            $item->kecamatan,
            $item->kab_kota,
        ])->filter()->implode(', ') ?: '-';

        return [
            $this->rowNumber,
            $item->nama_lengkap         ?? '-',
            $item->email                ?? '-',
            $item->nomor_hp             ?? '-',
            $domisili,
            $item->jenis_kelamin        ?? '-',
            $pendidikan,
            $keahlian,
            $namaPekerjaan,
            $tanggalDaftar,
            $statusAkun,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $totalCols  = 11; // selalu 11 kolom
                $lastColumn = Coordinate::stringFromColumnIndex($totalCols);
                $lastRow    = $sheet->getHighestRow();

                // =====================================================
                // HEADER ATAS (Row 1 & 2)
                // =====================================================
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

                // =====================================================
                // HEADING TABEL (Row 4)
                // =====================================================
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

                // =====================================================
                // ISI TABEL (Row 5+)
                // =====================================================
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

                    // Center kolom: No, JK, Tanggal, Status
                    foreach (['A', 'F', 'J', 'K'] as $col) {
                        $sheet->getStyle("{$col}5:{$col}{$lastRow}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // =====================================================
                // FOOTER TANDA TANGAN
                // =====================================================
                $footerStartColIndex = $totalCols - 2; // kolom I
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

                // =====================================================
                // LEBAR KOLOM MANUAL
                // =====================================================
                $widths = [
                    'A' => 5,  // No
                    'B' => 26, // Nama
                    'C' => 26, // Email
                    'D' => 16, // No HP
                    'E' => 24, // Domisili
                    'F' => 14, // JK
                    'G' => 18, // Pendidikan
                    'H' => 28, // Keahlian
                    'I' => 28, // Nama Pekerjaan
                    'J' => 16, // Tanggal
                    'K' => 14, // Status
                ];

                foreach ($widths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
