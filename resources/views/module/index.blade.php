@extends('site.layouts.default_module_page')

{{-- Web site Title --}}
@section('title')
Modules :: @parent
@stop
{{-- Content --}}
@section('content')  
    <style type="text/css">
      .container{
        display: block;
        margin-left: auto;
        margin-right: auto;
      }
      .img img{
        width:100px; 
        height:100px;
      }
      .info img{
        width:150px; 
        height:150px;ò
      }

     @media screen and (min-device-width: 1000px) and (max-device-width: 1189px) { 
            #slider {
               height: 380px !important;
               width: 90% !important;
            }
            .slider_image
            {
               background-size: 90% !important;
            }
      }

      @media screen and (min-device-width: 1190px) and (max-device-width: 1350px) { 
            #slider {
               height: 400px !important;
               width: 90% !important;
            }
            .slider_image
            {
               background-size: 90% !important;
            }
      }
      @media screen and (min-device-width: 1351px) and (max-device-width: 1400px) { 
            #slider {
               height: 450px !important;
               width: 90% !important;
            }
            .slider_image
            {
               background-size: 90% !important;
            }
      }
      @media screen and (min-device-width: 1401px) and (max-device-width: 1690px) { 
            #slider {
               height: 500px !important;
               width: 90% !important;
            }
            .slider_image
            {
               background-size: 90% !important;
            }
      }




    </style>
      <link rel="stylesheet" type="text/css" href="{{asset('assets/site/new_slide/css/demo.css')}}" />
      <link rel="stylesheet" type="text/css" href="{{asset('assets/site/new_slide/css/style.css')}}" />
      <link rel="stylesheet" type="text/css" href="{{asset('assets/site/new_slide/css/custom.css')}}" />
      <script type="text/javascript" src="{{asset('assets/site/new_slide/js/modernizr.custom.79639.js')}}"></script>
      <noscript>
         <link rel="stylesheet" type="text/css" href="{{asset('assets/site/new_slide/css/styleNoJS.css')}}" />
      </noscript>
    <!-- <div class="container" id="rowcenter" style="height:540px; margin: 0 auto;"> -->
    <div class="container" id="rowcenter" style="margin: 0 auto;background-color: #E6E6E6">

          <h1 style = "color: red;"><b> Welcome to CIA </b></h1> 
          <p>Please select a module to begin</p>


          <div id="slider" style="margin-bottom: -10px;" class="sl-slider-wrapper">
               <div class="sl-slider">
                  <div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                     <div class="sl-slide-inner slider_image" style="background-image: url('assets/site/images/top_pic_2.jpg') !important;background: no-repeat;">
                        <div class="bg-img bg-img-1"></div>
                        <h1 style="color: #fff;margin-left: -120px !important">Make Your Story With CIA</h1>
                        <!-- <blockquote><p>Description</cite></blockquote> -->
                     </div>
                  </div>
                  <div class="sl-slide" data-orientation="vertical" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5" data-slice2-scale="1.5">
                     <div class="sl-slide-inner slider_image" style="background-image: url('assets/site/images/top_pic_3.png') !important;background: no-repeat;">
                        <div class="bg-img bg-img-2"></div>
                        <h1 style="color: #fff;margin-left: -120px !important">Make Your Story With CIA</h1>
                        <!-- <blockquote><p>Description</cite></blockquote> -->
                     </div>
                  </div>
               </div>
               <nav id="nav-dots" class="nav-dots" style="background-color: rgba(66, 7, 7, 0.91);">
                  <span class="nav-dot-current"></span>
                  <span></span>
               </nav>
          </div>

          <div class="container" align="center">
          <br/>

            <div class="col-sm-12">
               <!-- <div class="col-sm-1">
               </div> -->
               {{--*/ $count = 0 /*--}}
               @foreach($app_arr as $app)
                  {{--*/ $count++ /*--}}
                  <div class="col-sm-2">
                  <!-- <div class="col-sm-4"> -->
                     <div class="ih-item circle effect5">
                        <a href="{{{ URL::to($app['AppURL']) }}}">
                           <div class="img" ><img src="{{{ asset($app['img']) }}}" alt="img"></div>
                           <div class="info">
                             <p>{{ $app['AppDesc'] }}</p>
                           </div>

                           <p>{{$app["AppName"]}}</p>
                        </a>
                     </div>
                  </div>

               @endforeach
            </div>
        </div>


       <center>
          <div class="">
          </div>

          <br>
          <br>
       </center>
    </div>
@stop
@section('scripts')

      <script type="text/javascript" src="{{asset('assets/site/new_slide/js/jquery.ba-cond.min.js')}}"></script>
      <script type="text/javascript" src="{{asset('assets/site/new_slide/js/jquery.slitslider.js')}}"></script>
      <script type="text/javascript">  
         $(function() {
         
            var Page = (function() {

               var $nav = $( '#nav-dots > span' ),
                  slitslider = $( '#slider' ).slitslider( {
                     onBeforeChange : function( slide, pos ) {

                        $nav.removeClass( 'nav-dot-current' );
                        $nav.eq( pos ).addClass( 'nav-dot-current' );

                     }
                  } ),

                  init = function() {

                     initEvents();
                     
                  },
                  initEvents = function() {

                     $nav.each( function( i ) {
                     
                        $( this ).on( 'click', function( event ) {
                           
                           var $dot = $( this );
                           
                           if( !slitslider.isActive() ) {

                              $nav.removeClass( 'nav-dot-current' );
                              $dot.addClass( 'nav-dot-current' );
                           
                           }
                           
                           slitslider.jump( i + 1 );
                           return false;
                        
                        } );
                        
                     } );

                  };

                  return { init : init };

            })();

            Page.init();

            /**
             * Notes: 
             * 
             * example how to add items:
             */

            /*
            
            var $items  = $('<div class="sl-slide sl-slide-color-2" data-orientation="horizontal" data-slice1-rotation="-5" data-slice2-rotation="10" data-slice1-scale="2" data-slice2-scale="1"><div class="sl-slide-inner bg-1"><div class="sl-deco" data-icon="t"></div><h2>some text</h2><blockquote><p>bla bla</p><cite>Margi Clarke</cite></blockquote></div></div>');
            
            // call the plugin's add method
            ss.add($items);

            */
         
         });
      </script>
@stop
