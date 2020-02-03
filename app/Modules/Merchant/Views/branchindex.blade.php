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
					@if(Auth::user()->can('merchant.branch.create'))
					<a href="{{ route('merchant.branch.create',$merchantid) }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('Merchant::branch.addbranch') }}</a>
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
					  		<option value="merchant_name">{{ __('Merchant::branch.fullname') }}</option>
					  		<option value="merchant_ssmno">{{ __('Merchant::branch.username') }}</option>
					  		<option value="merchant_email">{{ __('Merchant::branch.email') }}</option>
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
							<!--th class="column-title">{{ __('Merchant::branch.branch') }}</th-->
							<th class="column-title">{{ __('Merchant::merchant.contactperson') }}</th>
							<th class="column-title">{{ __('Merchant::branch.mobileno') }}</th>
							<th class="column-title text-center">{{ __('Merchant::merchant.merchant') }}</th>
							<th class="column-title text-center">{{ __('Merchant::branch.email') }}</th>
							<th class="column-title text-center" width="16%">{{ __('Admin::user.registerdt') }}</th>
							<th class="column-title text-center" width="10%">Status</th>
							<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($branches) > 0)
						@foreach($branches as $branch)
						<tr>
							<!--td>{!! $branch->merchant_branch_name !!}</td--> 
							<td>{!! $branch->merchant_branch_person_incharge !!}</td>
							<td>{!! $branch->merchant_branch_mobileno !!}</td>
							<td>{!! $branch->merchant->merchant_name !!}</td>
							<td>{!! $branch->merchant_branch_email !!}</td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($branch->created_at)) !!}</td>
							<td class="text-center">
								{!! ($branch->user->status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
							</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Merchant::branch.detail') }}" data-id="{!! $branch->merchant_branch_id !!}" class="btn btn-xs btn-info" href="{{ route('merchant.branch.show',Crypt::encrypt($branch->merchant_branch_id)) }}"><i class="fa fa-info-circle"></i></a>
								@if($branch->user->status_approve == 1)
									@if($branch->user->status == 1)
										@if(Auth::user()->can('merchant.branch.disable'))
										<a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" data-askmsg="{{ __('Admin::base.askdisable') }}" class="btn btn-xs btn-primary enabledata {{ $branch->user->status==0 ? 'disabled' : '' }}" value="{{ route('merchant.branch.disable', $branch->user->id) }}"><i class="fa fa-minus-circle"></i></a>
										@endif
									@endif
									@if($branch->user->status == 0)
										@if(Auth::user()->can('merchant.branch.enable'))
										<a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" data-askmsg="{{ __('Admin::base.askenable') }}" class="btn btn-xs btn-success enabledata" value="{{ route('merchant.branch.enable', $branch->user->id) }}"><i class="fa fa-check-circle"></i></a>
										@endif
									@endif
								@else
									@if(Auth::user()->can('merchant.branch.approve'))
									<a data-toggle="tooltip" title="{{ __('Admin::base.approve') }}" data-askmsg="{{ __('Admin::base.askapprove') }}" class="btn btn-xs btn-success enabledata" value="{{ route('merchant.branch.approve', $branch->user->id) }}"><i class="fas fa-thumbs-up"></i></a>
									@endif
									@if(Auth::user()->can('merchant.branch.delete'))
									<a class="btn btn-xs btn-danger deletedata" value="{{ route('merchant.branch.delete',$branch->merchant_branch_id) }}"><i class="fa fa-times-circle"></i></a>
									@endif
								@endif
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="7">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $branches->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
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