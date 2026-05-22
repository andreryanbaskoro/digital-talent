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

class LaporanPelamarPerusahaanExport implements
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
                'lowongan.profilPerusahaan',
                'pencariKerja',
            ]);

        if ($this->mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();

            if (!$idPengguna) {
                abort(403, 'Akun perusahaan tidak valid.');
            }

            $query->whereHas('lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                $q->where('id_pengguna', $idPengguna);
            });
        }

        if ($this->mode === 'disnaker' && !empty($this->filters['nama_perusahaan'])) {
            $namaPerusahaan = $this->filters['nama_perusahaan'];

            $query->whereHas('lowongan.profilPerusahaan', function ($q) use ($namaPerusahaan) {
                $q->where('nama_perusahaan', 'like', '%' . $namaPerusahaan . '%');
            });
        }

        if (!empty($this->filters['jenis_pekerjaan'])) {
            $jenisPekerjaan = $this->filters['jenis_pekerjaan'];

            $query->whereHas('lowongan', function ($q) use ($jenisPekerjaan) {
                $q->where('jenis_pekerjaan', $jenisPekerjaan);
            });
        }

        if (!empty($this->filters['nama_pekerjaan'])) {
            $namaPekerjaan = $this->filters['nama_pekerjaan'];

            $query->whereHas('lowongan', function ($q) use ($namaPekerjaan) {
                $q->where('judul_lowongan', 'like', '%' . $namaPekerjaan . '%');
            });
        }

        if (!empty($this->filters['tanggal_posting'])) {

            $tanggal = explode('-', $this->filters['tanggal_posting']);

            $tahun = $tanggal[0] ?? null;
            $bulan = $tanggal[1] ?? null;

            $query->whereHas('lowongan', function ($q) use ($tahun, $bulan) {

                if ($tahun) {
                    $q->whereYear('created_at', $tahun);
                }

                if ($bulan) {
                    $q->whereMonth('created_at', $bulan);
                }
            });
        }

        if ($this->mode === 'perusahaan' && !empty($this->filters['jenis_kelamin'])) {
            $jenisKelamin = $this->filters['jenis_kelamin'];

            $query->whereHas('pencariKerja', function ($q) use ($jenisKelamin) {
                $q->where('jenis_kelamin', $jenisKelamin);
            });
        }

        return $query->orderByDesc('tanggal_lamar')->get();
    }

    public function collection()
    {
        // Reset counter setiap kali collection dipanggil
        $this->rowNumber = 0;

        return $this->buildQuery();
    }

    /**
     * Heading tabel mulai di row 4
     * Row 1 = Judul laporan
     * Row 2 = Tanggal export
     * Row 3 = (kosong sebagai spacer)
     * Row 4 = Heading tabel
     * Row 5+ = Data
     */
    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        $headings = ['No'];

        if ($this->mode === 'disnaker') {
            $headings[] = 'Nama Perusahaan';
        }

        return array_merge($headings, [
            'Nama Pelamar',
            'Jenis Kelamin',
            'Pendidikan',
            'Nama Pekerjaan',
            'Jenis Pekerjaan',
            'Tanggal Melamar',
        ]);
    }

    public function map($item): array
    {
        $this->rowNumber++;

        $row = [$this->rowNumber];

        if ($this->mode === 'disnaker') {
            $row[] = optional(optional($item->lowongan)->profilPerusahaan)->nama_perusahaan ?? '-';
        }

        $row[] = optional($item->pencariKerja)->nama_lengkap                                        ?? '-';
        $row[] = optional($item->pencariKerja)->jenis_kelamin                                       ?? '-';
        $row[] = optional($item->pencariKerja)->pendidikan ?? optional($item->pencariKerja)->pendidikan_terakhir ?? '-';
        $row[] = optional($item->lowongan)->judul_lowongan                                          ?? '-';
        $row[] = optional($item->lowongan)->jenis_pekerjaan                                         ?? '-';
        $row[] = $item->tanggal_lamar ? $item->tanggal_lamar->format('d-m-Y') : '-';

        return $row;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Jumlah kolom: 8 untuk disnaker (No + NamaPerusahaan + 6 lainnya),
                //               7 untuk perusahaan (No + 6 lainnya)
                $totalCols  = $this->mode === 'disnaker' ? 8 : 7;
                $lastColumn = Coordinate::stringFromColumnIndex($totalCols);
                $lastRow    = $sheet->getHighestRow();

                // =====================================================
                // HEADER ATAS (Row 1 & 2) — judul & tanggal export
                // =====================================================
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->mergeCells("A2:{$lastColumn}2");

                $sheet->setCellValue('A1', 'LAPORAN DATA PELAMAR PERUSAHAAN');
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

                // Row 1 & 2 height
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(18);

                // Row 3 = spacer kosong
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

                $sheet->getRowDimension(4)->setRowHeight(24);

                // =====================================================
                // ISI TABEL (Row 5 dst)
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

                    // Zebra stripe (baris genap sedikit lebih gelap)
                    for ($r = 5; $r <= $lastRow; $r++) {
                        if ($r % 2 === 0) {
                            $sheet->getStyle("A{$r}:{$lastColumn}{$r}")
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('EEF4FB');
                        }
                    }
                }

                // =====================================================
                // ALIGNMENT PER KOLOM
                // =====================================================

                // Kolom No (A) — center
                $sheet->getStyle("A5:A{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Kolom Tanggal — selalu kolom terakhir — center
                if ($lastRow >= 5) {
                    $sheet->getStyle("{$lastColumn}5:{$lastColumn}{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // =====================================================
                // FOOTER TANDA TANGAN — selalu di 3 kolom TERAKHIR,
                // rata kanan tabel (merge dari kolom ke-6 s/d lastColumn
                // untuk disnaker, kolom ke-5 s/d lastColumn untuk perusahaan)
                // =====================================================

                // Footer mulai 3 kolom dari kanan
                $footerStartColIndex = $totalCols - 2;                          // mis. 6 (col F) untuk disnaker
                $footerStartCol      = Coordinate::stringFromColumnIndex($footerStartColIndex);

                $r1 = $lastRow + 3;  // "Mengetahui"
                $r2 = $lastRow + 4;  // nama instansi
                $r3 = $lastRow + 7;  // spasi tanda tangan
                $r4 = $lastRow + 8;  // garis tanda tangan

                // Merge tiap baris footer di 3 kolom terakhir
                foreach ([$r1, $r2, $r3, $r4] as $fr) {
                    $sheet->mergeCells("{$footerStartCol}{$fr}:{$lastColumn}{$fr}");
                }

                $sheet->setCellValue("{$footerStartCol}{$r1}", 'Mengetahui,');
                $sheet->setCellValue(
                    "{$footerStartCol}{$r2}",
                    $this->mode === 'disnaker' ? 'Kepala Dinas Tenaga Kerja' : 'Pimpinan Perusahaan'
                );
                // r3 dibiarkan kosong sebagai ruang tanda tangan
                $sheet->setCellValue("{$footerStartCol}{$r4}", '(...................................)');

                $sheet->getStyle("{$footerStartCol}{$r1}:{$lastColumn}{$r4}")->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Beri tinggi pada baris tanda tangan agar ada ruang
                $sheet->getRowDimension($r3)->setRowHeight(36);

                // =====================================================
                // LEBAR KOLOM MANUAL
                // =====================================================
                $widths = $this->mode === 'disnaker'
                    ? [
                        'A' => 6,
                        'B' => 28,
                        'C' => 26,
                        'D' => 14,
                        'E' => 20,
                        'F' => 30,
                        'G' => 18,
                        'H' => 16,
                    ]
                    : [
                        'A' => 6,
                        'B' => 26,
                        'C' => 14,
                        'D' => 20,
                        'E' => 30,
                        'F' => 18,
                        'G' => 16,
                    ];

                foreach ($widths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
