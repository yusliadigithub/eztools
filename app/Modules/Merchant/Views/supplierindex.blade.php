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
					@if(Auth::user()->can('merchant.supplier.create'))
					<a href="{{ URL::to('merchant/supplier/'.Crypt::encrypt($merchant->merchant_id).'/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('Merchant::supplier.addsupplier') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		 <div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			  {{ Form::open(['action'=>'\App\Modules\Merchant\Controllers\SupplierController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="merchant_supplier_name">{{ __('Merchant::supplier.companyname') }}</option>
					  		<option value="merchant_supplier_ssmno">{{ __('Merchant::supplier.ssmno') }}</option>
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
							<th class="column-title">{{ __('Merchant::supplier.supplier') }}</th>
							<th class="column-title text-center" width="16%">{{ __('Merchant::supplier.ssmno') }}</th>
							<th class="column-title text-center" width="16%">{{ __('Merchant::supplier.mobileno') }}</th>
							<th class="column-title">{{ __('Merchant::merchant.merchant') }}</th>
							<th class="column-title text-center" width="16%">{{ __('Admin::user.registerdt') }}</th>
							<th class="column-title text-center" width="10%">Status</th>
							<th class="column-title text-center" width="16%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($suppliers) > 0)
						@foreach($suppliers as $supplier)
						<tr>
							<td>{!! $supplier->merchant_supplier_name !!}</td>
							<td>{!! $supplier->merchant_supplier_ssmno !!}</td>
							<td>{!! $supplier->merchant_supplier_mobileno !!}</td>
							<td>{!! $supplier->merchant->merchant_name !!}</td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($supplier->created_at)) !!}</td>
							<td class="text-center">
								{!! ($supplier->user->status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
							</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Merchant::supplier.detail') }}" data-id="{!! $supplier->merchant_supplier_id !!}" class="btn btn-xs btn-info" href="{{ route('merchant.supplier.show',Crypt::encrypt($supplier->merchant_supplier_id)) }}"><i class="fa fa-info-circle"></i></a>
								
								<a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" data-askmsg="{{ __('Admin::base.askdisable') }}" class="btn btn-xs btn-primary enabledata {{ $supplier->merchant_supplier_status==0 ? 'disabled' : '' }}" value="{{ route('merchant.supplier.disable', $supplier->merchant_supplier_id) }}"><i class="fa fa-minus-circle"></i></a>

								@if($supplier->merchant_supplier_status == 0)
								<a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" data-askmsg="{{ __('Admin::base.askenable') }}" class="btn btn-xs btn-success enabledata" value="{{ route('merchant.supplier.enable', $supplier->merchant_supplier_id) }}"><i class="fa fa-check-circle"></i></a>
								@endif
								<a class="btn btn-xs btn-danger deletedata" value="{{ route('merchant.supplier.delete',$supplier->merchant_supplier_id) }}"><i class="fa fa-times-circle"></i></a>
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="7">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $suppliers->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
		</div>
	</div>

@stop

@section('footer')

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

  		var id = $(this).data('id');

  		$.ajax({
            url: '{{ URL::to("user/getUserInfo") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {
            	console.log(data);
            	$('#fullname').val(data.salutation_desc+' '+data.users_detail_name);
		  		$('#email').val(data.email);
		  		$('#gender').val(data.gender_desc);
		  		$('#idno').val(data.users_detail_icno);
		  		$('#nationality').val(data.country_desc);
		  		$('#mobileno').val(data.users_detail_mobileno);
		  		$('#homeno').val(data.users_detail_homeno);
		  		$('#workno').val(data.users_detail_workno);

            }
        });

  		$('#modal-1').modal('show');

	});

	$('.enabledata').on('click', function() {
  	
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

</script>
@stop