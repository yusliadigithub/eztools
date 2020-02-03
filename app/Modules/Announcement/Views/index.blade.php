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
				    
					@hasanyrole('admin','superadmin')
					    <a href="{{ URL::to('announcement/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"> Add Announcement</i></a>
					@endhasanyrole
					
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
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
							<th class="column-title text-center" width="20%">Created By</th>
							@hasanyrole('admin','superadmin')
							<th class="column-title text-center" width="20%">Created To</th>
							@endhasanyrole
							<th class="column-title text-center" width="20%">Subject</th>
							<th class="column-title text-center" width="15%">Status</th>
							<th class="column-title text-center" width="15%">Created At</th>
							@hasanyrole('admin','superadmin')
							<th class="column-title text-center" width="10%">Action</th>
							@endhasanyrole
						</tr>
					</thead>
					<tbody>
						@foreach ($announcement as $announ)
						<tr>
							<td align="center">{{ $announ->announcement_from }}</td>
							@hasanyrole('admin','superadmin')
							<td align="center">{{ $announ->announcement_to }}</td>
							@endhasanyrole
							<td align="center">{{ $announ->subject }}</td>
							<td align="center">{{ $announ->status }}
							</td>
							<td align="center">{{ $announ->created_at }}</td>
							@hasanyrole('admin','superadmin')
							<td align="center">
								<a href="{{ URL::to('announcement/show', $announ->announcement_ID) }}" align="center"><i class="fa fa-edit "></i></a>&nbsp;&nbsp;&nbsp;
								<a class="deletedata" value="{{ route('announcement.delete', $announ->announcement_ID) }}" class="btn btn-sm btn-primary" align="center"><i class="fa fa-trash "></i></a>
							</td>
							@endhasanyrole
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>


@stop

@section('footer')
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