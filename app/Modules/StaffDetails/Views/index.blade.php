@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')


	<div class="box">
		<div class="box-header with-border">
			<div class="row">
			@hasanyrole('admin','superadmin')
				<div class="pull-left col-sm-12 col-md-6 col-lg-6">
					<a href="{{ URL::to('staffdetails/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"> Add User</i></a>
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			@endhasanyrole
			</div>
		</div>
		 <div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-2">{{ __('Admin::user.searchby') }}</label>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('user') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			</div>

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title text-center" width="20%">Name</th>
							<th class="column-title text-center" width="20%">Staff ID</th>
							<th class="column-title text-center" width="30%">Email</th>
							<th class="column-title text-center" width="15%">Mobile No</th>
							<th class="column-title text-center" width="10%">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($staffdetails as $staff)
						<tr>
							<td class="text-center">{{ $staff->staff_name }}</td>
							<td class="text-center">{{ $staff->staff_id }}</td>
							<td class="text-center">{{ $staff->staff_email }}</td>
							<td class="text-center">{{ $staff->staff_mobileno }}</td>
							<td class="text-center">
								<a href="{{ URL::to('staffdetails/show', $staff->id) }}"><i class="fa fa-edit "></i></a>&nbsp;&nbsp;&nbsp;
							@hasanyrole('admin','superadmin')
								<a class="deletedata" value="{{ route('staffdetails.delete', $staff->id) }}" class="btn btn-sm btn-primar" align="center"><i class="fa fa-trash "></i></a>
							@endhasanyrole
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				{{ $staffdetails->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
		</div>
	</div>


@stop

@section('footer')
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Staff</h4>
        </div>
        {!! Form::open(['action'=>'\App\Modules\StaffDetails\Controllers\StaffDetailsController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left']) !!}
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.fullname') }}</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input class="form-control" type="text" name="name" placeholder="{{ __('Admin::user.fullname') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.username') }}</label>
					<div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control" type="text" name="username" placeholder="{{ __('Admin::user.username') }}" /></div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.password') }}</label>
					<div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control" type="password" name="password" placeholder="{{ __('Admin::user.password') }}" /></div>				
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::user.email') }}</label>
					<div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control" type="email" name="email" placeholder="{{ __('Admin::user.email') }}" /></div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Number</label>
					<div class="col-md-6 col-sm-6 col-xs-12"><input class="form-control" type="text" name="mobile_no" placeholder="mobile Numberr" /></div>
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

<script>
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