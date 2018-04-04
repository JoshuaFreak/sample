@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("gen_user.delete_user") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
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
            <h3> {{{ Lang::get("gen_user.delete_user") }}}  
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('registrar/register_guardian') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("registrar.registered_guardian") }}</a>
                    </div>
                </div>
            </h3>
        </div>
        <div>
            {{ Lang::get("form.delete_message") }}
        </div>
        <form class="form-horizontal" method="post" action="{{ URL::to('registrar/register_guardian/' . $guardian->id . '/delete') }}" autocomplete="off">
           @include('registrar/register_guardian.form_delete')
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <a href="{{{ URL::to('registrar/register_guardian') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}
                        </button>   
                    </div>
                </div>
            </div>  
        </form>
    </div>
</div>  
@stop
@section('scripts')
<script type="text/javascript">
    $(function() {
        $("#student").select2()
    });
</script>
@stop
