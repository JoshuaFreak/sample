@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.student") }}} :: @parent
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
                @include('student_sidebar')
        </ul>
      </div>
    </div>

<div id="page-wrapper">
    <div class="row">
        <div class="page-header">
            <br/>
        		<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
        			{{{ Lang::get("student.student_schedule") }}}

        		</h2>
        </div>
      	<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      	<div class="col-md-12">
      	     <div class="form-group {{{ $errors->has('student_id') ? 'has-error' : '' }}}">
          			<label class="col-md-1 control-label" for="student_name">{!! Lang::get('student.search_student') !!}</label>
                <input type="hidden" name="student_id" id="student_id" value="0" />
                <input type="hidden" name="nickname" id="nickname" value="0" />
                <!-- <input type="hidden" name="date_from" id="date_from" value="0" /> -->
                <!-- <input type="hidden" name="date_to" id="date_to" value="0" /> -->
                <input type="hidden" name="gender_id" id="gender_id" value="0" />
                <input type="hidden" name="nationality_id" id="nationality_id" value="0" />
                <input type="hidden" name="period" id="period" value="0" />
                <div class="col-md-4">
          					<input class="typeahead form-control" type="text" name="student_name" id="student_name" value="" autofocus/>
          				{!! $errors->first('student_id', '<label class="control-label" for="student_name">:message</label>')!!}
          			</div>
                <div class="col-md-3">
                    <select id="program_id" class="form-control">
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="examination_id" class="form-control">
                    </select>
                </div>
      		  </div>
        </div>

   <!--      <button class="btn btn-sm btn-primary pull-right" style="margin-right:50px;" onclick="PrintElem('#table')" type="button">Print
        </button> -->

        <button id="printPdf" class="btn btn-sm btn-primary pull-right" style="margin-right:50px;" type="button">Print
        </button>
        <br>
        <br>
        <br>
        <br>
        <div class="form-group">
          <div class="col-md-1">
          </div>
          <div class="col-md-3">
              <label class="control-label">Date From: <span id="date_from"></span></label>
          </div>
          <div class="col-md-3">
              <label class="control-label">Date To: <span id="date_to"></span></label>
          </div>
        </div>
        <div id="table">
            <table class="table" style="width: 90%;margin-left: 50px;">
                <tbody style="background-color:#007BEF;color: #fff">
                  <th>Subject</th>
                  <th>Teacher</th>
                  <th>Room</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                </tbody>
                <tbody id="schedule_con"></tbody>
            </table>
        </div>
    </div>
