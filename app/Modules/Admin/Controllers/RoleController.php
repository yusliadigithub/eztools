<?php 

namespace App\Modules\Admin\Controllers;
use App\Http\Controllers\Controller;
use DB;
use Redirect;
use Session;
use Exception;
use Config;
use Input;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index() {
        $query = Role::query();//Get all roles

        if(Input::has('keyword')) {
            $query->where('name', 'LIKE', '%'.Input::get('keyword').'%');
        }

        $roles = $query->paginate(Config::get('constants.common.paginate'));

        return view('Admin::roleindex',['pagetitle'=>__('Admin::role.roles'), 'pagedesc'=>__('Admin::role.and_permission_management'), 'roles'=>$roles]);
    }

    public function create() {
    	
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
                'rolename'=>$request->input('rolename'),
                'description'=>$request->input('description'),
            ];

            $rules = [
                'rolename'=>'required|max:50',
                'description'=>'required|max:200',
            ];

            $messages = [
                'rolename' => __('Admin::user.required'),
                'description' => __('Admin::user.required'),
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

            Role::insert(['name'=>$request->input('rolename'), 
                            'description'=>$request->input('description'),
                            'created_at'=>Carbon::now(Config::get('constants.common.systemtimezone')),
                            'updated_at'=>Carbon::now(Config::get('constants.common.systemtimezone'))]);

            DB::commit();
            return Redirect('admin/roles')->with('flash_success', 'Data successfully created!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('admin/roles')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }
    }

    public function storepermission(Request $request)
    {
        DB::beginTransaction();

        try {

            $id = $request->input('roleidmodal2');
            $desc = $request->input('rolenamemodal2');
            $list = $request->input('permission');

            DB::table('role_has_permissions')->where('role_id',$id)->delete();

            if(!empty($list)){
                
                for($i=0;$i<count($list);$i++){

                    DB::table('role_has_permissions')->insert(['permission_id'=>$list[$i], 'role_id'=>$id]); 

                }

            }

            DB::commit();
            return Redirect('admin/roles')->with('flash_success', 'Permission for Role '.$desc.' has been updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('admin/roles')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }
    }

}


?>