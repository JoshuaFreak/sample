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
}
.top_text{
    display:inline-block;
    border-bottom:1px solid #000;
    padding-bottom: 0px;
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
                    {{{ Lang::get("scheduler.create_schedule_by_teacher") }}}
                    <div class="pull-right">
                    </div>
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
<div class="modal fade" id="timeModal" role="dialog">

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
                            <input type="hidden" id="student_count" value="1"/>
                            
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <label>Select Class Capacity</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="modal_course_capacity_id" class="form-control input-sm" id="modal_course_capacity_id" tabindex="4" style="width:100%;">
                                        <option value="0"></option>
                                        @foreach($course_capacity_list as $course_capacity)
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
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <label>Room</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="modal_room_id" class="form-control input-sm" id="modal_room_id" tabindex="4" style="width:100%;">
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
                                    <div class="col-md-8" id="scrollable-dropdown-menu">
                                        <input id="modal_student_name" type="text" class="modal_student_name typeahead form-control input-sm" style="margin-bottom:10px;">
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label class="col-md-4" for="from">Date Range</label>
                                    <div class="input-daterange input-group col-md-8" id="datepicker1" style="padding-left: 15px !important;padding-right: 15px !important;">
                                        <input type="text" class="date_start form-control" name="start" value="" />
                                        <span class="input-group-addon">to</span>
                                        <input type="text" class="date_end form-control" name="end" value="" />
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
            <br/><br/><br/><br/><br/><br/><br/><br/>
           <!--  <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
                <a id="release_modal" class="btn btn-success btn-ok">Ok</a>

            </div> -->

        </div>
      
    </div>
</div>
<!-- END MODAL -->
<!-- MODAL -->
<div class="modal fade" id="timeEditModal" role="dialog">

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
                            <input type="hidden" id="edit_modal_section_id" value=""/>
                            <input type="hidden" id="edit_modal_class_id" value=""/>
                            <input type="hidden" id="edit_course_capacity_id" value=""/>
                            <input type="hidden" id="edit_course_id" value=""/>
                            <input type="hidden" id="edit_program_id" value=""/>
                            <input type="hidden" id="edit_room_id" value=""/>
                            <input type="hidden" id="edit_batch_id" value=""/>
                            <input type="hidden" id="edit_time_in_id" value=""/>
                            <input type="hidden" id="edit_time_out_id" value=""/>
                            <input type="hidden" id="edit_teacher_id" value=""/>
                            <input type="hidden" id="edit_student_count" value="1"/>
                            <input type="hidden" id="edit_schedule_id" value="1"/>
                            
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <label>Select Class Capacity</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="edit_modal_course_capacity_id" class="form-control input-sm" id="edit_modal_course_capacity_id" tabindex="4" style="width:100%;">
                                        <option value="0"></option>
                                        @foreach($course_capacity_list as $course_capacity)
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
                                    <select name="edit_modal_program_id" class="form-control input-sm" id="edit_modal_program_id" tabindex="4" style="width:100%;">
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
                                    <select name="edit_modal_course_id" class="form-control input-sm" id="edit_modal_course_id" tabindex="4" style="width:100%;">
                                        <option value=""></option>
                                        @foreach($course_list as $course)
                                            <option value="{{$course -> id}}">{{$course -> course_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="col-md-4">
                                    <label>Room</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="edit_modal_room_id" class="form-control input-sm" id="edit_modal_room_id" tabindex="4" style="width:100%;">
                                        @foreach($room_list as $room)
                                            <option value="{{$room -> id}}">{{$room -> room_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" id="scheduler_student_list">
                            </div>
                            <div>
                                <div class="col-md-12 form-group" id="edit_modal_add_student_button">
                                    <div class="pull-right">
                                        <button class="btn btn-sm btn-primary" type="button" id="edit_modal_add_student">Add Student</button>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12 form-group">
                                    <div class="col-md-4">
                                        <label>Search Student Name</label>
                                    </div>
                                    <div class="col-md-8" id="scrollable-dropdown-menu">
                                        <input id="edit_modal_student_name" type="text" class="edit_modal_student_name typeahead form-control input-sm" style="margin-bottom:10px;">
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label class="col-md-4" for="from">Date Range</label>
                                    <div class="input-daterange input-group col-md-8" id="datepicker1" style="padding-left: 15px !important;padding-right: 15px !important;">
                                        <input type="text" class="date_start form-control" name="edit_start" value="" />
                                        <span class="input-group-addon">to</span>
                                        <input type="text" class="date_end form-control" name="edit_end" value="" />
                                    </div>
                                </div> -->
                            </div>
                             <div id="edit_modal_student_names">
                            </div>
                           <!--  <div class="col-md-12" style="margin-top:10px;margin-bottom:10px;">
                                <button type="button" class="btn btn-success btn-sm create-class">Create Class</button>
                            </div> -->
                            
                        </div>
                        <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                            <button id="modalEdit" type="button" class="btn btn-success btn-sm btn-ok">Update Class</button>
                            <button id="modalEditDelete" type="button" class="btn btn-danger btn-sm btn-ok">Delete Class</button>
                            <button id="modalEditDeleteAll" type="button" class="btn btn-warning btn-sm btn-ok">Delete All Class</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="edit_add_space"></div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/>
            <br/><br/><br/>
           <!--  <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
                <a id="release_modal" class="btn btn-success btn-ok">Ok</a>

            </div> -->

        </div>
      
    </div>
</div>
<!-- END MODAL -->

<!-- MODAL -->
<div class="modal fade" id="timeDutyModal" role="dialog">

    <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title">Edit Class</h4>
            </div>
            <div class="modal-body form-group">
                <form id="classDutyForm" class="form-horizontal">
                    <input type="hidden" id="duty_teacher_id" value="0" />
                    <input type="hidden" id="duty_details" value="0" />
                    <input type="hidden" id="duty_class_id" value="0"/>
                    <div class="col-md-12" style="padding:0px;">
                        <div class="col-md-12" style="border:0px solid #D3D3D3;padding:0px;">
                            <select class="form-control col-md-11" id="duty_type_id" name="duty_type_id">
                                <option value="0" selected></option>
                                @foreach($duty_type_list as $duty_type)
                                    <option value="{{$duty_type -> id}}" data-color="{{$duty_type -> color}}">{{ $duty_type -> duty_type_name}}</option>
                                @endforeach
                            </select>                       
                        </div>
                        <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                        </div>
                        <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                            <button id="modalSaveChanges" type="button" class="btn btn-success btn-sm btn-ok">Save Class</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="add_space"></div>
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

    $('#timeDutyModal').on('shown.bs.modal', function(e) {
    });

    loadSched($("#date").val());
    $("#load_sched").click(function(){
        loadSched($("#date").val());

        $(".edit_schedule").click(function(){
            var course_capacity_id = $(this).data('course_capacity_id');
            var program_id = $(this).data('program_id');
            var room_id = $(this).data('room_id');
            var batch_id = $(this).data('batch_id');
            var course_id = $(this).data('course_id');
            var time_in_id = $(this).data('time_start');
            var time_out_id = $(this).data('time_end');
            var teacher_id = $(this).data('teacher');
            var schedule_id = $(this).data('id');

            $("#edit_course_capacity_id").val(course_capacity_id);
            $("#edit_program_id").val(program_id);
            $("#edit_room_id").val(room_id);
            $("#edit_course_id").val(course_id);
            $("#edit_batch_id").val(batch_id);
            $("#edit_time_in_id").val(time_in_id);
            $("#edit_time_out_id").val(time_out_id);
            $("#edit_teacher_id").val(teacher_id);
            $("#edit_schedule_id").val(schedule_id);
            $("#modalEditDelete").attr('data-schedule_id',schedule_id);
            $("#modalEditDeleteAll").attr('data-schedule_id',schedule_id);

            edit_course_capacity_id = $("#edit_course_capacity_id").val();


            if(edit_course_capacity_id == 1 || edit_course_capacity_id == 5 || edit_course_capacity_id == 6)
            {
                $("#edit_modal_add_student").removeClass('shown');
                $("#edit_modal_add_student").addClass('hidden');
            }
            else
            {
                $("#edit_modal_add_student").addClass('shown');
                $("#edit_modal_add_student").removeClass('hidden');
            }

            openModal('timeEditModal');
        });

        $(".sched").click(function(){
            // $("#modal_student_names").empty();
            // $("#add_space").empty();
            var teacher_id = $(this).data('teacher');
            var class_id = $(this).data('class_id');
            var details = $(this).data('details');

            // $('#timeDutyModal').find("#modalSaveChanges").removeAttr('data-teacher_id');
            // $('#timeDutyModal').find("#modalSaveChanges").removeAttr('data-class_id');
            // $('#timeDutyModal').find("#modalSaveChanges").attr('data-teacher_id',teacher_id);
            // $('#timeDutyModal').find("#modalSaveChanges").attr('data-class_id',class_id);
            $("#duty_teacher_id").val(teacher_id);
            $("#duty_class_id").val(class_id);
            $("#duty_details").val(details);

        });
    });

    $(".edit_schedule").click(function(){

            var course_capacity_id = $(this).data('course_capacity_id');
            var program_id = $(this).data('program_id');
            var room_id = $(this).data('room_id');
            var batch_id = $(this).data('batch_id');
            var course_id = $(this).data('course_id');
            var time_in_id = $(this).data('time_start');
            var time_out_id = $(this).data('time_end');
            var teacher_id = $(this).data('teacher');
            var schedule_id = $(this).data('id');

            $("#edit_course_capacity_id").val(course_capacity_id);
            $("#edit_program_id").val(program_id);
            $("#edit_room_id").val(room_id);
            $("#edit_course_id").val(course_id);
            $("#edit_batch_id").val(batch_id);
            $("#edit_time_in_id").val(time_in_id);
            $("#edit_time_out_id").val(time_out_id);
            $("#edit_teacher_id").val(teacher_id);
            $("#edit_schedule_id").val(schedule_id);
            $("#modalEditDelete").attr('data-schedule_id',schedule_id);
            $("#modalEditDeleteAll").attr('data-schedule_id',schedule_id);

            edit_course_capacity_id = $("#edit_course_capacity_id").val();

            if(edit_course_capacity_id == 1 || edit_course_capacity_id == 5 || edit_course_capacity_id == 6)
            {
                $("#edit_modal_add_student").removeClass('shown');
                $("#edit_modal_add_student").addClass('hidden');
            }
            else
            {
                $("#edit_modal_add_student").addClass('shown');
                $("#edit_modal_add_student").removeClass('hidden');
            }

            openModal('timeEditModal');
    });

    $(".sched").click(function(){
        // $("#modal_student_names").empty();
        // $("#add_space").empty();
        var teacher_id = $(this).data('teacher');
        var class_id = $(this).data('class_id');
        var details = $(this).data('details');

        // $('#timeDutyModal').find("#modalSaveChanges").removeAttr('data-teacher_id');
        // $('#timeDutyModal').find("#modalSaveChanges").removeAttr('data-class_id');
        // $('#timeDutyModal').find("#modalSaveChanges").attr('data-teacher_id',teacher_id);
        // $('#timeDutyModal').find("#modalSaveChanges").attr('data-class_id',class_id);
        $("#duty_teacher_id").val(teacher_id);
        $("#duty_class_id").val(class_id);
        $("#duty_details").val(details);

    });

    $("#modalSaveChanges").click(function(){
        var duty_teacher_id = $("#duty_teacher_id").val();
        var duty_details = $("#duty_details").val();
        var duty_class_id = $("#duty_class_id").val();
        var duty_type = $("#duty_type_id option:selected").text();
        var color = $("#duty_type_id option:selected").data('color');

        $.ajax({
            url:'{{{ URL::to("scheduler/update_teacher_class") }}}',
            type:'POST',
            data: {
                'teacher_id' : duty_teacher_id,
                'class_id' : duty_class_id,
                'date' : $("#date").val(),
                'duty_type_id' : $("#duty_type_id").val(),
                '_token' : $("input[name=_token]").val(),
            },
            dataType: "json",
            async:false,
            success: function (data) 
            {            
                swal("Saved!", "Your class has been updated.", "success"); 
                $("#timeDutyModal").modal('hide'); 

                if($("#duty_type_id").val() == 0)
                {
                    $("#sched_"+duty_teacher_id+'_'+duty_class_id).empty();
                    $("#sched_"+duty_teacher_id+'_'+duty_class_id).attr('style','background-color:#00FFFF !important;');
                    $("#sched_"+duty_teacher_id+'_'+duty_class_id).append(duty_details);
                }
                else
                {
                    $("#sched_"+duty_teacher_id+'_'+duty_class_id).empty();
                    $("#sched_"+duty_teacher_id+'_'+duty_class_id).attr('style','background-color:'+color+' !important;font-size:15px;color:#003366;');
                    $("#sched_"+duty_teacher_id+'_'+duty_class_id).append('<b>'+duty_type+'</b>');    
                }
                
            }  
        });
    });


    var edit_count = 0;
    $('#timeEditModal').on('show.bs.modal', function(e) {

        // var course_id = $(e.relatedTarget).data('course_id');
        // var course_capacity_id = $(e.relatedTarget).data('course_capacity_id');
        // var program_id = $(e.relatedTarget).data('program_id');
        // var room_id = $(e.relatedTarget).data('room_id');
        // var batch_id = $(e.relatedTarget).data('batch_id');

        var course_capacity_id = $("#edit_course_capacity_id").val();
        var program_id = $("#edit_program_id").val();
        var room_id = $("#edit_room_id").val();
        var course_id = $("#edit_course_id").val();
        var batch_id = $("#edit_batch_id").val();
        var edit_time_in_id = $("#edit_time_in_id").val();
        var edit_time_out_id = $("#edit_time_out_id").val();
        var edit_teacher_id = $("#edit_teacher_id").val();

        $("#edit_modal_program_id [value='"+program_id+"']").attr("selected","selected");
        selectListChange('edit_modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': $("#edit_modal_program_id").val() } ,'Please select a Program');

        $("#edit_modal_program_id").change(function(){     
            selectListChange('edit_modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': $("#edit_modal_program_id").val() } ,'Please select a Program');
        });

        $("#edit_modal_course_capacity_id [value='"+course_capacity_id+"']").attr("selected","selected");

                data = $("#edit_modal_course_capacity_id").val();
  
                if(data != 1)
                {   
                    data = 8;
                }

        selectListChange('edit_modal_room_id','{{{URL::to("scheduler/courseCapacityRoomDataJson")}}}',  { 'course_capacity_id': data } ,'Please select a Program');
        
        $("#edit_modal_course_capacity_id").change(function(){     

            data = $(this).val();
            if(data != 1)
            {   
                data = 8;
            }

            selectListChange('edit_modal_room_id','{{{URL::to("scheduler/courseCapacityRoomDataJson")}}}',  { 'course_capacity_id': data } ,'Please select a Program');
        });

        $("#edit_modal_room_id [value='"+room_id+"']").attr("selected","selected");
        $("#edit_modal_course_id [value='"+course_id+"']").attr("selected","selected");

        $("#edit_add_space").empty();

        $.ajax({
              url:'{{{ URL::to("scheduler/getBatchStudentDataJson") }}}',
              type:'GET',
              dataType: "json",
              async:false,
              data:
                  {  
                      'batch_id': batch_id,
                  },
              success: function (data) 
              { 
                    $("#scheduler_student_list").empty();
                    
                    $.map(data,function(item){
                        $("#scheduler_student_list").append('<div class="col-md-12"><label class="control-label">'+item.student_id+' - '+item.student_english_name+'</label></div>');    
                        $("#edit_add_space").append('<br/><br/>');
                    })
                    
              }  

        });

        if(edit_count == 0)
        {
            edit_count++;
            $("#edit_modal_add_student").click(function(){

                    var edit_student_count = $("#edit_student_count").val();
                    edit_student_count++;
                    $("#edit_student_count").val(edit_student_count);
                    $("#edit_modal_student_names").append(''
                        +'<div class="col-md-12 form-group">'
                            +'<div class="col-md-4">'
                                +'<label>Search Student Name</label>'
                            +'</div>'
                            +'<div class="col-md-8">'
                                +'<input id="edit_modal_student_name_'+edit_student_count+'" type="text" class="edit_modal_student_name typeahead form-control input-sm" style="margin-bottom:10px;">'
                            +'</div>'
                        +'</div>'
                        +'<div class="col-md-12 form-group">'
                            +'<label class="col-md-4" for="from">Date Range</label>'
                            +'<div class="input-daterange input-group col-md-8" id="datepicker'+edit_student_count+'" style="padding-left: 15px !important;padding-right: 15px !important;">'
                                +'<input type="text" class="date_start form-control" name="edit_start" value="" />'
                                +'<span class="input-group-addon">to</span>'
                                +'<input type="text" class="date_end form-control" name="edit_end" value="" />'
                           +'</div>'
                        +'</div>'
                    +'');

                    datepicker(edit_student_count);

                    $("#edit_add_space").append("<br/><br/><br/><br/><br/><br/>");

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

                            $('#edit_modal_student_name_'+edit_student_count).typeahead(
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

            $("#modalEditDelete").click(function(){
                var schedule_id = $(this).data('schedule_id');

                    swal({
                      title: "Are you sure?",
                      text: "You will not be able to recover this data!",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "Yes, delete it!",
                      closeOnConfirm: false
                    },
                    function(){
                        $.ajax({
                            url:'{{{ URL::to("scheduler/deleteClass") }}}',
                            type:'POST',
                            data: {
                                'schedule_id' : schedule_id,
                                '_token' : $("input[name=_token]").val(),
                            },
                            dataType: "json",
                            async:false,
                            success: function (data) 
                            {            
                                swal("Deleted!", "Your class has been deleted.", "success");  
                                location.reload();    
                            }  
                        });
                    });
            });

            $("#modalEditDeleteAll").click(function(){
                var schedule_id = $(this).data('schedule_id');

                    swal({
                      title: "Are you sure?",
                      text: "You will not be able to recover this data!",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "Yes, delete it!",
                      closeOnConfirm: false
                    },
                    function(){
                        $.ajax({
                            url:'{{{ URL::to("scheduler/deleteClassAll") }}}',
                            type:'POST',
                            data: {
                                'schedule_id' : schedule_id,
                                '_token' : $("input[name=_token]").val(),
                            },
                            dataType: "json",
                            async:false,
                            success: function (data) 
                            {            
                                swal("Deleted!", "Your class has been deleted.", "success");  
                                location.reload();    
                            }  
                        });
                    });
            });
        }

        $("#modalEdit").click(function(){

                    var edit_student_name_arr = [];
                    var edit_student_eng_name_arr = [];
                    var edit_start_arr = [];
                    var edit_end_arr = [];
                    edit_name_count = 0;
                    $(".edit_modal_student_name").each(function(){
                        if($(this).data('student_id') != undefined){
                            edit_student_name_arr[edit_name_count] = $(this).data('student_id');
                            edit_student_eng_name_arr[edit_name_count] = $(this).data('student_name');
                            edit_name_count++;
                            // alert($(this).data('student_id'));
                        }
                    });
                    edit_name_count = 0;
                    $(".edit_date_start").each(function(){
                        if($(this).val() != undefined){
                            edit_start_arr[edit_name_count] = $(this).val();
                            edit_name_count++;
                            // alert($(this).data('student_id'));
                        }
                    });
                    edit_name_count = 0;
                    $(".edit_date_end").each(function(){
                        if($(this).val() != undefined){
                            edit_end_arr[edit_name_count] = $(this).val();
                            edit_name_count++;
                            // alert($(this).data('student_id'));
                        }
                    });


                    $.ajax({
                        url:'{{{ URL::to("scheduler/edit")}}}',
                        type:'post',
                        data:{ 
                            '_token': $("input[name=_token]").val(),    
                            'date':$("#date").val(),
                            'schedule_id':$("#edit_schedule_id").val(),
                            'batch_id':$("#edit_batch_id").val(),
                            'time_id':edit_time_in_id, 
                            'time_out':edit_time_out_id, 
                            'teacher_id':edit_teacher_id, 
                            'program_id':$("#edit_modal_program_id").val(), 
                            'course_id':$("#edit_modal_course_id").val(), 
                            'room_id':$("#edit_modal_room_id").val(), 
                            'student_name_arr':edit_student_name_arr, 
                            'student_eng_name_arr':edit_student_eng_name_arr, 
                            'start_arr':edit_start_arr, 
                            'end_arr':edit_end_arr, 
                            // 'date_start':$("#date_start").val(), 
                            // 'date_end':$("#date_end").val(), 
                            'course_capacity_id':$("#edit_modal_course_capacity_id").val(), 
                        },async:false,
                        success: function (data) {
                            swal("Successfully Create");                  
                          }
                    }).done(function(){
                        // $("#Teacher"+teacher_id+" > div.sched").remove();
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

    });
});



function getScheduleJson(date){

    $.ajax({
              url:'{{{ URL::to("scheduler/getScheduleByGroupDataJson") }}}',
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
            teacherJsonArray = data[1];
            $("#MainDiv").empty();
            populateTimeHeader(timeJsonArray,"<span class='top_text'>"
                +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                +"TIME"
                +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                +"</span> TUTOR");

            var scheduleJsonArray = getScheduleJson(date);
            populateTeacherSchedule(teacherJsonArray, scheduleJsonArray, timeJsonArray);
            
            $.ajax({
                url:'{{{ URL::to("scheduler/get_teacher_class") }}}',
                type:'GET',
                data: {
                    'date' : $("#date").val(),
                },
                dataType: "json",
                async:false,
                success: function (data) 
                { 
                    $.map(data,function(item){
                        $("#sched_"+item.teacher_id+"_"+item.class_id).empty();
                        $("#sched_"+item.teacher_id+'_'+item.class_id).attr('style','background-color:'+item.color+' !important;font-size:15px;color:#003366;');
                        $("#sched_"+item.teacher_id+'_'+item.class_id).append('<b>'+item.duty_type_name+'</b>');
                    });
                }
            });

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
    