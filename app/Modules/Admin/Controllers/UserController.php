<?php 

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\User;
use DB;
use Redirect;
use Session;
use Exception;
use Config;
use Input;
use Validator;
use Auth;
use App;
use Globe;
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Admin\Models\UploadModel;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Admin\Models\MasterSalutationModel;
use App\Modules\Admin\Models\UsersDetailModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {


	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:user.index']);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// $roles = Role::pluck('name','id')->toArray();
		$roles = Role::where('name','!=','agent')->get();
		$query = User::query();
		//$query = UserModel::with('roles');

		if(Input::has('search') && Input::has('keyword')) {

			$query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

		}

		//$query->role(['admin','agent','merchant']);
		$query->role(['admin']);

		$users = $query->where('name','!=','superadmin')->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
		return view("Admin::userindex", ['pagetitle'=>__('Admin::user.users'), 'pagedesc'=>__('Admin::base.list'), 'users'=> $users, 'roles'=>$roles]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// $roles = Role::get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		DB::beginTransaction();

		try {

			$input = [
				'name'=>$request->input('name'),
				'email'=>$request->input('email'),
				'username'=>$request->input('username'),
				'password'=>$request->input('password'),
			];

			$rules = [
	            'name'=>'required|max:120',
	            'email'=>'required|email|unique:users',
	            'password'=>'required|min:6',
	            'username'=>'required|min:4|unique:users'
	        ];

			$messages = [
				'required' => __('Admin::user.required'),
				'email' => __('Admin::user.emailvalid'),
				'email.unique' => __('Admin::user.emailusernameunique'),
				'password.min' => __('Admin::user.passwordmin'),
				'username.min' => __('Admin::user.usernamemin'),
				'username.unique' => __('Admin::user.emailusernameunique'),
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

	        $user = User::create($request->only('name', 'email', 'password','username'));

			$roles = $request['roles']; //Retrieving the roles field

		    //Checking if a role was selected
	        if (isset($roles)) {

	            foreach ($roles as $role) {
	            	$role_r = Role::where('id', '=', $role)->firstOrFail();            
	            	$user->assignRole($role_r); //Assigning role to user
	            }
	        }

			DB::commit();
			return Redirect('user')->with('flash_success', 'User successfully created');
			
		} catch (Exception $error) {
			DB::rollback();
			return Redirect('user')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
			
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return view("auth.userprofile", ['pagetitle'=>__('Admin::user.profile'), 'pagedesc'=> Auth::user()->name ]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($uid)
	{
		if( !Auth::user()->can('user.delete') ):
			abort(403);
		endif;

		DB::beginTransaction();
		try {

			if(!empty($uid)):
					
				$user = new UserModel;
				$user->deleteUser($uid);
				
			else:
				throw new Exception("You didn't select any user to delete");				
			endif;

			DB::commit();
			// return Redirect( Request::header('referer') )->with('flash_success', 'User(s) has been deleted!');
			return Redirect()->back()->with('flash_success', 'User(s) has been deleted!');
			
		} catch (Exception $e) {
			DB::rollback();
			return Redirect::back()->withInput()->with('flash_error', $e->getMessage());
		}
	}


	public function processaction(Request $request) {

		try {

			if( empty($request->useraction) ):
				throw new Exception("Please select action!");
			endif;
			
			if(! $request->uid ):
				throw new Exception("Please select user to perform an action!");				
			endif;

			

			switch ($request->useraction) {
				case 'disable':
					foreach ($request->uid as $uid) :
						self::disableUser($uid);
					endforeach;

					return Redirect('user')->with('flash_success', 'User(s) has been disabled!');
				break;

				case 'enable':
					foreach ($request->uid as $uid) :
						self::enableUser($uid);
					endforeach;

					return Redirect('user')->with('flash_success', 'User(s) has been activated!');
				break;
				
				case 'delete':
					foreach ($request->uid as $uid) :
						self::destroy($uid);
					endforeach;

					return Redirect('user')->with('flash_success', 'User(s) has been deleted!');
				break;
			}

			
			
		} catch (Exception $e) {
			return Redirect::back()->withInput()->with('flash_error', $e->getMessage());
		}
		

	}

	public function disableUser($uid) {

		if( !Auth::user()->can('user.disable') ):
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
			return Redirect()->back()->with('flash_success', 'User(s) has been disabled!');

		} catch (Exception $error) {
			DB::rollback();
			return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
		}
	}


	public function enableUser($uid) {
		
		if( !Auth::user()->can('user.enable') ):
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
			return Redirect()->back()->with('flash_success', 'User(s) has been activated!');

		} catch (Exception $error) {
			DB::rollback();
			return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
		}		
	}

	public function approveUser($uid) {

		if( !Auth::user()->can('user.approve') ):
			abort(403);
		endif;
		
		DB::beginTransaction();
		try {
			
			if(!empty($uid)):
					
				$user = UserModel::where('id',$uid)->update(['status_approve'=>1,'status'=>1]);

				$userdata = UserModel::findOrFail($uid);

				if($userdata->merchant_id!=''):

					$count = MerchantModel::whereNotNull('merchant_domain')->count();
					$newcount = $count+1;
					//dd($count);

					//if($userdata->merchant->merchant_domain!=''):
						MerchantModel::where('merchant_id',$userdata->merchant_id)->update(['merchant_status'=>'1','merchant_domain'=>'cloud'.$userdata->merchant_id.'.gohjielong.com']);
					//else:
						//throw new Exception(__('Merchant::merchant.msgdomainsetfirst')); 
					//endif;

					if(Config::get('constants.common.cpanelsubdomain') == 1){
						$cpanel = App::make('cpanel');
				        $cpanel->setHost('gohjielong.com');
				        $cpanel->setAuth('gohjielo', '1q2w3e4r!@#$');
				        $cpanel->createSubdomain('cloud'.$userdata->merchant_id, 'gohjielo', '/cloud/public', 'gohjielong.com');
				    }

				endif;
				
			else:
				throw new Exception("You didn't select any user to be approved");				
			endif;

			DB::commit();
			return Redirect()->back()->with('flash_success', 'User(s) has been approved!');

		} catch (Exception $error) {
			DB::rollback();
			return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
		}
	}

	public function getUserInfo($id){

        //$user = UserModel::findOrFail($id);
        $user = UserModel::leftJoin('users_detail','user_id','=','id')
        					->leftJoin('master_salutation','users_detail.salutation_id','=','master_salutation.salutation_id')
        					//->leftJoin('master_country','users_detail.country_id','=','master_country.country_id')
        					//->leftJoin('master_gender','users_detail.gender_id','=','master_gender.gender_id')
        					->leftJoin('master_state','users_detail.state_id','=','master_state.state_id')
        					->leftJoin('master_district','users_detail.district_id','=','master_district.district_id')
        					->leftJoin('upload', function($join){
							   		$join->on('users.id', '=', 'upload.upload_model_id'); 
							   		$join->where('upload.upload_model', '=', 'UserModel');
							   	})
        					->where('users.id',$id)->first();

        return json_encode($user);

    }

    public static function getUserRole($id){

        $role = Role::get();

        $userhasroles = DB::table('user_has_roles')->where('user_id',$id)->pluck('role_id');

        $data = ['role'=>$role, 'userhasroles'=>$userhasroles];

        return json_encode($data);

    }

    public function storepermission(Request $request)
    {
        DB::beginTransaction();

        try {

            $id = $request->input('useridmodal2');
            $desc = $request->input('usernamemodal2');
            $list = $request->input('permission');

            DB::table('user_has_permissions')->where('user_id',$id)->delete();
            
            for($i=0;$i<count($list);$i++){

                DB::table('user_has_permissions')->insert(['permission_id'=>$list[$i], 'user_id'=>$id]); 

            }

            DB::commit();
            return Redirect('user')->with('flash_success', 'Permission for User '.$desc.' has been updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('user')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }
    }

    public function createagent(){

        $agent = new UserModel;

        $salutations = MasterSalutationModel::get();
        $states = MasterStateModel::get();

        return view('Admin::createstaff');

    }

    public function showagent($id){

    	$agent = UserModel::findOrFail(Crypt::decrypt($id));

        $salutations = MasterSalutationModel::get();
        $states = MasterStateModel::get();

        return view('Admin::createuser', ['pagetitle'=>__('Admin::user.agent'), 'pagedesc'=>__('Admin::user.userdetail'), 'salutations'=>$salutations, 'states'=>$states, 'agent'=>$agent]);
    	
    }

    public function storeagent(Request $request)
    {
        DB::beginTransaction();

        try {

            $input = [
                'salutation'=>$request->input('salutation_id'),
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'password'=>$request->input('password'),
                'identification_no'=>$request->input('users_detail_idno'),
                'address'=>$request->input('users_detail_address1'),
                'postcode'=>$request->input('users_detail_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'contact_no'=>$request->input('users_detail_mobileno'),
            ];

            $rules = [
                'salutation'=>'required',
                'name'=>'required|max:100',
                //'email'=>'required|string|email|max:100|unique:users',
                'email'=>'required|string|email|max:100',
                //'password' => 'required|string|min:6|confirmed',
                'password' => 'required|string|min:6',
                'identification_no'=>'required|numeric',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'contact_no'=>'required|numeric',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
                //'numeric' => __('Admin::base.postcodenumericonly'),
                'contact_no.numeric' => __('Admin::user.numericonly'),
                'identification_no.numeric' => __('Admin::user.numericonly'),
                'postcode.numeric' => __('Admin::user.numericonly'),
                'password.min' => __('Admin::user.passwordmin'),
                'email.unique' => __('Admin::user.emailusernameunique'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                if ( UserModel::checkDataExist('email', $request->input('email'), '') ) {
                    $validator->errors()->add('emailexist', __('Admin::user.emailexists'));
                }

                // if ( UserModel::checkDataExist('username', $request->input('username'), '') ) {
                //     $validator->errors()->add('usernameexist', __('Admin::user.usernameexists'));
                // }

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

            $user = new UserModel;
            $user->name = strtoupper($request->input('name'));
            $user->email = strtolower($request->input('email'));
            $user->password = bcrypt($request->input('password'));
            $user->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            $user->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

            if($user->save()){
                $detail = new UsersDetailModel;
                $detail->user_id = $user->id;
                $detail->users_detail_name = strtoupper($request->input('name'));
                $detail->users_detail_idno = $request->input('users_detail_idno');
                $detail->users_detail_email = strtolower($request->input('email'));
                $detail->users_detail_address1 = strtoupper($request->input('users_detail_address1'));
                $detail->users_detail_address2 = strtoupper($request->input('users_detail_address2'));
                $detail->users_detail_address3 = strtoupper($request->input('users_detail_address3'));
                $detail->users_detail_postcode = $request->input('users_detail_postcode');
                $detail->district_id = $request->input('district_id');
                $detail->state_id = $request->input('state_id');
                $detail->users_detail_mobileno = $request->input('users_detail_mobileno');
                $detail->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $detail->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

                if($detail->save()){
                    DB::table('user_has_roles')->insert(['role_id'=>'3','user_id'=>$user->id]);
                }
            }

            //uploadfile
            if($request->hasfile('personalphoto')) {

                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/users/';
                foreach($request->file('personalphoto') as $file){
                    //$name=$file->getClientOriginalName();
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $user->id.'_'.date('YmdHis').'.'.$ext;
                    $file->move($path, $name); 
                    $upload = new UploadModel;
                    $upload->upload_model = 'UserModel';
                    $upload->upload_model_id = $user->id;
                    $upload->upload_path = '/uploads/users/';
                    $upload->upload_filename = $name;
                    $upload->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $upload->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $upload->save();  
                }
            }

            DB::commit();
            return Redirect('user')->with('flash_success', 'Agent successfully registered.');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

    public function updateagent(Request $request)
    {
        DB::beginTransaction();

        try {

        	$user = UserModel::findOrFail($request->input('userid'));

            $input = [
                'salutation'=>$request->input('salutation_id'),
                'name'=>$request->input('name'),
                'password'=>$request->input('password'),
                'identification_no'=>$request->input('users_detail_idno'),
                'address'=>$request->input('users_detail_address1'),
                'postcode'=>$request->input('users_detail_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'contact_no'=>$request->input('users_detail_mobileno'),
            ];

            $rules = [
                'salutation'=>'required',
                'name'=>'required|max:100',
                'identification_no'=>'required|numeric',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'contact_no'=>'required|numeric',
            ];

            if($user->email != strtolower($request->input('email'))){
                $input2 = ['email'=>strtolower($request->input('email'))];
                $input = array_merge($input,$input2);
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
                'contact_no.numeric' => __('Admin::user.numericonly'),
                'identification_no.numeric' => __('Admin::user.numericonly'),
                'postcode.numeric' => __('Admin::user.numericonly'),
                'password.min' => __('Admin::user.passwordmin'),
                'email.unique' => __('Admin::user.emailusernameunique'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                if ( UserModel::checkDataExist('email', $request->input('email'), $request->input('userid')) ) {
                    $validator->errors()->add('emailexist', __('Admin::user.emailexists'));
                }

                // if ( UserModel::checkDataExist('username', $request->input('username'), '') ) {
                //     $validator->errors()->add('usernameexist', __('Admin::user.usernameexists'));
                // }

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

            $user->name = strtoupper($request->input('name'));
            $user->email = strtolower($request->input('email'));
            if($request->input('password') != ''){
            	$user->password = bcrypt($request->input('password'));
        	}
            $user->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

            if($user->save()){
                $detail = UsersDetailModel::where('user_id',$user->id)->first();//findOrFail($user->id);
                $detail->users_detail_name = strtoupper($request->input('name'));
                $detail->users_detail_idno = $request->input('users_detail_idno');
                $detail->users_detail_email = strtolower($request->input('email'));
                $detail->users_detail_address1 = strtoupper($request->input('users_detail_address1'));
                $detail->users_detail_address2 = strtoupper($request->input('users_detail_address2'));
                $detail->users_detail_address3 = strtoupper($request->input('users_detail_address3'));
                $detail->users_detail_postcode = $request->input('users_detail_postcode');
                $detail->district_id = $request->input('district_id');
                $detail->state_id = $request->input('state_id');
                $detail->users_detail_mobileno = $request->input('users_detail_mobileno');
                $detail->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
				$detail->save();
            }

            //uploadfile
            if($request->hasfile('personalphoto')) {

            	$photo = UploadModel::where('upload_model','UserModel')->where('upload_model_id',$user->id)->first();

            	if(!empty($photo)){
            		unlink(public_path($photo->upload_path.$photo->upload_filename));
            		$photo->delete();
            	}

                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/users/';
                foreach($request->file('personalphoto') as $file){
                    //$name=$file->getClientOriginalName();
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $user->id.'_'.date('YmdHis').'.'.$ext;
                    $file->move($path, $name); 
                    $upload = new UploadModel;
                    $upload->upload_model = 'UserModel';
                    $upload->upload_model_id = $user->id;
                    $upload->upload_path = '/uploads/users/';
                    $upload->upload_filename = $name;
                    $upload->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $upload->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $upload->save();  
                }
            }

            DB::commit();
            return Redirect()->back()->with('flash_success', 'User successfully updated.');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

}
