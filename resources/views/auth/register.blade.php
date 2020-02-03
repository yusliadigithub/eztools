@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register New User</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Fullname</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="newuser@domain.com" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('idno') ? ' has-error' : '' }}">
                            <label for="idno" class="col-md-4 control-label">Identification #</label>

                            <div class="col-md-6">
                                <input id="idno" type="text" class="form-control" name="idno" value="{{ old('idno') }}" placeholder="IC Number/Passport Number" maxlength="16" required>

                                @if ($errors->has('idno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('idno') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('contactno') ? ' has-error' : '' }}">
                            <label for="contactno" class="col-md-4 control-label">Contact #</label>

                            <div class="col-md-6">
                                <input id="contactno" type="text" class="form-control" name="contactno" value="{{ old('contactno') }}" placeholder="011-23456789" maxlength="16" required>

                                @if ($errors->has('contactno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contactno') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('property_type_id') ? ' has-error' : '' }}">
                            <label for="property_type_id" class="col-md-4 control-label">Type</label>
                            <div class="col-md-6">
                                <select name="property_type_id" id="property_type_id" class="form-control" disabled>
                                    @if(count($types)>0)
                                        @foreach($types as $type)
                                            <option value="{!! $type->property_type_id !!}">{!! $type->property_type_desc !!}</option>
                                        @endforeach
                                    @else
                                        <option value="">-- Empty --</option>
                                    @endif
                                </select> 

                                @if ($errors->has('property_type_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('property_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('property_block_id') ? ' has-error' : '' }}">
                            <label for="property_block_id" class="col-md-4 control-label">Block</label>
                            <div class="col-md-6">
                                <select name="property_block_id" id="property_block_id" class="form-control" required>
                                    <option value="">-- Please Select --</option>
                                    @if(count($blocks)>0)
                                        @foreach($blocks as $block)
                                            <option value="{!! $block->property_block_id !!}">{!! $block->property_block_desc !!}</option>
                                        @endforeach
                                    @else
                                        <option value="">-- Empty --</option>
                                    @endif
                                </select> 

                                @if ($errors->has('property_block_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('property_block_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('property_level_id') ? ' has-error' : '' }}">
                            <label for="property_level_id" class="col-md-4 control-label">Level</label>
                            <div class="col-md-6">
                                <select name="property_level_id" id="property_level_id" class="form-control" required>
                                    <option value="">-- Please Select --</option>
                                    @if(count($levels)>0)
                                        @foreach($levels as $level)
                                            <option value="{!! $level->property_level_id !!}">{!! $level->property_level_desc !!}</option>
                                        @endforeach
                                    @else
                                        <option value="">-- Empty --</option>
                                    @endif
                                </select> 

                                @if ($errors->has('property_level_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('property_level_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('properties_id') ? ' has-error' : '' }}">
                            <label for="properties_id" class="col-md-4 control-label">Unit #</label>
                            <div class="col-md-6">
                                <select name="properties_id" id="properties_id" class="form-control" required>
                                    <option value="">-- Please Select Block And Level --</option>
                                </select> 

                                @if ($errors->has('properties_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('properties_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Register</button>
                                <a type="button" class="btn btn-danger" href="{{ route('login') }}">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
