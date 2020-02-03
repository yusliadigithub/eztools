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
use Validator;
use Auth;
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Merchant\Models\MerchantSupplierModel;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Admin\Models\UploadModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
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
    public function index($merchantid)
    {

        if( !Auth::user()->can('merchant.supplier.index') ):
            abort(403);
        endif;

        $mid = Crypt::decrypt($merchantid);

        $query = MerchantSupplierModel::where('merchant_id',$mid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        /*if(Auth::user()->hasrole('merchant')){
            $query->where('merchant_id',Auth::user()->merchant_id);
        }*/

        $merchant = MerchantModel::findOrFail($mid);

        $suppliers = $query->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));

        return view("Merchant::supplierindex", ['pagetitle'=>__('Merchant::supplier.supplier'), 'pagedesc'=>__('Admin::base.list'), 'suppliers'=>$suppliers, 'merchant'=>$merchant]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //Storage::append('apilog.txt', 'test1234');
        if( !Auth::user()->can('merchant.supplier.create') ):
            abort(403);
        endif;

        $supplier = new MerchantSupplierModel;

        return view("Merchant::createsupplier", ['pagetitle'=>__('Merchant::supplier.addsupplier'), 'pagedesc'=>__('Merchant::supplier.supplier'),'supplier'=>$supplier, 'merchantid'=>$id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if( !Auth::user()->can('merchant.supplier.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'email'=>strtolower($request->input('merchant_supplier_email')),
                'supplier_name'=>$request->input('merchant_supplier_name'),
                'address'=>$request->input('merchant_supplier_address1'),
                'postcode'=>$request->input('merchant_supplier_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'mobile_number'=>$request->input('merchant_supplier_mobileno'),
            ];

            $rules = [
                'email'=>'required|string|email',
                'supplier_name'=>'required|max:100',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'mobile_number'=>'required|numeric',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                //'email' => __('Admin::user.emailvalid'),
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

            MerchantSupplierModel::insertData($request);

            DB::commit();
            return Redirect('merchant/supplier/'.Crypt::encrypt($request->input('merchant_id')).'/index')->with('flash_success', 'Supplier successfully registered!');
            
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
        if( !Auth::user()->can('merchant.supplier.show') ):
            abort(403);
        endif;

        $sid = Crypt::decrypt($id);

        $supplier = MerchantSupplierModel::findOrFail($sid);

        return view("Merchant::createsupplier", ['pagetitle'=>__('Merchant::supplier.updatesupplier'), 'pagedesc'=>__('Merchant::supplier.supplier'),'supplier'=>$supplier, 'merchantid'=>Crypt::encrypt($supplier->merchant_id)]);

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
        if( !Auth::user()->can('merchant.supplier.update') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $current = MerchantSupplierModel::findOrFail($id);

            $input = [
                'email'=>strtolower($request->input('merchant_supplier_email')),
                'supplier_name'=>$request->input('merchant_supplier_name'),
                'address'=>$request->input('merchant_supplier_address1'),
                'postcode'=>$request->input('merchant_supplier_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'mobile_number'=>$request->input('merchant_supplier_mobileno'),
            ];

            $rules = [
                'email'=>'required|string|email',
                'supplier_name'=>'required|max:100',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'mobile_number'=>'required|numeric',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                //'email' => __('Admin::user.emailvalid'),
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

            MerchantSupplierModel::updateData($id,$request);

            DB::commit();
            return Redirect('merchant/supplier/'.$id)->with('flash_success', 'Supplier '.$request->input('supplier_name').' successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('merchant/supplier/create')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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
        if( !Auth::user()->can('merchant.supplier.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = new MerchantSupplierModel;
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

    public function disable($id) {

        if( !Auth::user()->can('merchant.supplier.disable') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MerchantSupplierModel::where('merchant_supplier_id',$id)->update(['merchant_supplier_status'=>0]);
                
            else:
                throw new Exception("You didn't select any data to disable");               
            endif;

            DB::commit();
            return Redirect('merchant/supplier')->with('flash_success', 'Data(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enable($id) {
        
        if( !Auth::user()->can('merchant.supplier.enable') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                MerchantSupplierModel::where('merchant_supplier_id',$id)->update(['merchant_supplier_status'=>1]);
                
            else:
                throw new Exception("You didn't select any data to enable");                
            endif;

            DB::commit();
            return Redirect('merchant/supplier')->with('flash_success', 'Data(s) has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

}
