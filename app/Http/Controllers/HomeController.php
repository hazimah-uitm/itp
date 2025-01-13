<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function applyFilters(Request $request)
    {
        $month = $request->input('month', 'all'); // Default to 'all'
        $year = $request->input('year', 'all'); // Default to the current year
        $campus = $request->input('campus');

        $query = Aduan::query();

        if ($campus && count($campus)) {
            $query->whereIn('campus', $campus);
        }

        // Apply the month filter if it's not 'all'
        if ($month !== 'all') {
            $query->whereMonth('date_applied', $month);
        }

        // Apply year filter if not 'all'
        if ($year !== 'all') {
            $query->whereYear('date_applied', $year);
        }

        return $query;
    }


    public function index(Request $request)
    {
        $query = $this->applyFilters($request);

        $aduanList = $query->get(); // Get filtered data

        // Total Aduan
        $totalAduan = $aduanList->count();

        // Aduan by Status
        $aduanCompleted = $aduanList->whereIn('aduan_status', ['ADUAN COMPLETED', 'ADUAN VERIFIED'])->count();
        $inProgress = $aduanList->whereIn('aduan_status', ['IT SERVICES - 2ND LEVEL SUPPORT', '2ND LEVEL MAINTENANCE', '1ST LEVEL SUPPORT'])->count();
        $cancelled = $aduanList->where('aduan_status', 'ADUAN CANCELLED')->count();
        $closed = $aduanList->where('aduan_status', 'ADUAN CLOSED (INCOMPLETE INFORMATION / WRONG CHANNEL)')->count();

        // Percentages for Aduan by Status
        $percentAduanCompleted = ($totalAduan > 0) ? round(($aduanCompleted / $totalAduan) * 100, 2) : 0;
        $percentInProgress = ($totalAduan > 0) ? round(($inProgress / $totalAduan) * 100, 2) : 0;
        $percentCancelled = ($totalAduan > 0) ? round(($cancelled / $totalAduan) * 100, 2) : 0;
        $percentClosed = ($totalAduan > 0) ? round(($closed / $totalAduan) * 100, 2) : 0;

        // Aduan by Campus
        $samarahan = $aduanList->where('campus', 'SAMARAHAN')->count();
        $samarahan2 = $aduanList->where('campus', 'SAMARAHAN 2')->count();
        $mukah = $aduanList->where('campus', 'MUKAH')->count();

        // Percentages for Aduan by Campus
        $percentSamarahan = ($totalAduan > 0) ? round(($samarahan / $totalAduan) * 100, 2) : 0;
        $percentSamarahan2 = ($totalAduan > 0) ? round(($samarahan2 / $totalAduan) * 100, 2) : 0;
        $percentMukah = ($totalAduan > 0) ? round(($mukah / $totalAduan) * 100, 2) : 0;

        // Aduan by Complainent Category
        $staff = $aduanList->where('complainent_category', 'STAFF')->count();
        $student = $aduanList->where('complainent_category', 'STUDENT')->count();
        $guest = $aduanList->where('complainent_category', 'GUEST')->count();

        // Percentages for Aduan by Complainent Category
        $percentStaff = ($totalAduan > 0) ? round(($staff / $totalAduan) * 100, 2) : 0;
        $percentStudent = ($totalAduan > 0) ? round(($student / $totalAduan) * 100, 2) : 0;
        $percentGuest = ($totalAduan > 0) ? round(($guest / $totalAduan) * 100, 2) : 0;

        // Response Days Categories
        $responseDaysLessThanOrEqual3 = $aduanList->where('response_days', '<=', 3)->count();
        $responseDaysMoreThan3 = $aduanList->where('response_days', '>', 3)->count();

        // Percentages for Response Days
        $percentResponseLessThanOrEqual3 = ($totalAduan > 0) ? round(($responseDaysLessThanOrEqual3 / $totalAduan) * 100, 2) : 0;
        $percentResponseMoreThan3 = ($totalAduan > 0) ? round(($responseDaysMoreThan3 / $totalAduan) * 100, 2) : 0;

        // Group by Aduan Category and count occurrences
        $aduanCategoryCounts = $aduanList->groupBy('aduan_category')->map(function ($items) {
            return $items->count();
        });

        $aduanCategoryData = [];
        foreach ($aduanCategoryCounts as $category => $count) {
            $aduanCategoryData[] = [
                'category' => $category,
                'count' => $count,
                'percentage' => ($totalAduan > 0) ? round(($count / $totalAduan) * 100, 2) : 0
            ];
        }

        return view('home', [
            'aduanList' => $aduanList,
            'totalAduan' => $totalAduan,
            'aduanCompleted' => $aduanCompleted,
            'inProgress' => $inProgress,
            'cancelled' => $cancelled,
            'closed' => $closed,
            'percentAduanCompleted' => $percentAduanCompleted,
            'percentInProgress' => $percentInProgress,
            'percentCancelled' => $percentCancelled,
            'percentClosed' => $percentClosed,
            'samarahan' => $samarahan,
            'samarahan2' => $samarahan2,
            'mukah' => $mukah,
            'percentSamarahan' => $percentSamarahan,
            'percentSamarahan2' => $percentSamarahan2,
            'percentMukah' => $percentMukah,
            'staff' => $staff,
            'student' => $student,
            'guest' => $guest,
            'percentStaff' => $percentStaff,
            'percentStudent' => $percentStudent,
            'percentGuest' => $percentGuest,
            'responseDaysLessThanOrEqual3' => $responseDaysLessThanOrEqual3,
            'responseDaysMoreThan3' => $responseDaysMoreThan3,
            'percentResponseLessThanOrEqual3' => $percentResponseLessThanOrEqual3,
            'percentResponseMoreThan3' => $percentResponseMoreThan3,
            'aduanCategoryData' => $aduanCategoryData,
            'campusFilter' => $request->input('campus'),
        ]);
    }
}
