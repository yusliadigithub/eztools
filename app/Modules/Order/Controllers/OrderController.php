<?php

namespace App\Modules\Order\Controllers;

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
use Globe;
use Cart;
use Carbon\Carbon;
use App\Modules\Product\Models\ProductModel;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Product\Models\ProductTypeModel;
use App\Modules\Order\Models\CartModel;
use App\Modules\Order\Models\CartDetailModel;
use App\Modules\Product\Models\ProductStockModel;
use App\Modules\Frontend\Models\UserGuestModel;
use App\Modules\Frontend\Models\UserGuestAddressModel;
use App\Modules\Admin\Models\UploadModel;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=> ['printdoc']]);
    }

    public function checkexistcartsession(){

        $count = 1;

        if(!Session::has('activecart')){
            $count = 0;
        }

        return json_encode($count);

    }

    public function createcartsession(Request $request){

        $stock = ProductStockModel::findOrFail($request->stockid);  

        if($request->action == 'add'){
            if($request->has('guestid')){

                Session::put('activecart', $request->guestid);

                Cart::session($request->guestid)->add([
                        'id'        =>  $request->stockid,
                        'name'      =>  $stock->product_stock_description,
                        'price'     =>  $stock->product_stock_sale_price,
                        'quantity'  =>  $request->quantity,
                        'attributes'=>  ['weight'   =>  $stock->product_stock_weight]
                                                                                    ]);

            }else{

                Cart::session(Session::get('activecart'))->add([
                        'id'        =>  $request->stockid,
                        'name'      =>  $stock->product_stock_description,
                        'price'     =>  $stock->product_stock_sale_price,
                        'quantity'  =>  $request->quantity,
                        'attributes'=>  ['weight'   =>  $stock->product_stock_weight]
                                                                                    ]);

            }
        }

        return json_encode(Cart::session(Session::get("activecart"))->getContent()->count());

    }

    public function removecartitem($id){

        Cart::session(Session::get('activecart'))->remove($id);

        $data = self::sharedisplaycart();

        return json_encode(['status'=>'1', 'totalleft' => Cart::session(Session::get("activecart"))->getContent()->count(), 'html'=>$data['html']]);

    }

    public function removeitem($id){

        $detail = CartDetailModel::findOrFail($id);

        if($detail->cart->cart_isinvoice == 1){

           Globe::stockmovementfromproduct($detail->product_stock_id,$detail->cart_detail_quantity,$detail->cart_detail_final_amount,__('Order::order.cancelorder'),$detail->cart->cart_orderno); 

        }

        $detail->delete();

        return json_encode(1);

    }

    public function sharedisplaycart(){

        $guest = UserGuestModel::findOrFail(Session::get("activecart"));
        $items = Cart::session(Session::get("activecart"))->getContent();
        $html = '';

        if(count($items)>0){

            $totalweight = 0;
            $totalamount = 0;
            $i = 0;
            $totalgst = 0;

            foreach ($items as $key => $item) {
                $i++;
                $stock = ProductStockModel::findOrFail($item->id);
                $itemcost = Globe::itemcost($item->id,$item->quantity);
                $totalweight += $itemcost['weight'];
                $totalamount += $itemcost['amount'];
                $totalgst += $itemcost['gstcost'];

                $qtybtn = '<div class="input-group">
                            <div class="input-group-btn">
                                <a data-toggle="tooltip" class="btn btn-s btn-default minusdata" data-id="'.$item->id.'"><i class="fa fa-minus"></i></a>
                            </div>
                            <input class="form-control" id="qty'.$item->id.'" type="text" value="'.$item->quantity.'" readonly>
                            <div class="input-group-btn">
                                <a data-toggle="tooltip" class="btn btn-s btn-default plusdata" data-id="'.$item->id.'"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>';

                $html .= '<tr id="itemtr'.$item->id.'">';
                $html .= '<td>'.$i.'</td><td>'.$stock->product->product_name.' - '.$stock->product_stock_description.'</td>';
                $html .= '<td class="text-right">'.Globe::moneyFormat($stock->product_stock_sale_price,2).'</td>';
                $html .= '<td class="text-right">'.number_format($stock->product->taxpurchase->tax_charge,2).'</td>';
                $html .= '<td class="text-right">'.number_format($itemcost['weight'],2).'</td>';
                $html .= '<td class="text-right">'.$qtybtn.'</td>';
                $html .= '<td class="text-right">'.Globe::moneyFormat($itemcost['amount'],2).'</td>';
                $html .= '<td class="text-center"><a data-toggle="tooltip" data-id="'.$item->id.'" class="btn btn-xs btn-danger removecartitem"><i class="fa fa-times-circle"></i></a></td></tr>';


            }

            $shippingcost = Globe::shippingcost($totalweight,'1',$guest->merchant_id);
            $finalamount = $shippingcost+$totalamount+$totalgst;

            $html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.totalamount').'</b></td><td class="text-right">'.Globe::moneyFormat($totalamount,2).'</td><td class="text-right"></td></tr>';
            $html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.totalgst').'</b></td><td class="text-right">'.Globe::moneyFormat($totalgst,2).'</td><td class="text-right"></td></tr>';
            $html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.shippingcost').'</b></td><td class="text-right">'.Globe::moneyFormat($shippingcost,2).'</td><td class="text-right"></td></tr>';
            $html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.finalamount').'</b></td><td class="text-right">'.Globe::moneyFormat($finalamount,2).'</td><td class="text-right"></td></tr>';
            $html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.saveas').'</b></td><td colspan="2"><select name="type_id" id="type_id" class="form-control modaldata" required>
                            <option value="">'.__('Admin::base.please_select').'</option>
                            <option value="1">'.__('Order::order.quotation').'</option>
                            <option value="2">'.__('Order::order.taxinvoice').'</option>
                        </select></td></tr>';
            
        }

        $data = ['html'=>$html, 'items'=>$items, 'guest'=>$guest];

        return $data;

    }

    public function displaycart(){

        //Cart::session(Session::get("activecart"))->clear();

        $data = self::sharedisplaycart();
        return view("Order::draftcart", ['pagetitle'=>__('Order::order.cartinfo'), 'pagedesc'=>__('Admin::base.detail'), 'items'=>$data['items'], 'guest'=>$data['guest'], 'html'=>$data['html']]);

    }

    public function getdraftaddress(Request $request){

        if($request->addid == ''){
            if($request->type == 'billing'){
                $data = Globe::guestaddresshtml($request->guestid,'billing','');
            }else{
                $data = Globe::guestaddresshtml($request->guestid,'shipping','');
            }
        }else{
            $data = Globe::guestaddresshtml($request->guestid,'',$request->addid);
        }

        return json_encode($data);

    }

    public function minuspluscartitem(Request $request){

        //dd($request->quantity);

        Cart::session(Session::get("activecart"))->update( $request->stockid, array(
            'quantity' => array(
            'relative' => false,
            'value' => $request->quantity
            ),
        ));

        $data = self::sharedisplaycart();

        return json_encode($data['html']);

    }

    public function keepcart(Request $request){

        if( !Auth::user()->can('order.addtocart') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $guest = UserGuestModel::findOrFail(Session::get("activecart"));
                
            //address meta
            if(Session::get('activebilladdress')){  
                $metadatabill = Globe::guestaddresshtml(Session::get("activecart"),'',Session::get('activebilladdress'));
            }else{
                $metadatabill = Globe::guestaddresshtml(Session::get("activecart"),'billing','');
            }

            if(Session::get('activeshipaddress')){  
                $metadataship = Globe::guestaddresshtml(Session::get("activecart"),'',Session::get('activeshipaddress'));
            }else{
                $metadataship = Globe::guestaddresshtml(Session::get("activecart"),'shipping','');
            }

            $meta = ['shipping'=>['name'=>$metadataship['name'], 'phone'=>$metadataship['phone'], 'address' =>$metadataship['address']],
                    'billing' => ['name'=>$metadatabill['name'], 'phone'=>$metadatabill['phone'], 'address' =>$metadatabill['address']],
                    'payment_type'=> 'direct'];

            $metadata = serialize($meta);
            //close address meta

            $items = Cart::session(Session::get("activecart"))->getContent();

            $cart = new CartModel;
            $cart->merchant_id = $guest->merchant_id;
            $cart->cart_metadata = $metadata;
            $cart->guest_id = Session::get("activecart");

            if($request->input('type_id') == '1'){
                $cart->cart_isquotation = 1;
                $cart->cart_isinvoice = 0;
                $redirect = 'order/quotation';
                $prefix = 'quo';
            }else{
                $cart->cart_isquotation = 0;
                $cart->cart_isinvoice = 1;
                $redirect = 'order/invoice';
                $prefix = 'inv';
            }

            //$cart->cart_orderno = Globe::running_no($guest->merchant_id,$prefix);
            $cart->cart_orderno = Globe::running_no($guest->merchant_id);
            $cart->cart_controller = 'OrderController';
            $cart->cart_isactive = 1;
            $cart->cart_confirm = 0;
            $cart->created_by = Auth::user()->id;
            $cart->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            $cart->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

            if($cart->save()){

                if(count($items)>0){

                    $totalweight = 0;
                    $totalamount = 0;
                    $i = 1;
                    $totalgst = 0;

                    foreach ($items as $key => $item) {

                        $itemcost = Globe::itemcost($item->id,$item->quantity);
                        $totalgst += $itemcost['gstcost'];
                        $totalweight += $itemcost['weight'];
                        $totalamount += $itemcost['amount'];

                        $detail = new CartDetailModel;
                        $detail->cart_id = $cart->cart_id;
                        $detail->product_stock_id = $item->id;
                        $detail->cart_detail_quantity = $item->quantity;
                        $detail->cart_detail_price = $itemcost['price'];
                        $detail->cart_detail_gst_percent = $itemcost['gst'];
                        $detail->cart_detail_weight = $itemcost['weight'];
                        $detail->cart_detail_actual_amount = $itemcost['amount'];
                        $detail->cart_detail_gst_amount = $itemcost['gstcost'];
                        $detail->cart_detail_final_amount = $itemcost['amount']+$itemcost['gstcost'];
                        $detail->save();

                        if($cart->cart_isinvoice==1){
                            Globe::stockmovementfromcart($detail->cart_detail_id);
                        }

                    }

                    $shippingcost = Globe::shippingcost($totalweight,'1',$guest->merchant_id);
                    $finalamount = $shippingcost+$totalamount+$totalgst;

                    $cart2 = CartModel::findOrFail($cart->cart_id);
                    $cart2->cart_total_weight = $totalweight;
                    $cart2->cart_actual_amount = $totalamount;
                    $cart2->cart_gst_amount = $totalgst;
                    $cart2->cart_shipping_amount = $shippingcost;
                    $cart2->cart_final_amount = $finalamount;
                    $cart2->save();

                }

            }

            DB::commit();
            Cart::session(Session::get("activecart"))->clear();
            Session::forget('activecart');
            Session::forget('activebilladdress');
            Session::forget('activeshipaddress');
            
            return Redirect($redirect)->with('flash_success', 'Cart successfully created!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }

    }

    public function clearcart(){

        Cart::session(Session::get("activecart"))->clear();
        Session::forget('activecart');

        return json_encode(1);

    }

    public function getcartinfo(){

        $html = '';
        
        $divstart = '<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">';
        $divmid = '</label><div class="col-md-7 col-sm-7 col-xs-12"><input class="form-control" type="text" value="';
        $divend = '" disabled></div></div>';

        $guest = UserGuestModel::findOrFail(Session::get("activecart"));

        $html .= $divstart.__('Admin::base.email').$divmid.$guest->email.$divend;
        $html .= $divstart.__('Order::order.customer').$divmid.$guest->guest_fullname.$divend;

        $items = Cart::session(Session::get("activecart"))->getContent();

        if(count($items)>0){

            $html .= '<div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12"><table class="table table-bordered"><tbody><tr bgcolor="#172f93"><th width="5%"><font color="#FFFFFF">#</font></th><th><font color="#FFFFFF">'.__('Product::product.product').'</font></th><th class="text-center" width="12%"><font color="#FFFFFF">'.__('Order::order.price').'</font></th><th class="text-center" width="12%"><font color="#FFFFFF">GST (%)</font></th><th class="text-center" width="12%"><font color="#FFFFFF">'.__('Order::order.weight').'</font></th><th class="text-center" width="12%"><font color="#FFFFFF">'.__('Order::order.quantity').'</font></th><th class="text-center" width="12%"><font color="#FFFFFF">Subtotal</font></th><th width="5%">&nbsp;</th></tr>';

            $totalweight = 0;
            $totalamount = 0;
            $i = 1;

            foreach ($items as $key => $item) {

                $stock = ProductStockModel::findOrFail($item->id);
                $amount = $stock->product_stock_sale_price*$item->quantity;
                $weight = $stock->product_stock_weight*$item->quantity;
                $totalweight += $weight;
                $totalamount += $amount;

                $html .= '<tr id="itemtr'.$item->id.'"><td>'.($i++).'</td><td>'.$stock->product->product_name.' - '.$stock->product_stock_description.'</td><td class="text-right">'.number_format($stock->product_stock_sale_price,2).'</td><td class="text-right">'.number_format($stock->product->taxpurchase->tax_charge,2).'</td><td class="text-right">'.number_format($weight,2).'</td><td class="text-right">'.number_format($item->quantity).'</td><td class="text-right">'.Globe::moneyFormat($amount,2).'</td><td><a data-toggle="tooltip" data-id="'.$item->id.'" class="btn btn-xs btn-danger removecartitem"><i class="fa fa-times-circle"></i></a></td></tr>';

            }

            $shippingcost = Globe::shippingcost($totalweight,'1',$guest->merchant_id);
            $finalamount = $shippingcost+$totalamount;

            /*$html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.totalamount').'</b></td><td class="text-right">'.Globe::moneyFormat($totalamount,2).'</td><td class="text-right"></td>';
            $html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.shippingcost').'</b></td><td class="text-right">'.Globe::moneyFormat($shippingcost,2).'</td><td class="text-right"></td>';
            $html .= '<tr><td class="text-right" colspan="6"><b>'.__('Order::order.finalamount').'</b></td><td class="text-right">'.Globe::moneyFormat($finalamount,2).'</td><td class="text-right"></td>';*/

            $html .= '</tbody></table></div></div>';

        }             

        return json_encode($html);

    }

    public function submitcart(Request $request){

    }

    public function invoice()
    {
        return self::sharequotationinvoicelist('2');
    }

    public function quotation()
    {
        return self::sharequotationinvoicelist('1');
    }

    public function sharequotationinvoicelist($type){

        if(Auth::user()->hasRole('merchant')){

            $query = CartModel::where('merchant_id',Auth::user()->merchant_id);

            if($type==1):
                // if( !Auth::user()->can('order.quotation') ):
                //     abort(403);
                // endif;
                $query->where('cart_isquotation','1')->where('cart_isinvoice','0');
                $title = __('Order::order.quotation');

            elseif($type==2):
                // if( !Auth::user()->can('order.taxinvoice') ):
                //     abort(403);
                // endif;
                $query->where('cart_isinvoice','1');
                $title = __('Order::order.taxinvoice');

            else:
                // if( !Auth::user()->can('order.index') ):
                //     abort(403);
                // endif;
                $query->where('cart_isinvoice','1')->where('cart_payment_status','1');
                $title = __('Order::order.order');

            endif;

            $list = $type;

            if(Input::has('search') && Input::has('keyword')) {

                $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

            }

            $types = $query->orderBy('updated_at','desc')->paginate(Config::get('constants.common.paginate'));

            return view("Order::quotationindex", ['pagetitle'=>$title, 'pagedesc'=>__('Admin::base.list'), 'types'=>$types, 'list'=>$list]);

        }else{
            abort(403);
        }

    }

    public function showquotation($id)
    {
        // if( !Auth::user()->can('order.showquotation') ):
        //     abort(403);
        // endif;

        $shippingconf = ['uptoweight'=>0, 'uptoprice'=>0, 'addweight'=>0, 'addprice'=>0];
        $data = CartModel::findOrFail(Crypt::decrypt($id));

        if($data->cart_isquotation==1){
            $title = __('Order::order.quotation');
        }else{ //invoice
            $title = __('Order::order.taxinvoice');
        }

        if($data->merchant->configuration->merchant_config_ship_status==1){
            if($data->guest->shippingAddress->state->area_id==1){
                $uptoweight = $data->merchant->configuration->merchant_config_ship_west_upto_weight;
                $uptoprice = $data->merchant->configuration->merchant_config_ship_west_upto_price;
                $addweight = $data->merchant->configuration->merchant_config_ship_west_add_weight;
                $addprice = $data->merchant->configuration->merchant_config_ship_west_add_price;
            }else{
                $uptoweight = $data->merchant->configuration->merchant_config_ship_east_upto_weight;
                $uptoprice = $data->merchant->configuration->merchant_config_ship_east_upto_price;
                $addweight = $data->merchant->configuration->merchant_config_ship_east_add_weight;
                $addprice = $data->merchant->configuration->merchant_config_ship_east_add_price;
            }
            $shippingconf = ['uptoweight'=>$uptoweight, 'uptoprice'=>$uptoprice, 'addweight'=>$addweight, 'addprice'=>$addprice];
        }

        return view("Order::showquotation", ['pagetitle'=>$title, 'pagedesc'=>$data->cart_orderno, 'data'=>$data, 'shippingconf'=>$shippingconf]);

    }

    public function updatequotationstatus(Request $request){

        // if( !Auth::user()->can('order.updatequotation') ):
        //     abort(403);
        // endif;

        DB::beginTransaction();

        try {

            $cartid = $request->input('modal_cartid');
            $type = $request->input('modal_type');
            $cart = CartModel::findOrFail($cartid);

            if($type==1){

                $cart->cart_payment_status = 1;

                if(count($cart->detail)>0){

                    foreach ($cart->detail as $detail) {
                        //Globe::stockmovementfromcart($detail->cart_detail_id);
                    }

                }else{
                    throw new Exception('No item found. Cannot proceed.');
                }

            }elseif($type==2){

                /*$validext = ['jpg','png','jpeg','pdf'];
                $path = public_path().'/uploads/orderpaymentslip/';
                $modelname = 'CartModel';
                $uploadpath = '/uploads/orderpaymentslip/';

                if($request->hasfile('paymentslip')) {

                    if(!empty($cart->paymentslip)){
                        unlink(public_path($cart->paymentslip->upload_path.$cart->paymentslip->upload_filename));
                        UploadModel::where('upload_model_id',$cartid)->where('upload_model',$modelname)->delete();
                    }
                    
                    $file = $request->file('paymentslip');
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    UploadModel::where('upload_model_id',$cartid)->where('upload_model',$modelname)->delete();

                    $name = $cartid.'_'.date('YmdHis').'_slip.'.$ext;
                    $file->move($path, $name);

                    UploadModel::insertData($modelname,$cartid,$uploadpath,$name,'');

                }*/

                if($request->hasfile('paymentslip')) {

                    $validext = ['jpg','png','jpeg','pdf'];
                    $path = public_path().'/uploads/orderpaymentslip/';
                    $modelname = 'CartModel';
                    $uploadpath = '/uploads/orderpaymentslip/';

                    if(count($cart->paymentslips)>0){
                        foreach ($cart->paymentslips as $ps) {
                            unlink(public_path($ps->upload_path.$ps->upload_filename));
                        }
                        UploadModel::where('upload_model_id',$cartid)->where('upload_model',$modelname)->delete();
                    }

                    $i = 1;
                    foreach($request->file('paymentslip') as $file){
                        
                        $ext=$file->getClientOriginalExtension();

                        if(!in_array($ext,$validext)){
                            throw new exception(__('Admin::base.photoformatnotvalid'));
                        }

                        $name = $cartid.'_'.date('YmdHis').'_slip_'.$i.'.'.$ext;
                        $file->move($path, $name);

                        UploadModel::insertData($modelname,$cartid,$uploadpath,$name,'');
                        $i++;  
                    }

                }

            }elseif($type==3){

                if($request->input('trackcode') != ''){
                    $cart->cart_courrierno = trim($request->input('trackcode'));
                    $cart->cart_courrier_status = 1;
                }else{
                    $cart->cart_courrier_status = 0;
                }

            }elseif($type==4){

                $cart->cart_isshipping = $request->input('modal_status');

            }elseif($type==5){

                $cart->cart_remark = $request->input('cart_remark');

            }

            $cart->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            $cart->save();

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Successfully updated!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }

    }

    public function updatequotation(Request $request)
    {
        // if( !Auth::user()->can('order.updatequotation') ):
        //     abort(403);
        // endif;

        DB::beginTransaction();

        try {

            $input = [
                'id'=>$request->input('cart_id'),
            ];

            $rules = [
                'id'=>'required',
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

            $data = CartModel::findOrFail($request->input('cart_id'));

            $action = $request->input('actionstatus');

            if(in_array($action,['1','3'])){

                $data->cart_actual_amount = $request->input('totalamount');
                $data->cart_gst_amount = $request->input('totalgst');
                $data->cart_shipping_amount = $request->input('shippingcost');
                $data->cart_final_amount = $request->input('finalamount');
                $data->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

                if($data->save()){

                    $details = array_filter($request->input('cart_detail_id'));
                    $prices = array_filter($request->input('price'));
                    $qtys = array_filter($request->input('quantity'));
                    $subtotal = array_filter($request->input('subtotal'));

                    if(count($details)>0){
                        foreach($details as $key=>$detail){

                            $child = CartDetailModel::findOrFail($details[$key]);

                            $gstamount = ($subtotal[$key]*$child->cart_detail_gst_percent)/100;

                            if($data->cart_isinvoice == 1){

                                $qtydifferent = $child->cart_detail_quantity-$qtys[$key];

                                $newamt = $gstamount+$subtotal[$key];
                                $different = $child->cart_detail_final_amount-$newamt;
                                $amtdifferent = ($different<0) ? (-1*$different) : $different;

                                Globe::stockmovementfromproduct($child->product_stock_id,$qtydifferent,$amtdifferent,__('Order::order.quantityadjustment'),$child->cart->cart_orderno);

                            }

                            $child->cart_detail_price = $prices[$key];
                            $child->cart_detail_quantity = $qtys[$key];
                            $child->cart_detail_actual_amount = $subtotal[$key];
                            $child->cart_detail_gst_amount = $gstamount;
                            $child->cart_detail_final_amount = $gstamount+$subtotal[$key];
                            $child->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                            $child->save();

                        }
                    }

                }

                if($action == 1){

                    $redirect = 'order/showquotation/'.Crypt::encrypt($request->input('cart_id'));
                    $msg = __('Order::order.msgquotationsuccessupdate');

                }else{

                    $redirect = 'order/showquotation/'.Crypt::encrypt($request->input('cart_id'));
                    $msg = __('Order::order.msginvoicesuccessupdate');

                }

            }elseif(in_array($action,['2','4'])){

                if($action == 2){

                    $inv = new CartModel;
                    $inv->merchant_id = $data->merchant_id;
                    $inv->guest_id = $data->guest_id;
                    $inv->cart_metadata = $data->cart_metadata;
                    //$inv->cart_orderno = Globe::running_no($data->merchant_id,'inv');
                    $inv->cart_orderno = Globe::running_no($data->merchant_id);
                    $inv->cart_isinvoice = 1;
                    $inv->cart_total_weight = $data->cart_total_weight;
                    $inv->cart_shipping_amount = $data->cart_shipping_amount;
                    $inv->cart_actual_amount = $data->cart_actual_amount;
                    $inv->cart_gst_amount = $data->cart_gst_amount;
                    $inv->cart_discount_amount = $data->cart_discount_amount;
                    $inv->cart_voucher_amount = $data->cart_voucher_amount;
                    $inv->cart_final_amount = $data->cart_final_amount;
                    $inv->cart_controller = 'OrderController';
                    $inv->cart_confirm = 1;
                    $inv->created_by = Auth::user()->id;
                    $inv->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $inv->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    
                    if($inv->save()){

                        if(count($data->detail)>0){
                            foreach ($data->detail as $key => $datad) {

                                $invd = new CartDetailModel;
                                $invd->cart_id = $inv->cart_id;
                                $invd->product_stock_id = $datad->product_stock_id;
                                $invd->cart_detail_quantity = $datad->cart_detail_quantity;
                                $invd->cart_detail_price = $datad->cart_detail_price;
                                $invd->cart_detail_weight = $datad->cart_detail_weight;
                                $invd->cart_detail_actual_amount = $datad->cart_detail_actual_amount;
                                $invd->cart_detail_gst_percent = $datad->cart_detail_gst_percent;
                                $invd->cart_detail_gst_amount = $datad->cart_detail_gst_amount;
                                $invd->cart_detail_discount_amount = $datad->cart_detail_discount_amount;
                                $invd->cart_detail_voucher_amount = $datad->cart_detail_voucher_amount;
                                $invd->cart_detail_final_amount = $datad->cart_detail_final_amount;
                                $invd->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                $invd->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                                $invd->save();

                                Globe::stockmovementfromcart($invd->cart_detail_id);

                            }
                        }

                    }

                    $redirect = 'order/quotation';
                    $msg = __('Order::order.msgquotationsuccesssubmit');

                }else{

                    // if(count($data->detail)>0){

                    //     foreach ($data->detail as $detail) {
                    //         Globe::stockmovementfromcart($detail->cart_detail_id);
                    //     }

                    // }else{
                    //     throw new Exception('No item found. Cannot proceed');
                    // }

                    $redirect = 'order/invoice';
                    $msg = __('Order::order.msginvoicesuccesssubmit');
                    
                }

                $data->cart_confirm = 1;
                $data->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $data->save();

            }

            DB::commit();
            return Redirect($redirect)->with('flash_success', $msg);
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

    public function deletecart($id)
    {
        // if( !Auth::user()->can('order.deletecart') ):
        //     abort(403);
        // endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = CartModel::findOrFail($id);

                if($data->cart_isquotation == 1):

                    if(CartDetailModel::where('cart_id',$data->cart_id)->delete()):
                        $data->delete();
                    endif;

                    $msg = 'Data(s) has been deleted!';

                else:

                    if(count($data->detail)>0):

                        foreach($data->detail as $detail):

                            Globe::stockmovementfromproduct($detail->product_stock_id,$detail->cart_detail_quantity,$detail->cart_detail_final_amount,__('Order::order.cancelorder'),$detail->cart->cart_orderno);

                        endforeach;

                    endif;

                    $data->cart_cancel = 1;
                    $data->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $data->save();

                    $msg = 'Data(s) has been cancelled!';

                endif;
                
            else:

                throw new Exception("You didn't select any data to delete");  

            endif;

            DB::commit();
            return Redirect()->back()->with('flash_success', $msg);
            
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $e->getMessage());
        }
    }

    public function catalog()
    {
        if( !Auth::user()->can('order.catalog') ):
            abort(403);
        endif;

        if(Auth::user()->hasRole('merchant')){

            $query = ProductModel::where('merchant_id',Auth::user()->merchant_id)->where('product_submitstatus','1');

            if(Input::has('search') && Input::has('keyword')) {

                $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

            }

            $types = $query->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));

            $catagories = ProductTypeModel::where('merchant_id',Auth::user()->merchant_id)->get();

            $guests = UserGuestModel::where('merchant_id',Auth::user()->merchant_id)->get();

            $countcart = 1;
            if(Cart::isEmpty()){
                $countcart = 0;
            }

            return view("Order::catalogindex", ['pagetitle'=>__('Order::order.catalog'), 'pagedesc'=>__('Admin::base.list'), 'catagories'=>$catagories, 'types'=>$types, 'guests'=>$guests, 'countcart'=>$countcart]);

        }else{
            abort(403);
        }
    }

    public function catalogdetail($productid)
    {
        if( !Auth::user()->can('order.catalog') ):
            abort(403);
        endif;

        $product = ProductModel::findOrFail(Crypt::decrypt($productid));
        $guests = UserGuestModel::where('merchant_id',$product->merchant_id)->get();
        $activecart = CartModel::where('merchant_id',$product->merchant_id)->where('cart_controller','OrderController')->where('cart_isactive','1')->first();

        return view("Order::catalogdetail", ['pagetitle'=>__('Order::order.productdetail'), 'pagedesc'=>__('Order::order.product'), 'product'=>$product, 'guests'=>$guests, 'activecart'=>$activecart]);
        //return view("Order::catalogdetail", ['pagetitle'=>'', 'pagedesc'=>'', 'product'=>$product, 'guests'=>$guests]);

    }

    public function getGuestAddress($id){

        $address = UserGuestAddressModel::where('guest_id',$id)->pluck('guest_address_one','guest_address_id');
        //->select('guest_address_id', DB::raw('SUM(price) as total_sales'))
        //guest_address_one
        //guest_address_two
        //guest_address_three
        //district
        //state
        //postcode

        return json_encode($address);

    }

    public function createcart(Request $request)
    {
        if( !Auth::user()->can('order.addtocart') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'merchant'=>$request->input('modal_merchantid'),
                'customer'=>$request->input('guest_id'),
                'stock'=>$request->input('modal_stockid'),
                'quantity'=>$request->input('modal_quantity'),
                'type'=>$request->input('typeid'),
            ];

            $rules = [
                'merchant'=>'required',
                'customer'=>'required',
                'stock'=>'required',
                'quantity'=>'required',
                'type'=>'required',
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

            CartModel::where('merchant_id',$request->input('modal_merchantid'))->where('cart_controller','OrderController')->update(['cart_isactive'=>0]);

            $cart = new CartModel;
            $cart->merchant_id = $request->input('modal_merchantid');
            $cart->guest_id = $request->input('guest_id');
            $cart->cart_orderno = 'ORD'.$request->input('modal_merchantid').date('YmdHis');

            if($request->input('typeid') == '1'){
                $cart->cart_isquotation = 1;
                $cart->cart_isinvoice = 0;
            }else{
                $cart->cart_isquotation = 0;
                $cart->cart_isinvoice = 1;
            }
            $cart->cart_controller = 'OrderController';
            $cart->cart_isactive = 1;
            $cart->cart_confirm = 0;
            $cart->created_by = Auth::user()->id;
            $cart->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            $cart->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

            if($cart->save()){

                $stock = ProductStockModel::findOrFail($request->input('modal_stockid'));
                $tax = $stock->product->taxpurchase->tax_charge;
                $gst = $tax*$stock->product_stock_sale_price/100;
                $quantity = $request->input('modal_quantity');
                $weight = $stock->product_stock_weight*$quantity;
                $actualamount = $stock->product_stock_sale_price*$quantity;
                $gstamount = $gst*$quantity;
                $finalamount = $actualamount+$gstamount;

                $detail = new CartDetailModel;
                $detail->cart_id = $cart->cart_id;
                $detail->product_stock_id = $stock->product_stock_id;
                $detail->cart_detail_quantity = $quantity;
                $detail->cart_detail_weight = $weight;
                $detail->cart_detail_actual_amount = $actualamount;
                $detail->cart_detail_gst_amount = $gstamount;
                $detail->cart_detail_final_amount = $finalamount;

                if($detail->save()){

                    $cart2 = CartModel::findOrFail($cart->cart_id);
                    $cart2->cart_total_weight = $weight;
                    $cart2->cart_actual_amount = $actualamount;
                    $cart2->cart_gst_amount = $gstamount;
                    $cart2->cart_final_amount = $finalamount;
                    $cart2->save();

                }

            }

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Cart successfully created!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
        }
    }

    public function getActiveCart($stockid,$qty,$merchantid){

        $cart = CartModel::where('merchant_id',$merchantid)->where('cart_controller','OrderController')->where('cart_isactive','1')->first();
        $html = '';
        $result = '';
        $item = 0;

        if(!empty($cart)){

            $stock = ProductStockModel::findOrFail($stockid);
            $cartdetail = CartDetailModel::where('cart_id',$cart->cart_id)->where('product_stock_id',$stockid)->first();
            $tax = $stock->product->taxpurchase->tax_charge;
            $gst = $tax*$stock->product_stock_sale_price/100;

            if(!empty($cartdetail)){

                $quantity = $cartdetail->cart_detail_quantity+$qty;
                $detail = CartDetailModel::findOrFail($cartdetail->cart_detail_id);

            }else{

                $quantity = $qty;
                
                $detail = new CartDetailModel;
                $detail->cart_id = $cart->cart_id;
                $detail->product_stock_id = $stockid;

            }

            $weight = $stock->product_stock_weight*$quantity;
            $actualamount = $stock->product_stock_sale_price*$quantity;
            $gstamount = $gst*$quantity;
            $finalamount = $actualamount+$gstamount;

            $detail->cart_detail_quantity = $quantity;
            $detail->cart_detail_weight = $weight;
            $detail->cart_detail_actual_amount = $actualamount;
            $detail->cart_detail_gst_amount = $gstamount;
            $detail->cart_detail_final_amount = $finalamount;
            
            if($detail->save()){

                $main = CartModel::findOrFail($cart->cart_id);
                $mainweight = 0;
                $mainactual = 0;
                $maingst = 0;
                $mainfinal = 0;
                foreach ($main->detail as $key => $dt) {
                    $mainweight += $dt->cart_detail_weight;
                    $mainactual += $dt->cart_detail_actual_amount;
                    $maingst += $dt->cart_detail_gst_amount;
                    $mainfinal += $dt->cart_detail_final_amount;
                }

                $main->cart_total_weight = $mainweight;
                $main->cart_actual_amount = $mainactual;
                $main->cart_gst_amount = $maingst;
                $main->cart_final_amount = $mainfinal;
                $main->save();

                $item = $key+1;

            }

            $result = 1;

        }else{

            //$cart2 = CartModel::where('merchant_id',$merchantid)->where('cart_controller','OrderController')->where('cart_isactive','0')->where('cart_confirm','0')->get();
            $cart2 = CartModel::where('merchant_id',$merchantid)->where('cart_controller','OrderController')->where('cart_confirm','0')->get();

            if(count($cart2)>0){

                //$html .= '<div class="form-group"><label class="control-label col-md-1 col-sm-1 col-xs-12">&nbsp;</label><div class="col-md-10 col-sm-10 col-xs-12"><a href="javascript:;" class="btn btn-m btn-primary newcartform"><i class="fa fa-shopping-cart"></i>'.__('Order::order.createnewcart').'</a></div></div>';

                foreach ($cart2 as $key => $c) {

                    $html .= '<div class="form-group">';

                    // if($key==0){
                    //     $html .= '<label class="control-label col-md-3 col-sm-3 col-xs-12">'.__('Order::order.existingcart').'</label>';
                    // }else{
                    //     $html .= '<label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>';
                    // }
                    $html .= '<label class="control-label col-md-1 col-sm-1 col-xs-12">&nbsp;</label>';

                    $html .= '<div class="col-md-10 col-sm-10 col-xs-12">';
                    if($c->cart_isactive == 0){
                        $html .= '<a data-toggle="tooltip" data-askmsg="'.__('Admin::base.askusable').'" class="btn btn-m btn-default enabledata" value="'.route('order.activatecart', $c->cart_id).'"><i class="fa fa-exclamation-circle "></i>';
                    }else{
                        $html .= '<a data-toggle="tooltip" class="btn btn-m btn-success" value=""><i class="fa fa-check-circle"></i>';
                    }

                    if($c->cart_isquotation==0){
                        $type = __('Order::order.taxinvoice');
                    }else{
                        $type = __('Order::order.quotation');
                    }

                    $html .= '  ['.$type.': '.$c->cart_orderno.']['.$c->guest->guest_fullname.']['.$c->guest->email.']</a></div></div>';

                }

                $result = 2;

            }else{

                $result = 3;

            }

        }

        return json_encode(['cart'=>$cart, 'result'=>$result, 'html'=>$html, 'item'=>$item]);

    }

    public function activatecart($id) {
        
        if( !Auth::user()->can('order.activatecart') ):
            abort(403);
        endif;
            
        DB::beginTransaction();
        try {
            
            if(!empty($id)):

                $cart = CartModel::findOrFail($id);

                CartModel::where('merchant_id',$cart->merchant_id)->where('cart_controller','OrderController')->update(['cart_isactive'=>0]);

                $cart->cart_isactive = 1;
                $cart->save();
                
            else:
                throw new Exception("You didn't select any cart to activate");                
            endif;

            DB::commit();
            return Redirect::back()->with('flash_success', 'Cart has been activated!');

        } catch (Exception $error) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $error->getMessage());
        }       
    }

    public function getcartlistformodal($id){

        //$cart = CartModel::where('merchant_id',$id)->where('cart_controller','OrderController')->where('cart_isactive','0')->where('cart_confirm','0')->get();
        $cart = CartModel::where('merchant_id',$id)->where('cart_controller','OrderController')->where('cart_confirm','0')->get();
        $html = '';
        //$html = '<div class="form-group"><label class="control-label col-md-1 col-sm-1 col-xs-12">&nbsp;</label><div class="col-md-10 col-sm-10 col-xs-12"><a href="javascript:;" class="btn btn-m btn-primary newcartform"><i class="fa fa-shopping-cart"></i>'.__('Order::order.createnewcart').'</a></div></div>';

        if(count($cart)>0){

            foreach ($cart as $key => $c) {

                $html .= '<div class="form-group">';

                // if($key==0){
                //     $html .= '<label class="control-label col-md-3 col-sm-3 col-xs-12">'.__('Order::order.existingcart').'</label>';
                // }else{
                //     $html .= '<label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>';
                // }
                $html .= '<label class="control-label col-md-1 col-sm-1 col-xs-12">&nbsp;</label>';

                $html .= '<div class="col-md-10 col-sm-10 col-xs-12">';
                if($c->cart_isactive == 0){
                    $html .= '<a data-toggle="tooltip" data-askmsg="'.__('Admin::base.askusable').'" class="btn btn-m btn-default enabledata" value="'.route('order.activatecart', $c->cart_id).'"><i class="fa fa-exclamation-circle "></i>';
                }else{
                    $html .= '<a data-toggle="tooltip" class="btn btn-m btn-success" value=""><i class="fa fa-check-circle"></i>';
                }

                if($c->cart_isquotation==0){
                    $type = __('Order::order.taxinvoice');
                }else{
                    $type = __('Order::order.quotation');
                }

                $html .= '  ['.$type.': '.$c->cart_orderno.']['.$c->guest->guest_fullname.']['.$c->guest->email.']</a></div></div>';

            }
        }

        return json_encode($html);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return self::sharequotationinvoicelist('3');

    }

    public function getorderinfo(Request $request){

        $html = '';
        $cartid = $request->cartid;
        $cart = CartModel::findOrFail($cartid);
        
        $divstart = '<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">';
        $divmid = '</label><div class="col-md-7 col-sm-7 col-xs-12"><input class="form-control" type="text" value="';
        $divend = '" disabled></div></div>';

        $html .= $divstart.__('Order::order.orderno').$divmid.$cart->cart_orderno.$divend;
        $html .= $divstart.__('Admin::base.email').$divmid.$cart->guest->email.$divend;
        $html .= $divstart.__('Order::order.customer').$divmid.$cart->guest->guest_fullname.$divend;

        if(count($cart->detail)>0){

            $html .= '<div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12"><table class="table table-bordered"><tbody><tr bgcolor="#172f93"><th width="7%"><font color="#FFFFFF">#</font></th><th><font color="#FFFFFF">'.__('Product::product.product').'</font></th><th class="text-center" width="20%"><font color="#FFFFFF">'.__('Order::order.quantity').'</font></th></tr>';

            $i = 1;

            foreach ($cart->detail as $key => $item) {

                $html .= '<tr><td>'.($i++).'</td><td>'.$item->stock->product->product_name.' - '.$item->stock->product_stock_description.'</td><td class="text-right">'.number_format($item->cart_detail_quantity).'</td></tr>';

            }

            $html .= '</tbody></table></div></div>';

        }             

        return json_encode($html);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Order::create", ['pagetitle'=>__('Order::order.createorder'), 'pagedesc'=>__('Order::order.order')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->can('order.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'person_in_charge'=>$request->input('merchant_person_incharge'),
            ];

            $rules = [
                'person_in_charge'=>'required|max:100',
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

            DB::commit();
            return Redirect('order')->with('flash_success', 'Successfully!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('order/create')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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
        //$order = OrderModel::findOrFail($id);

        return view("Order::create", ['pagetitle'=>__('Order::order.orderdetail'), 'pagedesc'=>__('Order::order.order'), 'order'=>$order]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( !Auth::user()->can('order.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = MerchantModel::findOrFail($id);

                if($data->delete()):
                    //
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

    public function printdoc($id,$type){

        Globe::printreceipt(Crypt::decrypt($id),$type);

    }

    public function getaddress($id,$type){

        $guest = UserGuestModel::findOrFail($id);

        $html = '<tr>
                    <th>'.__('Frontend::frontend.my_address').'</th>
                    <th class="text-center" width="20%">'.__('Order::order.contactno').'</th>
                    <th class="text-center" width="20%">Label</th>
                </tr>';

        if($type == 'ship'):
            if(count($guest->shippingAddresses)>0):
                foreach ($guest->shippingAddresses as $key => $address):
                    
                    $html .= '<tr><td>';
                    if($address->guest_address_one!=''):
                        $html .= $address->guest_address_one.'<br>';
                    endif;
                    if($address->guest_address_two!=''):
                        $html .= $address->guest_address_two.'<br>';
                    endif;
                    if($address->guest_addres_three!=''):
                        $html .= $address->guest_addres_three.'<br>';
                    endif;
                    $html .= $address->guest_address_postcode.' '.$address->district->district_desc.'<br>';
                    $html .= $address->state->state_desc;
                    $html .= '</td><td class="text-center">'.$address->guest_address_phone.'</td>';
                    $html .= '<td class="text-center"><a data-id="'.$address->guest_address_id.'" data-type="shipping" class="btn btn-xs btn-primary chooseaddress">'.__('Admin::base.choose').'</a></td>';
                    $html .= '</tr>';

                endforeach;
            endif;
        else:
            if(count($guest->billingAddresses)>0):
                foreach ($guest->billingAddresses as $key => $address):
                    
                    $html .= '<tr><td>';
                    if($address->guest_address_one!=''):
                        $html .= $address->guest_address_one.'<br>';
                    endif;
                    if($address->guest_address_two!=''):
                        $html .= $address->guest_address_two.'<br>';
                    endif;
                    if($address->guest_addres_three!=''):
                        $html .= $address->guest_addres_three.'<br>';
                    endif;
                    $html .= $address->guest_address_postcode.' '.$address->district->district_desc.'<br>';
                    $html .= $address->state->state_desc;
                    $html .= '</td><td class="text-center">'.$address->guest_address_phone.'</td>';
                    $html .= '<td class="text-center"><a data-id="'.$address->guest_address_id.'" data-type="billing" class="btn btn-xs btn-primary chooseaddress">'.__('Admin::base.choose').'</a></td>';
                    $html .= '</tr>';

                endforeach;
            endif;
        endif;

        return json_encode($html);

    }

    public function chooseaddress($id,$type,$cartid){

        $cart = CartModel::findOrFail($cartid);
        $newaddress = UserGuestAddressModel::findOrFail($id);

        $addressname = '<strong>'.$newaddress->guest_address_name.'</strong><br>';
        $addresscontact = __('Order::order.contactno').': '.$newaddress->guest_address_phone.'<br>';
        if($type == 'billing'){
            $addresscontact .= __('Admin::base.email').': '.$newaddress->owner->email;
        }

        $newaddresstxt = $newaddress->guest_address_one.'<br>';
        if($newaddress->guest_address_two != ''){
            $newaddresstxt .= $newaddress->guest_address_two.'<br>';
        }
        if($newaddress->guest_address_three != ''){
            $newaddresstxt .= $newaddress->guest_address_three.'<br>';
        }
        $newaddresstxt .= $newaddress->guest_address_postcode.' '.$newaddress->district->district_desc.'<br>';
        $newaddresstxt .= $newaddress->state->state_desc;

        if($cart->cart_metadata!=''){

            if($type == 'shipping'){

                $meta = [   'shipping' => ['name'=>$newaddress->guest_address_name,
                                            'phone'=>$newaddress->guest_address_phone,
                                            'address' => $newaddresstxt
                                       ],

                            'billing'=>['name'=>Globe::readMeta($cart->cart_metadata, 'billing')['name'],
                                            'phone'=>Globe::readMeta($cart->cart_metadata, 'billing')['phone'],
                                            'address' => Globe::readMeta($cart->cart_metadata, 'billing')['address']
                                        ],

                            'payment_type'=> Globe::readMeta($cart->cart_metadata, 'payment_type')
                        ];
            }else{
                $meta = [   'shipping'=>['name'=>Globe::readMeta($cart->cart_metadata, 'billing')['name'],
                                            'phone'=>Globe::readMeta($cart->cart_metadata, 'billing')['phone'],
                                            'address' => Globe::readMeta($cart->cart_metadata, 'billing')['address']
                                        ],
                            'billing' => ['name'=>$newaddress->guest_address_name,
                                            'phone'=>$newaddress->guest_address_phone,
                                            'address' => $newaddresstxt
                                       ],

                            'payment_type'=> Globe::readMeta($cart->cart_metadata, 'payment_type')
                        ];
            }

        }else{

            if($type == 'shipping'){

                $meta = [   'shipping' => ['name'=>$newaddress->guest_address_name,
                                            'phone'=>$newaddress->guest_address_phone,
                                            'address' => $newaddresstxt
                                       ],

                            'billing'=>['name'=>'','phone'=>'','address' =>''],

                            'payment_type'=> Globe::readMeta($cart->cart_metadata, 'payment_type')
                        ];
            }else{
                $meta = [   'shipping'=>['name'=>'','phone'=>'','address' =>''],

                            'billing' => ['name'=>$newaddress->guest_address_name,
                                            'phone'=>$newaddress->guest_address_phone,
                                            'address' => $newaddresstxt
                                       ],

                            'payment_type'=> 'direct'
                        ];
            }

        }

        $metadata = serialize($meta);

        $cart->cart_metadata = $metadata;
        $cart->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $cart->save();

        $html = strtoupper($addressname.$newaddresstxt).'<br>'.$addresscontact;

        return json_encode($html);

    }

    public function choosedraftaddress($id,$type){

        if($type == 'shipping'){
            Session::put('activeshipaddress', $id);
        }else{
            Session::put('activebilladdress', $id);
        }

        return json_encode(1);

    }

}
