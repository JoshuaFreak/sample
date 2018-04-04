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
		  <h2>Student Contacts Report</h2>
		</div>
    
    <form>
      <div class="form-horizontal">
          <div class="col-md-12">
              <label class="control-label col-md-1">Filter by:</label>
              <div class="col-md-3">
                  <select class="form-control" name="classification_level_id" id="classification_level_id" required>
                    @foreach($classification_level as $class_level)
                      <option value="{{$class_level->id}}">{{$class_level->level}}</option>
                    @endforeach
                  </select>
              </div> 
              <div class="col-md-3">
                  <select class="form-control" name="section_id" id="section_id" required>
                    <option></option>
                  </select>
              </div>  
              <div class="col-md-2">
                  <button type="submit" class="btn btn-danger" id="filter">Filter</button>
              </div>          
          </div>
      </div>
    </form>
    <br><br><br><br>  
    <table id="table" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>School ID Number</th>
          <th>Name</th>
          <th>Address</th>
          <th>Contact/ Home/ Student Mobile Number</th>
          <th>Email Address</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>
@stop
@section('scripts')
<script>
    var oTable;
    $(document).ready(function(){
      oTable = $('#table').dataTable( {
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "sPaginationType": "bootstrap",
        "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "{{ URL::to('admin/reports/student_contacts/data') }}",
            "fnDrawCallback": function ( oSettings ) {
            }
      });
    })

    $('#classification_level_id').change(function(){
      selectListChange('section_id', '{{URL::to("admin/reports/student_contacts/dataJson")}}', {'classification_level_id':$(this).val()}, '')
    })

    $('form').submit(function(e){
      e.preventDefault()
      var section_id = $('#section_id').val();
      $('#table').dataTable().fnReloadAjax('{{URL::to("admin/reports/student_contacts/data?section_id=")}}'+ section_id)
    });

    
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