<?php

namespace App\Http\Controllers\Admin\Setup;

use Illuminate\Http\Request;
use App\Models\CustomDropdown;
use App\Http\Controllers\Controller;
use App\Models\UrinalysisReferenceRanges;

class CustomDropdownController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // 'id' => 'nullable|array',
            // 'id.*' => 'nullable|integer|exists:custom_dropdowns,id',
            // 'dropdown_name' => 'required|array',
            // 'dropdown_name.*' => 'required|string|max:255',
            // 'value' => 'required|array',
            // 'value.*' => 'required|string|max:255',
            // 'deleted_ids' => 'nullable|string'
        ]);

        // Handle deletions
        if ($request->filled('deleted_ids')) {
            $deletedIds = explode(',', $request->deleted_ids);
            CustomDropdown::whereIn('id', $deletedIds)->delete();
        }

        // Handle updates and new entries
        foreach ($request->dropdown_name as $index => $dropdown_name) {
            $id = $request->id[$index];
            $value = $request->value[$index];

            if ($id) {
                // Update existing record
                $dropdown = CustomDropdown::find($id);
                $dropdown->update([
                    'value' => $value,
                ]);
            } else {
                // Create new record
                CustomDropdown::create([
                    'dropdown_name' => $dropdown_name,
                    'value' => $value,
                ]);
            }
        }

        return response()->json(['success' => 'Values added/updated/deleted successfully!']);
    }

    public function getDropdownNames($id)
    {
        $dropdownNames = CustomDropdown::select('dropdown_name', 'value')
        ->where('dropdown_name', $id)
        ->get();
        return response()->json($dropdownNames);
    }

    public function getvalues($id){
        $customdropdownvalues = CustomDropdown::where('dropdown_name', $id)->get();

        return response()->json(['customdropdownvalues' => $customdropdownvalues]);
    }

    public function uriRefRangesstore(Request $request){

        $reference_range = $request->input('urireference_range');
        $analyte = $request->input('analyte');
        // $test = new UrinalysisReferenceRanges();

        // $test->analyte  = $request->input('analyte');
            // Attempt to find an existing record by analyte
        $test = UrinalysisReferenceRanges::where('analyte', $analyte)->first();

        // If no existing record is found, create a new instance
        if (!$test) {
            $test = new UrinalysisReferenceRanges();
            $test->analyte = $analyte;
        }
        $test->urireference_range  = $request->input('urireference_range');
        if($reference_range == 'uri_basic_ref'){
            $test->low  = $request->input('basic_low_value');
            $test->high  = $request->input('basic_high_value');
            $test->male_low  = null;
            $test->male_high  = null;
            $test->female_low  = null;
            $test->female_high  = null;
            $test->nomanualvalues_ref_range = null;
        }else if($reference_range == 'uri_optional_ref'){
            $test->male_low  = $request->input('male_low_value');
            $test->male_high  = $request->input('male_high_value');
            $test->female_low  = $request->input('female_low_value');
            $test->female_high  = $request->input('female_high_value');
            $test->low  = null;
            $test->high  = null;
            $test->nomanualvalues_ref_range = null;
        }elseif ($reference_range == 'uri_no_manual_tag') {
            $test->nomanualvalues_ref_range = $request->input('nomanualvalues');
            $test->low  = null;
            $test->high  = null;
            $test->male_low  = null;
            $test->male_high  = null;
            $test->female_low  = null;
            $test->female_high  = null;
        }
        $test->save();

        return response()->json([
            'success' => 'Added Succesfully!',
            'test' => $test
        ]);
    }

    public function uriRefRangesgetvalues($id){
        $tests = UrinalysisReferenceRanges::where('analyte', $id)->first();
        // dd($test);
        return response()->json(['success' => $tests]);

    }
}
