<?php 

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\Admin\Models\UserModel;
use App\User;
use DB;
use Redirect;
use Session;
use Exception;
use Config;
use Input;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller {


	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware(['auth', 'isAdmin']);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$query = Permission::query();

		if(Input::has('search') && Input::has('keyword')) {
			$query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');
		}

		$permissions = $query->orderBy('name','asc')->orderBy('created_at','asc')->paginate(Config::get('constants.common.paginate'));
		return view("Admin::permissionindex", ['pagetitle'=>__('Admin::permission.permission'), 'pagedesc'=>__('Admin::base.list'), 'permissions'=> $permissions]);

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
				'description'=>$request->input('description'),
				'namespace'=>$request->input('namespace'),
				'controller'=>$request->input('controller'),
				'function'=>$request->input('function'),
				'created_at'=>Carbon::now(Config::get('constants.common.systemtimezone')),
				'updated_at'=>Carbon::now(Config::get('constants.common.systemtimezone'))
			];

			$rules = [
	            'name'=>'required|max:50',
	            'description'=>'required',
	            'namespace'=>'required',
	            'controller'=>'required',
	            'function'=>'required',
	        ];

			$messages = [
				'name' => __('Admin::user.required'),
				'description' => __('Admin::user.required'),
				'namespace' => __('Admin::user.required'),
				'controller' => __('Admin::user.required'),
				'function' => __('Admin::user.required'),
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

	        /*Permission::insert(['name'=>$request->input('name'), 
                            'description'=>$request->input('description'),
                            'created_at'=>Carbon::now(Config::get('constants.common.systemtimezone')),
                            'updated_at'=>Carbon::now(Config::get('constants.common.systemtimezone'))]);*/

	        Permission::insert($input);

			DB::commit();
			return Redirect('admin/permission')->with('flash_success', 'Data successfully created!');
			
		} catch (Exception $error) {
			DB::rollback();
			return Redirect('admin/permission')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
			
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
		dd("ERROR HERE");
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
	public function update($id, Request $request)
	{
		DB::beginTransaction();

		try {		

	        $permission = Permission::find($id);
	        $permission->name = $request->name; // akan ada error bila nama tidak berubah
	        $permission->description = $request->description;
	        $permission->namespace = $request->namespace;
	        $permission->controller = $request->controller;
	        $permission->function = $request->function;
	        $permission->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
	        $permission->save();

			DB::commit();
			return Redirect('admin/permission')->with('flash_success', 'Data successfully updated!');
			
		} catch (Exception $error) {
			DB::rollback();
			return Redirect('admin/permission')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
			
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($uid)
	{
		DB::beginTransaction();
		try {

			if(!empty($uid)):

				$user = new Permission;
				$user->deleteMaster($uid);
				
			else:
				throw new Exception("You didn't select any data to delete");				
			endif;

			DB::commit();
			// return Redirect( Request::header('referer') )->with('flash_success', 'User(s) has been deleted!');
			return Redirect()->back()->with('flash_success', 'Data(s) has been deleted!');
			
		} catch (Exception $e) {
			DB::rollback();
			return Redirect::back()->withInput()->with('flash_error', $e->getMessage());
		}
	}

	public static function getRolePermission($id){

        //$permission = Permission::orderBy('name')->get();
        $permission = Permission::get();

        $role = Role::findOrFail($id);

        $rolehaspermissions = DB::table('role_has_permissions')->where('role_id',$id)->pluck('permission_id')->toArray();

        $html = '<div class="checkbox"><label><input type="checkbox" onclick="toggleCheck(this)" name="checkall" class="checkall">All</label></div>';
        $checked = '';
        $width = 40;
        $i = 0;
        foreach($permission as $perm){

        	$i++;
	        if(in_array($perm->id,$rolehaspermissions)){
	        	$checked = 'checked';
	        }else{
	        	$checked = '';
	        }

			if ($i % 40 == 0){
				$width += 15;
				$html .= '</div><div class="col-md-3 col-sm-3 col-xs-12">';
			}

	        $html .= '<div class="checkbox"><label><input type="checkbox" name="permission[]" value="'.$perm->id.'" '.$checked.'>'.$perm->description.'</label></div>';

    	}

    	if($i>39){
    		$collabel  = '2';
    		$collabel2 = '3';
    	}else{
    		$collabel = '3';
    		$collabel2 = '7';
    	}

    	$formgroup1 = '<div class="form-group"><input type="hidden" name="roleidmodal2" value="'.$id.'"><input type="hidden" name="rolenamemodal2" value="'.strtoupper($role->name).'"><label class="control-label col-md-'.$collabel.' col-sm-'.$collabel.' col-xs-12">'.__('Admin::role.rolename').'</label><div class="col-md-'.$collabel2.' col-sm-'.$collabel2.' col-xs-12"><label class="control-label">'.strtoupper($role->name).'</label></div></div>';

		$formgroup2 = '<div class="form-group"><div class="form-group"><label class="control-label col-md-'.$collabel.' col-sm-'.$collabel.' col-xs-12">'.__('Admin::permission.permission').'</label><div class="col-md-'.$collabel2.' col-sm-'.$collabel2.' col-xs-12">';

    	$html .= '</div></div>';

    	$html2 = $formgroup1.$formgroup2.$html;

        $data = ['html'=>$html2, 'width'=>$width];

        return json_encode($data);

    }

    public static function getUserPermission($id){

        $permission = Permission::get();

        $userhaspermissions = DB::table('user_has_permissions')->where('user_id',$id)->pluck('permission_id');

        $data = ['permission'=>$permission, 'userhaspermissions'=>$userhaspermissions];

        return json_encode($data);

    }

    public static function getPermission($id){

    	return json_encode(Permission::get());

    }

}
