<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomDropdown;

class CustomDropdownController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dropdown_name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        CustomDropdown::create([
            'dropdown_name' => $request->dropdown_name,
            'value' => $request->value,
        ]);

        return redirect()->back()->with('success', 'Value added successfully!');
    }
}
