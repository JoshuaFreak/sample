@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ $title }}} :: @parent
@stop
{{-- Content --}}
@section('content')
@section('styles')
    <style>
        .panel-footer {
            color: inherit;
            background-color: #f5f5f5;
        }

        .huge {
            font-size: 30px;
        }
    </style>
@endsection
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
          <h2>Reports</h2>
        </div>
        <div class="col-lg-5">
            <div class="row">
                <div class="col-lg-12 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div class="huge">Collection</div>
                                    <div>Report</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::to('admin/reports/collection')}}" target="_blank">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div class="huge">Official Receipt</div>
                                    <div>Report</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::to('admin/reports/or')}}" target="_blank">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div class="huge">Acknowledgement Receipt</div>
                                    <div>Report</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::to('admin/reports/ar')}}" target="_blank">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div class="huge">Student Contact</div>
                                    <div>Report</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::to('admin/reports/student_contacts')}}" target="_blank">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div class="huge">Student Population</div>
                                    <div>Report</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::to('admin/reports/student_population')}}" target="_blank">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div class="huge">Student Population By Gender</div>
                                    <div>Report</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::to('admin/reports/student_population_gender')}}" target="_blank">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


