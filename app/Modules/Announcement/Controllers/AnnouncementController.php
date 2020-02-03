<?php

namespace App\Modules\Announcement\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Announcement\Models\Announcement;
use Carbon\Carbon;
use Config;
use Auth;

class AnnouncementController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->can('announcement.page') ):
            abort(403);
        endif;

        if(Auth::user()->hasrole('admin')) {
            $announcement = Announcement::orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
        } else {
            $announcement = Announcement::where('announcement_to','like','%'. Auth::user()->name .'%')->paginate(Config::get('constants.common.paginate'));
        }
        return view("Announcement::index", ['pagetitle'=>'Announcement', 'pagedesc'=>'List'])->with(['announcement' => $announcement ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->can('announcement.create') ):
            abort(403);
        endif;
        return view("Announcement::create", ['pagetitle'=>'Announcement', 'pagedesc'=>'Create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new Announcement;
        $post->announcement_from = $request->input('announcement_from') ;
        $post->announcement_to = implode(',', $request->input('announcement_to')) ;
        $post->subject = $request->input('announcement_subject') ;
        $post->message = $request->input('announcement_message') ;
        $post->status = $request->input('announcement_status') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect()->route('announcement.page')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $announce = Announcement::find($id);

        return view("Announcement::show", ['pagetitle'=>'Announcement', 'pagedesc'=>'Update'])->with( ['announce' => $announce] );
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
        if( !Auth::user()->can('announcement.update') ):
            abort(403);
        endif;
        $post = Announcement::find($id);
        $post->announcement_to =implode(',', $request->input('announcement_to')) ;
        $post->subject = $request->input('announcement_subject') ;
        $post->message = $request->input('announcement_message') ;
        $post->status = $request->input('announcement_status') ;
        $post->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect()->route('announcement.page')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( !Auth::user()->can('announcement.destroy') ):
            abort(403);
        endif;
        $announce = Announcement::find($id);
        $announce->delete();

        return Redirect()->route('announcement.page')->with(['flash_success'=> 'Data Deleted']);
    }
}
