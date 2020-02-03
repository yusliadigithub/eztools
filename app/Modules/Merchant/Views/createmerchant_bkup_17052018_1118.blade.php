@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('Merchant::merchant.detail') }}</h3>
    </div>
    @if($merchant->merchant_id != '')
    {!! Form::open(['action'=>['\App\Modules\Merchant\Controllers\MerchantController@update',$merchant->merchant_id], 'method'=>'put', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
    @else
    {!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\MerchantController@store', 'method'=>'post', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
    @endif
    <input name="merchant_id" type="hidden" value="{{ $merchant->merchant_id }}">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="merchant_person_incharge" class="col-sm-4 control-label">{{ __('Merchant::merchant.contactperson') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_person_incharge" id="merchant_person_incharge" maxlength="100" type="text" value="{{ ($merchant->merchant_person_incharge != '') ? $merchant->merchant_person_incharge : old('merchant_person_incharge') }}" placeholder="{{ __('Merchant::merchant.contactperson') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_email" class="col-sm-4 control-label">{{ __('Merchant::merchant.email') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_email" id="merchant_email" maxlength="100" placeholder="myemail@gmail.com" type="email" value="{{ ($merchant->merchant_email != '') ? $merchant->merchant_email : old('merchant_email') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_username" class="col-sm-4 control-label">{{ __('Admin::user.username') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_username" id="merchant_username" maxlength="100" type="text" value="{{ ($merchant->merchant_username != '') ? $merchant->merchant_username : old('merchant_username') }}" placeholder="{{ __('Admin::user.username') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label">{{ __('Admin::user.password') }}</label>
                        <div class="col-sm-6">
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="col-sm-4 control-label">{{ __('Admin::user.confirmpassword') }}</label>
                        <div class="col-sm-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="merchant_name" class="col-sm-4 control-label">{{ __('Merchant::merchant.companyname') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_name" id="merchant_name" maxlength="100" placeholder="{{ __('Merchant::merchant.companyname') }}" type="text" value="{{ ($merchant->merchant_name != '') ? $merchant->merchant_name : old('merchant_name') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_ssmno" class="col-sm-4 control-label">{{ __('Merchant::merchant.ssmno') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_ssmno" id="merchant_ssmno" maxlength="20" placeholder="{{ __('Merchant::merchant.ssmno') }}" type="text" value="{{ ($merchant->merchant_ssmno != '') ? $merchant->merchant_ssmno : old('merchant_ssmno') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_gstno" class="col-sm-4 control-label">{{ __('Merchant::merchant.gstno') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_gstno" id="merchant_gstno" maxlength="20" placeholder="{{ __('Merchant::merchant.gstno') }}" type="text" value="{{ ($merchant->merchant_gstno != '') ? $merchant->merchant_gstno : old('merchant_gstno') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_type_id" class="col-sm-4 control-label">{{ __('Admin::base.type') }}</label>
                        <div class="col-sm-6">
                            <select name="merchant_type_id" id="merchant_type_id" class="form-control" required>
                                <option value="">{{ __('Admin::base.please_select') }}</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->merchant_type_id }}" {{ (old('merchant_type_id')==$type->merchant_type_id) ? 'selected' : '' }}>{{ $type->merchant_type_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_description" class="col-sm-4 control-label">{{ __('Admin::base.description') }}</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="merchant_description" name="merchant_description" required>{{ ($merchant->merchant_description != '') ? $merchant->merchant_description : old('merchant_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_address1" class="col-sm-4 control-label">{{ __('Merchant::merchant.address') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_address1" id="merchant_address1" maxlength="100" type="text" value="{{ ($merchant->merchant_address1 != '') ? $merchant->merchant_address1 : old('merchant_address1') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_address2" class="col-sm-4 control-label">&nbsp;</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_address2" id="merchant_address2" maxlength="100" type="text" value="{{ ($merchant->merchant_address2 != '') ? $merchant->merchant_address2 : old('merchant_address2') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_address3" class="col-sm-4 control-label">&nbsp;</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_address3" id="merchant_address3" maxlength="100" type="text" value="{{ ($merchant->merchant_address3 != '') ? $merchant->merchant_address3 : old('merchant_address3') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_postcode" class="col-sm-4 control-label">{{ __('Merchant::merchant.postcode') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_postcode" id="merchant_postcode" maxlength="5" type="text" value="{{ ($merchant->merchant_postcode != '') ? $merchant->merchant_postcode : old('merchant_postcode') }}" placeholder="eg: 43000" required>
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

                    <!--div class="form-group">
                        <label for="state_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.state') }}</label>
                        <div class="col-sm-4">
                            {{ Form::state('state_id', [''=>__('Admin::base.please_select')] , old('state_id'), ['class'=>'form-control','id'=>'state_id','required'=>'required']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="district_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.district') }}</label>
                        <div class="col-sm-4">
                            {{-- Form::district('district_id', [''=>__('Admin::base.please_select')] , old('district_id'), ['class'=>'form-control','id'=>'district_id','required'=>'required']) --}}
                            <select name="district_id" id="district_id" class="form-control" required>
                                <option value="">{{ __('Admin::base.please_select') }}</option>
                            </select>
                        </div>
                    </div-->

                    <div class="form-group">
                        <label for="merchant_latitude" class="col-sm-4 control-label">{{ __('Merchant::merchant.coordinate') }}</label>
                        <div class="col-sm-3">
                            <input class="form-control" name="merchant_latitude" id="merchant_latitude" maxlength="20" placeholder="Latitude" type="text" value="{{ ($merchant->merchant_latitude != '') ? $merchant->merchant_latitude : old('merchant_latitude') }}">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="merchant_longitude" id="merchant_longitude" maxlength="20" placeholder="Longitude" type="text" value="{{ ($merchant->merchant_longitude != '') ? $merchant->merchant_longitude : old('merchant_longitude') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_website" class="col-sm-4 control-label">{{ __('Merchant::merchant.website') }}</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="merchant_website" id="merchant_website" maxlength="100" type="text" placeholder="http://www.domain.com" value="{{ ($merchant->merchant_website != '') ? $merchant->merchant_website : old('merchant_website') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_officeno" class="col-sm-4 control-label">{{ __('Merchant::merchant.officeno') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_officeno" id="merchant_officeno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($merchant->merchant_officeno != '') ? $merchant->merchant_officeno : old('merchant_officeno') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_faxno" class="col-sm-4 control-label">{{ __('Merchant::merchant.faxno') }}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="merchant_faxno" id="merchant_faxno" maxlength="16" type="text" placeholder="0330003000" value="{{ ($merchant->merchant_faxno != '') ? $merchant->merchant_faxno : old('merchant_faxno') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="merchant_mobileno" class="col-sm-4 control-label">{{ __('Merchant::merchant.mobileno') }}</label>
                        <div class="col-sm-6">
                            <table class="table">
                                <tbody id="mobilediv">
                                    <tr>
                                        <td><input name="merchant_mobileno" id="merchant_mobileno" class="form-control" maxlength="16" type="text" placeholder=" 0123456789" value="{{ ($merchant->merchant_mobileno != '') ? $merchant->merchant_mobileno : old('merchant_mobileno') }}" required></td>
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
                                        <td><input name="thisemail" id="thisemail"  class="form-control" maxlength="100" type="text" value="{{ ($merchant->merchant_email != '') ? $merchant->merchant_email : old('thisemail') }}" disabled></td>
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

                    <!-- open social -->
                    @hasrole(['admin','agent','merchant'])
                    <div class="box box-solid box-success">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Merchant::merchant.socialnetwork') }}</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label for="merchant_whatsapp" class="col-sm-4 control-label">Whatsapp  <!--i class="fa fa-whatsapp"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_whatsapp" id="merchant_whatsapp" maxlength="100" type="text" value="{{ ($merchant->merchant_whatsapp != '') ? $merchant->merchant_whatsapp : old('merchant_whatsapp') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_telegram" class="col-sm-4 control-label">Telegram  <!--i class="fa fa-telegram"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_telegram" id="merchant_telegram" maxlength="100" type="text" value="{{ ($merchant->merchant_telegram != '') ? $merchant->merchant_telegram : old('merchant_telegram') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_facebook" class="col-sm-4 control-label">Facebook  <!--i class="fa fa-facebook"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_facebook" id="merchant_facebook" maxlength="100" type="text" value="{{ ($merchant->merchant_facebook != '') ? $merchant->merchant_facebook : old('merchant_facebook') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_wechat" class="col-sm-4 control-label">Wechat  <!--i class="fa fa-weixin"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_wechat" id="merchant_wechat" maxlength="100" type="text" value="{{ ($merchant->merchant_wechat != '') ? $merchant->merchant_wechat : old('merchant_wechat') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_line" class="col-sm-4 control-label">Line  <!--i class="fa fa-line"></i--></label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_line" id="merchant_line" maxlength="100" type="text" value="{{ ($merchant->merchant_line != '') ? $merchant->merchant_line : old('merchant_line') }}">
                                </div>
                            </div>

                        </div>
                    </div>
                    @endhasrole
                    <!-- close social-->

                </div>

                <div class="col-md-6">

                    @hasrole(['admin','agent','merchant'])
                    <div class="box box-solid box-default">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Admin::base.attachment') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="merchant_whatsapp" class="col-sm-5 control-label">Logo</label>
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
                                        <label for="merchant_whatsapp" class="col-sm-5 control-label">Flyer</label>
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
                                        <label for="merchant_whatsapp" class="col-sm-5 control-label">Store Front</label>
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
                                        <label for="merchant_whatsapp" class="col-sm-5 control-label">Background</label>
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

                    <div class="box box-solid box-info">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Merchant::merchant.voucher') }}</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label for="merchant_voucher1" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 1</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_voucher1 != '') ? $merchant->merchant_voucher1 : old('merchant_voucher1') }}" id="merchant_voucher1" type="number" name="merchant_voucher1" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_voucher2" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 2</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_voucher2 != '') ? $merchant->merchant_voucher2 : old('merchant_voucher2') }}" id="merchant_voucher2" type="number" name="merchant_voucher2" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_voucher3" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 3</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_voucher3 != '') ? $merchant->merchant_voucher3 : old('merchant_voucher3') }}" id="merchant_voucher3" type="number" name="merchant_voucher3" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_voucher4" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 4</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_voucher4 != '') ? $merchant->merchant_voucher4 : old('merchant_voucher4') }}" id="merchant_voucher4" type="number" name="merchant_voucher4" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_voucher5" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 5</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_voucher5 != '') ? $merchant->merchant_voucher5 : old('merchant_voucher5') }}" id="merchant_voucher5" type="number" name="merchant_voucher5" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_voucher6" class="col-sm-4 control-label">{{ __('Merchant::merchant.voucher') }} 6</label>
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                        <input class="form-control" value="{{ ($merchant->merchant_voucher6 != '') ? $merchant->merchant_voucher6 : old('merchant_voucher6') }}" id="merchant_voucher6" type="number" name="merchant_voucher6" required/>
                                        <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    @hasrole(['admin','agent','merchant'])
                    <div class="box box-solid box-warning">
                        <div class="box-header">
                            <h3 class="box-title">{{ __('Merchant::merchant.package') }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="merchant_appname" class="col-sm-4 control-label">{{ __('Merchant::merchant.appname') }}</label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_appname" id="merchant_appname" maxlength="100" type="text" value="{{ ($merchant->merchant_appname != '') ? $merchant->merchant_appname : old('merchant_appname') }}" placeholder="{{ __('Merchant::merchant.appname') }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="merchant_android" class="col-sm-4 control-label">Google Store</label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_android" id="merchant_android" maxlength="100" type="text" value="{{ ($merchant->merchant_android != '') ? $merchant->merchant_android : old('merchant_android') }}" placeholder="Google Store" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="merchant_ios" class="col-sm-4 control-label">Apple Store</label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="merchant_ios" id="merchant_ios" maxlength="100" type="text" value="{{ ($merchant->merchant_ios != '') ? $merchant->merchant_ios : old('merchant_ios') }}" placeholder="Apple Store" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="template_id" class="col-sm-4 control-label">{{ __('Merchant::merchant.websitetemplate') }}</label>
                                <div class="col-sm-6">
                                    <select name="template_id" id="template_id" class="form-control" required>
                                        <option value="" {{ (old('template_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_select') }}</option>
                                        @foreach($templates as $template)
                                            <option value="{{ $template->template_id }}" {{ (old('template_id')==$template->template_id) ? 'selected' : '' }}>{{ $template->template_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 viewtemplatediv"></div>
                            </div>

                            <div class="form-group">
                                <label for="merchant_package_id" class="col-sm-4 control-label">{{ __('Merchant::package.package') }}</label>
                                <div class="col-sm-6">
                                    <select name="merchant_package_id" id="merchant_package_id" class="form-control" required>
                                        <option value="" {{ (old('merchant_package_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_select') }}</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->merchant_package_id }}" {{ (old('merchant_package_id')==$package->merchant_package_id) ? 'selected' : '' }}>{{ $package->merchant_package_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group packagelistdiv">
                                <label class="col-sm-4 control-label">{{ __('Merchant::package.websitepackagelist') }}</label>
                                <div class="col-sm-6 viewpackagelist"></div>
                            </div>

                        </div>
                    </div>
                    @endhasrole
                </div>
            </div>
        </div>
    <div class="box-footer divfooter">
        <a href="{{ URL::to('merchant') }}" class="btn btn-sm btn-default">{{ __('Admin::base.close') }}</a>
        @if(Auth::user()->can('merchant.store'))
        <button type="button" class="btn btn-sm btn-success pull-right submitform"><i class="fa fa-check-circle"></i> {{ __('Admin::base.submit') }}</button>
        @endif
    </div>
    {!! Form::close() !!}
</div>

@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">
<script type="text/javascript">

    $(document).ready(function() {

        @if($disabled==1)
            $('.divfooter').hide();
        @endif

        $(document).on('click', '.addmobile', function() {
            $('#mobilediv tr:last').after('<tr><td><input name="merchant_mobile_no[]" class="form-control" maxlength="16" type="text" placeholder=" 0123456789" required></td><td width="30%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a></td></tr>');
        });

        $(document).on('click', '.addemail', function() {
            $('#emaildiv tr:last').after('<tr><td><input name="merchant_email_address[]" class="form-control" maxlength="100" type="text" placeholder=" myemail@gmail.com" required></td><td width="15%"><a class="btn btn-xs btn-danger pull-right removetr"><i class="fa fa-times-circle"></i></a></td></tr>');
        });

        $(document).on('click', '.removetr', function() {
            $(this).parent().parent().remove();
        });

        @if($merchant->merchant_type_id != '')
            $('#merchant_type_id').val('{{ $merchant->merchant_type_id }}');
        @else
            $('#merchant_type_id').val('{{ old("merchant_type_id") }}');
        @endif

        if($('#merchant_email').val()!=''){
            @if($merchant->merchant_email != '')
                $('#thisemail').val('{{ $merchant->merchant_email }}');
            @else
                $('#thisemail').val('{{ old("merchant_email") }}');
            @endif
        }

        $('#merchant_email').on('keyup', function(e){
            
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

        //if($('#merchant_postcode').val()!=''){
            @if($merchant->merchant_postcode != '')
                getstatedistrict('{{ $merchant->merchant_postcode }}');
            @else
                getstatedistrict('{{ old("merchant_postcode") }}');
            @endif
        //}

        $('#merchant_postcode').on('keyup', function(e){
            
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

        /*
        $('#state_id').on('change', function(e){
            
            var id = $(this).val();
            $('#district_id').empty();
            
            if(id!=''){

                $.ajax({
                    url: '{{ URL::to("admin/getDistrict") }}/'+id,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data!=''){
                            $('#district_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
                            $.each(data, function(key, value) {
                                $('#district_id').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                        }else{
                            $('#district_id').append('<option value="">-- No Record Found --</option>');
                        }

                    }
                });

            }else{
                $('#district_id').append('<option value="">-- Please Select State --</option>');
            }

        });*/

    });

</script>
@stop
          