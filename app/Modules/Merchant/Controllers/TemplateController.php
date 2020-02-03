<?php

namespace App\Modules\Merchant\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use DB;
use Redirect;
use Session;
use Exception;
use Config;
use Input;
//use Illuminate\Support\Facades\Validator;
use Validator;
use Auth;
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Merchant\Models\MasterMerchantTemplateModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if( !Auth::user()->can('merchant.template.index') ):
            abort(403);
        endif;

        $query = MasterMerchantTemplateModel::query();

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        $types = $query->orderBy('created_at','asc')->paginate(Config::get('constants.common.paginate'));
        return view("Merchant::templateindex", ['pagetitle'=>__('Merchant::merchant.websitetemplate'), 'pagedesc'=>__('Admin::base.list'), 'types'=> $types]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->can('merchant.template.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'template_name'=>$request->input('template_name'),
                'template_url'=>$request->input('template_url'),
                'template_price'=>$request->input('template_price'),
            ];

            $rules = [
                'template_name'=>'required',
                'template_url'=>'required',
                'template_price'=>'required',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<br />'.$error;
                }

                throw new exception( $err );
            }

            if(MasterMerchantTemplateModel::existData($request->input('template_name'))>0){
                throw new exception(__('Admin::base.dataexist'));
            }

            if($request->input('template_id')!=''){
                MasterMerchantTemplateModel::updateData($request->input('template_id'),$request);
                $msg = 'updated';
            }else{
                MasterMerchantTemplateModel::insertData($request);
                $msg = 'created';
            }

            DB::commit();
            return Redirect('merchant/template')->with('flash_success', 'Data successfully '.$msg.'!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/template')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if( !Auth::user()->can('merchant.template.update') ):
            abort(403);
        endif;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( !Auth::user()->can('merchant.template.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $user = new MasterMerchantTemplateModel;
                $user->deleteData($id);
                
            else:

                throw new Exception("You didn't select any data to delete");  

            endif;

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Data(s) has been deleted!');
            
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $e->getMessage());
        }
    }

    public function getTemplateInfo($id){

        return json_encode(MasterMerchantTemplateModel::find($id));

    }

    public function disableData($id) {

        if( !Auth::user()->can('merchant.template.disable') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                
                MasterMerchantTemplateModel::where('template_id',$id)->update(['template_status'=>0]);
                
            else:
                throw new Exception("You didn't select any data to disable");               
            endif;

            DB::commit();
            return Redirect('merchant/template')->with('flash_success', 'Data(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enableData($id) {
        
        if( !Auth::user()->can('merchant.template.enable') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MasterMerchantTemplateModel::where('template_id',$id)->update(['template_status'=>1]);
                
            else:
                throw new Exception("You didn't select any data to enable");                
            endif;

            DB::commit();
            return Redirect('merchant/template')->with('flash_success', 'Data(s) has been enabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

}
