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
use Globe;
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Product\Models\ProductModel;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Admin\Models\UploadModel;
use App\Modules\Product\Models\ProductAttributeModel;
use App\Modules\Product\Models\ProductAttributeValueModel;
use App\Modules\Admin\Models\MasterTaxModel;
use App\Modules\Product\Models\ProductStockModel;
use App\Modules\Product\Models\ProductStockDetailModel;
use App\Modules\Product\Models\ProductStockLedgerModel;
use App\Modules\Product\Models\ProductStockMovementModel;
use App\Modules\Product\Models\ProductStockTransactionModel;
use App\Modules\Product\Models\ProductStockQuantityMovementModel;

use App\Modules\Product\Models\MasterProductAttributeModel;
use App\Modules\Product\Models\MasterProductAttributeValueModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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

        if( !Auth::user()->can('product.index') ):
            abort(403);
        endif;

        $mid = Crypt::decrypt($merchantid);

        $query = ProductModel::where('merchant_id',$mid);

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
        $taxsupplies = MasterTaxModel::where('tax_type','1')->get();
        $taxpuchases = MasterTaxModel::where('tax_type','2')->get();

        $types = $query->orderBy('updated_at','desc')->paginate(Config::get('constants.common.paginate'));
        return view("Product::index", ['pagetitle'=>__('Product::product.product'), 'pagedesc'=>__('Admin::base.list'), 'types'=> $types, 'merchants'=>$merchants, 'taxsupplies'=>$taxsupplies, 'taxpuchases'=>$taxpuchases]);
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
        if( !Auth::user()->can('product.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'merchant'=>$request->input('merchant_id'),
                'product_type'=>$request->input('product_type_id'),
                'product_name'=>$request->input('product_name'),
            ];

            $rules = [
                'product_type'=>'required',
                'merchant'=>'required',
                'product_name'=>'required',
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
            if($request->input('product_id')!=''){
                $typeid = ProductModel::updateData($request->input('product_id'),$request);
                $msg = 'updated';
            }else{
                $typeid = ProductModel::insertData($request);
                $msg = 'created';
            }

            if($request->hasfile('product')) {

                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/product/';
                $file = $request->file('product');
                $ext=$file->getClientOriginalExtension();

                if(!in_array($ext,$validext)){
                    throw new exception(__('Admin::base.photoformatnotvalid'));
                }

                if($request->input('product_id')!=''){
                    UploadModel::where('upload_model_id',$typeid)->where('upload_model','ProductModel')->delete();
                }

                $name = $typeid.'_'.date('YmdHis').'_product.'.$ext;
                $file->move($path, $name);

                UploadModel::insertData('ProductModel',$typeid,'/uploads/product/',$name,'1');

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
        if( !Auth::user()->can('product.update') ):
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
        if( !Auth::user()->can('product.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = ProductModel::find($id);
                
                if(count($data->stock)>0){
                    throw new Exception(__('Product::product.msgstockexistcannotdelete'));
                }

                if(!empty($data->image)){
                    unlink(public_path($data->image->upload_path.$data->image->upload_filename));
                    UploadModel::where('upload_model_id',$id)->where('upload_model','ProductModel')->delete();
                }

                ProductAttributeModel::where('product_id',$id)->delete();

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

    public function disable($id) {

        if( !Auth::user()->can('product.disable') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                ProductModel::where('product_id',$id)->update(['product_status'=>0]);
                
            else:
                throw new Exception("You didn't select any data to disable");               
            endif;

            DB::commit();
            return Redirect('product')->with('flash_success', 'Data(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enable($id) {
        
        if( !Auth::user()->can('product.enable') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                ProductModel::where('product_id',$id)->update(['product_status'=>1]);
                
            else:
                throw new Exception("You didn't select any data to enable");                
            endif;

            DB::commit();
            return Redirect('product')->with('flash_success', 'Data(s) has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

    public function getInfo($id){

        $type = ProductModel::find($id);

        if(!empty($type->image)){
            $image = $type->image->upload_path.$type->image->upload_filename;
        }else{
            $image = [];
        }

        return json_encode(['type'=>$type, 'image'=>$image]);

    }

    public function getProductAttribute($id){

        $product = ProductModel::findOrFail($id);

        if($product->product_submitstatus=='1'){
            $disabled = 'disabled';
        }else{
            $disabled = '';
        }
        
        $html = '';
        $rownum = 0;

        if(!empty($product->attribute)){

            $attrs = MasterProductAttributeModel::where('merchant_id',$product->merchant_id)->get();

            foreach($product->attribute as $attribute){
                $rownum++;

                $html .= '<tr id="tr'.$rownum.'"><td><select name="attribute['.$rownum.']" data-id="'.$rownum.'" class="form-control getoption" '.$disabled.'>';
                $html .= '<option value="">'.__('Admin::base.please_select').'</option>';

                foreach ($attrs as $attr) {
                    if($attr->attribute_id==$attribute->attribute_id){
                        $option1selected = 'selected';
                    }else{
                        $option1selected = '';
                    }
                    $html .= '<option value="'.$attr->attribute_id.'" '.$option1selected.'>'.$attr->attribute_desc.'</option>';
                }

                $html .= '</select></td><td id="td'.$rownum.'">';

                if(!empty($attribute->value)){

                    $arrayval = [];
                    foreach ($attribute->value as $attrval) {
                        $arrayval[] += $attrval->attribute_value_id;
                    }

                    $vals = MasterProductAttributeValueModel::where('attribute_id',$attribute->attribute_id)->get();

                    $html .= '<select class="form-control" name="value['.$rownum.'][]" multiple '.$disabled.'>';
                    //$html .= '<select class="multiselect-ui form-control" name="value['.$rownum.'][]" multiple>';
                    
                    foreach ($vals as $val) {
                        if(in_array($val->attribute_value_id,$arrayval)){
                            $option2selected = 'selected';
                        }else{
                            $option2selected = '';
                        }
                        $html .= '<option value="'.$val->attribute_value_id.'" '.$option2selected.'>'.$val->attribute_value_desc.'</option>';
                    }

                    $html .= '</select>';
                }

                $html .= '</td><td width="20%"><a class="btn btn-xs btn-danger pull-right removerow" '.$disabled.'><i class="fa fa-times-circle"></i> Row</a></td></tr>';

            }
        }

        $data = ['html'=>$html, 'rownum'=>$rownum];

        return json_encode($data);

    }

    public function getValue($id,$rownum)
    {
        $vals = MasterProductAttributeValueModel::where('attribute_id',$id)->get();

        $html = '<select class="form-control" name="value['.$rownum.'][]" multiple>';
        //$html = '<select class="multiselect-ui form-control" name="value['.$rownum.'][]" multiple>';
        
        if(count($vals)>0){
            foreach ($vals as $val) {
                $html .= '<option value="'.$val->attribute_value_id.'">'.$val->attribute_value_desc.'</option>';
            }
        }

        $html .= '</select>';

        return json_encode($html);

    }

    public function getAttribute($rownum,$pid)
    {   
        
        $product = ProductModel::findOrFail($pid);

        if($product->product_submitstatus=='1'){
            $disabled = 'disabled';
        }else{
            $disabled = '';
        }

        $attrs = MasterProductAttributeModel::where('merchant_id',$product->merchant_id)->get();

        $html  = '<tr id="tr'.$rownum.'"><td><select name="attribute['.$rownum.']" data-id="'.$rownum.'" class="form-control getoption">';
        $html .= '<option value="">'.__('Admin::base.please_select').'</option>';

        if(count($attrs)>0){
            foreach ($attrs as $attr) {
                $html .= '<option value="'.$attr->attribute_id.'">'.$attr->attribute_desc.'</option>';
            }
        }

        $html .= '</select></td><td id="td'.$rownum.'"></td><td width="20%"><a class="btn btn-xs btn-danger pull-right removerow" '.$disabled.'><i class="fa fa-times-circle"></i> Row</a></td></tr>';

        return json_encode($html);

    }

    public function attribute($id)
    {

        if( !Auth::user()->can('product.attribute') ):
            abort(403);
        endif;

        $pid = Crypt::decrypt($id);

        Globe::dataisaccessible('product',$pid);

        $product = ProductModel::findOrFail($pid);

        $query = ProductStockModel::where('product_id',$pid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        $stocks = $query->orderBy('updated_at','desc')->paginate(Config::get('constants.common.paginate'));

        return view("Product::productinfo", ['pagetitle'=>__('Product::product.product'), 'pagedesc'=>__('Product::product.variant'), 'product'=> $product, 'stocks'=>$stocks]);
    }

    public function storeattribute(Request $request)
    {   
        if( !Auth::user()->can('product.storeattribute') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $productid = $request->input('product_id');

            $data = ProductModel::findOrFail($productid);
            $data->product_content = $request->input('product_content');
            $data->save();

            if($data->product_submitstatus != '1'){

                ProductAttributeModel::where('product_id',$productid)->delete();

                if(count($request->input('attribute'))>0){
                    $attribute = array_filter($request->input('attribute'));
                    $value = array_filter($request->input('value'));

                    if(!empty($attribute)){

                        foreach($attribute as $key1=>$val){

                            $attmodel = new ProductAttributeModel;
                            $attmodel->product_id = $productid;
                            $attmodel->attribute_id = $attribute[$key1];
                            $attmodel->created_by = Auth::user()->id;
                            $attmodel->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                            $attmodel->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                            $attmodel->save();

                            //$value = array_filter($request->input('value'));

                            if(!empty($value[$key1])){

                                foreach($value[$key1] as $key2=>$val){
                                    $valmodel = new ProductAttributeValueModel;
                                    $valmodel->product_attribute_id = $attmodel->product_attribute_id;
                                    $valmodel->attribute_value_id = $value[$key1][$key2];
                                    $valmodel->created_by = Auth::user()->id;
                                    $valmodel->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                    $valmodel->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                    $valmodel->save();
                                }

                            }
                        }

                    }
                }
            }

            DB::commit();
            return Redirect('product/attributes/'.Crypt::encrypt($productid))->with('flash_success', 'Data successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('product/attributes/'.Crypt::encrypt($productid))->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }

    }

    public function setprice(Request $request)
    {   
        /*if( !Auth::user()->can('product.setprice') ):
            abort(403);
        endif;*/

        DB::beginTransaction();

        try {

            $stock = ProductStockModel::findOrFail($request->input('product_stock_id'));
            $stock->product_stock_original_price = $request->input('product_stock_original_price');
            $stock->product_stock_market_price = $request->input('product_stock_market_price');
            $stock->product_stock_sale_price = $request->input('product_stock_sale_price');
            $stock->save();

            $productid = $stock->product->product_id;

            DB::commit();
            return Redirect('product/attributes/'.$productid)->with('flash_success', 'Data successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('product/attributes/'.$productid)->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }

    }

    //public function confirm($id)
    public function confirm(Request $request)
    {
        if( !Auth::user()->can('product.confirm') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {
            $productid = $request->input('product_id');

            $product = ProductModel::findOrFail($productid);

            /*if($product->product_isvariable != '0'){
                $totalattribute = ProductAttributeModel::where('product_id',$productid)->count();

                if($totalattribute==0){
                    throw new Exception(__('Product::product.msgatleastonevariant'));
                }
            }*/

            $product->product_submitstatus = '1';

            if($product->save()){

                if($product->product_isvariable != '0'){
                    //store attribute
                    ProductAttributeModel::where('product_id',$productid)->delete();

                    if(count($request->input('attribute'))>0){

                        $attribute = array_filter($request->input('attribute'));
                        $value = array_filter($request->input('value'));

                        if(!empty($attribute)){

                            foreach($attribute as $key1=>$val){

                                $attmodel = new ProductAttributeModel;
                                $attmodel->product_id = $productid;
                                $attmodel->attribute_id = $attribute[$key1];
                                $attmodel->created_by = Auth::user()->id;
                                $attmodel->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                $attmodel->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                $attmodel->save();

                                //$value = array_filter($request->input('value'));

                                if(!empty($value[$key1])){

                                    foreach($value[$key1] as $key2=>$val){
                                        $valmodel = new ProductAttributeValueModel;
                                        $valmodel->product_attribute_id = $attmodel->product_attribute_id;
                                        $valmodel->attribute_value_id = $value[$key1][$key2];
                                        $valmodel->created_by = Auth::user()->id;
                                        $valmodel->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                        $valmodel->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                        $valmodel->save();
                                    }

                                }
                            }

                        }
                    }else{
                        throw new Exception(__('Product::product.msgatleastonevariant'));
                    }
                    //endstore attribute
                }else{

                    $slug = Globe::checkslugvalue('product_stock','product_stock_slug',$product->product_name);

                    $stock = new ProductStockModel;
                    $stock->product_id = $productid;
                    $stock->product_stock_name = $product->product_name;
                    $stock->product_stock_slug = $slug; 
                    $stock->product_stock_description = $product->product_name;
                    $stock->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $stock->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $stock->save();

                }

                /*$count = ProductAttributeModel::leftjoin('product_attribute_value','product_attribute.product_attribute_id','=','product_attribute_value.product_attribute_id')->where('product_id',$id)->select(DB::raw('count(*) as count'))->groupby('product_attribute.attribute_id')->get();

                $newcount = 1;
                foreach ($count as $c) {
                    $newcount *= $c->count;
                }

                $attributevalues = ProductAttributeModel::leftjoin('product_attribute_value','product_attribute.product_attribute_id','=','product_attribute_value.product_attribute_id')->select('product_attribute.attribute_id','product_attribute_value.attribute_value_id')->where('product_id',$id)->get();

                $maxid = ProductStockModel::max('product_stock_id');
                $maxid = ($maxid!='') ? $maxid : 0;

                $stockidarr = [];
                foreach ($attributevalues as $key => $attributevalue) {
               
                    $test = [];
                    
                    if($key % 2){
                        $j = $maxid;
                        $actualcount = ($maxid+$newcount)-1;
                    }else{
                        $j = $maxid+1;
                        $actualcount = ($maxid+$newcount);
                    }
                    for($i=$j; $i<=$actualcount;$i++){
                        $i++;
                        $test[] += $i;
                        $exist = ProductStockModel::existid($i);

                        if($exist==0){
                            $stockid = ProductStockModel::insertData(['product_stock_id'=>$i, 'product_id'=>$id]);
                        }else{
                            $stockid = $i;
                        }

                        $countdetail = ProductStockDetailModel::existdata($stockid,$attributevalue->attribute_id);

                        if($countdetail==0){
                            ProductStockDetailModel::insertData(['product_stock_id'=>$stockid, 'attribute_id'=>$attributevalue->attribute_id, 'attribute_value_id'=>$attributevalue->attribute_value_id]);
                        }

                        $stockidarr[] += $stockid;
                    }

                }*/

                //startset ni dah jadi tapi xcomplete 14052018_1208 //setthree
                /*$maxid = ProductStockModel::max('product_stock_id');
                $maxid = ($maxid!='') ? $maxid+1 : 1;

                $count = ProductAttributeModel::leftjoin('product_attribute_value','product_attribute.product_attribute_id','=','product_attribute_value.product_attribute_id')->where('product_id',$id)->select(DB::raw('count(*) as count,attribute_id'))->groupby('product_attribute.attribute_id')->orderby('attribute_id')->get();
                //dd($count);

                $newcount = 1;
                foreach ($count as $c) {
                    $newcount *= $c->count;
                }

                $stockidcollection = [];
                foreach ($count as $key=>$c) {
                    for($i=$maxid; $i<=$maxid+$newcount-1; $i++){

                        $exist = ProductStockModel::existid($i);
                        if($exist == 0){
                            $stockid = ProductStockModel::insertData(['product_stock_id'=>$i, 'product_id'=>$id]);
                        }else{
                            $stockid = $i;
                        }
                        ProductStockDetailModel::insert(['attribute_id'=>$c->attribute_id,'product_stock_id'=>$i]);
                        $stockidcollection[] += $i;

                    }
                }

                $count2 = ProductAttributeModel::leftjoin('product_attribute_value','product_attribute.product_attribute_id','=','product_attribute_value.product_attribute_id')->where('product_id',$id)->select(DB::raw('count(*) as count,product_attribute.product_attribute_id'))->groupby('product_attribute.product_attribute_id')->orderby('product_attribute.product_attribute_id')->get();
                
                foreach ($count2 as $key2=>$c2) {

                    $values = ProductAttributeValueModel::where('product_attribute_id',$c2->product_attribute_id)->orderby('attribute_value_id')->get();
                    
                    $countvalues = count($values);

                    //start setthree
                    $dividecount = $newcount/$countvalues;
                    $dividecount2 = ($newcount/$dividecount)-1;
                    
                    $data = [];
                    foreach($values as $key=>$val){
                        
                        if($key==0){
                            $newmaxid = $maxid;
                        }else{
                            $newmaxid++;
                        }

                        $j=0;
                        for($i=$newmaxid; $i<=$maxid+$newcount-1; $i++){

                            $j++;
                            if($j != 1){
                                $i += $countvalues-1;
                            }

                            //$countattrinstock = ProductStockDetailModel::existdatawithvalue($i,$val->attribute->attribute_id,$val->attribute_value_id);
                            $countattrinstock = ProductStockDetailModel::existdatavaluenull($i,$val->attribute->attribute_id);
                            
                            if($countattrinstock == 1){
                                $countincollection = ProductStockDetailModel::whereIn('product_stock_id',$stockidcollection)->where('attribute_id',$val->attribute->attribute_id)->where('attribute_value_id',$val->attribute_value_id)->count();
                                
                                if($countincollection < $dividecount){
                                    ProductStockDetailModel::where('product_stock_id',$i)->where('attribute_id',$val->attribute->attribute_id)->update(['attribute_value_id'=>$val->attribute_value_id]);    
                                }
                            }
                            $data[] += $i;

                        }

                        //dd($data);

                    }
                    //end setthree
                    
                    //$data = [];
                    //start settwo
                    /*$increment = $newcount/$countvalues;
                    $newmaxid=$maxid;
                    $newincrement=0;

                    foreach($values as $key=>$val){

                        $newkey = $key+1;
                        $newincrement = $newkey*$increment;

                        if($key==0){
                            $newcount = $newmaxid+$newincrement-1;
                        }else{
                            $newcount = $newincrement;
                        }
                        for($i=$newmaxid; $i<=$newcount; $i++){
                            //$data[] += $i.$val->attribute->attribute_id.$val->attribute_value_id;
                            ProductStockDetailModel::where('product_stock_id',$i)->where('attribute_id',$val->attribute->attribute_id)->update(['attribute_value_id'=>$val->attribute_value_id]);
                            
                        }
                        $newmaxid = $newmaxid+$increment;
                        
                    }*/
                    //end settwo
                   
                //}
                //endset

                /*$stockidarr = ProductStockModel::where('product_id',$id)->pluck('product_stock_id');

                //update description
                //$stockdetails = ProductStockDetailModel::whereIn('product_stock_id',$stockidarr)->orderBy('product_stock_id','attribute_id')->get();
                $stocktables = ProductStockModel::whereIn('product_stock_id',$stockidarr)->orderby('product_stock_id')->get();
                
                foreach ($stocktables as $stocktable) {
                    
                    $detailtables = ProductStockDetailModel::where('product_stock_id',$stocktable->product_stock_id)->orderby('attribute_id')->get();

                    $text = '';
                    foreach ($detailtables as $key => $detailtable) {
                        
                        if($key==0){
                            $text .= $detailtable->attribute->attribute_desc.':'.$detailtable->value->attribute_value_desc;
                        }else{
                            $text .= ' | '.$detailtable->attribute->attribute_desc.':'.$detailtable->value->attribute_value_desc;
                        }

                    }

                    ProductStockModel::where('product_stock_id',$stocktable->product_stock_id)->update(['product_stock_description'=>$text]);

                }*/ 

            }
            

            /*$product = ProductModel::findOrFail($id);
            $product->product_submitstatus = '1';

            if($product->save()){

                if(count($product->attribute)>0){
                    foreach($product->attribute as $pa){
                        if(count($pa->value)>0){
                            foreach($pa->value as $pv){

                                $data = ['product_id'=>$id , 'attribute_id'=>$pa->attribute_id , 'attribute_value_id'=>$pv->attribute_value_id];
                                ProductStockModel::insertData($data);

                            }
                        }
                    }
                }

            }*/


            DB::commit();
            return Redirect('product/attributes/'.Crypt::encrypt($productid))->with('flash_success', 'Data successfully submitted!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('product/attributes/'.Crypt::encrypt($productid))->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

    /*public function getProductAttribute($id){

        $product = ProductModel::findOrFail($id);
        
        $html = '';
        $i = 0;
        $j = 0;

        if(!empty($product->attribute)){

            foreach($product->attribute as $attribute){
                $i++;
                $html .= '<tr id="tr'.$i.'"><td><input type="text" name="attribute['.$i.']" value="'.$attribute->product_attribute_desc.'"></td><td id="td'.$i.'">';

                $j = 0;
                if(!empty($attribute->value)){
                    foreach($attribute->value as $value){
                        $j++; 

                        if($j==1){
                            $html .= '<div><input type="text" name="value['.$i.']['.$j.']" value="'.$value->product_attribute_value_desc.'"><a class="btn btn-xs btn-primary pull-right" href="javascript:addval('.$i.');"><i class="fa fa-plus-circle"></i></a></div>';
                        }else{
                            $html .= '<div><input type="text" name="value['.$i.']['.$j.']" value="'.$value->product_attribute_value_desc.'"><a class="btn btn-xs btn-danger pull-right removeval"><i class="fa fa-times-circle"></i></a></div>';
                        }
                    }
                }

                $html .= '</td><td width="15%"><a class="btn btn-xs btn-danger pull-right removerow"><i class="fa fa-times-circle"></i> Row</a></td></tr>';

            }
        }

        $data = ['html'=>$html, 'rownum'=>$i, 'tdnum'=>$j];

        return json_encode($data);

    }*/

    public function destroystock($id)
    {
        if( !Auth::user()->can('product.deletestock') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = ProductStockModel::find($id);

                if(!empty($data->image)){
                    unlink(public_path($data->image->upload_path.$data->image->upload_filename));
                }

                if(count($data->multiimage)>0){
                    foreach ($data->multiimage as $multiimage) {
                        unlink(public_path($multiimage->upload_path.$multiimage->upload_filename));
                    }
                }
                ProductStockDetailModel::where('product_stock_id',$id)->delete();
                UploadModel::where('upload_model','ProductStockModel')->where('upload_model_id',$id)->delete();

                $data->delete();

                //$data = new ProductStockModel;
                //$data->deleteData($id);
                
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

    public function disablestock($id) {

        if( !Auth::user()->can('product.disablestock') ):
            abort(403);
        endif;
        
        DB::beginTransaction();
        try {
            
            if(!empty($id)):
                    
                ProductStockModel::where('product_stock_id',$id)->update(['product_stock_status'=>0]);
                
            else:
                throw new Exception("You didn't select any data to disable");               
            endif;

            DB::commit();
            return Redirect::back()->with('flash_success', 'Data(s) has been disabled!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }
    }


    public function enablestock($id) {
        
        if( !Auth::user()->can('product.enablestock') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):

                $stock = ProductStockModel::findOrFail($id);

                if($stock->product->product_content == ''):
                    throw new exception(__('Product::product.msgproductcontentempty'));
                endif;

                $stock->product_stock_status = '1';
                $stock->save();
                
            else:
                throw new Exception("You didn't select any data to enable");                
            endif;

            DB::commit();
            return Redirect::back()->with('flash_success', 'Data(s) has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

    /*public function createstock($id)
    {

        // if( !Auth::user()->can('product.createstock') ):
        //     abort(403);
        // endif;

        
    }*/

    public function adjustquantity(Request $request){

        // if( !Auth::user()->can('product.adjustquantity') ):
        //     abort(403);
        // endif;

        DB::beginTransaction();
        try {

            if($request->input('modal5_product_stock_id') == '' || $request->input('modal5_adjustmentvalue') == ''){
                throw new Exception( __('Admin::base.pleasefillupform') );
            }

            Globe::stockmovementfromproduct($request->input('modal5_product_stock_id'),$request->input('modal5_adjustmentvalue'),0,__('Order::order.selfquantityadjustment'));

            DB::commit();
            return Redirect::back()->with('flash_success', 'Data(s) has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with([ 'flash_error' => $error->getMessage(), 'modal5'=>true ]);
        }       

    }

    public function storestock(Request $request)
    {

        if( !Auth::user()->can('product.storestock') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            //dd($request);
            $productid = $request->input('m2_product_id');

            if($request->input('m2_product_stock_id')!=''){
                
                $stockid = $request->input('m2_product_stock_id');

                $stock = ProductStockModel::findOrFail($stockid);
                //$stock->product_stock_quantity = ($request->input('product_stock_quantity')!='') ? $request->input('product_stock_quantity') : 0;
                $stock->product_stock_weight = ($request->input('product_stock_weight') != '') ? $request->input('product_stock_weight') : 0;
                $stock->product_stock_original_price = ($request->input('product_stock_original_price')!='') ? $request->input('product_stock_original_price') : 0;
                $stock->product_stock_market_price = ($request->input('product_stock_market_price')!='') ? $request->input('product_stock_market_price') : 0;
                $stock->product_stock_sale_price = ($request->input('product_stock_sale_price')!='') ? $request->input('product_stock_sale_price') : 0;
                $stock->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $stock->save();

                $msg = 'updated';

            }else{
                
                $stock = new ProductStockModel;
                $stock->product_id = $productid;
                $quantity = ($request->input('product_stock_quantity')!='') ? $request->input('product_stock_quantity') : 0;
                //$stock->product_stock_quantity = ($request->input('product_stock_quantity')!='') ? $request->input('product_stock_quantity') : 0;
                $stock->product_stock_weight = ($request->input('product_stock_weight') != '') ? $request->input('product_stock_weight') : 0;
                $stock->product_stock_original_price = ($request->input('product_stock_original_price')!='') ? $request->input('product_stock_original_price') : 0;
                $stock->product_stock_market_price = ($request->input('product_stock_market_price')!='') ? $request->input('product_stock_market_price') : 0;
                $stock->product_stock_sale_price = ($request->input('product_stock_sale_price')!='') ? $request->input('product_stock_sale_price') : 0;

                if($stock->save()){

                    $stockid = $stock->product_stock_id;

                    $attr = array_filter($request->input('m2_attribute'));
                    $val = $request->input('m2_attribute_value');

                    if(count($attr)>0){

                        for($i=0; $i<count($attr); $i++){
                            $detail = new ProductStockDetailModel;
                            $detail->product_stock_id = $stockid;
                            $detail->attribute_id = $attr[$i];
                            $detail->attribute_value_id = $val[$i];
                            $detail->save();
                        }

                    }

                    $detailtables = ProductStockDetailModel::where('product_stock_id',$stockid)->orderby('attribute_id')->get();

                    $text = '';
                    $desc = '';
                    $arr_attr = [];
                    $arr_val  = [];
                    $stockname = $stock->product->product_name;
                    foreach ($detailtables as $key=>$detailtable) {
                        
                        if($key==0){
                            $stockname .= ' '.$detailtable->value->attribute_value_desc;
                            $text .= $detailtable->attribute_id.':'.$detailtable->attribute_value_id;
                            $desc .= $detailtable->attribute->attribute_desc.':'.$detailtable->value->attribute_value_desc;
                        }else{
                            $stockname .= '|'.$detailtable->value->attribute_value_desc;
                            $text .= ','.$detailtable->attribute_id.':'.$detailtable->attribute_value_id;
                            $desc .= ' | '.$detailtable->attribute->attribute_desc.':'.$detailtable->value->attribute_value_desc;
                        }
                        $arr_attr[] += $detailtable->attribute_id;
                        $arr_val[] += $detailtable->attribute_value_id;

                    }

                    $existingstock = ProductStockModel::where('product_id',$productid)->where('product_stock_id','!=',$stockid)->get();

                    foreach ($existingstock as $es) {
                        $countexistdetail = ProductStockDetailModel::where('product_stock_id',$es->product_stock_id)->whereIn('attribute_id',$arr_attr)->whereIn('attribute_value_id',$arr_val)->count();
                        
                        if($countexistdetail == count($attr)){
                            throw new exception(__('Product::product.msgstockexist'));
                        }

                    }

                    ProductStockModel::where('product_stock_id',$stockid)->update(['product_stock_description'=>$desc, 'product_stock_variant'=>$text]);

                    Globe::stockmovementfromproduct($stockid,$quantity,0,__('Order::order.selfquantityadjustment'));

                }

                ProductStockModel::updatestockname($stockid,$stockname);

                $msg = 'created';
            }

            //uploadfile
            $validext = ['jpg','png','jpeg'];
            $path = public_path().'/uploads/stock/';

            if($request->hasfile('mainphoto')) {

                $file = $request->file('mainphoto');
                $ext=$file->getClientOriginalExtension();

                if(!in_array($ext,$validext)){
                    throw new exception(__('Admin::base.photoformatnotvalid'));
                }

                //if($file->getSize())
                $name = $stockid.'_'.date('YmdHis').'_main.'.$ext;
                $file->move($path, $name);

                UploadModel::insertData('ProductStockModel',$stockid,'/uploads/stock/',$name,'1');

            }

            if($request->hasfile('childphoto')) {
                
                $i = 0;
                foreach($request->file('childphoto') as $file){
                    $i++;
                    //$name=$file->getClientOriginalName();
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $stockid.'_'.date('YmdHis').'_'.$i.'.'.$ext;
                    $file->move($path, $name); 

                    UploadModel::insertData('ProductStockModel',$stockid,'/uploads/stock/',$name,'2');
                }
            }
            
            DB::commit();
            return Redirect('product/attributes/'.Crypt::encrypt($productid))->with('flash_success', 'Data successfully '.$msg.'!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('product/attributes/'.Crypt::encrypt($productid))->withInput()->with( [ 'flash_error' => $error->getMessage(), 'modal2'=>true ]);
            
        }
        
    }

    public function getStockInfo($id){

        $stock = ProductStockModel::find($id);

        if(!empty($stock->image)){
            $image = $stock->image->upload_path.$stock->image->upload_filename;
        }else{
            $image = '/img/noimage.jpg';
        }

        $html = '';
        if(!empty($stock->multiimage)){

            foreach ($stock->multiimage as $multiimage) {
                $html .= '<li class="liphoto"><div class="fileinput fileinput-new" data-provides="fileinput"><div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput"><img src="'.asset($multiimage->upload_path.$multiimage->upload_filename).'" alt="'.asset("/img/noimage.jpg").'" height="120" width="120" id="imagesrc"></div><div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div><div><span class="btn btn-primary btn-sm btn-file"><span class="fileinput-new">'.__("Admin::base.selectphoto").'</span><span class="fileinput-exists">'.__("Admin::base.change").'</span><input type="file" name="childphoto[]" id="childphoto" class="modaldata" accept="image/*" multiple="" required/></span><a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a></div></div></li>';
            }

        }

        return json_encode(['stock'=>$stock, 'image'=>$image, 'html'=>$html]);

    }

    public function getModalAttribute($id){

        $html = '';

        $variants =  ProductAttributeModel::where('product_id',$id)->get();

        $arrno = 0;
        foreach ($variants as $key => $variant) {
            
            $html .= '<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">'.$variant->attribute->attribute_desc.'</label><input type="hidden" name="m2_attribute['.$arrno.']" value="'.$variant->attribute_id.'"><div class="col-md-7 col-sm-7 col-xs-12"><select class="form-control" name="m2_attribute_value['.$arrno.']"><!--option value="">'.__('Admin::base.please_select').'</option-->';

            $values = ProductAttributeValueModel::where('product_attribute_id',$variant->product_attribute_id)->get();

            foreach ($values as $key => $value) {

                $html .= '<option value="'.$value->attribute_value_id.'">'.$value->value->attribute_value_desc.'</option>';

            }


            $html .= '</select></div></div>';
            $arrno++;

        }

        return json_encode($html);

    }

    public function stockqtyledger($stockid)
    {

        // if( !Auth::user()->can('product.stockqtyledger') ):
        //     abort(403);
        // endif;

        $sid = Crypt::decrypt($stockid);

        $years = ProductStockLedgerModel::where('product_stock_id',$sid)->get();
        $stock = ProductStockModel::findOrFail($sid);

        $movements = '';
        $ledgeryear = '';

        //if(Input::has('search') && Input::has('ledgerid')) {
        if(Input::has('ledgeryear')) {

            //$movements = ProductStockMovementModel::where('product_stock_ledger_id',Input::get('ledgerid'))->get();
            $movements = ProductStockLedgerModel::where('product_stock_id',$sid)->where('product_stock_ledger_year',Input::get('ledgeryear'))->first();
            $ledgeryear = Input::get('ledgeryear');

        } 

        return view("Product::stockledger", ['pagetitle'=>__('Product::product.stockledger'), 'pagedesc'=>'', 'movements'=> $movements, 'years'=>$years, 'stock'=>$stock, 'ledgeryear'=>$ledgeryear]);
    }

    public function stockqtytrans($movementid)
    {

        // if( !Auth::user()->can('product.stockqtytrans') ):
        //     abort(403);
        // endif;

        $mid = Crypt::decrypt($movementid);

        $data = ProductStockTransactionModel::where('product_stock_movement_id',$mid)->first(); 

        $stock = ProductStockModel::findOrFail($data->movement->ledger->product_stock_id);

        $transactions = ProductStockTransactionModel::where('product_stock_movement_id',$mid)->get();

        return view("Product::stocktransaction", ['pagetitle'=>__('Product::product.dailytransaction'), 'pagedesc'=>'', 'transactions'=> $transactions, 'stock'=>$stock, 'data'=>$data]);
    }

    public function stockmovement($stockid)
    {

        // if( !Auth::user()->can('product.stockmovement') ):
        //     abort(403);
        // endif;

        $sid = Crypt::decrypt($stockid);

        $stock = ProductStockModel::findOrFail($sid);

        $daterangestr = '';
        $movements = '';

        if(Input::has('daterangeinput')) {

            $daterange = str_replace(' ','',trim(Input::get('daterangeinput')));
            $daterange = explode('-',$daterange);
            $datefrom = date( 'Y-m-d', strtotime($daterange[0]));
            $dateto = date( 'Y-m-d', strtotime($daterange[1]));
            $daterangestr = date( 'd/m/Y', strtotime($daterange[0])).' - '.date( 'd/m/Y', strtotime($daterange[1]));

            $movements = ProductStockQuantityMovementModel::where('product_stock_id',$sid)->whereDate('created_at','>=',$datefrom)->whereDate('created_at','<=',$dateto)->get();
        }

        return view("Product::stockmovement", ['pagetitle'=>__('Product::product.dailytransaction'), 'pagedesc'=>'', 'movements'=> $movements, 'stock'=>$stock, 'daterangestr'=>$daterangestr]);
    }

    public function productmovement($merchantid)
    {

        // if( !Auth::user()->can('product.productmovement') ):
        //     abort(403);
        // endif;

        $mid = Crypt::decrypt($merchantid);

        $query = ProductModel::where('merchant_id',$mid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }   

        $types = $query->orderBy('updated_at','desc')->paginate(Config::get('constants.common.paginate'));

        return view("Product::productmovement", ['pagetitle'=>__('Product::product.product'), 'pagedesc'=>__('Product::product.stockmovementbyproduct'), 'types'=> $types, 'merchantid'=>$mid]);

    }

    public function productvariantmovement($productid){

        // if( !Auth::user()->can('product.productvariantmovement') ):
        //     abort(403);
        // endif;

        $pid = Crypt::decrypt($productid);

        $product = ProductModel::findOrFail($pid);

        $query = ProductStockModel::where('product_id',$pid);

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }   

        $types = $query->orderBy('updated_at','desc')->paginate(Config::get('constants.common.paginate'));

        return view("Product::productvariantmovement", ['pagetitle'=>__('Product::product.product'), 'pagedesc'=>__('Product::product.stockmovementbyproductvariant'), 'types'=> $types, 'productid'=>$pid, 'product_name'=>$product->product_name]);

    }

}
