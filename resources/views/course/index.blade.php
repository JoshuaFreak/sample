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
				{{ Lang::get('course.course_list') }}
				<div class="pull-right" style="margin-right: 10px !important">
					<a href="{{{ URL::to('course/create') }}}" class="btn btn-sm  btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>  {{ Lang::get('course.new_course') }}</a>
				</div>
			</h3>
		</div>
		<table id="courseTable" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ Lang::get('course.course_name') }}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		var course;
		course = $('#courseTable').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "{{ URL::to('course/data') }}",
                "fnDrawCallback": function ( oSettings ) {
                }
            });
	});
</script>
@endsection