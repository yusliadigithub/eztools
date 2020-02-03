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
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="merchant_name">{{ __('Merchant::merchant.companyname') }}</option>
					  		<option value="merchant_ssmno">{{ __('Merchant::merchant.ssmno') }}</option>
					  		<option value="merchant_email">{{ __('Merchant::merchant.email') }}</option>
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
			</div>
		</div>
	</div>


@stop

@section('footer')

@stop