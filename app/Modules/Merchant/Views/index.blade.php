@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')


	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="pull-left col-sm-12 col-md-6 col-lg-6">
					@if(Auth::user()->can('merchant.create'))
					<a href="{{ URL::to('merchant/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('Merchant::merchant.addmerchant') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		 <div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			  {{ Form::open(['action'=>'\App\Modules\Merchant\Controllers\MerchantController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="merchant_name">{{ __('Merchant::merchant.companyname') }}</option>
					  		<option value="merchant_ssmno">{{ __('Merchant::merchant.ssmno') }}</option>
					  		<option value="merchant_email">{{ __('Merchant::merchant.email') }}</option>
					  	</select>
				  	</div>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('user') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			  {{ Form::close() }}
			</div>

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title">{{ __('Merchant::merchant.companyname') }}</th>
							<!--th class="column-title text-center" width="10%">{{ __('Merchant::merchant.ssmno') }}</th-->
							<th class="column-title text-center" width="10%">Domain</th>
							<th class="column-title text-center">{{ __('Merchant::merchant.email') }}</th>
							<th class="column-title text-center">{{ __('Admin::user.agent') }}</th>
							<!--th class="column-title text-center" width="10%">{{ __('Merchant::merchant.expirydate') }}</th-->
							<th class="column-title text-center" width="12%">{{ __('Admin::user.registerdt') }}</th>
							<th class="column-title text-center" width="10%">{{-- __('Admin::base.action') --}}</th>
							<th class="column-title text-center" width="10%">{{ __('Merchant::package.package') }}</th>
							<th class="column-title text-center" width="8%">Status</th>
						</tr>
					</thead>
					<tbody>
						@if(count($merchants) > 0)
						@foreach($merchants as $merchant)
						<tr>
							<td>{!! $merchant->merchant_name.'<br>('.$merchant->merchant_ssmno.')' !!}</td>
							<!--td class="text-center">{!! $merchant->merchant_ssmno !!}</td-->
							<td class="text-center">
								@if($merchant->user->status_approve==1)
								<!--a href="{!! Request::root().'/frontend/'.$merchant->merchant_uuid !!}" target="_blank">{!! Request::root().'/frontend/'.$merchant->merchant_uuid !!}</a-->
								<a href="{!! 'http://'.$merchant->merchant_domain !!}" target="_blank">{!! $merchant->merchant_domain !!}</a>
								@else
								{!! '<span class="label label-warning">'.__('Admin::base.pendingapproval').'</span>' !!}
								@endif
							</td>
							<td>{!! $merchant->merchant_email !!}</td>
							<td>{!! $merchant->agent->detail->users_detail_name !!}</td>
							<!--td class="text-center">{!! ($merchant->merchant_expirydate) ? date( 'd F Y',strtotime($merchant->merchant_expirydate)) : '' !!}</td-->
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($merchant->created_at)) !!}</td>
							<td class="text-center">
								<div class="btn-group">
									<a data-toggle="tooltip" data-id="{!! $merchant->merchant_id !!}" class="btn btn-primary btn-xs datainfo">{{ __('Admin::base.action') }}</a>
									<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" type="button">
									<span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a data-toggle="tooltip" data-id="{!! $merchant->merchant_id !!}" href="{{ route('merchant.show',Crypt::encrypt($merchant->merchant_id)) }}"><i class="fa fa-info-circle"></i>  {{ __('Merchant::merchant.detail') }}</a></li>
										@if($merchant->user->status_approve == 1)
											@if(Auth::user()->can('merchant.branch.index'))
												<li><a data-toggle="tooltip" data-id="{!! $merchant->merchant_id !!}" href="{{ route('merchant.branch.index',Crypt::encrypt($merchant->merchant_id)) }}"><i class="fa fa-sitemap"></i>  {{ __('Merchant::branch.branch') }}</a></li>
											@endif
											<li><a data-toggle="tooltip" data-id="{!! $merchant->merchant_id !!}" href="{{ route('merchant.members',Crypt::encrypt($merchant->merchant_id)) }}"><i class="fa fa-users"></i>  {{ __('Merchant::merchant.websitemember') }}</a></li>
											@if($merchant->user->status == 0)
												@if(Auth::user()->can('user.enable'))
												<li><a data-toggle="tooltip" data-askmsg="{{ __('Admin::base.askenable') }}" class="enabledata" value="{{ route('user.enable', $merchant->user->id) }}"><i class="fa fa-check-circle"></i>  {{ __('Admin::user.enable') }}</a></li>
												@endif
											@endif
										@else
											@if(Auth::user()->can('user.approve'))
											<li><a data-toggle="tooltip" data-askmsg="{{ __('Admin::base.askapprove') }}" class="enabledata" value="{{ route('user.approve', $merchant->user->id) }}"><i class="fas fa-thumbs-up"></i>  {{ __('Admin::base.approve') }}</a></li>
											@endif
										@endif
										@if($merchant->user->status == 1)
											@if(Auth::user()->can('user.disable'))
											<li><a data-toggle="tooltip" data-askmsg="{{ __('Admin::base.askdisable') }}" class="enabledata" value="{{ route('user.disable', $merchant->user->id) }}"><i class="fa fa-minus-circle"></i>  {{ __('Admin::user.disable') }}</a></li>
											@endif
											@if(Auth::user()->can('merchant.showconfig'))
											<li><a data-toggle="tooltip" href="{{ route('merchant.showconfig',Crypt::encrypt($merchant->configuration->merchant_config_id)) }}"><i class="fa fa-wrench"></i>  {{ __('Merchant::merchant.ecommerceconf') }}</a></li>
											@endif
											@if(Auth::user()->can('merchant.pageindex'))
											<li><a data-toggle="tooltip" href="{{ route('merchant.pageindex',Crypt::encrypt($merchant->merchant_id)) }}"><i class="fa fa-envelope"></i>  {{ __('Merchant::merchant.ecommercepage') }}</a></li>
											@endif
											@if(Auth::user()->can('product.type.index'))
											<li><a data-toggle="tooltip" href="{{ route('merchant.supplier.index',Crypt::encrypt($merchant->merchant_id)) }}"><i class="fa fa-truck"></i>  {{ __('Merchant::supplier.supplier') }}</a></li>
											@endif
											@if(Auth::user()->can('product.type.index'))
											<li><a data-toggle="tooltip" href="{{ route('product.type.index',Crypt::encrypt($merchant->merchant_id)) }}"><i class="fas fa-clipboard-list"></i>  {{ __('Product::product.producttype') }}</a></li>
											@endif
											@if(Auth::user()->can('product.attribute.index'))
											<li><a data-toggle="tooltip" href="{{ route('product.attribute.index',Crypt::encrypt($merchant->merchant_id)) }}"><i class="fa fa-book"></i>  {{ __('Product::product.productvariant') }}</a></li>
											@endif
											@if(Auth::user()->can('product.index'))
											<li><a data-toggle="tooltip" href="{{ URL::to('product/'.Crypt::encrypt($merchant->merchant_id).'/index') }}"><i class="fa fa-tasks"></i>  {{ __('Product::product.product') }}</a></li>
											@endif
											{{-- @if(Auth::user()->can('product.productmovement')) --}}
											<li><a data-toggle="tooltip" href="{{ URL::to('product/productmovement/'.Crypt::encrypt($merchant->merchant_id)) }}"><i class="fa fa-chart-line"></i>  {{ __('Product::product.stockmovement') }}</a></li>
											{{-- @endif --}}
										@endif
										@if(strtotime($merchant->merchant_expirydate)>strtotime('2 week ago') )
										<!--li><a data-toggle="tooltip" data-id="{!! $merchant->merchant_id !!}" class="btn btn-xs btn-success datainfo"><i class="fa fa-plus-circle"></i>  {{ __('Merchant::merchant.renew') }}</a></li-->
										@endif
										@if($merchant->user->status_approve=='0')
											@if(Auth::user()->can('merchant.delete'))
											<li><a class="deletedata" value="{{ route('merchant.delete',$merchant->merchant_id) }}"><i class="fa fa-times-circle"></i>  {{ __('Admin::base.delete') }}</a></li>
											@endif
										@endif
									</ul>
								</div>
							</td>
							<td class="text-center">
								<div class="btn-group">
									<?php $merchantpkg = [];
										foreach($merchant->package->subpackage as $sub){
											$merchantpkg[] += $sub->merchant_subpackage_id;
										}
									?>
									<a data-toggle="tooltip" data-id="{!! $merchant->merchant_id !!}" class="btn btn-warning btn-xs packageinfo">{{ __('Merchant::package.package') }}</a>
									<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" type="button">
									<span class="caret"></span></button>
									<ul class="dropdown-menu">
										@if(count($subpackage)>0)
											<li><a href="#"><b>{{ $merchant->package->merchant_package_name }}</b></a></li>
											<li class="divider"></li>
											@foreach($subpackage as $sp)
												@if(in_array($sp->merchant_subpackage_id,$merchantpkg))
												{!! '<li><a href="#"><i class="fa fa-check"></i>  '.$sp->merchant_subpackage_desc.'</a></li>' !!}
												@else
												{!! '<li><a><i class="fa fa-window-minimize"></i>  '.$sp->merchant_subpackage_desc.'</a></li>' !!}
												@endif
											@endforeach
										@endif
									</ul>
								</div>
							</td>
							<td class="text-center">
								{{-- ($merchant->merchant_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Merchant::merchant.expired').'</span>' --}}
								{!! ($merchant->user->status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="7">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $merchants->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
		</div>
	</div>

@stop

@section('footer')

<div class="modal modal-default fade" id="modal-1">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><b id="titlem1">{!! __('Admin::base.action') !!}</b></h4>
            </div>

            <form class="form-horizontal form-label-left">
                <div class="modal-body modalbody">
                    
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

	@if( Session::get('modal') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
	@endif

	setInterval(function(){

        $('blink').each(function() {

            $(this).toggle();

        });

    }, 1200);

  	$('.datainfo').on('click', function() {

  		$('.modalbody').empty();
  		$('#titlem1').empty();
  		$('#titlem1').append('{!! __("Admin::base.action") !!}');
  		var id = $(this).data('id');

  		$.ajax({
            url: '{{ URL::to("merchant/getbutton") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {

            	console.log(data);
            	$('.modalbody').append(data);

            }
        });

  		$('#modal-1').modal('show');

	});

	$('.packageinfo').on('click', function() {

  		$('.modalbody').empty();
  		$('#titlem1').empty();
  		$('#titlem1').append('{!! __("Merchant::package.package") !!}');
  		var id = $(this).data('id');

  		$.ajax({
            url: '{{ URL::to("merchant/getbuttonpkg") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {

            	console.log(data);
            	$('.modalbody').append(data);

            }
        });

  		$('#modal-1').modal('show');

	});

  	$(document).on('click', '.enabledata', function() {
	//$('.enabledata').on('click', function() {
  	
  		var url = $(this).attr('value');
  		var askmsg = $(this).data('askmsg');

	  	swal({
		  	title: askmsg,
		  	//text: "{{ __('Admin::base.norevert') }}",
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

  	$(document).on('click', '.deletedata', function() {
  	//$('.deletedata').on('click', function() {
  	
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

</script>
@stop