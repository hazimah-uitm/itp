<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function applyFilters(Request $request)
    {
        $months = (array) $request->input('month', []);
        $years = (array) $request->input('year', []);
        $campus = $request->input('campus', []);

        $aduanStatus = $request->input('aduan_status', []);
        $complainentCategory = $request->input('complainent_category', []);
        $category = $request->input('category', []);
        $aduanCategory = $request->input('aduan_category', []);
        $staffDuty = $request->input('staff_duty', []);

        $query = Aduan::query();

        if (!empty($campus)) {
            $query->whereIn('campus', $campus);
        }

        if (!empty($staffDuty)) {
            $query->whereIn('staff_duty', $staffDuty);
        }

        if (!empty($aduanStatus)) {
            $query->whereIn('aduan_status', $aduanStatus);
        }

        if (!empty($complainentCategory)) {
            $query->whereIn('complainent_category', $complainentCategory);
        }

        if (!empty($category)) {
            $query->whereIn('category', $category);
        }

        if (!empty($aduanCategory)) {
            $query->whereIn('aduan_category', $aduanCategory);
        }

        if (!empty($months) && !in_array('all', $months)) {
            $query->whereIn(DB::raw('MONTH(date_applied)'), $months);
        }

        if (!empty($years) && !in_array('all', $years)) {
            $query->whereIn(DB::raw('YEAR(date_applied)'), $years);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->applyFilters($request);

        $aduanList = $query->get();

        $staffDutyFilter = Aduan::select('staff_duty')
            ->whereIn('staff_duty', $this->getSelectedStaff()) // Filter specific values
            ->distinct()
            ->orderBy('staff_duty', 'asc')
            ->pluck('staff_duty');

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
            ->distinct()
            ->orderBy('campus', 'asc')
            ->pluck('campus')
            ->unique();

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

        // JUMLAH ADUAN BY 1ST & 2ND LEVEL
        $selectedStaff = $this->getSelectedStaff();

        $aduan1stLevel = $aduanList->filter(function ($aduan) use ($selectedStaff) {
            return !is_null($aduan->staff_duty) && in_array($aduan->staff_duty, $selectedStaff);
        })->count();

        $aduan2ndLevel = $aduanList->filter(function ($aduan) use ($selectedStaff) {
            return !is_null($aduan->staff_duty) && !in_array($aduan->staff_duty, $selectedStaff);
        })->count();

        $total1st2ndLevel = $aduan1stLevel + $aduan2ndLevel;
        $percent1stLevel = ($total1st2ndLevel > 0) ? round(($aduan1stLevel / $total1st2ndLevel) * 100, 2) : 0;
        $percent2ndLevel = ($total1st2ndLevel > 0) ? round(($aduan2ndLevel / $total1st2ndLevel) * 100, 2) : 0;

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
        $responseDaysLessThanOrEqual3 = collect($aduanList)
            ->filter(function ($aduan) {
                return !is_null($aduan['response_days'])
                    && $aduan['response_days'] <= 3
                    && !in_array($aduan['aduan_status'], ['IT SERVICES - 2ND LEVEL SUPPORT', '2ND LEVEL MAINTENANCE', '1ST LEVEL MAINTENANCE']);
            })
            ->count();
        $responseDaysMoreThan3 = collect($aduanList)
            ->filter(function ($aduan) {
                return !is_null($aduan['response_days'])
                    && $aduan['response_days'] > 3
                    && !in_array($aduan['aduan_status'], ['IT SERVICES - 2ND LEVEL SUPPORT', '2ND LEVEL MAINTENANCE', '1ST LEVEL MAINTENANCE']);
            })
            ->count();
        $totalAduanCompleted = $responseDaysLessThanOrEqual3 + $responseDaysMoreThan3;
        $percentResponseLessThanOrEqual3 = ($totalAduanCompleted > 0) ? round(($responseDaysLessThanOrEqual3 / $totalAduanCompleted) * 100, 2) : 0;
        $percentResponseMoreThan3 = ($totalAduanCompleted > 0) ? round(($responseDaysMoreThan3 / $totalAduanCompleted) * 100, 2) : 0;

        // KATEGORI ADUAN AND SUBCATEGORY
        $aduanCategorySubcategoryCounts = $aduanList->groupBy('aduan_category')->map(function ($items) {
            return $items->groupBy('aduan_subcategory')->map(function ($subItems) {
                return $subItems->count();
            });
        })->toArray();

        $categoryTotalCounts = [];
        foreach ($aduanCategorySubcategoryCounts as $category => $subcategories) {
            $categoryTotalCounts[$category] = array_sum($subcategories);
        }
        arsort($categoryTotalCounts);
        foreach ($aduanCategorySubcategoryCounts as &$categoryCounts) {
            arsort($categoryCounts);
        }

        $allCategories = [];
        foreach ($categoryTotalCounts as $category => $totalCount) {
            foreach ($aduanCategorySubcategoryCounts[$category] as $subcategory => $count) {
                $allCategories[] = [
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'count' => $count,
                    'total_count' => $totalCount
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
        $excludeStatuses = ['IT SERVICES - 2ND LEVEL SUPPORT', '2ND LEVEL MAINTENANCE', '1ST LEVEL MAINTENANCE'];

        $complainantData = [
            'STAFF' => collect([0, 1, 2, 3, '>3'])->mapWithKeys(function ($days) use ($aduanList, $excludeStatuses) {
                return [
                    $days => $aduanList->where('complainent_category', 'STAFF')->filter(function ($item) use ($days, $excludeStatuses) {
                        return $item->response_days !== null &&
                            (($days === '>3' && $item->response_days > 3) || $item->response_days == $days) &&
                            !in_array($item->aduan_status, $excludeStatuses);
                    })->count(),
                ];
            }),
            'STUDENT' => collect([0, 1, 2, 3, '>3'])->mapWithKeys(function ($days) use ($aduanList, $excludeStatuses) {
                return [
                    $days => $aduanList->where('complainent_category', 'STUDENT')->filter(function ($item) use ($days, $excludeStatuses) {
                        return $item->response_days !== null &&
                            (($days === '>3' && $item->response_days > 3) || $item->response_days == $days) &&
                            !in_array($item->aduan_status, $excludeStatuses);
                    })->count(),
                ];
            }),
            'GUEST' => collect([0, 1, 2, 3, '>3'])->mapWithKeys(function ($days) use ($aduanList, $excludeStatuses) {
                return [
                    $days => $aduanList->where('complainent_category', 'GUEST')->filter(function ($item) use ($days, $excludeStatuses) {
                        return $item->response_days !== null &&
                            (($days === '>3' && $item->response_days > 3) || $item->response_days == $days) &&
                            !in_array($item->aduan_status, $excludeStatuses);
                    })->count(),
                ];
            }),
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
            '0' => ($totalAduan > 0) ? round(($totalComplaints['0'] / $totalAduan) * 100, 2) : 0,
            '1' => ($totalAduan > 0) ? round(($totalComplaints['1'] / $totalAduan) * 100, 2) : 0,
            '2' => ($totalAduan > 0) ? round(($totalComplaints['2'] / $totalAduan) * 100, 2) : 0,
            '3' => ($totalAduan > 0) ? round(($totalComplaints['3'] / $totalAduan) * 100, 2) : 0,
            '>3' => ($totalAduan > 0) ? round(($totalComplaints['>3'] / $totalAduan) * 100, 2) : 0,
        ];

        // Prepare data for stacked bar chart
        $aduanByMonthAndCategory = $query
            ->selectRaw("DATE_FORMAT(date_applied, '%m') as month, category, COUNT(*) as total")
            ->groupBy('month', 'category')
            ->orderBy('month', 'asc')
            ->get()
            ->groupBy('month');

        // Define month names
        $monthNames = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];

        // Format data for the stacked bar chart
        $aduanMonthCategoryChart = [];
        $categories = Aduan::distinct()->orderBy('category', 'asc')->pluck('category')->toArray();
        $months = collect(array_keys($monthNames)); // Use numeric keys to maintain order

        foreach ($months as $month) {
            $monthData = ['month' => $monthNames[$month]]; // Replace numeric month with its name
            foreach ($categories as $category) {
                $monthData[$category] = isset($aduanByMonthAndCategory[$month])
                    ? $aduanByMonthAndCategory[$month]->where('category', $category)->sum('total')
                    : 0;
            }
            $aduanMonthCategoryChart[] = $monthData;
        }

        //JUMLAH ADUAN X 1ST LEVEL STAFF
        $aduanByStaff = $aduanList->groupBy('staff_duty')->map(function ($aduans) {
            // Count total aduan for each staff
            $totalAduan = $aduans->count();
    
            // Count aduan where response_days >= 3
            $aduanMoreThan3Days = $aduans->where('response_days', '>', 3)->count();
    
            // Count aduan where response_days < 3
            $aduanLessThan3Days = $aduans->where('response_days', '<=', 3)->count();
    
            return [
                'total' => $totalAduan,
                'moreThan3Days' => $aduanMoreThan3Days,
                'lessThan3Days' => $aduanLessThan3Days
            ];
        });
    
        // Filter the results to show only the selected staff
        $aduanBySelectedStaff = $aduanByStaff->only($selectedStaff);

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
            'staffDutyFilter' => $staffDutyFilter,
            'complainantData' => $complainantData,
            'totalComplaints' => $totalComplaints,
            'percentageData' => $percentageData,
            'categories' => $categories,
            'aduanMonthCategoryChart' => $aduanMonthCategoryChart,
            'aduan1stLevel' => number_format($aduan1stLevel),
            'aduan2ndLevel' => number_format($aduan2ndLevel),
            'percent1stLevel' => $percent1stLevel,
            'percent2ndLevel' => $percent2ndLevel,
            'total1st2ndLevel' => number_format($total1st2ndLevel),
            'totalCountAllCategories' => number_format($totalCountAllCategories),
            'aduanBySelectedStaff' => $aduanBySelectedStaff,
        ]);
    }

    private function getSelectedStaff()
    {
        return [
            'AWANG BAHARUDIN B. AWANG AHMAD',
            'JUHARI BIN NORILY',
            'ARIZZAN BIN JAINI',
            'AMINUDDIN BIN BAKAR',
            'SITI SARA BINTI JULAIHI',
            'NARANG ANAK GERAMAN',
            'SUHANAH BINTI SHUHOR',
            'NORHAFIZAH BINTI MADHI',
            'SYARIFUDDIN BIN BUJANG',
            'AIMA SUMIYATI BINTI MALIKI',
            'AMIZAN BIN HAJI TAHET',
            'NAZAREEN BIN ABDUL LATIFF',
            'JULIANA BINTI KARTAWI',
            'RAFIDAH BINTI AHMAD',
            'ANAS ASRAWY BIN PENDAPAT',
            'SHILEYIUSKEN MIJEN',
            'IRENE BINTI SELUROH'
        ];
    }
}
