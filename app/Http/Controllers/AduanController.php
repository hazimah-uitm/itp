<?php

namespace App\Http\Controllers;

use App\Imports\AduanExport;
use App\Imports\AduanImport;
use App\Models\Aduan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AduanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $aduanList = Aduan::paginate($perPage);

        // Convert the date_applied field to Carbon instances
        foreach ($aduanList as $aduan) {
            $aduan->date_applied = \Carbon\Carbon::parse($aduan->date_applied);
        }

        return view('pages.aduan.index', [
            'aduanList' => $aduanList,
            'perPage' => $perPage
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new AduanExport, 'Aduan-ICT.xlsx');
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
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = Aduan::query();

        if ($search) {
            $query->where('aduan_category', 'LIKE', "%$search%")
                ->orWhere('campus', 'LIKE', "%$search%")
                ->orWhere('aduan_status', 'LIKE', "%$search%")
                ->orWhere('complainent_name', 'LIKE', "%$search%")
                ->orWhere('complainent_id', 'LIKE', "%$search%");
        }

        $aduanList = $query->latest()->paginate($perPage);

        return view('pages.aduan.index', [
            'aduanList' => $aduanList,
            'perPage' => $perPage,
            'search' => $search
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
