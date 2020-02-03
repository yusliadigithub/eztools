@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('Admin::user.registeragent') }}</div>
                @include('common.message')
                <div class="panel-body">
                    {!! Form::open(['action'=>'\App\Modules\Agent\Controllers\AgentController@store', 'method'=>'post', 'class'=>'form-horizontal', 'id'=>'newdataform','files'=>true]) !!}
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Admin::user.salutation') }}</label>
                            <div class="col-md-6">
                                <select name="salutation_id" id="salutation_id" class="form-control" required autofocus>
                                    <option value="" {{ (old('salutation_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_select') }}</option>
                                    @foreach($salutations as $salutation)
                                        <option value="{{ $salutation->salutation_id }}" {{ (old('salutation_id')==$salutation->salutation_id) ? 'selected' : '' }}>{{ $salutation->salutation_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{ __('Admin::user.agentfullname') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{ __('Admin::user.email') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="newuser@domain.com" required>
                            </div>
                        </div>

                        <!--div class="form-group">
                            <label for="email" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                            </div>
                        </div-->

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">{{ __('Admin::user.password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">{{ __('Admin::user.confirmpassword') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_detail_idno" class="col-md-4 control-label">{{ __('Admin::user.idno') }}</label>
                            <div class="col-md-6">
                                <input id="users_detail_idno" type="text" class="form-control" name="users_detail_idno" value="{{ old('users_detail_idno') }}" placeholder="eg: 800808108080" maxlength="12" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_detail_address1" class="col-md-4 control-label">{{ __('Merchant::merchant.address') }}</label>
                            <div class="col-md-6">
                                <input id="users_detail_address1" type="text" class="form-control" name="users_detail_address1" maxlength="100" value="{{ old('users_detail_address1') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_detail_address2" class="col-md-4 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                <input id="users_detail_address2" type="text" class="form-control" name="users_detail_address2" maxlength="100" value="{{ old('users_detail_address2') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_detail_address3" class="col-md-4 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                <input id="users_detail_address3" type="text" class="form-control" name="users_detail_address3" maxlength="100" value="{{ old('users_detail_address3') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_detail_postcode" class="col-md-4 control-label">{{ __('Merchant::merchant.postcode') }}</label>
                            <div class="col-md-6">
                                <input id="users_detail_postcode" type="text" class="form-control" name="users_detail_postcode" maxlength="5" value="{{ old('users_detail_postcode') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="district_id" class="col-md-4 control-label">{{ __('Merchant::merchant.district') }}</label>
                            <div class="col-md-6">
                                <select name="district_id" id="district_id" class="form-control" required>
                                    <option value="" {{ (old('district_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="state_id" class="col-md-4 control-label">{{ __('Merchant::merchant.state') }}</label>
                            <div class="col-md-6">
                                <!--select name="state_id" id="state_id" class="form-control" required>
                                    <option value="" {{ (old('state_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_select') }}</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->state_id }}" {{ (old('state_id')==$state->state_id) ? 'selected' : '' }}>{{ $state->state_desc }}</option>
                                    @endforeach
                                </select-->
                                <select name="state_id" id="state_id" class="form-control" required>
                                    <option value="" {{ (old('state_id')) ? 'selected' : '' }}>{{ __('Admin::base.please_type').' '.__('Admin::base.postcode') }}</option>
                                </select>
                                {{-- Form::state('state_id', [''=>__('Admin::base.please_select')] , old('state_id'), ['class'=>'form-control','id'=>'state_id','required'=>'required']) --}}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_detail_mobileno" class="col-md-4 control-label">{{ __('Merchant::merchant.mobileno') }}</label>
                            <div class="col-md-6">
                                <input id="users_detail_mobileno" type="text" class="form-control" name="users_detail_mobileno" value="{{ old('users_detail_mobileno') }}" placeholder="eg: 0123456789" maxlength="16" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_detail_mobileno" class="col-md-4 control-label">{{ __('Admin::base.personalphoto') }}</label>
                            <div class="col-md-6">
                                <input type="file" name="personalphoto[]">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Register</button>
                                <a type="button" class="btn btn-danger" href="{{ route('login') }}">Cancel</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
