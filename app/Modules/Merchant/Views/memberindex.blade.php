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
			{{ Form::open(['action'=>['\App\Modules\Merchant\Controllers\MerchantController@members', Crypt::encrypt($merchant_id)], 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="email">{{ __('Admin::base.email') }}</option>
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
							<th class="column-title">{{ __('Admin::user.fullname') }}</th>
							<th class="column-title">{{ __('Admin::user.email') }}</th>
							<th class="column-title text-center" width="13%">{{ __('Admin::user.gender') }}</th>
							<th class="column-title text-center" width="13%">D.O.B</th>
							<th class="column-title text-center" width="13%">{{ __('Admin::base.created_at') }}</th>
							<th class="column-title text-center" width="10%">Status</th>
							<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td>{!! $type->guest_fullname !!}</td>
							<td>{!! $type->email !!}</td>
							<td class="text-center">{!! ($type->guest_gender == 'M') ? 'Male' : 'Female' !!}</td>
							<td class="text-center">{!! date( 'd F Y', strtotime($type->guest_dob)) !!}</td>
							<td class="text-center">{!! date( 'd F Y', strtotime($type->created_at)) !!}</td>
							<td class="text-center">
								{!! ($type->guest_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
							</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Admin::base.changepassword') }}" data-name="{!! $type->guest_fullname !!}" data-id="{!! $type->guest_id !!}" data-email="{!! $type->email !!}" class="btn btn-xs btn-warning changepassword"><i class="fa fa-key"></i></a>

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

<div class="modal modal-warning fade" id="modal-1">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Admin::base.changepassword') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\MerchantController@changememberpassword', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1']) !!}
			<input type="hidden" name="guest_id" id="guest_id" class="modaldata" value="{{ old('guest_id') }}">
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.fullname') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control modaldata" type="text" value="{{ old('guestfullname') }}" name="guestfullname" id="guestfullname" readonly />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.email') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control modaldata" type="text" value="{{ old('guestemail') }}" name="guestemail" id="guestemail" readonly />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.password') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control modaldata" type="password" value="{{ old('guestemail') }}" name="password" id="password"  />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.confirmpassword') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control modaldata" type="password" value="{{ old('guestemail') }}" name="password-confirm" id="password-confirm"  />
					</div>					
				</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				{{-- @if(Auth::user()->can('product.type.store')) --}}
				<button type="button" class="btn btn-sm btn-success addbtn"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
				{{-- @endif --}}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div> 

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

	@if( Session::get('modal1') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
	@endif

	$('.changepassword').on('click', function(){

		$('.modaldata').val('');
		$('#guest_id').val($(this).data('id'));
		$('#guestfullname').val($(this).data('name'));
		$('#guestemail').val($(this).data('email'));
		$('#modal-1').modal('show');

	});

	$('.addbtn').on('click', function() {

		if( $('#password').val()=='' || $('#password-confirm').val()=='' ){
	    	swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
	    	return false;
	    }else{
	    	if( $('#password').val() != $('#password-confirm').val()=='' ){
	    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::user.passwordnotmatch") }}', 'error');
	    	}
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
            swal(
              '{{ __("Admin::base.success") }}!',
              '',
              'success'
            )
        });

    });

</script>
@stop