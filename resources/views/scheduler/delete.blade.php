@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.delete_scheduler") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<style>
h3{
    color:#008cba;
}
</style>

<div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">    
          <ul class="nav" id="side-menu"> 
          @include('scheduler_sidebar')
        </ul>
      </div>

</div>
<div id="page-wrapper">
    <div class="row">
<div class="page-header">
    <h3> {{{ Lang::get("scheduler.delete_scheduler") }}}  
        <div class="pull-right">
            <div class="pull-right">
                <a href="{{{ URL::to('scheduler') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("scheduler.scheduler_list") }}</a>
            </div>
        </div>
    </h3>
</div>
<div>
    {{ Lang::get("form.delete_message") }}
</div>
<form class="form-horizontal" method="post" action="{{ URL::to('' . $scheduler->id . '/delete') }}" autocomplete="off">
    <input type="hidden" name="id" value="{{ $scheduler->id }}" />   
    @include('scheduler.form')
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-md-3 control-label" for="actions">&nbsp;</label>
            <div class="col-md-9">  
                <a href="{{{ URL::to('scheduler') }}}" class="btn btn-sm btn-warning close_popup">
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
        $("#roles").select2()
    });
</script>
@stop
