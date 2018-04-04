@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.student_list") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
          @include('admin_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
  <div class="row">
		<div class="page-header"><br>
		  <h2>AR Report</h2>
		</div>
    <div class="form-horizontal">
      <div class="col-md-9">
        <div class="form-group {{{ $errors->has('date') ? 'has-error' : '' }}}">
          <label class="col-md-3 control-label" for="date">Date</label>
          <div class="col-md-9">
            <div class="input-daterange input-group" id="datepicker">
              <input type="text" id="date_start" class="form-control" name="start" value="" />
                <span class="input-group-addon">to</span>
                <input type="text" id="date_end" class="form-control" name="end" value="" />             
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <button type="button" class="btn btn-sm btn-success" id="loadCollection">
          <span class="glyphicon glyphicon-ok-circle"></span> Filter
        </button>
      </div><br/><br/><br/><br/>
      <div class="col-md-12" id="no_data" style="background-color: #eeeeee;" align="center"><br>No data available<br/><br/></div>
      <div class="col-md-12">
        <div class="pull-right" id="printBTN">
          <div class="col-md-7">
            <a data-url="{{ URL::to('accounting_report/pdf_payment_report') }}" id="pdfAR"  href="javascript:callReport('pdfAR');">
              <button type="button" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-print"></span> VIEW AS PDF FOR PRINTING
              </button>
            </a>
          </div>
         <!--  <div class="col-md-5">
            <a data-url="{{ URL::to('accounting_report/xls_payment_report') }}" id="xlsAR"  href="javascript:callReport('xlsAR');">
              <button type="button" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-download-alt"></span> Download Excel
              </button>
            </a>
          </div> -->
        </div>
      </div>
      <div id="CollectionContainer" class="col-md-12"></div>
	</div>
</div>
@stop
@section('scripts')
<script>

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

    $("#printBTN").hide();
    $("#no_data").show();

    $("#loadCollection").click(function()
    {
        LoadCollection($("#date_start").val(), $("#date_end").val());
        $("#printBTN").show();
        $("#no_data").hide();
    });

    function LoadCollection(DateStart, DateEnd)
    {
      $.ajax(
          {
            url: "{{{ URL::to('accounting_report/collection_report_detail') }}}",
            data: { 
              'date_start': DateStart, 
              'date_end': DateEnd,
              'is_ar': 1,
            },
          }
        ).done(function(cashier_detail_html){
          $("#CollectionContainer").html(cashier_detail_html);
        });

    }

    function callReport(reportId)
    {
      var url = $("#"+reportId).data('url');
      var date_start  = $("#date_start").val(); 
      var date_end = $("#date_end").val();

      url = url +"?date_start="+date_start+"&date_end="+date_end;
      
      window.open(url);
    }
</script>
@stop