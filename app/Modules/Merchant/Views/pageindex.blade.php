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
					@if(Auth::user()->can('merchant.createpage'))
					<a href="{{ URL::to('merchant/createpage').'/'.$merchantid }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('Merchant::merchant.addnewpage') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Merchant\Controllers\TypeController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="merchant_page_title">{{ __('Merchant::merchant.pagetitle') }}</option>
					  		<option value="merchant_page_content">{{ __('Merchant::merchant.pagecontent') }}</option>
					  	</select>
				  	</div>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('Merchant/type') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			{{ Form::close() }}
			</div>
		 	{{ csrf_field() }}

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title">{{ __('Merchant::merchant.pagetitle') }}</th>
							<th class="column-title text-center" width="15%">{{ __('Merchant::merchant.publishstatus') }}</th>
							<th class="column-title text-center" width="15%">{{ __('Merchant::merchant.publishdate') }}</th>
							<th class="column-title text-center" width="18%">{{ __('Admin::base.updateddate') }}</th>
							<th class="column-title text-center" width="15%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td>{!! $type->merchant_page_title !!}</td>
							<td class="text-center">
                            	{!! ($type->merchant_page_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
                            </td>
							<td class="text-center">{!! ($type->merchant_page_date!='') ? date( 'd F Y', strtotime($type->merchant_page_date)) : '' !!}</td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($type->updated_at)) !!}</td>
							<td class="text-center">
								@if(Auth::user()->can('merchant.showpage'))
								<a href="{{ URL::to('merchant/showpage').'/'.Crypt::encrypt($type->merchant_page_id) }}" data-toggle="tooltip" title="{{ __('Merchant::merchant.editpage') }}" class="btn btn-xs btn-info infodata"><i class="fa fa-edit"></i></a>
								@endif
								@if($type->merchant_page_status == 0)
									@if(Auth::user()->can('merchant.enablepage'))
									<a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" data-askmsg="{{ __('Admin::base.askenable') }}" class="btn btn-xs btn-success enabledata" value="{{ route('merchant.enablepage', $type->merchant_page_id) }}"><i class="fa fa-check-circle"></i></a>
									@endif
								@else
									@if(Auth::user()->can('merchant.disablepage'))
									<a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" data-askmsg="{{ __('Admin::base.askdisable') }}" class="btn btn-xs btn-primary enabledata" value="{{ route('merchant.disablepage', $type->merchant_page_id) }}"><i class="fa fa-minus-circle"></i></a>
									@endif
								@endif
								@if(Auth::user()->can('merchant.deletepage'))
								<a class="btn btn-xs btn-danger deletedata" value="{{ route('merchant.deletepage',$type->merchant_page_id) }}"><i class="fa fa-times-circle"></i></a>
								@endif
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="5">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $types->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
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

	$('.enabledata').on('click', function() {
    
        var url = $(this).attr('value');
        var askmsg = $(this).data('askmsg');

        swal({
            title: askmsg,
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