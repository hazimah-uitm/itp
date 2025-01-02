<?php

namespace App\Imports;

use App\Models\Aduan;
use Maatwebsite\Excel\Concerns\ToModel;

class AduanImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function headingRow(): int
    {
        return 4; // Set the row number where headers are located
    }

    
    public function model(array $row)
    {
        if (isset($row['NO.']) && is_numeric($row['NO.'])) {
            unset($row['NO.']); // Optional: Remove the 'No.' column data if needed.
        }

        return new Aduan ([
            'aduan_ict_tiket' => $row['ADUAN ICT TICKET'],
            'complainent_name_id' => $row['COMPLAINENT NAME (ID)'],
            'complainent_category' => $row['COMPLAINENT CATEGORY'],
            'aduan_category' => $row['ADUAN CATEGORY'],
            'aduan_subcategory' => $row['ADUAN SUB CATEGORY'],
            'campus' => $row['CAMPUS ZONE'],
            'location' => $row['LOCATION'],
            'aduan_details' => $row['ADUAN DETAILS'],
            'aduan_status' => $row['ADUAN STATUS'],
            'aduan_type' => $row['ADUAN TYPE'],
            'staff_duty' => $row['STAFF ON DUTY'],
            'remark_staff_duty' => $row['REMARK STAFF ON DUTY'],
            'date_applied' => $row['DATE APPLIED'],
            'time_applied' => $row['TIME APPLIED'],
            'date_completed' => $row['DATE COMPLETED'],
            'time_completed' => $row['TIME COMPLETED'],
            'response_time' => $row['RESPONSE TIME'],
            'rating' => $row['RATING']
        ]);
    }
}
