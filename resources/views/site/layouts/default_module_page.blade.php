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
        <link href="{{asset('assets/site/chartist/chartist.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/plugins/metisMenu/metisMenu.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/sb-admin-2.css')}}" rel="stylesheet">
        <link href="{{{asset('assets/site/css/select2.css') }}}"  rel="stylesheet" type="text/css" > 
        <link href="{{asset('assets/site/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"> 
        <link href="{{asset('assets/site/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet"> 
        <link href="{{asset('assets/site/css/jquery.dataTables.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/dataTables.bootstrap.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/colorbox.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/font-awesome-4.2.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/typeahead.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/home.css')}}" rel="stylesheet">

        <link href="{{asset('assets/site/css/fullcalendar.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/sweetalert.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/fullcalendar.print.css')}}" rel="stylesheet" type="text/css" media='print'>
        <link rel="shortcut icon" href="{{{ asset('assets/site/ico/cia_logo.png') }}}">
        <link rel="stylesheet" href="{{asset('assets/site/css/ihover.css')}}"/>
        <style type="text/css">
            .navbar {
                height: 20px !important;
            }
            #body {
              display: flex;
              height: 100vh;
              width: 100%;
              justify-content: center;
              align-items: center;
              background: #512DA8;
              /*background: #272822;*/
            }

            .loader {
              position: relative;
            }

            .one {
              position: absolute;
              border-radius: 50%;
              background: #FFFFFF;
              opacity: .0;
              animation: loading 1.3s .65s infinite;
              height: 40px;
              width: 40px;
            }

            .two {
              position: absolute;
              border-radius: 50%;
              background: #FF4081;
              opacity: .0;
              animation: loading 1.3s infinite;
              height: 40px;
              width: 40px;
            }


            @keyframes loading {
              0% {
                opacity: .0;
                transform: scale(.15);
                box-shadow: 0 0 2px rgba(black, .1);
              }
              50% {
                opacity: 1;
                transform: scale(2);
                box-shadow: 0 0 10px rgba(black, .1);
              }
              100% {
                opacity: .0;
                transform: scale(.15);
                box-shadow: 0 0 2px rgba(black, .1);
              }
            }
            #page-wrapper
            {
                background-color: #E6E6E6 !important;
                background-color: #F3F3F3 !important;
            }
            .page-header
            {
                color:#fff;background-color:#6f8c80;padding-top:0px;height:45px !important;
                padding-top: 0px;
            }
        </style>
        @yield('styles')
    </head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;">
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
    <footer id="default_footer">
        <div class="container">
            <h5>Powered by: <img src="{{{ asset('assets/site/images/itechrar white logo.png') }}}" style="width:120px;"></h5>
        </div>
    </footer>

    <script src="{{asset('assets/site/chartist/chartist.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery-2.1.1.min.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery-ui.1.11.2.min.js')}}"></script>
    <script src="{{asset('assets/site/js/plugins/metisMenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/site/js/sb-admin-2.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap-datepicker.min.js')}}"></script>         
    <script src="{{asset('assets/site/js/bootstrap-colorpicker.min.js')}}"></script>         
    <script src="{{asset('assets/site/js/moment.min.js')}}"></script> 
    <script src="{{asset('assets/site/js/bootstrap-datetimepicker.min.js')}}"></script> 
    <script src="{{asset('assets/site/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap-dataTables-paging.js')}}"></script>
    <script src="{{asset('assets/site/js/datatables.fnReloadAjax.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery.colorbox.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery.numeric.js')}}"></script> 
    <script src="{{asset('assets/site/js/modal.js')}}"></script>
    <script src="{{asset('assets/site/js/SelectListChange.js')}}"></script> 
    <script src="{{asset('assets/site/js/typeahead.bundle.js')}}"></script>
    <script src="{{asset('assets/site/js/select2.js')}}"></script>
    <script src="{{asset('assets/site/js/fullcalendar.min.js')}}"></script> 
    <script src="{{asset('assets/site/js/jquery.print.js')}}"></script> 
    <script src="{{asset('assets/site/js/sweetalert.min.js')}}"></script> 
    <script>
        $('.carousel').carousel({
          interval: 1000 * 10
        });
    </script>

        @yield('scripts')
    </body>
</html>




