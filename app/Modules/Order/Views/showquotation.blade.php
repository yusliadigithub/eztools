@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

{!! Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@updatequotation', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1']) !!}
<section class="invoice">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> {{ $data->guest->merchant->merchant_name }}
                <small class="pull-right">Date: {{ date('d/m/Y') }}</small>
            </h2>
        </div>
    </div>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <strong>{{-- __('Admin::base.referenceno').': '.$data->cart_orderno --}}[{{ $data->cart_orderno }}]</strong><br>
            {!! __('Admin::base.from') !!}
            <address>
            {{ $data->guest->merchant->merchant_name }}<br>
            {!! ($data->guest->merchant->configuration->merchant_config_address1!='') ? $data->guest->merchant->configuration->merchant_config_address1.'<br>' : '' !!}
            {!! ($data->guest->merchant->configuration->merchant_config_address2!='') ? $data->guest->merchant->configuration->merchant_config_address2.'<br>' : '' !!}
            {!! ($data->guest->merchant->configuration->merchant_config_address3!='') ? $data->guest->merchant->configuration->merchant_config_address3.'<br>' : '' !!}
            {!! $data->guest->merchant->configuration->merchant_config_postcode.', '.$data->guest->merchant->configuration->district->district_desc.'<br>' !!}
            {!! $data->guest->merchant->configuration->state->state_desc.'<br>' !!}
            {!! __('Order::order.contactno').': '.$data->guest->merchant->configuration->merchant_config_mobileno.'<br>' !!}
            {!! __('Admin::base.email').': '.$data->guest->merchant->configuration->merchant_config_email !!}
            </address>
        </div>
        <div class="col-sm-4 invoice-col">{!! __('Frontend::frontend.billing_address') !!}
            <address id="addressbill">
            @if($data->cart_metadata!='')
            <strong>{{ strtoupper(Globe::readMeta($data->cart_metadata, 'billing')['name']) }}</strong><br>
            {!! strtoupper(Globe::readMeta($data->cart_metadata, 'billing')['address']) !!}
            {!! '<br>'.__('Order::order.contactno').': '.Globe::readMeta($data->cart_metadata, 'billing')['phone'].'<br>' !!}
            {!! __('Admin::base.email').': '.$data->guest->email !!}
            @endif
            </address>
            <a data-toggle="tooltip" title="{{ __('Order::order.changebillingaddress') }}" data-id="{{ $data->guest->guest_id }}" data-type="bill" class="btn btn-xs btn-default changeaddress">Change</a>
        </div>

        <div class="col-sm-4 invoice-col">{!! __('Frontend::frontend.shipping_address') !!}
            <address id="addressship">
            @if($data->cart_metadata!='')
            <strong>{{ strtoupper(Globe::readMeta($data->cart_metadata, 'shipping')['name']) }}</strong><br>
            {!! strtoupper(Globe::readMeta($data->cart_metadata, 'shipping')['address']) !!}
            {!! '<br>'.__('Order::order.contactno').': '.Globe::readMeta($data->cart_metadata, 'shipping')['phone'].'<br>' !!}
            @endif
            </address>
            <a data-toggle="tooltip" title="{{ __('Order::order.changeshippingaddress') }}" data-id="{{ $data->guest->guest_id }}" data-type="ship" class="btn btn-xs btn-default changeaddress">Change</a>
        </div>
    </div>

    <hr>

    @if($data->cart_confirm==1 && $data->cart_isinvoice==1)
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr bgcolor="#172f93">
                        <th class="text-center" width="25%">
                            <font color="#FFFFFF">{!! __('Admin::base.remark').'?' !!}</font>
                            &nbsp;&nbsp;<a data-id="5" class="btn btn-xs btn-default updatestatus"> {{ __('Admin::base.update') }}</a>
                        </th>
                        <th class="text-center" width="20%">
                            <font color="#FFFFFF">{!! __('Order::order.paymentslip') !!}
                            @if($data->cart_courrier_status==0 && $data->cart_payment_status==0 && $data->cart_cancel==0)
                            &nbsp;&nbsp;<a data-id="2" class="btn btn-xs btn-default updatestatus"><i class="fa fa-file"></i> {{ __('Order::order.attachfile') }}</a></font>
                            @endif
                        </th>
                        <th class="text-center" width="20%">
                            <font color="#FFFFFF">{!! __('Order::order.paymentstatus') !!}</font>
                            @if($data->cart_courrier_status==0 && count($data->paymentslips)>0 && $data->cart_payment_status==0 && $data->cart_cancel==0)
                            &nbsp;&nbsp;<a data-id="1" class="btn btn-xs btn-default updatestatuspay"> {{ __('Admin::base.update') }}</a>
                            @endif
                        </th>
                        <th class="text-center" width="18%">
                            <font color="#FFFFFF">{!! __('Order::order.trackcodeno') !!}</font>
                            @if($data->cart_payment_status==1 && $data->cart_isshipping==0 && $data->cart_cancel==0)
                            &nbsp;&nbsp;<a data-id="3" class="btn btn-xs btn-default updatestatus"> {{ __('Admin::base.update') }}</a>
                            @endif
                        </th>
                        <th class="text-center" width="17%">
                            <font color="#FFFFFF">{!! __('Order::order.delivered').'?' !!}</font>
                            @if($data->cart_payment_status==1 && $data->cart_isshipping==0 && $data->cart_courrier_status==1 && $data->cart_cancel==0)
                            &nbsp;&nbsp;<a data-id="4" class="btn btn-xs btn-default updatestatus"> {{ __('Admin::base.update') }}</a>
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <td class="text-center" id="remarktd">
                            <textarea readonly>{{ $data->cart_remark }}</textarea>
                        </td>
                        <td id="paymentsliptd">
                            @if(count($data->paymentslips)>0)
                                @foreach($data->paymentslips as $ps)
                                    <a href="{!! url($ps->upload_path.$ps->upload_filename) !!}" target="_blank">{!! $ps->upload_filename !!}</a>
                                @endforeach
                            @else
                                {!! '<font color="red">'.__('Order::order.noattachment').'</font>' !!}
                            @endif
                        </td>
                        <td class="text-center" id="paymentstatustd">
                            @if($data->cart_payment_status==1)
                                {{ __('Order::order.paid') }}
                            @else
                                {!! '<font color="red">'.__('Order::order.pending').'</font>' !!}
                            @endif
                        </td>
                        <td class="text-center" id="tracknotd">
                            <b>{!! ($data->cart_courrierno != '') ? $data->cart_courrierno : '<font color="red">N/A</font>' !!}</b>
                        </td>
                        <td class="text-center" id="shippingstatustd">
                            @if($data->cart_isshipping==1)
                                {{ __('Order::order.done') }}
                            @else
                                @if($data->cart_courrier_status==1)
                                {!! '<font color="red">'.__('Order::order.shipped').'</font>' !!}
                                @else
                                {!! '<b><font color="red">N/A</font></b>' !!}
                                @endif
                            @endif
                        </td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    @endif

    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr bgcolor="#172f93">
                        <th width="3%"><font color="#FFFFFF">#</font></th><th><font color="#FFFFFF">{!! __('Product::product.product') !!}</font></th>
                        <th class="text-center" width="12%"><font color="#FFFFFF">{!! __('Order::order.price') !!}</font></th>
                        <th class="text-center" width="10%"><font color="#FFFFFF">GST (%)</font></th>
                        <th class="text-center" width="10%"><font color="#FFFFFF">{!! __('Order::order.weight') !!}</font></th>
                        <th class="text-center" width="10%"><font color="#FFFFFF">{!! __('Order::order.quantity') !!}</font></th>
                        <th class="text-center" width="10%"><font color="#FFFFFF">Subtotal</font></th>
                        @if($data->cart_confirm==0)
                        <th width="5%">&nbsp;</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(count($data->detail)>0)
                        <?php 
                            $totalweight = 0;
                            $totalamount = 0;
                            $totalgst = 0; 
                        ?>
                        @foreach($data->detail as $key=>$detail)
                            <?php 
                                $itemcost = Globe::itemcost($detail->product_stock_id,$detail->cart_detail_quantity);
                                $totalweight += $itemcost['weight'];
                                $totalamount += $itemcost['amount'];
                                $totalgst += $itemcost['gstcost'];
                            ?>
                            <tr id="itemtr{{ $detail->cart_detail_id }}">
                                <td>{{ ($key+1) }}</td>
                                <td>{{ $detail->stock->product->product_name.' - '.$detail->stock->product_stock_description }}</td>
                                <td class="text-right">
                                    @if($data->cart_confirm==0)
                                    <input class="calculate" type="text" name="price[]" id="price{{ ($key+1) }}" value="{{ $detail->cart_detail_price }}">
                                    @else
                                    {{ number_format($detail->cart_detail_price,2) }}
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($itemcost['gst'],2) }}</td>
                                <td class="text-right">{{ number_format($detail->cart_detail_weight,2) }}</td>
                                <td class="text-right">
                                    @if($data->cart_confirm==0)
                                    <input class="calculate" type="text" name="quantity[]" id="quantity{{ ($key+1) }}" value="{{ $detail->cart_detail_quantity }}">
                                    @else
                                    {{ number_format($detail->cart_detail_quantity) }}
                                    @endif
                                </td>
                                <td class="text-right effectedtd" id="subtotaltd{{ ($key+1) }}">{{ Globe::moneyFormat($detail->cart_detail_actual_amount,2) }}</td>
                                @if($data->cart_confirm==0)
                                <td class="text-center"><a data-toggle="tooltip" data-id="{{ $detail->cart_detail_id }}" class="btn btn-xs btn-danger removecartitem"><i class="fa fa-times-circle"></i></a></td>
                                @endif
                                <input type="hidden" name="cart_detail_id[]" value="{{ $detail->cart_detail_id }}">
                                <input type="hidden" id="gst{{ ($key+1) }}" value="{{ $itemcost['gst'] }}">
                                <input type="hidden" id="weight{{ ($key+1) }}" value="{{ $detail->cart_detail_weight }}">
                                <input type="hidden" name="subtotal[]" id="subtotal{{ ($key+1) }}" value="{{ $detail->cart_detail_actual_amount }}">
                            </tr>
                        @endforeach
                    <?php 
                        $shippingcost = Globe::shippingcost($totalweight,'1',$data->guest->merchant_id);
                        $finalamount = $shippingcost+$totalamount+$totalgst;
                    ?>
                    <input type="hidden" name="totalrow" id="totalrow" value="{{ $key+1 }}">
                    <input type="hidden" name="cart_id" value="{{ $data->cart_id }}">
                    <input type="hidden" id="uptoweight" value="{{ $shippingconf['uptoweight'] }}">
                    <input type="hidden" id="uptoprice" value="{{ $shippingconf['uptoprice'] }}">
                    <input type="hidden" id="addweight" value="{{ $shippingconf['addweight'] }}">
                    <input type="hidden" id="addprice" value="{{ $shippingconf['addprice'] }}">
                    <input type="hidden" name="actionstatus" id="actionstatus" value="">
                    <tr>
                        <td class="text-right" colspan="6"><b>{{ __('Order::order.totalamount') }}</b></td>
                        <td class="text-right effectedtd" id="totalamounttd">{{ Globe::moneyFormat($data->cart_actual_amount,2) }}</td>
                        @if($data->cart_confirm==0)
                        <td class="text-right"><input type="hidden" name="totalamount" id="totalamount" value="{{ $data->cart_actual_amount }}"></td>
                        @endif
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><b>{{ __('Order::order.totalgst') }}</b></td>
                        <td class="text-right effectedtd" id="totalgsttd">{{ Globe::moneyFormat($data->cart_gst_amount,2) }}</td>
                        @if($data->cart_confirm==0)
                        <td class="text-right"><input type="hidden" name="totalgst" id="totalgst" value="{{ $data->cart_gst_amount }}"></td>
                        @endif
                    </tr>
                    @if($data->cart_confirm==1)
                    <tr>
                        <td class="text-right" colspan="6"><b>{{ __('Order::order.shippingcost') }}</b></td>
                        <td class="text-right">{{ Globe::moneyFormat($data->cart_shipping_amount,2) }}</td>
                        @if($data->cart_confirm==0)
                        <td class="text-right"></td>
                        @endif
                    </tr>
                    @else
                    <tr>
                        <td class="text-right" colspan="6"><b>{{ __('Order::order.shippingcost') }}</b></td>
                        <td class="text-right"><input class="calculate" type="text" name="shippingcost" id="shippingcost" value="{{ number_format($data->cart_shipping_amount,2) }}"></td>
                        @if($data->cart_confirm==0)
                        <td class="text-right"></td>
                        @endif
                    </tr>
                    @endif
                    <tr>
                        <td class="text-right" colspan="6"><b>{{ __('Order::order.finalamount') }}</b></td>
                        <td class="text-right effectedtd" id="finalamounttd">{{ Globe::moneyFormat($data->cart_final_amount,2) }}</td>
                        @if($data->cart_confirm==0)
                        <td class="text-right"><input type="hidden" name="finalamount" id="finalamount" value="{{ $data->cart_final_amount }}"></td>
                        @endif
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="row no-print">
        <div class="col-xs-12">
            {{-- @if(Auth::user()->can('merchant.updatequotation')) --}}
            <!--a class="btn btn-sm btn-warning printdoc pull-right" data-toggle="tooltip" data-id="{{ $data->cart_id }}" data-type="{{ ($data->cart_isquotation==1) ? 'quo' : 'inv' }}"><i class="fa fa-print"></i> {{ __('Admin::base.print') }}</a-->
            @if($data->cart_cancel==0)
                <a class="btn btn-sm btn-danger deletedata" value="{{ route('order.deletecart',$data->cart_id) }}"><i class="fa fa-times-circle"></i> {{ __('Admin::base.cancel') }}</a>
                @if($data->cart_isquotation==1)
                    <a href="{{ route('order.printdoc',[Crypt::encrypt($data->cart_id),'quo']) }}" class="btn btn-sm btn-warning pull-right"><i class="fa fa-print"></i> {{ __('Admin::base.print') }}</a>
                @else
                    <a href="{{ route('order.printdoc',[Crypt::encrypt($data->cart_id),'inv']) }}" class="btn btn-sm btn-warning pull-right"><i class="fa fa-print"></i> {{ __('Admin::base.print').' '.__('Order::order.taxinvoice') }}</a>
                    <a href="{{ route('order.printdoc',[Crypt::encrypt($data->cart_id),'do']) }}" class="btn btn-sm btn-info pull-right"><i class="fa fa-print"></i> {{ __('Admin::base.print').' '.__('Order::order.deliveryorder') }}</a>
                @endif
            @endif
            @if($data->cart_isquotation==1)
                <a href="{{ URL::to('order/quotation') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
                @if($data->cart_cancel==0)
                    <a data-toggle="tooltip" data-id="2" data-askmsg="{{ __('Admin::base.confirmsubmission') }}" class="btn btn-sm pull-right btn-success saveform submitbtn"><i class="fa fa-check-circle"></i> {{ ($data->cart_confirm==0) ? __('Order::order.confirmtotaxinvoice') : __('Order::order.generateanothertaxinvoice') }}</a>
                    @if($data->cart_confirm==0)
                    <a data-toggle="tooltip" data-id="1" data-askmsg="{{ __('Admin::base.update') }}" class="btn btn-sm pull-right btn-primary saveform"><i class="fa fa-save"></i> {{ __('Admin::base.update') }}</a>
                    @endif
                @endif
            @else
                <a href="{{ URL::to('order/invoice') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
                @if($data->cart_cancel==0)
                    @if($data->cart_confirm==0)
                    <a data-toggle="tooltip" data-id="4" data-askmsg="{{ __('Admin::base.confirmsubmission') }}" class="btn btn-sm pull-right btn-success saveform submitbtn"><i class="fa fa-check-circle"></i> {{ __('Order::order.placeorder') }}</a>
                    <a data-toggle="tooltip" data-id="3" data-askmsg="{{ __('Admin::base.update') }}" class="btn btn-sm pull-right btn-primary saveform"><i class="fa fa-save"></i> {{ __('Admin::base.update') }}</a>
                    @endif
                @endif
            @endif
            {{-- @endif --}}
        </div>
    </div>
