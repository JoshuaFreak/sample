@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.scheduler") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<style>

    .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
        border: 1px solid #D0C798;
        background: #EEEDDF url("images/ui-bg_highlight-soft_75_ffe45c_1x100.png") 50% top repeat-x;
        color: #363636;
    }
    .ui-widget-header {
        border: 1px solid #fff;
        background: #3B84D1 url("images/ui-bg_gloss-wave_35_f6a828_500x100.png") 50% 50% repeat-x;
        color: #fff;
        font-weight: bold;
    }
   .ui-widget-content {
        border: 1px solid #E0DED1;
        background: #FFFFFF url("images/ui-bg_highlight-soft_100_eeeeee_1x100.png") 50% top repeat-x;
        color: #333;
    }
    .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {
    border: 1px solid #F0CA9E;
        background: #fff url("images/ui-bg_glass_65_ffffff_1x400.png") 50% 50% repeat-x;
        font-weight: bold;
        color: #FFAD0B;
    }

    .fc-unthemed .fc-today {
    background: #fcf8e3;
    }

</style>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{!!URL::to('site/dashboard')!!}">Dashboard</a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
                <div class="scheduler">
                    <!-- <span class="hello"> {{ Lang::get('site/site.welcome') }}!</span> -->
                </div> </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{{ URL::to('/') }}}"><i class="glyphicon glyphicon-home"></i> {{ Lang::get('admin/admin.homepage') }}</a>
                    </li>
                    <li>
                        <a href="{{{ URL::to('auth/logout') }}}"><i class="glyphicon glyphicon-off"></i> {{ Lang::get('site/site.logout') }}</a>
                    </li>
                </ul>
            </li>
        </ul>

</nav>
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
        <div class="page-header">
          <h2>{{ Lang::get("scheduler.dashboard")}}</h2>
        </div>
<!-- Table Section -->
        
      <!--   <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon glyphicon-list-alt fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{ Lang::get("scheduler.schedules") }}!</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('schedules')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("scheduler.view_details") }}</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div> -->

       {!! $calendar->calendar() !!}
      <!--   <div id='calendar'></div> -->
    </div>
</div>
@stop

@section('scripts')

{!! $calendar->script() !!}
<script>


    // $(document).ready(function() {
        

    // code using native javascript
    //     $('#calendar').fullCalendar({
    //         header: {
    //             left: 'prev,next today',
    //             center: 'title',
    //             right: 'month,agendaWeek,agendaDay'
    //         },
    //         defaultDate: '2015-07-1',
    //         selectable: true,
    //         selectHelper: true,
    //         select: function(start, end) {
    //             var title = prompt('Event Title:');
    //             var eventData;
    //             if (title) {
    //                 eventData = {
    //                     title: title,
    //                     start: start,
    //                     end: end
    //                 };
    //                 $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
    //             }
    //             $('#calendar').fullCalendar('unselect');
    //         },
    //         editable: true,
    //         eventLimit: true, // allow "more" link when too many events
    //         events: [
    //             {
    //                 title: 'All Day Event',
    //                 start: '2015-07-11',
    //                 color: 'lightblue',   
    //                 textColor: 'black' 
    //             },
    //         ]
    //     });
        
    // });
    // basic code for displaying calendar
    // $(document).ready(function() {

    //     // page is now ready, initialize the calendar...
    //     $('#calendar').fullCalendar({
    //         defaultDate: '2014-09-12',
    //         editable: true,
    //         eventLimit: true, // allow "more" link when too many events
    //     });
    // });
</script>
@stop