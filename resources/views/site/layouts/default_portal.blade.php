<!DOCTYPE html>

<html lang="en">

    <head id="Starter-Site">

        <meta charset="UTF-8">

        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title> GAKKŌ SYSTEM :: @yield('title')</title>

        <meta name="keywords" content="" />
        <meta name="author" content="" />
        <meta name="description" content="" />
        <meta name="google-site-verification" content="">
        <meta name="DC.title" content="Laravel 5 Starter Site">
        <meta name="DC.subject" content="">
        <meta name="DC.creator" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <link href="{{asset('assets/site/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/plugins/metisMenu/metisMenu.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/sb-admin-2.css')}}" rel="stylesheet">
        <link href="{{{asset('assets/site/css/select2.css') }}}"  rel="stylesheet" type="text/css" > 
        <link href="{{asset('assets/site/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"> 

        <link href="{{asset('assets/site/css/jquery.dataTables.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/dataTables.bootstrap.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/colorbox.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/font-awesome-4.2.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/typeahead.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/home.css')}}" rel="stylesheet">

        <link href="{{asset('assets/site/css/jqx.base.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/fullcalendar.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/fullcalendar.print.css')}}" rel="stylesheet" type="text/css" media='print'>
        <link rel="shortcut icon" href="{{{ asset('assets/site/ico/favicon.png') }}}">
        <link href="{{asset('assets/site/css/fieldset.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/site/css/ihover.css')}}"/>
        <link href="{{asset('assets/site/css/sweetalert.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/handsontable.full.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/handsontable.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/event.css')}}" rel="stylesheet" type="text/css">
        
    </head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        	<div class="container">
	            <div class="navbar-header">
	                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	                    <span class="sr-only">Toggle navigation</span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                </button>
                    <a class="navbar-brand" href="#" style="color:#FFFFFF"><img src="{{{ asset('assets/site/images/logo.png') }}}" class="logo"></a>
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
    <footer>
        <div class="container">
            <h5>Powered by: <img src="{{{ asset('assets/site/images/itechrar white logo.png') }}}" style="width:150px; height:50px;"></h5>
        </div>
    </footer>

    <script src="{{asset('assets/site/js/jquery-2.1.1.min.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery-ui.1.11.2.min.js')}}"></script>
    <script src="{{asset('assets/site/js/plugins/metisMenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/site/js/sb-admin-2.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap-datepicker.min.js')}}"></script>         
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
    <script src="{{asset('assets/site/js/jqxcore.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxdata.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxbuttons.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxscrollbar.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxmenu.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxnumberinput.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxgrid.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxgrid.edit.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxgrid.aggregates.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxgrid.selection.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxgrid.columnsresize.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxlistbox.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxdropdownlist.js')}}"></script> 
    <script src="{{asset('assets/site/js/jqxdragdrop.js')}}"></script> 
    <script src="{{asset('assets/site/js/handsontable.full.js')}}"></script> 
    <script src="{{asset('assets/site/js/sweetalert.min.js')}}"></script> 
    <script>
        $('.carousel').carousel({
          interval: 1000 * 10
        });
    </script>

        @yield('scripts')
    </body>
</html>




