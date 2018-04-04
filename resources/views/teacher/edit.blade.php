@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("teacher.edit_teacher") }}} :: @parent
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
    	@include('notifications')
		<div class="page-header"><br>
			<h3> {{{ Lang::get("teacher.edit_teacher") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('teacher') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("teacher.teacher_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('teacher/' . $teacher->id . '/edit') }}" autocomplete="off">
			<input type="hidden" name="id" value="{{ $teacher->id }}" />
			@include('teacher.form_edit')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-primary">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('teacher') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
    </div>
</div>	
@stop

{{-- Scripts --}}
@section('scripts')
	<script>

	$(function() {
		$("#degree_id").select2();
		$("#program_id").select2();

	</script>

@stop

