<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Models\SpecimenType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SpecimenTypeController extends Controller
{
    public function index(Request $request)
    {
        $specimenTypes = SpecimenType::orderBy('priority')->get();
        return view('setup.specimen_type', compact('specimenTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:specimen_types,name',
            'priority' => 'required|integer',
        ]);

        $specimenType = new SpecimenType();
        $specimenType->name = $request->input('name');
        $specimenType->priority = $request->input('priority');
        $specimenType->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'specimenType' => $specimenType]);
        }

        Session::flash('message', 'Specimen type created successfully!');
        Session::flash('alert-class', 'alert-success');

        return redirect()->back();
    }

    public function edit($id)
    {
        $specimenType = SpecimenType::findOrFail($id);
        return response()->json(['specimenType' => $specimenType]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:specimen_types,name,' . $id,
            'priority' => 'required|integer',
        ]);

        $specimenType = SpecimenType::findOrFail($id);
        $specimenType->name = $request->input('name');
        $specimenType->priority = $request->input('priority');
        $specimenType->save();

        Session::flash('message', 'Specimen type updated successfully!');
        Session::flash('alert-class', 'alert-success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $specimenType = SpecimenType::findOrFail($id);
        // dd($specimenType);
        $specimenType->delete();

        Session::flash('message', 'Specimen type deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        // return redirect()->back();
    }
}
