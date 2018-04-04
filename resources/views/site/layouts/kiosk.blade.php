<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>
            @section('title')
            Cebu International Academy
            @show
        </title>
        @section('meta_keywords')
        <meta name="keywords" content="your, awesome, keywords, here" />
        @show
        @section('meta_author')
        <meta name="author" content="Jon Doe" />
        @show
        @section('meta_description')
        <meta name="description" content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei." />
        @show
        <!-- Mobile Specific Metas
        ================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{{ asset('assets/site/ico/cia_logo.png') }}}">
        <!-- CSS
        ================================================== -->
        <!-- <link href="{{asset('assets/site/css/bootstrap-combined.min.css')}}" rel="stylesheet"> -->
        <link href="{{asset('assets/site/css/style.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/font-awesome-4.2.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/typeahead.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/jquery-ui.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/bootstrap-timepicker.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/home.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/css/fullcalendar.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/site/css/fullcalendar.print.css')}}" rel="stylesheet" type="text/css" media='print'>
        <link href="{{asset('assets/site/css/sweetalert.css')}}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{asset('assets/site/css/ihover.css')}}"/>
        <link rel="stylesheet" href="{{asset('assets/site/css/ihover.min.css')}}"/>
        <link href="{{asset('assets/site/alertify/themes/alertify.bootstrap.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/alertify/themes/alertify.core.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/alertify/themes/alertify.default.css')}}" rel="stylesheet">
        <link href="{{asset('assets/site/keyboard/css/keyboard-dark.min.css')}}" rel="stylesheet">
        
        @section('styles')
        @show
        <style>

        .navbar-default .navbar-nav>li>a {
            color: #fff;
        }

        @font-face {
            font-family: Roboto;
            src: url(assets/site/fonts/Roboto/robotoslab-regular-webfont.woff);
        }


        @font-face {
         font-family: Roboto;
         src: url({{ URL::asset('assets/site/fonts/Roboto/roboto-regular-webfont.woff') }});
        }

        h1,h2,h3 {
            font-family: Roboto;
            font-size: 30px;
            font-weight: 500;
            color: #2C5871;
        }

        h4{
            font-family: Roboto;
            font-weight: 500;
            font-size: 20px;
            color: #1a5b8e;
        }

        p {
            font-family: Roboto;
            font-weight: 500;
            font-size: 15px;
            color: #1a5b8e;
        }

        .center-align{
          text-align: center;
          background-color:#FFFFFF;
          border-bottom-right-radius: 30px;
          border-bottom-left-radius: 30px;
          padding: 20px;
        }
        /*html, body {height: 80%; width:90%;}*/

        #home_footer {margin-top:20px;margin-bottom:0px;padding-top:40px;padding-bottom:0px;}
        #home_footer div:first-child {background-color:#05868c;margin-top:8px;padding-bottom:3px;}
        </style>

        <style>
            body {
                font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
            }
        </style>

        <script src="{{asset('assets/site/js/html5.js')}}"></script>
            <link rel="shortcut icon" href="{{{ asset('assets/site/images/favicon.png') }}}">
    </head>

    <body>
        <div id="wrapper">
           <!--  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="container">
                <div class="navbar-header">
                   
                   <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                   </button>
                </div>

                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                   <ul class="nav navbar-nav pull-right">
                       <a class="navbar-brand" href="{!!URL::to('/')!!}" style="color:#FFFFFF"><img src="{{{ asset('assets/site/images/logo.png') }}}" class="logo"></a> -->
                         <!-- <li><a>THREEH REDWOOD</a></li> -->
                   <!-- </ul>
                </div>
                </div>
            </nav> -->
            @yield('content')
        </div>
    <!--     <footer id="home_footer">
            <div class="navbar-fixed-bottom">
              <div style="color:white; margin-left:20px">Powered by:
                     <img src="{{{ asset('assets/site/images/itechrar white logo.png') }}}" style="width:120px; height:40px;">
                 </div>
            </div>
        </footer> --><!-- 

  
        <script src="{{asset('assets/site/js/jquery-2.1.4.min.js')}}"></script>
        <script src="{{asset('assets/site/js/jquery-ui.1.11.4.min.js')}}"></script>
        <script src="{{asset('assets/site/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/site/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/site/js/typeahead.bundle.js')}}"></script>
        <script src="{{asset('assets/site/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('assets/site/js/bootstrap-timepicker.min.js')}}"></script>
        <script src="{{asset('assets/site/js/Autocomplete.js')}}"></script>
        <script src="{{asset('assets/site/js/DateHelper.js')}}"></script>
        <script src="{{asset('assets/site/js/Print.js')}}"></script>
        <script src="{{asset('assets/site/js/SelectListChange.js')}}"></script>
        <script src="{{asset('assets/site/js/TabLoader.js')}}"></script>
        <script src="{{asset('assets/site/js/jquery.numeric.js')}}"></script>
        <script src="{{asset('assets/site/js/jquery-birthday-picker.min.js')}}"></script>
        <script src="{{asset('assets/site/js/validator.min.js')}}"></script>
        <script src="{{asset('assets/site/js/moment.min.js')}}"></script>
        <script src="{{asset('assets/site/js/bootstrap-multiselect.js')}}"></script>
        <script src="{{asset('assets/site/js/bootstrap-datetimepicker.min.js')}}"></script>
        <script src="{{asset('assets/site/js/fullcalendar.min.js')}}"></script>
        <script src="{{asset('assets/site/js/jquery.print.js')}}"></script>
        <script src="{{asset('assets/site/js/sweetalert.min.js') }}"></script>
        <script src="{{asset('assets/site/js/jquery.colorbox.js')}}"></script>
        <script src="{{asset('assets/site/alertify/lib/alertify.min.js')}}"></script>
        <script src="{{asset('assets/site/alertify/lib/alertify.js')}}"></script>
        <script src="{{asset('assets/site/keyboard/js/jquery.keyboard.js')}}"></script>
        <script src="{{asset('assets/site/keyboard/js/jquery.keyboard.extension-all.min.js')}}"></script> -->
      @yield('scripts')
    </body>
</html>
