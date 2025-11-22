<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransaksiKeluarExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Customer',
            'Tanggal',
            'Kasir',
            'Barang',       // Kolom E
            'Jumlah',       // Kolom F
            'Harga Jual',   // Kolom G
            'Total Pendapatan',
        ];
    }

    public function map($trx): array
    {
        // 1. Buat List Barang dengan Bullet Point (•)
        // Contoh hasil:
        // • Laptop
        // • Mouse
        $barangList = $trx->details->map(function ($d) {
            $nama = $d->barang->nama_barang ?? 'Barang Dihapus';
            return "• " . $nama;
        })->join("\n"); // Gabung dengan Enter

        // 2. Buat List Jumlah sesuai urutan barang
        $jumlahList = $trx->details->map(function ($d) {
            return $d->jumlah . " pcs";
        })->join("\n");

        // 3. Buat List Harga sesuai urutan barang
        $hargaList = $trx->details->map(function ($d) {
            return 'Rp ' . number_format($d->harga_jual, 0, ',', '.');
        })->join("\n");

        // Hitung Total
        $totalPendapatan = $trx->details->sum(fn($d) => $d->jumlah * $d->harga_jual);

        return [
            $trx->kode_transaksi,
            $trx->customer,
            $trx->created_at->format('d-m-Y H:i'),
            $trx->user->name ?? '-',
            $barangList, // Hasil map di atas
            $jumlahList,
            $hargaList,
            'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header Style
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD3D3D3'],
                ],
            ],

            // Style Global untuk Data (A sampai H)
            'A:H' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP, // Teks mulai dari atas sel
                    'wrapText' => true, // WAJIB: Agar \n (enter) terbaca sebagai baris baru
                ],
            ],

            // Alignment Khusus per Kolom
            'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Kode
            'C' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Tanggal
            'F' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Jumlah
            'G' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],  // Harga
            'H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]], // Total (Tengah Vertikal biar rapi)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();

                // Beri Border Hitam ke seluruh tabel
                $sheet->getStyle('A1:H' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Tambahan Info Total Data di bawah tabel
                $footerRow = $highestRow + 2;
                $sheet->setCellValue('A' . $footerRow, 'Total Transaksi: ' . $this->data->count());
                $sheet->getStyle('A' . $footerRow)->getFont()->setBold(true)->setItalic(true);
            },
        ];
    }
}
