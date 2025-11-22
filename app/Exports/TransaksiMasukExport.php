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

class TransaksiMasukExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
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
            'Supplier',
            'Tanggal',
            'Barang',
            'Jumlah',
            'Harga Beli',
            'Total Pengeluaran',
            'Pegawai Penerima'
        ];
    }

    public function map($trx): array
    {
        $barangList = $trx->details->pluck('barang.nama_barang')->join(", \n");
        $jumlahList = $trx->details->pluck('jumlah')->join("\n");
        $hargaList = $trx->details->map(fn($d) => 'Rp ' . number_format($d->harga_beli, 0, ',', '.'))->join("\n");
        $totalPengeluaran = $trx->details->sum(fn($d) => $d->jumlah * $d->harga_beli);

        return [
            $trx->kode_transaksi,
            $trx->supplier ? $trx->supplier->nama_supplier : 'Supplier Terhapus',
            $trx->created_at->format('d-m-Y H:i'),
            $barangList,
            $jumlahList,
            $hargaList,
            'Rp ' . number_format($totalPengeluaran, 0, ',', '.'),
            $trx->user ? $trx->user->name : 'User Terhapus',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD3D3D3'],
                ],
            ],
            'A:H' => [
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP],
            ],
            'A' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();

                // Border ke seluruh tabel
                $sheet->getStyle('A1:H' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Tulisan Total Data di bawah
                $footerRow = $highestRow + 2;
                $sheet->setCellValue('A' . $footerRow, 'Total Data Transaksi: ' . $this->data->count());
                $sheet->getStyle('A' . $footerRow)->getFont()->setBold(true)->setItalic(true);
            },
        ];
    }
}
