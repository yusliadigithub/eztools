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
use App\Modules\Merchant\Models\MasterMerchantPackageModel;
use App\Modules\Merchant\Models\MasterMerchantSubPackageModel;
use App\Modules\Merchant\Models\MasterMerchantPackageSubModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
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

        if( !Auth::user()->can('merchant.package.index') ):
            abort(403);
        endif;

        $query = MasterMerchantPackageModel::query();

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        $types = $query->orderBy('created_at','asc')->paginate(Config::get('constants.common.paginate'));

        return view("Merchant::packageindex", ['pagetitle'=>__('Merchant::package.package'), 'pagedesc'=>__('Admin::base.list'), 'types'=> $types]);
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
        if( !Auth::user()->can('merchant.package.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'package_name'=>$request->input('merchant_package_name'),
                'package_price'=>$request->input('merchant_package_price'),
                'package_renewal_price'=>$request->input('merchant_package_renew_price'), 
                'package_maximum_total_product'=>$request->input('merchant_package_max_product'),
            ];

            $rules = [
                'package_name'=>'required',
                'package_price'=>'required',
                'package_renewal_price'=>'required',
                'package_maximum_total_product'=>'required',
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

            if(MasterMerchantPackageModel::existData($request->input('merchant_package_id'),$request->input('merchant_package_name'))>0){
                throw new exception(__('Admin::base.dataexist'));
            }

            if($request->input('merchant_package_id')!=''){
                $pkid = MasterMerchantPackageModel::updateData($request->input('merchant_package_id'),$request);
                $msg = 'updated';
            }else{
                $pkid = MasterMerchantPackageModel::insertData($request);
                $msg = 'created';
            }

            if(!empty($request->input('merchant_subpackage_id'))){
                $subpackage = array_filter($request->input('merchant_subpackage_id'));
            
                MasterMerchantPackageSubModel::where('merchant_package_id',$pkid)->delete();

                for($i=0; $i<count($subpackage); $i++){
                    MasterMerchantPackageSubModel::insertData(['merchant_package_id'=>$pkid, 'merchant_subpackage_id'=>$subpackage[$i]]);
                }

            }else{
                throw new exception(__('Merchant::package.chooseatleast'));
            }

            DB::commit();
            return Redirect('merchant/package')->with('flash_success', 'Data successfully '.$msg.'!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/package')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
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
        if( !Auth::user()->can('merchant.package.update') ):
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
        if( !Auth::user()->can('merchant.package.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $user = new MasterMerchantPackageModel;
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

    public function disableData($id) {

        if( !Auth::user()->can('merchant.package.disable') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                
                MasterMerchantPackageModel::where('merchant_package_id',$id)->update(['merchant_package_status'=>0]);
                
            else:
                throw new Exception("You didn't select any data to disable");               
            endif;

            DB::commit();
            return Redirect('merchant/package')->with('flash_success', 'Data(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enableData($id) {
        
        if( !Auth::user()->can('merchant.package.enable') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MasterMerchantPackageModel::where('merchant_package_id',$id)->update(['merchant_package_status'=>1]);
                
            else:
                throw new Exception("You didn't select any data to enable");                
            endif;

            DB::commit();
            return Redirect('merchant/package')->with('flash_success', 'Data(s) has been enabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

    public function getSubpackageNew(){

        $subpackages = MasterMerchantSubPackageModel::where('merchant_subpackage_status','1')->get();

        $html = '<div class="checkbox checbox-switch switch-dark"><label><input type="checkbox" onclick="toggleCheck(this)" name="checkall" class="checkall"><span></span>All</label></div>';

        if(count($subpackages)>0){
            foreach ($subpackages as $subpackage) {
                $html .= '<div class="checkbox checbox-switch switch-dark"><label><input type="checkbox" name="merchant_subpackage_id[]" value="'.$subpackage->merchant_subpackage_id.'"><span></span>'.$subpackage->merchant_subpackage_desc.'</label></div>';
            }
        }

        return json_encode($html);
        
    }

    public function getPackageInfo($id){

        $package = MasterMerchantPackageModel::find($id);

        $existsubpkg = [];
        if(!empty($package->subpackage)){
            foreach ($package->subpackage as $sp) {
                $existsubpkg[] += $sp->merchant_subpackage_id;
            }
        }

        $subpackages = MasterMerchantSubPackageModel::where('merchant_subpackage_status','1')->get();

        $html = '';

        if(count($subpackages)>0){
            foreach ($subpackages as $subpackage) {
                if(in_array($subpackage->merchant_subpackage_id,$existsubpkg)){
                    $checked = 'checked';
                }else{
                    $checked = '';
                }
                $html .= '<div class="checkbox checbox-switch switch-dark"><label><input type="checkbox" name="merchant_subpackage_id[]" value="'.$subpackage->merchant_subpackage_id.'" '.$checked.'><span></span>'.$subpackage->merchant_subpackage_desc.'</label></div>';
            }
        }

        if(count($subpackages) == count($existsubpkg)){
            $allchecked = 'checked';
        }else{
            $allchecked = '';
        }

        $htmlall = '<div class="checkbox checbox-switch switch-dark"><label><input type="checkbox" onclick="toggleCheck(this)" name="checkall" class="checkall" '.$allchecked.'><span></span>All</label></div>';

        return json_encode(['package'=>$package, 'html'=>$htmlall.$html]);

    }

    public function getSubPackageInfo($id){

        $package = MasterMerchantPackageModel::findOrFail($id);

        $html = '';
        if(count($package->subpackage)>0){
            foreach ($package->subpackage as $sp) {

                $html .= '<tr><td><i class="fa fa-dot-circle"></i>  '.$sp->subpackage->merchant_subpackage_desc.'</td></tr>';

            }
        }

        return json_encode(['package'=>$package, 'html'=>$html]);

    }

}
