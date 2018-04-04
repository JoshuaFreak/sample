@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("register_lang.import_student_score") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('payroll_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="page-header"><br>
            <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
                {{{ Lang::get("payroll.payroll") }}}
                <div class="pull-right" style="margin-right: 10px !important">
                    <!-- <a href="{{{ URL::to('register/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('register_lang.register_person') }}</a> -->
                </div>
            </h3>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
</script>
@stop
