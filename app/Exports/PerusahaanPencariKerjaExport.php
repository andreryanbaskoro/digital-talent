<?php

namespace App\Exports;

use App\Models\ProfilPencariKerja;
use App\Models\ProfilPerusahaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerusahaanPencariKerjaExport implements FromCollection, WithStyles, WithColumnWidths
{
    private string $idPerusahaan;

    private ?ProfilPerusahaan $perusahaan = null;

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
        $this->perusahaan = ProfilPerusahaan::where('id_perusahaan', $this->idPerusahaan)->first();

        $namaPerusahaan = $this->perusahaan->nama_perusahaan ?? 'PERUSAHAAN';
        $alamatPerusahaan =
            $this->perusahaan->alamat
            ?? 'Jayapura, Papua';

        $pencariKerja = ProfilPencariKerja::withTrashed()
            ->with([
                'kartuAk1',
                'lamaranPekerjaan.lowongan',
            ])
            ->whereHas('lamaranPekerjaan', function ($q) {
                $q->whereNull('deleted_at')
                    ->whereHas('lowongan', function ($lowongan) {
                        $lowongan->where('id_perusahaan', $this->idPerusahaan);
                    });
            })
            ->orderByDesc('created_at')
            ->get();

        $rows = collect();

        // ================= KOP =================
        $rows->push([$namaPerusahaan]);
        $rows->push(['LAPORAN PENCARI KERJA']);
        $rows->push(['Alamat: ' . $alamatPerusahaan]);
        $rows->push(['']);

        // ================= HEADER =================
        $rows->push([
            'NO',
            'NAMA PELAMAR',
            'NIK',
            'LOWONGAN DILAMAR',
            'TANGGAL LAMAR',
            'KONTAK',
            'STATUS LAMARAN'
        ]);

        // ================= DATA =================
        $no = 1;

        foreach ($pencariKerja as $candidate) {
            $lamaranPerusahaan = $candidate->lamaranPekerjaan
                ->filter(function ($lamaran) {
                    return optional($lamaran->lowongan)->id_perusahaan === $this->idPerusahaan
                        && is_null($lamaran->deleted_at);
                });

            foreach ($lamaranPerusahaan as $lamaran) {
                $rows->push([
                    $no++,
                    $candidate->nama_lengkap ?? '-',
                    "'" . ($candidate->nik ?? '-'),
                    optional($lamaran->lowongan)->judul_lowongan ?? '-',
                    optional($lamaran->tanggal_lamar)->format('d-m-Y') ?? '-',
                    trim(($candidate->nomor_hp ?? '-') . ' / ' . ($candidate->email ?? '-')),
                    strtoupper($lamaran->status_lamaran ?? 'PENDING'),
                ]);
            }
        }

        // hitung baris data yang benar
        $this->dataCount = $rows->count() - 5; // 4 baris kop + 1 header
        $this->dataEndRow = $this->dataCount > 0
            ? $this->dataStartRow + $this->dataCount - 1
            : $this->dataStartRow;

        $this->footerStartRow = $this->dataEndRow + 2;

        // ================= FOOTER =================
        $rows->push(['']);

        $rows->push([
            '',
            '',
            '',
            '',
            '',
            'Jayapura, ' . now()->format('d F Y')
        ]);

        $rows->push([
            '',
            '',
            '',
            '',
            '',
            'Pimpinan Perusahaan'
        ]);

        $rows->push(['', '', '', '', '', '']);

        $rows->push([
            '',
            '',
            '',
            '',
            '',
            '________________________'
        ]);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        $sheet->getStyle('A:G')
            ->getFont()
            ->setName('Calibri')
            ->setSize(10);

        // ================= KOP =================
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');

        $sheet->getStyle('A1')
            ->getFont()
            ->setBold(true)
            ->setSize(16);

        $sheet->getStyle('A2')
            ->getFont()
            ->setBold(true)
            ->setSize(13);

        $sheet->getStyle('A3')
            ->getFont()
            ->setItalic(true)
            ->setSize(11);

        $sheet->getStyle('A1:G3')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // ================= HEADER TABEL =================
        $sheet->getStyle("A{$this->tableHeaderRow}:G{$this->tableHeaderRow}")
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
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
        $sheet->setAutoFilter("A{$this->tableHeaderRow}:G{$this->tableHeaderRow}");

        // ================= DATA =================
        if ($this->dataCount > 0) {
            $sheet->getStyle("A{$this->dataStartRow}:G{$this->dataEndRow}")
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
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("D{$this->dataStartRow}:E{$this->dataEndRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("G{$this->dataStartRow}:G{$this->dataEndRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("B{$this->dataStartRow}:F{$this->dataEndRow}")
                ->getAlignment()
                ->setWrapText(true);

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

        // ================= TANDA TANGAN =================
        $start = $this->footerStartRow;

        for ($i = 0; $i < 4; $i++) {
            $sheet->mergeCells("F" . ($start + $i) . ":G" . ($start + $i));
            $sheet->getStyle("F" . ($start + $i) . ":G" . ($start + $i))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getStyle("F{$start}:G" . ($start + 3))
            ->getFont()
            ->setSize(10);

        $sheet->getStyle("F" . ($start + 1))
            ->getFont()
            ->setBold(true);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 28,
            'C' => 22,
            'D' => 28,
            'E' => 16,
            'F' => 24,
            'G' => 18,
        ];
    }
}
