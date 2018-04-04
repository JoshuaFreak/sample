@extends('site.layouts.default')

{{-- Title --}}
@section('title')
Course
@endsection

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
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{ Lang::get('course.new_course') }}
				<div class="pull-right" style="margin-right: 10px !important">
					<a href="{{{ URL::to('course/') }}}" class="btn btn-sm  btn-primary">
						<span class="glyphicon glyphicon-plus-sign"></span>  
						{{ Lang::get('course.course_list') }}
					</a>
				</div>
			</h2>
		</div>

		<form class="form-horizontal" method="post" action="{{ URL::to('course/create') }}" autocomplete="off">
			@include('course.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
						<button type="submit" class="btn btn-sm btn-success">
							<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
						</button>	
						<button type="reset" class="btn btn-sm btn-default">
							<span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
						</button>	
						<a href="{{{ URL::to('course/') }}}" class="btn btn-sm btn-warning close_popup">
							<span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
						</a>
					</div>
				</div>
			</div>	
		</form>
	</div>
</div>
@endsection