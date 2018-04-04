@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("report.daily_schedules") }}} :: @parent
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
				{{{ Lang::get("report.daily_schedules") }}}
			</h3>
		</div>

	</div> <!-- row end -->

	<div class="col-md-12">
			<div class="col-md-1">
				<label class="control-label">Date: </label>
			</div>
			<div class="col-md-3">
				<input type="date" id="date" value="{{ date('Y-m-d') }}" class="form-control"/>
			</div>
			<div class="col-md-2">
				<a class="btn btn-primary" data-url="{{ URL::to('scheduler/report/daily_schedules_excel') }}" id="report_excel"  href="javascript:callReport('report_excel');">Generate Excel Report</a>
			</div>
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
 		var date  = $("#date").val(); 
 	
 		url = url +"?date="+date;
 		
 		window.open(url);
 	}


	</script>

@stop