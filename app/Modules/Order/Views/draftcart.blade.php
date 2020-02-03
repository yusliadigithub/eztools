@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

{!! Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@keepcart', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1']) !!}
<section class="invoice">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> {{ $guest->merchant->merchant_name }}
				<small class="pull-right">Date: {{ date('d/m/Y') }}</small>
			</h2>
		</div>
	</div>
	<div class="row invoice-info">
		<div class="col-sm-4 invoice-col">{!! __('Admin::base.from') !!}
			<address>
			<strong>{{ $guest->merchant->merchant_name }}</strong><br>
			{!! ($guest->merchant->configuration->merchant_config_address1!='') ? $guest->merchant->configuration->merchant_config_address1.'<br>' : '' !!}
			{!! ($guest->merchant->configuration->merchant_config_address2!='') ? $guest->merchant->configuration->merchant_config_address2.'<br>' : '' !!}
			{!! ($guest->merchant->configuration->merchant_config_address3!='') ? $guest->merchant->configuration->merchant_config_address3.'<br>' : '' !!}
			{!! $guest->merchant->configuration->merchant_config_postcode.', '.$guest->merchant->configuration->district->district_desc.'<br>' !!}
			{!! $guest->merchant->configuration->state->state_desc.'<br>' !!}
			{!! __('Order::order.contactno').': '.$guest->merchant->configuration->merchant_config_mobileno.'<br>' !!}
			{!! __('Admin::base.email').': '.$guest->merchant->configuration->merchant_config_email !!}
			</address>
		</div>
		<div class="col-sm-4 invoice-col">{!! __('Frontend::frontend.billing_address') !!}
			<address id="addressbill"></address>
			<a data-toggle="tooltip" title="{{ __('Order::order.changebillingaddress') }}" data-id="{{ $guest->guest_id }}" data-type="bill" class="btn btn-xs btn-default changeaddress">Change</a>
		</div>

		<div class="col-sm-4 invoice-col">{!! __('Frontend::frontend.shipping_address') !!}
			<address id="addressship"></address>
			<a data-toggle="tooltip" title="{{ __('Order::order.changeshippingaddress') }}" data-id="{{ $guest->guest_id }}" data-type="ship" class="btn btn-xs btn-default changeaddress">Change</a>
		</div>

	</div>

	<hr>

	<div class="row">
		<div class="col-xs-12 table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr bgcolor="#172f93">
						<th width="3%"><font color="#FFFFFF">#</font></th><th><font color="#FFFFFF">{!! __('Product::product.product') !!}</font></th>
						<th class="text-center" width="12%"><font color="#FFFFFF">{!! __('Order::order.price') !!}</font></th>
						<th class="text-center" width="12%"><font color="#FFFFFF">GST (%)</font></th>
						<th class="text-center" width="12%"><font color="#FFFFFF">{!! __('Order::order.weight') !!} (KG)</font></th>
						<th class="text-center" width="12%"><font color="#FFFFFF">{!! __('Order::order.quantity') !!}</font></th>
						<th class="text-center" width="12%"><font color="#FFFFFF">Subtotal</font></th>
						<th width="5%">&nbsp;</th>
					</tr>
				</thead>
				<tbody class="itembody">
					{!! $html !!}
				</tbody>
			</table>
		</div>
	</div>

	<div class="row no-print">
		<div class="col-xs-12">
			<a href="{{ URL::to('order/catalog') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
            {{-- @if(Auth::user()->can('merchant.updatequotation')) --}}
                <button type="button" class="btn btn-sm btn-success pull-right submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
            {{-- @endif --}}
		</div>
	</div>
</section>
{!! Form::close() !!}

@stop

@section('footer') 

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

	@if(Session::get('activebilladdress'))
		getdraftaddress('{{ $guest->guest_id }}','{{ Session::get("activebilladdress") }}','billing');
	@else
		getdraftaddress('{{ $guest->guest_id }}','','billing');
	@endif

	@if(Session::get('activeshipaddress'))
		getdraftaddress('{{ $guest->guest_id }}','{{ Session::get("activeshipaddress") }}','shipping');
	@else
		getdraftaddress('{{ $guest->guest_id }}','','shipping');
	@endif

});

	function getdraftaddress(guestid,addid,type){

		$.ajax({
            url: '{{ URL::to("order/getdraftaddress") }}',
            type: 'get',
            data: { guestid: guestid, addid: addid, type: type },
            dataType: 'json',
            success:function(data) {
            	console.log(data);
            	if(type == 'billing'){
	            	$('#addressbill').empty();
	                $('#addressbill').append(data.html);
	            }else{
	                $('#addressship').empty();
	                $('#addressship').append(data.html);
	            }

            }
        });

	}

	$(document).on('click', '.chooseaddress', function() {

        var id = $(this).data('id');
        var type = $(this).data('type');

        if(id!='' && type!=''){

            $.ajax({
                url: '{{ URL::to("order/choosedraftaddress") }}/'+id+'/'+type,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    $('#modal-2').modal('hide');
                    swal('{{ __("Admin::base.success") }}!','','success');
                    window.location.reload();

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

	$(document).on('click', '.plusdata', function() { 
    
        var id = $(this).data('id');
        var qty = $('#qty'+id).val();
        var newqty = parseInt(qty)+1;
        $('#qty'+id).val(newqty);

        minusplus(id,newqty);

    });

    $(document).on('click', '.minusdata', function() { 
    
        var id = $(this).data('id');
        var qty = $('#qty'+id).val();
        var newqty = parseInt(qty)-1;

        if(newqty==0){
        	swal('{{ __("Admin::base.error") }}!', '{{ __("Order::order.itemquantitycannotzero") }}', 'error');
        	return false;
        }

        $('#qty'+id).val(newqty);

        minusplus(id,newqty);

    });

    function minusplus(id,qty){

    	$.ajax({
            url: '{{ URL::to("order/minuspluscartitem") }}',
            type: 'get',
            data: { stockid: id, quantity: qty },
            dataType: 'json',
            success:function(data) {

            	$('.itembody').empty();
            	$('.itembody').append(data);
            	//swal('{{ __("Admin::base.success") }}!', '{{ __("Order::order.addedtocart") }}', 'success');

            }
        });

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
                        url: '{{ URL::to("order/removecartitem") }}/'+id,
                        type: 'get',
                        dataType: 'json',
                        success:function(data) {

                            //$('#itemtr'+id).remove();
                            $('.itembody').empty();
            				$('.itembody').append(data.html);
                            swal('{{ __("Order::order.cancelled") }}!','{{ __("Order::order.itemhasbeencancelled") }}.','success');
                            //window.location.reload();

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

    $('.submitform').on('click', function() {

		if($('#type_id').val()==''){
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

	                $("#form1").submit();

	            });
	        },

        }).then(function () {
            swal('{{ __("Admin::base.success") }}!','','success')
        });

    });

</script>
@stop