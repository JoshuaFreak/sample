<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>
            @section('title')
            Gakkō System
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

        <!-- CSS
        ================================================== -->
        <link rel="stylesheet" href="{{asset('assets/site/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/site/css/bootstrap-theme.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/site/css/half-slider.css')}}">
        <link rel="stylesheet" href="{{asset('assets/site/css/justifiedGallery.min.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/site/css/lightbox.min.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/site/css/home.css')}}"/>
        <!-- Javascripts
        ================================================== -->
        <script src="{{asset('assets/site/js/jquery-1.11.1.min.js')}}"></script>
        <script src="{{asset('assets/site/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/site/js/jquery.justifiedGallery.min.js')}}"></script>
        <script src="{{asset('assets/site/js/lightbox.min.js')}}"></script>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <style>
        body {
            padding: 60px 0;
            background:url({{{ asset('assets/site/images/bg.jpg') }}});
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size: 100%;
        }
        footer{
          position:fixed;
          left:0px;
          bottom:0px;
          height:80px;
          width:100%;
          color: #FFFFFF;
          background-color:#098FF6;
          text-align: left;
        }
        </style>
        <!-- <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> -->
        <link rel="shortcut icon" href="{{{ asset('assets/site/ico/favicon.png') }}}">
    </head>

<body>
    <div id="wrap">
        <!-- <nav class="navbar navbar-default navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li><p class="navbar-brand"><a href="{{{ URL::to('/') }}}"><img src="../images/logo.png" class="logo"></a></p></li>
                    </ul>
                </div>
            </div>
        </nav> -->
        @yield('carousel')
        <div class="container">
            @include('notifications')
            @yield('content')
            @yield('galeries')
        </div>
        <div id="push"></div>
    </div>
    @yield('scripts')

 <!--    <footer>
        <div class="container">
            <h5>Powered by: <img src="{{{ asset('assets/site/images/itechrar white logo.png') }}}" style="width:150px; height:50px;"></h5>
        </div>
    </footer> -->
</body>
</html>