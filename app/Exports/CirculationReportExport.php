<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CirculationReportExport implements FromCollection, WithHeadings, WithStyles
{
    protected $loans;

    public function __construct($loans)
    {
        $this->loans = $loans;
    }

    public function collection()
    {
        return $this->loans->map(function ($loan) {
            return [
                'No. Peminjaman' => $loan->id,
                'Nama Peminjam' => $loan->user->name ?? '-',
                'Nama Barang' => $loan->barang->nama_barang ?? '-',
                'Kode Barang' => $loan->barang->kode_barang ?? '-',
                'Tanggal Pinjam' => $loan->started_at->format('d/m/Y H:i') ?? '-',
                'Tanggal Kembali Estimasi' => $loan->due_date->format('d/m/Y') ?? '-',
                'Tanggal Kembali Aktual' => $loan->returned_at ? $loan->returned_at->format('d/m/Y H:i') : 'Belum Dikembalikan',
                'Durasi (Hari)' => $loan->returned_at ? $loan->started_at->diffInDays($loan->returned_at) : '-',
                'Status' => ucfirst($loan->status),
                'Terlambat (Hari)' => $loan->isOverdue() ? $loan->getDaysOverdue() : 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No. Peminjaman',
            'Nama Peminjam',
            'Nama Barang',
            'Kode Barang',
            'Tanggal Pinjam',
            'Tanggal Kembali Estimasi',
            'Tanggal Kembali Aktual',
            'Durasi (Hari)',
            'Status',
            'Terlambat (Hari)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('1')->getFill()->getStartColor()->setARGB('FF003366');
        $sheet->getStyle('1')->getFont()->getColor()->setARGB('FFFFFFFF');

        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
