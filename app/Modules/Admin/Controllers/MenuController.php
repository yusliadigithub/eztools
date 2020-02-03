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

use App\Modules\Admin\Models\MenuModel;

class MenuController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index() {

        $permissions = Permission::pluck('description','name')->toArray();

        $query = MenuModel::query();

        if(Input::has('keyword')) {
            $query->where('menu_name', 'LIKE', '%'.Input::get('keyword').'%');
        }

        $menus = $query->paginate(Config::get('constants.common.paginate'));
        
        return view('Admin::menuindex',['pagetitle'=>__('Admin::menu.menu'), 'pagedesc'=>'', 'menus'=>$menus, 'permissions'=>$permissions]);
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

            $menu = new MenuModel;
            $menu->menu_name = $request->input('menuname');
            $menu->menu_trans = $request->input('menutrans');
            $menu->menu_desc = $request->input('description');
            $menu->menu_sort = $request->input('menusort');
            $menu->menu_icon = $request->input('menuicon');
            $menu->menu_url = $request->input('menuurl');
            $menu->parent_id = $request->input('menuparent');
            $menu->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            $menu->save();

            DB::commit();
            return Redirect('admin/menus')->with('flash_success', 'Data successfully created!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('admin/menus')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }
    }


    public function update($menuid, Request $request) {

        DB::beginTransaction();
        try {
            
            $menu = MenuModel::find($menuid);
            $menu->menu_name = $request->input('menuname');
            $menu->menu_trans = $request->input('menutrans');
            $menu->menu_desc = $request->input('description');
            $menu->menu_sort = $request->input('menusort');
            $menu->menu_icon = $request->input('menuicon');
            $menu->menu_url = $request->input('menuurl');
            $menu->parent_id = $request->input('menuparent');
            $menu->save();

            DB::commit();
            return Redirect('admin/menus')->with('flash_success', 'Data successfully updated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect('admin/menus')->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }
        
    }

    public function destroy($menuid) {
        
        DB::beginTransaction();
        try {

            $menu = MenuModel::find($menuid);
            $menu->delete();

            DB::commit();
            return Redirect('admin/menus')->with('flash_success', 'Data successfully deleted!');
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('admin/menus')->with( [ 'flash_error' => $error->getMessage() ]);
        }
    }

    public function disable($id) {
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MenuModel::where('menu_id',$id)->update(['menu_status'=>0]);
                
            else:
                throw new Exception("You didn't select any menu to disable");               
            endif;

            DB::commit();
            return Redirect('admin/menus')->with('flash_success', 'Menu(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enable($id) {
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MenuModel::where('menu_id',$id)->update(['menu_status'=>1]);
                
            else:
                throw new Exception("You didn't select any menu to enable");                
            endif;

            DB::commit();
            return Redirect('admin/menus')->with('flash_success', 'menu(s) has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

}


?>