<?php

namespace App\Modules\Setting\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\SettingRequest;
use Carbon\Carbon;
use Config;


class SettingControllerRequest extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingrequest = SettingRequest::all();

        return view("Setting::createrequest", ['pagetitle'=>'Setting Request', 'pagedesc'=>'List'])->with(['settingrequest' => $settingrequest ]);
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
        $post = new SettingRequest;
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingrequest')->with(['flash_success'=> 'Data Saved']);


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = SettingRequest::find($id);

        return view("Setting::showrequest", ['pagetitle'=>'Setting Request', 'pagedesc'=>'List'])->with(['setting' => $setting ]);
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
        $post = SettingRequest::find($id);
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingrequest')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = SettingRequest::find($id);
        $post->delete();
        
        return Redirect('setting/settingrequest')->with(['flash_success'=> 'Data Saved']);
    }
}
