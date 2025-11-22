<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; // Tambahan 1
use Maatwebsite\Excel\Events\AfterSheet; // Tambahan 2
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border; // Tambahan 3

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
            'Barang',
            'Jumlah',
            'Harga Jual',
            'Total Pendapatan',
        ];
    }

    public function map($trx): array
    {
        $barangList = $trx->details->map(fn($d) => $d->barang->nama_barang ?? 'Barang Dihapus')->join(", \n");
        $jumlahList = $trx->details->pluck('jumlah')->join("\n");
        $hargaList = $trx->details->map(fn($d) => 'Rp ' . number_format($d->harga_jual, 0, ',', '.'))->join("\n");
        $totalPendapatan = $trx->details->sum(fn($d) => $d->jumlah * $d->harga_jual);

        return [
            $trx->kode_transaksi,
            $trx->customer,
            $trx->created_at->format('d-m-Y H:i'),
            $trx->user->name ?? '-',
            $barangList,
            $jumlahList,
            $hargaList,
            'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
        ];
    }

    // Style Header & Alignment
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD3D3D3'], // Warna abu-abu untuk header
                ],
            ],
            'A:H' => [
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP],
            ],
            // Rata tengah untuk kolom Kode, Tanggal, Jumlah
            'A' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    // Event untuk Menambah Border & Tulisan Total Data
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Hitung baris terakhir (Header + Data)
                $highestRow = $sheet->getHighestRow();

                // 1. Buat BORDER Hitam untuk seluruh tabel (A1 sampai H paling bawah)
                $sheet->getStyle('A1:H' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // 2. Tambahan Info TOTAL DATA di bawah tabel
                $footerRow = $highestRow + 2; // Beri jarak 1 baris kosong
                $sheet->setCellValue('A' . $footerRow, 'Total Data Transaksi: ' . $this->data->count());
                $sheet->getStyle('A' . $footerRow)->getFont()->setBold(true)->setItalic(true);
            },
        ];
    }
}
