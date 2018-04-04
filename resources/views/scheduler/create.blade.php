@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.schedules") }}} :: @parent
@stop

{{-- Content --}}
@section('content')

<div id="full-page-wrapper">
    <div class="row">
    	 @include('notifications')
<div class="page-header">
	<h3> {{{ Lang::get("scheduler.create_scheduler") }}}
		<div class="pull-right">
			<div class="pull-right">
	            <a href="{{{ URL::to('scheduler') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("scheduler.scheduler_list") }}</a>
	        </div>
		</div>
	</h3>
</div>
<form class="form-horizontal" method="post" action="{{ URL::to('scheduler/create') }}" autocomplete="off">
	@include('scheduler.form')
<!-- 	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-3 control-label" for="actions">&nbsp;</label>
			<div class="col-md-9">	
	 			<button type="submit" class="btn btn-sm btn-success">
	                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
	            </button>	
	            <button type="reset" class="btn btn-sm btn-default">
	                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
	            </button>	
	 			<a href="{{{ URL::to('scheduler') }}}" class="btn btn-sm btn-warning close_popup">
	                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
	       		</a>
	        </div>
	    </div>
	</div>	 -->
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
