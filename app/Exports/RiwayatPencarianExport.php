<?php

namespace App\Exports;

use App\Models\LogPencarianSlip;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RiwayatPencarianExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = LogPencarianSlip::query()
            ->orderBy('created_at', 'desc');

        if (isset($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        if (isset($this->filters['nip'])) {
            $query->where('nip', 'like', '%' . $this->filters['nip'] . '%');
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->get()->map(function ($item) {
            return [
                $item->id,
                $item->user_id,
                $item->nip,
                $item->bulan,
                $item->tahun,
                $item->unit_kerja,
                $item->tujuan_unduh,
                $item->status,
                $item->execution_time_ms,
                $item->ip_address,
                $item->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'User ID',
            'NIP',
            'Bulan',
            'Tahun',
            'Unit Kerja',
            'Tujuan Unduh',
            'Status',
            'Waktu Eksekusi (ms)',
            'IP Address',
            'Tanggal',
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '198754'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 10,
            'C' => 20,
            'D' => 10,
            'E' => 10,
            'F' => 25,
            'G' => 20,
            'H' => 12,
            'I' => 20,
            'J' => 18,
            'K' => 22,
        ];
    }
}