</div>

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

          var student_id = $("#student_id").val();
          var program_id = $("#program_id").val();
          var nickname = $("#nickname").val();
          var gender_id = $("#gender_id").val();
          var period = $("#period").val();
          var nationality_id = $("#nationality_id").val();
          var examination_id = $("#examination_id").val();
          var date_from = $("#date_from").text();
          var date_to = $("#date_to").text();
          var program_category_id = $("#program_id option:selected").data('program_category_id');
          // location.href="student/studentSchedulePdf?student_id="+student_id+"&program_id="+program_id;
          window.open(
            "student/studentSchedulePdf?student_id="+student_id+"&program_id="+program_id+"&program_category_id="+program_category_id+"&examination_id="+examination_id+"&nickname="+nickname+"&gender_id="+gender_id+"&date_from="+date_from+"&date_to="+date_to+"&nationality_id="+nationality_id+"&period="+period,
            "_blank" // <- This is what makes it open in a new window.
          );
      });


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
                        START OF student_name ->  typeahead
                    *************************************************************************/
                        var student_list = new Bloodhound({
                                datumTokenizer: function (datum) {
                                    //return Bloodhound.tokenizers.whitespace(datum.student_name);
                                       var tokens = [];
                                        //the available string is 'name' in your datum
                                        var stringSize = datum.student_name.length;
                                        //multiple combinations for every available size
                                        //(eg. dog = d, o, g, do, og, dog)
                                        for (var size = 1; size <= stringSize; size++){
                                          for (var i = 0; i+size<= stringSize; i++){
                                              tokens.push(datum.student_name.substr(i, size));
                                          }
                                        }

                                        return tokens;


                                },
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                limit: 10,

                                remote:{
                                  url:'{{ URL::to("http://cebucia.com/api/get_student_scheduler.php?query=%QUERY&_token=L3tM3P@55!") }}',
                                  // url:'{{ URL::to("scoreEntry/dataJson?query=%QUERY") }}',
                                  filter: function (student_list) {

                                            // return $.map(student_list, function (student) {
                                            //     console.log(student); //debugging
                                            //     return {
                                            //         student_name: student.last_name + ' '+student.first_name + ' ' + student.middle_name + ' - ( ' + student.student_english_name+')',
                                            //         id: student.id,
                                            //         examination_id: student.examination_id
                                            //     };
                                            // });
                                            return $.map(student_list, function (student) {
                                                console.log(student); //debugging
                                                
                                                return $.map(student, function (data){

                                                    return {
                                                        student_name: data.student_id+' - '+data.sename,
                                                        student_eng_name: data.sename,
                                                        nickname: data.nick,
                                                        date_from: data.abrod_date,
                                                        date_to: data.end_date,
                                                        gender_id: data.gender_id,
                                                        nationality_id: data.nationality_id,
                                                        period: data.period,
                                                        id: data.student_id,
                                                        // examination_id: student.examination_id
                                                    };
                                                });
                                            });
                                     }
                                }

                        });

                        student_list.initialize();
                        console.log(student_list);

                            $('#student_name').typeahead(
                            {
                              hint: true,
                              highlight: true,
                              minLength: 1
                            },
                            {
                                student_name: 'student_list',
                                displayKey: 'student_name',
                                source: student_list.ttAdapter()
                            }

                            ).bind("typeahead:selected", function(obj, student, account_no) {
                                // console.log(student);
                                $(this).attr('data-student_id',student.id);
                                $(this).attr('data-student_name',student.student_eng_name);
                                $("#student_id").val(student.id);
                                $(this).val(student.student_name);
                                $("#nickname").val(student.nickname);
                                $("#date_from").val(student.date_from);
                                $("#date_to").val(student.date_to);
                                $("#gender_id").val(student.gender_id);
                                $("#nationality_id").val(student.nationality_id);
                                $("#period").val(student.period);

                                $.ajax({
                                    url:'{{{ URL::to("student/getStudentProgram") }}}',
                                    type:'GET',
                                    data: {
                                        'student_id' : student.id
                                    },
                                    dataType: "json",
                                    async:false,
                                    success: function (data) 
                                    {   
                                        $("#program_id").empty();
                                        $("#program_id").append('<option></option>');
                                        $.map(data[0],function(item){
                                            $("#program_id").append('<option value="'+item.id+'" data-program_category_id="'+item.program_category_id+'">'+item.program_name+'</option>');
                                        });
                                        $("#examination_id").empty();
                                        $("#examination_id").append('<option></option>');
                                        $.map(data[1],function(item){
                                            $("#examination_id").append('<option value="'+item.examination_id+'">'+item.examination_name+'</option>');
                                        });
                                    }  

                                });
                                
                            });//endbind



    });

</script>
<script>
$(function(){
    $("#program_id").change(function(){

          $.ajax({
              url:'{{{ URL::to("student/getStudentSchedule") }}}',
              type:'GET',
              data: {
                  'student_id' : $("#student_id").val(),
                  'program_id' : $("#program_id").val(),
              },
              dataType: "json",
              async:false,
              success: function (data) 
              {   
                  $("#date_from").empty();
                  $("#date_to").empty();
                  $("#schedule_con").empty();
                  var date = data[0];
                  $("#date_from").text(date.date_from);
                  $("#date_to").text(date.date_to);
                  $.map(data[1],function(item){

                      var course_name = item.course_name;
                      var nickname = item.nickname;
                      var room_name = item.room_name;
                      var time_in = item.time_in;
                      var time_out = item.time_out;
                      var time_in_session = item.time_in_session;
                      var time_out_session = item.time_out_session;

                      if(course_name == null)
                      {
                          course_name = "";
                      }
                      if(nickname == null)
                      {
                          nickname = "";
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

                      $("#schedule_con").append('<tr style="background-color:#fff;">'
                          +'<td style="border:1px solid #000"><label class="control-label">'+course_name+'</label></td>'
                          +'<td style="border:1px solid #000"><label class="control-label">'+nickname+'</label></td>'
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
