@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.students_portal") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<link href="{{asset('assets/site/css/glyphicon.design.css')}}" rel="stylesheet">
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
            @include('student_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
  <div class="row"><br>
    <div class="page-header">
        <div class="well">
            <h2>Welcome {{{ $student->last_name }}}, {{{ $student->first_name }}} {{{ $student->middle_name }}} {{{ $student->suffix_name }}}!</h2>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon-content glyphicon glyphicon-credit-card fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{{ Lang::get("students_portal.ledger") }}}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('students_portal/'.Auth::user()->username.'/student_ledger')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
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
                                <i class="glyphicon-content glyphicon glyphicon-calendar fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{{ Lang::get("students_portal.schedule") }}}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('students_portal/'.Auth::user()->username.'/schedule')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
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
                                <i class="glyphicon-content glyphicon glyphicon-list-alt fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{{ Lang::get("students_portal.report_card") }}}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('students_portal/'.Auth::user()->username.'/grade')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
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
                                <i class="glyphicon-content glyphicon glyphicon-align-justify fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{ Lang::get("students_portal.curriculum") }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('students_portal/'.Auth::user()->username.'/curriculum')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
 <!--            <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon-content glyphicon glyphicon-align-justify fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{ Lang::get("students_portal.academic_projection") }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('students_portal/'.Auth::user()->username.'/academic_projection')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div> -->
<!--             <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon-content glyphicon glyphicon-download fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{ Lang::get("students_portal.document_management") }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('students_portal/'.Auth::user()->username.'/student_document')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div> -->
        </div>
  </div>
</div>
@stop