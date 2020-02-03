@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')


	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="pull-left col-sm-12 col-md-6 col-lg-6">&nbsp;</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@quotation', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="cart_orderno">{{ __('Order::order.orderno') }}</option>
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
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title text-center" width="14%">{{ __('Order::order.orderno') }}</th>
							<th class="column-title">{{ __('Order::order.customer') }}</th>
							@if($list!='3')
							<th class="column-title text-center" width="12%">{{ __('Order::order.totalweight') }} (KG)</th>
							<th class="column-title text-center" width="13%">{{ __('Order::order.finalamount') }} (MYR)</th>
							@endif
							@if($list=='3')
							<th class="column-title text-center" width="13%">{{ __('Order::order.shippingtrackcodeno') }}</th>
							@endif
							<th class="column-title text-center" width="12%">Updated At</th>
							@if($list!='3')
							<th class="column-title text-center" width="8%">Status</th>
							@else
							<th class="column-title text-center" width="8%">{{ __('Order::order.shipping') }}</th>
							@endif
							@if(in_array($list,['2','3']))
							<th class="column-title text-center" width="8%">{{ __('Order::order.payment') }}</th>
							<th class="column-title text-center" width="8%">{{ __('Order::order.delivered') }}?</th>
							@endif
							<th class="column-title text-center" width="13%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td class="text-center">{!! $type->cart_orderno !!}</td>
							<td>{!! $type->guest->guest_fullname !!}</td>
							@if($list!='3')
							<td class="text-right">{!! number_format($type->cart_total_weight,2) !!}</td>
							<td class="text-right">{!! number_format($type->cart_final_amount,2) !!}</td>
							@endif
							@if($list=='3')
							<td>{!! $type->cart_courrierno !!}</td>
							@endif
							<td class="text-center">{!! date( 'd/m/y, H:i:s', strtotime($type->updated_at)) !!}</td>
							<td class="text-center">
								@if($list!='3')
								{!! ($type->cart_confirm=='1') ? '<span class="label label-success">'.__('Order::order.checkout').'</span>' : '<span class="label label-warning">'.__('Order::order.pending').'</span>' !!}
								@else
								{!! ($type->cart_courrier_status=='1') ? '<span class="label label-success">'.__('Order::order.done').'</span>' : '<span class="label label-warning">'.__('Order::order.pending').'</span>' !!}
								@endif
							</td>
							@if($type->cart_isinvoice=='1')
							<td class="text-center">
								{!! ($type->cart_payment_status=='1') ? '<span class="label label-success">'.__('Order::order.paid').'</span>' : '<span class="label label-warning">'.__('Order::order.pending').'</span>' !!}
							</td>
							<td class="text-center">
								@if($type->cart_isshipping=='1')
									{!! '<span class="label label-success">'.__('Order::order.done').'</span>' !!}
								@else
									@if($type->cart_courrier_status=='1')
										{!! '<span class="label label-warning">'.__('Order::order.shipped').'</span>' !!}
									@else
										{!! '<span class="label label-warning">'.__('Order::order.pending').'</span>' !!}
									@endif
								@endif
								{{-- ($type->cart_isshipping=='1') ? '<span class="label label-success">'.__('Order::order.done').'</span>' : '<span class="label label-warning">'.__('Order::order.pending').'</span>' --}}
							</td>
							@endif
							<td class="text-center">
								@if($list!='3')
									<a data-toggle="tooltip" title="{{ __('Admin::base.detail') }}" class="btn btn-xs btn-info" href="{{ route('order.showquotation',Crypt::encrypt($type->cart_id)) }}"><i class="fa fa-info-circle"></i></a>
								@else
									<a data-toggle="tooltip" data-id="{{ $type->cart_id }}" title="{{ __('Order::order.orderdetail') }}" class="btn btn-xs btn-info orderdetail"><i class="fa fa-info-circle"></i></a>
									@if($type->cart_isshipping=='0')
									<a data-toggle="tooltip" data-id="3" data-cartid="{{ $type->cart_id }}" data-trackcode="{{ $type->cart_courrierno }}" title="{{ __('Admin::base.update').' '.__('Order::order.shippingstatus') }}" class="btn btn-xs btn-default updatestatus"><i class="fa fa-ship"></i></a>
									@endif
								@endif
								@if($type->cart_isshipping == 0)
									<a data-toggle="tooltip" title="{{ ($type->cart_isinvoice=='1') ? __('Order::order.cancelorder') : __('Admin::base.delete') }}" class="btn btn-xs btn-danger deletedata" value="{{ route('order.deletecart',$type->cart_id) }}"><i class="fa fa-times-circle"></i></a>
								@endif
								@if($type->cart_confirm == 1)
									@if($type->cart_cancel==0)
										@if($type->cart_isinvoice=='1')
											<a href="{{ route('order.printdoc',[Crypt::encrypt($type->cart_id),'do']) }}" data-toggle="tooltip" title="{{ __('Admin::base.print').' '.__('Order::order.deliveryorder') }}" class="btn btn-xs btn-primary"><i class="fa fa-print"></i></a>
											<a href="{{ route('order.printdoc',[Crypt::encrypt($type->cart_id),'inv']) }}" data-toggle="tooltip" title="{{ __('Admin::base.print').' '.__('Order::order.taxinvoice') }}" class="btn btn-xs btn-warning"><i class="fa fa-print"></i></a>
										@else	
											<a href="{{ route('order.printdoc',[Crypt::encrypt($type->cart_id),'quo']) }}" data-toggle="tooltip" title="{{ __('Admin::base.print') }}" class="btn btn-xs btn-warning"><i class="fa fa-print"></i></a>
										@endif
									@endif
								@endif
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="8">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $types->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
		</div>
	</div>

@stop

@section('footer')

<div class="modal modal-default fade" id="modal-3">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Order::order.orderdetail') !!}</h4>
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

<div class="modal modal-default fade" id="modal-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modaltitle">{{ __('Admin::base.update').' '.__('Order::order.shippingstatus') }}</h4>
            </div>
            {!! Form::open(['action'=>'\App\Modules\Order\Controllers\OrderController@updatequotationstatus', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'modalform']) !!}
            <input type="hidden" name="modal_cartid" id="modal_cartid" value="">
            <input type="hidden" name="modal_type" id="modal_type" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Order::order.trackcodeno') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input type="text" class="form-control" name="trackcode" id="trackcode" value=""> 
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

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

	$('.updatestatus').on('click', function(){

        var id = $(this).data('id');
        var cartid = $(this).data('cartid');
        var trackcode = $(this).data('trackcode');
        $('#modal_type').val(id);
        $('#modal_cartid').val(cartid);
        $('#trackcode').val(trackcode);
        $('#modal-1').modal('show');

    });

	$('.modalupdatestatus').on('click', function() {

        var type = $('#modal_type').val();

        if($('#trackcode').val()==''){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
            return false;
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
		  confirmButtonText: '{{ __("Admin::base.yesdeleteit") }}',
		  
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

  	$('.orderdetail').on('click', function() {

  		var cartid = $(this).data('id');
		$('.modalbody3').empty();

        $.ajax({
            url: '{{ URL::to("order/getorderinfo") }}',
            data: {cartid: cartid},
            type: 'get',
            dataType: 'json',
            success:function(data) {
                console.log(data);

                $('.modalbody3').append(data);

            }
        });

        $('#modal-3').modal('show');

	});

</script>
@stop