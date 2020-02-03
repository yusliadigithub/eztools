@extends('layouts.adminLTE.master')

@section('header')
<link rel="stylesheet" type="text/css" href="{!! asset('css/fancyradio.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/minusinputplus.css') !!}">
@stop

@section('content')

{!! Form::open(['action'=>'\App\Modules\Product\Controllers\ProductController@storeattribute', 'method'=>'post', 'role'=>'form', 'id'=>'newdataform' ]) !!}
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><b>{!! $product->product_name !!}</b></h3>
            </div>
            <input type="hidden" name="product_id" id="product_id" value="{!! $product->product_id !!}">
            <input type="hidden" id="merchant_id" value="{!! $product->merchant_id !!}">
            <input type="hidden" id="contentmore" value="{!! $product->product_content !!}">
            <input type="hidden" id="contentless" value="{!! Globe::truncateString($product->product_content,400) !!}">
            <div class="box-body">
        
                <div class="form-group">
                    <label for="complaint_type_id">&nbsp;</label>
                    <img src="{!! (!empty($product->image)) ? asset($product->image->upload_path.$product->image->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="140" width="140">
                </div>
                <div class="form-group productcontent">
                    {!! Globe::truncateString($product->product_content,400) !!}
                    @if(strlen($product->product_content)>400)
                        {!! '<a class="showcontent" value="1">'.__('Admin::base.readmore').'</a> ' !!}
                    @endif
                </div>
                <hr>
                @if(count($product->stock)>0)
                    @foreach($product->stock as $key => $stock)
                        @if($key != 0)
                            {!! '<br>' !!}
                        @endif
                        <div class="dlk-radio btn-group">
                            <label class="btn btn-default">
                                <input name="stockid" class="form-control stockval" type="radio" value="{{ $stock->product_stock_id }}">
                                <i class="fa fa-check glyphicon glyphicon-ok"></i>&nbsp;&nbsp;&nbsp;{{ __('Order::order.weight') }}: {{ number_format($stock->product_stock_weight,2) }},&nbsp;&nbsp;&nbsp;{!! str_replace('|','<b>|</b>',$stock->product_stock_description) !!}
                            </label>
                        </div><br>
                    @endforeach
                @endif
            </div>
            <div class="box-footer">
                <div class="count-input space-bottom pull-right">
                    <a class="incr-btn" data-action="decrease" href="#">–</a>
                    <input class="quantity" type="text" name="quantity" id="stockqty" value="1"/>
                    <a class="incr-btn" data-action="increase" href="#">&plus;</a>
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group pull-right">
                    <a href="{{ URL::to('order/catalog') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
                    @if(!empty($activecart))
                    <a data-toggle="tooltip" title="{{ __('Order::order.changecart') }}" class="btn btn-sm btn-success changecart"><i class="fa fa-exchange-alt"></i> </a>
                    <a data-toggle="tooltip" data-id="{{ $activecart->cart_id }}" title="{{ __('Order::order.activecart') }}" class="btn btn-sm btn-info cartinfo currentcart">({{ count($activecart->detail)}}) <i class="fas fa-shopping-cart"></i></a>
                    @endif
                    @if(Auth::user()->can('order.addtocart'))
                    <button type="button" class="btn btn-sm btn-warning addtocart"><i class="fa fa-cart-plus"></i> {{ __('Order::order.addtocart') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop

@section('footer')

<div class="modal modal-default fade" id="modal-1">
    <div class="modal-dialog" style="width:45%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.choosecart') !!}</h4>
            </div>

            @if (session('flash_error'))
                <div class="alert alert-block alert-error fade in">
                    <strong>{{ __('Admin::base.error') }}!</strong>
                    {!! session('flash_error') !!}
                </div>
            @endif

            <form class="form-horizontal form-label-left">
                <div class="modal-body modalbody1"></div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                    <a href="javascript:;" class="btn btn-sm btn-primary newcartform"><i class="fa fa-shopping-cart"></i>{{ __('Order::order.createnewcart') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-info fade" id="modal-2">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.createcart') !!}</h4>
            </div>

            @if (session('flash_error'))
                <div class="alert alert-block alert-error fade in">
                    <strong>{{ __('Admin::base.error') }}!</strong>
                    {!! session('flash_error') !!}
                </div>
            @endif

            {!! Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@createcart', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form2']) !!}
            <input type="hidden" name="modal_merchantid" id="modal_merchantid" value="{!! $product->merchant_id !!}">
            <input type="hidden" name="modal_stockid" id="modal_stockid" class="modaldata" value="">
            <input type="hidden" name="modal_quantity" id="modal_quantity" class="modaldata" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Order::order.type') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <select name="typeid" id="typeid" class="form-control modaldata" required>
                            <option value="">{{ __('Admin::base.please_select') }}</option>
                            <option value="1" {{ (old('typeid')==1) ? 'selected' : '' }}>{{ __('Order::order.quotation') }}</option>
                            <option value="2" {{ (old('typeid')==2) ? 'selected' : '' }}>{{ __('Order::order.taxinvoice') }}</option>
                        </select> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Order::order.customer') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <select name="guest_id" id="guest_id" class="form-control modaldata" required>
                            <option value="">{{ __('Admin::base.please_select') }}</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->guest_id }}" {{ (old('guest_id')==$guest->guest_id) ? 'selected' : '' }}>{{ $guest->guest_fullname }} ({{ $guest->email }})</option>
                            @endforeach
                        </select> 
                    </div>                  
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                @if(Auth::user()->can('product.storestock'))
                <button type="button" class="btn btn-sm btn-success createcart"><i class="fa fa-check-circle"></i> {{ __('Order::order.createcart') }}</button>
                @endif
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal modal-default fade" id="modal-3">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.cartinfo') !!}</h4>
            </div>

            <form class="form-horizontal form-label-left">
                <div class="modal-body modalbody3"></div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{!! asset('js/minusinputplus.js') !!}"></script>
