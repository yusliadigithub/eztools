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
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Product\Models\MasterProductAttributeModel;
use App\Modules\Product\Models\MasterProductAttributeValueModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class AttributeController extends Controller
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

        if( !Auth::user()->can('product.attribute.index') ):
            abort(403);
        endif;

        $mid = Crypt::decrypt($merchantid);

        $query = MasterProductAttributeModel::where('merchant_id',$mid);

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
        return view("Product::attributeindex", ['pagetitle'=>__('Product::product.variant'), 'pagedesc'=>__('Admin::base.list'), 'types'=> $types, 'merchants'=>$merchants]);
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
        if( !Auth::user()->can('product.attribute.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'attribute'=>$request->input('attribute_desc'),
                'merchant'=>$request->input('merchant_id'),
            ];

            $rules = [
                'merchant'=>'required',
                'attribute'=>'required',
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
            if($request->input('attribute_id')!=''){
                $pkid = MasterProductAttributeModel::updateData($request->input('attribute_id'),$request);
                $msg = 'updated';
            }else{
                $pkid = MasterProductAttributeModel::insertData($request);
                $msg = 'created';
            }

            $attrval = array_filter($request->input('attvalue'));

            if(count($attrval)>0){

                MasterProductAttributeValueModel::where('attribute_id',$pkid)->delete();

                foreach($attrval as $key=>$val){

                    if($attrval[$key]!=''){
                        $data = ['attribute_id'=> $pkid, 'attribute_value_desc'=>$attrval[$key]];
                        MasterProductAttributeValueModel::insertData($data);
                    }

                }
            }else{
                throw new exception(__('Product::product.msgatleastonevalue'));
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
        if( !Auth::user()->can('product.attribute.update') ):
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
        if( !Auth::user()->can('product.attribute.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = new MasterProductAttributeModel;
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

    public function getInfo($id){

        $attribute = MasterProductAttributeModel::findOrFail($id);
        $html = '';

        if(count($attribute->value)>0){
            $i=0;
            foreach ($attribute->value as $value) {
                $i++;
                $html .= '<tr><td><input class="form-control" type="text" name="attvalue[]" value="'.$value->attribute_value_desc.'"></td><td width="30%">';

                if($i==1){
                    $html .= '<a class="btn btn-xs btn-primary addrow"><i class="fa fa-plus-circle"></i></a>';
                }else{
                    $html .= '<a class="btn btn-xs btn-danger removerow"><i class="fa fa-times-circle"></i></a>';
                }

                $html .= '</td></tr>';
            }
        }else{
            $html .= '<tr>
                        <td><input class="form-control" type="text" name="attvalue[]" value=""></td>
                        <td width="30%"><a class="btn btn-xs btn-primary addrow"><i class="fa fa-plus-circle"></i></a></td>
                    </tr>';
        }

        return json_encode($html);

    }

}
