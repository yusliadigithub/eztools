<?php

namespace App\Modules\Setting\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\Setting;
use Carbon\Carbon;
use Config;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::all();

        return view("Setting::index", ['pagetitle'=>'Setting', 'pagedesc'=>'List'])->with(['setting' => $setting ]);
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
        $post = new Setting;
        $post->type = $request->input('name') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect()->route('setting.page')->with(['flash_success'=> 'Data Saved']);


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = Setting::find($id);

        return view("Setting::show")->with( ['setting' => $setting] );
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
        $post = Setting::find($id);
        $post->announcement_from = $request->input('announcement_from') ;
        $post->announcement_to = $request->input('announcement_to') ;
        $post->subject = $request->input('announcement_subject') ;
        $post->status = $request->input('announcement_status') ;
        $post->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect()->route('setting.page')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
