@extends('layouts.adminLTE.master')

@section('header')
<script src="https://cdn.ckeditor.com/4.9.2/standard-all/ckeditor.js"></script>
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Order::order.orderdetail') }}</h3>
            </div>
            {!! Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@quotation', 'method'=>'post', 'role'=>'form', 'id'=>'pageform' ]) !!}
            <input type="hidden" name="cart_id" id="cart_id" value="{!! $data->cart_id !!}">
            <input type="hidden" name="merchant_id" id="merchant_id" value="{!! ($data->merchant_id != '') ? $data->merchant_id : Crypt::decrypt($merchantid) !!}">
            <div class="box-body">
                
                <div class="form-group">
                    <label for="cart_orderno">{{ __('Order::order.orderno') }}</label>
                    <input class="form-control" type="text" id="cart_orderno" value="{{ $data->cart_orderno }}" disabled />
                </div>
                <div class="form-group">
                    <label for="guest_fullname">{{ __('Order::order.customer') }}</label>
                    <input class="form-control" type="text" id="guest_fullname" value="{{ $data->guest->guest_fullname }}" disabled />
                </div>
                <div class="form-group">
                    <label for="email">{{ __('Admin::base.email') }}</label>
                    <input class="form-control" type="text" id="email" value="{{ $data->guest->email }}" disabled />
                </div>
                <div class="form-group">
                    <label for="cart_remark">{{ __('Admin::base.remark') }}</label>
                    <textarea cols="80" id="cart_remark" name="cart_remark">{{ ($data->cart_remark != '') ? $data->cart_remark : old('cart_remark') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="complaint_message">{{ __('Order::order.shippingcost') }}?</label>
                    <div class="checkbox checbox-switch switch-primary">
                        <label>
                            <input type="checkbox" class="form-control" name="cart_isshipping" id="cart_isshipping" value="1" {{ ($data->cart_isshipping=='1') ? 'checked' : '' }} />
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--div class="col-md-6">
        <div class="box box-default">
            <div class="box-body">
                <div class="form-group">
                    <label for="cart_remark">{{ __('Admin::base.remark') }}</label>
                    <textarea cols="80" id="cart_remark" name="cart_remark">{{ ($data->cart_remark != '') ? $data->cart_remark : old('cart_remark') }}</textarea>
                </div>
            </div>
        </div>
    </div-->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">{{ __('Order::order.itemlist') }}</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>{{ __('Product::Product.product') }}</th>
                                <th>{{ __('Order::order.quantity') }}</th>
                                <th>{{ __('Order::order.weight') }}</th>
                                <th>{{ __('Order::order.totalamount') }}</th>
                                <th>{{ __('Order::order.gst') }}</th>
                                <th>{{ __('Order::order.amount') }}</th>
                                <th class="text-center" width="5%">&nbsp;</th>
                            </tr>
                            @if(count($data->detail) > 0)
                            @foreach($data->detail as $key=>$detail)
                            <tr>
                                <td>{{ $detail->stock->product->product_name.' - '.$detail->stock->product_stock_description }}</td>
                                <td><input name="cart_detail_quantity[]" id="cart_detail_quantity{{ ($key+1) }}" value="{{ $detail->cart_detail_quantity }}"></td>
                                <td class="text-right">
                                    <input name="cart_detail_weight[]" id="cart_detail_weight{{ ($key+1) }}" value="{{ number_format($detail->cart_detail_weight,2) }}">
                                </td>
                                <td class="text-right">
                                    <input name="cart_detail_actual_amount[]" id="cart_detail_actual_amount{{ ($key+1) }}" value="{{ number_format($detail->cart_detail_actual_amount,2) }}">
                                </td>
                                <td class="text-right">
                                    <input name="cart_detail_gst_amount[]" id="cart_detail_gst_amount{{ ($key+1) }}" value="{{ number_format($detail->cart_detail_gst_amount,2) }}">
                                </td>
                                <td class="text-right">
                                    <input name="cart_detail_final_amount[]" id="cart_detail_final_amount{{ ($key+1) }}" value="{{ number_format($detail->cart_detail_final_amount,2) }}">
                                </td>
                                <td class="text-center">XX</td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="8">No result(s)</td></tr>
                            @endif
                            <tr>
                                <td class="text-right"><b>{{ __('Order::order.total') }}</b></td>
                                <td><input id="totalquantity" value="0"></td>
                                <td class="text-right">
                                    <input name="cart_total_weight" id="cart_total_weight" value="{{ number_format($data->cart_total_weight,2) }}" readonly>
                                </td>
                                <td class="text-right">
                                    <input name="cart_actual_amount" id="cart_actual_amount" value="{{ number_format($data->cart_actual_amount,2) }}" readonly>
                                </td>
                                <td class="text-right">
                                    <input name="cart_gst_amount" id="cart_gst_amount" value="{{ number_format($data->cart_gst_amount,2) }}">
                                </td>
                                <td class="text-right">
                                    <input name="cart_final_amount" id="cart_final_amount" value="{{ number_format($data->cart_final_amount,2) }}">
                                </td>
                                <td class="text-center">XX</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>{{ __('Order::order.discount') }}</b></td>
                                <td class="text-right">
                                    <input name="cart_discount_amount" id="cart_discount_amount" value="{{ number_format($data->cart_discount_amount,2) }}">
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>{{ __('Order::order.shippingcost') }}</b></td>
                                <td class="text-right">
                                    <input name="cart_shipping_amount" id="cart_shipping_amount" value="{{ number_format($data->cart_shipping_amount,2) }}">
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><b>{{ __('Order::order.finalamount') }}</b></td>
                                <td class="text-right">
                                    <input name="cart_final_amount" id="cart_final_amount" value="{{ number_format($data->cart_final_amount,2) }}">
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group pull-right">
                    <a href="{{ URL::to('order/quotation') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
                    {{-- @if(Auth::user()->can('merchant.updatequotation')) --}}
                        <button type="button" class="btn btn-sm btn-primary submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.update') }}</button>
                    {{-- @endif --}}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('footer')

<script type="text/javascript">

$(document).ready(function() {

    
});

    $('.submitform').on('click', function() {

        if($("#merchant_page_title").val()=='' || $("#merchant_page_order").val()==''){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
            return false;
        }

        swal({
        title: '{{ __("Admin::base.confirmsubmission") }}',
        //text: "{{ __('Admin::base.inadjustable') }}",
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: '{{ __("Admin::base.cancel") }}',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ __("Admin::base.yes") }}',
      
        preConfirm: function() {
            return new Promise(function(resolve) {

                $('#pageform').submit();

            });
        },

        }).then(function () {
            swal(
              '{{ __("Admin::base.success") }}!',
              '',
              'success'
            )
        });

    });

</script>
@stop