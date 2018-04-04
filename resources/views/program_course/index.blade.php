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
				{{ Lang::get('program_course.program_course_list') }}
				<div class="pull-right" style="margin-right: 10px !important">
					<a href="{{{ URL::to('program_course/create') }}}" class="btn btn-sm  btn-primary">
						<span class="glyphicon glyphicon-plus-sign"></span>  
							{{ Lang::get('program_course.new_program_course') }}
					</a>
				</div>
			</h3>
		</div>
		<div class="col-md-12">
			<div class="form-group col-md-6">
				<label class="control-label col-md-3">Filter by Program</label>
				<div class="col-md-5">
					<select class="form-control" name="program_id" id="program_id">
						<option type="text" name="0" id="" value=""></option>
						@foreach($program_list as $program)
							<option class="form-control" id="program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label class="control-label col-md-3">Filter by Class</label>
				<div class="col-md-5">
					<select class="form-control" name="class_id" id="class_id">
						<option type="text" name="0" id="" value=""></option>
						@foreach($class_list as $class)
							<option class="form-control" id="class_id" value="{{ $class -> id }}">{{ $class -> class_name }}</option>
						@endforeach
					</select>
				</div>
			</div>   
		</div>

		<table id="programcourseTable" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ Lang::get('program.program_name') }}</th>
					<th>{{ Lang::get('class.class_name') }}</th>
					<th>{{ Lang::get('course_capacity.course_capacity') }}</th>
					<th>{{ Lang::get('course.subject') }}</th>
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
		var programcourse;
		programcourse = $('#programcourseTable').dataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "pageLength": 50,
                "sAjaxSource": "{{ URL::to('program_course/data') }}",
                "fnDrawCallback": function ( oSettings ) {
                },
                "fnServerParams": function(aoData){
                	aoData.push(
                    { "name":"program_id", "value": $("#program_id").val() },
                    { "name":"class_id", "value": $("#class_id").val() }
                	);
            	}
            });
		$("#program_id").change(function(){
        	programcourse.fnDraw();
     	});
		$("#class_id").change(function(){
         programcourse.fnDraw();
      });
	});
</script>
@endsection