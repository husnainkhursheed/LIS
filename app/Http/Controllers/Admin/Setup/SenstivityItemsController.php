<?php

namespace App\Http\Controllers\Admin\Setup;

use Illuminate\Http\Request;
use App\Models\SensitivityItems;
use App\Models\SensitivityProfiles;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class SenstivityItemsController extends Controller
{
    public function index(Request $request)

    {

        $query = SensitivityProfiles::query();

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');

            });
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order') ?? 'asc'; // Default to ascending if not specified
            $query->orderBy($request->input('sort_by'), $sortOrder);
        }

       $profiles = $query->paginate(10);

    //    $profiles = SensitivityProfiles::all();

       return view('setup.senstivity', compact('profiles'));

    }

    public function store(Request $request)

    {

        // dd($request->all());

        $request->validate([

            'name'=>'required',

            'antibiotic' => 'required',

        ]);



        $profile = new SensitivityProfiles();

        $profile->name = $request->name;

        if ($profile->save()) {

            foreach ($request->antibiotic as $index => $antibioticValue) {

                $antibiotic_value = new SensitivityItems();

                $antibiotic_value->profile_id = $profile->id;

                $antibiotic_value->antibiotic = $antibioticValue;

                $antibiotic_value->save();

            }
        }

        Session::flash('message', 'Profile created successfully!');

        Session::flash('alert-class', 'alert-success');

        return redirect()->back();

    }



    public function edit($id)

    {

        $profile = SensitivityProfiles::find($id);

        $profile->sensitivityValues;

        // dd($attribute->attributeValues);



        return response()->json([

            'profile' => $profile,

        ]);

    }



    public function update(Request $request, $id)

    {



        // dd($request->all());

        // $request->validate([

        //     'attribute_name'=>'required',

        //     'attribute_values' => 'required',

        // ]);



        // $attribute =  Attribute::find($id);

        // $attribute->attribute_name = $request->attribute_name;

        // $attribute->attribute_code = $request->attribute_code;

        // $attribute->sort_order = $request->sort_order;

        // $attribute->update();

        // $attribute->attributeValues()->delete();

        // // if ($attribute->save()) {

        //     foreach ($request->attribute_values as $index => $attributeValue) {

        //         $attribute_value = new AttributeValue();

        //         $attribute_value->attribute_id = $attribute->id;

        //         $attribute_value->attribute_value = $attributeValue;

        //         $attribute_value->sort_order = $request->sort_order_attvalue[$index];

        //         $attribute_value->attribute_value_code = $request->attribute_value_code[$index];

        //         $attribute_value->save();

        //     }

        // Session::flash('message', 'Updated successfully!');

        // Session::flash('alert-class', 'alert-success');

        // return redirect()->back();



        $request->validate([

            'name' => 'required',

            'antibiotic' => 'required|array',

            // Add other validation rules as needed

        ]);

        // dd($request->attribute_values_ids);



        $profile =  SensitivityProfiles::find($id);

        $profile->name = $request->name;

        $profile->update();

        // Get the existing AttributeValue IDs associated with the attribute

        $existingValueIds = $profile->sensitivityValues()->pluck('id')->toArray();

        // dd($existingValueIds);



        // Identify values to be deleted

        $valuesToDelete = array_diff($existingValueIds, $request->senstivityItems_ids);



        // Check for associations before deleting

        foreach ($valuesToDelete as $valueId) {

            // $associatedProducts = ProductToAttribute::where('attribute_value_id', $valueId)->exists();



            // if ($associatedProducts) {

            //     // Handle the case where there are associated products (e.g., skip deletion)

            //     // You might want to add some logic here based on your specific requirements

            //     continue;

            // }

            // If no associated products, delete the AttributeValue

            SensitivityItems::find($valueId)->delete();

        }



        foreach ($request->senstivityItems_ids as $index => $valueId) {

            // Find the existing AttributeValue

            $existingValue = SensitivityItems::find($valueId);



            if ($existingValue) {

                // Update the existing value

                $existingValue->antibiotic = $request->antibiotic[$index];

                $existingValue->update();

            } else {

                // Handle the case if the value doesn't exist (optional)

                $newSensitivityItems = new SensitivityItems();

                $newSensitivityItems->profile_id = $profile->id;

                $newSensitivityItems->antibiotic = $request->antibiotic[$index];

                $newSensitivityItems->save();

            }

        }



        Session::flash('message', 'Updated successfully!');

        Session::flash('alert-class', 'alert-success');

        return redirect()->back();
    }



    public function destroy($id)

    {

        // $role = Role::find($id);

        $associatedProducts = ProductToAttribute::where('attribute_id', $id)->exists();

        // dd($associatedProducts);

        if ($associatedProducts) {



            Session::flash('message', 'This Attribute is associted with some products!');

            Session::flash('alert-class', 'alert-danger');

            return $response = [

                'status' => 'success',

                // 'message' => 'This Attribute is associated with some products!',

            ];



            // return redirect()->back();



        }else {

            $role = Attribute::destroy($id);

            // $role->delete();

            Session::flash('message', 'Deleted successfully!');

            Session::flash('alert-class', 'alert-success');

        }



    }

}
