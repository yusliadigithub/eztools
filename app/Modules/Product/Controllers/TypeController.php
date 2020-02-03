<?php

namespace App\Modules\Product\Controllers;

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
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Product\Models\ProductTypeModel;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Admin\Models\UploadModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
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

        if( !Auth::user()->can('product.type.index') ):
            abort(403);
        endif;

        $mid = Crypt::decrypt($merchantid);

        $query = ProductTypeModel::where('merchant_id',$mid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        /*$merchants = [];

        if(Auth::user()->hasRole('agent')){

            $merchants = MerchantModel::where('created_by',Auth::user()->id)->get();
            
        }elseif(Auth::user()->hasRole('merchant')){

            $merchants = MerchantModel::where('merchant_id',Auth::user()->merchant_id)->get();
            
        }elseif(Auth::user()->hasRole('branch')){

            $merchants = MerchantModel::where('merchant_id',Auth::user()->merchant_id)->get();

        }

        $merchantid = [];
        foreach($merchants as $merchant):
            $merchantid[] += $merchant->merchant_id;
        endforeach;
        
        $query->whereIn('merchant_id',$merchantid);*/

        $merchants = MerchantModel::findOrFail($mid);

        $types = $query->orderBy('created_at','asc')->paginate(Config::get('constants.common.paginate'));
        return view("Product::typeindex", ['pagetitle'=>__('Product::product.producttype'), 'pagedesc'=>__('Admin::base.list'), 'types'=> $types, 'merchants'=>$merchants]);
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
        if( !Auth::user()->can('product.type.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'product_type'=>$request->input('product_type_desc'),
            ];

            $rules = [
                'product_type'=>'required',
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
            if($request->input('product_type_id')!=''){
                $typeid = ProductTypeModel::updateData($request->input('product_type_id'),$request);
                $msg = 'updated';
            }else{
                $typeid = ProductTypeModel::insertData($request);
                $msg = 'created';
            }

            if($request->hasfile('productcategory')) {

                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/productcategory/';
                $file = $request->file('productcategory');
                $ext=$file->getClientOriginalExtension();

                if(!in_array($ext,$validext)){
                    throw new exception(__('Admin::base.photoformatnotvalid'));
                }

                //if($file->getSize())

                if($request->input('product_type_id')!=''){
                    UploadModel::where('upload_model_id',$typeid)->where('upload_model','ProductTypeModel')->delete();
                }

                $name = $typeid.'_'.date('YmdHis').'_productcategory.'.$ext;
                $file->move($path, $name);

                UploadModel::insertData('ProductTypeModel',$typeid,'/uploads/productcategory/',$name);

            }

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Data successfully '.$msg.'!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
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
        if( !Auth::user()->can('product.type.update') ):
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
        if( !Auth::user()->can('product.type.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = ProductTypeModel::find($id);

                if(!empty($data->image)){
                    unlink(public_path($data->image->upload_path.$data->image->upload_filename));
                    UploadModel::where('upload_model_id',$id)->where('upload_model','ProductTypeModel')->delete();
                }
                $data->delete();
                
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

    public function getInfo($id){

        $type = ProductTypeModel::find($id);

        if(!empty($type->image)){
            $image = $type->image->upload_path.$type->image->upload_filename;
        }else{
            $image = [];
        }

        return json_encode(['type'=>$type, 'image'=>$image]);

    }

    public function getParentCategoryByMerchant($id,$eid){

        if($eid != 'noid'):
            return json_encode(ProductTypeModel::where('merchant_id',$id)->where('product_type_id','!=',$eid)->pluck('product_type_desc','product_type_id'));
        else:
            return json_encode(ProductTypeModel::where('merchant_id',$id)->pluck('product_type_desc','product_type_id'));
        endif;

    }

}
