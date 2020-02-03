<?php

namespace App\Modules\Setting\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\SettingDepartment;
use Carbon\Carbon;
use Config;


class SettingControllerDepartment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setdepart = SettingDepartment::all();

        return view("Setting::createdepartment", ['pagetitle'=>'Setting Department', 'pagedesc'=>'List'])->with(['setupdepart' => $setdepart ]);
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
        $post = new SettingDepartment;
        $post->name = $request->input('name') ;
        $post->type = $request->input('type') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingdepartment')->with(['flash_success'=> 'Data Saved']);


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = SettingDepartment::find($id);

        return view("Setting::showdepartment", ['pagetitle'=>'Setting Department', 'pagedesc'=>'Update'])->with(['setting' => $setting ],['flash_success'=> 'Data Saved']);
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
        $post = SettingDepartment::find($id);
        $post->name = $request->input('name') ;
        $post->type = $request->input('type') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingdepartment')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = SettingDepartment::find($id);
        $setting->delete();

        return Redirect('setting/settingdepartment')->with(['flash_success'=> 'Data Deleted']);
    }
}
