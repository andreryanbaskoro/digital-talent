<?php

namespace App\Exports;

use App\Models\LowonganPekerjaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerusahaanLowonganExport implements FromCollection, WithStyles, WithColumnWidths
{
    private string $idPerusahaan;

    private int $tableHeaderRow = 5;
    private int $dataStartRow = 6;
    private int $dataCount = 0;
    private int $dataEndRow = 0;
    private int $footerStartRow = 0;

    public function __construct(string $idPerusahaan)
    {
        $this->idPerusahaan = $idPerusahaan;
    }

    public function collection()
    {
        $data = LowonganPekerjaan::withTrashed()
            ->where('id_perusahaan', $this->idPerusahaan)
            ->with(['profilPerusahaan:id_perusahaan,nama_perusahaan'])
            ->orderByDesc('created_at')
            ->get();

        $this->dataCount = $data->count();
        $this->dataEndRow = $this->dataCount > 0
            ? $this->dataStartRow + $this->dataCount - 1
            : $this->dataStartRow;

        $this->footerStartRow = $this->dataCount > 0
            ? $this->dataEndRow + 2
            : $this->dataStartRow + 2;

        $namaPerusahaan = optional($data->first()?->profilPerusahaan)->nama_perusahaan ?? 'PERUSAHAAN';

        $rows = collect();

        $rows->push([$namaPerusahaan]);
        $rows->push(['LAPORAN LOWONGAN PEKERJAAN']);
        $rows->push(['Periode: ' . now()->format('d F Y')]);
        $rows->push(['']);

        $rows->push([
            'NO',
            'JUDUL LOWONGAN',
            'LOKASI',
            'JENIS PEKERJAAN',
            'KUOTA',
            'STATUS'
        ]);

        foreach ($data as $i => $item) {
            $rows->push([
                $i + 1,
                $item->judul_lowongan ?? '-',
                $item->lokasi ?? '-',
                ucfirst($item->jenis_pekerjaan ?? '-'),
                $item->kuota ?? '-',
                $item->deleted_at ? 'TERHAPUS' : strtoupper($item->status ?? 'PENDING'),
            ]);
        }

        $rows->push(['']);
        $rows->push(['', '', '', 'Jayapura, ' . now()->format('d F Y')]);
        $rows->push(['', '', '', 'Pimpinan Perusahaan']);
        $rows->push(['', '', '', '']);
        $rows->push(['', '', '', '________________________']);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getStyle('A:F')->getFont()->setName('Calibri')->setSize(10);

        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(11);

        $sheet->getStyle('A1:F3')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A{$this->tableHeaderRow}:F{$this->tableHeaderRow}")
            ->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '1F4E79'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

        $sheet->getRowDimension($this->tableHeaderRow)->setRowHeight(22);
        $sheet->freezePane('A6');
        $sheet->setAutoFilter("A{$this->tableHeaderRow}:F{$this->tableHeaderRow}");

        if ($this->dataCount > 0) {
            $sheet->getStyle("A{$this->dataStartRow}:F{$this->dataEndRow}")
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

            $sheet->getStyle("A{$this->dataStartRow}:A{$this->dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("E{$this->dataStartRow}:F{$this->dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $start = $this->footerStartRow;
        for ($i = 0; $i < 4; $i++) {
            $sheet->mergeCells("D" . ($start + $i) . ":F" . ($start + $i));
            $sheet->getStyle("D" . ($start + $i))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 34,
            'C' => 18,
            'D' => 18,
            'E' => 10,
            'F' => 15,
        ];
    }
}