</section>
{!! Form::close() !!}

@stop

@section('footer') 

<div class="modal modal-default fade" id="modal-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modaltitle">{!! trans('Order::order.updatestatus') !!}</h4>
            </div>
            {!! Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@updatequotationstatus', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'modalform', 'files'=>true]) !!}
            <input type="hidden" name="modal_cartid" id="modal_cartid" value="{!! $data->cart_id !!}">
            <input type="hidden" name="modal_type" id="modal_type" value="">
            <div class="modal-body">
                <div class="form-group remarkdiv">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::base.remark') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <textarea class="form-control" name="cart_remark" id="cart_remark">{{ $data->cart_remark }}</textarea>
                    </div>                  
                </div>
                <div class="form-group tracknodiv">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Order::order.trackcodeno') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input type="text" class="form-control" name="trackcode" id="trackcode" value="{{ $data->cart_courrierno }}"> 
                    </div>                  
                </div>
                <div class="form-group paymentslipdiv">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Order::order.paymentslip') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input type="file" name="paymentslip[]" id="paymentslip">
                    </div>                  
                </div>
                <div class="form-group paymentslipdiv">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input type="file" name="paymentslip[]" id="paymentslip">
                    </div>                  
                </div>
                <div class="form-group paymentslipdiv">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input type="file" name="paymentslip[]" id="paymentslip">
                    </div>                  
                </div>
                <div class="form-group statusdiv">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <select name="modal_status" id="modal_status" class="form-control" required>
                            <option value="0" id="option1"></option>
                            <option value="1" id="option2"></option>
                        </select> 
                    </div>                  
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                {{-- @if(Auth::user()->can('product.storestock')) --}}
                <button type="button" class="btn btn-sm btn-primary modalupdatestatus"><i class="fa fa-save"></i> {{ __('Admin::base.update') }}</button>
                {{-- @endif --}}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal modal-default fade" id="modal-2">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><b id="title2">{!! __('Admin::base.action') !!}</b></h4>
            </div>

            <form class="form-horizontal form-label-left">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-condensed">
                            <tbody class="modalbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

$( document ).ready(function() {

    @if($data->cart_confirm==1)
        $('.calculate').attr('readonly','readonly');
    @endif

});

    $(document).on('click', '.chooseaddress', function() {

        var id = $(this).data('id');
        var type = $(this).data('type');
        var cartid = $('#modal_cartid').val();

        if(id!='' && type!=''){

            $.ajax({
                url: '{{ URL::to("order/chooseaddress") }}/'+id+'/'+type+'/'+cartid,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    //console.log(data);
                    if(type=='shipping'){
                        $('#addressship').empty();
                        $('#addressship').append(data);
                    }else{
                        $('#addressbill').empty();
                        $('#addressbill').append(data);
                    }

                    $('#modal-2').modal('hide');
                    swal('{{ __("Admin::base.success") }}!','','success');
                }
            });

        }

    });

    $('.changeaddress').on('click', function(){

        $('.modalbody').empty();
        $('#title2').empty();
        var id = $(this).data('id');
        var type = $(this).data('type');

        if(id!='' && type!=''){

            if(type=='ship'){
                $('#title2').append('{{ __("Order::order.changeshippingaddress") }}');
            }else{
                $('#title2').append('{{ __("Order::order.changebillingaddress") }}');
            }

            $.ajax({
                url: '{{ URL::to("order/getaddress") }}/'+id+'/'+type,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    console.log(data);
                    $('.modalbody').append(data);

                }
            });

            $('#modal-2').modal('show');

        }

    });

    $('.printdoc').on('click', function(){

        var id = $(this).data('id');
        var type = $(this).data('type');

        if(id!='' && type!=''){

            $.ajax({
                url: '{{ URL::to("order/printdoc") }}/'+id+'/'+type,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    console.log(data);

                }
            });

        }

    });

    $('.modalupdatestatus').on('click', function() {

        var type = $('#modal_type').val();

        if(type == 2){
            if($("#paymentslip")[0].files.length==0){
                swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
                return false;
            }
        }else if(type == 3){
            if($('#trackcode').val()==''){
                swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
                return false;
            }
        }else if(type == 5){
            if($('#cart_remark').val()==''){
                swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
                return false;
            }
        }

        swal({
            title: '{{ __("Admin::base.update") }}?',
            //text: "{{ __('Admin::base.inadjustable') }}",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                    $("#modalform").submit();

                });
            },

        }).then(function () {
            swal('{{ __("Admin::base.success") }}!','','success')
        });

    });
    
    $('.updatestatus').on('click', function(){

        var id = $(this).data('id');
        $('#modal_type').val(id);
        $('#option1').empty();
        $('#option2').empty();
        $('.modaltitle').empty();
        $('.tracknodiv').hide();
        $('.paymentslipdiv').hide();
        $('.statusdiv').hide();
        $('.remarkdiv').hide();

        if(id=='1' || id == '2'){
            $('#modal_status').val('{{ $data->cart_payment_status }}');
            $('#option1').append('{{ __("Order::order.pending") }}');
            $('#option2').append('{{ __("Order::order.paid") }}');
            if(id=='1'){
                $('.modaltitle').append('{{ __("Order::order.paymentstatus") }}');
                $('.statusdiv').show();
            }else{
                $('.modaltitle').append('{{ __("Order::order.attachfile") }}');
                $('.paymentslipdiv').show();
            }
        }else if(id=='5'){
            $('.modaltitle').append('{{ __("Admin::base.update") }}'+' '+'{{ __("Admin::base.remark") }}');
            $('.remarkdiv').show();
        }else{
            $('#modal_status').val('{{ $data->cart_isshipping }}');
            $('#option1').append('{{ __("Order::order.pending") }}');
            $('#option2').append('{{ __("Order::order.done") }}');
            if(id=='3'){
                $('.modaltitle').append('{{ __("Admin::base.update") }}');
                $('.tracknodiv').show();
            }else{
                $('.modaltitle').append('{{ __("Order::order.shippingstatus") }}');
                $('.statusdiv').show();
            }
        }

        $('#modal-1').modal('show');

    });

    $('.updatestatuspay').on('click', function() {

        $('#modal_type').val($(this).data('id'));

        swal({
            title: '{{ __("Order::order.changepaymentstatustopaid") }}?',
            text: "{{ __('Admin::base.inadjustable') }}",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                    $("#modalform").submit();

                });
            },

        }).then(function () {
            swal('{{ __("Admin::base.success") }}!','','success')
        });

    });

    $(document).on('change', '.calculate', function() {
        calculate();
    });

    $(document).on('keyup', '.calculate', function() {
        calculate();
    });

    function calculate(){

        $('.effectedtd').empty();
        var rownum = parseInt($('#totalrow').val());
        var totalamount = 0;
        var totalgst = 0;
        var shippingcost = 0;
        var finalamount = 0;
        var totalweight = 0;
        var i = 0;
        var uptoweight = parseFloat($('#uptoweight').val());
        var uptoprice = parseFloat($('#uptoprice').val());
        var addweight = parseFloat($('#addweight').val());
        var addprice = parseFloat($('#addprice').val());

        for(i=1; i<=rownum; i++){

            if(!isNaN($('#weight'+i).val())){
                var price = ($('#price'+i).val()!='') ? parseFloat($('#price'+i).val()) : 0;
                var gst = ($('#gst'+i).val()!='') ? parseFloat($('#gst'+i).val()) : 0;
                var weight = ($('#weight'+i).val()!='') ? parseFloat($('#weight'+i).val()) : 0;
                var quantity = ($('#quantity'+i).val()!='') ? parseFloat($('#quantity'+i).val()) : 0;
                var subtotal = price*quantity;
                var subgst = gst*subtotal/100;

                $('#subtotaltd'+i).append(subtotal.toFixed(2));
                $('#subtotal'+i).val(subtotal);

                totalamount += subtotal;
                totalgst += subgst;
                totalweight += (weight*quantity);
            }

        }

        if(totalweight <= uptoweight){
            shippingcost = uptoprice;
        }else{
            var times = Math.ceil((totalweight - uptoweight) / addweight);
            shippingcost = uptoprice + (addprice * times);
        }

        finalamount = totalamount+totalgst+shippingcost;
        $('#totalamounttd').append(totalamount.toFixed(2));
        $('#totalamount').val(totalamount.toFixed(2));
        $('#totalgsttd').append(totalgst.toFixed(2));
        $('#totalgst').val(totalgst.toFixed(2));
        $('#shippingcost').val(shippingcost.toFixed(2));
        $('#finalamounttd').append(finalamount.toFixed(2));
        $('#finalamount').val(finalamount.toFixed(2));

        $('.submitbtn').attr('disabled','disabled');
    }

    $(document).on('click', '.removecartitem', function() { 
    
        var id = $(this).data('id');

        swal({
            title: '{{ __("Admin::base.cancel") }}?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.no") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                    $.ajax({
                        url: '{{ URL::to("order/removeitem") }}/'+id,
                        type: 'get',
                        dataType: 'json',
                        success:function(data) {

                            $('#itemtr'+id).remove();
                            swal('{{ __("Order::order.cancelled") }}!','{{ __("Order::order.itemhasbeencancelled") }}.','success');
                            calculate();

                        }
                    });

                });
            },

        }).then(function () {
            swal(
                '{{ __("Admin::base.deleted") }}!',
                '{{ __("Admin::base.deletedtext") }}.',
                'success'
            )
        });

    });

    $('.saveform').on('click', function() {

        // if($('#type_id').val()==''){
        //     swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
        //     return false;
        // }
        var askmsg2 = '';
        var status = $(this).data('id');
        var askmsg = $(this).data('askmsg');

        if(status==2){
            askmsg2 = '{{ __("Order::order.invoicecreateplaceorder") }}';
        }

        if(status==4){
            askmsg2 = '{{ __("Order::order.willplaceorder") }}';
        }

        $('#actionstatus').val(status);

        swal({
            title: askmsg+'?',
            text: askmsg2,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                    $("#form1").submit();

                });
            },

        }).then(function () {
            swal('{{ __("Admin::base.success") }}!','','success')
        });

    });

    $('.deletedata').on('click', function() {
    
        var url = $(this).attr('value');

        swal({
          title: '{{ __("Admin::base.areyousure") }}',
          text: "{{ __('Admin::base.norevert') }}",
          type: 'warning',
          showCancelButton: true,
          cancelButtonText: '{{ __("Admin::base.cancel") }}',
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: '{{ __("Admin::base.yescancelit") }}',
          
          preConfirm: function() {
            return new Promise(function(resolve) {
                 window.location.href = url;
            });
        },

        }).then(function () {
          swal(
            '{{ __("Admin::base.cancelled") }}!',
            '{{ __("Admin::base.deletedtext") }}.',
            'success'
          )
        });

    });

</script>
@stop