@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.scheduler_list") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<link href="{{asset('assets/site/css/schedule-index.css')}}" rel="stylesheet">

<style type="text/css">
#scrollable-dropdown-menu .tt-dropdown-menu {
  max-height: 300px;
  overflow-y: auto;
}
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
                    {{{ Lang::get("scheduler.scheduler_men_to_men") }}}
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

@stop

{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/site/js/schedule-index.js')}}"></script>
<script type="text/javascript">

$("#container").hide();
$("#default_footer").hide();
jQuery(window).load(function () {
    $("#body").hide();
    $("#container").show();
    $("#default_footer").show();
});

function openModal(id){
    $('#'+id).modal({
        keyboard: false,
        backdrop: 'static'
    });
}

$(function(){
    loadSched($("#date").val());
    $("#load_sched").click(function(){
        loadSched($("#date").val());

    });
});



function getScheduleJson(date){

    $.ajax({
              url:'{{{ URL::to("scheduler/getScheduleDataJson") }}}',
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
              url:'{{{ URL::to("scheduler/getScheduleDataJsonByTeacher") }}}',
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

$("#modal_add_student_button").addClass('hidden');

function datepicker(id) {
    $("#datepicker"+id).datepicker().on('show.bs.modal', function(event) {
        // prevent datepicker from firing bootstrap modal "show.bs.modal"
        event.stopPropagation();
    });

    $('#datepicker'+id).datepicker({
        format: "yyyy-mm-dd",
        orientation: "auto",
        autoclose: true,
        startView: 1,
        todayHighlight: true,
        todayBtn: "linked",
    });
}
        
function typeaheadStudent()
{
           

}

function loadSched(date){

    $.ajax({
        url:'{{{ URL::to("scheduler/getTimeDataJson") }}}',
        type:'GET',
        data: 0,
        dataType: "json",
        async:false,
        success: function (data) 
        {                  
            timeJsonArray = data[0];
            roomJsonArray = data[3];
            $("#MainDiv").empty();
            populateTimeHeader(timeJsonArray,"<span class='top_text'>"
                +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                +"TIME"
                +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                +"</span> ROOM");

            var scheduleJsonArray = getScheduleJson(date);
            populateRoomSchedule(roomJsonArray, scheduleJsonArray, timeJsonArray);
        }  

    });
    var count = 0;
    $('#timeModal').on('show.bs.modal', function(e) {

        datepicker(1);
        
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
                            +'<div class="col-md-12 form-group">'
                                +'<label class="col-md-4" for="from">Date Range</label>'
                                +'<div class="input-daterange input-group col-md-8" id="datepicker'+student_count+'" style="padding-left: 15px !important;padding-right: 15px !important;">'
                                    +'<input type="text" class="date_start form-control" name="start" value="" />'
                                    +'<span class="input-group-addon">to</span>'
                                    +'<input type="text" class="date_end form-control" name="end" value="" />'
                               +'</div>'
                            +'</div>'
                        +'');
                        datepicker(student_count);
                        $("#add_space").append("<br/><br/><br/><br/><br/><br/>");

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
                                    $(this).attr('data-student_name',student.student_eng_name);
                                    $("#student_id").val(student.id);
                                    $(this).val(student.student_name);
                                    // $("#examination_id [value='"+student.examination_id+"']").attr("selected","selected");
                                   // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search student') 
                                    
                                });//endbind


                    });

                
                // typeaheadStudent();

            $("#modal_program_id").change(function(){
                
                selectListChange('modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': $("#modal_program_id").val() } ,'Please select a Program');
            });
            $("#modal_course_capacity_id").change(function(){
                
                selectListChange('modal_room_id','{{{URL::to("scheduler/courseCapacityRoomDataJson")}}}',  { 'course_capacity_id': $("#modal_course_capacity_id").val() } ,'Please select a Class Capacity');
            });
            count++;  

            $("#modalCreate").click(function(){

                    var student_name_arr = [];
                    var student_eng_name_arr = [];
                    var start_arr = [];
                    var end_arr = [];
                    name_count = 0;
                    $(".modal_student_name").each(function(){
                        if($(this).data('student_id') != undefined){
                            student_name_arr[name_count] = $(this).data('student_id');
                            student_eng_name_arr[name_count] = $(this).data('student_name');
                            name_count++;
                            // alert($(this).data('student_id'));
                        }
                    });
                    name_count = 0;
                    $(".date_start").each(function(){
                        if($(this).val() != undefined){
                            start_arr[name_count] = $(this).val();
                            name_count++;
                            // alert($(this).data('student_id'));
                        }
                    });
                    name_count = 0;
                    $(".date_end").each(function(){
                        if($(this).val() != undefined){
                            end_arr[name_count] = $(this).val();
                            name_count++;
                            // alert($(this).data('student_id'));
                        }
                    });


                    $.ajax({
                        url:'{{{ URL::to("scheduler/create")}}}',
                        type:'post',
                        data:{ 
                            '_token': $("input[name=_token]").val(),    
                            'time_id':time_in_id, 
                            'time_out':time_out_id, 
                            'teacher_id':teacher_id, 
                            'program_id':$("#modal_program_id").val(), 
                            'course_id':$("#modal_course_id").val(), 
                            'room_id':$("#modal_room_id").val(), 
                            'student_name_arr':student_name_arr, 
                            'student_eng_name_arr':student_eng_name_arr, 
                            'start_arr':start_arr, 
                            'end_arr':end_arr, 
                            // 'date_start':$("#date_start").val(), 
                            // 'date_end':$("#date_end").val(), 
                            'course_capacity_id':$("#modal_course_capacity_id").val(), 
                        },async:false,
                        success: function (data) {
                            swal("Successfully Create");                  
                          }
                    }).done(function(){
                        $("#Teacher"+teacher_id+" > div.sched").remove();
                        $.ajax({
                            url:'{{{ URL::to("scheduler/getTimeDataJson") }}}',
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
                        limit: 100,

                        remote:{
                            url:'{{ URL::to("http://cebucia.com/api/get_student_scheduler.php?query=%QUERY&_token=L3tM3P@55!") }}',
                            // url:'{{ URL::to("http://cebucia.com/api/get_student_scheduler.php?_token=L3tM3P@55!&query=%QUERY") }}',
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
                        $(this).attr('data-student_name',student.student_eng_name);
                        $("#student_id").val(student.id);
                        $("#modal_student_name").val(student.student_name);
                        // $("#examination_id [value='"+student.examination_id+"']").attr("selected","selected");
                       // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search student') 
                        
                    });//endbind

            
        }

    });

}
</script>
@stop
    