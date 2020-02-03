<?php

namespace App\Modules\Setting\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\SettingEmpStatus;
use Carbon\Carbon;
use Config;


class SettingControllerEmpStatus extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingemploy = SettingEmpStatus::all();

        return view("Setting::createemploymentstatus", ['pagetitle'=>'Setting Employment Status', 'pagedesc'=>'List'])->with(['settingemploy' => $settingemploy ]);
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
        $post = new SettingEmpStatus;
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingempstatus')->with(['flash_success'=> 'Data Saved']);        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = SettingEmpStatus::find($id);

        return view("Setting::showempstatus", ['pagetitle'=>'Setting Chief', 'pagedesc'=>'Update'])->with(['setting' => $setting ]);
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
        $post = SettingEmpStatus::find($id);
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingempstatus')->with(['flash_success'=> 'Data Saved']);     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = SettingEmpStatus::find($id);
        $setting->delete();
        return Redirect('setting/settingempstatus')->with(['flash_success'=> 'Data Saved']);   
    }
}
