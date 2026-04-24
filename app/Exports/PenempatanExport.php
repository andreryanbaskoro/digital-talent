<?php

namespace App\Exports;

use App\Models\LamaranPekerjaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenempatanExport implements FromCollection, WithStyles, WithColumnWidths
{
    private int $tableHeaderRow = 8;
    private int $dataStartRow = 9;
    private int $dataCount = 0;
    private int $dataEndRow = 0;
    private int $footerStartRow = 0;

    public function collection()
    {
        $data = LamaranPekerjaan::withTrashed()
            ->with([
                'pencariKerja',
                'lowongan.profilPerusahaan'
            ])
            ->orderByDesc('created_at')
            ->get();

        $this->dataCount = $data->count();
        $this->dataEndRow = $this->dataStartRow + max($this->dataCount - 1, 0);
        $this->footerStartRow = $this->dataEndRow + 3;

        $rows = collect();

        // ===== KOP =====
        $rows->push(['PEMERINTAH KOTA JAYAPURA']);
        $rows->push(['DINAS TENAGA KERJA']);
        $rows->push(['Jl. Samratulangi No.25, Mandala, Jayapura Utara']);
        $rows->push(['']);
        $rows->push(['LAPORAN PENEMPATAN TENAGA KERJA']);
        $rows->push(['']);
        $rows->push(['']);

        // ===== HEADER =====
        $rows->push([
            'NO',
            'ID',
            'NIK',
            'NAMA',
            'PERUSAHAAN',
            'LOWONGAN',
            'LOKASI',
            'TGL LAMAR',
            'TGL DITERIMA',
            'STATUS'
        ]);

        // ===== DATA =====
        foreach ($data as $i => $item) {

            $status = strtolower($item->status_lamaran ?? '');

            $tanggalDiterima = $status === 'diterima'
                ? optional($item->updated_at)->format('d-m-Y')
                : '-';

            $rows->push([
                $i + 1,
                $item->id_pencari_kerja ?? '-',
                "'" . ($item->pencariKerja->nik ?? '-'),
                $item->pencariKerja->nama_lengkap ?? '-',
                $item->lowongan->profilPerusahaan->nama_perusahaan ?? '-',
                $item->lowongan->judul_lowongan ?? '-',
                $item->lowongan->lokasi ?? '-',
                optional($item->tanggal_lamar)->format('d-m-Y') ?? '-',
                $tanggalDiterima,
                strtoupper($item->status_lamaran ?? '-'),
            ]);
        }

        // ===== SPACE =====
        $rows->push(['']);
        $rows->push(['']);

        // ===== SIGNATURE =====
        $rows->push(['', '', '', '', '', '', '', 'Jayapura, ' . now()->format('d F Y')]);
        $rows->push(['', '', '', '', '', '', '', 'Kepala Dinas Tenaga Kerja']);
        $rows->push(['', '', '', '', '', '', '', '']);
        $rows->push(['', '', '', '', '', '', '', '(...................................)']);
        $rows->push(['', '', '', '', '', '', '', 'NIP. ..................................']);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getStyle('A:J')->getFont()->setName('Calibri')->setSize(10);

        // ===== KOP =====
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');
        $sheet->mergeCells('A5:J5');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:J2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3:J3')->getFont()->getColor()->setRGB('6B7280');
        $sheet->getStyle('A5:J5')->getFont()->setBold(true)->setSize(15);

        $sheet->getStyle('A1:J5')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // ===== HEADER =====
        $sheet->getStyle("A{$this->tableHeaderRow}:J{$this->tableHeaderRow}")
            ->applyFromArray([
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

        $sheet->freezePane('A9');
        $sheet->setAutoFilter("A{$this->tableHeaderRow}:J{$this->tableHeaderRow}");

        // ===== DATA =====
        if ($this->dataCount > 0) {
            $sheet->getStyle("A{$this->dataStartRow}:J{$this->dataEndRow}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);

            // center kolom tertentu
            $sheet->getStyle("A{$this->dataStartRow}:C{$this->dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("H{$this->dataStartRow}:J{$this->dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // wrap text
            $sheet->getStyle("D{$this->dataStartRow}:G{$this->dataEndRow}")
                ->getAlignment()->setWrapText(true);
        }

        // ===== SIGNATURE =====
        $start = $this->footerStartRow;

        for ($i = 0; $i < 5; $i++) {
            $sheet->mergeCells("H" . ($start + $i) . ":J" . ($start + $i));
            $sheet->getStyle("H" . ($start + $i))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 20,
            'C' => 20,
            'D' => 28,
            'E' => 28,
            'F' => 30,
            'G' => 20,
            'H' => 16,
            'I' => 16,
            'J' => 16,
        ];
    }
}