<script type="text/javascript">

$(document).ready(function() {

});
    
    $(document).on('click', '.newcartform', function() {
    //$('.newcartform').on('click', function(){
        openmodal2();
    });

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
                        url: '{{ URL::to("order/removecartitem") }}/'+id,
                        type: 'get',
                        dataType: 'json',
                        success:function(data) {

                            $('#itemtr'+id).remove();
                            swal('{{ __("Order::order.cancelled") }}!','{{ __("Order::order.itemhasbeencancelled") }}.','success');
                            $('.currentcart').empty();
                            $('.currentcart').append('('+data.totalleft+') <i class="fas fa-shopping-cart"></i>');

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

    function openmodal1(){

        $('#modal-2').modal('hide');
        $('.modaldata').val('');
        $('#modal_stockid').val($('input[name=stockid]:checked').val());
        $('#modal_quantity').val($('#stockqty').val());
        $('#modal-1').modal('show');

    }

    function openmodal2(){

        $('#modal-1').modal('hide');
        $('.modaldata').val('');
        $('#guest_address_id').empty();
        $('#modal_stockid').val($('input[name=stockid]:checked').val());
        $('#modal_quantity').val($('#stockqty').val());
        $('#modal-2').modal('show');

    }

    $('.cartinfo').on('click', function(){

        var cartid = $(this).data('id');
        $('.modalbody3').empty();

        $.ajax({
            url: '{{ URL::to("order/getcartinfo") }}/'+cartid,
            type: 'get',
            dataType: 'json',
            success:function(data) {
                console.log(data);

                $('.modalbody3').append(data);

            }
        });

        $('#modal-3').modal('show');

    });
    
    $('.addtocart').on('click', function(){

        var mid = $('#merchant_id').val();
        var qty = $('#stockqty').val();
        var stockid = $('input[name=stockid]:checked').val();
        //alert(qty+' '+stockid);
        $('.modalbody1').empty();

        if(qty=='' || ( ! $('.stockval').is(':checked') ) ){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Order::order.pleasechooseitem") }}', 'error');
            return false;
        }else{

            $.ajax({
                url: '{{ URL::to("order/getActiveCart") }}/'+stockid+'/'+qty+'/'+mid,
                type: 'get',
                dataType: 'json',
                success:function(data) {
                    console.log(data);

                    if(data.result==1){

                        swal('{{ __("Admin::base.success") }}!','{{ __("Order::order.addedtocart") }}.','success');
                        $('.currentcart').empty();
                        $('.currentcart').append('('+data.item+') <i class="fas fa-shopping-cart"></i>');

                    }else if(data.result==2){

                        //var url = '{{ Request()->url() }}';
                        $('.modalbody1').append(data.html);
                        //$('.modalbody1').load();
                        openmodal1();

                    }else if(data.result==3){

                        openmodal2();

                    }

                }
            });

        }

    });

    $('.changecart').on('click', function(){

        var mid = $('#merchant_id').val();
        $('.modalbody1').empty();

        $.ajax({
            url: '{{ URL::to("order/getcartlistformodal") }}/'+mid,
            type: 'get',
            dataType: 'json',
            success:function(data) {
                console.log(data);

                $('.modalbody1').append(data);
                openmodal1();

            }
        });

    });

    $('.createcart').on('click', function() {

        if($('#guest_id').val()=='' || $('#typeid').val()==''){
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

                    $("#form2").submit();

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

    $('.submitform').on('click', function() {
    
      var url = $(this).attr('value');

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

                $("#newdataform").submit();

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

    $(document).on('click', '.enabledata', function() { 
    //$('.enabledata').on('click', function() {
    
        var url = $(this).attr('value');
        var askmsg = $(this).data('askmsg');

        swal({
            title: askmsg,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                 window.location.href = url;

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

    @if( Session::get('modal2') )
        //getGuessAddres('{{ old("guest_id") }}','{{ old("guest_address_id") }}');
        $('#modal-2').modal( {backdrop: 'static', keyboard: false} ); 
    @endif

    $('#guest_id').on('change', function(){

        //getGuessAddress($(this).val(),'');

    }); 

    function getGuessAddress(id,did){

        $('#guest_address_id').empty();
        
        if(id!=''){

            $.ajax({
                url: '{{ URL::to("order/getGuestAddress") }}/'+id,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    if(data!=''){
                        $('#guest_address_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
                        $.each(data, function(key, value) {
                            if(did!='' && did==key){
                                $('#guest_address_id').append('<option value="'+ key +'" selected>'+ value +'</option>');
                            }else{
                                $('#guest_address_id').append('<option value="'+ key +'">'+ value +'</option>');
                            }
                        });
                    }else{
                        $('#guest_address_id').append('<option value="">-- No Record Found --</option>');
                    }

                }
            });

        }else{
            $('#guest_address_id').append('<option value="">-- Please Select State --</option>');
        }

    }

    $('.showcontent').on('click', function(){

        //var con = $(this).attr('value');
        $('.productcontent').empty();
        $('.productcontent').append($('#contentmore').val());
        /*if(con == '1'){
            $('.productcontent').append($('#contentmore').val()+'<a class="showcontent" value="2">Less</a>');
        }else{
            $('.productcontent').append($('#contentless').val()+'<a class="showcontent" value="1">Read More</a>');
        }*/

    });

</script>
@stop