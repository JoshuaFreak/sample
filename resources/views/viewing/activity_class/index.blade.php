@extends('site.layouts.default_view')

{{-- Web site Title --}}
@section('title')
Activity Class
@stop

{{-- Content --}}
@section('content')
<link href="{{asset('assets/site/css/schedule-index.css')}}" rel="stylesheet">
<style type="text/css">
.hidden{
   display:none;
 }
 .shown{
   display:block;
 }
 .optdisabled{
   color: gray;
 }
 #loadingFade {
    display: none;
    position:absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    background-color: #ababab;
    z-index: 1001;
    -moz-opacity: 0.8;
    opacity: .70;
    filter: alpha(opacity=80);
}

#loadingModal {
    display: none;
    position: absolute;
    top: 45%;
    left: 45%;
    width: 64px;
    height: 64px;
    padding:30px 15px 0px;
    border: 3px solid #ababab;
    box-shadow:1px 1px 10px #ababab;
    border-radius:20px;
    background-color: white;
    z-index: 1002;
    text-align:center;
/*    overflow: auto;*/
}
h2
{
    margin-left: 50px;
}
#container
{
    margin-top: 0px;
    display: none;
}
.top_text{
    display:inline-block;
    border-bottom:1px solid #000;
    padding-bottom: 0px;
}
#default_footer
{
    display: none;
}

.div-table1
{
      height:600px;
      overflow-x: scroll;
      overflow-y: scroll;
      background-color:#eee;
      margin-bottom: 50px; 
}
#TimeHeaderRow
{   
    width: 1729px;
    top: 0px;
    position: sticky !important;
    position: -webkit-sticky;
}
</style>
<div id="body">
        <div class="loader">
          <div class="one"></div>
          <div class="two"></div>
        </div>
</div>
<div id="container">
    <div class="row" style="padding:0px;margin: 0px;">
        <center>
            <div class="page-header" style="margin-left: 3%;margin-right: 3%;"><br>
                <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
                    {{{ Lang::get("scheduler_view.activity_class") }}}
                </h3>
            </div>
        </center>

        <div class="col-md-9 form-group" style="margin-left: 3%;margin-right: 3%;">
            <div class="col-md-2">
                <label class="control-label">Select Date:</label>
            </div>
            <div class="col-md-3">
                <input id="date" type="date" class="form-control input-sm" value="{{ date('Y-m-d') }}"></input>
            </div>
            <div class="col-md-3">
                <button id="load_sched" class="btn btn-sm btn-primary" type="button"> LOAD </button>
            </div>
        </div> 

        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <center>
            <div class="col-md-12">
                <div id="MainDiv" class="div-table1" style="margin-left: 3%;margin-right: 3%;">
                </div> 
            </div>
        </center>
    </div>
</div>

<!--     <div id="loadingFade"></div>
    <div id="loadingModal">
        <img id="loader" src="{{asset('assets/site/images/loading-wheel.gif')}}" style="width:50px;margin-left:-10px;margin-top:-25px;" />
    </div> -->
