@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')


	<div class="box box-primary">
		<div class="box-header with-border">
			<div class="row">
				<div class="pull-left col-sm-12 col-md-6 col-lg-6">
            @hasanyrole('agent')
					<a href="{{ URL::to('pdca/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"> Add P.D.C.A</i></a>
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
			  		<label class="form-label col-sm-12 col-md-1"></label>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('user') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			</div>

			<div class="panel panel-primary">
				<div class="panel-heading">
					Improvement
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped jambo_table bulk_action table-bordered">
							<thead>
								<tr class="headings">
									<th class="column-title text-center" width="20%">Name</th>
									<th class="column-title text-center" width="10%">Status</th>
									<th class="column-title text-center" width="10%">Date</th>
									<th class="column-title text-center" width="10%">Action</th>
								</tr>
							</thead>
							<tbody>
							@foreach ($pdca as $p)
							<tr>
								<td class="text-center">{{ $p->user_name }}</td>
								<td class="text-center">
									{!! ($p->status == 'approved') ? '<span class="label label-success">Approved</span>' : '<span class="label label-warning">Pending</span>' !!}
								</td>
								<td class="text-center">{{ $p->created_at }}</td>
								<td class="text-center">
									<a href="{{ URL::to('pdca/show', $p->pdca_id) }}" class="btn btn-sm btn-primar" align="center"><i class="fa fa-edit "></i></a>
									@hasanyrole('admin','superadmin')
									<a class="deletedata" value="{{ route('pdca.delete', $p->pdca_id) }}" class="btn btn-sm btn-primar" align="center"><i class="fa fa-trash "></i></a>
									@endhasanyrole
								</td>
							</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
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