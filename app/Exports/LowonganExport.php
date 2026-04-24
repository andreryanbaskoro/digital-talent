<?php

namespace App\Exports;

use App\Models\LowonganPekerjaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LowonganExport implements FromCollection, WithStyles, WithColumnWidths
{
    private int $tableHeaderRow = 8;
    private int $dataStartRow = 9;
    private int $dataCount = 0;
    private int $dataEndRow = 0;
    private int $footerStartRow = 0;

    public function collection()
    {
        $data = LowonganPekerjaan::withTrashed()
            ->with('profilPerusahaan')
            ->orderByDesc('created_at')
            ->get();

        $this->dataCount = $data->count();
        $this->dataEndRow = $this->dataCount > 0
            ? $this->dataStartRow + $this->dataCount - 1
            : $this->dataStartRow;

        $this->footerStartRow = $this->dataEndRow + 3;

        $rows = collect();

        // ===== KOP =====
        $rows->push(['PEMERINTAH KOTA JAYAPURA']);
        $rows->push(['DINAS TENAGA KERJA']);
        $rows->push(['Jl. Samratulangi No.25, Mandala, Jayapura Utara']);
        $rows->push(['']);
        $rows->push(['LAPORAN LOWONGAN PEKERJAAN']);
        $rows->push(['']);
        $rows->push(['']);

        // ===== HEADER TABLE =====
        $rows->push([
            'NO',
            'PERUSAHAAN',
            'JUDUL LOWONGAN',
            'LOKASI',
            'JENIS',
            'KUOTA',
            'STATUS'
        ]);

        // ===== DATA =====
        foreach ($data as $i => $item) {
            $rows->push([
                $i + 1,
                $item->profilPerusahaan->nama_perusahaan ?? '-',
                $item->judul_lowongan ?? '-',
                $item->lokasi ?? '-',
                ucfirst($item->jenis_pekerjaan ?? '-'),
                $item->kuota ?? '-',
                $item->deleted_at ? 'TERHAPUS' : strtoupper($item->status ?? 'PENDING'),
            ]);
        }

        // ===== SPACE =====
        $rows->push(['']);
        $rows->push(['']);

        // ===== SIGNATURE =====
        $rows->push(['', '', '', 'Jayapura, ' . now()->format('d F Y')]);
        $rows->push(['', '', '', 'Kepala Dinas Tenaga Kerja']);
        $rows->push(['', '', '', '']);
        $rows->push(['', '', '', '(...................................)']);
        $rows->push(['', '', '', 'NIP. ..................................']);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getStyle('A:G')->getFont()->setName('Calibri')->setSize(10);

        // ===== KOP =====
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->mergeCells('A5:G5');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:G2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3:G3')->getFont()->getColor()->setRGB('6B7280');

        $sheet->getStyle('A5:G5')->getFont()->setBold(true)->setSize(15);

        $sheet->getStyle('A1:G5')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // ===== TABLE HEADER =====
        $sheet->getStyle("A{$this->tableHeaderRow}:G{$this->tableHeaderRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '2F3B52'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        $sheet->getRowDimension($this->tableHeaderRow)->setRowHeight(22);

        // freeze + filter
        $sheet->freezePane('A9');
        $sheet->setAutoFilter("A{$this->tableHeaderRow}:G{$this->tableHeaderRow}");

        // ===== DATA =====
        if ($this->dataCount > 0) {
            $sheet->getStyle("A{$this->dataStartRow}:G{$this->dataEndRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getStyle("A{$this->dataStartRow}:A{$this->dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("F{$this->dataStartRow}:G{$this->dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("B{$this->dataStartRow}:D{$this->dataEndRow}")
                ->getAlignment()->setWrapText(true);

            // zebra striping
            for ($row = $this->dataStartRow; $row <= $this->dataEndRow; $row++) {
                if (($row - $this->dataStartRow) % 2 === 1) {
                    $sheet->getStyle("A{$row}:G{$row}")
                        ->getFill()
                        ->setFillType('solid')
                        ->getStartColor()
                        ->setRGB('F9FAFB');
                }
            }
        }

        // ===== SIGNATURE =====
        $start = $this->footerStartRow;

        for ($i = 0; $i < 5; $i++) {
            $sheet->mergeCells("D" . ($start + $i) . ":G" . ($start + $i));
            $sheet->getStyle("D" . ($start + $i) . ":G" . ($start + $i))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getStyle("D{$start}:G" . ($start + 4))
            ->getFont()->setSize(10);

        $sheet->getStyle("D" . ($start + 1))
            ->getFont()->setBold(true);

        $sheet->getStyle("D" . ($start + 3))
            ->getFont()->setUnderline(true);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 28,
            'C' => 34,
            'D' => 18,
            'E' => 14,
            'F' => 10,
            'G' => 15,
        ];
    }
}