<!-- MODAL -->
<div class="modal fade" id="timeGroupModal" role="dialog">

    <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title">Add Class</h4>
            </div>
            <div class="modal-body form-group">
                <form id="myForm" class="form-horizontal">
                    <div class="col-md-12" style="padding:0px;">
                        <div class="col-md-12" style="border:0px solid #D3D3D3;padding:0px;">
                            <input type="hidden" id="modal_section_id" value=""/>
                            <input type="hidden" id="modal_class_id" value=""/>
                            <input type="hidden" id="student_count" value="0"/>
                            <div class="col-md-12 form-group">
                                <label class="col-md-4" for="from">Date Range</label>
                                <div class="input-daterange input-group col-md-8" id="datepicker">
                                    <input type="text" id="date_start" class="form-control" name="start" value="" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" id="date_end" class="form-control" name="end" value="" />
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <label>Select Class Capacity</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="modal_course_capacity_id" class="form-control input-sm" id="modal_course_capacity_id" tabindex="4" style="width:100%;">
                                       ]@foreach($course_capacity_list as $course_capacity)
                                            <option value="{{ $course_capacity ->id }}" data-capacity="{{ $course_capacity ->capacity }}">{{ $course_capacity -> capacity_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <label>Course</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="modal_program_id" class="form-control input-sm" id="modal_program_id" tabindex="4" style="width:100%;">
                                        <option value="0"></option>
                                        @foreach($program_list as $program)
                                            <option value="{{ $program ->id }}">{{ $program -> program_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <label>Subject</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="modal_course_id" class="form-control input-sm" id="modal_course_id" tabindex="4" style="width:100%;">
                                    </select>
                                </div>
                            </div>
                            <div>
                                <div class="col-md-12 form-group" id="modal_add_student_button">
                                    <div class="pull-right">
                                        <button class="btn btn-sm btn-primary" type="button" id="modal_add_student">Add Student</button>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <div class="col-md-4">
                                        <label>Search Student Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input id="modal_student_name" type="text" class="modal_student_name typeahead form-control input-sm" style="margin-bottom:10px;">
                                    </div>
                                </div>
                            </div>
                             <div id="modal_student_names">
                            </div>
                           <!--  <div class="col-md-12" style="margin-top:10px;margin-bottom:10px;">
                                <button type="button" class="btn btn-success btn-sm create-class">Create Class</button>
                            </div> -->
                            
                        </div>
                        <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                            <button id="modalCreate" type="button" class="btn btn-success btn-sm btn-ok" data-value="1">Create Class</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="add_space"></div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/>
            <br/><br/><br/><br/><br/><br/>
           <!--  <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
                <a id="release_modal" class="btn btn-success btn-ok">Ok</a>

            </div> -->

        </div>
      
    </div>
</div>
<!-- END MODAL -->

@stop

{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/site/js/schedule-index.js')}}"></script>
<script type="text/javascript">

$("#container").hide()
$("#default_footer").hide();

jQuery(window).load(function () {
    $("#body").hide();
    $("#container").show();
    $("#default_footer").show();
});


$(function(){

    setInterval(function() {
      window.location.reload();
    }, 600000);

    loadSched($("#date").val());
    $("#load_sched").click(function(){
        loadSched($("#date").val());
    });

    $(".sched").click(function(){
        $("#modal_student_names").empty();
        $("#add_space").empty();
    });
});

function getScheduleJson(date){

    $.ajax({
              url:'{{{ URL::to("scheduler_view/getScheduleSelfDataJson") }}}',
              type:'GET',
              dataType: "json",
              async:false,
              data:
                  {  
                      'date': date,
                  },
              success: function (data) 
              { 

                  scheduleJsonArray = data;
              }  

          });

    return scheduleJsonArray;

}

function getScheduleJsonByTeacher(date,teacher_id){

    $.ajax({
              url:'{{{ URL::to("scheduler_view/getScheduleDataJsonByTeacher") }}}',
              type:'GET',
              dataType: "json",
              async:false,
              data:
                  {  
                      'date': date,
                      'teacher_id': teacher_id,
                  },
              success: function (data) 
              { 

                  scheduleJsonArray = data;
              }  

          });

    return scheduleJsonArray;

}

function typeaheadStudent()
{
           

}

function loadSched(date){

    $.ajax({
        url:'{{{ URL::to("scheduler_view/getTimeDataJson") }}}',
        type:'GET',
        data: 0,
        dataType: "json",
        async:false,
        success: function (data) 
        {                  
            timeJsonArray = data[0];
            roomJsonArray = data[4];
            $("#MainDiv").empty();
            populateTimeHeaderGroup(timeJsonArray);

            var scheduleJsonArray = getScheduleJson($("#date").val());
            populateRoomGroupSchedule(roomJsonArray, scheduleJsonArray, timeJsonArray);
        }  

    });
    var count = 0;
    $('#timeModal').on('show.bs.modal', function(e) {

        $("#modal_student_names").empty();
        $("#modal_add_student_button").addClass('hidden');

        $(function() {
            $('#datepicker').datepicker({
            format: "yyyy-mm-dd",
            orientation: "top left",
            autoclose: true,
            startView: 1,
            todayHighlight: true,
            todayBtn: "linked",
            });
        });
        
        
        if(count == 0)
        {
            var time_in_id = $(e.relatedTarget).data('time');
            var time_out_id = $(e.relatedTarget).data('time_next');
            var teacher_id = $(e.relatedTarget).data('teacher');
            $("#modal_course_capacity_id").change(function(){

                var capacity = $("#modal_course_capacity_id option:selected").data('capacity');

                if(capacity == 1)
                {
                    $("#modal_add_student_button").removeClass('shown');
                    $("#modal_add_student_button").addClass('hidden');
                }
                else
                {
                    $("#modal_add_student_button").addClass('shown');
                    $("#modal_add_student_button").removeClass('hidden');

                }
            });


                
                    $("#modal_add_student").click(function(){

                        var student_count = $("#student_count").val();
                        student_count++;
                        $("#student_count").val(student_count);
                        $("#modal_student_names").append(''
                            +'<div class="col-md-12 form-group">'
                                +'<div class="col-md-4">'
                                    +'<label>Search Student Name</label>'
                                +'</div>'
                                +'<div class="col-md-8">'
                                    +'<input id="modal_student_name_'+student_count+'" type="text" class=" modal_student_name typeahead form-control input-sm" style="margin-bottom:10px;">'
                                +'</div>'
                            +'</div>'
                        +'');

                        $("#add_space").append("<br/><br/><br/>");

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
                                      url:'{{ URL::to("scoreEntry/dataJson?query=%QUERY") }}',
                                      filter: function (student_list) {

                                                return $.map(student_list, function (student) {
                                                    console.log(student); //debugging
                                                    return {
                                                        student_name: student.last_name + ', '+student.first_name + ' ' + student.middle_name + ' -( ' + student.student_english_name+')',
                                                        id: student.id,
                                                        examination_id: student.examination_id
                                                    };
                                                });
                                         }
                                    }

                            });

                            student_list.initialize();
                            console.log(student_list);

                                $('#modal_student_name_'+student_count).typeahead(
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
                                    $("#student_id").val(student.id);
                                    $("#student_name").val(student.student_name);
                                    $("#examination_id [value='"+student.examination_id+"']").attr("selected","selected");
                                   // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search student') 
                                    
                                });//endbind


                    });

                
                // typeaheadStudent();

            $("#modal_program_id").change(function(){
                
                selectListChange('modal_course_id','{{{URL::to("scheduler_view/programCourseDataJson")}}}',  { 'program_id': $("#modal_program_id").val() } ,'Please select a Program');
            });
            count++;  

            $("#modalCreate").click(function(){

                    var student_name_arr = [];
                    name_count = 0;
                    $(".modal_student_name").each(function(){

                        student_name_arr[name_count] = $(this).data('student_id');
                        name_count++;
                    });

                    $.ajax({
                        url:'{{{ URL::to("scheduler_view/create")}}}',
                        type:'post',
                        data:{ 
                            '_token': $("input[name=_token]").val(),
                            'time_id':time_in_id, 
                            'time_out':time_out_id, 
                            'teacher_id':teacher_id, 
                            'program_id':$("#modal_program_id").val(), 
                            'course_id':$("#modal_course_id").val(), 
                            'student_name_arr':student_name_arr, 
                            'date_start':$("#date_start").val(), 
                            'date_end':$("#date_end").val(), 
                            'course_capacity_id':$("#modal_course_capacity_id").val(), 
                        },async:false,
                        success: function (data) {
                            swal("Successfully Create");                  
                          }
                    }).done(function(){
                        $("#Teacher"+teacher_id+" > div.sched").remove();
                        $.ajax({
                            url:'{{{ URL::to("scheduler_view/getTimeDataJson") }}}',
                            type:'GET',
                            data: 0,
                            dataType: "json",
                            async:false,
                            success: function (data) 
                            {                  
                                // timeJsonArray = data[0];

                                // var scheduleJsonArray = getScheduleJsonByTeacher($("#date").val(),teacher_id);
                                // populateScheduleByTeacher(scheduleJsonArray, timeJsonArray, teacher_id);
                                // $("#timeModal").modal('hide');
                                location.reload();
                            }  

                        });
                    });
            });

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
                          url:'{{ URL::to("scoreEntry/dataJson?query=%QUERY") }}',
                          filter: function (student_list) {

                                    return $.map(student_list, function (student) {
                                        console.log(student); //debugging
                                        return {
                                            student_name: student.last_name + ', '+student.first_name + ' ' + student.middle_name + ' -( ' + student.student_english_name+')',
                                            id: student.id,
                                            examination_id: student.examination_id
                                        };
                                    });
                             }
                        }

                });

                student_list.initialize();
                console.log(student_list);

                    $('#modal_student_name').typeahead(
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
                        $("#student_id").val(student.id);
                        $("#student_name").val(student.student_name);
                        $("#examination_id [value='"+student.examination_id+"']").attr("selected","selected");
                       // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search student') 
                        
                    });//endbind

            
        }

    });

}
</script>
@stop
    