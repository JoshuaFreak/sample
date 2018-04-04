@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ $title }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<link href="{{asset('assets/site/css/glyphicon.design.css')}}" rel="stylesheet">
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('admin_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row"><br>
        <div class="page-header">
          <h2>Admin Dashboard</h2>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon-content glyphicon glyphicon-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{$gen_user_count}}</div>
                                <div>{{ Lang::get("gen_user.users") }}!</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('gen_user')}}">
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
                                <i class="glyphicon-content glyphicon glyphicon-tasks fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{$gen_role_count}}</div>
                                <div>{{ Lang::get("gen_role.roles") }}!</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('gen_role')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>

           <!--   <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon-content glyphicon glyphicon-tasks fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>Users List</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('admin_report/user_list')}}">
                        <div class="panel-footer">
                            <span class="pull-left">{{ Lang::get("admin.view_detail") }}</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div> -->

   <!--           <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon-content glyphicon glyphicon-tasks fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>Students Master List</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{URL::to('admin_report/students_master_list')}}">
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


