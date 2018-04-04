@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.schedule") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
            @include('scheduler_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
      <div class="row">
            <div class="page-header"><br>
              <h3 style="margin-top: -12px !important;margin-left: 20px !important;">
                {{{ Lang::get("scheduler.scheduler_dashboard") }}}
              </h3>
            </div>
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <div class="form-group col-md-12" style="width: 100%;height: 500px;background-color: #F8F8F8;padding-top: 20px;">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="glyphicon-content glyphicon glyphicon-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>Daily Schedules</div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{URL::to('scheduler/report/daily_schedules')}}">
                                <div class="panel-footer">
                                    <span class="pull-left">{{ Lang::get("hrms.download") }}</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="glyphicon-content glyphicon glyphicon-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>Course Students</div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{URL::to('scheduler/course_student')}}">
                                <div class="panel-footer">
                                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
            </div>
      </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

   
</script>
@stop