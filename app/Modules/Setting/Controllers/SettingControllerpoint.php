<?php

namespace App\Modules\Setting\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\SettingPoint;
use Carbon\Carbon;
use Config;


class SettingControllerPoint extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingpoint = SettingPoint::all();

        return view("Setting::createpoint", ['pagetitle'=>'Setting Point', 'pagedesc'=>'List'])->with(['settingpoint' => $settingpoint ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Setting::create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r($_POST) ;
        $post = new SettingPoint;
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingpoint')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = SettingPoint::find($id);

        return view("Setting::showpoint", ['pagetitle'=>'Setting Point', 'pagedesc'=>'Update'])->with(['setting' => $setting ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = SettingPoint::find($id);
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingpoint')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $setting = SettingPoint::find($id);
        $setting->delete();

        return Redirect('setting/settingpoint')->with(['flash_success'=> 'Data Saved']);
    }
}
