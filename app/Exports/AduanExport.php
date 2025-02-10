<?php

namespace App\Exports;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AduanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Aduan::query();

        $search = $this->request->input('search');
        $month = $this->request->input('month', 'all');
        $year = $this->request->input('year', 'all');
        $campus = $this->request->input('campus');
        $aduanStatus = $this->request->input('aduan_status');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('aduan_category', 'LIKE', "%$search%")
                    ->orWhere('campus', 'LIKE', "%$search%")
                    ->orWhere('aduan_status', 'LIKE', "%$search%")
                    ->orWhere('complainent_name', 'LIKE', "%$search%")
                    ->orWhere('aduan_ict_ticket', 'LIKE', "%$search%")
                    ->orWhere('complainent_id', 'LIKE', "%$search%");
            });
        }

        if ($campus && $campus !== 'all') {
            $query->where('campus', $campus);
        }

        if ($aduanStatus && $aduanStatus !== 'all') {
            $query->where('aduan_status', $aduanStatus);
        }

        if ($month !== 'all') {
            $query->whereMonth('date_applied', $month);
        }

        if ($year !== 'all') {
            $query->whereYear('date_applied', $year);
        }

        return $query->select([
            'aduan_ict_ticket',
            'complainent_name',
            'complainent_id',
            'complainent_category',
            'aduan_category',
            'category',
            'aduan_subcategory',
            'campus',
            'location',
            'aduan_details',
            'aduan_status',
            'aduan_type',
            'staff_duty',
            'remark_staff_duty',
            'date_applied',
            'time_applied',
            'date_completed',
            'time_completed',
            'response_time',
            'response_days',
            'rating',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Ticket No',
            'Complainant Name',
            'Complainant ID',
            'Complainant Category',
            'Aduan Category',
            'Category',
            'Aduan Subcategory',
            'Campus',
            'Location',
            'Aduan Details',
            'Aduan Status',
            'Aduan Type',
            'Staff Duty',
            'Staff Remarks',
            'Date Applied',
            'Month Applied',
            'Year Applied',
            'Time Applied',
            'Date Completed',
            'Time Completed',
            'Response Time',
            'Response Days',
            'Piagam',
            'Rating',
        ];
    }

    public function map($aduan): array
    {
        return [
            $aduan->aduan_ict_ticket,
            $aduan->complainent_name,
            $aduan->complainent_id,
            $aduan->complainent_category,
            $aduan->aduan_category,
            $aduan->category,
            $aduan->aduan_subcategory,
            $aduan->campus,
            $aduan->location,
            $aduan->aduan_details,
            $aduan->aduan_status,
            $aduan->aduan_type,
            $aduan->staff_duty,
            $aduan->remark_staff_duty,
            $aduan->date_applied,
            date('F', strtotime($aduan->date_applied)), 
            date('Y', strtotime($aduan->date_applied)), 
            $aduan->time_applied,
            $aduan->date_completed,
            $aduan->time_completed,
            $aduan->response_time,
            $aduan->response_days ?? '-', 
            ($aduan->response_days === null) ? '-' : (($aduan->response_days <= 3) ? '≤ 3 Hari' : '> 3 Hari'), 
            $aduan->rating,
        ];
    }
}
