@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("subject.edit_subject") }}} :: @parent
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
			<h3> {{{ Lang::get("subject.edit_subject") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('subject') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("subject.subject_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('subject/' . $subject->id . '/edit') }}" autocomplete="off">
			<input type="hidden" name="id" value="{{ $subject->id }}" />
			@include('subject.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('subject') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
    </div>
</div>	
@stop
