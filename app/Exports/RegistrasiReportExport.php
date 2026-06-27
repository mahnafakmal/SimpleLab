<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RegistrasiReportExport
{
    protected $registrations;

    public function __construct($registrations)
    {
        $this->registrations = $registrations;
    }

    public function build()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['No', 'Tanggal', 'Event', 'Detail'];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        $no = 1;
        foreach ($this->registrations as $r) {
            $displayRegWhen = '-';
            if (!empty($r->created_at)) {
                try {
                    if ($r->created_at instanceof \Illuminate\Support\Carbon) {
                        $displayRegWhen = $r->created_at->isoFormat('D MMM YYYY, HH:mm');
                    } else {
                        $displayRegWhen = \Illuminate\Support\Carbon::parse($r->created_at)->isoFormat('D MMM YYYY, HH:mm');
                    }
                } catch (\Exception $e) {
                    $displayRegWhen = '-';
                }
            }

            $data = [
                $no++,
                $displayRegWhen . ' WIB',
                $r->event,
                $r->detail,
            ];

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:D1')->getFill()->getStartColor()->setRGB('003366');
        $sheet->getStyle('A1:D1')->getFont()->getColor()->setRGB('FFFFFF');

        foreach (range('A', 'D') as $colLetter) {
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
