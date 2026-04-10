<?php

namespace App\Exports;

use App\Models\ServiceRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KtpReportsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $search;
    protected $pickupMethod;

    public function __construct($startDate = null, $endDate = null, $search = null, $pickupMethod = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
        $this->pickupMethod = $pickupMethod;
    }

    public function collection()
    {
        $query = ServiceRequest::with(['user', 'releasedBy'])
            ->where('status', 'completed');

        // Filter Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('applicant_name', 'like', "%{$this->search}%")
                    ->orWhere('nik', 'like', "%{$this->search}%")
                    ->orWhere('taker_nik', 'like', "%{$this->search}%");
            });
        }

        // Filter Pickup Method (YBS / Diwakilkan)
        if ($this->pickupMethod) {
            if ($this->pickupMethod == 'ybs') {
                $query->whereNull('taker_nik');
            } elseif ($this->pickupMethod == 'diwakilkan') {
                $query->whereNotNull('taker_nik');
            }
        }

        // Filter Date Range (picked_up_at)
        if ($this->startDate) {
            $query->whereDate('picked_up_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('picked_up_at', '<=', $this->endDate);
        }

        return $query->orderBy('picked_up_at', 'desc')->get();
    }

    public function headings(): array
    {
        $title = 'DATA PENGAMBILAN KTP';

        if ($this->startDate && $this->endDate) {
            $start = \Carbon\Carbon::parse($this->startDate);
            $end = \Carbon\Carbon::parse($this->endDate);

            if ($start->format('Y-m') === $end->format('Y-m')) {
                // Jika bulan dan tahunnya sama
                $title .= ' - BULAN ' . strtoupper($start->translatedFormat('F Y'));
            } else {
                // Jika berbeda bulan atau rentang panjang
                $title .= ' - PERIODE ' . strtoupper($start->translatedFormat('d F Y')) . ' S.D ' . strtoupper($end->translatedFormat('d F Y'));
            }
        } elseif ($this->startDate) {
            $start = \Carbon\Carbon::parse($this->startDate);
            $title .= ' - SEJAK ' . strtoupper($start->translatedFormat('d F Y'));
        } elseif ($this->endDate) {
            $end = \Carbon\Carbon::parse($this->endDate);
            $title .= ' - HINGGA ' . strtoupper($end->translatedFormat('d F Y'));
        } else {
            // Jika tidak ada filter tanggal
            $title .= ' - KESELURUHAN';
        }

        return [
            [$title],
            [],
            [
                'NIK Pemohon',
                'Nama Pemohon',
                'Tanggal Diambil',
                'Jam Diambil',
                'Petugas Penyerah',
                'No. Telp Pengambil',
                'Status Pengambilan',
                'NIK Pengambil (Wakil)',
            ]
        ];
    }

    public function map($row): array
    {
        return [
            "'" . $row->nik, // Tambahkan tanda kutip satu agar Excel memaksanya mutlak sebagai teks 
            $row->applicant_name,
            $row->picked_up_at ? Carbon::parse($row->picked_up_at)->format('d-m-Y') : '-',
            $row->picked_up_at ? Carbon::parse($row->picked_up_at)->format('H:i') : '-',
            $row->releasedBy ? $row->releasedBy->name : '-',
            $row->taker_phone ? "'" . $row->taker_phone : '-',
            $row->taker_nik ? 'Diwakilkan' : 'YBS',
            $row->taker_nik ? "'" . $row->taker_nik : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for the title (Now ranges from A1 to H1 because of the added column)
        $sheet->mergeCells('A1:H1');

        return [
            // Style the first row as bold, larger text, centered.
            1    => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            // Style the headings row (row 3)
            3    => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
            ],
        ];
    }
}
