<?php

namespace App\Http\Controllers;

use App\Exports\AduanExport;
use App\Imports\AduanImport;
use App\Models\Aduan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AduanController extends Controller
{
    protected function getFilteredAduan(Request $request, $applyFilters = false)
    {
        $perPage = $request->input('perPage', 10);
        $query = Aduan::query();
    
        if ($applyFilters) {
            $search = $request->input('search');
            $month = $request->input('month', 'all'); 
            $year = $request->input('year', 'all'); 
            $campus = $request->input('campus');
            $aduanStatus = $request->input('aduan_status');
        
            // Apply search filter
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
        
            // Apply month filter if not 'all'
            if ($month !== 'all') {
                $query->whereMonth('date_applied', $month);
            }
        
            // Apply year filter if not 'all'
            if ($year !== 'all') {
                $query->whereYear('date_applied', $year);
            }
        }
        
        $campusFilter = Aduan::select('campus')
        ->whereIn('campus', ['Samarahan', 'Samarahan 2', 'Mukah'])
        ->distinct()
        ->orderBy('campus', 'asc')
        ->pluck('campus');    
        
        $aduanStatusFilter = Aduan::select('aduan_status')
        ->distinct()
        ->pluck('aduan_status');
    
        $aduanList = $query->latest()->paginate($perPage);
    
        // Format dates
        foreach ($aduanList as $aduan) {
            $dateApplied = Carbon::parse($aduan->date_applied)->timezone('Asia/Kuching');
            $aduan->formatted_date = $dateApplied->format('d-m-Y');
            $aduan->month = $dateApplied->format('F');
            $aduan->year = $dateApplied->format('Y');
        }
    
        return [
            'aduanList' => $aduanList,
            'campusFilter' => $campusFilter,
            'aduanStatusFilter' => $aduanStatusFilter,
        ];
    }    

    public function index(Request $request)
    {
        $result = $this->getFilteredAduan($request);
    
        return view('pages.aduan.index', [
            'aduanList' => $result['aduanList'],
            'perPage' => $request->input('perPage', 10),
            'campusFilter' => $result['campusFilter'],
            'aduanStatusFilter' => $result['aduanStatusFilter'],
        ]);
    }
    
    public function export(Request $request)
    {
        return Excel::download(new AduanExport($request), 'Aduan-ICT.xlsx');
    }

    public function create()
    {
        return view('pages.aduan.create', [
            'save_route' => route('aduan.store'),
            'str_mode' => 'Tambah',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AduanImport, $request->file('file'));

            return redirect()->back()->with('success', 'Data telah berjaya di import');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'aduan_ict_ticket' => 'nullable',
            'complainent_name' => 'nullable',
            'complainent_id' => 'nullable',
            'complainent_category' => 'nullable',
            'aduan_category' => 'nullable',
            'category' => 'nullable',
            'aduan_subcategory' => 'nullable',
            'campus' => 'nullable',
            'location' => 'nullable',
            'aduan_details' => 'nullable',
            'aduan_status' => 'nullable',
            'aduan_type' => 'nullable',
            'staff_duty' => 'nullable',
            'remark_staff_duty' => 'nullable',
            'date_applied' => 'nullable',
            'time_applied' => 'nullable',
            'date_completed' => 'nullable',
            'time_completed' => 'nullable',
            'response_time' => 'nullable',
            'response_days' => 'nullable',
            'rating' => 'nullable'
        ]);

        $aduan = new Aduan();

        $aduan->fill($request->all());
        $aduan->save();

        return redirect()->route('aduan')->with('success', 'Maklumat berjaya disimpan');
    }

    public function show($id)
    {
        $aduan = Aduan::findOrFail($id);

        return view('pages.aduan.view', [
            'aduan' => $aduan,
        ]);
    }

    public function edit($id)
    {
        $aduan = Aduan::findOrFail($id);

        return view('pages.aduan.edit', [
            'aduan' => $aduan,
            'save_route' => route('aduan.update', $id),
            'str_mode' => 'Kemaskini',
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'aduan_ict_ticket' => 'nullable',
            'complainent_name' => 'nullable',
            'complainent_id' => 'nullable',
            'complainent_category' => 'nullable',
            'aduan_category' => 'nullable',
            'category' => 'nullable',
            'aduan_subcategory' => 'nullable',
            'campus' => 'nullable',
            'location' => 'nullable',
            'aduan_details' => 'nullable',
            'aduan_status' => 'nullable',
            'aduan_type' => 'nullable',
            'staff_duty' => 'nullable',
            'remark_staff_duty' => 'nullable',
            'date_applied' => 'nullable',
            'time_applied' => 'nullable',
            'date_completed' => 'nullable',
            'time_completed' => 'nullable',
            'response_time' => 'nullable',
            'response_days' => 'nullable',
            'rating' => 'nullable'
        ]);

        // Find the aduan record by ID
        $aduan = Aduan::findOrFail($id);

        $aduan->fill($request->all());
        $aduan->save();

        return redirect()->route('aduan')->with('success', 'Maklumat berjaya dikemas kini');
    }

    public function search(Request $request)
    {
        $result = $this->getFilteredAduan($request, true);
    
        return view('pages.aduan.index', [
            'aduanList' => $result['aduanList'],
            'perPage' => $request->input('perPage', 10),
            'search' => $request->input('search'),
            'month' => $request->input('month', 'all'),
            'year' => $request->input('year', 'all'),
            'campusFilter' => $result['campusFilter'],
            'aduanStatusFilter' => $result['aduanStatusFilter'],
        ]);
    }
    
    public function destroy(Request $request, $id)
    {
        $aduan = Aduan::findOrFail($id);

        $aduan->delete();

        return redirect()->route('aduan')->with('success', 'Maklumat berjaya dihapuskan');
    }

    public function trashList()
    {
        $trashList = Aduan::onlyTrashed()->latest()->paginate(10);

        return view('pages.aduan.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        Aduan::withTrashed()->where('id', $id)->restore();

        return redirect()->route('aduan')->with('success', 'Maklumat berjaya dikembalikan');
    }


    public function forceDelete($id)
    {
        $aduan = Aduan::withTrashed()->findOrFail($id);

        $aduan->forceDelete();

        return redirect()->route('aduan.trash')->with('success', 'Maklumat berjaya dihapuskan sepenuhnya');
    }
}
