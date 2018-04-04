@extends('site.layouts.default_portal')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("students_portal.modify_account") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
      @include('student_sidebar')
    </ul>
  </div>

</div>
<div id="page-wrapper">
	 <div class="row">
	 	@include('notifications')
	    <div class="page-header">
	      <h2>{{{ Lang::get("students_portal.edit_password") }}}</h2>
	    </div>
	    <form class="form-horizontal" method="post" action="{{ URL::to('students_portal/'. $student->id .'/edit') }}">
	      @include('students_portal/my_profile.form_edit')
      	<div class="col-xs-12">
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<button type="submit" class="btn btn-sm btn-success">
			   		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save") }}
				</button>
			</div>
		</div>	
	    </form>
	  </div>
</div>	
@stop
