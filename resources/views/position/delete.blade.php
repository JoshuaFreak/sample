@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("position.edit_position") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
      @include('hrms_sidebar')
    </ul>
  </div>

</div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;"> {{{ Lang::get("position.delete_position") }}}
				<div class="pull-right" style="margin-right: 10px !important">
			            <a href="{{{ URL::to('position') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("position.position_list") }}</a>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('position/' . $position->id . '/delete') }}" autocomplete="off">
			<input type="hidden" name="id" value="{{ $position->id }}" />
			@include('position.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">
			 			<a href="{{{ URL::to('position') }}}" class="btn btn-sm btn-warning close_popup">
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
