@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("report.employee_list_report") }}} :: @parent
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
                    @include('hrms_sidebar')                                
                </ul>
            </div>
        </div>
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
		<div class="page-header">
				</br>
			<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
				{{{ Lang::get("report.employee_list_report") }}}
			</h3>
		</div>

	<!-- new date range picker -->
<!-- 	
		<div class="col-md-6">
			<label class="control-label col-md-2" for="from">Date Range:</label>
			<div class="input-daterange input-group" id="datepicker">
				<input type="text" id="from" class="form-control" name="start" value="" />
			    <span class="input-group-addon">to</span>
			    <input type="text" id="to" class="form-control" name="end" value="" />
			</div>
		</div> -->

	</div> <!-- row end -->

	<div class="col-md-12 col-md-offset-1">
			<a class="btn btn-primary" data-url="{{ URL::to('hrms/report/employee_list_excel') }}" id="report_excel"  href="javascript:callReport('report_excel');">Generate Excel Report</a>
	</div>

</div> <!-- page wrapper end -->
@stop

@section('scripts')

<script type="text/javascript">
 	$(function() {
 		$('#datepicker').datepicker({
        format: "MM d, yyyy",
		orientation: "top left",
		autoclose: true,
		startView: 1,
		todayHighlight: true,
		todayBtn: "linked",
 		});
	});
 	
 	function callReport(reportId)
 	{
 		var url = $("#"+reportId).data('url');
 		// var date_from  = $("#from").val(); 
 		// var date_to = $("#to").val();

 		// url = url +"?date_from="+date_from+"&date_to="+date_to;
 		
 		window.open(url);
 	}


	</script>

@stop