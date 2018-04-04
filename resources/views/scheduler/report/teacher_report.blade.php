@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("teacher.teacher") }}} :: @parent
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
        <div class="page-header">
            <br/>
            <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
              {{{ Lang::get("teacher.teacher_schedule_report") }}}

            </h2>
        </div>
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="col-md-4">
            <input class="typeahead form-control" type="text" name="employee_name" id="employee_name" value="" autofocus/>
          {!! $errors->first('student_id', '<label class="control-label" for="employee_name">:message</label>')!!}
        </div>
<!--        <div class="col-md-12">
             <div class="form-group {{{ $errors->has('student_id') ? 'has-error' : '' }}}">
                <label class="col-md-2 control-label" for="teacher_name">{!! Lang::get('teacher.teacher_search') !!}</label>
                <input type="hidden" name="student_id" id="student_id" value="0" />
                <div class="col-md-5">
                    <input class="typeahead form-control" type="text" name="teacher_name" id="teacher_name" value="" autofocus/>
                  {!! $errors->first('student_id', '<label class="control-label" for="teacher_name">:message</label>')!!}
                </div>
            </div>
        </div>
 -->
   <!--      <button class="btn btn-sm btn-primary pull-right" style="margin-right:50px;" onclick="PrintElem('#table')" type="button">Print
        </button> -->
        <div class="col-md-2">
          <input id="date" type="date" class="form-control" value="{{ date('Y-m-d') }}"></input>
        </div>
         <!--  <div class="col-md-2">
            <button id="load" class="btn btn-sm btn-primary" type="button">Load
            </button>
          </div> -->
        <button id="printPdf" class="btn btn-sm btn-default pull-right" style="margin-right:50px;" type="button">Print Schedule
        </button>
        <br>
        <br>
        <br>
        <br>
     <!--    <div id="table">
            <table class="table" style="width: 90%;margin-left: 50px;">
                <tbody>
                  <th>Subject</th>
                  <th>Student</th>
                  <th>Room</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                </tbody>
                <tbody id="schedule_con"></tbody>
            </table>
        </div> -->
    </div>
</div>
<br>
<br>
<br>
@stop

{{-- Scripts --}}
@section('scripts')
  <script type="text/javascript">

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
        var mywindow = window.open('', 'Schedule', 'height=700,width=1000');

        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</html><body>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }

    $(function() {

      $("#printPdf").click(function(){

          // var student_id = $("#student_id").val();
          var date = $("#date").val();
          var employee_id = $("#employee_name").data('employee_id');

          // location.href="student/studentSchedulePdf?student_id="+student_id+"&program_id="+program_id;
          window.open(
            "dataJson/teacherSchedulePdf?date="+date+"&employee_id="+employee_id,
            "_blank" // <- This is what makes it open in a new window.
          );
      });
    });

</script>
<script>
$(function(){

    var substringMatcher = function(strs) {
return function findMatches(q, cb) {
var matches, substringRegex;

// an array that will be populated with substring matches
matches = [];

// regex used to determine if a string contains the substring `q`
substrRegex = new RegExp(q, 'i');

// iterate through the pool of strings and for any string that
// contains the substring `q`, add it to the `matches` array
$.each(strs, function(i, str) {
  if (substrRegex.test(str)) {
    matches.push(str);
  }
});

cb(matches);
};
};

  var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
          var matches, substringRegex;
       
          // an array that will be populated with substring matches
          matches = [];
       
          // regex used to determine if a string contains the substring `q`
          substrRegex = new RegExp(q, 'i');
       
          // iterate through the pool of strings and for any string that
          // contains the substring `q`, add it to the `matches` array
          $.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
              matches.push(str);
            }
          });
       
          cb(matches);
        };
      };


/********
  START OF employee_name ->  typeahead
*************************************************************************/
  var employee_list = new Bloodhound({
          datumTokenizer: function (datum) {
              //return Bloodhound.tokenizers.whitespace(datum.employee_name);
                 var tokens = [];
                  //the available string is 'name' in your datum
                  var stringSize = datum.employee_name.length;
                  //multiple combinations for every available size
                  //(eg. dog = d, o, g, do, og, dog)
                  for (var size = 1; size <= stringSize; size++){          
                    for (var i = 0; i+size<= stringSize; i++){
                        tokens.push(datum.employee_name.substr(i, size));
                    }
                  }

                  return tokens;    


          },
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          limit: 10,
         
          remote:{
            url:'{{ URL::to("employee/dataJson?query=%QUERY") }}',
            filter: function (employee_list) {
                        // alert('this is an alert script from create');
                        // console.log(employee_list); //debugging
                        // Map the remote source JSON array to a JavaScript object array
                      return $.map(employee_list, function (employee) {
                          console.log(employee); //debugging
                          return {
                              employee_name: employee.first_name +' '+ employee.middle_name +' '+ employee.last_name+' - '+employee.nickname,
                              id: employee.employee_id
                          };
                      });
               }
          }

  });

  employee_list.initialize();
   console.log(employee_list);

  //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
 // $('#employee_employee_no_typeahead .typeahead').typeahead(null, {
    $('#employee_name').typeahead(
      {
        hint: true,
        highlight: true,
        minLength: 1
      }, 
      {
          employee_name: 'employee_list',
           displayKey: 'employee_name',
          source: employee_list.ttAdapter()         
      }

      ).bind("typeahead:selected", function(obj, employee, employee_no) {
            $(this).attr('data-employee_id',employee.id);
      });
  // END OF employee_name ->  typeahead


    $("#load").click(function(){
          $.ajax({
              url:'{{{ URL::to("teacher_portal/dataJson") }}}',
              type:'GET',
              data: {
                  'date' : $("#date").val(),
              },
              dataType: "json",
              async:false,
              success: function (data) 
              {   
                  $("#date_from").empty();
                  $("#date_to").empty();
                  $("#schedule_con").empty();
                  var date = data[0];
                  $("#date_from").append(date.date_from);
                  $("#date_to").append(date.date_to);
                  $.map(data[1],function(item){

                      var course_name = item.course_name;
                      var student_english_name = item.student_english_name;
                      var room_name = item.room_name;
                      var time_in = item.time_in;
                      var time_out = item.time_out;
                      var time_in_session = item.time_in_session;
                      var time_out_session = item.time_out_session;

                      if(course_name == null)
                      {
                          course_name = "";
                      }
                      if(student_english_name == null)
                      {
                          student_english_name = "";
                      }
                      if(room_name == null)
                      {
                          room_name = "";
                      }
                      if(time_in == null)
                      {
                          time_in = "";
                      }
                      if(time_out == null)
                      {
                          time_out = "";
                      }

                      $("#schedule_con").append('<tr>'
                          +'<td style="border:1px solid #000"><label class="control-label">'+course_name+'</label></td>'
                          +'<td style="border:1px solid #000"><label class="control-label">'+student_english_name+'</label></td>'
                          +'<td style="border:1px solid #000"><label class="control-label">'+room_name+'</label></td>'
                          +'<td style="border:1px solid #000"><label class="control-label">'+time_in+' '+time_in_session+'</label></td>'
                          +'<td style="border:1px solid #000"><label class="control-label">'+time_out+' '+time_out_session+'</label></td>'
                          +'</tr>');
                    
                  });
              }  

          });
    });
})
</script>


@stop
