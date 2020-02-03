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
					<a href="javascript:;" data-toggle="modal" data-target="#modal-1" data-backdrop="static" class="btn btn-sm btn-primary"><i class="fas fa-plus-circle"></i> {{ __('Admin::user.registeruser') }}</a>
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		 <div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			  {{ Form::open(['action'=>'\App\Modules\Admin\Controllers\UserController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="name">{{ __('Admin::user.fullname') }}</option>
					  		<!--option value="username">{{ __('Admin::user.username') }}</option-->
					  		<option value="email">{{ __('Admin::user.email') }}</option>
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
		 {!! Form::open(['action'=>'\App\Modules\Admin\Controllers\UserController@processaction', 'method'=>'post', 'class'=>'form-horizontal']) !!}
		 {{ csrf_field() }}
		 	@if(Auth::user()->can('perfom.bulk.action'))
			<div class="row">
				<div class="col-xs-12 col-md-6 col-sm-6">
					<div class="form-group">
						<div class="col-sm-4">
							{{ Form::actions('useraction',[''=>'-- '.__('Admin::base.select_action').' ', 'enable'=>__('Admin::user.enable')], '', ['class'=>'form-control input-sm']) }}
						</div>
						<div class="col-sm-1"><button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button></div>
					</div>
				</div>
			</div>
			@endif

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<!--th><input type="checkbox" onclick="toggleCheck(this)" name="checkall" class="checkall"></th-->
							<th class="column-title">{{ __('Admin::user.fullname') }}</th>
							<th class="column-title">{{ __('Admin::user.username') }}</th>
							<th class="column-title">{{ __('Admin::user.email') }}</th>
							<!--th class="column-title">Domain</th-->
							<th class="column-title text-center" width="14%">{{ __('Admin::user.registerdt') }}</th>
							<th class="column-title text-center" width="13%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($users) > 0)
						@foreach($users as $user)
						<tr>
							<!--td>
								@if(!in_array($user->id,[1,2]) && $user->status_approve!='1') 
									<input type="checkbox" class="" value="{!! $user->id !!}" name="uid[]"> 
								@endif
							</td-->
							<td>{!! $user->name !!}</td>
							<td>{!! $user->username !!}</td>
							<td>{!! $user->email !!}</td>
							<!--td>{!! ($user->merchant!='') ? '<a href="http://'.$user->merchant->merchant_domain.'" target="_blank">'.$user->merchant->merchant_domain : '' !!}</td-->
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($user->created_at)) !!}</td>
							<td class="text-center">
								@if(in_array($user->id,[1,2]))
									<!--span class="label label-info">{{ __('Admin::base.permanentdata') }}</span-->
								@else
									<!-- modal <a data-toggle="tooltip" title="{{ __('Admin::user.userdetail') }}" data-id="{!! $user->id !!}" class="btn btn-xs btn-info userinfo"><i class="fa fa-info-circle"></i></a> -->
									<a data-toggle="tooltip" title="{{ __('Admin::user.userdetail') }}" class="btn btn-xs btn-info" href="{{ route('user.showagent', Crypt::encrypt($user->id)) }}"><i class="fa fa-info-circle"></i></a>
									<!--a class="btn btn-xs btn-warning editpermission" data-toggle="tooltip" title="{{ __('Admin::user.userpermission') }}" data-id="{!! $user->id !!}" data-name="{!! $user->name !!}" data-email="{!! $user->email !!}"><i class="fa fa-edit "></i></a-->
									@if($user->merchant!='')
										<!--a data-toggle="tooltip" title="{{ __('Admin::user.setdomainname') }}" class="btn btn-xs btn-warning setdomain" data-id="{{ $user->merchant_id }}" data-name="{{ $user->merchant->merchant_domain }}"><i class="fa fa-laptop"></i></a-->
									@endif
									@if($user->status == 1)
										@if(Auth::user()->can('user.disable'))
										<a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" data-askmsg="{{ __('Admin::base.askdisable') }}" class="btn btn-xs btn-primary enabledata {{ $user->status==0 ? 'disabled' : '' }}" value="{{ route('user.disable', $user->id) }}"><i class="fa fa-minus-circle"></i></a>
										@endif
									@endif
									@if($user->status_approve == 1)
										@if($user->status == 0)
											@if(Auth::user()->can('user.enable'))
											<a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" data-askmsg="{{ __('Admin::base.askenable') }}" class="btn btn-xs btn-success enabledata" value="{{ route('user.enable', $user->id) }}"><i class="fa fa-check-circle"></i></a>
											@endif
										@endif
									@else
										@if(Auth::user()->can('user.approve'))
										<a data-toggle="tooltip" title="{{ __('Admin::base.approve') }}" data-askmsg="{{ __('Admin::base.askapprove') }}" class="btn btn-xs btn-success enabledata" value="{{ route('user.approve', $user->id) }}"><i class="fas fa-thumbs-up"></i></a>
										@endif
										@if(Auth::user()->can('user.delete'))
										<a class="btn btn-xs btn-danger deleteuser" value="{{ route('user.delete',$user->id) }}"><i class="fa fa-times-circle"></i></a>
										@endif
									@endif

									<!--a data-toggle="tooltip" title="{{ __('Admin::base.delete') }}" class="btn btn-xs btn-danger" id="deleteuser" href="javascript:;{{-- route('user.delete',$user->id) --}}"><i class="fa fa-times-circle"></i></a-->

								@endif
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="7">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $users->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
			{!! Form::close() !!}
		</div>
	</div>

@stop

@section('footer')
<div class="modal fade" id="modal-1">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Admin::user.adduser') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Admin\Controllers\UserController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left']) !!}
			<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.fullname') }}</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input class="form-control" type="text" value="{{ old('name') }}" name="name" placeholder="{{ __('Admin::user.fullname') }}" />
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.username') }}</label>
						<div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control" value="{{ old('username') }}" type="text" name="username" placeholder="{{ __('Admin::user.username') }}" /></div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::role.roles') }}</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							@foreach ($roles as $role)
								{{ Form::radio('roles[]',  $role->id, '', ['id'=>$role->name]) }}&nbsp;
					            {{ Form::label($role->name, ucfirst($role->name) ) }}<br>
					        @endforeach
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.password') }}</label>
						<div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control" type="password" name="password" placeholder="{{ __('Admin::user.password') }}" /></div>				
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.email') }}</label>
						<div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control" value="{{ old('email') }}" type="email" name="email" placeholder="{{ __('Admin::user.email') }}" /></div>					
					</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div> 

