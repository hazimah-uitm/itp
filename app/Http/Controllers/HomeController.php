<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aduanList = Aduan::all();
    
        // Total Aduan
        $totalAduan = $aduanList->count();
    
        // Aduan by Status
        $aduanCompleted = $aduanList->whereIn('aduan_status', ['ADUAN COMPLETED', 'ADUAN VERIFIED'])->count();
        $inProgress = $aduanList->whereIn('aduan_status', ['IT SERVICES - 2ND LEVEL SUPPORT', '2ND LEVEL MAINTENANCE', '1ST LEVEL SUPPORT'])->count();
        $cancelled = $aduanList->where('aduan_status', 'ADUAN CANCELLED')->count();
        $closed = $aduanList->where('aduan_status', 'ADUAN CLOSED (INCOMPLETE INFORMATION / WRONG CHANNEL)')->count();
    
        // Percentage calculation
        $percentAduanCompleted = ($totalAduan > 0) ? round(($aduanCompleted / $totalAduan) * 100, 2) : 0;
        $percentInProgress = ($totalAduan > 0) ? round(($inProgress / $totalAduan) * 100, 2) : 0;
        $percentCancelled = ($totalAduan > 0) ? round(($cancelled / $totalAduan) * 100, 2) : 0;
        $percentClosed = ($totalAduan > 0) ? round(($closed / $totalAduan) * 100, 2) : 0;
    
        // Aduan by campus
        $samarahan = $aduanList->where('campus', 'SAMARAHAN')->count();
        $samarahan2 = $aduanList->where('campus', 'SAMARAHAN 2')->count();
        $mukah = $aduanList->where('campus', 'MUKAH')->count();
    
        // Percentage calculation
        $percentSamarahan = ($totalAduan > 0) ? round(($samarahan / $totalAduan) * 100, 2) : 0;
        $percentSamarahan2 = ($totalAduan > 0) ? round(($samarahan2 / $totalAduan) * 100, 2) : 0;
        $percentMukah = ($totalAduan > 0) ? round(($mukah / $totalAduan) * 100, 2) : 0;
    
        // Aduan by complainent category
        $staff = $aduanList->where('complainent_category', 'STAFF')->count();
        $student = $aduanList->where('complainent_category', 'STUDENT')->count();
        $guest = $aduanList->where('complainent_category', 'GUEST')->count();
    
        // Percentage calculation
        $percentStaff = ($totalAduan > 0) ? round(($staff / $totalAduan) * 100, 2) : 0;
        $percentStudent = ($totalAduan > 0) ? round(($student / $totalAduan) * 100, 2) : 0;
        $percentGuest = ($totalAduan > 0) ? round(($guest / $totalAduan) * 100, 2) : 0;
    
        // Calculate response days categories
        $responseDaysLessThanOrEqual3 = $aduanList->where('response_days', '<=', 3)->count();
        $responseDaysMoreThan3 = $aduanList->where('response_days', '>', 3)->count();
    
        // Percentage calculation for response days
        $percentResponseLessThanOrEqual3 = ($totalAduan > 0) ? round(($responseDaysLessThanOrEqual3 / $totalAduan) * 100, 2) : 0;
        $percentResponseMoreThan3 = ($totalAduan > 0) ? round(($responseDaysMoreThan3 / $totalAduan) * 100, 2) : 0;
    
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
        ]);
    }
    
}
