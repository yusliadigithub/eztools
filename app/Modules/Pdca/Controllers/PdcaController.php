<?php

namespace App\Modules\Pdca\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Pdca\Models\Pdca;
use Carbon\Carbon;
use Config;
use Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class PdcaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->can('pdca.index') ):
            abort(403);
        endif;

        if(Auth::user()->hasrole('admin')) {
            $pdca = Pdca::orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
        } else {
            $pdca = Pdca::where('user_id', Auth::user()->id)->orwhere('createdToName', Auth::user()->name)->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
        }
        
        return view("Pdca::index", ['pagetitle'=>'PDCA', 'pagedesc'=>'List'])->with(['pdca' => $pdca ]);
    }

    public function pendingList()
    {
        $pdca = Pdca::where('status','pending')->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
        
        return view("Pdca::pendingList", ['pagetitle'=>'PDCA', 'pagedesc'=>'List'])->with(['pdca' => $pdca ]);
    }
    
    public function approvedList()
    {
        $pdca = Pdca::where('status','approved')->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
        
        return view("Pdca::pendingList", ['pagetitle'=>'PDCA', 'pagedesc'=>'List'])->with(['pdca' => $pdca ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( !Auth::user()->can('pdca.create') ):
            abort(403);
        endif;

        return view("Pdca::create", ['pagetitle'=>'PDCA', 'pagedesc'=>'Create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new Pdca;
        $post->user_name = $request->input('name') ;
        $post->user_id = $request->input('userid') ;
        $post->createdToName = $request->input('created_to') ;
        $post->p_current_problem = $request->input('p_current_problem') ;
        $post->p_cause_problem = $request->input('p_cause_problem') ;
        $post->p_solution = $request->input('p_solution') ;
        $post->p_investment = $request->input('p_investment') ;
        $post->p_investment = $request->input('p_investment') ;
        $post->p_benefit = $request->input('p_benefit') ;
        $post->p_goal = $request->input('p_goal') ;
        $post->d_before = $request->input('d_before') ;
        $post->d_after = $request->input('d_after') ;
        $post->c_follow_up = $request->input('c_follow_up') ;
        $post->c_effect_confirmation = $request->input('c_effect_confirmation') ;
        $post->a_rebuild = $request->input('a_rebuild') ;
        $post->a_sustain_method = $request->input('a_sustain_method') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect()->route('pdca.index')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pdca = Pdca::find($id);
        
        return view("Pdca::show", ['pagetitle'=>'PDCA', 'pagedesc'=>'Update'])->with( ['pdca' => $pdca] );
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
        if( !Auth::user()->can('pdca.update') ):
            abort(403);
        endif;

        $post = Pdca::find($id);
        $post->p_current_problem = $request->input('p_current_problem') ;
        $post->p_current_problem_remark = $request->input('p_current_problem_remark') ;
        $post->p_cause_problem = $request->input('p_cause_problem') ;
        $post->p_cause_problem_remark = $request->input('p_cause_problem_remark') ;
        $post->p_solution = $request->input('p_solution') ;
        $post->p_solution_remark = $request->input('p_solution_remark') ;
        $post->p_investment = $request->input('p_investment') ;
        $post->p_investment_remark = $request->input('p_investment_remark') ;
        $post->p_benefit = $request->input('p_benefit') ;
        $post->p_benefit_remark = $request->input('p_benefit_remark') ;
        $post->p_goal = $request->input('p_goal') ;
        $post->p_goal_remark = $request->input('p_goal_remark') ;
        $post->d_before = $request->input('d_before') ;
        $post->d_before_remark = $request->input('d_before_remark') ;
        $post->d_after = $request->input('d_after') ;
        $post->d_after_remark = $request->input('d_after_remark') ;
        $post->c_follow_up = $request->input('c_follow_up') ;
        $post->c_follow_up_remark = $request->input('c_follow_up_remark') ;
        $post->c_effect_confirmation = $request->input('c_effect_confirmation') ;
        $post->c_effect_confirmation_remark = $request->input('c_effect_confirmation_remark') ;
        $post->a_rebuild = $request->input('a_rebuild') ;
        $post->a_rebuild_remark = $request->input('a_rebuild_remark') ;
        $post->a_sustain_method = $request->input('a_sustain_method') ;
        $post->status = $request->input('status') ;
        $post->a_sustain_method_remark = $request->input('a_sustain_method_remark') ;
        $post->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        
        return Redirect()->route('pdca.index')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( !Auth::user()->can('pdca.destroy') ):
            abort(403);
        endif;
        
        $pdca = Pdca::find($id);
        $pdca->delete();

        return Redirect()->route('pdca.index')->with(['flash_success'=> 'Data Removed']);
    }
}
