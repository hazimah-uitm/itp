<?php

namespace App\Imports;

use App\Models\Aduan;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AduanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Extract complainent_id (value within parentheses)
        $complainentNameID = $row['complainent_name_id'];
        preg_match('/\(([^)]+)\)/', $complainentNameID, $matches);
        $complainentId = $matches[1] ?? null;

        // Extract complainent_name (value before parentheses)
        preg_match('/^[^(]+/', $complainentNameID, $nameMatches);
        $complainentName = trim($nameMatches[0] ?? '');

        // Convert the date format from 'DD.MM.YYYY' to 'YYYY-MM-DD'
        $dateApplied = \DateTime::createFromFormat('d.m.Y', $row['date_applied']);
        $dateAppliedFormatted = $dateApplied ? $dateApplied->format('Y-m-d') : null;

        $dateCompleted = \DateTime::createFromFormat('d.m.Y', $row['date_completed']);
        $dateCompletedFormatted = $dateCompleted ? $dateCompleted->format('Y-m-d') : null;

        // Check if time columns have '-' and replace with null, keep time format as 'HH:MM'
        $timeApplied = ($row['time_applied'] == '-' || empty($row['time_applied'])) ? null : $this->formatTime($row['time_applied']);
        $timeCompleted = ($row['time_completed'] == '-' || empty($row['time_completed'])) ? null : $this->formatTime($row['time_completed']);

        // Extract the value after "UiTM" or "UiTM Kampus" and before "-"
        $campusValue = $row['campus_zone'];
        if (strpos($campusValue, 'UiTM Kampus') !== false) {
            $splitCampusValue = explode('UiTM Kampus', $campusValue);
        } else {
            $splitCampusValue = explode('UiTM', $campusValue);
        }
        if (isset($splitCampusValue[1])) {
            $subValue = explode('-', $splitCampusValue[1]);
            $campus = isset($subValue[0]) ? trim($subValue[0]) : null;
        } else {
            $campus = null;
        }

        // Extract category (value before "-")
        $aduanMainCategory = $row['aduan_category'];
        $category = explode(' - ', $aduanMainCategory)[0];

        $rating = ($row['rating'] == '-' || empty($row['rating'])) ? null : (int) $row['rating'];
        $responseTime = ($row['response_time'] == '-' || empty($row['response_time'])) ? null : $row['response_time'];

        $responseDays = null;

        // If response_time is not null and contains 'd' (days)
        if ($responseTime) {
            // Match the number of days before the "d" in the format "8 d 7 h 22 m 52 s"
            preg_match('/(\d+)\s*d/', $responseTime, $dayMatches);
            // Extract and assign the number of days if available
            $responseDays = $dayMatches[1] ?? null;
        }

        // Check if the record exists
        $existingAduan = Aduan::where('aduan_ict_ticket', $row['aduan_ict_ticket'])->first();

        if ($existingAduan) {
            // Update existing record
            $existingAduan->update([
                'complainent_name' => $complainentName,
                'complainent_id' => $complainentId,
                'complainent_category' => $row['complainent_category'],
                'aduan_category' => $row['aduan_category'],
                'category' => $category,
                'aduan_subcategory' => $row['aduan_sub_category'],
                'campus' => $campus,
                'location' => $row['location'],
                'aduan_details' => $row['aduan_details'],
                'aduan_status' => $row['aduan_status'],
                'aduan_type' => $row['aduan_type'],
                'staff_duty' => $row['staff_on_duty'],
                'remark_staff_duty' => $row['remark_staff_on_duty'],
                'date_applied' => $dateAppliedFormatted,
                'time_applied' => $timeApplied,
                'date_completed' => $dateCompletedFormatted,
                'time_completed' => $timeCompleted,
                'response_time' => $responseTime,
                'response_days' => $responseDays,
                'rating' => $rating,
            ]);
            return null; // Return null since no new model is created
        }

        // Create a new record if it does not exist
        return new Aduan([
            'aduan_ict_ticket' => $row['aduan_ict_ticket'],
            'complainent_name' => $complainentName,
            'complainent_id' => $complainentId,
            'complainent_category' => $row['complainent_category'],
            'aduan_category' => $row['aduan_category'],
            'category' => $category,
            'aduan_subcategory' => $row['aduan_sub_category'],
            'campus' => $campus,
            'location' => $row['location'],
            'aduan_details' => $row['aduan_details'],
            'aduan_status' => $row['aduan_status'],
            'aduan_type' => $row['aduan_type'],
            'staff_duty' => $row['staff_on_duty'],
            'remark_staff_duty' => $row['remark_staff_on_duty'],
            'date_applied' => $dateAppliedFormatted,
            'time_applied' => $timeApplied,
            'date_completed' => $dateCompletedFormatted,
            'time_completed' => $timeCompleted,
            'response_time' => $responseTime,
            'response_days' => $responseDays,
            'rating' => $rating,
        ]);
    }

    private function formatTime($time)
    {
        if (empty($time) || $time == '-' || $time == null) {
            return null;  // Return null if the time is missing or placeholder
        }

        if (is_numeric($time)) {
            $timeInSeconds = $time * 24 * 60 * 60;

            $formattedTime = new DateTime();
            $formattedTime->setTimestamp($timeInSeconds);

            return $formattedTime->format('H:i:s');
        }

        $formattedTime = DateTime::createFromFormat('h:i:s A', $time);  // 12-hour format with AM/PM

        if ($formattedTime === false) {
            $formattedTime = DateTime::createFromFormat('H:i:s', $time);  // 24-hour format
        }

        return $formattedTime ? $formattedTime->format('H:i:s') : null;
    }
}
