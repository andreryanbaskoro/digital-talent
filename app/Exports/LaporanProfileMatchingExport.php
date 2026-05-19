<?php

namespace App\Exports;

use App\Models\HasilPerhitungan;
use App\Models\ProfilPerusahaan;
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

class LaporanProfileMatchingExport implements
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
        $query = HasilPerhitungan::with([
            'lamaran.pencariKerja',
            'lamaran.lowongan.profilPerusahaan',
        ]);

        if ($this->mode === 'perusahaan') {
            $idPengguna = $this->currentIdPengguna();

            if (!$idPengguna) {
                abort(403, 'Akun perusahaan tidak valid.');
            }

            $query->whereHas('lamaran.lowongan.profilPerusahaan', function ($q) use ($idPengguna) {
                $q->where('id_pengguna', $idPengguna);
            });
        }

        if (!empty($this->filters['nama_pekerjaan'])) {
            $query->whereHas('lamaran.lowongan', function ($q) {
                $q->where('judul_lowongan', 'like', '%' . $this->filters['nama_pekerjaan'] . '%');
            });
        }

        if ($this->mode === 'disnaker' && !empty($this->filters['jenis_pekerjaan'])) {
            $query->whereHas('lamaran.lowongan', function ($q) {
                $q->where('jenis_pekerjaan', $this->filters['jenis_pekerjaan']);
            });
        }

        if (!empty($this->filters['tanggal_seleksi'])) {
            $query->whereDate('created_at', $this->filters['tanggal_seleksi']);
        }

        if (!empty($this->filters['kesimpulan'])) {
            $query->where('rekomendasi', 'like', '%' . $this->filters['kesimpulan'] . '%');
        }

        return $query->orderByDesc('created_at')->get();
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
        if ($this->mode === 'disnaker') {
            return [
                'No',
                'Nama Pelamar',
                'Jenis Kelamin',
                'Nama Perusahaan',
                'Lowongan',
                'Jenis Pekerjaan',
                'Nilai Core Factor',
                'Nilai Secondary Factor',
                'Persentase Matching',
                'Ranking',
                'Kesimpulan',
                'Tanggal Seleksi',
            ];
        }

        // Mode perusahaan (tanpa kolom perusahaan & jenis pekerjaan)
        return [
            'No',
            'Nama Pelamar',
            'Lowongan',
            'Nilai Core Factor',
            'Nilai Secondary Factor',
            'Persentase Matching',
            'Ranking',
            'Kesimpulan',
            'Tanggal Seleksi',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;

        $pencariKerja = optional($item->lamaran->pencariKerja);
        $lowongan     = optional($item->lamaran->lowongan);
        $perusahaan   = optional($lowongan->profilPerusahaan);

        $namaLengkap  = $pencariKerja->nama_lengkap ?? '-';
        $jenisKelamin = $pencariKerja->jenis_kelamin == 'L'
            ? 'Laki-laki'
            : ($pencariKerja->jenis_kelamin == 'P' ? 'Perempuan' : '-');

        $namaPerusahaan  = $perusahaan->nama_perusahaan ?? '-';
        $judulLowongan   = $lowongan->judul_lowongan ?? '-';
        $jenisPekerjaan  = $lowongan->jenis_pekerjaan ?? '-';

        $nilaiTotal      = (float) ($item->nilai_total ?? 0);
        $persentase      = round($nilaiTotal * 20, 2); // skala 0-5 → 0-100%
        $coreFactor      = (float) ($item->nilai_faktor_inti ?? 0);
        $secondaryFactor = (float) ($item->nilai_faktor_pendukung ?? 0);
        $ranking         = $item->peringkat ?? '-';
        $kesimpulan      = $item->rekomendasi ?? '-';
        $tanggalSeleksi  = $item->created_at ? $item->created_at->format('d-m-Y') : '-';

        if ($this->mode === 'disnaker') {
            return [
                $this->rowNumber,
                $namaLengkap,
                $jenisKelamin,
                $namaPerusahaan,
                $judulLowongan,
                $jenisPekerjaan,
                $coreFactor,
                $secondaryFactor,
                $persentase . '%',
                $ranking,
                $kesimpulan,
                $tanggalSeleksi,
            ];
        }

        return [
            $this->rowNumber,
            $namaLengkap,
            $judulLowongan,
            $coreFactor,
            $secondaryFactor,
            $persentase . '%',
            $ranking,
            $kesimpulan,
            $tanggalSeleksi,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $totalCols = $this->mode === 'disnaker' ? 12 : 9;
                $lastCol   = Coordinate::stringFromColumnIndex($totalCols);
                $lastRow   = $sheet->getHighestRow();

                // =====================================================
                // HEADER ATAS (Row 1 & 2)
                // =====================================================
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->mergeCells("A2:{$lastCol}2");

                $modeLabel = $this->mode === 'disnaker' ? 'DISNAKER' : 'PERUSAHAAN';
                $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI PROFILE MATCHING - ' . $modeLabel);
                $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d-m-Y H:i'));

                $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
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
                $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
                    'font' => ['bold' => true, 'name' => 'Arial', 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A5276']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '154360']],
                    ],
                    'font' => ['bold' => true, 'name' => 'Arial', 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                ]);

                $sheet->getRowDimension(4)->setRowHeight(28);

                // =====================================================
                // ISI TABEL (Row 5+)
                // =====================================================
                if ($lastRow >= 5) {
                    $sheet->getStyle("A5:{$lastCol}{$lastRow}")->applyFromArray([
                        'font'      => ['name' => 'Arial', 'size' => 10],
                        'borders'   => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'B8C9E0']],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);

                    // Zebra stripe
                    for ($r = 5; $r <= $lastRow; $r++) {
                        if ($r % 2 === 0) {
                            $sheet->getStyle("A{$r}:{$lastCol}{$r}")
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB('EEF4FB');
                        }
                    }

                    // Warnai kolom Kesimpulan — exact match sesuai getRekomendasi()
                    $colKesimpulan = $this->mode === 'disnaker' ? 'K' : 'H';
                    for ($r = 5; $r <= $lastRow; $r++) {
                        $val = (string) $sheet->getCell("{$colKesimpulan}{$r}")->getValue();

                        if ($val === '⭐ Sangat Cocok') {
                            $sheet->getStyle("{$colKesimpulan}{$r}")
                                ->getFill()->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('D5F5E3');
                        } elseif ($val === '👍 Cocok') {
                            $sheet->getStyle("{$colKesimpulan}{$r}")
                                ->getFill()->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('D6EAF8');
                        } elseif ($val === '❗ Kurang Cocok') {
                            $sheet->getStyle("{$colKesimpulan}{$r}")
                                ->getFill()->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('FADBD8');
                        }
                    }

                    // Center beberapa kolom
                    $centerCols = $this->mode === 'disnaker'
                        ? ['A', 'C', 'G', 'H', 'I', 'J', 'K', 'L']
                        : ['A', 'D', 'E', 'F', 'G', 'H', 'I'];

                    foreach ($centerCols as $col) {
                        $sheet->getStyle("{$col}5:{$col}{$lastRow}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // =====================================================
                // FOOTER TANDA TANGAN
                // =====================================================
                $footerStartColIndex = $totalCols - 2;
                $footerStartCol      = Coordinate::stringFromColumnIndex($footerStartColIndex);

                $r1 = $lastRow + 3;
                $r2 = $lastRow + 4;
                $r3 = $lastRow + 7;
                $r4 = $lastRow + 8;

                foreach ([$r1, $r2, $r3, $r4] as $fr) {
                    $sheet->mergeCells("{$footerStartCol}{$fr}:{$lastCol}{$fr}");
                }

                $sheet->setCellValue("{$footerStartCol}{$r1}", 'Mengetahui,');
                $sheet->setCellValue(
                    "{$footerStartCol}{$r2}",
                    $this->mode === 'disnaker' ? 'Kepala Dinas Tenaga Kerja' : 'Pimpinan Perusahaan'
                );
                $sheet->setCellValue("{$footerStartCol}{$r4}", '(...................................)');

                $sheet->getStyle("{$footerStartCol}{$r1}:{$lastCol}{$r4}")->applyFromArray([
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
                if ($this->mode === 'disnaker') {
                    $widths = [
                        'A' => 4,
                        'B' => 24,
                        'C' => 12,
                        'D' => 22,
                        'E' => 24,
                        'F' => 16,
                        'G' => 14,
                        'H' => 14,
                        'I' => 14,
                        'J' => 8,
                        'K' => 18,
                        'L' => 14,
                    ];
                } else {
                    $widths = [
                        'A' => 4,
                        'B' => 24,
                        'C' => 24,
                        'D' => 14,
                        'E' => 14,
                        'F' => 14,
                        'G' => 8,
                        'H' => 18,
                        'I' => 14,
                    ];
                }

                foreach ($widths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
