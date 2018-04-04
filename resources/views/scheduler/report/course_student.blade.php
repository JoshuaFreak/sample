@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("report.course_student") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
           
<!-- Side Bar -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    @include('scheduler_sidebar')                                
                </ul>
            </div>
        </div>
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
		<div class="page-header">
			</br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{{ Lang::get("report.course_student") }}}
			</h3>
		</div>
		@foreach($program_list as $program)
			<div class="form-group" style="float: left; margin-left: 10px;">
				<button type="button" data-id="{{ $program-> id}}" data-course_name="{{$program -> program_name}}" class="program btn btn-xs btn-info" style="color:#000 !important;">{{$program -> program_name}}</button>
			</div>
		@endforeach
		<div class="col-md-12">
			<hr/>
		</div>
		<div class="col-md-12">
			<h3 id="course_name"></h3>
		</div>
		<!-- Table Section -->
		    <table id="StudentFilter" class="table table-striped table-hover">
		        <thead>
		            <tr>
		                    <th> {{ Lang::get("report.student_id") }}</th>
                            <th> {{ Lang::get("report.student_name") }}</th>
		                    <th> {{ Lang::get("report.nickname") }}</th>
		            </tr>
		        </thead>
		        <tbody>
		        </tbody>
		    </table>
	</div> <!-- row end -->

</div> <!-- page wrapper end -->

@stop

@section('scripts')

<script type="text/javascript">
	$(document).ready(function() {

		StudentFilter = $('#StudentFilter').DataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "{{ URL::to('scheduler/course_student/data?id=') }}"+0,
                "fnDrawCallback": function ( oSettings ) {
                },

                "fnServerParams": function(aoData){
                    // aoData.push(
                    //     { "name":"filter_data", "value": $("#filter_data").val() },
                    //     { "name":"filter", "value": $("#filter").val() }
                    // );
                }
            });

		$(".program").click(function(){

			id = $(this).data('id');
			course_name = $(this).data('course_name');
			StudentFilter.destroy();
			$("#course_name").text(course_name);

            StudentFilter = $('#StudentFilter').DataTable( {
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "bProcessing": true,
                "bServerSide": true,
                "bStateSave": true,
                "sAjaxSource": "{{ URL::to('scheduler/course_student/data?id=') }}"+id,
                "fnDrawCallback": function ( oSettings ) {
                },

                "fnServerParams": function(aoData){
                    // aoData.push(
                    //     { "name":"filter_data", "value": $("#filter_data").val() },
                    //     { "name":"filter", "value": $("#filter").val() }
                    // );
                }
            });  

        });

	});
</script>

@stop