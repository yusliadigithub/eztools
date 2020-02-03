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
					@if(Auth::user()->can('product.store'))
					<a class="btn btn-sm btn-primary adddata"><i class="fa fa-plus-circle"></i> {{ __('Product::product.addproduct') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Product\Controllers\ProductController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="product_name">{{ __('Product::product.productname') }}</option>
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
							<th class="column-title">{{ __('Product::product.product') }}</th>
							<th class="column-title">{{ __('Product::product.category') }}</th>
							<th class="column-title">{{ __('Product::product.merchant') }}</th>
							<th class="column-title text-center" width="15%">{{ __('Admin::base.createddate') }}</th>
							<!--th class="column-title text-center" width="10%">Status</th-->
							<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td class="text-center"><img src="{!! (!empty($type->image)) ? asset($type->image->upload_path.$type->image->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120"></td>
							<td>{!! $type->product_name !!}</td>
							<td>{!! $type->type->product_type_desc !!}</td>
							<td>{!! $type->merchant->merchant_name !!}</td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($type->created_at)) !!}</td>
							<!--td class="text-center">
								{!! ($type->product_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
							</td-->
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Product::product.editproduct') }}" data-id="{!! $type->product_id !!}" class="btn btn-xs btn-info infodata"><i class="fa fa-edit"></i></a>
								{{-- @if($type->product_status == 1) --}}
								@if($type->product_status!='')
								<a data-toggle="tooltip" title="{{ __('Product::product.stocklist') }}" class="btn btn-xs btn-warning" href="{{ route('product.attribute', Crypt::encrypt($type->product_id)) }}"><i class="fa fa-chevron-circle-right"></i></a>
								@endif
								<!--a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" data-askmsg="{{ __('Admin::base.askdisable') }}" class="btn btn-xs btn-primary enabledata {{ $type->product_status==0 ? 'disabled' : '' }}" value="{{ route('product.disable', $type->product_id) }}"><i class="fa fa-minus-circle"></i></a-->
								@if($type->product_status == 0)
								<!--a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" data-askmsg="{{ __('Admin::base.askenable') }}" class="btn btn-xs btn-success enabledata" value="{{ route('product.enable', $type->product_id) }}"><i class="fa fa-check-circle"></i></a-->
								@endif
								@if(Auth::user()->can('product.delete'))
								<a class="btn btn-xs btn-danger deletedata" value="{{ route('product.delete',$type->product_id) }}"><i class="fa fa-times-circle"></i></a>
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
				<h4 class="modal-title">{!! trans('Product::product.addproduct') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Product\Controllers\ProductController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
			<input type="hidden" name="product_id" id="product_id" class="modaldata" value="{{ old('product_id') }}">
			<input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchants->merchant_id }}">
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.category') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<select name="product_type_id" id="product_type_id" class="form-control modaldata" required>
                            <option value="">{{ __('Admin::base.please_select') }}</option>
                            {{-- @foreach($types as $type)
                                <option value="{{ $type->product_id }}" {{ (old('product_type_id')==$type->product_id) ? 'selected' : '' }}>{{ $type->product_type_desc }}</option>
                            @endforeach --}}
                        </select> 
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.product') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control modaldata" type="text" value="{{ old('product_name') }}" name="product_name" id="product_name" placeholder="{{ __('Product::product.product') }}" />
					</div>					
				</div>
				<!--div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::base.description') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<textarea class="form-control modaldata" name="product_description" id="product_description" placeholder="{{ __('Admin::base.description') }}">{{ old('product_description') }}</textarea> 
					</div>					
				</div-->

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.supplytaxcode') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						{{ Form::taxsupply('taxsupply_id', [''=>__('Admin::base.please_select')] , old('taxsupply_id'), ['class'=>'form-control','id'=>'taxsupply_id','required'=>'required']) }} 
					</div>					
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.purchasetaxcode') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						{{ Form::taxpurchase('taxpurchase_id', [''=>__('Admin::base.please_select')] , old('taxpurchase_id'), ['class'=>'form-control','id'=>'taxpurchase_id','required'=>'required']) }} 
					</div>					
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.variantproduct') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<div class="checkbox checbox-switch switch-dark">
                            <label>
                                <input type="checkbox" class="form-control" name="product_isvariable" id="product_isvariable" value="1" {{ (old('product_isvariable')) ? 'checked' : '' }}/>
                                <span></span>
                            </label>
                        </div>
					</div>					
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.stockcontrol') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<div class="checkbox checbox-switch switch-dark">
                            <label>
                                <input type="checkbox" class="form-control" name="product_isstockcontrol" id="product_isstockcontrol" value="1" {{ (old('product_isstockcontrol')) ? 'checked' : '' }}/>
                                <span></span>
                                {{-- __('Product::product.stockcontrol') --}}
                            </label>
                        </div>
					</div>					
				</div>

				<div class="form-group downloadablediv">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.downloadable') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<div class="checkbox checbox-switch switch-dark">
                            <label>
                                <input type="checkbox" class="form-control" name="product_isdownloadable" id="product_isdownloadable" value="1" {{ (old('product_isdownloadable')) ? 'checked' : '' }}/>
                                <span></span>
                            </label>
                        </div>
					</div>					
				</div>

				<div class="form-group urldiv">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.downloadurl') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<textarea class="form-control modaldata" name="product_downloadurl" id="product_downloadurl" placeholder="{{ __('Product::product.downloadurl') }}">{{ old('product_downloadurl') }}</textarea>
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
                                    <input type="file" name="product" id="product" class="modaldata" accept="image/*" multiple="" required/>
                                </span>
                                <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                            </div>
                        </div>
					</div>					
				</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				@if(Auth::user()->can('product.store'))
				<button type="button" class="btn btn-sm btn-success addbtn"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
				@endif
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div> 


<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

$( document ).ready(function() {

	$('.downloadablediv').hide(); //temporarily hide

});

	@if( Session::get('modal') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
	@endif

	$('#merchant_id').on('change', function(){

		getParent($(this).val(),'');

	});

	$('#product_isdownloadable').on('change', function(){
	    if(this.checked) {
	        $('.urldiv').show();
	    }else{
	    	$('.urldiv').hide();
	    }
	});

	function getParent(id,did){
		//var id = $(this).val();
		$('#product_type_id').empty();
            
        if(id!=''){
        	eid = 'noid';
            $.ajax({
                url: '{{ URL::to("product/type/getParent") }}/'+id+'/'+eid,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                    if(data!=''){
                        $('#product_type_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
                        $.each(data, function(key, value) {
                        	if(did!='' && did==key){
                                $('#product_type_id').append('<option value="'+ key +'" selected>'+ value +'</option>');
                            }else{
                                $('#product_type_id').append('<option value="'+ key +'">'+ value +'</option>');
                            }
                        });
                    }else{
                        $('#product_type_id').append('<option value="">{{ __("Admin::base.norecordfound") }}</option>');
                    }

                }
            });

        }else{
            $('#product_type_id').append('<option value="">{{ __("Admin::base.norecordfound") }}</option>');
        }
	}

	$('.adddata').on('click', function(){
		$('.modaldata').val('');
		$('#product_isvariable').prop('checked',false);
		$('#product_isstockcontrol').prop('checked',false);
		$('#product_isdownloadable').prop('checked',false);
		$('.urldiv').hide();
		$('#product_type_id').empty();
		$('#product_type_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
		var merchantid = '{{ $merchants->merchant_id }}';
		getParent(merchantid,'');
		$('#imagesrc').attr('src','{!! asset("/img/noimage.jpg") !!}');
		$('#modal-1').modal('show');
	});

	$('.infodata').on('click', function(){

		$('.modaldata').val('');
		$('#product_isvariable').prop('checked',false);
		$('#product_isstockcontrol').prop('checked',false);
		$('#product_isdownloadable').prop('checked',false);
		$('.urldiv').hide();
		$('#product_type_id').empty();
		$('#imagesrc').attr('src','{!! asset("/img/noimage.jpg") !!}');

		var id = $(this).data('id');

		if(id!=''){
			$.ajax({
	            url: '{{ URL::to("product/getInfo") }}/'+id,
	            type: 'get',
	            dataType: 'json',
	            success:function(data) {
	            	console.log(data.image);
	            	$('#product_id').val(id);
	                $('#product_name').val(data.type.product_name);
	                $('#product_description').val(data.type.product_description); 
	                $('#merchant_id').val(data.type.merchant_id);
	                $('#taxsupply_id').val(data.type.taxsupply_id);
	                $('#taxpurchase_id').val(data.type.taxpurchase_id); 
	                //$('#product_type_id').val(data.type.product_type_id);
	                if(data.type.product_isvariable=='1'){
	                	$('#product_isvariable').prop('checked',true);
	                }else{
	                	$('#product_isvariable').prop('checked',false);
	                }
	                if(data.type.product_isstockcontrol=='1'){
	                	$('#product_isstockcontrol').prop('checked',true);
	                }else{
	                	$('#product_isstockcontrol').prop('checked',false);
	                }
	                if(data.type.product_isdownloadable=='1'){
	                	$('#product_isdownloadable').prop('checked',true);
	                	$('.urldiv').show();
	                }else{
	                	$('#product_isdownloadable').prop('checked',false);
	                	$('.urldiv').hide();
	                }
	                $('#product_downloadurl').val(data.type.product_downloadurl);
	                $('#imagesrc').attr('src','{!! asset("'+data.image+'") !!}');

	                getParent(data.type.merchant_id,data.type.product_type_id);
	            }
	        });
	    }

		$('#modal-1').modal('show');
	});

	$('.addbtn').on('click', function() {

		if($('#product_id').val()==''){
	    	if($('#product_name').val()=='' || $("#product")[0].files.length==0 || $('#merchant_id').val()=='' || $('#product_type_id').val()==''){
	    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
	    		return false;
	    	}
	    }else{
	    	if($('#product_name').val()=='' || $('#merchant_id').val()=='' || $('#product_type_id').val()==''){
	    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
	    		return false;
	    	}
	    }

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