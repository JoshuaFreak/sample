@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.register_student") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<style type="text/css">
    .inner-addon { 
    position: relative; 
    }

    /* style icon */
    .inner-addon .glyphicon {
      position: absolute;
      padding-left: 10px;
      pointer-events: none;
      color: white;
    }

    .inner-addon .btn-danger {
      padding-left: 28px;
    }
</style>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
            @include('registrar_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="page-header"><br>
            <h3> {{{ Lang::get("registrar.delete_register_student") }}}  
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('registrar/register_student') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("registrar.registered_list") }}</a>
                    </div>
                </div>
            </h3>
        </div>
        <div>
            {{ Lang::get("registrar.delete_message") }}
        </div>
        <form class="form-horizontal" method="post" action="{{ URL::to('registrar/register_student/' . $student_curriculum->id . '/delete') }}" autocomplete="off">
            <input type="hidden" name="id" value="{{ $student_curriculum->id }}" />   
            @include('registrar/register_student.form')

         <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <a href="{{{ URL::to('registrar/register_student') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                        <a class="inner-addon left-addon">
                            <span class="glyphicon glyphicon-trash"></span> 
                            <input type="submit" class="btn btn-sm btn-danger" value="{{ Lang::get("form.delete") }}" />
                        </a>
                    </div>
                </div>
            </div>  
        </form>>
    </div>
</div>  
@stop
@section('scripts')
<script type="text/javascript">
    $(function() {
        $("#roles").select2()
    });
</script>
@stop