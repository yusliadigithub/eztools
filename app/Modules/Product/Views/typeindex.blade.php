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
					@if(Auth::user()->can('product.type.store'))
					<a class="btn btn-sm btn-primary adddata"><i class="fa fa-plus-circle"></i> {{ __('Product::product.addproducttype') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Product\Controllers\TypeController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="product_type_desc">{{ __('Product::product.producttypename') }}</option>
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
							<th class="column-title" width="15%">{{ __('Product::product.image') }}</th>
							<th class="column-title">{{ __('Product::product.producttype') }}</th>
							<th class="column-title">{{ __('Product::product.producttypeparent') }}</th>
							<th class="column-title">{{ __('Product::product.merchant') }}</th>
							<th class="column-title text-center" width="15%">{{ __('Admin::base.createddate') }}</th>
							<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td class="text-center"><img src="{!! (!empty($type->image)) ? asset($type->image->upload_path.$type->image->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120"></td>
							<td>{!! $type->product_type_desc !!}</td>
							<td>{!! ($type->product_type_parent_id!=0) ? $type->parent->product_type_desc : 'N/A' !!}</td>
							<td>{!! $type->merchant->merchant_name !!}</td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($type->created_at)) !!}</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Product::product.editproducttype') }}" data-id="{!! $type->product_type_id !!}" class="btn btn-xs btn-info infodata"><i class="fa fa-edit"></i></a>
								@if(Auth::user()->can('product.type.delete'))
								<a class="btn btn-xs btn-danger deletedata" value="{{ route('product.type.delete',$type->product_type_id) }}"><i class="fa fa-times-circle"></i></a>
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
<div class="modal modal-info fade" id="modal-1">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Product::product.addproducttype') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Product\Controllers\TypeController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
			<input type="hidden" name="product_type_id" id="product_type_id" class="modaldata" value="{{ old('product_type_id') }}">
			<input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchants->merchant_id }}">
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.producttypeparent') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<select name="product_type_parent_id" id="product_type_parent_id" class="form-control modaldata" required>
                            <option value="">{{ __('Admin::base.please_select') }}</option>
                            {{-- @foreach($types as $type)
                                <option value="{{ $type->product_type_id }}" {{ (old('product_type_parent_id')==$type->product_type_id) ? 'selected' : '' }}>{{ $type->product_type_desc }}</option>
                            @endforeach --}}
                        </select> 
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.producttype') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control modaldata" type="text" value="{{ old('product_type_desc') }}" name="product_type_desc" id="product_type_desc" placeholder="{{ __('Product::product.producttype') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.image') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                <img src="{!! asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120" id="imagesrc">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                            <div>
                                <span class="btn btn-primary btn-sm btn-file">
                                    <span class="fileinput-new">{{ __('Admin::base.selectphoto') }}</span>
                                    <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                    <input type="file" name="productcategory" id="productcategory" class="modaldata" accept="image/*" multiple="" required/>
                                </span>
                                <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                            </div>
                        </div>
					</div>					
				</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				@if(Auth::user()->can('product.type.store'))
				<button type="button" class="btn btn-sm btn-success addbtn"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
				@endif
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

	$('#merchant_id').on('change', function(){

		getParent($(this).val(),'','');

	});

	function getParent(id,eid,did){
		//var id = $(this).val();
		$('#product_type_parent_id').empty();
            
        if(id!=''){

        	if(eid == ''){
        		eid = 'noid';
        	}

            $.ajax({
                url: '{{ URL::to("product/type/getParent") }}/'+id+'/'+eid,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    if(data!=''){
                        $('#product_type_parent_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
                        $.each(data, function(key, value) {
                        	if(did!='' && did==key){
                                $('#product_type_parent_id').append('<option value="'+ key +'" selected>'+ value +'</option>');
                            }else{
                                $('#product_type_parent_id').append('<option value="'+ key +'">'+ value +'</option>');
                            }
                        });
                    }else{
                        $('#product_type_parent_id').append('<option value="">{{ __("Admin::base.norecordfound") }}</option>');
                    }

                }
            });

        }else{
            $('#product_type_parent_id').append('<option value="">{{ __("Admin::base.norecordfound") }}</option>');
        }
	}

	$('.adddata').on('click', function(){
		$('.modaldata').val('');
		$('#product_type_parent_id').empty();
		$('#product_type_parent_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
		var merchantid = '{{ $merchants->merchant_id }}';
		getParent(merchantid,'','');
		$('#imagesrc').attr('src','{!! asset("/img/noimage.jpg") !!}');
		$('#modal-1').modal('show');
	});

	$('.infodata').on('click', function(){

		$('.modaldata').val('');
		$('#product_type_parent_id').empty();
		$('#imagesrc').attr('src','{!! asset("/img/noimage.jpg") !!}');

		var id = $(this).data('id');

		if(id!=''){
			$.ajax({
	            url: '{{ URL::to("product/type/getInfo") }}/'+id,
	            type: 'get',
	            dataType: 'json',
	            success:function(data) {
	            	console.log(data.image);
	            	$('#product_type_id').val(id);
	                $('#product_type_desc').val(data.type.product_type_desc); 
	                $('#merchant_id').val(data.type.merchant_id);
	                $('#product_type_parent_id').val(data.type.product_type_parent_id);
	                $('#imagesrc').attr('src','{!! asset("'+data.image+'") !!}');

	                getParent(data.type.merchant_id,data.type.product_type_id,data.type.product_type_parent_id);
	            }
	        });
	    }

		$('#modal-1').modal('show');
	});

	$('.addbtn').on('click', function() {

		if($('#product_type_id').val()!=''){
	    	if($('#product_type_desc').val()=='' || $('#merchant_id').val()==''){
	    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
	    		return false;
	    	}
	    }/*else{
	    	if($('#product_type_desc').val()=='' || $("#productcategory")[0].files.length==0 || $('#merchant_id').val()==''){
	    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
	    		return false;
	    	}
	    }*/

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