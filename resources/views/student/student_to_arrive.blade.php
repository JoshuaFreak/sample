@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.student") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<style type="text/css">
  tbody > tr > td 
  {border: 1px solid #fff;}
</style>

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
                @include('student_sidebar')
        </ul>
      </div>
    </div>

<div id="page-wrapper">
    <div class="row">
        <div class="page-header">
            <br/>
        		<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
        			{{{ Lang::get("student.student_to_arrive") }}}

        		</h2>
        </div>
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      	<input type="hidden" name="date" id="date" value="{{{ date('Y-m-d') }}}" />
        <div class="col-md-12 form-group">
            <!-- <button class="btn btn-primary" type="button">Print List</button> -->
        </div>
      	<div class="col-md-12 form-group">
            <table class="table" style="width: 100%; ">
              <th>Student Id</th>
              <th>Name</th>
              <th>Arrival Date</th>
              <tbody id="student_name"></tbody>
            </table>
        </div>
    </div>
</div>

@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

$(function(){

  studentJsonArray = [];
  $.ajax({
      url:'{{ URL::to("http://cebucia.com/api/student_arrive.php") }}',
      type:'GET',
      data: {
          "_token": "L3tM3P@55!",
          // "date": $("#date").val(),
          // "check_in_date": $("#check_in_date").val(),
          // "check_out_date": $("#check_out_date").val(),
      },
      dataType: "json",
      async:false,
      success: function (data) 
      {                  
          studentJsonArray = data.data;
      }  

  });


  var date_now = new Date();
  var year = date_now.getFullYear();
  var month = date_now.getMonth()+1;
  var day = date_now.getDate();

  day_length = day.toString().length;
  month_length = month.toString().length;

  if(month_length == 1)
  {
      month = '0'+month;
  }

  if(day_length == 1)
  {
      day = '0'+day;
  }

  date_now = year+'-'+month+'-'+day;


  $(jQuery.parseJSON(JSON.stringify(studentJsonArray))).each(function() {  

      var student_id = this.student_id;
      var name = this.sename;
      var abrod_date = this.abrod_date;

      if(abrod_date == date_now)
      {
          color = "#A7F9D5";
          font_color = "#000";
      }
      else
      {
          color = "#F7EEA0";
          font_color = "#000";
      }

      $("#student_name").append('<tr style="background-color:'+color+';color:'+font_color+';">'
            +'<td>'+student_id+'</td>'
            +'<td>'+name+'</td>'
            +'<td>'+abrod_date+'</td>'
      +'</tr>');
  });

});

</script>
@stop
