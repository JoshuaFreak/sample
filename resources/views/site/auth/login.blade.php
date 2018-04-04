@extends('site.layouts.default_login')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user.login') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>GAKKOU | Login</title>
        <style>
            .body{
              background-color:#87CEFA;
            }
            label
            {
                width: 127px;
                height: 19px;
                color: #6D6E72;
                font-family: 'Open Sans', sans-serif !important;
                font-size: 25px;
                font-weight: 400;
                line-height: 29px;
                text-transform: uppercase;
            }
            input
            {
                width: 288px !important;
                height: 36px;
                /*opacity: 0.45;*/
                background-color: #d8d9db;
            }
            button
            {
                width: 126px;
                height: 36px;
                color: #ffffff;
                background-color: #af1d23;
            }
        </style>
    </head>
<body class="body">
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="container" style="width: 541px;height: 252px;background-color: white;box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);margin-top: 120px;">
        <img src="{{{ asset('assets/site/images/cia_logo.png') }}}" style="margin-top: -55px;"></img>
        <div class="page-header" style="color: #B62127;margin-top: -55px;margin-left: 100px;">
            <h3 style="padding-left: 45px;">{{{ Lang::get('user.login_to_account') }}}</h3>
        </div>
        <form class="navbar-form navbar-right" method="POST" action="{{URL::to('auth/login')}}"  accept-charset="UTF-8">
            <!-- CSRF Token -->
        	<input type="hidden" name="_token" value="{{ csrf_token() }}">
        	<!-- ./ csrf token -->
            <fieldset>
                <div class="form-group {{$errors->has('username')?'has-error':''}}">
                    <label class="col-md-4" for="username">
                        {{ Lang::get('user.username') }}
                    </label>
                    <div class="col-md-8">
                        <input class="form-control" tabindex="1" type="text" name="username" id="username" value="{{ Input::old('email') }}">
                        <span class="help-block">{!!$errors->first('username', '<span class="help-block">:message </span>')!!}</span>
                    </div>
                </div>
                <div class="form-group {{$errors->has('username')?'has-error':''}}">
                    <label class="col-md-4" for="password">
                        {{ Lang::get('user.password') }}
                    </label>
                    <div class="col-md-8">
                        <input class="form-control" tabindex="2" type="password" name="password" id="password">
                         <span class="help-block">{!!$errors->first('password', '<span class="help-block">:message </span>')!!}</span>
                    </div>
                </div>
            </fieldset>
            <div class="form-group" style="padding-left: 260px;padding-top: 10px;">
                <div class="col-md-offset-5 col-md-12">
                    <button tabindex="3" type="submit" class="btn">{{ Lang::get('user.submit') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="container" style="width: 541px;height: 18px;background-color: #b62127;">
    </div>
</body>
</html>
@stop