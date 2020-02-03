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
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Merchant\Models\MerchantBranchModel;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Admin\Models\UploadModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
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
    public function index($merhantid)
    {

        if( !Auth::user()->can('merchant.branch.index') ):
            abort(403);
        endif;

        $mid = Crypt::decrypt($merhantid);

        $query = MerchantBranchModel::where('merchant_id',$mid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        /*if(Auth::user()->hasrole('merchant')){
            $query->where('merchant_id',Auth::user()->merchant_id);
        }

        if(Auth::user()->hasrole('branch')){
            $query->where('merchant_branch_id',Auth::user()->branch_id);
        }*/

        $branches = $query->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));

        //dd($branches->user);

        return view("Merchant::branchindex", ['pagetitle'=>__('Merchant::branch.branch'), 'pagedesc'=>__('Admin::base.list'), 'branches'=>$branches, 'merchantid'=>$merhantid]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($merchantid)
    {
        //Storage::append('apilog.txt', 'test1234');
        if( !Auth::user()->can('merchant.branch.create') ):
            abort(403);
        endif;

        $mid = Crypt::decrypt($merchantid);

        $branch = new MerchantBranchModel;

        /*if(Auth::user()->hasrole('merchant')){
            $merchants = MerchantModel::where('merchant_id',Auth::user()->merchant_id)->get();
        }elseif(Auth::user()->hasrole('agent')){
            $merchants = MerchantModel::get();
        }else{
            $merchants = MerchantModel::get();
        }*/

        //$merchants = MerchantModel::where('merchant_id',$mid)->get();
        $merchants = MerchantModel::findOrFail($mid);

        return view("Merchant::createbranch", ['pagetitle'=>__('Merchant::branch.addbranch'), 'pagedesc'=>__('Merchant::branch.branch'),'branch'=>$branch, 'merchants'=>$merchants]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if( !Auth::user()->can('merchant.branch.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'person_in_charge'=>$request->input('merchant_branch_person_incharge'),
                'email'=>strtolower($request->input('merchant_branch_email')),
                'username'=>$request->input('merchant_branch_username'),
                'password'=>$request->input('password'),
                //'branch_name'=>$request->input('merchant_branch_name'),
                'address'=>$request->input('merchant_branch_address1'),
                'postcode'=>$request->input('merchant_branch_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'mobile_number'=>$request->input('merchant_branch_mobileno'),
            ];

            $rules = [
                'person_in_charge'=>'required|max:100',
                //'email'=>'required|string|email|max:100|unique:users',
                'email'=>'required|string|email|max:100',
                'username'=>'required|max:100',
                'password' => 'required|string|min:6',
                //'branch_name'=>'required|max:100',
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
                'password.min' => __('Admin::user.passwordmin'),
                'company_email.unique' => __('Admin::user.emailusernameunique'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                if ( UserModel::checkDataExist('email', $request->input('merchant_branch_email'), '') ) {
                    $validator->errors()->add('emailexist', __('Admin::user.emailexists'));
                }

                if ( UserModel::checkDataExist('username', $request->input('merchant_branch_username'), '') ) {
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

            $newdata = MerchantBranchModel::insertData($request);

            if(!empty($newdata)){

                $user = new UserModel;
                $user->name = strtoupper($request->input('merchant_branch_person_incharge'));
                $user->email = strtolower($request->input('merchant_branch_email'));
                $user->username = $request->input('merchant_branch_username');
                $user->mobileno = $request->input('merchant_branch_mobileno');
                $user->password = bcrypt($request->input('password'));
                $user->merchant_id = $newdata['merchant_id'];
                $user->branch_id = $newdata['merchant_branch_id'];
                $user->created_at = Carbon::now();
                $user->updated_at = Carbon::now();

                if($user->save()){
                    DB::table('user_has_roles')->insert(['role_id'=>'5','user_id'=>$user->id]);
                }

                if($request->hasfile('img_storefront')) {

                    $validext = ['jpg','png','jpeg'];
                    $path = public_path().'/uploads/branch/';
                    $file = $request->file('img_storefront');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $newdata['merchant_branch_id'].'_'.date('YmdHis').'_logo.'.$ext;
                    $file->move($path, $name);

                    UploadModel::insertData('MerchantBranchModel',$newdata['merchant_branch_id'],'/uploads/branch/',$name);

                }

            }

            DB::commit();
            return Redirect('merchant/branch/'.Crypt::encrypt($request->input('merchant_id')).'/index')->with('flash_success', 'Merchant successfully registered!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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
        if( !Auth::user()->can('merchant.branch.show') ):
            abort(403);
        endif;

        $bid = Crypt::decrypt($id);

        Globe::dataisaccessible('merchant_branch',$bid);

        $branch = MerchantBranchModel::findOrFail($bid);

        /*if(Auth::user()->hasrole('merchant')){
            $merchants = MerchantModel::where('merchant_id',Auth::user()->merchant_id)->get();
        }elseif(Auth::user()->hasrole('agent')){
            $merchants = MerchantModel::get();
        }else{
            $merchants = MerchantModel::get();
        }*/

        $merchants = MerchantModel::where('merchant_id',$branch->merchant_id)->first();

        return view("Merchant::createbranch", ['pagetitle'=>__('Merchant::branch.updatebranch'), 'pagedesc'=>__('Merchant::branch.branch'),'branch'=>$branch, 'merchants'=>$merchants, 'disabled'=>'0']);

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
        if( !Auth::user()->can('merchant.branch.update') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $current = MerchantBranchModel::findOrFail($id);

            $input = [
                'person_in_charge'=>$request->input('merchant_branch_person_incharge'),
                'username'=>$request->input('merchant_branch_username'),
                //'branch_name'=>$request->input('merchant_branch_name'),
                'address'=>$request->input('merchant_branch_address1'),
                'postcode'=>$request->input('merchant_branch_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'mobile_number'=>$request->input('merchant_branch_mobileno'),
            ];

            $rules = [
                'person_in_charge'=>'required|max:100',
                'username'=>'required|max:100',
                //'branch_name'=>'required|max:100',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'mobile_number'=>'required|numeric',
            ];

            if($current->merchant_branch_email != strtolower($request->input('merchant_branch_email'))){
                $input2 = ['email'=>strtolower($request->input('merchant_branch_email'))];
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

            //dd($input);

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
                
                if ( UserModel::checkDataExist('email', $request->input('merchant_branch_email'), $current->user->id) ) {
                    $validator->errors()->add('emailexist', __('Admin::user.emailexists'));
                }

                if ( UserModel::checkDataExist('username', $request->input('merchant_branch_username'), $current->user->id) ) {
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

            $branchid = MerchantBranchModel::updateData($id,$request);

            if($branchid!=''){

                $user = UserModel::where('branch_id',$branchid)->first();
                $user->name = strtoupper($request->input('merchant_branch_person_incharge'));
                $user->email = strtolower($request->input('merchant_branch_email'));
                $user->username = $request->input('merchant_branch_username');
                $user->mobileno = $request->input('merchant_branch_mobileno');
                if($request->input('password') != ''){
                    $user->password = bcrypt($request->input('password'));
                }
                $user->updated_at = Carbon::now();
                $user->save();

                if($request->hasfile('img_storefront')) {
                    
                    $validext = ['jpg','png','jpeg'];
                    $path = public_path().'/uploads/branch/';
                    $file = $request->file('img_storefront');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$branchid)->where('upload_model','MerchantBranchModel')->delete();

                    $name = $branchid.'_'.date('YmdHis').'_logo.'.$ext;
                    $file->move($path, $name);

                    UploadModel::insertData('MerchantBranchModel',$branchid,'/uploads/branch/',$name);

                }

            }

            DB::commit();
            return Redirect('merchant/branch/'.$id)->with('flash_success', 'Merchant '.$request->input('merchant_name').' successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/branch/create')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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
        if( !Auth::user()->can('merchant.branch.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                UserModel::where('branch_id',$id)->delete();

                $data = new MerchantBranchModel;
                $data->deleteData($id);
                
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

    public function disable($uid) {

        if( !Auth::user()->can('merchant.branch.disable') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($uid)):
                    
                $user = new UserModel;
                $data = ['status'=>0];
                $user->updateUser($uid, $data);
                
            else:
                throw new Exception("You didn't select any user to disable");               
            endif;

            DB::commit();
            return Redirect('merchant/branch')->with('flash_success', 'User(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enable($uid) {
        
        if( !Auth::user()->can('merchant.branch.enable') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($uid)):
                    
                $user = new UserModel;
                $data = ['status'=>1];
                $user->updateUser($uid, $data);
                
            else:
                throw new Exception("You didn't select any user to enable");                
            endif;

            DB::commit();
            return Redirect('merchant/branch')->with('flash_success', 'User(s) has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

    public function approve($uid) {

        if( !Auth::user()->can('merchant.branch.approve') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($uid)):
                    
                $user = UserModel::where('id',$uid)->update(['status_approve'=>1,'status'=>1]);
                
            else:
                throw new Exception("You didn't select any user to be approved");               
            endif;

            DB::commit();
            return Redirect('merchant/branch')->with('flash_success', 'User(s) has been approved!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }

    public function showinfo()
    {
        if( !Auth::user()->can('merchant.branch.showinfo') ):
            abort(403);
        endif;

        /*$branch = MerchantBranchModel::findOrFail(Auth::user()->branch_id);

        if(Auth::user()->hasrole('merchant')){
            $merchants = MerchantModel::where('merchant_id',Auth::user()->merchant_id)->get();
        }elseif(Auth::user()->hasrole('agent')){
            $merchants = MerchantModel::get();
        }else{
            $merchants = MerchantModel::get();
        }

        return view("Merchant::createbranch", ['pagetitle'=>__('Merchant::branch.updatebranch'), 'pagedesc'=>__('Merchant::branch.branch'),'branch'=>$branch, 'merchants'=>$merchants]);*/

        return $this->show(Auth::user()->branch_id);

    }
}
