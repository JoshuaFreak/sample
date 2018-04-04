<!DOCTYPE html>

<html lang="en">

    <head id="Starter-Site">

        <meta charset="UTF-8">

        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title> Cebu International Academy :: @yield('title')</title>

        <meta name="keywords" content="" />
        <meta name="author" content="" />
        <meta name="description" content="" />
        <meta name="google-site-verification" content="">
        <meta name="DC.title" content="CIA">
        <meta name="DC.subject" content="">
        <meta name="DC.creator" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <link href="{{asset('assets/site/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/font-awesome-4.2.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/home.css')}}" rel="stylesheet">
        <link rel="shortcut icon" href="{{{ asset('assets/site/ico/cia_logo.png') }}}">
        <link rel="stylesheet" href="{{asset('assets/site/css/ihover.css')}}"/>
        <style type="text/css">
            .navbar {
                height: 20px !important;
            }
        </style>
        @yield('styles')
    </head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;" >
        	<div class="container">
	            <div class="navbar-header">
	                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	                    <span class="sr-only">Toggle navigation</span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                </button>
                    <a class="navbar-brand" href="{!!URL::to('module')!!}" style="color:#FFFFFF"><img src="{{{ asset('assets/site/images/cia_logo.png') }}}" class="logo"></a>
	            </div>
	            <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav pull-right">
                        @if (Auth::check())
                            <li><a href="#">{{{ Lang::get('site.login_as') }}} {{{ Auth::user()->username }}}</a></li>
                            <li><a href="{{{ URL::to('auth/logout') }}}">{{{ Lang::get('site.logout') }}}</a></li>
                        @else
                            <li {{ (Request::is('auth/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('auth/login') }}}">{{{ Lang::get('site.login') }}}</a></li>
                        @endif
                    </ul>
				</div>
			</div>
        </nav>
			@yield('content')
    </div>
    <footer style="background-color:#A50E07 !important;">
        <div class="container">
            <h5>Powered by: <img src="{{{ asset('assets/site/images/itechrar white logo.png') }}}" style="width:100px; height:30px;"></h5>
        </div>
    </footer>
        @yield('scripts')
    </body>
</html>




