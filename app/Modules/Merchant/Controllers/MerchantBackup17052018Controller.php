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
use Globe;
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Merchant\Models\MerchantSubscriptionModel;
use App\Modules\Merchant\Models\MasterMerchantTemplateModel;
use App\Modules\Merchant\Models\MasterMerchantPackageModel;
use App\Modules\Merchant\Models\MasterMerchantSubPackageModel;
use App\Modules\Merchant\Models\MasterMerchantTypeModel;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Merchant\Models\MerchantMobileModel;
use App\Modules\Merchant\Models\MerchantEmailModel;
use App\Modules\Admin\Models\UploadModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class MerchantBackup17052018Controller extends Controller
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

        if( !Auth::user()->can('merchant.index') ):
            abort(403);
        endif;

        $query = MerchantModel::query();

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        if(Auth::user()->hasrole(['merchant','branch'])){
            $query->where('merchant_id',Auth::user()->merchant_id);
        }

        if(Auth::user()->hasrole('agent')){
            $query->where('created_by',Auth::user()->id);
        }

        $merchant = $query->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));

        $subpackage = MasterMerchantSubPackageModel::where('merchant_subpackage_status','1')->get();

        return view("Merchant::index", ['pagetitle'=>__('Merchant::merchant.merchant'), 'pagedesc'=>__('Admin::base.list'), 'merchants'=>$merchant, 'subpackage'=>$subpackage]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Storage::append('apilog.txt', 'test1234');
        if( !Auth::user()->can('merchant.create') ):
            abort(403);
        endif;

        $merchant = new MerchantModel;
        $states = MasterStateModel::get();
        $templates = MasterMerchantTemplateModel::where('template_status','1')->get();
        //$marketplaces = MasterMerchantMarketplaceModel::get();
        $types = MasterMerchantTypeModel::get();
        $packages = MasterMerchantPackageModel::where('merchant_package_status','1')->get();

        return view("Merchant::createmerchant", ['pagetitle'=>__('Merchant::merchant.addmerchant'), 'pagedesc'=>__('Merchant::merchant.merchant'), 'states'=>$states, 'templates'=>$templates, 'types'=>$types, 'packages'=>$packages, 'merchant'=>$merchant, 'disabled'=>'0']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if( !Auth::user()->can('merchant.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'person_in_charge'=>$request->input('merchant_person_incharge'),
                'email'=>strtolower($request->input('merchant_email')),
                'username'=>$request->input('merchant_username'),
                'password'=>$request->input('password'),
                //'app_name'=>$request->input('merchant_appname'),
                'company_name'=>$request->input('merchant_name'),
                'ssm_number'=>$request->input('merchant_ssmno'),
                'gst_number'=>$request->input('merchant_gstno'),
                'business_type'=>$request->input('merchant_type_id'),
                'address'=>$request->input('merchant_address1'),
                'postcode'=>$request->input('merchant_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'mobile_number'=>$request->input('merchant_mobileno'),
                'website_template'=>$request->input('template_id'),
                'package'=>$request->input('merchant_package_id'),
            ];

            $rules = [
                'person_in_charge'=>'required|max:100',
                //'email'=>'required|string|email|max:100|unique:users',
                'email'=>'required|string|email|max:100',
                'username'=>'required|max:100',
                'password' => 'required|string|min:6',
                //'app_name'=>'required|max:100',
                'company_name'=>'required|max:100',
                'ssm_number'=>'required|max:20',
                'gst_number'=>'required|max:20',
                'business_type'=>'required',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'mobile_number'=>'required|numeric',
                //'mobile_number'=>'required|numeric|max:16',
                'website_template'=>'required',
                'package'=>'required',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
                //'numeric' => __('Admin::base.postcodenumericonly'),
                'mobile_number.numeric' => __('Admin::user.numericonly'),
                'postcode.numeric' => __('Admin::user.numericonly'),
                'password.min' => __('Admin::user.passwordmin'),
                'company_email.unique' => __('Admin::user.emailusernameunique'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                if ( UserModel::checkDataExist('email', $request->input('merchant_email'), '') ) {
                    $validator->errors()->add('emailexist', __('Admin::user.emailexists'));
                }

                if ( UserModel::checkDataExist('username', $request->input('merchant_username'), '') ) {
                    $validator->errors()->add('usernameexist', __('Admin::user.usernameexists'));
                }

            });

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<br />'.$error;
                }

                throw new exception( $err );
            }

            if($request->input('password')!=$request->input('password_confirmation')){
                throw new exception(__('Admin::user.passwordnotmatch'));
            }

            $merchantid = MerchantModel::insertData($request);

            if($merchantid!=''){
                $mobilelist = $request->input('merchant_mobile_no');

                if(!empty($mobilelist)){
                    if(count($mobilelist)>0){
                        for($i=0; $i<count($mobilelist); $i++){
                            MerchantMobileModel::insertData($merchantid,$mobilelist[$i]);
                        }
                    }
                }

                $emaillist = $request->input('merchant_email_address');

                if(!empty($emaillist)){
                    if(count($emaillist)>0){
                        for($i=0; $i<count($emaillist); $i++){
                            MerchantEmailModel::insertData($merchantid,$emaillist[$i]);
                        }
                    }
                }

                $user = new UserModel;
                $user->name = strtoupper($request->input('merchant_person_incharge'));
                $user->email = strtolower($request->input('merchant_email'));
                $user->username = $request->input('merchant_username');
                $user->mobileno = $request->input('merchant_mobileno');
                $user->password = bcrypt($request->input('password'));
                $user->merchant_id = $merchantid;
                $user->created_at = Carbon::now();
                $user->updated_at = Carbon::now();

                if($user->save()){
                    DB::table('user_has_roles')->insert(['role_id'=>'4','user_id'=>$user->id]);
                }

                //uploadfile
                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/merchant/';
                $modelname = 'MerchantModel';
                $uploadpath = '/uploads/merchant/';

                if($request->hasfile('img_logo')) {
                    
                    $file = $request->file('img_logo');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $merchantid.'_'.date('YmdHis').'_logo.'.$ext;
                    $file->move($path, $name);

                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'1');

                }

                if($request->hasfile('img_flyer')) {
                    
                    $file = $request->file('img_flyer');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $merchantid.'_'.date('YmdHis').'_flyer.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'2');

                }

                if($request->hasfile('img_storefront')) {
                    
                    $file = $request->file('img_storefront');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $merchantid.'_'.date('YmdHis').'_storefront.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'3');

                }

                if($request->hasfile('img_background')) {
                    
                    $file = $request->file('img_background');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $merchantid.'_'.date('YmdHis').'_background.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'4');

                }

            }

            DB::commit();
            return Redirect('merchant')->with('flash_success', 'Merchant successfully registered!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/create')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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
        if( !Auth::user()->can('merchant.show') ):
            abort(403);
        endif;

        Globe::dataisaccessible('merchant',$id);

        $merchant = MerchantModel::findOrFail($id);

        $states = MasterStateModel::get();
        $templates = MasterMerchantTemplateModel::get();
        //$marketplaces = MasterMerchantMarketplaceModel::get();
        $types = MasterMerchantTypeModel::get();
        $packages = MasterMerchantPackageModel::where('merchant_package_status','1')->get();

        return view("Merchant::createmerchant", ['pagetitle'=>__('Merchant::merchant.updatemerchant'), 'pagedesc'=>__('Merchant::merchant.merchant'), 'states'=>$states, 'templates'=>$templates, 'types'=>$types, 'packages'=>$packages, 'merchant'=>$merchant, 'disabled'=>'0']);

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
        if( !Auth::user()->can('merchant.update') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $current = MerchantModel::findOrFail($id);

            $input = [
                'person_in_charge'=>$request->input('merchant_person_incharge'),
                'username'=>$request->input('merchant_username'),
                //'app_name'=>$request->input('merchant_appname'),
                'company_name'=>$request->input('merchant_name'),
                'ssm_number'=>$request->input('merchant_ssmno'),
                'gst_number'=>$request->input('merchant_gstno'),
                'business_type'=>$request->input('merchant_type_id'),
                'address'=>$request->input('merchant_address1'),
                'postcode'=>$request->input('merchant_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'mobile_number'=>$request->input('merchant_mobileno'),
                'website_template'=>$request->input('template_id'),
                'package'=>$request->input('merchant_package_id'),
            ];

            $rules = [
                'person_in_charge'=>'required|max:100',
                'username'=>'required|max:100',
                //'app_name'=>'required|max:100',
                'company_name'=>'required|max:100',
                'ssm_number'=>'required|max:20',
                'gst_number'=>'required|max:20',
                'business_type'=>'required',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'mobile_number'=>'required|numeric',
                //'mobile_number'=>'required|numeric|max:16',
                'website_template'=>'required',
                'package'=>'required',
            ];

            if($current->merchant_email != strtolower($request->input('merchant_email'))){
                $input2 = ['email'=>strtolower($request->input('merchant_email'))];
                $input = array_merge($input,$input2);
                //$rules2 = ['email'=>'required|string|email|max:100|unique:users'];
                $rules2 = ['email'=>'required|string|email|max:100'];
                $rules = array_merge($rules,$rules2);
            }

            if($request->input('password') != ''){
                $input3 = ['password'=>$request->input('password')];
                $input = array_merge($input,$input3);
                $rules3 = ['password' => 'required|string|min:6'];
                $rules = array_merge($rules,$rules3);
            }

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
                //'numeric' => __('Admin::base.postcodenumericonly'),
                'mobile_number.numeric' => __('Admin::user.numericonly'),
                'postcode.numeric' => __('Admin::user.numericonly'),
                'password.min' => __('Admin::user.passwordmin'),
                'company_email.unique' => __('Admin::user.emailusernameunique'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request,$current) {
                
                if ( UserModel::checkDataExist('email', $request->input('merchant_email'), $current->user->id) ) {
                    $validator->errors()->add('emailexist', __('Admin::user.emailexists'));
                }

                if ( UserModel::checkDataExist('username', $request->input('merchant_username'), $current->user->id) ) {
                    $validator->errors()->add('usernameexist', __('Admin::user.usernameexists'));
                }

            });

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<br />'.$error;
                }

                throw new exception( $err );
            }

            if($request->input('password') != ''){
                if($request->input('password')!=$request->input('password_confirmation')){
                    throw new exception(__('Admin::user.passwordnotmatch'));
                }
            }

            $merchantid = MerchantModel::updateData($id,$request);

            if($merchantid!=''){

                MerchantMobileModel::where('merchant_id',$merchantid)->where('merchant_mobile_type','0')->delete();

                $mobilelist = $request->input('merchant_mobile_no');

                if(!empty($mobilelist)){
                    if(count($mobilelist)>0){
                        for($i=0; $i<count($mobilelist); $i++){
                            MerchantMobileModel::insertData($merchantid,$mobilelist[$i]);
                        }
                    }
                }

                MerchantEmailModel::where('merchant_id',$merchantid)->where('merchant_email_type','0')->delete();

                $emaillist = $request->input('merchant_email_address');

                if(!empty($emaillist)){
                    if(count($emaillist)>0){
                        for($i=0; $i<count($emaillist); $i++){
                            MerchantEmailModel::insertData($merchantid,$emaillist[$i]);
                        }
                    }
                }

                $user = UserModel::where('merchant_id',$merchantid)->first();
                $user->name = strtoupper($request->input('merchant_person_incharge'));
                $user->email = strtolower($request->input('merchant_email'));
                $user->username = $request->input('merchant_username');
                $user->mobileno = $request->input('merchant_mobileno');
                if($request->input('password') != ''){
                    $user->password = bcrypt($request->input('password'));
                }
                $user->updated_at = Carbon::now();
                $user->save();

                //uploadfile
                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/merchant/';
                $modelname = 'MerchantModel';
                $uploadpath = '/uploads/merchant/';

                if($request->hasfile('img_logo')) {
                    
                    $file = $request->file('img_logo');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model','MerchantModel')->where('upload_type','1')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_logo.'.$ext;
                    $file->move($path, $name);

                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'1');

                }

                if($request->hasfile('img_flyer')) {
                    
                    $file = $request->file('img_flyer');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model','MerchantModel')->where('upload_type','2')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_flyer.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'2');

                }

                if($request->hasfile('img_storefront')) {
                    
                    $file = $request->file('img_storefront');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model','MerchantModel')->where('upload_type','3')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_storefront.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'3');

                }

                if($request->hasfile('img_background')) {
                    
                    $file = $request->file('img_background');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model','MerchantModel')->where('upload_type','4')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_background.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'4');

                }

            }

            DB::commit();
            return Redirect('merchant/'.$id)->with('flash_success', 'Merchant '.$request->input('merchant_name').' successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/'.$id)->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if( !Auth::user()->can('merchant.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = MerchantModel::findOrFail($id);

                if(!empty($data->logo)):
                    unlink(public_path($data->logo->upload_path.$data->logo->upload_filename));
                    UploadModel::where('upload_model_id',$id)->where('upload_model','MerchantModel')->where('upload_type','1')->delete();
                endif;

                if(!empty($data->flyer)):
                    unlink(public_path($data->flyer->upload_path.$data->flyer->upload_filename));
                    UploadModel::where('upload_model_id',$id)->where('upload_model','MerchantModel')->where('upload_type','2')->delete();
                endif;

                if(!empty($data->storefront)):
                    unlink(public_path($data->storefront->upload_path.$data->storefront->upload_filename));
                    UploadModel::where('upload_model_id',$id)->where('upload_model','MerchantModel')->where('upload_type','3')->delete();
                endif;

                if(!empty($data->background)):
                    unlink(public_path($data->background->upload_path.$data->background->upload_filename));
                    UploadModel::where('upload_model_id',$id)->where('upload_model','MerchantModel')->where('upload_type','4')->delete();
                endif;

                if($data->delete()):
                    MerchantEmailModel::where('merchant_id',$id)->delete();
                    MerchantMobileModel::where('merchant_id',$id)->delete();
                    UserModel::where('merchant_id',$id)->delete();
                endif;
                
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

    public function showinfo()
    {
        if( !Auth::user()->can('merchant.showinfo') ):
            abort(403);
        endif;

        $merchant = MerchantModel::findOrFail(Auth::user()->merchant_id);

        $states = MasterStateModel::get();
        $templates = MasterMerchantTemplateModel::where('template_status')->get();
        
        $types = MasterMerchantTypeModel::get();
        $packages = MasterMerchantPackageModel::where('merchant_package_status','1')->get();

        return view("Merchant::createmerchant", ['pagetitle'=>__('Merchant::merchant.detail'), 'pagedesc'=>'Info', 'states'=>$states, 'templates'=>$templates, 'types'=>$types, 'packages'=>$packages, 'merchant'=>$merchant, 'disabled'=>'1']);

    }

    public function setdomain(Request $request)
    {   
        /*if( !Auth::user()->can('merchant.setdomain') ):
            abort(403);
        endif;*/

        DB::beginTransaction();

        try {

            $input = [
                'domain_name'=>$request->input('merchant_domain'),
            ];

            $rules = [
                'domain_name'=>'required',
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

            //dd($request);
            $exist = MerchantModel::domainexist($request->input('merchant_id'),strtolower($request->input('merchant_domain')));

            if($exist==0){
                MerchantModel::where('merchant_id',$request->input('merchant_id'))->update(['merchant_domain'=>strtolower($request->input('merchant_domain'))]);
            }else{
                throw new exception(__('Merchant::merchant.msgdomainexist'));
            }

            DB::commit();
            return Redirect('user')->with('flash_success', 'Domain successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('user')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal4'=>true ]);
            
        }
    }
}
