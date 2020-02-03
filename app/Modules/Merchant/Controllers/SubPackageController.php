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
use App\Modules\Merchant\Models\MasterMerchantSubPackageModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class SubPackageController extends Controller
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

        if( !Auth::user()->can('merchant.subpackage.index') ):
            abort(403);
        endif;

        $query = MasterMerchantSubPackageModel::query();

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        $types = $query->orderBy('created_at','asc')->paginate(Config::get('constants.common.paginate'));
        return view("Merchant::subpackageindex", ['pagetitle'=>__('Merchant::package.subpackage'), 'pagedesc'=>__('Admin::base.list'), 'types'=> $types]);
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
        if( !Auth::user()->can('merchant.subpackage.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'sub_package'=>$request->input('merchant_subpackage_desc'),
            ];

            $rules = [
                'sub_package'=>'required',
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

            if(MasterMerchantSubPackageModel::existData($request->input('merchant_subpackage_id'),$request->input('merchant_subpackage_desc'))>0){
                throw new exception(__('Admin::base.dataexist'));
            }

            //dd($request);
            if($request->input('merchant_subpackage_id')!=''){
                MasterMerchantSubPackageModel::updateData($request->input('merchant_subpackage_id'),$request);
                $msg = 'updated';
            }else{
                MasterMerchantSubPackageModel::insertData($request);
                $msg = 'created';
            }

            DB::commit();
            return Redirect('merchant/subpackage')->with('flash_success', 'Data successfully '.$msg.'!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/subpackage')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
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
        if( !Auth::user()->can('merchant.subpackage.update') ):
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
        if( !Auth::user()->can('merchant.subpackage.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $user = new MasterMerchantSubPackageModel;
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

    public function disable($id) {

        // if( !Auth::user()->can('merchant.subpackage.disable') ):
        //     abort(403);
        // endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MasterMerchantSubPackageModel::where('merchant_subpackage_id',$id)->update(['merchant_subpackage_status'=>0]);
                
            else:
                throw new Exception("You didn't select any data to disable");               
            endif;

            DB::commit();
            return Redirect('merchant/subpackage')->with('flash_success', 'Data(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enable($id) {
        
        // if( !Auth::user()->can('merchant.subpackage.enable') ):
        //     abort(403);
        // endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MasterMerchantSubPackageModel::where('merchant_subpackage_id',$id)->update(['merchant_subpackage_status'=>1]);
                
            else:
                throw new Exception("You didn't select any data to enable");                
            endif;

            DB::commit();
            return Redirect('merchant/subpackage')->with('flash_success', 'Data(s) has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

    public function getInfo($id){

        return json_encode(MasterMerchantSubPackageModel::findOrFail($id));

    }

}
