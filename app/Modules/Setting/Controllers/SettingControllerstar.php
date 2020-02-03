<?php

namespace App\Modules\Setting\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\SettingStar;
use Carbon\Carbon;
use Config;


class SettingControllerStar extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingstar = SettingStar::all();

        return view("Setting::createstar", ['pagetitle'=>'Setting Star', 'pagedesc'=>'List'])->with(['settingstar' => $settingstar ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view("Setting::createstar");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new SettingStar;
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect('setting/settingstar')->with(['flash_success'=> 'Data Saved']);


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = SettingStar::find($id);

        return view("Setting::showstar", ['pagetitle'=>'Setting Star', 'pagedesc'=>'Update'])->with(['setting' => $setting ]);
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
        $post = SettingStar::find($id);
        $post->name = $request->input('name') ;
        $post->value = $request->input('value') ;
        $post->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;    
        return Redirect('setting/settingstar')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $settingstar = SettingStar::find($id);
        $settingstar->delete();
        
        return Redirect()->route('settingstar.index')->with(['flash_success'=> 'Data Saved']);
        
        return Redirect('setting/settingchief')->with(['flash_success'=> 'Data Deleted']);
    }
}
