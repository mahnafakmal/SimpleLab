<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PeminjamanReportExport
{
    protected $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['No', 'Tanggal', 'User', 'Barang', 'UID Tag', 'Status', 'Detail'];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        $no = 1;
        foreach ($this->peminjaman as $p) {
            $displayWhen = '-';
            if (!empty($p->created_at)) {
                try {
                    if ($p->created_at instanceof \Illuminate\Support\Carbon) {
                        $displayWhen = $p->created_at->isoFormat('D MMM YYYY, HH:mm');
                    } else {
                        $displayWhen = \Illuminate\Support\Carbon::parse($p->created_at)->isoFormat('D MMM YYYY, HH:mm');
                    }
                } catch (\Exception $e) {
                    $displayWhen = '-';
                }
            }

            $status = '-';
            if ($p->status === 'pending') {
                $status = 'Pending';
            } elseif ($p->status === 'borrowed' || $p->status === 'dipinjam') {
                $status = 'Dipinjam';
            } else {
                $status = ucfirst($p->status);
            }

            $data = [
                $no++,
                $displayWhen . ' WIB',
                $p->user->name ?? 'User#' . $p->user_id,
                $p->barang->name ?? 'Barang Terhapus',
                $p->tagRfid->uid ?? '-',
                $status,
                $p->notes ?? '-',
            ];

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:G1')->getFill()->getStartColor()->setRGB('003366');
        $sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('FFFFFF');

        foreach (range('A', 'G') as $colLetter) {
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    public function getTempFile()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $writer = new Xlsx($this->build());
        $writer->save($tempFile);
        return $tempFile;
    }
}
