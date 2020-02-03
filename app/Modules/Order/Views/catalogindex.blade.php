@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{!! asset('css/minusinputplus.css') !!}">
@stop

@section('content')
	{{-- (Session::has('activecart')) ? Cart::session(Session::get('activecart'))->getContent() : '' --}}
	<input id="sessionid" value="" type="hidden">
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="pull-left col-sm-12 col-md-6 col-lg-6">
                    <a data-toggle="tooltip" title="{{ __('Order::order.activecart') }}" class="btn btn-sm btn-info cartinfo currentcart">({{ (Session::has('activecart')) ? Cart::session(Session::get('activecart'))->getContent()->count() : 0 }}) <i class="fas fa-shopping-cart"></i> {{ __('Order::order.activecart') }}</a>
					<a data-toggle="tooltip" title="{{ __('Order::order.proceedtocheckout') }}" href="{{ route('order.displaycart') }}" class="btn btn-sm btn-success proceedcheckout"><i class="fas fa-sign-out-alt"></i> {{ __('Order::order.proceedtocheckout') }}</a>
                    <a data-toggle="tooltip" title="{{ __('Order::order.clearcart') }}" class="btn btn-sm btn-danger clearcart proceedcheckout"><i class="fa fa-times-circle"></i> {{ __('Order::order.clearcart') }}</a>
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@catalog', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="product_name">{{ __('Product::product.productname') }}</option>
					  	</select>
				  	</div>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('product/type') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			{{ Form::close() }}
			</div>
		 	{{ csrf_field() }}

			<div class="table-responsive">
				<!--table class="table table-striped jambo_table bulk_action table-bordered"-->
                <table class="table jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title" width="15%">{{-- __('Product::product.image') --}}</th>
							<th class="column-title" width="25%">{{ __('Product::product.product') }}</th>
							<th class="column-title" width="50%">{{ __('Product::product.stock') }}</th>
							<!--th class="column-title text-center">{{ __('Product::product.price') }} (MYR)</th-->
							<th class="column-title text-center" width="10%">{{ __('Product::product.category') }}</th>
							<!--th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th-->
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td class="text-center"><img src="{!! (!empty($type->image)) ? asset($type->image->upload_path.$type->image->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120"></td>
							<td>{!! $type->product_name !!}</td>
							<td>
								@if(count($type->stock)>0)
                                    <table class="table">
                                        <tbody>
									@foreach($type->stock as $key => $stock)
                                        <tr>
                                            <td>{{ $stock->product_stock_description }}</td>
                                            <td width="20%">{{ 'QTY ('.number_format($stock->product_stock_quantity).')' }}</td>
                                            <td width="20%">{{ 'MYR '.number_format($stock->product_stock_sale_price,2) }}</td>
                                            <td width="20%">
                                                <a data-toggle="tooltip" data-id="{{ $stock->product_stock_id }}" class="btn btn-m btn-warning addtocart" {{ ($stock->product_stock_status==0) ? 'disabled' : '' }}><i class="fa fa-cart-plus"></i>{{ __('Order::order.addtocart') }}</a>
                                            </td>
                                        </tr>
									{{-- '<div class="input-group margin"><input class="form-control" type="text" value="'.$stock->product_stock_description.'" readonly><div class="input-group-btn"><a data-toggle="tooltip" data-id="'.$stock->product_stock_id.'" class="btn btn-warning addtocart"><i class="fa fa-cart-plus"></i> '.__('Order::order.addtocart').'</a></div></div>' --}}
									@endforeach
                                        </tbody>
                                    </table>
								@endif
							</td>
							<!--td>
								@if(count($type->stock)>0)
									{{ number_format($type->stock->min('product_stock_sale_price'),2) }}
								@endif
							</td-->
							<td>{!! $type->type->product_type_desc !!}</td>
							<!--td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Order::order.productdetail') }}" href="{{ route('order.catalogdetail',Crypt::encrypt($type->product_id)) }}" class="btn btn-xs btn-success"><i class="fa fa-search"></i> {{ __('Order::order.view') }}</a>
							</td-->
							<?php $merchantid = $type->merchant_id; ?>
						</tr>
						@endforeach
						@else
						<tr><td colspan="8">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{-- $types->appends(Request::all())->links() --}}
				{{ $types->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
		</div>
	</div>

@stop

@section('footer')

<div class="modal modal-default fade" id="modal-2">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.createcart') !!}</h4>
            </div>

            <form class="form-horizontal form-label-left" id="form2">
            <input type="hidden" name="modal2_merchantid" id="modal2_merchantid" value="{!! $merchantid !!}">
            <input type="hidden" name="modal2_stockid" id="modal2_stockid" class="modaldata" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Order::order.customer') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <select name="guestid" id="guestid" class="form-control modaldata" required>
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
                <button type="button" class="btn btn-sm btn-success registersession"><i class="fa fa-check-circle"></i> {{ __('Order::order.createcart') }}</button>
                @endif
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-default fade" id="modal-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.determinequantity') !!}</h4>
            </div>

            <form class="form-horizontal form-label-left" id="formm1">
            <input type="hidden" name="modal1_merchantid" id="modal1_merchantid" value="{!! $merchantid !!}">
            <input type="hidden" name="modal1_stockid" id="modal1_stockid" class="modaldata" value="">
            <input type="hidden" name="modal1_guestid" id="modal1_guestid" class="" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Order::order.quantity') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="count-input space-bottom pull-right">
		                    <a class="incr-btn" data-action="decrease" href="#">–</a>
		                    <input class="quantity" type="text" name="modal1_quantity" id="modal1_quantity" value="1"/>
		                    <a class="incr-btn" data-action="increase" href="#">&plus;</a>
		                </div>
                    </div>                  
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                @if(Auth::user()->can('product.storestock'))
                <button type="button" class="btn btn-sm btn-success addquantity"><i class="fa fa-check-circle"></i> {{ __('Order::order.addtocart') }}</button>
                @endif
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-default fade" id="modal-3">
    <div class="modal-dialog" style="width:60%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.cartinfo') !!}</h4>
            </div>

            <form class="form-horizontal form-label-left">
                <div class="modal-body modalbody3"></div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                    <!--button type="button" class="btn btn-sm btn-success checkout"><i class="fa fa-sign-out-alt"></i> {{ __('Order::order.checkout') }}</button-->
                    <a data-toggle="tooltip" title="{{ __('Order::order.activecart') }}" href="{{ route('order.displaycart') }}" class="btn btn-sm btn-success"><i class="fas fa-sign-out-alt"></i> {{ __('Order::order.proceedtocheckout') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-default fade" id="modal-4">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.keepas') !!}</h4>
            </div>

            {!! Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@submitcart', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1']) !!}
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
            </div>
            
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                @if(Auth::user()->can('product.storestock'))
                <button type="button" class="btn btn-sm btn-success submitcart"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
                @endif
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script src="{!! asset('js/minusinputplus.js') !!}"></script>
<script type="text/javascript">

	@if(!Session::has('activecart'))
		$('.currentcart').hide();
        $('.proceedcheckout').hide();
	@endif

	$('.checkout').on('click', function() {

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

	                $('#modal-3').modal('hide');
	                $('#modal-4').modal('show');

	            });
	        },

        }).then(function () {
            swal('{{ __("Admin::base.success") }}!','','success')
        });

    });

	$('.submitcart').on('click', function() {

		if($('#typeid').val()==''){
    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
    		return false;
    	}

      	swal({
	        title: '{{ __("Admin::base.areyousure") }}',
	        //text: "{{ __('Admin::base.inadjustable') }}",
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

	function getcartinfo(){

		$('.modalbody3').empty();

        $.ajax({
            url: '{{ URL::to("order/getcartinfo") }}',
            type: 'get',
            dataType: 'json',
            success:function(data) {
                console.log(data);

                $('.modalbody3').append(data);

            }
        });

        $('#modal-3').modal('show');

	}

	$('.cartinfo').on('click', function(){

        getcartinfo();

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
                            $('.currentcart').append('('+data.totalleft+') <i class="fas fa-shopping-cart"></i> {{ __("Order::order.activecart") }}');
                            getcartinfo();

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

	$('.addtocart').on('click', function(){

		var stockid = $(this).data('id');
		var merchantid = $('#modal1_merchantid').val();

		$.ajax({
            url: '{{ URL::to("order/checkexistcartsession") }}',
            type: 'get',
            dataType: 'json',
            success:function(data) {

             	console.log(data);

             	if(data==0){
             		opencreatenew(stockid);
             	}else{
             		opencreateqty(stockid,'');
             	}

            }
        });

	});

	function opencreatenew(sid){

		$('#modal-1').modal('hide');
		$('.modaldata').val('');
		$('#modal2_stockid').val(sid);
		$('#modal-2').modal('show');

	}

	function opencreateqty(sid,gid){

		$('#modal-2').modal('hide');
		$('.modaldata').val('');
		$('#modal1_stockid').val(sid);
		$('#modal1_guestid').val(gid);
		$('#modal-1').modal('show');

	}

	$('.registersession').on('click', function(){

		var stockid = $('#modal2_stockid').val();
		var guestid = $('#guestid').val();

		if(stockid=='' || guestid==''){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Order::order.pleasechoosecustomer") }}', 'error');
            return false;
        }else{
			opencreateqty(stockid,guestid);
	    }

	}); 

	$('.addquantity').on('click', function(){

		var stockid = $('#modal1_stockid').val();
		var guestid = ($('#modal1_guestid').val()!='') ? $('#modal1_guestid').val() : '';
		var quantity = $('#modal1_quantity').val();

		if(stockid=='' || quantity=='' ){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
            return false;
        }else{

			$.ajax({
	            url: '{{ URL::to("order/createcartsession") }}',
	            type: 'get',
	            data: { stockid: stockid, guestid: guestid, quantity: quantity, action: 'add' },
	            dataType: 'json',
	            success:function(data) {

	             	$('.currentcart').empty();
	             	$('.currentcart').append('('+data+') <i class="fas fa-shopping-cart"></i> {{ __("Order::order.activecart") }}');
	             	$('#modal-1').modal('hide');
	             	$('.currentcart').show();
                    $('.proceedcheckout').show();
	             	swal('{{ __("Admin::base.success") }}!', '{{ __("Order::order.addedtocart") }}', 'success');
                    $('#modal1_quantity').val(1);

	            }
	        });
		}
	});

    $('.clearcart').on('click', function(){

        swal({
            title: '{{ __("Admin::base.areyousure") }}',
            //text: "{{ __('Admin::base.inadjustable') }}",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                    $.ajax({
                        url: '{{ URL::to("order/clearcart") }}',
                        type: 'get',
                        dataType: 'json',
                        success:function(data) {

                            $('.currentcart').hide();
                            $('.proceedcheckout').hide();
                            swal('{{ __("Admin::base.success") }}!', '{{ __("Order::order.msgcartsuccessclear") }}', 'success');

                        }
                    });

                });
            },

        }).then(function () {
            swal('{{ __("Admin::base.success") }}!','','success')
        });

    });

	$('#merchant_id').on('change', function(){

		getParent($(this).val(),'');

	});

	function getParent(id,did){
		//var id = $(this).val();
		$('#product_type_id').empty();
            
        if(id!=''){
        	eid = 'noid';
            $.ajax({
                url: '{{ URL::to("product/type/getParent") }}/'+id+'/'+eid,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    if(data!=''){
                        $('#product_type_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
                        $.each(data, function(key, value) {
                        	if(did!='' && did==key){
                                $('#product_type_id').append('<option value="'+ key +'" selected>'+ value +'</option>');
                            }else{
                                $('#product_type_id').append('<option value="'+ key +'">'+ value +'</option>');
                            }
                        });
                    }else{
                        $('#product_type_id').append('<option value="">{{ __("Admin::base.norecordfound") }}</option>');
                    }

                }
            });

        }else{
            $('#product_type_id').append('<option value="">{{ __("Admin::base.norecordfound") }}</option>');
        }
	}

</script>
@stop