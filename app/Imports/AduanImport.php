<?php

namespace App\Imports;

use App\Models\Aduan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AduanImport implements ToModel, WithHeadingRow
{
    /**
     * Specify the row where the actual column headers are located.
     *
     * @return int
     */
    public function headingRow(): int
    {
        return 4; // Headers are located on row 4.
    }

    /**
     * Map each row to the Aduan model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip rows where 'NO.' exists or its value is empty
        if (!isset($row['NO.']) || empty($row['NO.'])) {
            return null; // Skip this row
        }

        return new Aduan([
            'aduan_ict_tiket' => $row['ADUAN ICT TICKET'] ?? null,
            'complainent_name_id' => $row['COMPLAINENT NAME (ID)'] ?? null,
            'complainent_category' => $row['COMPLAINENT CATEGORY'] ?? null,
            'aduan_category' => $row['ADUAN CATEGORY'] ?? null,
            'aduan_subcategory' => $row['ADUAN SUB CATEGORY'] ?? null,
            'campus' => $row['CAMPUS ZONE'] ?? null,
            'location' => $row['LOCATION'] ?? null,
            'aduan_details' => $row['ADUAN DETAILS'] ?? null,
            'aduan_status' => $row['ADUAN STATUS'] ?? null,
            'aduan_type' => $row['ADUAN TYPE'] ?? null,
            'staff_duty' => $row['STAFF ON DUTY'] ?? null,
            'remark_staff_duty' => $row['REMARK STAFF ON DUTY'] ?? null,
            'date_applied' => $row['DATE APPLIED'] ?? null,
            'time_applied' => $row['TIME APPLIED'] ?? null,
            'date_completed' => $row['DATE COMPLETED'] ?? null,
            'time_completed' => $row['TIME COMPLETED'] ?? null,
            'response_time' => $row['RESPONSE TIME'] ?? null,
            'rating' => $row['RATING'] ?? null,
        ]);
    }
}
