<?php

namespace App\Http\Controllers\Admin\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomDropdown;

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
}
