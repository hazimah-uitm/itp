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

        $aduanList = $query->whereIn('campus', ['Samarahan', 'Samarahan 2', 'Mukah'])->get();

        $complainentCategoryFilter = Aduan::select('complainent_category')
            ->distinct()
            ->orderBy('complainent_category', 'asc')
            ->pluck('complainent_category');

        $categoryFilter = Aduan::select('category')
            ->distinct()
            ->orderBy('category', 'asc')
            ->pluck('category');

        $aduanCategoryFilter = Aduan::select('aduan_category')
            ->distinct()
            ->orderBy('aduan_category', 'asc')
            ->pluck('aduan_category');

        $campusFilter = Aduan::select('campus')
            ->whereIn('campus', ['Samarahan', 'Samarahan 2', 'Mukah'])
            ->distinct()
            ->orderBy('campus', 'asc')
            ->pluck('campus');

        $aduanStatusFilter = Aduan::select('aduan_status')
            ->distinct()
            ->orderBy('aduan_status', 'asc')
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
        $samarahan = $aduanList->where('campus', 'Samarahan')->count();
        $samarahan2 = $aduanList->where('campus', 'Samarahan 2')->count();
        $mukah = $aduanList->where('campus', 'Mukah')->count();
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

        // KATEGORI ADUAN AND SUBCATEGORY
        $aduanCategorySubcategoryCounts = $aduanList->groupBy('aduan_category')->map(function ($items) {
            return $items->groupBy('aduan_subcategory')->map(function ($subItems) {
                return $subItems->count();
            });
        })->toArray();

        // Sort the counts by category and subcategory
        foreach ($aduanCategorySubcategoryCounts as &$categoryCounts) {
            arsort($categoryCounts);  // Sort subcategories in descending order by count
        }

        $allCategories = [];
        foreach ($aduanCategorySubcategoryCounts as $category => $subcategories) {
            foreach ($subcategories as $subcategory => $count) {
                $allCategories[] = [
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'count' => $count
                ];
            }
        }

        $totalCountAllCategories = array_sum(array_map(function ($category) {
            return $category['count'];
        }, $allCategories));

        // TOP 10 KATEGORI ADUAN
        $aduanTopCategoryCounts = $aduanList->groupBy('aduan_category')->map(function ($items) {
            return $items->count();
        })->toArray(); // Convert to an array for sorting
        arsort($aduanTopCategoryCounts);  // Sort the array in descending order by count
        $topCategories = array_slice($aduanTopCategoryCounts, 0, 10, true);  // Get the top 10 categories
        $othersCount = array_sum(array_slice($aduanTopCategoryCounts, 10));   // Calculate the "Lain-lain" count
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
            'STAFF' => [
                '0' => $aduanList->where('complainent_category', 'STAFF')->where('response_days', 0)->count(),
                '1' => $aduanList->where('complainent_category', 'STAFF')->where('response_days', 1)->count(),
                '2' => $aduanList->where('complainent_category', 'STAFF')->where('response_days', 2)->count(),
                '3' => $aduanList->where('complainent_category', 'STAFF')->where('response_days', 3)->count(),
                '>3' => $aduanList->where('complainent_category', 'STAFF')->where('response_days', '>', 3)->count(),
            ],
            'STUDENT' => [
                '0' => $aduanList->where('complainent_category', 'STUDENT')->where('response_days', 0)->count(),
                '1' => $aduanList->where('complainent_category', 'STUDENT')->where('response_days', 1)->count(),
                '2' => $aduanList->where('complainent_category', 'STUDENT')->where('response_days', 2)->count(),
                '3' => $aduanList->where('complainent_category', 'STUDENT')->where('response_days', 3)->count(),
                '>3' => $aduanList->where('complainent_category', 'STUDENT')->where('response_days', '>', 3)->count(),
            ],
            'GUEST' => [
                '0' => $aduanList->where('complainent_category', 'GUEST')->where('response_days', 0)->count(),
                '1' => $aduanList->where('complainent_category', 'GUEST')->where('response_days', 1)->count(),
                '2' => $aduanList->where('complainent_category', 'GUEST')->where('response_days', 2)->count(),
                '3' => $aduanList->where('complainent_category', 'GUEST')->where('response_days', 3)->count(),
                '>3' => $aduanList->where('complainent_category', 'GUEST')->where('response_days', '>', 3)->count(),
            ]
        ];

        // Calculate total complaints for each response day (combined for STAFF, STUDENT, and GUEST)
        $totalComplaints = [
            '0' => $complainantData['STAFF']['0'] + $complainantData['STUDENT']['0'] + $complainantData['GUEST']['0'],
            '1' => $complainantData['STAFF']['1'] + $complainantData['STUDENT']['1'] + $complainantData['GUEST']['1'],
            '2' => $complainantData['STAFF']['2'] + $complainantData['STUDENT']['2'] + $complainantData['GUEST']['2'],
            '3' => $complainantData['STAFF']['3'] + $complainantData['STUDENT']['3'] + $complainantData['GUEST']['3'],
            '>3' => $complainantData['STAFF']['>3'] + $complainantData['STUDENT']['>3'] + $complainantData['GUEST']['>3']
        ];

        // Calculate the percentage for each response day
        $percentageData = [
            '0' => ($totalComplaints['0'] / $totalAduan) * 100,
            '1' => ($totalComplaints['1'] / $totalAduan) * 100,
            '2' => ($totalComplaints['2'] / $totalAduan) * 100,
            '3' => ($totalComplaints['3'] / $totalAduan) * 100,
            '>3' => ($totalComplaints['>3'] / $totalAduan) * 100
        ];

        return view('home', [
            'aduanList' => $aduanList,
            'totalAduan' => number_format($totalAduan),
            'aduanCompleted' => number_format($aduanCompleted),
            'inProgress' => number_format($inProgress),
            'cancelled' => number_format($cancelled),
            'closed' => number_format($closed),
            'percentAduanCompleted' => $percentAduanCompleted,
            'percentInProgress' => $percentInProgress,
            'percentCancelled' => $percentCancelled,
            'percentClosed' => $percentClosed,
            'samarahan' => number_format($samarahan),
            'samarahan2' => number_format($samarahan2),
            'mukah' => number_format($mukah),
            'percentSamarahan' => $percentSamarahan,
            'percentSamarahan2' => $percentSamarahan2,
            'percentMukah' => $percentMukah,
            'staff' => number_format($staff),
            'student' => number_format($student),
            'guest' => number_format($guest),
            'percentStaff' => $percentStaff,
            'percentStudent' => $percentStudent,
            'percentGuest' => $percentGuest,
            'responseDaysLessThanOrEqual3' => number_format($responseDaysLessThanOrEqual3),
            'responseDaysMoreThan3' => number_format($responseDaysMoreThan3),
            'percentResponseLessThanOrEqual3' => $percentResponseLessThanOrEqual3,
            'percentResponseMoreThan3' => $percentResponseMoreThan3,
            'aduanCategoryData' => $aduanCategoryData,
            'allCategories' => $allCategories,
            'campusFilter' => $campusFilter,
            'aduanStatusFilter' => $aduanStatusFilter,
            'complainentCategoryFilter' => $complainentCategoryFilter,
            'categoryFilter' => $categoryFilter,
            'aduanCategoryFilter' => $aduanCategoryFilter,
            'complainantData' => $complainantData,
            'totalComplaints' => $totalComplaints,
            'percentageData' => $percentageData,
            'totalCountAllCategories' => number_format($totalCountAllCategories),
        ]);
    }
}
