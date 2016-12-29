<?php

namespace App\Http\Controllers\Dashboard;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(){
        $setting=Setting::first();
        return view('dashboard.setting',compact('setting'));
    }

    public function update(Request $request){
        $this->validate($request,[
            'site_name'=>'required',
            'about'=>'required',
        ]);
        $setting=Setting::first();
        $setting->site_name=$request['site_name'];
        $setting->facebook=$request['facebook'];
        $setting->twitter=$request['twitter'];
        $setting->about=$request['about'];
        if ($setting->update()){
            return redirect('dashboard/settings')->with('message', 'Settings updated successfully!')->with('class', 'alert-success');
        }
        return back()->with('message', 'Failed updating settings')->with('class', 'alert-danger');
    }
}
