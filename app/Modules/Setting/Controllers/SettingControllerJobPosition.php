<?php

namespace App\Modules\Setting\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\SettingJobPosition;
use Carbon\Carbon;
use Config;


class SettingControllerJobPosition extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingjobposition = SettingJobPosition::all();

        return view("Setting::createjobposition", ['pagetitle'=>'Setting Job Position', 'pagedesc'=>'List'])->with(['settingjobposition' => $settingjobposition ]);
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
        $post = new SettingJobPosition;
        $post->name = $request->input('name') ;
        $post->type = $request->input('type') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingjobposition')->with(['flash_success'=> 'Data Saved']);


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = SettingJobPosition::find($id);

        return view("Setting::showjobposition", ['pagetitle'=>'Setting Job Position', 'pagedesc'=>'Update'])->with( ['setting' => $setting] );
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
        $post = SettingJobPosition::find($id);
        $post->name = $request->input('name') ;
        $post->type = $request->input('type') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingjobposition')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = SettingJobPosition::find($id);
        $setting->delete();
        return Redirect('setting/settingjobposition')->with(['flash_success'=> 'Data Deleted']);
    }
}
