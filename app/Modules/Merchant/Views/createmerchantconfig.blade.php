@extends('layouts.adminLTE.master')

@section('header')
<script src="https://cdn.ckeditor.com/4.9.2/standard-all/ckeditor.js"></script>
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Admin::base.configuration') }}</h3>
    </div>
    {!! Form::open(['action'=>['\App\Modules\Merchant\Controllers\MerchantController@editconfig'], 'method'=>'post', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
    <input name="merchant_id" class="thisismerchantid" type="hidden" value="{{ $merchant->merchant->merchant_id }}">
    <input name="merchant_config_id" type="hidden" value="{{ $merchant->merchant_config_id }}">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="merchant_name" class="col-sm-4 control-label">{{ __('Merchant::merchant.companyname') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" value="{{ $merchant->merchant->merchant_name }} ({{ $merchant->merchant->merchant_ssmno }})" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_appname" class="col-sm-4 control-label">{{ __('Merchant::merchant.appname') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_appname" id="merchant_config_appname" maxlength="100" type="text" value="{{ ($merchant->merchant_config_appname != '') ? $merchant->merchant_config_appname : old('merchant_config_appname') }}" placeholder="{{ __('Merchant::merchant.appname') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="merchant_config_android" class="col-sm-4 control-label">Google Store</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_android" id="merchant_config_android" maxlength="100" type="text" value="{{ ($merchant->merchant_config_android != '') ? $merchant->merchant_config_android : old('merchant_config_android') }}" placeholder="Google Store" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="merchant_config_ios" class="col-sm-4 control-label">Apple Store</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_ios" id="merchant_config_ios" maxlength="100" type="text" value="{{ ($merchant->merchant_config_ios != '') ? $merchant->merchant_config_ios : old('merchant_config_ios') }}" placeholder="Apple Store" required>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="merchant_config_address1" class="col-sm-4 control-label">{{ __('Merchant::merchant.address') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_address1" id="merchant_config_address1" maxlength="100" type="text" value="{{ ($merchant->merchant_config_address1 != '') ? $merchant->merchant_config_address1 : old('merchant_config_address1') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_address2" class="col-sm-4 control-label">&nbsp;</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_address2" id="merchant_config_address2" maxlength="100" type="text" value="{{ ($merchant->merchant_config_address2 != '') ? $merchant->merchant_config_address2 : old('merchant_config_address2') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_address3" class="col-sm-4 control-label">&nbsp;</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_address3" id="merchant_config_address3" maxlength="100" type="text" value="{{ ($merchant->merchant_config_address3 != '') ? $merchant->merchant_config_address3 : old('merchant_config_address3') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_postcode" class="col-sm-4 control-label">{{ __('Merchant::merchant.postcode') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_config_postcode" id="merchant_config_postcode" maxlength="5" type="text" value="{{ ($merchant->merchant_config_postcode != '') ? $merchant->merchant_config_postcode : old('merchant_config_postcode') }}" placeholder="eg: 43000" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="district_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.district') }}</label>
                        <div class="col-sm-6">
                            <select name="district_id" id="district_id" class="form-control" required>
                                    <option value="" {{ (old('district_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="state_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.state') }}</label>
                        <div class="col-sm-6">
                            <select name="state_id" id="state_id" class="form-control" required>
                                    <option value="" {{ (old('state_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_email" class="col-sm-4 control-label">{{ __('Merchant::merchant.email') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_email" id="merchant_config_email" maxlength="100" placeholder="myemail@gmail.com" type="email" value="{{ ($merchant->merchant_config_email != '') ? $merchant->merchant_config_email : old('merchant_config_email') }}" required>
                        </div>
                    </div>                    

                    <div class="form-group">
                        <label for="merchant_config_latitude" class="col-sm-4 control-label">{{ __('Merchant::merchant.coordinate') }}</label>
                        <div class="col-sm-3">
                            <input class="form-control" name="merchant_config_latitude" id="merchant_config_latitude" maxlength="20" placeholder="Latitude" type="text" value="{{ ($merchant->merchant_config_latitude != '') ? $merchant->merchant_config_latitude : old('merchant_config_latitude') }}">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="merchant_config_longitude" id="merchant_config_longitude" maxlength="20" placeholder="Longitude" type="text" value="{{ ($merchant->merchant_config_longitude != '') ? $merchant->merchant_config_longitude : old('merchant_config_longitude') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_website" class="col-sm-4 control-label">{{ __('Merchant::merchant.website') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_config_website" id="merchant_config_website" maxlength="100" type="text" placeholder="http://www.domain.com" value="{{ ($merchant->merchant_config_website != '') ? $merchant->merchant_config_website : old('merchant_config_website') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_officeno" class="col-sm-4 control-label">{{ __('Merchant::merchant.officeno') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_config_officeno" id="merchant_config_officeno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($merchant->merchant_config_officeno != '') ? $merchant->merchant_config_officeno : old('merchant_config_officeno') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_faxno" class="col-sm-4 control-label">{{ __('Merchant::merchant.faxno') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_config_faxno" id="merchant_config_faxno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($merchant->merchant_config_faxno != '') ? $merchant->merchant_config_faxno : old('merchant_config_faxno') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_mobileno" class="col-sm-4 control-label">{{ __('Merchant::merchant.mobileno') }}</label>
                        <div class="col-sm-6">
                            <table class="table">
                                <tbody id="mobilediv">
                                    <tr>
                                        <td><input name="merchant_config_mobileno" id="merchant_config_mobileno" class="form-control" maxlength="16" type="text" placeholder=" 0123456789" value="{{ ($merchant->merchant_config_mobileno != '') ? $merchant->merchant_config_mobileno : old('merchant_config_mobileno') }}" required></td>
                                        <td width="30%">
                                            <a class="btn btn-xs btn-primary pull-right addmobile"><i class="fa fa-plus-circle"></i></a>
                                        </td>
                                    </tr>
                                    @if(count($merchant->mobileno)>0)
                                        @foreach($merchant->mobileno as $mobileno)
                                            <tr><td>
                                                <input name="merchant_mobile_no[]" class="form-control" maxlength="16" type="text" value="{{ $mobileno->merchant_mobile_no }}" required></td><td width="30%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a>
                                            </td></tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="thisemail" class="col-sm-4 control-label">{{ __('Merchant::merchant.companyemail') }}</label>
                        <div class="col-sm-6">
                            <table class="table">
                                <tbody id="emaildiv">
                                    <tr>
                                        <td><input name="thisemail" id="thisemail"  class="form-control" maxlength="100" type="text" value="{{ ($merchant->merchant_config_email != '') ? $merchant->merchant_config_email : old('thisemail') }}" disabled></td>
                                        <td width="15%">
                                            <a class="btn btn-xs btn-primary pull-right addemail"><i class="fa fa-plus-circle"></i></a>
                                        </td>
                                    </tr>
                                    @if(count($merchant->emailaddress)>0)
                                        @foreach($merchant->emailaddress as $emailadd)
                                            <tr><td>
                                                <input name="merchant_email_address[]" class="form-control" maxlength="100" type="text" value="{{ $emailadd->merchant_email_address }}" required></td><td width="15%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a>
                                            </td></tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_config_email" class="col-sm-4 control-label">{{ __('Merchant::merchant.ecommerce_language') }}</label>
                        <div class="col-sm-6">
                            {{ Form::language_select('merchant_config_language', [], ($merchant->merchant_config_language != '') ? $merchant->merchant_config_language : old('merchant_config_language'), ['class'=>'form-control', 'required' ]) }}
                        </div>
                    </div>

                    <!-- open social -->
                    @hasrole(['admin','agent','merchant'])
                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Merchant::merchant.socialnetwork') }}</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label for="merchant_config_whatsapp" class="col-sm-4 control-label">Whatsapp  <!--i class="fa fa-whatsapp"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_config_whatsapp" id="merchant_config_whatsapp" maxlength="100" type="text" value="{{ ($merchant->merchant_config_whatsapp != '') ? $merchant->merchant_config_whatsapp : old('merchant_config_whatsapp') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_telegram" class="col-sm-4 control-label">Telegram  <!--i class="fa fa-telegram"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_config_telegram" id="merchant_config_telegram" maxlength="100" type="text" value="{{ ($merchant->merchant_config_telegram != '') ? $merchant->merchant_config_telegram : old('merchant_config_telegram') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_facebook" class="col-sm-4 control-label">Facebook  <!--i class="fa fa-facebook"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_config_facebook" id="merchant_config_facebook" maxlength="100" type="text" value="{{ ($merchant->merchant_config_facebook != '') ? $merchant->merchant_config_facebook : old('merchant_config_facebook') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_wechat" class="col-sm-4 control-label">Wechat  <!--i class="fa fa-weixin"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_config_wechat" id="merchant_config_wechat" maxlength="100" type="text" value="{{ ($merchant->merchant_config_wechat != '') ? $merchant->merchant_config_wechat : old('merchant_config_wechat') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_line" class="col-sm-4 control-label">Line  <!--i class="fa fa-line"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_config_line" id="merchant_config_line" maxlength="100" type="text" value="{{ ($merchant->merchant_config_line != '') ? $merchant->merchant_config_line : old('merchant_config_line') }}">
                                </div>
                            </div>

                        </div>
                    </div>
                    @endhasrole
                    <!-- close social-->

                    @hasrole(['admin','agent','merchant'])
                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Merchant::merchant.websiteimage') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="merchant_config_whatsapp" class="col-sm-5 control-label">Logo</label>
                                        <div class="col-sm-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                                    <img src="{!! (!empty($merchant->logo)) ? asset($merchant->logo->upload_path.$merchant->logo->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                                                <div>
                                                    <span class="btn btn-primary btn-sm btn-file">
                                                        <span class="fileinput-new">{{ __('Admin::base.selectphoto') }}</span>
                                                        <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                                        <input type="file" name="img_logo" id="img_logo" accept="image/*" multiple="" required/>
                                                    </span>
                                                    <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="merchant_config_whatsapp" class="col-sm-5 control-label">Banner</label>
                                        <div class="col-sm-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                                    <img src="{!! (!empty($merchant->banner)) ? asset($merchant->banner->upload_path.$merchant->banner->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                                                <div>
                                                    <span class="btn btn-primary btn-sm btn-file">
                                                        <span class="fileinput-new">{{ __('Admin::base.selectphoto') }}</span>
                                                        <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                                        <input type="file" name="img_banner" id="img_banner" accept="image/*" multiple="" required/>
                                                    </span>
                                                    <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="merchant_config_whatsapp" class="col-sm-5 control-label">Flyer</label>
                                        <div class="col-sm-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                                    <img src="{!! (!empty($merchant->flyer)) ? asset($merchant->flyer->upload_path.$merchant->flyer->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                                                <div>
                                                    <span class="btn btn-primary btn-sm btn-file">
                                                        <span class="fileinput-new">{{ __('Admin::base.selectphoto') }}</span>
                                                        <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                                        <input type="file" name="img_flyer" id="img_flyer" accept="image/*" multiple="" required/>
                                                    </span>
                                                    <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="merchant_config_whatsapp" class="col-sm-5 control-label">Store Front</label>
                                        <div class="col-sm-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                                    <img src="{!! (!empty($merchant->storefront)) ? asset($merchant->storefront->upload_path.$merchant->storefront->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                                                <div>
                                                    <span class="btn btn-primary btn-sm btn-file">
                                                        <span class="fileinput-new">{{ __('Admin::base.selectphoto') }}</span>
                                                        <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                                        <input type="file" name="img_storefront" id="img_storefront" accept="image/*" multiple="" required/>
                                                    </span>
                                                    <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="merchant_config_whatsapp" class="col-sm-5 control-label">Background</label>
                                        <div class="col-sm-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 120px; height: 120px;" data-trigger="fileinput">
                                                    <img src="{!! (!empty($merchant->background)) ? asset($merchant->background->upload_path.$merchant->background->upload_filename) : asset('/img/noimage.jpg') !!}" alt="..." height="120" width="120">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 170px; max-height: 170px"></div>
                                                <div>
                                                    <span class="btn btn-primary btn-sm btn-file">
                                                        <span class="fileinput-new">{{ __('Admin::base.selectphoto') }}</span>
                                                        <span class="fileinput-exists">{{ __('Admin::base.change') }}</span>
                                                        <input type="file" name="img_background" id="img_background" accept="image/*" multiple="" required/>
                                                    </span>
                                                    <!--a href="#" class="btn btn-warning btn-sm fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endhasrole

                </div>

                <div class="col-md-6">
                    
                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Merchant::merchant.voucher') }}</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label for="merchant_config_voucher1" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 1</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_config_voucher1 != '') ? $merchant->merchant_config_voucher1 : old('merchant_config_voucher1') }}" id="merchant_config_voucher1" type="number" name="merchant_config_voucher1" step="0.1" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 2</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_config_voucher2 != '') ? $merchant->merchant_config_voucher2 : old('merchant_config_voucher2') }}" id="merchant_config_voucher2" type="number" name="merchant_config_voucher2" step="0.1" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher3" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 3</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_config_voucher3 != '') ? $merchant->merchant_config_voucher3 : old('merchant_config_voucher3') }}" id="merchant_config_voucher3" type="number" name="merchant_config_voucher3" step="0.1" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher4" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 4</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_config_voucher4 != '') ? $merchant->merchant_config_voucher4 : old('merchant_config_voucher4') }}" id="merchant_config_voucher4" type="number" name="merchant_config_voucher4" step="0.1" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher5" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 5</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_config_voucher5 != '') ? $merchant->merchant_config_voucher5 : old('merchant_config_voucher5') }}" id="merchant_config_voucher5" type="number" name="merchant_config_voucher5" step="0.1" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher6" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 6</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_config_voucher6 != '') ? $merchant->merchant_config_voucher6 : old('merchant_config_voucher6') }}" id="merchant_config_voucher6" type="number" name="merchant_config_voucher6" step="0.1" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="merchant_config_voucher6" class="col-sm-3 control-label">{{ __('Merchant::merchant.termcondition') }}</label>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea cols="80" id="merchant_config_termsconditions" name="merchant_config_termsconditions" rows="10" >{{ ($merchant->merchant_config_termsconditions != '') ? $merchant->merchant_config_termsconditions : old('merchant_config_termsconditions') }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Merchant::merchant.shippingratebyproduct') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">{{ __('Merchant::merchant.free') }}</label>
                                <div class="col-sm-9">
                                    <div class="checkbox checbox-switch switch-primary">
                                        <label>
                                            <input type="checkbox" class="form-control" name="merchant_config_ship_status" id="merchant_config_ship_status" value="0" {{ ($merchant->merchant_config_ship_status==0) ? 'checked' : '' }} />
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label"><b>{{ __('Merchant::merchant.west') }} Malaysia</b></label>
                                <div class="col-sm-9">&nbsp;</div>
                            </div>
                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">{{ __('Merchant::merchant.upto') }}</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_west_upto_weight != '') ? $merchant->merchant_config_ship_west_upto_weight : old('merchant_config_ship_west_upto_weight') }}" id="merchant_config_ship_west_upto_weight" type="number" name="merchant_config_ship_west_upto_weight" step="0.1" required/>
                                        <div class="input-group-addon">KG</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <div class="input-group-addon">MYR</div>
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_west_upto_price != '') ? $merchant->merchant_config_ship_west_upto_price : old('merchant_config_ship_west_upto_price') }}" id="merchant_config_ship_west_upto_price" type="number" name="merchant_config_ship_west_upto_price" step="0.1" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">{{ __('Merchant::merchant.foradditional') }}</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_west_add_weight != '') ? $merchant->merchant_config_ship_west_add_weight : old('merchant_config_ship_west_add_weight') }}" id="merchant_config_ship_west_add_weight" type="number" name="merchant_config_ship_west_add_weight" step="0.1" required/>
                                        <div class="input-group-addon">KG</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <div class="input-group-addon">MYR</div>
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_west_add_price != '') ? $merchant->merchant_config_ship_west_add_price : old('merchant_config_ship_west_add_price') }}" id="merchant_config_ship_west_add_price" type="number" name="merchant_config_ship_west_add_price" step="0.1" required/>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label"><b>{{ __('Merchant::merchant.east') }} Malaysia</b></label>
                                <div class="col-sm-9">&nbsp;</div>
                            </div>
                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">{{ __('Merchant::merchant.upto') }}</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_east_upto_weight != '') ? $merchant->merchant_config_ship_east_upto_weight : old('merchant_config_ship_east_upto_weight') }}" id="merchant_config_ship_east_upto_weight" type="number" name="merchant_config_ship_east_upto_weight" step="0.1" required/>
                                        <div class="input-group-addon">KG</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <div class="input-group-addon">MYR</div>
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_east_upto_price != '') ? $merchant->merchant_config_ship_east_upto_price : old('merchant_config_ship_east_upto_price') }}" id="merchant_config_ship_east_upto_price" type="number" name="merchant_config_ship_east_upto_price" step="0.1" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">{{ __('Merchant::merchant.foradditional') }}</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_east_add_weight != '') ? $merchant->merchant_config_ship_east_add_weight : old('merchant_config_ship_east_add_weight') }}" id="merchant_config_ship_east_add_weight" type="number" name="merchant_config_ship_east_add_weight" step="0.1" required/>
                                        <div class="input-group-addon">KG</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <div class="input-group-addon">MYR</div>
                                        <input class="form-control shipprice" value="{{ ($merchant->merchant_config_ship_east_add_price != '') ? $merchant->merchant_config_ship_east_add_price : old('merchant_config_ship_east_add_price') }}" id="merchant_config_ship_east_add_price" type="number" name="merchant_config_ship_east_add_price" step="0.1" equired/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Search Engine Optimization (SEO)</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">Meta Description</label>
                                <div class="col-sm-9">
                                    <textarea name="merchant_config_meta_description" id="merchant_config_meta_description" class="form-control">{{ ($merchant->merchant_config_meta_description != '') ? $merchant->merchant_config_meta_description : old('merchant_config_meta_description') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">Meta Keywords</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="merchant_config_meta_keyword" id="merchant_config_meta_keyword" type="text" value="{{ ($merchant->merchant_config_meta_keyword != '') ? $merchant->merchant_config_meta_keyword : old('merchant_config_meta_keyword') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">Og Title</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="merchant_config_og_title" id="merchant_config_og_title" type="text" value="{{ ($merchant->merchant_config_og_title != '') ? $merchant->merchant_config_og_title : old('merchant_config_og_title') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">Og URL</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="merchant_config_og_url" id="merchant_config_og_url" type="text" value="{{ ($merchant->merchant_config_og_url != '') ? $merchant->merchant_config_og_url : old('merchant_config_og_url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">Og Description</label>
                                <div class="col-sm-9">
                                    <textarea name="merchant_config_og_description" id="merchant_config_og_description" class="form-control">{{ ($merchant->merchant_config_og_description != '') ? $merchant->merchant_config_og_description : old('merchant_config_og_description') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_voucher2" class="col-sm-3 control-label">Og Sitename</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="merchant_config_og_sitename" id="merchant_config_og_sitename" type="text" value="{{ ($merchant->merchant_config_og_sitename != '') ? $merchant->merchant_config_og_sitename : old('merchant_config_og_sitename') }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- payment method -->
                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Payment Method</h3>
                        </div>
                        <div class="box-body">

                            <input checked="" id="{{ $directPay->payment_method_description }}" type="radio" name="directpayment" value="{{ $directPay->payment_method_id }}">
                            <label for="{{ $directPay->payment_method_description }}">{{ $directPay->payment_method_description }}</label><br>
                            @foreach($paymentMethods as $paymentMethod)
                                <input <?php echo ($selected == $paymentMethod->payment_method_id) ? 'checked=""' : '' ?> onclick="checkedPaymentGateway({{ $paymentMethod->payment_method_id }}, this)" id="{{ $paymentMethod->payment_method_description }}" type="radio" name="gatewaypayment" value="{{ $paymentMethod->payment_method_id }}">
                                <label for="{{ $paymentMethod->payment_method_description }}">{{ $paymentMethod->payment_method_description }}</label>
                                <span class="payment_param"><a class="settingparams" data-id="{{ $paymentMethod->payment_method_id }}" data-toggle="modal" data-target="#myModal" href="javascript:;">(settings)</a></span>
                                <br>
                            @endforeach
                        </div>
                    </div>

                    <!-- SMTP configuration -->
                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">SMTP Configuration</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="merchant_config_smtp_host" class="col-sm-3 control-label">Host</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="merchant_config_smtp_host" id="merchant_config_smtp_host" type="text" value="{{ ($merchant->merchant_config_smtp_host != '') ? $merchant->merchant_config_smtp_host : old('merchant_config_smtp_host') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_smtp_username" class="col-sm-3 control-label">Username</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="merchant_config_smtp_username" id="merchant_config_smtp_username" type="text" value="{{ ($merchant->merchant_config_smtp_username != '') ? $merchant->merchant_config_smtp_username : old('merchant_config_smtp_username') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_smtp_password" class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="merchant_config_smtp_password" id="merchant_config_smtp_password" type="password" value="{{ ($merchant->merchant_config_smtp_password != '') ? $merchant->merchant_config_smtp_password : old('merchant_config_smtp_password') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_config_smtp_encryption" class="col-sm-3 control-label">Encryption</label>
                                <div class="col-sm-9">                                    
                                    {{ Form::smtp_encryption('merchant_config_smtp_encryption', [''=>'-- please select --'], $merchant->merchant_config_smtp_encryption, ['class'=>'form-control']) }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    <div class="box-footer divfooter">
        <a href="{{ URL::to('merchant') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
        @if(Auth::user()->can('merchant.editconfig'))
        <button type="button" class="btn btn-sm btn-success pull-right submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
        @endif
    </div>
    {!! Form::close() !!}
</div>
@stop

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    {!! Form::open(['action'=>['\App\Modules\Merchant\Controllers\MerchantController@savePaymentParameters'], 'method'=>'post', 'class'=>'form-horizontal']) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title modal_title"></h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="modal_merchant_id" class="modal_merchant_id">
        <input type="hidden" name="modal_merchant_payment_method_id" class="modal_merchant_payment_method_id">
        <input type="hidden" name="modal_merchant_config_id" class="modal_merchant_config_id">
        <span class="modal_html"></span>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    {!! Form::close() !!}
    </div>

  </div>
</div>

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">
<script type="text/javascript">

    CKEDITOR.config.devtools_styles =
        '#cke_tooltip { line-height: 20px; font-size: 12px; padding: 5px; border: 2px solid #333; background: #ffffff }' +
        '#cke_tooltip h2 { font-size: 14px; border-bottom: 1px solid; margin: 0; padding: 1px; }' +
        '#cke_tooltip ul { padding: 0pt; list-style-type: none; }';

    CKEDITOR.replace( 'merchant_config_termsconditions', {
        height: 200,
        extraPlugins: 'devtools'
    });

    $(document).ready(function() {

        @if($merchant->merchant_config_ship_status == '0')
            $('.shipprice').attr('readonly','readonly');
        @endif

        $('#merchant_config_ship_status').on('change', function(){
            if(this.checked) {
                $('.shipprice').attr('readonly','readonly');
            }else{
                $('.shipprice').removeAttr('readonly');
            }
        });

        $(document).on('click', '.addmobile', function() {
            $('#mobilediv tr:last').after('<tr><td><input name="merchant_mobile_no[]" class="form-control" maxlength="16" type="text" placeholder=" 0123456789" required></td><td width="30%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a></td></tr>');
        });

        $(document).on('click', '.addemail', function() {
            $('#emaildiv tr:last').after('<tr><td><input name="merchant_config_email_address[]" class="form-control" maxlength="100" type="text" placeholder=" myemail@gmail.com" required></td><td width="15%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a></td></tr>');
        });

        $(document).on('click', '.removetr', function() {
            $(this).parent().parent().remove();
        });

        @if($merchant->merchant_type_id != '')
            $('#merchant_type_id').val('{{ $merchant->merchant_type_id }}');
        @else
            $('#merchant_type_id').val('{{ old("merchant_type_id") }}');
        @endif

        if($('#merchant_config_email').val()!=''){
            @if($merchant->merchant_config_email != '')
                $('#thisemail').val('{{ $merchant->merchant_config_email }}');
            @else
                $('#thisemail').val('{{ old("merchant_config_email") }}');
            @endif
        }

        $('#merchant_config_email').on('keyup', function(e){
            
            $('#thisemail').val('');
            $('#thisemail').val($(this).val());

        });

        $('.packagelistdiv').hide();

        //if($('#merchant_package_id').val()!=''){
            @if($merchant->merchant_package_id != '')
                getPackageList('{{ $merchant->merchant_package_id }}');
                $('#merchant_package_id').val('{{ $merchant->merchant_package_id }}');
            @else
                getPackageList('{{ old("merchant_package_id") }}');
            @endif
        //}

        $('#merchant_package_id').on('change', function(e){
            
            var id = $(this).val();
            getPackageList(id);

        });

        function getPackageList(id){
            
            $('.viewpackagelist').empty();
            //var id = $(this).val();

            if(id!=''){
                $.ajax({
                    url: '{{ URL::to("merchant/package/getSubPackageInfo") }}/'+id,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data!=null){
                            var list = '<table class="table"><tbody>';

                            list += data.html;

                            list += '<tr><td><i class="fa fa-dot-circle"></i>  {{ __("Merchant::package.maxproduct") }}: ';
                            if(data.package.merchant_package_max_product==0){
                                list += '{{ __("Admin::base.unlimited") }}';
                            }else{
                                list += data.package.merchant_package_max_product;
                            }
                            list += '</td></tr>';

                            list += '</tbody></table>';

                            $('.viewpackagelist').append(list);
                            $('.packagelistdiv').show();
                        }

                    }
                });
            }

        }
       
        //if($('#template_id').val()!=''){
            @if($merchant->template_id != '')
                $('#template_id').val('{{ $merchant->template_id }}');
                getTemplateInfo('{{ $merchant->template_id }}');
            @else
                //$('#template_id').val('{{ old("template_id") }}');
                getTemplateInfo('{{ old("template_id") }}');
            @endif
        //}

        $('#template_id').on('change', function(e){
            
            var id = $(this).val();
            getTemplateInfo(id);

        });

        function getTemplateInfo(id){
            
            $('.viewtemplatediv').empty();
            //var id = $(this).val();

            if(id!=''){
                $.ajax({
                    url: '{{ URL::to("merchant/template/getInfo") }}/'+id,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data!=null){
                            $('.viewtemplatediv').append('<a data-toggle="tooltip" title="{{ __("Merchant::template.viewtemplate") }}" class="btn btn-xs btn-success pull-right" target="_blank" href="'+data.template_url+'"><i class="fa fa-search"></i> View</a>');
                        }

                    }
                });
            }

        }

        //if($('#merchant_config_postcode').val()!=''){
            @if($merchant->merchant_config_postcode != '')
                getstatedistrict('{{ $merchant->merchant_config_postcode }}');
            @else
                getstatedistrict('{{ old("merchant_config_postcode") }}');
            @endif
        //}

        $('#merchant_config_postcode').on('keyup', function(e){
            
            var code = $(this).val();
            getstatedistrict(code);

        });

        function getstatedistrict(code){

            $('#district_id').empty();
            $('#state_id').empty();
            
            if(code.length>4){

                $.ajax({
                    url: '{{ URL::to("admin/getStateDistrict") }}/'+code,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data.states!=null){
                            console.log(data);
                            var district = data.districts;
                            var state = data.states;

                            $('#district_id').append('<option value="'+district.district_id+'">'+district.district_desc+'</option>');
                            $('#state_id').append('<option value="'+state.state_id+'">'+state.state_desc+'</option>');
                        }else{
                            swal('{{ __("Admin::base.norecordfound") }}','{{ __("Admin::base.chooseother") }}','error');
                            $('#district_id').append('<option value="">-- No Record Found --</option>');
                            $('#state_id').append('<option value="">-- No Record Found --</option>');
                        }

                    }
                });

            }else{
                $('#district_id').append('<option value="">{{ __("Admin::base.please_type").' '.__("Admin::base.postcode") }}</option>');
                $('#state_id').append('<option value="">{{ __("Admin::base.please_type").' '.__("Admin::base.postcode") }}</option>');
            }

        }

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

    });

    function checkedPaymentGateway(a, b) {

        // $('.payment_param').hide();

        $.ajax({
            url: '{{ URL::to("merchant/set/payment/gateway") }}',
            data: {gid: a, mid: $('input[name=merchant_id]').val() },
            type: 'post',
            datatype: 'json',
            success:function(data) {
            }
        });
    }

    $('.settingparams').click(function(){

        $('.modal_merchant_id').val( $('input[name=merchant_id]').val() );
        $('.modal_merchant_config_id').val( $('input[name=merchant_config_id]').val() );
        $('.modal_merchant_payment_method_id').val( $(this).data('id') );

        // get parameters
        $.ajax({
            url: '{{ URL::to("merchant/payment/gateway/params") }}',
            data: {pid: $('.modal_merchant_payment_method_id').val(), mid: $('.modal_merchant_id').val() },
            type: 'get',
            datatype: 'json',
            success:function(data) {
                $('.modal_html').html(data.html);
                $('.modal_title').text(data.title)
            }
        })

    });

</script>
@stop
          