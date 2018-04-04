@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("campus.edit_campus") }}} :: @parent
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
		<div class="page-header"><br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left"> {{{ Lang::get("campus.edit_campus") }}}
				<div class="pull-right" style="margin-right: 10px !important">
			            <a href="{{{ URL::to('campus') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("campus.campus_list") }}
			            </a>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('campus/' . $campus->id . '/edit') }}" autocomplete="off">
			<input type="hidden" name="id" value="{{ $campus->id }}" />
			@include('campus.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('campus') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
    </div>
</div>	
@stop
