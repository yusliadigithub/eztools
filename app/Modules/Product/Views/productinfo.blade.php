@extends('layouts.adminLTE.master')

@section('header')
<script src="https://cdn.ckeditor.com/4.9.2/standard-all/ckeditor.js"></script>
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

@if($product->product_submitstatus=='1')   
<div class="box">
    <div class="box-header with-border">
        <div class="row">
            <div class="pull-left col-sm-12 col-md-6 col-lg-6">
                @if(Auth::user()->can('product.storestock') && $product->product_isvariable == '1')
                <a data-toggle="tooltip" data-prodid="{!! $product->product_id !!}" class="btn btn-sm btn-primary addnewstock"><i class="fa fa-plus-circle"></i> {{ __('Product::product.addstock') }}</a>
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
                        <!--th class="column-title text-center" width="13%">{{ __('Product::product.attribute') }}</th>
                        <th class="column-title text-center" width="13%">{{ __('Product::product.value') }}</th-->
                        <th class="column-title text-center">{{ __('Product::product.variant') }}</th>
                        @if($product->product_isstockcontrol == 1)    
                        <th class="column-title text-center" width="9%">{{ __('Product::product.quantityleft') }}</th>
                        @endif
                        <th class="column-title text-center" width="9%">{{ __('Product::product.weight') }} (KG)</th>
                        <th class="column-title text-center" width="12%">{{ __('Product::product.originalprice') }} (MYR)</th>
                        <th class="column-title text-center" width="12%">{{ __('Product::product.marketprice') }} (MYR)</th>
                        <th class="column-title text-center" width="10%">{{ __('Product::product.saleprice') }} (MYR)</th>
                        <!--th class="column-title text-center" width="10%">{{ __('Admin::base.updateddate') }}</th-->
                        <th class="column-title text-center" width="8%">Status</th>
                        <th class="column-title text-center" width="14%">{{ __('Admin::base.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($stocks) > 0)
                    @foreach($stocks as $stock)
                    <tr>
                        <!--td>{!! ($stock->attribute) ? $stock->attribute->attribute_desc : '' !!}</td>
                        <td>{!! ($stock->value) ? $stock->value->attribute_value_desc : '' !!}</td-->
                        <td>{!! $stock->product_stock_description !!}</td>
                        @if($product->product_isstockcontrol == 1)
                        <td class="text-right">{!! number_format($stock->product_stock_quantity) !!}</td>
                        @endif
                        <td class="text-right">{!! number_format($stock->product_stock_weight,2) !!}</td>
                        <td class="text-right">{!! number_format($stock->product_stock_original_price,2) !!}</td>
                        <td class="text-right">{!! number_format($stock->product_stock_market_price,2) !!}</td>
                        <td class="text-right">{!! number_format($stock->product_stock_sale_price,2) !!}</td>
                        <!--td class="text-center">{!! date( 'd F Y', strtotime($stock->updated_at)) !!}</td-->
                        <td class="text-center">
                            {!! ($stock->product_stock_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}</td>
                        <td class="text-center">
                            <a data-toggle="tooltip" title="{{ __('Product::product.stock') }}" data-id="{!! $stock->product_stock_id !!}" data-prodid="{!! $product->product_id !!}" data-variant="{{ $stock->product_stock_description }}" class="btn btn-xs btn-info infostockdata"><i class="fas fa-info-circle"></i></a>
                            @if($product->product_isstockcontrol == 1)
                            <a data-toggle="tooltip" title="{{ __('Product::product.quantityadjustment') }}" data-id="{!! $stock->product_stock_id !!}" data-prodid="{!! $product->product_id !!}" data-variant="{{ $stock->product_stock_description }}" data-quantity="{{ $stock->product_stock_quantity }}" class="btn btn-xs btn-warning adjustquantitymodal"><i class="fas fa-info-circle"></i></a>
                            @endif
                            <!--a data-toggle="tooltip" title="{{ __('Product::product.setprice') }}" data-id="{!! $stock->product_stock_id !!}" data-original="{!! $stock->product_stock_original_price !!}" data-market="{!! $stock->product_stock_market_price !!}" data-sale="{!! $stock->product_stock_sale_price !!}" data-variant="{{ $stock->product_stock_description }}" class="btn btn-xs btn-success infostock"><i class="fas fa-dollar-sign"></i></a-->
                            @if($stock->product_stock_status == 1)
                                <a data-toggle="tooltip" title="{{ __('Product::product.stockmovement') }}" href="{{ route('product.stockmovement',Crypt::encrypt($stock->product_stock_id)) }}" class="btn btn-xs btn-info"><i class="fas fa-chart-line"></i></a>
                                <a data-toggle="tooltip" title="{{ __('Product::product.stockledger') }}" href="{{ route('product.stockqtyledger',Crypt::encrypt($stock->product_stock_id)) }}" class="btn btn-xs btn-success"><i class="fas fa-list-alt"></i></a>
                                @if(Auth::user()->can('product.disablestock'))
                                <a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" data-askmsg="{{ __('Admin::base.askdisable') }}" class="btn btn-xs btn-primary enabledata {{ $stock->product_stock_status==0 ? 'disabled' : '' }}" value="{{ route('product.disablestock', $stock->product_stock_id) }}"><i class="fa fa-minus-circle"></i></a>
                                @endif
                            @endif
                            @if($stock->product_stock_status == 0)
                                @if(Auth::user()->can('product.enablestock'))
                                <a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" data-askmsg="{{ __('Admin::base.askenable') }}" class="btn btn-xs btn-success enabledata" value="{{ route('product.enablestock', $stock->product_stock_id) }}"><i class="fa fa-check-circle"></i></a>
                                @endif
                            @endif
                            @if(Auth::user()->can('product.deletestock'))
                            <a class="btn btn-xs btn-danger deletedata" value="{{ route('product.deletestock',$stock->product_stock_id) }}"><i class="fa fa-times-circle"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr><td colspan="9">No result(s)</td></tr>
                    @endif
                </tbody>
            </table>
            {{ $stocks->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
        </div>
    </div>
</div>
@endif

{{-- @if($product->product_submitstatus=='0') --}}
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Product::product.variantsetting') }}</h3>
    </div>
    {!! Form::open(['action'=>'\App\Modules\Product\Controllers\ProductController@storeattribute', 'method'=>'post', 'role'=>'form', 'id'=>'newdataform' ]) !!}
    <input type="hidden" name="product_id" id="product_id" value="{!! $product->product_id !!}">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="complaint_type_id">&nbsp;</label>
                    <img src="{!! (!empty($product->image)) ? asset($product->image->upload_path.$product->image->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="140" width="140">
                </div>
                <div class="form-group">
                    <label for="complaint_type_id">{{ __('Product::product.productname') }}</label>
                    <input class="form-control" type="text" value="{{ $product->product_name }}" disabled />
                </div>
                <div class="form-group">
                    <label for="complaint_type_id">{{ __('Product::product.category') }}</label>
                    <input class="form-control" type="text" value="{{ $product->type->product_type_desc }}" disabled />
                </div>
                <!--div class="form-group">
                    <label for="complaint_message">{{ __('Admin::base.description') }}</label>
                    <textarea class="form-control" disabled>{{ $product->product_description }}</textarea>
                </div-->
                <div class="form-group">
                    <label for="complaint_message">{{ __('Product::product.supplytaxcode') }}</label>
                    <input class="form-control" type="text" value="{{ $product->taxsupply->tax_code }}" disabled />
                </div>
                <div class="form-group">
                    <label for="complaint_message">{{ __('Product::product.purchasetaxcode') }}</label>
                    <input class="form-control" type="text" value="{{ $product->taxpurchase->tax_code }}" disabled />
                </div>
                <div class="form-group">
                    <label for="complaint_message">{{ __('Product::product.variantproduct') }}</label>
                    <div class="checkbox checbox-switch switch-primary">
                        <label>
                            <input type="checkbox" class="form-control" name="product_isvariable" id="product_isvariable" value="1" {{ ($product->product_isvariable=='1') ? 'checked' : '' }} disabled/>
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="complaint_message">{{ __('Product::product.stockcontrol') }}</label>
                    <div class="checkbox checbox-switch switch-primary">
                        <label>
                            <input type="checkbox" class="form-control" name="product_isdownloadable" id="product_isdownloadable" value="1" {{ ($product->product_isstockcontrol=='1') ? 'checked' : '' }} disabled/>
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group downloadablediv">
                    <label for="complaint_message">{{ __('Product::product.downloadable') }}</label>
                    <div class="checkbox checbox-switch switch-primary">
                        <label>
                            <input type="checkbox" class="form-control" name="product_isdownloadable" id="product_isdownloadable" value="1" {{ ($product->product_isdownloadable=='1') ? 'checked' : '' }} disabled/>
                            <span></span>
                        </label>
                    </div>
                </div>
                @if($product->product_isdownloadable=='1')
                <div class="form-group">
                    <label for="complaint_message">{{ __('Product::product.downloadurl') }}</label>
                    <textarea class="form-control" disabled>{{ $product->product_downloadurl }}</textarea>
                </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="complaint_message">{{ __('Merchant::merchant.pagecontent') }}</label>
                    <textarea id="product_content" name="product_content" rows="20" >{{ ($product->product_content != '') ? $product->product_content : old('product_content') }}</textarea>
                </div>
                <div class="form-group">
                    <!--label for="complaint_message">{{-- __('Product::product.attachment') --}}</label-->
                    <input id="totalrow" type="hidden" value="0">
                    <input id="totaltd" type="hidden" value="0">
                    <table class="table tablevariable">
                        <tbody id="attachtbody">
                            <tr>
                                <td width="40%">{{ __('Product::product.variant') }}</td>
                                <td width="40%">{{ __('Product::product.value') }}</td>
                                <td width="20%">
                                    <a class="pull-right btn btn-xs btn-primary addrow" {{ ($product->product_submitstatus=='1') ? 'disabled' : '' }}><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;{{-- __('Product::product.addattribute') --}}{{ __('Admin::base.add') }}</a>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="form-group pull-right">
            <a href="{{ URL::to('product/'.Crypt::encrypt($product->merchant_id).'/index') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
            @if(Auth::user()->can('product.storeattribute'))
            <button type="button" class="btn btn-sm btn-primary submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.update') }}</button>
            @endif
            @if($product->product_submitstatus!='1')
                @if(Auth::user()->can('product.confirm'))
                <a data-toggle="tooltip" title="{{ __('Admin::base.submit') }}" data-askmsg="{{ __('Admin::base.makesurechanges') }}" class="btn btn-sm btn-success confirmdata" value="{{ route('product.confirm') }}"><i class="fa fa-check-circle"></i>{{ __('Admin::base.submit') }}</a>
                @endif
            @endif
        </div>
    </div>
    {!! Form::close() !!}
</div>
{{-- @endif --}}

@stop

@section('footer')

<div class="modal modal-info fade" id="modal-2">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Product::product.addstock') !!}</h4>
            </div>

            @if (session('flash_error'))
                <div class="alert alert-block alert-error fade in">
                    <strong>{{ __('Admin::base.error') }}!</strong>
                    {!! session('flash_error') !!}
                    <!--span class="close" data-dismiss="alert">×</span-->
                </div>
            @endif

            {!! Form::open(['action'=>'\App\Modules\Product\Controllers\ProductController@storestock', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form2', 'files'=>true]) !!}
            <input type="hidden" name="m2_product_stock_id" id="m2_product_stock_id" class="modaldata" value="{{ old('m2_product_stock_id') }}">
            <input type="hidden" name="m2_product_id" id="m2_product_id" class="modaldata" value="{{ old('m2_product_id') }}">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.productname') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control" type="text" value="{{ $product->product_name }}" disabled />
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.category') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control" type="text" value="{{ $product->type->product_type_desc }}" disabled /> 
                    </div>                  
                </div>
                <div class="form-group variantdiv"></div>
                <div class="form-group notstockcontrol">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.quantity') }}</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input class="form-control modaldata" value="{{ old('product_stock_quantity') }}" id="product_stock_quantity" type="number" name="product_stock_quantity" /> 
                    </div>                 
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.weight') }}</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="input-group">          
                            <input class="form-control modaldata" value="{{ old('product_stock_weight') }}" id="product_stock_weight" type="number" name="product_stock_weight" step="0.01" />
                            <div class="input-group-addon">KG</div>
                        </div> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.originalprice') }}</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="input-group">           
                            <div class="input-group-addon">MYR</div>
                            <input class="form-control modaldata" value="{{ old('product_stock_original_price') }}" id="product_stock_original_price" type="number" name="product_stock_original_price" step="0.1" />
                        </div> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.marketprice') }}</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="input-group">           
                            <div class="input-group-addon">MYR</div>
                            <input class="form-control modaldata" value="{{ old('product_stock_market_price') }}" id="product_stock_market_price" type="number" name="product_stock_market_price" step="0.1" />
                        </div> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.saleprice') }}</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="input-group">           
                            <div class="input-group-addon">MYR</div>
                            <input class="form-control modaldata" value="{{ old('product_stock_sale_price') }}" id="product_stock_sale_price" type="number" name="product_stock_sale_price" step="0.1" />
                        </div> 
                    </div>                  
                </div>
            </div>
            <div class="modal-body modal2select">
            </div>

            <div class="modal-body photodiv">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.image') }} <br> <a class="pull-right btn btn-xs btn-primary addphoto"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;{{ __('Admin::base.add') }}</a></label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                    <ul id="photolist" style="list-style-type: none" class="list-inline">
                        <li>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                    <img src="{!! asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120" id="imagesrc">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                                <div>
                                    <span class="btn btn-primary btn-sm btn-file">
                                        <span class="fileinput-new">{{ __('Product::product.selectthumbnail') }}</span>
                                        <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                        <input type="file" name="mainphoto" id="mainphoto" class="modaldata" accept="image/*" multiple="" required/>
                                    </span>
                                    <a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                @if(Auth::user()->can('product.storestock'))
                <button type="button" class="btn btn-sm btn-success addstock"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
                @endif
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal modal-info fade" id="modal-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Product::product.setprice') !!}</h4>
            </div>

            @if (session('flash_error'))
                <div class="alert alert-block alert-error fade in">
                    <strong>{{ __('Admin::base.error') }}!</strong>
                    {!! session('flash_error') !!}
                    <!--span class="close" data-dismiss="alert">×</span-->
                </div>
            @endif

            {!! Form::open(['action'=>'\App\Modules\Product\Controllers\ProductController@setprice', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1']) !!}
            <input type="hidden" name="product_stock_id" id="product_stock_id" class="modaldata" value="{{ old('product_stock_id') }}">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.productname') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control" type="text" value="{{ $product->product_name }}" disabled />
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.category') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control" type="text" value="{{ $product->type->product_type_desc }}" disabled /> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.variant') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control modaldata" type="text" id="productstockvariant" value="" disabled /> 
                    </div>                  
                </div>
                <!--div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.originalprice') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="input-group">           
                            <div class="input-group-addon">MYR</div>
                            <input class="form-control modaldata" value="{{ old('product_stock_original_price') }}" id="product_stock_original_price" type="number" name="product_stock_original_price" />
                        </div> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.marketprice') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="input-group">           
                            <div class="input-group-addon">MYR</div>
                            <input class="form-control modaldata" value="{{ old('product_stock_market_price') }}" id="product_stock_market_price" type="number" name="product_stock_market_price" />
                        </div> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.saleprice') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="input-group">           
                            <div class="input-group-addon">MYR</div>
                            <input class="form-control modaldata" value="{{ old('product_stock_sale_price') }}" id="product_stock_sale_price" type="number" name="product_stock_sale_price" />
                        </div> 
                    </div>                  
                </div-->
            </div>
            
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                <button type="button" class="btn btn-sm btn-success setprice"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal modal-default fade" id="modal-5">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{!! trans('Product::product.quantityadjustment') !!}</h4>
            </div>

            @if (session('flash_error'))
                <div class="alert alert-block alert-error fade in">
                    <strong>{{ __('Admin::base.error') }}!</strong>
                    {!! session('flash_error') !!}
                    <!--span class="close" data-dismiss="alert">×</span-->
                </div>
            @endif

            {!! Form::open(['action'=>'\App\Modules\Product\Controllers\ProductController@adjustquantity', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form5']) !!}
            <input type="hidden" name="modal5_product_stock_id" id="modal5_product_stock_id" class="modaldata" value="{{ old('modal5_product_stock_id') }}">
            <div class="modal-body">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> Info!</h4>{{ __('Product::product.theadjustmentvaluemustbenegativefordeduction') }}
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.productname') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control" type="text" value="{{ $product->product_name }}" disabled />
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.category') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control" type="text" value="{{ $product->type->product_type_desc }}" disabled /> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.variant') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control modaldata" type="text" id="modal5_productstockvariant" value="" disabled /> 
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.currentquantity') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control modaldata" value="{{ old('modal5_currentquantity') }}" id="modal5_currentquantity" name="modal5_currentquantity" disabled/>
                    </div>                  
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.adjustmentvalue') }}</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <input class="form-control modaldata" value="{{ old('modal5_adjustmentvalue') }}" id="modal5_adjustmentvalue" type="number" name="modal5_adjustmentvalue" />
                    </div>                  
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
                <button type="button" class="btn btn-sm btn-success adjustquantity"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script type="text/javascript">

CKEDITOR.config.devtools_styles =
    '#cke_tooltip { line-height: 20px; font-size: 12px; padding: 5px; border: 2px solid #333; background: #ffffff }' +
    '#cke_tooltip h2 { font-size: 14px; border-bottom: 1px solid; margin: 0; padding: 1px; }' +
    '#cke_tooltip ul { padding: 0pt; list-style-type: none; }';

CKEDITOR.replace( 'product_content', {
    height: 200,
    extraPlugins: 'devtools'
});

$(document).ready(function() {

    $('.downloadablediv').hide(); //temporarily hide

    @if($product->product_isstockcontrol == 0)
        $('.notstockcontrol').hide();    
    @endif

    var productid = '{!! $product->product_id !!}';

    $.ajax({
        url: '{{ URL::to("product/getProductAttribute") }}/'+productid,
        type: 'get',
        dataType: 'json',
        success:function(data) {

            if(data!=''){
                $('#attachtbody tr:last').after(data.html);
                $('#totalrow').val(data.rownum);
                //$('.multiselect-ui').multiselect({ includeSelectAllOption: true });
            }

        }
    });

    @if($product->product_isvariable == '1')
        $('.tablevariable').show();
    @else
        $('.tablevariable').hide();
    @endif

});

    $('.infostockdata').on('click', function(){

        var stockid = $(this).data('id');
        $('.modaldata').val('');
        $('#m2_product_id').val($(this).data('prodid'));
        $('#m2_product_stock_id').val(stockid);
        $('.modal2select').empty();
        $('.variantdiv').empty();
        $('.liphoto').remove();
        $('#imagesrc').attr('src','{!! asset("/img/noimage.jpg") !!}');
        $('.notstockcontrol').hide();

        var variant = $(this).data('variant');

        if(stockid!=''){
            $.ajax({
                url: '{{ URL::to("product/getStockInfo") }}/'+stockid,
                type: 'get',
                dataType: 'json',
                success:function(data) {
                    console.log(data.image);
                    $('#product_stock_quantity').val(data.stock.product_stock_quantity);
                    $('#product_stock_weight').val(data.stock.product_stock_weight);
                    $('#product_stock_original_price').val(data.stock.product_stock_original_price);
                    $('#product_stock_market_price').val(data.stock.product_stock_market_price);
                    $('#product_stock_sale_price').val(data.stock.product_stock_sale_price);
                    $('#imagesrc').attr('src','{!! asset("'+data.image+'") !!}');
                    $('.variantdiv').append('<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __("Product::product.variant") }}</label><div class="col-md-7 col-sm-7 col-xs-12"><input class="form-control" type="text" value="'+variant+'" disabled /></div>');
                    $('#photolist').append(data.html);

                }
            });
        }

        $('#modal-2').modal('show');
    });

    $('.addnewstock').on('click', function(){

        $('.modaldata').val('');
        $('#m2_product_id').val($(this).data('prodid'));
        $('#m2_product_stock_id').val($(this).data('stockid'));
        $('.modal2select').empty();
        $('.variantdiv').empty();
        $('.liphoto').remove();
        $('#imagesrc').attr('src','{!! asset("/img/noimage.jpg") !!}');

        @if($product->product_isstockcontrol == 1)
            $('.notstockcontrol').show();    
        @endif

        getdropdownattr($(this).data('prodid'));

        $('#modal-2').modal('show');

    });

    @if( Session::get('modal2') )
        getdropdownattr('{{ old("m2_product_id") }}');
        $('#modal-2').modal( {backdrop: 'static', keyboard: false} ); 
    @endif

    @if( Session::get('modal') )
        $('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
    @endif

    @if( Session::get('modal5') )
        $('#modal-5').modal( {backdrop: 'static', keyboard: false} ); 
    @endif

    $('.infostock').on('click', function(){

        $('.modaldata').val('');
        $('#product_stock_id').val($(this).data('id'));
        $('#productstockvariant').val($(this).data('variant'));
        $('#product_stock_original_price').val($(this).data('original'));
        $('#product_stock_market_price').val($(this).data('market'));
        $('#product_stock_sale_price').val($(this).data('sale'));
        $('#modal-1').modal('show');

    });

    $('.adjustquantitymodal').on('click', function(){

        $('.modaldata').val(''); 
        $('#modal5_product_stock_id').val($(this).data('id'));
        $('#modal5_currentquantity').val($(this).data('quantity'));
        $('#modal5_productstockvariant').val($(this).data('variant'));
        $('#modal-5').modal('show');

    });

    $('.addphoto').on('click', function(){

        var newphoto = '<li class="liphoto"><div class="fileinput fileinput-new" data-provides="fileinput"><div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput"><img src="{!! asset("/img/noimage.jpg") !!}" alt="..." height="120" width="120" id="imagesrc"></div><div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div><div><span class="btn btn-primary btn-sm btn-file"><span class="fileinput-new">{{ __("Admin::base.selectphoto") }}</span><span class="fileinput-exists">{{ __('Admin::base.change') }}</span><input type="file" name="childphoto[]" id="childphoto" class="modaldata" accept="image/*" multiple="" required/></span><a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a></div></div></li>';

        $('#photolist').append(newphoto);

    });

    function getdropdownattr($id){

        $.ajax({
            url: '{{ URL::to("product/getModalAttribute") }}/'+$id,
            type: 'get',
            dataType: 'json',
            success:function(data) {

                if(data!=''){
                    $('.modal2select').append(data);
                }

            }
        });

    }

    $(document).on('click', '.addrow', function() {

        var r = parseInt($('#totalrow').val())+1;

        $.ajax({
            url: '{{ URL::to("product/getAttribute") }}/'+r+'/'+$('#product_id').val(),
            type: 'get',
            dataType: 'json',
            success:function(data) {

                if(data!=''){
                    $('#attachtbody tr:last').after(data);
                    $('#totalrow').val(r);
                }

            }
        });

    });

    $(document).on('change', '.getoption', function() {

        var attrid = $(this).val();
        var currnum = $(this).data('id');
        $('#td'+currnum).empty();

        $.ajax({
            url: '{{ URL::to("product/getValue") }}/'+attrid+'/'+currnum,
            type: 'get',
            dataType: 'json',
            success:function(data) {

                if(data!=''){
                    $('#td'+currnum).append(data);
                    //$('.multiselect-ui').multiselect({ includeSelectAllOption: true });
                }

            }
        });

    });

    $(document).on('click', '.removerow', function() {
        $(this).parent().parent().remove();
    });

    $('.submitform').on('click', function() {
    
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

                $("#newdataform").submit();

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

    $('.addstock').on('click', function() {

        var secondmsg = '';

        if($('#m2_product_stock_id').val()==''){
            if($("#mainphoto")[0].files.length==0){
                swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
                return false;
            }
        }

        if($("#product_stock_original_price").val()=='' || $("#product_stock_market_price").val()=='' || $("#product_stock_sale_price").val()==''){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
            return false;
        }

        @if($product->product_isstockcontrol == 1)
            if($("#product_stock_quantity").val()=='' || $("#product_stock_weight").val()==''){
                swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
                return false;
            }
            secondmsg = '{{ __("Product::product.keyedinquantityimmediatelyrecordinstockmovement") }}';
        @endif

        swal({
        title: '{{ __("Admin::base.confirmsubmission") }}',
        text: secondmsg,
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: '{{ __("Admin::base.cancel") }}',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ __("Admin::base.yes") }}',
      
        preConfirm: function() {
            return new Promise(function(resolve) {

                $('#form2').submit();

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

    $('.adjustquantity').on('click', function() {
    
        if($('#modal5_product_stock_id').val()=='' || $('#modal5_adjustmentvalue').val()==''){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
            return false;
        }

      swal({
        title: '{{ __("Admin::base.confirmsubmission") }}',
        text: "{{ __('Product::product.thisadjustmentwillaffectstockmovement') }}",
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: '{{ __("Admin::base.cancel") }}',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ __("Admin::base.yes") }}',
      
        preConfirm: function() {
            return new Promise(function(resolve) {

                $("#form5").submit();

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

    $('.setprice').on('click', function() {
    
        if($('#product_stock_original_price').val()=='' || $('#product_stock_market_price').val()=='' || $('#product_stock_sale_price').val()==''){
            swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
            return false;
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

    $('.confirmdata').on('click', function() {
    
        var url = $(this).attr('value');
        var askmsg = $(this).data('askmsg');

        swal({
            title: "{{ __('Admin::base.confirmsubmission') }}",
            text: askmsg,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __("Admin::base.cancel") }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("Admin::base.yes") }}',
          
            preConfirm: function() {
                return new Promise(function(resolve) {

                    $('#newdataform').attr('action','{{ URL::to("/product/confirm") }}');
                    $("#newdataform").submit();

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

    $('#product_stock_market_price').on('keyup', function(){

        $('#product_stock_sale_price').val($(this).val());

    });

</script>
@stop