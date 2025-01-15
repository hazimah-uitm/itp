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
        $aduanStatus = $request->input('aduan_status');
        $complainentCategory = $request->input('complainent_category');
        $category = $request->input('category');
        $aduanCategory = $request->input('aduan_category');

        $query = Aduan::query();

        if ($campus && $campus !== 'all') {
            $query->where('campus', $campus);
        }

        if ($aduanStatus && $aduanStatus !== 'all') {
            $query->where('aduan_status', $aduanStatus);
        }

        if ($complainentCategory && $complainentCategory !== 'all') {
            $query->where('complainent_category', $complainentCategory);
        }        

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($aduanCategory && $aduanCategory !== 'all') {
            $query->where('aduan_category', $aduanCategory);
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

        $aduanList = $query->get(); 
        
        $complainentCategoryFilter = Aduan::select('complainent_category')
        ->distinct()
        ->pluck('complainent_category');
        
        $categoryFilter = Aduan::select('category')
        ->distinct()
        ->pluck('category');
        
        $aduanCategoryFilter = Aduan::select('aduan_category')
        ->distinct()
        ->pluck('aduan_category');
        
        $campusFilter = Aduan::select('campus')
        ->distinct()
        ->pluck('campus');
        
        $aduanStatusFilter = Aduan::select('aduan_status')
        ->distinct()
        ->pluck('aduan_status');

        // JUMLAH ADUAN ICT
        $totalAduan = $aduanList->count();

        // JUMLAH ADUAN BY STATUS
        $aduanCompleted = $aduanList->whereIn('aduan_status', ['ADUAN COMPLETED', 'ADUAN VERIFIED'])->count();
        $inProgress = $aduanList->whereIn('aduan_status', ['IT SERVICES - 2ND LEVEL SUPPORT', '2ND LEVEL MAINTENANCE', '1ST LEVEL MAINTENANCE'])->count();
        $cancelled = $aduanList->where('aduan_status', 'ADUAN CANCELLED')->count();
        $closed = $aduanList->where('aduan_status', 'ADUAN CLOSED (INCOMPLETE INFORMATION / WRONG CHANNEL)')->count();
        $percentAduanCompleted = ($totalAduan > 0) ? round(($aduanCompleted / $totalAduan) * 100, 2) : 0;
        $percentInProgress = ($totalAduan > 0) ? round(($inProgress / $totalAduan) * 100, 2) : 0;
        $percentCancelled = ($totalAduan > 0) ? round(($cancelled / $totalAduan) * 100, 2) : 0;
        $percentClosed = ($totalAduan > 0) ? round(($closed / $totalAduan) * 100, 2) : 0;

        // JUMLAH ADUAN BY CAMPUS
        $samarahan = $aduanList->where('campus', 'SAMARAHAN')->count();
        $samarahan2 = $aduanList->where('campus', 'SAMARAHAN 2')->count();
        $mukah = $aduanList->where('campus', 'MUKAH')->count();
        $percentSamarahan = ($totalAduan > 0) ? round(($samarahan / $totalAduan) * 100, 2) : 0;
        $percentSamarahan2 = ($totalAduan > 0) ? round(($samarahan2 / $totalAduan) * 100, 2) : 0;
        $percentMukah = ($totalAduan > 0) ? round(($mukah / $totalAduan) * 100, 2) : 0;

        // JUMLAH ADUAN BY COMPLAINENT CATEGORY
        $staff = $aduanList->where('complainent_category', 'STAFF')->count();
        $student = $aduanList->where('complainent_category', 'STUDENT')->count();
        $guest = $aduanList->where('complainent_category', 'GUEST')->count();
        $percentStaff = ($totalAduan > 0) ? round(($staff / $totalAduan) * 100, 2) : 0;
        $percentStudent = ($totalAduan > 0) ? round(($student / $totalAduan) * 100, 2) : 0;
        $percentGuest = ($totalAduan > 0) ? round(($guest / $totalAduan) * 100, 2) : 0;

        // JUMLAH ADUAN MENGIKUT PIAGAM
        $responseDaysLessThanOrEqual3 = $aduanList->where('response_days', '<=', 3)->count();
        $responseDaysMoreThan3 = $aduanList->where('response_days', '>', 3)->count();
        $percentResponseLessThanOrEqual3 = ($totalAduan > 0) ? round(($responseDaysLessThanOrEqual3 / $totalAduan) * 100, 2) : 0;
        $percentResponseMoreThan3 = ($totalAduan > 0) ? round(($responseDaysMoreThan3 / $totalAduan) * 100, 2) : 0;

        // KATEGORI ADUAN
        $aduanCategoryCounts = $aduanList->groupBy('aduan_category')->map(function ($items) {
            return $items->count();
        })->toArray(); // Convert to an array for sorting
        arsort($aduanCategoryCounts);  // Sort the array in descending order by count
        $topCategories = array_slice($aduanCategoryCounts, 0, 10, true);  // Get the top 10 categories
        $othersCount = array_sum(array_slice($aduanCategoryCounts, 10));   // Calculate the "Lain-lain" count
        $aduanCategoryData = [];
        foreach ($topCategories as $category => $count) {
            $percentage = ($totalAduan > 0) ? round(($count / $totalAduan) * 100, 2) : 0;
            $aduanCategoryData[] = [
                'category' => $category,
                'count' => $count,
                'percentage' => $percentage
            ];
        }
        if ($othersCount > 0) {
            $aduanCategoryData[] = [
                'category' => 'Lain-lain',
                'count' => $othersCount,
                'percentage' => ($totalAduan > 0) ? round(($othersCount / $totalAduan) * 100, 2) : 0
            ];
        }

        // ADUAN BY KATEGORI PENGADU DAN RESPONSE DAYS
        $complainantData = [
            'STAFF' => $staff,
            'STUDENT' => $student,
            'GUEST' => $guest,
        ];
        
        $responseDaysData = [
            '0' => $aduanList->where('response_days', 0)->count(),
            '1' => $aduanList->where('response_days', 1)->count(),
            '2' => $aduanList->where('response_days', 2)->count(),
            '3' => $aduanList->where('response_days', 3)->count(),
            '>3' => $aduanList->where('response_days', '>', 3)->count(),
        ];

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
            'campusFilter' => $campusFilter,
            'aduanStatusFilter' => $aduanStatusFilter,
            'complainentCategoryFilter' => $complainentCategoryFilter,
            'categoryFilter' => $categoryFilter,
            'aduanCategoryFilter' => $aduanCategoryFilter,
            'complainantData' => $complainantData,
            'responseDaysData' => $responseDaysData,
        ]);
    }
}
