@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("program.program_list") }}} :: @parent
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
			<h2>
				{{{ Lang::get("registrar.add_examination") }}}
				<!-- <div class="pull-right">
					<a href="{{{ URL::to('program/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('program.create_new_program') }}</a>
				</div> -->
			</h2>
		</div>
		<div class="form-group">
			<label class="control-label col-md-2">Search Student</label>
			<div class="col-md-5">
				<input type="text" id="studnent_name" class="form-control"/>
			</div>
		</div>
	</div>
</div>
@stop
    
{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {

		});
	</script>
@stop