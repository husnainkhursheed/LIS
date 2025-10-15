<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function change_password_index(){
        $name = Setting::first();
        // $prompt = TranscrptionPrompts::first();
        // dd($prompt->summary_prompt);
        return view("profile.settings" ,compact('name'));
    }

    public function change_password(Request $request){
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string',  'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            Session::flash('message', 'Your Current password does not matches with the password you provided. Please try again.!');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        } else {
            $id = Auth::user()->id;
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back();
            }
        }
    }

    public function change_socialconfig(Request $request){
    //     $request->validate([
    //         'auth_Id' => 'required',
    //         'fb_pageId' => 'required',
    //         'assemblyai_api_key' => 'required',
    //    ]);
        $id = $request->input('lab_director_nameid');
        // $save =  WhatsappConfiguration::find($id);

        // $save->ultramsg_intance_id  = $request->input('ultramsg_intance_id');
        // $save->ultramsg_token  = $request->input('ultramsg_token');
        // $save->inserted_by  = Auth::user()->id;

        Setting::updateOrCreate(
            ['id' => $id], // Conditions to find the record
            [
                'lab_director_name' => $request->input('lab_director_name'),
            ]
        );

        Session::flash('message', 'Updated successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }
}
