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
            @include('hrms_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
  <div class="row">
    <div class="page-header"><br>
      <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
        {{{ Lang::get("hrms.hrms_dashboard") }}}
      </h3>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="glyphicon-content glyphicon glyphicon-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"></div>
                        <div>Employee List</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('hrms/report/employee_list')}}">
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
                        <div class="huge">{{ $employee_count }}</div>
                        <div>Employee Count</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('employee/employee_type?id=0&filter=')}}">
                <div class="panel-footer">
                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                    <span class="pull-right"><i class="fa fa-user"></i></span>
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
                        <div class="huge">{{ $teacher_count }}</div>
                        <div>Teacher Count</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('teacher')}}">
                <div class="panel-footer">
                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                    <span class="pull-right"><i class="fa fa-user"></i></span>
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
                        <div class="huge">{{ $international_count }}</div>
                        <div>International Count</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('employee/employee_type?id=1&filter=')}}">
                <div class="panel-footer">
                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                    <span class="pull-right"><i class="fa fa-user"></i></span>
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
                        <div class="huge">{{ $admin_count }}</div>
                        <div>Admin Count</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('employee/employee_type?id=2&filter=')}}">
                <div class="panel-footer">
                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                    <span class="pull-right"><i class="fa fa-user"></i></span>
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
                        <div class="huge">{{ $acad_lead_count }}</div>
                        <div>Acad Leaders Count</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('employee/employee_type?id=3&filter=al')}}">
                <div class="panel-footer">
                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                    <span class="pull-right"><i class="fa fa-user"></i></span>
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
                        <div class="huge">{{ $acad_sup_count }}</div>
                        <div>Acad Support Count</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('employee/employee_type?id=3&filter=as')}}">
                <div class="panel-footer">
                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                    <span class="pull-right"><i class="fa fa-user"></i></span>
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
                        <div class="huge">{{ $resigned_count }}</div>
                        <div>Resigned Count</div>
                    </div>
                </div>
            </div>
            <a href="{{URL::to('employee/employee_type?id=0&filter=resigned_employee')}}">
                <div class="panel-footer">
                    <span class="pull-left">{{ Lang::get("hrms.see_more") }}</span>
                    <span class="pull-right"><i class="fa fa-user"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
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