<div class="modal fade" id="modal-2">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Admin::permission.permission') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Admin\Controllers\UserController@storepermission', 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'id'=>'userpermissionform']) !!}
			<div class="modal-body">
				<div class="form-group">
					<input type="hidden" name="useridmodal2" id="useridmodal2">
					<input type="hidden" name="usernamemodal2" id="usernamemodal2">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.fullname') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<label class="control-label" id="namepermission"></label>
						<!--input class="form-control" type="text" value="" id="rolenamepermission" /-->
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.email') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<label class="control-label" id="emailpermission"></label>
					</div>					
				</div>
				<!--div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::role.rolename') }}</label>			
					<div class="col-md-7 col-sm-7 col-xs-12" id="rolediv">
						<div class="checkbox"><label><input type="checkbox"> Check me out</label></div>
					</div>
				</div-->
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::permission.permission') }}</label>			
					<div class="col-md-7 col-sm-7 col-xs-12" id="permissiondiv">
						<div class="checkbox"><label><input type="checkbox"> Check me out</label></div>
					</div>
				</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				<button type="button" class="btn btn-sm btn-success savebtn"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="modal-3">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Admin::user.userdetail') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			<form class="form-horizontal form-label-left">
			<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<img id="personalphoto" src="" alt="" height="120" width="110">
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.fullname') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control" type="text" id="fullname" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.email') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control" type="text" id="email" disabled/>
						</div>					
					</div>
					<!--div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.gender') }}</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input class="form-control" type="text" id="gender" disabled/>
						</div>					
					</div-->
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.idno') }}</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input class="form-control" type="text" id="idno" disabled/>
						</div>					
					</div>
					<!--div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.nationality') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control" type="text" id="nationality" disabled/>
						</div>					
					</div-->
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.address') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control" type="text" id="address1" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control" type="text" id="address2" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control" type="text" id="address3" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.postcode') }}</label>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<input class="form-control" type="text" id="postcode" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.district') }}</label>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<input class="form-control" type="text" id="district" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.state') }}</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input class="form-control" type="text" id="state" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.mobileno') }}</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input class="form-control" type="text" id="mobileno" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.homeno') }}</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input class="form-control" type="text" id="homeno" disabled/>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.workno') }}</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input class="form-control" type="text" id="workno" disabled/>
						</div>					
					</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				<!--button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button-->
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-4">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Admin::user.setdomainname') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\MerchantController@setdomain', 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'id'=>'setdomainform']) !!}
			<div class="modal-body">
				<div class="form-group">
					<input type="hidden" name="merchant_id" id="merchant_id" class="domaindata">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.domainname') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control domaindata" type="text" id="merchant_domain" name="merchant_domain" value="{{ old('merchant_domain') }}" />
					</div>					
				</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				<button type="button" class="btn btn-sm btn-success savedomain"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

	@if( Session::get('modal') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
	@endif

	@if( Session::get('modal4') )
		$('#modal-4').modal( {backdrop: 'static', keyboard: false} ); 
	@endif

  	function toggleCheck(source) {
    	checkboxes = document.getElementsByTagName('input');
    
    	for(var i=0, l=checkboxes.length; i<l; i++) {
      		checkboxes[i].checked = source.checked;
    	}
  	}

  	$('.setdomain').on('click', function() {

  		$('.domaindata').val('');

		$('#merchant_id').val($(this).data('id'));
		$('#merchant_domain').val($(this).data('name'));

		$('#modal-4').modal('show');

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

  	$('.userinfo').on('click', function() {

  		var id = $(this).data('id');

  		$.ajax({
            url: '{{ URL::to("user/getUserInfo") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {
            	console.log(data);
            	$('#fullname').val(data.salutation_desc+' '+data.users_detail_name);
		  		$('#email').val(data.email);
		  		//$('#gender').val(data.gender_desc);
		  		$('#idno').val(data.users_detail_idno);
		  		//$('#nationality').val(data.country_desc);
		  		$('#address1').val(data.users_detail_address1);
		  		$('#address2').val(data.users_detail_address2);
		  		$('#address3').val(data.users_detail_address3);
		  		$('#postcode').val(data.users_detail_postcode);
		  		$('#district').val(data.district_desc);
		  		$('#state').val(data.state_desc);
		  		$('#mobileno').val(data.users_detail_mobileno);
		  		$('#homeno').val(data.users_detail_homeno);
		  		$('#workno').val(data.users_detail_workno);
		  		$('#personalphoto').attr('src','{{ asset("") }}'+data.upload_path+data.upload_filename);

            }
        });

  		$('#modal-3').modal('show');

	});

  	$('.editpermission').on('click', function() {

		var id = $(this).data('id');
		var name = $(this).data('name').toUpperCase();
		var email = $(this).data('email');

		$('#rolediv').empty();
		$('#permissiondiv').empty();
		$('#namepermission').empty();
		$('#emailpermission').empty();
		
		$('#namepermission').append(name);
		$('#emailpermission').append(email);
		$('#useridmodal2').val(id);
		$('#usernamemodal2').val(name);

		$.ajax({
            url: '{{ URL::to("admin/permission/getUserPermission") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {

            	var permission = data.permission;
            	var userpermissions = data.userhaspermissions;
            	//console.log(rolepermissions);
            	$('#permissiondiv').append('<div class="checkbox"><label><input type="checkbox" onclick="toggleCheck(this)" name="checkall" class="checkall">All</label></div>');

            	$.each(permission, function(key,val) {
            		if (jQuery.inArray(val.id, userpermissions)!='-1') {
            			var ischecked = 'checked';
            		}else{
            			var ischecked = '';
            		}
            		$('#permissiondiv').append('<div class="checkbox"><label><input type="checkbox" name="permission[]" value="'+val.id+'" '+ischecked+'>'+val.description+'</label></div>');
            	});

            }
        });

        /*$.ajax({
            url: '{{ URL::to("user/getUserRole") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {

            	var role = data.role;
            	var userroles = data.userhasroles;
            	//console.log(rolepermissions);
            	$('#rolediv').append('<div class="checkbox"><label><input type="checkbox" onclick="toggleCheck(this)" name="checkall" class="checkall">All</label></div>');

            	$.each(role, function(key,val) {
            		if (jQuery.inArray(val.id, userroles)!='-1') {
            			var ischecked = 'checked';
            		}else{
            			var ischecked = '';
            		}
            		$('#rolediv').append('<div class="checkbox"><label><input type="checkbox" name="role[]" value="'+val.id+'" '+ischecked+'>'+val.description+'</label></div>');
            	});

            }
        });*/

        $('#modal-2').modal('show');

	});	

	//submiteditpermission
	$('.savebtn').on('click', function() {

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
                $("#userpermissionform").submit();
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

    $('.savedomain').on('click', function() {

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
                $("#setdomainform").submit();
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

  	$('.deleteuser').on('click', function() {
  	
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

     			// alert( url );
     			 window.location.href = url;
          
	        	// $.ajax({
	        	// 	url: 'delete.php',
	        	// 	type: 'POST',
	         //   		data: 'delete='+productId,
	         //   		dataType: 'json'
	        	// });
	        // 	.done(function(response){
	        //  		swal('Deleted!', response.message, response.status);
	     			// readProducts();
	        // 	})
	        // 	.fail(function(){
	        //  		swal('Oops...', 'Something went wrong with ajax !', 'error');
	        // 	});
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