<?php

namespace App\Modules\Merchant\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
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
use URL;
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
use App\Modules\Merchant\Models\MerchantConfigurationModel;
use App\Modules\Merchant\Models\MerchantScheduleDayModel;
use App\Modules\Merchant\Models\MerchantPageModel;
use App\Modules\Frontend\Models\UserGuestModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use App\Modules\Admin\Models\MasterPaymentMethodModel;
use App\Modules\Merchant\Models\MerchantPaymentMethodModel;

use Illuminate\Support\Facades\Storage;

class MerchantController extends Controller
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
        print_r($_POST) ;
        exit;

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
                $user->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $user->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

                if($user->save()){
                    DB::table('user_has_roles')->insert(['role_id'=>'4','user_id'=>$user->id]);
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

        $mid = Crypt::decrypt($id);

        Globe::dataisaccessible('merchant',$mid);

        $merchant = MerchantModel::findOrFail($mid);

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
                $user->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $user->save();

            }

            DB::commit();
            return Redirect('merchant/'.Crypt::encrypt($id))->with('flash_success', 'Merchant '.$request->input('merchant_name').' successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/'.Crypt::encrypt($id))->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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

                if($data->delete()):
                    MerchantEmailModel::where('merchant_id',$id)->delete();
                    MerchantMobileModel::where('merchant_id',$id)->delete();
                    MerchantScheduleDayModel::where('merchant_id',$id)->delete();
                    MerchantConfigurationModel::where('merchant_id',$id)->delete();
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

    public function getbuttonpackage($id){

        $html = '';

        $merchant = MerchantModel::findOrFail($id);
        $subpackage = MasterMerchantSubPackageModel::where('merchant_subpackage_status','1')->get();

        $merchantpkg = [];
        foreach($merchant->package->subpackage as $sub){
            $merchantpkg[] += $sub->merchant_subpackage_id;
        }

        if(count($subpackage)>0){

            foreach($subpackage as $sp){

                $html .= '<div class="input-group"><div class="input-group-btn"><a class="btn btn-default">';

                if(in_array($sp->merchant_subpackage_id,$merchantpkg)){

                    $html .= '<i class="fa fa-check"></i> ';

                }else{

                    $html .= '<i class="fa fa-window-minimize"></i> ';

                }

                $html .= '</a></div><input class="form-control" type="text" value="'.$sp->merchant_subpackage_desc.'" disabled></div>';

            }

        }

        return json_encode($html);

    }

    public function getbutton($id){

        $html = '';
        $divstart = '<div class="input-group"><div class="input-group-btn"><a data-toggle="tooltip" ';
        $divmid = '</i></a></div><input class="form-control" type="text" value="';
        $divend = '" disabled></div>';

        $merchant = MerchantModel::findOrFail($id);

        $html .= $divstart.'data-id="'.$merchant->merchant_id.'" href="'.route('merchant.show',Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-info"><i class="fa fa-info-circle">'.$divmid.__('Merchant::merchant.detail').$divend;
        
        if($merchant->user->status_approve == 1){

            if(Auth::user()->can('merchant.branch.index')){

                $html .= $divstart.'data-id="'.$merchant->merchant_id.'" href="'.route('merchant.branch.index',Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-info"><i class="fa fa-info-circle">'.$divmid.__('Merchant::branch.branch').$divend;

            }

            $html .= $divstart.'data-id="'.$merchant->merchant_id.'" href="'.route('merchant.members',Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-info"><i class="fa fa-info-circle">'.$divmid.__('Merchant::merchant.websitemember').$divend;

            if($merchant->user->status == 0){
                if(Auth::user()->can('user.enable')){
                    $html .= $divstart.'data-askmsg="'.__('Admin::base.askenable').'" class="btn btn-success enabledata" value="'.route('user.enable', $merchant->user->id).'"><i class="fa fa-info-circle">'.$divmid.__('Admin::user.enable').$divend;
                }
            }

        }else{

            if(Auth::user()->can('user.approve')){
                $html .= $divstart.'data-askmsg="'.__('Admin::base.askapprove').'" class="btn btn-success enabledata" value="'.route('user.approve', $merchant->user->id).'"><i class="fa fa-thumbs-up">'.$divmid.__('Admin::base.approve').$divend;
            }

        }

        if($merchant->user->status == 1){

            if(Auth::user()->can('user.disable')){
                $html .= $divstart.'data-askmsg="'.__('Admin::base.askdisable').'" class="btn btn-warning enabledata" value="'.route('user.disable', $merchant->user->id).'"><i class="fa fa-minus-circle">'.$divmid.__('Admin::user.disable').$divend;
            }
            if(Auth::user()->can('merchant.showconfig')){
                $html .= $divstart.'href="'.route('merchant.showconfig',Crypt::encrypt($merchant->configuration->merchant_config_id)).'" class="btn btn-primary"><i class="fa fa-wrench">'.$divmid.__('Merchant::merchant.ecommerceconf').$divend;
            }
            if(Auth::user()->can('merchant.pageindex')){
                $html .= $divstart.'href="'.route('merchant.pageindex',Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-primary"><i class="fa fa-wrench">'.$divmid.__('Merchant::merchant.ecommercepage').$divend;
            }
            if(Auth::user()->can('product.type.index')){
                $html .= $divstart.'href="'.route('merchant.supplier.index',Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-success"><i class="fa fa-tasks">'.$divmid.__('Merchant::supplier.supplier').$divend;
            }
            if(Auth::user()->can('product.type.index')){
                $html .= $divstart.'href="'.route('product.type.index',Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-success"><i class="fa fa-tasks">'.$divmid.__('Product::product.producttype').$divend;
            }
            if(Auth::user()->can('product.attribute.index')){
                $html .= $divstart.'href="'.route('product.attribute.index',Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-success"><i class="fa fa-tasks">'.$divmid.__('Product::product.productvariant').$divend;
            }
            if(Auth::user()->can('product.index')){
                $html .= $divstart.'href="'.URL::to('product/'.Crypt::encrypt($merchant->merchant_id).'/index').'" class="btn btn-success"><i class="fa fa-tasks">'.$divmid.__('Product::product.product').$divend;
            }
            //if(Auth::user()->can('product.productmovement')){
                $html .= $divstart.'href="'.URL::to('product/productmovement/'.Crypt::encrypt($merchant->merchant_id)).'" class="btn btn-success"><i class="fa fa-tasks">'.$divmid.__('Product::product.stockmovement').$divend;
            //}

        }

        if($merchant->user->status_approve=='0'){

            if(Auth::user()->can('merchant.delete')){
                $html .= $divstart.'class="btn btn-danger deletedata" value="'.route('merchant.delete',$merchant->merchant_id).'"><i class="fa fa-times-circle">'.$divmid.__('Admin::base.delete').$divend;
            }

        }

        return json_encode($html);

    }

    public function members($id){

        // if( !Auth::user()->can('merchant.members') ):
        //     abort(403);
        // endif;

        $mid = Crypt::decrypt($id);

        $query = UserGuestModel::where('merchant_id',$mid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        $types = $query->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));

        return view("Merchant::memberindex", ['pagetitle'=>__('Merchant::merchant.websitemember'), 'pagedesc'=>__('Admin::base.list'), 'types'=>$types, 'merchant_id'=>$mid]);

    }

    public function changememberpassword(Request $request){

        /*if( !Auth::user()->can('merchant.setdomain') ):
            abort(403);
        endif;*/

        DB::beginTransaction();

        try {

            $input = [
                'password'=>$request->input('password'),
                'confirmation_password'=>$request->input('password-confirm'),
            ];

            $rules = [
                'password'=>'required',
                'confirmation_password'=>'required',
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

            if($request->input('password') != $request->input('password-confirm')){
                throw new exception(__('Admin::user.passwordnotmatch'));
            }

            UserGuestModel::where('guest_id',$request->input('guest_id'))->update(['password'=>bcrypt($request->input('password'))]);

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Password successfully changed!');
            
        } catch (Exception $error) {

            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal1'=>true ]);
            
        }

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

    public function showconfig($id)
    {

        $selected = 0;

        if( !Auth::user()->can('merchant.showconfig') ):
            abort(403);
        endif;

        $mcid = Crypt::decrypt($id);

        Globe::dataisaccessible('merchant_configuration',$mcid);

        $merchant = MerchantConfigurationModel::findOrFail($mcid);

        $directPaymentMethods = MasterPaymentMethodModel::find(1); // style ni aku tak gemar
        $paymentMethods = MasterPaymentMethodModel::where('master_payment_method.payment_method_id','!=', 1)->get();

        $selectedPaymentGateway = MerchantPaymentMethodModel::where('payment_method_id', '!=', 1)->where('merchant_id', $merchant->merchant->merchant_id)->where('merchant_payment_method_status', 1)->first();
        if(!empty($selectedPaymentGateway)):
            $selected = $selectedPaymentGateway->payment_method_id;
        endif;


        return view("Merchant::createmerchantconfig", ['pagetitle'=>__('Merchant::merchant.ecommerceconf'), 'pagedesc'=>__('Merchant::merchant.merchant'), 'merchant'=>$merchant,'directPay'=>$directPaymentMethods, 'paymentMethods' => $paymentMethods, 'selected'=>$selected ]);

    }

    public function editconfig(Request $request)
    {
        if( !Auth::user()->can('merchant.editconfig') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'address'=>$request->input('merchant_config_address1'),
                'postcode'=>$request->input('merchant_config_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'mobile_number'=>$request->input('merchant_config_mobileno'),
            ];

            $rules = [
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'mobile_number'=>'required|numeric',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
                //'numeric' => __('Admin::base.postcodenumericonly'),
                'mobile_number.numeric' => __('Admin::user.numericonly'),
                'postcode.numeric' => __('Admin::user.numericonly'),
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

            $merchantconfigid = $request->input('merchant_config_id');
            $merchantid = $request->input('merchant_id');
            
            $data = MerchantConfigurationModel::updateData($merchantconfigid,$request);

            if($merchantconfigid!=''){

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

                //uploadfile
                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/merchant/';
                $modelname = 'MerchantModel';
                $uploadpath = '/uploads/merchant/';

                if($request->hasfile('img_logo')) {

                    if(!empty($data->logo)){
                        unlink(public_path($data->logo->upload_path.$data->logo->upload_filename));
                        UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','1')->delete();
                    }
                    
                    $file = $request->file('img_logo');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','1')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_logo.'.$ext;
                    $file->move($path, $name);

                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'1');

                }

                if($request->hasfile('img_flyer')) {

                    if(!empty($data->flyer)){
                        unlink(public_path($data->flyer->upload_path.$data->flyer->upload_filename));
                        UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','2')->delete();
                    }
                    
                    $file = $request->file('img_flyer');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','2')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_flyer.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'2');

                }

                if($request->hasfile('img_storefront')) {

                    if(!empty($data->storefront)){
                        unlink(public_path($data->storefront->upload_path.$data->storefront->upload_filename));
                        UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','3')->delete();
                    }
                    
                    $file = $request->file('img_storefront');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','3')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_storefront.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'3');

                }

                if($request->hasfile('img_background')) {

                    if(!empty($data->background)){
                        unlink(public_path($data->background->upload_path.$data->background->upload_filename));
                        UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','4')->delete();
                    }
                    
                    $file = $request->file('img_background');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','4')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_background.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'4');

                }

                if($request->hasfile('img_banner')) {

                    if(!empty($data->banner)){
                        unlink(public_path($data->banner->upload_path.$data->banner->upload_filename));
                        UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','5')->delete();
                    }
                    
                    $file = $request->file('img_banner');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$merchantid)->where('upload_model',$modelname)->where('upload_type','5')->delete();

                    $name = $merchantid.'_'.date('YmdHis').'_banner.'.$ext;
                    $file->move($path, $name);
                    
                    UploadModel::insertData($modelname,$merchantid,$uploadpath,$name,'5');

                }


                // save direct payment
                $directPay = MerchantPaymentMethodModel::findOrNew($request->input('directpayment'));
                $directPay->merchant_id = $merchantid;
                $directPay->payment_method_id = $request->input('directpayment');
                $directPay->merchant_payment_method_status = 1;
                $directPay->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $directPay->save();

            }

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Configuration successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

    public function pageindex($id)
    {

        if( !Auth::user()->can('merchant.pageindex') ):
            abort(403);
        endif;

        $mid = Crypt::decrypt($id);

        //Globe::dataisaccessible('merchant_configuration',$id);

        $query = MerchantPageModel::where('merchant_id',$mid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        $pages = $query->orderBy('updated_at','desc')->paginate(Config::get('constants.common.paginate'));

        return view("Merchant::pageindex", ['pagetitle'=>__('Merchant::merchant.websitepage'), 'pagedesc'=>__('Admin::base.list'), 'types'=>$pages, 'merchantid'=>$id]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createpage($id)
    {
        
        if( !Auth::user()->can('merchant.createpage') ):
            abort(403);
        endif;

        $page = new MerchantPageModel;

        return view("Merchant::createpage", ['pagetitle'=>__('Merchant::merchant.newpage'), 'pagedesc'=>__('Merchant::merchant.website'), 'page'=>$page, 'merchantid'=>$id]);

    }

    public function updatepage(Request $request)
    {
        if( !Auth::user()->can('merchant.editpage') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'title'=>$request->input('merchant_page_title'),
                'content'=>$request->input('merchant_page_content'),
                'order'=>$request->input('merchant_page_order'),
            ];

            $rules = [
                'title'=>'required',
                'content'=>'required',
                'order'=>'required|numeric',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'order.numeric' => __('Admin::user.numericonly'),
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

            if($request->input('merchant_page_id') != ''):
                $pageid = MerchantPageModel::updateData($request->input('merchant_page_id'),$request);
                $msg = 'updated';
            else:
                $pageid = MerchantPageModel::insertData($request);
                $msg = 'created';
            endif;


            DB::commit();
            return Redirect('merchant/showpage/'.Crypt::encrypt($pageid))->with('flash_success', 'Data successfully '.$msg.'!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

    public function showpage($id)
    {
        
        if( !Auth::user()->can('merchant.showpage') ):
            abort(403);
        endif;

        $pid = Crypt::decrypt($id);

        Globe::dataisaccessible('merchant_page',$pid);

        $page = MerchantPageModel::findOrFail($pid);

        return view("Merchant::createpage", ['pagetitle'=>__('Merchant::merchant.newpage'), 'pagedesc'=>__('Merchant::merchant.website'), 'page'=>$page]);

    }

    public function destroypage($id)
    {   
        if( !Auth::user()->can('merchant.deletepage') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                MerchantPageModel::findOrFail($id)->delete();
                
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

    public function disablepage($id) {

        if( !Auth::user()->can('merchant.disablepage') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MerchantPageModel::where('merchant_page_id',$id)->update(['merchant_page_status'=>0]);
                
            else:
                throw new Exception("You didn't select any data to disable");               
            endif;

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Data(s) has been unpublished!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enablepage($id) {
        
        if( !Auth::user()->can('merchant.enablepage') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MerchantPageModel::where('merchant_page_id',$id)->update(['merchant_page_status'=>1]);
                
            else:
                throw new Exception("You didn't select any data to enable");                
            endif;

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Data(s) has been published!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

    public function setPaymentGateway() {
        
        DB::beginTransaction();
        try {

            // update payment kepada 0 terlebih dahulu sebelum update payment yang selected
            MerchantPaymentMethodModel::where('payment_method_id','!=', 1)->where('merchant_id', Request('mid'))->update(['merchant_payment_method_status'=>0]);

            $paymentgateway = MerchantPaymentMethodModel::where('merchant_id', Request('mid'))
                              ->where('payment_method_id', Request('gid'))->first();
            if($paymentgateway) {
                $paymentgateway->merchant_payment_method_status = 1;
                $paymentgateway->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $paymentgateway->save();
            } else {

                $newpaymentgateway = new MerchantPaymentMethodModel;
                $newpaymentgateway->merchant_id = Request('mid');
                $newpaymentgateway->payment_method_id = Request('gid');
                $newpaymentgateway->merchant_payment_method_status = 1;
                $newpaymentgateway->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $newpaymentgateway->save();
            }
            
            DB::commit();
            return response()->json(['status' => 'OK']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }
    }

    public function paymentGatewayParams() {
        
        $html = '';
        $paymentGateway = MasterPaymentMethodModel::where('payment_method_id', Request('pid'))->first();
        $merchantconfig = MerchantPaymentMethodModel::where( 'payment_method_id', Request('pid') )
                          ->where('merchant_id', Request('mid'))->first();

        if(!empty($paymentGateway->payment_method_param)) {

            $params = explode('|', $paymentGateway->payment_method_param);

            foreach ($params as $param) {

                // get the values from metadata
                $values = !empty($merchantconfig->merchant_payment_method_meta) ? Globe::readMeta($merchantconfig->merchant_payment_method_meta, strtolower($param)) : '';

                $html .= '<div class="form-group"><label class="col-sm-3 control-label">'.$param.'</label><div class="col-sm-9"><input class="form-control" name="'.strtolower($param).'" value="'.$values.'" /></div></div>';
            }
        }

        return response()->json(['html' => $html, 'title'=>$paymentGateway->payment_method_description]);

    }

    public function savePaymentParameters() {
        
        DB::beginTransaction();

        $requests = serialize( \Request()->except('modal_merchant_id', 'modal_merchant_payment_method_id', 'modal_merchant_config_id', '_token') );

        try {

            $paymentgateway = MerchantPaymentMethodModel::where('payment_method_id','!=', 1)
                              ->where('merchant_id', Request('modal_merchant_id'))
                              ->where('payment_method_id', Request('modal_merchant_payment_method_id'))
                              ->first();

            if($paymentgateway) {
                $paymentgateway->merchant_payment_method_meta = $requests;
                $paymentgateway->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $paymentgateway->save();
            } else {

                $newpaymentgateway = new MerchantPaymentMethodModel;
                $newpaymentgateway->merchant_id = Request('modal_merchant_id');
                $newpaymentgateway->payment_method_id = Request('modal_merchant_payment_method_id');
                $newpaymentgateway->merchant_payment_method_meta = $requests;
                $newpaymentgateway->merchant_payment_method_status = 1;
                $newpaymentgateway->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $newpaymentgateway->save();
            }
            
            DB::commit();
            return Redirect()->route('merchant.showconfig', [encrypt(Request('modal_merchant_config_id'))])->withInput()->with('flash_success', "Configuration saved!");

        } catch (Exception $e) {
            DB::rollback();
            return Redirect()->route('merchant.showconfig', [encrypt(Request('modal_merchant_config_id'))])->withInput()->with('flash_error', "Unable to save configuration");
        }
    }

}
