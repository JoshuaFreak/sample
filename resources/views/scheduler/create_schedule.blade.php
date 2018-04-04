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
    height: 600px;
    overflow-x: scroll;
    overflow-y: scroll;
    background-color: #eee;
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
                {{{ Lang::get("scheduler.create_schedule_by_student") }}}
            </h3>
        </div>
        </center>

        <div class="col-md-9 form-group" style="margin-left: 3%;margin-right: 3%;">
            <div class="col-md-2">
                <label class="control-label">Select Date</label>
            </div>
            <div class="col-md-3">
                <input id="date" type="date" class="form-control input-sm" value="{{ date('Y-m-d') }}"></input>
            </div>
            <div class="col-md-3">
                <button id="load_sched" class="btn btn-sm btn-primary" type="button"> LOAD </button>
            </div>
        </div> 

        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <input type="hidden" id="is_admin" value="{{{ $is_admin }}}" />
        <input type="hidden" id="is_edit" value="0"/>
        <input type="hidden" id="course_counter" value="1" />
        <center>
            <div class="col-md-12">
                <div id="MainDiv" class="div-table1" style="margin-left: 3%;margin-right: 3%;">
                </div> 
            </div>
        </center>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="timeModal" data-backdrop="static" data-keyboard="false" role="dialog">

    <!-- <div class="modal-dialog" style="width: 1300px !important;"> -->
    <div class="modal-dialog" style="width: 1200px !important;">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color:red !important;">&times;</button>
                <h4 class="modal-title">Add Class</h4>
                <label id="name_of_student" style="color:#008900 !important;"></label> &nbsp;&nbsp;-&nbsp;&nbsp;
                <label id="abrod_start" style="color:#008900 !important;"></label> &nbsp;&nbsp;
                <label id="end_depart" style="color:#008900 !important;"></label>
                <a href="" class="btn btn-primary pull-right" type="button" id="modal_print_student_schedule" target="_blank" style="margin-right: 20px;margin-left: 20px;">Print</a>
                <button class="btn btn-danger pull-right" type="button" id="modal_delete_date">Delete</button>
                <!-- <button class="btn btn-danger pull-right" type="button" id="modal_delete_date" data-toggle="modal" data-target="#timeDeleteDateModal">Delete</button> -->
            </div>
            <div class="modal-body form-group">

                    <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="nav_tab_schedule" role="tablist">

                    <li><a><i class="fa"><button id="add_schedule_tab" class="btn btn-sm btn-primary" type="button">+</button></i></a></li>
                    <li class="active"><a href="#course1" data-toggle="tab" role="tab" id="main_course" align="center">Course 1 <i class="fa"></i></a></li>
                </ul>
                <br>
                <!-- Tab panes -->
                <div class="tab-content" id="tab_content_schedule">
                    <div role="tabpanel" class="tab-pane active" id="course1">
                        <form id="myForm" class="form-horizontal">
                            <div class="col-md-12" style="padding:0px;">
                                <div class="col-md-12" style="border:0px solid #D3D3D3;padding:0px;">
                                    <input type="hidden" id="modal_section_id" value=""/>
                                    <input type="hidden" id="modal_class_id" value=""/>
                                    <input type="hidden" id="student_count" value="1"/>
                                    <input type="hidden" id="student_id_save" value="1"/>
                                    <input type="hidden" id="schedule_id_delete" value="0"/>
                                    <input type="hidden" id="student_nickname_save" value="1"/>
                                    
                                    <!-- <div class="col-md-12 form-group">
                                        <div class="col-md-4">
                                            <label id="class_period" class="control-label"></label>
                                            <input type="hidden" id="class_period_id" value="0"/>
                                        </div>
                                    </div>-->                            
                                    <div class="col-md-12 form-group">
                                        <div class="col-md-1">
                                            <label>Select Date</label>
                                        </div>
                                        <div class="datepicker1 input-daterange input-group col-md-4" id="datepicker_" style="float:left !important;padding-left: 15px !important;padding-right: 15px !important;">
                                                <input type="text" class="date_start form-control" name="start" id="modal_start_date" value="" />
                                                <span class="input-group-addon">to</span>
                                                <input type="text" class="date_end form-control" name="end" id="modal_end_date" value="" />
                                        </div>
                                        <div class="col-md-1">
                                            <label>Course</label>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="modal_program_id" class="form-control input-sm" id="modal_program_id" tabindex="4" style="width:100%;">
                                                <option value="0"></option>
                                                @foreach($program_list as $program)
                                                    <option value="{{ $program ->id }}">{{ $program -> program_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                             <select name="modal_student_personality_id" class="form-control input-sm" id="modal_student_personality_id" tabindex="4" style="width:100%;">
                                                <option value="0" disabled="" selected="">Student Personality</option>
                                                <option value="0"></option>
                                                @foreach($student_personality_list as $student_personality)
                                                    <option value="{{ $student_personality ->id }}">{{ $student_personality -> student_personality_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Class Capacity</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Subject</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Room</label>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <label>Date Range</label>
                                        </div> -->
                                        <div class="col-md-3">
                                            <label>Teacher</label>
                                        </div>
                                    </div>
                                    @for($i = 1;$i <= 11;$i++)
                                        <div class="col-md-12 form-group" id="div_{{$i}}">
                                            <div class="col-md-2">
                                                @if($i == 1)
                                                <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}st</b></span>
                                                @elseif($i == 2)
                                                <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}nd</b></span>
                                                @elseif($i == 3)
                                                <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}rd</b></span>
                                                @else
                                                <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}th</b></span>
                                                @endif
                                                <input type="hidden" class="class_period" value="{{$i}}"></input>
                                                <input type="hidden" class="modal_time" id="modal_time_{{$i}}" value="0"></input>
                                                <input type="hidden" class="modal_time_next" id="modal_time_next_{{$i}}" value="0"></input>
                                                <input type="hidden" class="db_id" id="db_id_{{$i}}" value="0"></input>
                                                <input type="hidden" class="db_batch_id" id="batch_id_{{$i}}" value="0"></input>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="modal_course_capacity_id" class="modal_course_capacity_id form-control input-sm" id="modal_course_capacity_id_{{$i}}" tabindex="4" style="width:100%;" data-counter="{{$i}}">
                                                    <option value="0"></option>
                                                    @foreach($course_capacity_list as $course_capacity)
                                                        <option value="{{ $course_capacity ->id }}" data-capacity="{{ $course_capacity ->capacity }}">{{ $course_capacity -> capacity_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="modal_course_id" class="modal_course_id form-control input-sm" id="modal_course_id_{{$i}}" tabindex="4" style="width:100%;" data-counter="{{$i}}">
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <!-- <select name="modal_room_id" class="modal_room_id form-control input-sm" id="modal_room_id_{{$i}}" tabindex="4" style="width:100%;" data-counter="{{$i}}">
                                                </select> -->
                                                <select name="modal_room_id" class="modal_room_id col-md-12" width="100%" id="modal_room_id_{{$i}}" data-counter="{{$i}}">
                                                </select>
                                            </div>
        <!--                                     <div class="datepicker1 input-daterange input-group col-md-3" id="datepicker_{{$i}}" style="float:left !important;padding-left: 15px !important;padding-right: 15px !important;">
                                                <input type="text" class="date_start form-control" name="start" value="" />
                                                <span class="input-group-addon">to</span>
                                                <input type="text" class="date_end form-control" name="end" value="" />
                                            </div> -->
                                            <!-- <div class="col-md-3" id="scrollable-dropdown-menu">
                                                <input id="modal_teacher_name_id_{{$i}}" type="hidden" data-counter="{{$i}}" class="modal_teacher_name_id">
                                                <input id="modal_teacher_name_{{$i}}" type="text" data-counter="{{$i}}" class="modal_teacher_name typeahead form-control input-sm" style="margin-bottom:10px;">
                                            </div> -->
                                            <div class="col-md-3">
                                                <input id="modal_teacher_name_id_{{$i}}" type="hidden" data-counter="{{$i}}" class="modal_teacher_name_id">
                                                <select id="modal_teacher_name_{{$i}}" data-counter="{{$i}}" class="modal_teacher_name col-md-12">
                                                    
                                                </select>
                                            </div>
                                            <!-- <div class="col-md-3" id="scrollable-dropdown-menu">
                                                <input id="modal_teacher_name_id_{{$i}}" type="hidden" data-counter="{{$i}}" class="modal_teacher_name_id">
                                                <select id="modal_teacher_name_{{$i}}" data-counter="{{$i}}" class="modal_teacher_name typeahead form-control input-sm" style="margin-bottom:10px;">

                                                    <option value="0"></option>                                                   
                                                </select>
                                            </div> -->
                                        </div>
                                    @endfor
                                   <!--  <div class="col-md-12" style="margin-top:10px;margin-bottom:10px;">
                                        <button type="button" class="btn btn-success btn-sm create-class">Create Class</button>
                                    </div> -->
                                    
                                </div>
                                <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                                    <button id="modalCreate" type="button" class="btn btn-success btn-sm btn-ok modalCreate" data-value="1">Save Class</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="add_space"></div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/>
            <br/><br/><br/><br/><br/><br/><br/><br/>
            <br/><br/><br/><br/><br/><br/><br/><br/>
            <br/><br/><br/><br/><br/><br/><br/><br/>
            <br/><br/><br/>
            <!-- <br/><br/><br/> -->
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
                                        <option value=""></option>
                                        @foreach($room_list as $room)
                                            <option value="{{$room -> id}}">{{$room -> room_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" id="scheduler_student_list">
                            </div>
                            <div>
                            </div>
                             <div id="edit_modal_student_names">
                            </div>
                            
                        </div>
                        <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                            <!-- <button id="modalEdit" type="button" class="btn btn-success btn-sm btn-ok">Update Class</button> -->
                            <!-- <button id="modalEditDelete" type="button" class="btn btn-danger btn-sm btn-ok">Delete Class</button> -->
                            <button id="modalEditDeleteAll" type="button" class="btn btn-danger btn-sm btn-ok">Delete Class</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="edit_add_space"></div>
            <br/><br/><br/><br/><br/><br/><br/>
            <br/>
           <!--  <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
                <a id="release_modal" class="btn btn-success btn-ok">Ok</a>

            </div> -->

        </div>
      
    </div>
</div>
<!-- END MODAL -->

<!-- MODAL -->
<div class="modal fade" id="timeDeleteDateModal" role="dialog">

    <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title">Delete Schedule</h4>
            </div>
            <div class="modal-body form-group">
                <form id="myForm" class="form-horizontal">
                    <div class="col-md-12" style="padding:0px;">
                        <div class="col-md-12" style="border:0px solid #D3D3D3;padding:0px;">
                                                 
                            
                            <div class="col-md-12 form-group">
                                <div class="col-md-3">
                                    <label>Date From</label>
                                </div>
                                <div class="col-md-3">
                                    <label id="modal_delete_start_date"></label>
                                </div>
                                <div class="col-md-3">
                                    <label>Date To</label>
                                </div>
                                <div class="col-md-3">
                                    <label id="modal_delete_end_date"></label>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                 <div class="col-md-1 form-group">
                                </div>
                                <div class="col-md-8 form-group">
                                    <select id="schedule_dates" class="form-control">
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                </div>
                                <div class="col-md-3 form-group">
                                    <button class="btn btn-sm btn-primary" id="add_dates_to_delete" type="button">Add Date</button>
                                </div>
                            </div>
                            <fieldset class="form-group col-md-12">Dates to Delete
                                <div class="col-md-12 form-group">
                                </div>
                                <div class="col-md-12 form-group" id="dates_to_delete">
                                
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
            <br/><br/><br/><br/><br/>
            <div class="modal-footer">
                <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                    <button id="modalEditDeleteDates" type="button" class="btn btn-danger btn-sm btn-ok">Delete Date's Schedule</button>
                </div>

            </div>

        </div>
      
    </div>
</div>
<!-- END MODAL -->


<div role="tabpanel" class="hidden" id="template_modal">
    <form id="myForm" class="form-horizontal">
        <div class="col-md-12" style="padding:0px;">
            <div class="col-md-12" style="border:0px solid #D3D3D3;padding:0px;">
                <input type="hidden" id="modal_section_id" value=""/>
                <input type="hidden" id="modal_class_id" value=""/>
                <input type="hidden" id="student_count" value="1"/>
                <input type="hidden" id="student_id_save" value="1"/>
                <input type="hidden" id="schedule_id_delete" value="0"/>
                <input type="hidden" id="student_nickname_save" value="1"/>

                <div class="col-md-12 form-group">
                    <div class="col-md-1">
                        <label>Select Date</label>
                    </div>
                    <div class="datepicker1 input-daterange input-group col-md-4" id="datepicker_" style="float:left !important;padding-left: 15px !important;padding-right: 15px !important;">
                            <input type="text" class="date_start form-control" name="start" id="modal_start_date" value="" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="date_end form-control" name="end" id="modal_end_date" value="" />
                    </div>
                    <div class="col-md-1">
                        <label>Course</label>
                    </div>
                    <div class="col-md-3">
                        <select name="modal_program_id" class="form-control input-sm" id="modal_program_id" tabindex="4" style="width:100%;">
                            <option value="0"></option>
                            @foreach($program_list as $program)
                                <option value="{{ $program ->id }}">{{ $program -> program_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                         <select name="modal_student_personality_id" class="form-control input-sm" id="modal_student_personality_id" tabindex="4" style="width:100%;">
                            <option value="0" disabled="" selected="">Student Personality</option>
                            <option value="0"></option>
                            @foreach($student_personality_list as $student_personality)
                                <option value="{{ $student_personality ->id }}">{{ $student_personality -> student_personality_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-2">
                        <label>Class Capacity</label>
                    </div>
                    <div class="col-md-3">
                        <label>Subject</label>
                    </div>
                    <div class="col-md-2">
                        <label>Room</label>
                    </div>
                    <!-- <div class="col-md-3">
                        <label>Date Range</label>
                    </div> -->
                    <div class="col-md-3">
                        <label>Teacher</label>
                    </div>
                </div>
                @for($i = 1;$i <= 11;$i++)
                    <div class="col-md-12 form-group" id="div_{{$i}}">
                        <div class="col-md-2">
                            @if($i == 1)
                            <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}st</b></span>
                            @elseif($i == 2)
                            <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}nd</b></span>
                            @elseif($i == 3)
                            <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}rd</b></span>
                            @else
                            <label id="period_{{$i}}"></label><span>-<b style="color: #428BCA">{{$i}}th</b></span>
                            @endif
                            <input type="hidden" class="class_period" value="{{$i}}"></input>
                            <input type="hidden" class="modal_time" id="modal_time_{{$i}}" value="0"></input>
                            <input type="hidden" class="modal_time_next" id="modal_time_next_{{$i}}" value="0"></input>
                            <input type="hidden" class="db_id" id="db_id_{{$i}}" value="0"></input>
                            <input type="hidden" class="db_batch_id" id="batch_id_{{$i}}" value="0"></input>
                        </div>
                        <div class="col-md-2">
                            <select name="modal_course_capacity_id" class="modal_course_capacity_id form-control input-sm" id="modal_course_capacity_id_{{$i}}" tabindex="4" style="width:100%;" data-counter="{{$i}}">
                                <option value="0"></option>
                                @foreach($course_capacity_list as $course_capacity)
                                    <option value="{{ $course_capacity ->id }}" data-capacity="{{ $course_capacity ->capacity }}">{{ $course_capacity -> capacity_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="modal_course_id" class="modal_course_id form-control input-sm" id="modal_course_id_{{$i}}" tabindex="4" style="width:100%;" data-counter="{{$i}}">
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="modal_room_id" class="modal_room_id col-md-12" width="100%" id="modal_room_id_{{$i}}" data-counter="{{$i}}">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input id="modal_teacher_name_id_{{$i}}" type="hidden" data-counter="{{$i}}" class="modal_teacher_name_id">
                            <select id="modal_teacher_name_{{$i}}" data-counter="{{$i}}" class="modal_teacher_name col-md-12">
                                
                            </select>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="col-md-12 form-group pull-right" style="margin-bottom:10px;">
                <button id="modalCreate" type="button" class="btn btn-success btn-sm btn-ok modalCreate" data-value="1">Save Class</button>
            </div>
        </div>
    </form>
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

function get_random(list) {
  return list[Math.floor((Math.random()*list.length))];
}

$(function(){

    var a = 0;
    loadSched($("#date").val(),0);
    $("#load_sched").click(function(){
        $("#is_edit").val(1);
        loadSched($("#date").val(),1);
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

            $("#schedule_id_delete").val(schedule_id);
            $("#student_id_save").val(teacher_id);
            $("#modal_print_student_schedule").removeAttr('href');
            $("#modal_print_student_schedule").attr('href','{{ URL::to("student/studentSchedulePdf?student_id=")}}'+teacher_id+'&program_id='+program_id+'&examination_id=null');

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
            $("#schedule_id_delete").val(schedule_id);
            $("#student_id_save").val(teacher_id);
            $("#modal_print_student_schedule").removeAttr('href');
            $("#modal_print_student_schedule").attr('href','{{ URL::to("student/studentSchedulePdf?student_id=")}}'+teacher_id+'&program_id='+program_id+'&examination_id=null');

            $("#edit_modal_room_id [value='"+room_id+"']").attr("selected","selected");
            $("#edit_modal_course_capacity_id [value='"+course_capacity_id+"']").attr("selected","selected");
            $("#edit_modal_course_id [value='"+course_id+"']").attr("selected","selected");

            $("#edit_course_capacity_id").val(course_capacity_id);
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


function deleteClassSchedule()
{
    $("#modalEditDeleteAll").click(function(){
        // var schedule_id = $(this).data('schedule_id');
        // $("#schedule_id_delete").val(schedule_id);
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
                        'schedule_id' : $("#schedule_id_delete").val(),
                        '_token' : $("input[name=_token]").val(),
                    },
                    dataType: "json",
                    async:false,
                    success: function (data) 
                    {            
                        swal("Deleted!", "Your class has been deleted.", "success");  
                        // location.reload();    
                        var teacher_id = $("#student_id_save").val();
                        $("#Teacher"+teacher_id+" > div.sched").remove();
                        $.ajax({
                            url:'{{{ URL::to("scheduler/getTimeDataJson") }}}',
                            type:'GET',
                            data: {'date':$("#date").val()},
                            dataType: "json",
                            async:false,
                            success: function (data) 
                            {                  
                                timeJsonArray = data[0];
                                var teacher_id = $("#student_id_save").val();
                                var sename = $("#student_color_"+teacher_id).data('student_eng_name');
                                var nickname = $("#student_color_"+teacher_id).data('nickname');
                                var abrod_date = $("#student_color_"+teacher_id).data('abrod_date');
                                var end_date = $("#student_color_"+teacher_id).data('end_date');

                                studentJsonArray = [{'student_id':teacher_id,'sename':sename,'date_from':abrod_date,'date_to':end_date,'nickname':nickname}]

                                var scheduleJsonArray = getScheduleStudentByTeacher($("#date").val(),teacher_id);
                                // populateStudentSchedule(studentJsonArray,scheduleJsonArray, timeJsonArray);
                                populateScheduleByStudent(scheduleJsonArray, timeJsonArray,teacher_id,sename);
                                // populateScheduleByTeacher(scheduleJsonArray, timeJsonArray, teacher_id);
                                $("#timeEditModal").modal('hide');
                                // location.reload();
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

                                    $("#schedule_id_delete").val(schedule_id);
                                    $("#student_id_save").val(teacher_id);
                                    $("#modal_print_student_schedule").removeAttr('href');
                                    $("#modal_print_student_schedule").attr('href','{{ URL::to("student/studentSchedulePdf?student_id=")}}'+teacher_id+'&program_id='+program_id+'&examination_id=null');

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
                            }  

                        });
                    }  
                });
            });
    });
}



var modal_counter = 0;
function call_sched(program_id,date,date_end,course_counter)
{
    // var program_id = $(this).data('program_id');
    // var course_counter_call = $(this).data('counter');
    $("#li_"+course_counter).removeAttr('onclick');
    var form = $("#course"+course_counter).find('form');
    var date_from = $("#course"+course_counter).data('date_from');
    var date_to = $("#course"+course_counter).data('date_to');
    var student_id = $(form).find('#student_id_save').val();
    // $(form).find("#modal_start_date").val(date_from);
    // $(form).find("#modal_end_date").val(date_to);
    $.ajax({
          url:'{{{ URL::to("scheduler/getStudentScheduleByCourse") }}}',
          type:'GET',
          dataType: "json",
          async:false,
          data:
              {  
                  'student_id': student_id,
                  'program_id': program_id,
                  'date_from': date_from,
                  'date_to': date_to,
              },
          success: function (data_sched) 
          { 
                var modal_count_loop = 0;

                // $(form).find("#modal_program_id [value='0']").attr("selected","selected");
                // $(form).find("#modal_student_personality_id [value='0']").attr("selected","selected");
                // $(form).find("#modal_start_date").val("");
                // $(form).find("#modal_end_date").val("");
                // $(form).find(".modal_course_id").each(function(){
                //     $(this).empty();
                // });
                // $(form).find(".modal_room_id").each(function(){
                //     $(this).empty();
                // });
                // $(form).find(".modal_teacher_name").each(function(){
                //     // $(this).val("");
                //     $(this).empty();
                // });
                // $(form).find(".modal_course_capacity_id").each(function(){
                //     $(".modal_course_capacity_id [value='0']").attr("selected","selected");
                // });
                // $(form).find(".db_id").each(function(){
                //     $(this).val(0);
                // });

                $.map(data_sched['data'],function(item){
                    // alert();
                    $("#is_edit").val(1);
                    modal_count_loop++;
                    if(modal_count_loop == 1)
                    {
                        $(form).find("#modal_start_date").val(item.date_from);
                        $(form).find("#modal_end_date").val(item.date_to);
                        selectListChange('modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': item.program_id },'modal_course_capacity_id' ,'Please select a Program');

                        $(form).find("#modal_program_id [value='"+item.program_id+"']").attr("selected","selected");
                        $(form).find("#modal_student_personality_id [value='"+item.student_personality_id+"']").attr("selected","selected");

                    }
                    $(form).find("#db_id_"+item.class_id).val(item.id);
                    $(form).find("#batch_id_"+item.class_id).val(item.batch_id);
                    $(form).find("#modal_course_capacity_id_"+item.class_id+" [value='"+item.course_capacity_id+"']").attr("selected","selected");
                    $(form).find("#modal_course_id_"+item.class_id+" [value='0']").removeAttr("selected","selected");
                    $(form).find("#modal_course_id_"+item.class_id+" [value='"+item.course_id+"']").attr("selected","selected");

                    data = item.course_capacity_id;

                    if(data != 1 && data != 5 && data != 6)
                    {   
                        data = 8;
                    }

                    // if(data == 7)
                    // {
                    //     data = 5;
                    // }

                    
                    $(form).find('#modal_room_id_'+item.class_id).empty();
                    $(form).find('#modal_room_id_'+item.class_id).append('<option value="0"></option>');
                    var data1 = data_sched['schedule'][item.class_id];

                    $.map(data1, function (value) 
                    {  
                            $(form).find('#modal_room_id_'+item.class_id).append($("<option/>").val(value.value).text(value.text));
                    });
                    // selectListRoomSpaceChange('modal_room_id_'+item.class_id,'{{{URL::to("scheduler/courseCapacityRoomDataJson")}}}',  { 'course_capacity_id': data } ,'Please select a Class Capacity');

                    $(form).find("#modal_room_id_"+item.class_id+" [value='"+item.room_id+"']").attr("selected","selected");
                    if(item.first_name != null && item.last_name != null)
                    {
                        // $("#modal_teacher_name_"+item.class_id).val(item.first_name+" "+item.last_name+" - "+item.nickname);
                        $(form).find("#modal_teacher_name_"+item.class_id).append('<option value="'+item.teacher+'">'+item.first_name+" "+item.last_name+" - "+item.nickname+'</option>');
                        $(form).find("#modal_teacher_name_"+item.class_id+" [value='"+item.teacher+"']").attr("selected","selected");
                        $(form).find("#modal_teacher_name_"+item.class_id).attr("data-teacher_id",item.teacher);
                        $(form).find("#modal_teacher_name_id_"+item.class_id).val(item.teacher);
                    }

                    if(data != 5 && data != 6)
                    { 
                        var data2 = data_sched['available_teacher_arr_period'][item.class_id];
                        $.map(data2, function (value) 
                        {  
                                $(form).find('#modal_teacher_name_'+item.class_id).append($("<option/>").val(value.teacher_id).text(value.teacher_name));
                        });
                    }
                });

          }  

    });

    for(var i = 1;i <= 11;i++)
    {
        $(form).find("#modal_room_id_"+i).select2(
        {
          placeholder: "Select Room",
          allowClear: true
        });

        $(form).find("#modal_teacher_name_"+i).select2(
        {
          placeholder: "Select Teacher",
          allowClear: true
        });
    }
}
function loadSched(date,condition){

    
    $.ajax({
        url:'{{{ URL::to("scheduler/getTimeDataJson") }}}',
        cache: false,
        type:'GET',
        data: {'date':date},
        dataType: "json",
        async:false,
        success: function (data) 
        {                  
            timeJsonArray = data[0];
            count_time = 1;
            count_time1 = 1;
            $(jQuery.parseJSON(JSON.stringify(timeJsonArray))).each(function() { 
                var timeStart = this;
                var timeEnd = getTimeByOrderNo(timeStart.order_no+1, timeJsonArray);

                // if(timeEnd != null && count_time % 2 < 1 || count_time == 1){
                if(timeStart.order_no % 2 > 0){
                    $("#modal_time_"+count_time1).val(timeStart.id);
                    $("#period_"+count_time1).text(timeStart.time+' '+timeStart.time_session+' '+timeEnd.time+' '+timeEnd.time_session);
                    $("#modal_time_next_"+count_time1).val(timeEnd.id); 
                    count_time1++;    
                }

                count_time++;            
            });

            scheduleJsonArray = [];
            studentJsonArray = [];
            $.ajax({
                // url:'{{ URL::to("http://cebucia.com/api/student_list_check_in.php") }}',
                url:'{{ URL::to("scheduler/studentListLocal") }}',
                type:'GET',
                data: {
                    "_token": $("input[name=_token]").val(),
                    // "_token": "L3tM3P@55!",
                },
                dataType: "json",
                async:false,
                success: function (data) 
                {                  
                    // studentJsonArray = data.data;
                    studentJsonArray = data;
                }  

            });
            $("#MainDiv").empty();
            populateTimeHeader(timeJsonArray,"<span class='top_text'>"
                +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                +"TIME"
                +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                +"</span> Student Name");

            var scheduleJsonArray = getScheduleJson(date);
            // var scheduleJsonArray = [];
            populateStudentSchedule(studentJsonArray, scheduleJsonArray, timeJsonArray);

                var is_admin = $("#is_admin").val();
                if(is_admin == 0)
                {
                    $(".student_data").each(function(){
                        $(this).removeAttr('data-target');
                    });
                    $(".edit_schedule").each(function(){
                        $(this).removeClass('edit_schedule');
                    });
                }
        }
    });
    var modal_count_loop = 0;
    $(".student_name_block").click(function(){
        modal_count_loop = 0;
        condition++;
        for($i = 1; 10 > $i; $i++)
        {
            
            // $("#modal_teacher_name_"+$i).val("");
            $("#modal_teacher_name_"+$i).empty();
            $("#modal_course_capacity_id_"+$i+" [value='0']").attr("selected","selected");
            $("#modal_room_id_"+$i).empty();
        }
    });

    var modal_count = 1;

    $(".student_data").click(function(){

        $("#student_id_save").val($(this).data('student'));

        $("#modal_print_student_schedule").removeAttr('href');
        $("#modal_print_student_schedule").attr('href','{{ URL::to("student/studentSchedulePdf?student_id=")}}'+$(this).data('student')+'&program_id='+$("#modal_program_id").val()+'&examination_id=null');

        $("#student_nickname_save").val($(this).data('nickname'));
            var name_of_student = $(this).data('student_eng_name');
            var abrod_start = $(this).data('abrod_date');
            var end_depart = $(this).data('end_date');
            $("#name_of_student").text("Name: "+name_of_student);
            $("#abrod_start").text("Departure Date: "+abrod_start);
            $("#end_depart").text("End Date: "+end_depart);
    });
    $("#modal_delete_date").click(function(){

        var start = $("#modal_start_date").val();
        var end = $("#modal_end_date").val();

        $("#modal_delete_start_date").text(start);
        $("#modal_delete_end_date").text(end);

        $("#schedule_dates").empty();

        count_dates = 0;
        $("#schedule_dates").append('<option value="0"></option>');
        for(var date = moment(start); date.diff(end) <= 0; date.add('days', 1))
        {
            count_dates++;
            var date1 = new Date(date);
            var year = date1.getFullYear();
            var month = date1.getMonth() + 1;
            var day = date1.getDate();

            day_length = day.toString.length;
            month_length = month.toString.length;

            if(month_length == 1)
            {
                month = '0'+month;
            }

            if(day_length == 1)
            {
                day = '0'+day;
            }

            $("#schedule_dates").append('<option value="'+count_dates+'">'+year+'-'+month+'-'+day+'</option>');
        }

        // $("#add_dates_to_delete").click(function(){
        //     date = $("#schedule_dates option:selected").text();
        //     val = $("#schedule_dates option:selected").val();

        //     $("#schedule_dates option[value='"+val+"']").attr('disabled','disabled');
        //     $("#schedule_dates [value='0']").attr('selected','selected');

        //     $("#dates_to_delete").append('<div id="remove_div_dates_'+val+'" class="col-md-4 form-group"><label class="dates_data_to_delete">'+date+'</label> <button type="button" class="btn btn-sm btn-default remove_btn_dates" data-id="'+val+'">X</button></div>');

        //     $(".remove_btn_dates").click(function(){
        //         id = $(this).data('id');
        //         $("#remove_div_dates_"+id).remove();
        //         $("#schedule_dates option[value='"+val+"']").removeAttr('disabled');
        //     });
        // });
    });

    deleteClassSchedule();

    var count_modal_dates = 0;
    $('#timeDeleteDateModal').on('shown.bs.modal', function(e) {

        $("#dates_to_delete").empty();
        if(count_modal_dates == 0 && condition == 0)
        {
            count_modal_dates++;
            // if(count_modal_dates == 0)
            // {

                $("#modalEditDeleteDates").click(function(){
                    counter_dates = 0;
                    dates = [];
                    $(".dates_data_to_delete").each(function(){
                        date = $(this).text();
                        dates[counter_dates] = date;
                        counter_dates++;
                    });

                    if(counter_dates > 0)
                    {
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
                                    url:'{{{ URL::to("scheduler/deleteClassDates") }}}',
                                    type:'POST',
                                    data: {
                                        'date' : dates,
                                        'student_id' : $("#student_id_save").val(),
                                        'program_id' : $("#modal_program_id").val(),
                                        '_token' : $("input[name=_token]").val(),
                                    },
                                    dataType: "json",
                                    async:false,
                                    success: function (data) 
                                    {        
                                        swal("Deleted!", "Your schedules has been deleted.", "success"); 
                                        location.reload();         
                                    }  
                                });
                            });
                    }
                });
            // }
                $("#add_dates_to_delete").click(function(){
                    date = $("#schedule_dates option:selected").text();
                    val = $("#schedule_dates option:selected").val();
                    if(val != 0)
                    {
                        $("#dates_to_delete").append('<div id="remove_div_dates_'+val+'" class="col-md-4 form-group"><label class="dates_data_to_delete">'+date+'</label> <button type="button" class="btn btn-sm btn-default remove_btn_dates" data-id="'+val+'">X</button></div>');

                        $("#schedule_dates option[value='"+val+"']").attr('disabled','disabled');
                    }


                    $("#schedule_dates [value='0']").attr('selected','selected');

                    $(".remove_btn_dates").click(function(){
                        id = $(this).data('id');
                        $("#remove_div_dates_"+id).remove();
                        $("#schedule_dates option[value='"+id+"']").removeAttr('disabled');
                    });
                });
        }
    });

    if(modal_counter == 0)
    {
        $('#timeModal').on('hidden.bs.modal', function () {
            $('#nav_tab_schedule li').not('li:first, li:nth-child(2)').remove();
            // $('#tab_content_schedule div').not('div:first').remove();/
            $('#tab_content_schedule div.tab-pane').not(':first').remove();
            // $('#tab_content_schedule div.tab-pane').addClass('active');
            $('#nav_tab_schedule li').not('li:first, li:nth-child(2)').remove();
            $("#course_counter").val(1);
        })

        $('#timeModal').on('shown.bs.modal', function(e) {

            $("#modal_program_id [value='0']").attr("selected","selected");

            // student_id = $(e.relatedTarget).data('student');
            if(modal_count == 1)
            {
                modal_count = $(e.relatedTarget).data('modal_count');
            }
            // $("#student_id_save").val($(e.relatedTarget).data('student'));
            student_id = $("#student_id_save").val();
            student_nickname = $("#student_nickname_save").val();
            date = $("#date").val();

            datepicker(1);
            
                // var class_period = $(e.relatedTarget).data('class_id');
                // var abrod_start = $(e.relatedTarget).data('abrod_date');
                // var end_depart = $(e.relatedTarget).data('end_date');
                // var student_id = $(e.relatedTarget).data('student');
                var student_eng_name = $(e.relatedTarget).data('student_eng_name');
                // var time_in_id = $(e.relatedTarget).data('time');
                // var time_out_id = $(e.relatedTarget).data('time_next');

                
                // $("#abrod_start").text("Departure Date: "+abrod_start);
                // $("#end_depart").text("End Date: "+end_depart);
                // $("#class_period").text("Period "+class_period);
                // $("#class_period_id").val(class_period);

            if(modal_count_loop == 0)
            {
                $(".modal_course_capacity_id").change(function(){
                    var counter = $(this).data('counter');
                    var capacity = $("#modal_course_capacity_id_"+counter+" option:selected").data('capacity');

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


                    
                        // $("#modal_add_student").click(function(){

                        //     var student_count = $("#student_count").val();
                        //     student_count++;
                        //     $("#student_count").val(student_count);
                        //     $("#modal_student_names").append(''
                        //         +'<div class="col-md-12 form-group">'
                        //             +'<div class="col-md-4">'
                        //                 +'<label>Search Student Name</label>'
                        //             +'</div>'
                        //             +'<div class="col-md-8">'
                        //                 +'<input id="modal_student_name_'+student_count+'" type="text" class=" modal_teacher_name typeahead form-control input-sm" style="margin-bottom:10px;">'
                        //             +'</div>'
                        //         +'</div>'
                        //         +'<div class="col-md-12 form-group">'
                        //             +'<label class="col-md-4" for="from">Date Range</label>'
                        //             +'<div class="input-daterange input-group col-md-8" id="datepicker'+student_count+'" style="padding-left: 15px !important;padding-right: 15px !important;">'
                        //                 +'<input type="text" class="date_start form-control" name="start" value="" />'
                        //                 +'<span class="input-group-addon">to</span>'
                        //                 +'<input type="text" class="date_end form-control" name="end" value="" />'
                        //            +'</div>'
                        //         +'</div>'
                        //     +'');
                        //     datepicker(student_count);
                        //     $("#add_space").append("<br/><br/><br/><br/><br/><br/>");

                        //      /********
                        //         START OF teacher_name ->  typeahead
                        //     *************************************************************************/
                        //         var teacher_list = new Bloodhound({
                        //                 datumTokenizer: function (datum) {
                        //                     //return Bloodhound.tokenizers.whitespace(datum.teacher_name);
                        //                        var tokens = [];
                        //                         //the available string is 'name' in your datum
                        //                         var stringSize = datum.teacher_name.length;
                        //                         //multiple combinations for every available size
                        //                         //(eg. dog = d, o, g, do, og, dog)
                        //                         for (var size = 1; size <= stringSize; size++){
                        //                           for (var i = 0; i+size<= stringSize; i++){
                        //                               tokens.push(datum.teacher_name.substr(i, size));
                        //                           }
                        //                         }

                        //                         return tokens;


                        //                 },
                        //                 queryTokenizer: Bloodhound.tokenizers.whitespace,
                        //                 limit: 10,

                        //                 remote:{
                        //                   url:'{{ URL::to("teacher/teacherDataJson?query=%QUERY") }}'+"&date="+$("check_out_date").val(),
                        //                   filter: function (teacher_list) {
                        //                             return $.map(teacher_list, function (data) {
                        //                                 // console.log(data); //debugging
                        //                                 return {
                        //                                     teacher_name: data.first_name+' '+data.last_name,
                        //                                     id: data.teacher_id,
                        //                                     // examination_id: teacher.examination_id
                        //                                 };
                        //                             });
                        //                      }
                        //                 }

                        //         });

                        //         teacher_list.initialize();
                        //         console.log(teacher_list);

                        //             $('#modal_student_name_'+student_count).typeahead(
                        //             {
                        //               hint: true,
                        //               highlight: true,
                        //               minLength: 1
                        //             },
                        //             {
                        //                 teacher_name: 'teacher_list',
                        //                 displayKey: 'teacher_name',
                        //                 source: teacher_list.ttAdapter()
                        //             }

                        //             ).bind("typeahead:selected", function(obj, teacher, account_no) {
                        //                 // console.log(teacher);
                        //                 $(this).attr('data-teacher_id',teacher.id);
                        //                 $(this).attr('data-teacher_name',teacher.student_eng_name);
                        //                 $("#teacher_id").val(teacher.id);
                        //                 $(this).val(teacher.teacher_name);
                        //                 // $("#examination_id [value='"+teacher.examination_id+"']").attr("selected","selected");
                        //                // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search teacher') 
                                        
                        //             });//endbind


                        // });
                if(condition == 0)
                {
                    /********
                        START OF teacher_name ->  typeahead
                    *************************************************************************/
                    var teacher_list = new Bloodhound({
                            datumTokenizer: function (datum) {
                                //return Bloodhound.tokenizers.whitespace(datum.teacher_name);
                                   var tokens = [];
                                    //the available string is 'name' in your datum
                                    var stringSize = datum.teacher_name.length;
                                    //multiple combinations for every available size
                                    //(eg. dog = d, o, g, do, og, dog)
                                    for (var size = 1; size <= stringSize; size++){
                                      for (var i = 0; i+size<= stringSize; i++){
                                          tokens.push(datum.teacher_name.substr(i, size));
                                      }
                                    }

                                    return tokens;


                            },
                            queryTokenizer: Bloodhound.tokenizers.whitespace,
                            limit: 100,

                            remote:{
                                // url:'{{ URL::to("http://cebucia.com/api/get_student_scheduler.php?query=%QUERY&_token=L3tM3P@55!") }}',
                                // url:'{{ URL::to("http://cebucia.com/api/get_student_scheduler.php?_token=L3tM3P@55!&query=%QUERY") }}',
                                // url:'{{ URL::to("scoreEntry/dataJson?query=%QUERY") }}',
                                url:'{{ URL::to("teacher/teacherDataJson?query=%QUERY") }}'+"&date="+$("#date").val()+"&room_id="+$("#modal_room_id_").val(),
                              filter: function (teacher_list) {

                                        // return $.map(teacher_list, function (teacher) {
                                        //     console.log(teacher); //debugging
                                        //     return {
                                        //         teacher_name: teacher.last_name + ' '+teacher.first_name + ' ' + teacher.middle_name + ' - ( ' + teacher.student_english_name+')',
                                        //         id: teacher.id,
                                        //         examination_id: teacher.examination_id
                                        //     };
                                        // });
                                        return $.map(teacher_list, function (data) {
                                            console.log(data); //debugging
                                            return {
                                                teacher_name: data.first_name+' '+data.last_name +' - '+data.nickname,
                                                id: data.teacher_id,
                                                // examination_id: teacher.examination_id
                                            };
                                        });
                                 }
                            }

                    });

                    teacher_list.initialize();
                    console.log(teacher_list);


                        $('.modal_teacher_name1').typeahead(
                        {
                          hint: true,
                          highlight: true,
                          minLength: 1
                        },
                        {
                            teacher_name: 'teacher_list',
                            displayKey: 'teacher_name',
                            source: teacher_list.ttAdapter()

                        }

                        ).bind("typeahead:selected", function(obj, teacher, account_no) {
                            // console.log(teacher);
                            $(this).attr('data-teacher_id',teacher.id);
                            data_counter = $(this).data('counter');
                            $("#modal_teacher_name_id_"+data_counter).val(teacher.id);
                            // $(this).attr('data-teacher_name',teacher.teacher_name);
                            // $("#teacher_id").val(teacher.id);
                            $(this).val(teacher.teacher_name);
                            // $("#examination_id [value='"+teacher.examination_id+"']").attr("selected","selected");
                           // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search teacher') 
                            
                        });//endbind
                    
                    // typeaheadStudent();
                }

                    


                $("#modal_program_id").change(function(){

                    // $(".modal_room_id").select2();
                    // $(".modal_teacher_name").select2();
                    // $(".modal_room_id").each(function(){
                    //     $(this).removeClass('form-control');
                    // });
                    // $(".modal_teacher_name").each(function(){
                    //     $(this).removeClass('form-control');
                    // });

                    is_edit = $("#is_edit").val();
                    if(is_edit == 0)
                    {
                        
                        var date = $("#modal_start_date").val();
                        var date_end = $("#modal_end_date").val();
                        // var date = $("#abrod_start").text();
                        // date = date.replace("Departure Date: ", "");
                        selectListChangeClass('modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': $("#modal_program_id").val() },'modal_course_capacity_id' ,'Please select a Program',date,date_end,1);


                        $("#modal_print_student_schedule").removeAttr('href');
                        $("#modal_print_student_schedule").attr('href','{{ URL::to("student/studentSchedulePdf?student_id=")}}'+$("#student_id_save").val()+'&program_id='+$("#modal_program_id").val()+'&examination_id=null');
                    }


                        // $.ajax({
                        //     url:'{{{ URL::to("scheduler/checkVacantSchedule") }}}',
                        //     type:'GET',
                        //     data: {
                        //         'program_id' : $(this).val(),
                        //     },
                        //     dataType: "json",
                        //     async:false,
                        //     success: function (data) 
                        //     {            
                                
                        //     }  
                        // });
                });
                $(".modal_course_capacity_id").change(function(){
                    var counter = $(this).data('counter');

                    data = $("#modal_course_capacity_id_"+counter).val();
                    id = $("#modal_course_capacity_id_"+counter+" option:selected").data('capacity');

                    // if(id > 1)
                    // {   
                        if(data != 1 && data != 5 && data != 6 && data != 0)
                        {   
                            data = 8;
                        }

                        // if(data == 7)
                        // {
                        //     data = 5;
                        // }

                    // }

                    // var counter = $(this).data('counter');
                    var date = $("#modal_start_date").val();
                    var date_end = $("#modal_end_date").val();
                    
                    // var date = $("#abrod_start").text();
                    // date = date.replace("Departure Date: ", "");
                    
                    selectListRoomSpaceChange('modal_room_id_'+counter,'{{{URL::to("scheduler/courseCapacityRoomTeacherDataJson")}}}',  { 'course_capacity_id': data, 'class_id': counter, 'date': date,'date_end': date_end } ,'Please select a Class Capacity',counter);
                });
                $("#add_schedule_tab").click(function(){
                    var course_counter = $("#course_counter").val();
                    course_counter++;
                    $("#course_counter").val(course_counter);
                    $("#nav_tab_schedule").append('<li><a href="#course'+course_counter+'" data-toggle="tab" role="tab">Course '+course_counter+'<i class="fa"></i></a></li>');
                    var html = $("#course1").html();
                    $("#tab_content_schedule").append('<div role="tabpanel" class="tab-pane" id="course'+course_counter+'">'+html+'</div>');
                    $("#course"+course_counter+" > form")[0].reset();
                    $("#course"+course_counter+" > form").each(function(){

                            $(this).find("#modal_program_id [value='0']").attr("selected","selected");
                            $(this).find("#modal_student_personality_id [value='0']").attr("selected","selected");
                            $(this).find("#modal_start_date").val("");
                            $(this).find("#modal_end_date").val("");
                            $(this).find(".modal_course_id").each(function(){
                                $(this).empty();
                            });
                            $(this).find(".modal_room_id").each(function(){
                                $(this).find('.select2-chosen').text("");
                            });
                            $(this).find(".modal_teacher_name").each(function(){
                                $(this).find('.select2-chosen').text("");
                            });
                            $(this).find(".modal_course_capacity_id").each(function(){
                                // $(this).empty();
                                var form = $(this).closest('form');

                                $(form).find(".modal_course_capacity_id").each(function(){
                                        $(this).removeAttr('selected');
                                });

                                $(form).find(".modal_course_capacity_id [value='0']").attr("selected","selected");
                            });
                            $(this).find(".db_id").each(function(){
                                $(this).val(0);
                            });

                                $(this).find("#modal_program_id").change(function(){
                                    var form = $(this).closest('form');
                                    var course_capacity_data_arr = [];
                                    var capacity_count_arr = 0;
                                    var room_count_arr = 0;
                                    var teacher_name_count_arr = 0;

                                    var date = $(form).find("#modal_start_date").val();
                                    var date_end = $(form).find("#modal_end_date").val();
                                    // var date = $("#abrod_start").text();
                                    // date = date.replace("Departure Date: ", "");
                                    selectListChangeClass('modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': $(this).val() },'modal_course_capacity_id' ,'Please select a Program',date,date_end,course_counter);

                                    // $(form).find(".modal_course_capacity_id").each(function(){
                                    //     course_capacity_data_arr[capacity_count_arr] = $(this).val();
                                    //     capacity_count_arr++;
                                    // });
                                    // $(form).find(".modal_room_id").each(function(){
                                    //     if(course_capacity_data_arr[room_count_arr] != undefined)
                                    //     {
                                    //         $(this).empty();
                                    //     }
                                    //     room_count_arr++;
                                    // });
                                    // $(form).find(".modal_teacher_name").each(function(){
                                    //     if(course_capacity_data_arr[teacher_name_count_arr])
                                    //     {
                                    //         $(this).empty();
                                    //     }
                                    //     teacher_name_count_arr++;
                                    // });
                                });
                                $(this).find(".modal_course_capacity_id").change(function(){
                                    var counter = $(this).data('counter');

                                    data = $(this).find("#modal_course_capacity_id_"+counter).val();
                                    id = $(this).find("#modal_course_capacity_id_"+counter+" option:selected").data('capacity'); 
                                    if(data != 1 && data != 5 && data != 6)
                                    {   
                                        data = 8;
                                    }
                                    var date = $("#modal_start_date").val();
                                    var date_end = $("#modal_end_date").val();
                                    
                                    // var date = $("#abrod_start").text();
                                    // date = date.replace("Departure Date: ", "");
                                    
                                        selectListRoomSpaceChange('modal_room_id_'+counter,'{{{URL::to("scheduler/courseCapacityRoomTeacherDataJson")}}}',  { 'course_capacity_id': data, 'class_id': counter, 'date': date,'date_end': date_end } ,'Please select a Class Capacity',counter);
                                    
                                });
                    });
                    datepicker(1);

                    $(".modalCreate").click(function(){

                        $(".tab-pane").each(function(){

                            // var form = $(this).parent('form');
                            var tab = this;
                            var teacher_name_arr = [];
                            var student_eng_name_arr = [];
                            var modal_course_capacity_arr = [];
                            var modal_course_arr = [];
                            var modal_room_arr = [];
                            var db_batch_id_arr = [];
                            var db_id_arr = [];
                            var start_arr = [];
                            var end_arr = [];
                            var modal_time_arr = [];
                            var modal_time_next_arr = [];
                            var class_period_arr = [];
                            name_count = 0;
                            name_counter = 1;
                            modal_course_capacity_id_count = 0;
                            modal_course_id_count = 0;
                            modal_room_id_count = 0;
                            db_id_count = 0;
                            db_batch_id_count = 0;
                            date_start_count = 0;
                            date_end_count = 0;
                            modal_time_count = 0;
                            modal_time_next_count = 0;
                            class_period_count = 0;

                            $(this).find("form .modal_course_capacity_id").each(function(){


                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    if($(tab).find("#modal_teacher_name_"+count).data('teacher_id') != undefined){
                                        // teacher_name_arr[name_count] = $("#modal_teacher_name_"+count).data('teacher_id');
                                        // teacher_name_arr[name_count] = $("#modal_teacher_name_id_"+count).val();
                                        // teacher_name_arr[name_count] = $("#modal_teacher_name_"+count).val();
                                        teacher_name_arr[name_count] = $(tab).find("#modal_teacher_name_"+count).select2("val");
                                        name_count++;
                                    }
                                    else
                                    {
                                        teacher_name_arr[name_count] = "";
                                        name_count++;
                                    }
                                }

                            });

                            $(this).find("form .modal_course_capacity_id").each(function(){
                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    modal_course_capacity_arr[modal_course_capacity_id_count] = $(tab).find("#modal_course_capacity_id_"+count+" option:selected").val();
                                    modal_course_capacity_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $(this).find("form .modal_course_id").each(function(){
                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    modal_course_arr[modal_course_id_count] = $(tab).find("#modal_course_id_"+count+" option:selected").val();
                                    modal_course_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $(this).find("form .modal_room_id").each(function(){
                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    modal_room_arr[modal_room_id_count] = $(tab).find("#modal_room_id_"+count+" option:selected").val();
                                    modal_room_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            $(this).find("form .db_id").each(function(){
                                if($(this).val() != undefined){
                                    db_id_arr[db_id_count] = $(this).val();
                                    db_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $(this).find("form .db_batch_id").each(function(){
                                if($(this).val() != undefined){
                                    db_batch_id_arr[db_batch_id_count] = $(this).val();
                                    db_batch_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .date_start").each(function(){
                                if($(this).val() != undefined){
                                    start_arr[date_start_count] = $(this).val();
                                    date_start_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .date_end").each(function(){
                                if($(this).val() != undefined){
                                    end_arr[date_end_count] = $(this).val();
                                    date_end_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .modal_time").each(function(){
                                if($(this).val() != undefined){
                                    modal_time_arr[modal_time_count] = $(this).val();
                                    modal_time_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .modal_time_next").each(function(){
                                if($(this).val() != undefined){
                                    modal_time_next_arr[modal_time_next_count] = $(this).val();
                                    modal_time_next_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .class_period").each(function(){
                                if($(this).val() != undefined){
                                    class_period_arr[class_period_count] = $(this).val();
                                    class_period_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $.ajax({
                                url:'{{{ URL::to("scheduler/createStudent")}}}',
                                type:'post',
                                data:{ 
                                    '_token': $("input[name=_token]").val(),    
                                    // 'time_id':time_in_id, 
                                    // 'time_out':time_out_id, 
                                    'student_id':$("#student_id_save").val(), 
                                    'student_nickname':$("#student_nickname_save").val(), 
                                    'program_id':$(tab).find("#modal_program_id").val(), 
                                    'student_personality_id':$(tab).find("#modal_student_personality_id").val(), 
                                    // 'course_id':$("#modal_course_id").val(), 
                                    'room_id':$("#modal_room_id").val(), 
                                    'teacher_name_arr':teacher_name_arr, 
                                    'student_eng_name':student_eng_name, 
                                    'db_id_arr':db_id_arr, 
                                    'batch_id_arr':db_batch_id_arr, 
                                    'start_arr':start_arr, 
                                    'end_arr':end_arr, 
                                    'modal_course_capacity_arr':modal_course_capacity_arr, 
                                    'modal_room_arr':modal_room_arr, 
                                    'modal_course_arr':modal_course_arr, 
                                    'modal_time_arr':modal_time_arr, 
                                    'modal_time_next_arr':modal_time_next_arr, 
                                    'class_period_arr':class_period_arr, 
                                    // 'date_start':$("#date_start").val(), 
                                    // 'date_end':$("#date_end").val(), 
                                    // 'course_capacity_id':$("modal_course_capacity_id").val(), 
                                },async:false,
                                success: function (data) {
                                    swal("Successfully Create");                  
                                  }
                            }).done(function(){
                                var teacher_id = $("#student_id_save").val();
                                $("#Teacher"+teacher_id+" > div.sched").remove();
                                $.ajax({
                                    url:'{{{ URL::to("scheduler/getTimeDataJson") }}}',
                                    type:'GET',
                                    data: {'date':$("#date").val()},
                                    dataType: "json",
                                    async:false,
                                    success: function (data) 
                                    {                  
                                        timeJsonArray = data[0];
                                        var teacher_id = $("#student_id_save").val();
                                        var sename = $("#student_color_"+teacher_id).data('student_eng_name');
                                        var nickname = $("#student_color_"+teacher_id).data('nickname');
                                        var abrod_date = $("#student_color_"+teacher_id).data('abrod_date');
                                        var end_date = $("#student_color_"+teacher_id).data('end_date');

                                        studentJsonArray = [{'student_id':teacher_id,'sename':sename,'date_from':abrod_date,'date_to':end_date,'nickname':nickname}]

                                        var scheduleJsonArray = getScheduleStudentByTeacher($("#date").val(),teacher_id);
                                        // populateStudentSchedule(studentJsonArray,scheduleJsonArray, timeJsonArray);
                                        populateScheduleByStudent(scheduleJsonArray, timeJsonArray,teacher_id,sename);
                                        // populateScheduleByTeacher(scheduleJsonArray, timeJsonArray, teacher_id);
                                        $("#timeModal").modal('hide');
                                        
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

                                            $("#schedule_id_delete").val(schedule_id);
                                            $("#student_id_save").val(teacher_id);

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
                                        // location.reload();
                                    }  

                                });
                            });

                        });
                    });

                });
            }

            // var teacher_name_arr = [];
            // var student_eng_name_arr = [];
            // var modal_course_capacity_arr = [];
            // var modal_course_arr = [];
            // var modal_room_arr = [];
            // var db_batch_id_arr = [];
            // var db_id_arr = [];
            // var start_arr = [];
            // var end_arr = [];
            // var modal_time_arr = [];
            // var modal_time_next_arr = [];
            // var class_period_arr = [];
            // name_count = 0;
            // name_counter = 1;
            // modal_course_capacity_id_count = 0;
            // modal_course_id_count = 0;
            // modal_room_id_count = 0;
            // db_id_count = 0;
            // db_batch_id_count = 0;
            // date_start_count = 0;
            // date_end_count = 0;
            // modal_time_count = 0;
            // modal_time_next_count = 0;
            // class_period_count = 0;

            if(modal_count_loop == 0)
            {
                modal_count_loop++;  

                // if(condition == 1)
                // {

                    $(".modalCreate").click(function(){

                        $(".tab-pane").each(function(){

                            // var form = $(this).parent('form');
                            var tab = this;
                            var teacher_name_arr = [];
                            var student_eng_name_arr = [];
                            var modal_course_capacity_arr = [];
                            var modal_course_arr = [];
                            var modal_room_arr = [];
                            var db_batch_id_arr = [];
                            var db_id_arr = [];
                            var start_arr = [];
                            var end_arr = [];
                            var modal_time_arr = [];
                            var modal_time_next_arr = [];
                            var class_period_arr = [];
                            name_count = 0;
                            name_counter = 1;
                            modal_course_capacity_id_count = 0;
                            modal_course_id_count = 0;
                            modal_room_id_count = 0;
                            db_id_count = 0;
                            db_batch_id_count = 0;
                            date_start_count = 0;
                            date_end_count = 0;
                            modal_time_count = 0;
                            modal_time_next_count = 0;
                            class_period_count = 0;

                            $(this).find("form .modal_course_capacity_id").each(function(){


                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    if($(tab).find("#modal_teacher_name_"+count).data('teacher_id') != undefined){
                                        // teacher_name_arr[name_count] = $("#modal_teacher_name_"+count).data('teacher_id');
                                        // teacher_name_arr[name_count] = $("#modal_teacher_name_id_"+count).val();
                                        // teacher_name_arr[name_count] = $("#modal_teacher_name_"+count).val();
                                        teacher_name_arr[name_count] = $(tab).find("#modal_teacher_name_"+count).select2("val");
                                        name_count++;
                                    }
                                    else
                                    {
                                        teacher_name_arr[name_count] = "";
                                        name_count++;
                                    }
                                }

                            });

                            $(this).find("form .modal_course_capacity_id").each(function(){
                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    modal_course_capacity_arr[modal_course_capacity_id_count] = $(tab).find("#modal_course_capacity_id_"+count+" option:selected").val();
                                    modal_course_capacity_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $(this).find("form .modal_course_id").each(function(){
                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    modal_course_arr[modal_course_id_count] = $(tab).find("#modal_course_id_"+count+" option:selected").val();
                                    modal_course_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $(this).find("form .modal_room_id").each(function(){
                                if($(this).data('counter') != undefined){
                                    count = $(this).data('counter');
                                    modal_room_arr[modal_room_id_count] = $(tab).find("#modal_room_id_"+count+" option:selected").val();
                                    modal_room_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            $(this).find("form .db_id").each(function(){
                                if($(this).val() != undefined){
                                    db_id_arr[db_id_count] = $(this).val();
                                    db_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $(this).find("form .db_batch_id").each(function(){
                                if($(this).val() != undefined){
                                    db_batch_id_arr[db_batch_id_count] = $(this).val();
                                    db_batch_id_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .date_start").each(function(){
                                if($(this).val() != undefined){
                                    start_arr[date_start_count] = $(this).val();
                                    date_start_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .date_end").each(function(){
                                if($(this).val() != undefined){
                                    end_arr[date_end_count] = $(this).val();
                                    date_end_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .modal_time").each(function(){
                                if($(this).val() != undefined){
                                    modal_time_arr[modal_time_count] = $(this).val();
                                    modal_time_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .modal_time_next").each(function(){
                                if($(this).val() != undefined){
                                    modal_time_next_arr[modal_time_next_count] = $(this).val();
                                    modal_time_next_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });
                            
                            $(this).find("form .class_period").each(function(){
                                if($(this).val() != undefined){
                                    class_period_arr[class_period_count] = $(this).val();
                                    class_period_count++;
                                    // alert($(this).data('teacher_id'));
                                }
                            });

                            $.ajax({
                                url:'{{{ URL::to("scheduler/createStudent")}}}',
                                type:'post',
                                data:{ 
                                    '_token': $("input[name=_token]").val(),    
                                    // 'time_id':time_in_id, 
                                    // 'time_out':time_out_id, 
                                    'student_id':$("#student_id_save").val(), 
                                    'student_nickname':$("#student_nickname_save").val(), 
                                    'program_id':$(tab).find("#modal_program_id").val(), 
                                    'student_personality_id':$(tab).find("#modal_student_personality_id").val(), 
                                    // 'course_id':$("#modal_course_id").val(), 
                                    'room_id':$("#modal_room_id").val(), 
                                    'teacher_name_arr':teacher_name_arr, 
                                    'student_eng_name':student_eng_name, 
                                    'db_id_arr':db_id_arr, 
                                    'batch_id_arr':db_batch_id_arr, 
                                    'start_arr':start_arr, 
                                    'end_arr':end_arr, 
                                    'modal_course_capacity_arr':modal_course_capacity_arr, 
                                    'modal_room_arr':modal_room_arr, 
                                    'modal_course_arr':modal_course_arr, 
                                    'modal_time_arr':modal_time_arr, 
                                    'modal_time_next_arr':modal_time_next_arr, 
                                    'class_period_arr':class_period_arr, 
                                    // 'date_start':$("#date_start").val(), 
                                    // 'date_end':$("#date_end").val(), 
                                    // 'course_capacity_id':$("modal_course_capacity_id").val(), 
                                },async:false,
                                success: function (data) {
                                    swal("Successfully Create");                  
                                  }
                            }).done(function(){
                                var teacher_id = $("#student_id_save").val();
                                $("#Teacher"+teacher_id+" > div.sched").remove();
                                $.ajax({
                                    url:'{{{ URL::to("scheduler/getTimeDataJson") }}}',
                                    type:'GET',
                                    data: {'date':$("#date").val()},
                                    dataType: "json",
                                    async:false,
                                    success: function (data) 
                                    {                  
                                        timeJsonArray = data[0];
                                        var teacher_id = $("#student_id_save").val();
                                        var sename = $("#student_color_"+teacher_id).data('student_eng_name');
                                        var nickname = $("#student_color_"+teacher_id).data('nickname');
                                        var abrod_date = $("#student_color_"+teacher_id).data('abrod_date');
                                        var end_date = $("#student_color_"+teacher_id).data('end_date');

                                        studentJsonArray = [{'student_id':teacher_id,'sename':sename,'date_from':abrod_date,'date_to':end_date,'nickname':nickname}]

                                        var scheduleJsonArray = getScheduleStudentByTeacher($("#date").val(),teacher_id);
                                        // populateStudentSchedule(studentJsonArray,scheduleJsonArray, timeJsonArray);
                                        populateScheduleByStudent(scheduleJsonArray, timeJsonArray,teacher_id,sename);
                                        // populateScheduleByTeacher(scheduleJsonArray, timeJsonArray, teacher_id);
                                        $("#timeModal").modal('hide');
                                        
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

                                            $("#schedule_id_delete").val(schedule_id);
                                            $("#student_id_save").val(teacher_id);

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
                                        // location.reload();
                                    }  

                                });
                            });

                        });
                    });
                    
                // }

            }
            $("#is_edit").val(0);
            if(condition == 0)
            {
            //         if(modal_count == 1)
            //         {   
                        var course_counter = 1;
                        modal_count++;
                        $.ajax({
                              url:'{{{ URL::to("scheduler/getStudentScheduleToday") }}}',
                              type:'GET',
                              dataType: "json",
                              async:false,
                              data:
                                  {  
                                      'student_id': student_id,
                                      'date': date,
                                  },
                              success: function (data_sched) 
                              { 
                                    var modal_count_loop = 0;

                                    $("#modal_program_id [value='0']").attr("selected","selected");
                                    $("#modal_student_personality_id [value='0']").attr("selected","selected");
                                    $("#modal_start_date").val("");
                                    $("#modal_end_date").val("");
                                    $(".modal_course_id").each(function(){
                                        $(this).empty();
                                    });
                                    $(".modal_room_id").each(function(){
                                        $(this).empty();
                                    });
                                    $(".modal_teacher_name").each(function(){
                                        // $(this).val("");
                                        $(this).empty();
                                    });
                                    $(".modal_course_capacity_id").each(function(){
                                        $(".modal_course_capacity_id [value='0']").attr("selected","selected");
                                    });
                                    $(".db_id").each(function(){
                                        $(this).val(0);
                                    });
                                    
                                    $("#main_course").text("Course 1");

                                    if(data_sched['main_program_name'] != "")
                                    {
                                        var data = data_sched['main_program_name'];
                                        $("#main_course").text("");
                                        $("#main_course").append(data.program_name+'<br/>(<span style="color:#428BCA;font-size:11px;">'+data.date_from+' - '+data.date_to+'</span>)');
                                    }

                                    $.map(data_sched['data'],function(item){
                                        // alert();
                                        $("#is_edit").val(1);
                                        modal_count_loop++;
                                        if(modal_count_loop == 1)
                                        {
                                            $("#modal_start_date").val(item.date_from);
                                            $("#modal_end_date").val(item.date_to);
                                            selectListChange('modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': item.program_id },'modal_course_capacity_id' ,'Please select a Program');

                                            $("#modal_program_id [value='"+item.program_id+"']").attr("selected","selected");
                                            $("#modal_student_personality_id [value='"+item.student_personality_id+"']").attr("selected","selected");

                                        }
                                        $("#db_id_"+item.class_id).val(item.id);
                                        $("#batch_id_"+item.class_id).val(item.batch_id);
                                        $("#modal_course_capacity_id_"+item.class_id+" [value='"+item.course_capacity_id+"']").attr("selected","selected");
                                        $("#modal_course_id_"+item.class_id+" [value='0']").removeAttr("selected","selected");
                                        $("#modal_course_id_"+item.class_id+" [value='"+item.course_id+"']").attr("selected","selected");

                                        data = item.course_capacity_id;

                                        if(data != 1 && data != 5 && data != 6)
                                        {   
                                            data = 8;
                                        }

                                        // if(data == 7)
                                        // {
                                        //     data = 5;
                                        // }

                                        
                                        $('#modal_room_id_'+item.class_id).empty();
                                        $('#modal_room_id_'+item.class_id).append('<option value="0"></option>');
                                        var data1 = data_sched['schedule'][item.class_id];

                                        $.map(data1, function (value) 
                                        {  
                                                $('#modal_room_id_'+item.class_id).append($("<option/>").val(value.value).text(value.text));
                                        });
                                        // selectListRoomSpaceChange('modal_room_id_'+item.class_id,'{{{URL::to("scheduler/courseCapacityRoomDataJson")}}}',  { 'course_capacity_id': data } ,'Please select a Class Capacity');

                                        $("#modal_room_id_"+item.class_id+" [value='"+item.room_id+"']").attr("selected","selected");
                                        if(item.first_name != null && item.last_name != null)
                                        {
                                            // $("#modal_teacher_name_"+item.class_id).val(item.first_name+" "+item.last_name+" - "+item.nickname);
                                            $("#modal_teacher_name_"+item.class_id).append('<option value="'+item.teacher+'">'+item.first_name+" "+item.last_name+" - "+item.nickname+'</option>');
                                            $("#modal_teacher_name_"+item.class_id+" [value='"+item.teacher+"']").attr("selected","selected");
                                            $("#modal_teacher_name_"+item.class_id).attr("data-teacher_id",item.teacher);
                                            $("#modal_teacher_name_id_"+item.class_id).val(item.teacher);
                                        }

                                        if(data != 5 && data != 6)
                                        { 
                                            var data2 = data_sched['available_teacher_arr_period'][item.class_id];
                                            $.map(data2, function (value) 
                                            {  
                                                    $('#modal_teacher_name_'+item.class_id).append($("<option/>").val(value.teacher_id).text(value.teacher_name));
                                            });
                                        }
                                    });
                                    
                                    $.map(data_sched['schedule_list'],function(item){
                                        var course_counter = $("#course_counter").val();
                                        course_counter++;

                                        var date_from = item.date_from.toString();
                                        var date_to = item.date_to.toString();
                                        $("#course_counter").val(course_counter);
                                        $("#nav_tab_schedule").append('<li id="li_'+course_counter+'" onclick="call_sched('+item.program_id+','+date_from+','+date_to+','+course_counter+')" data-program_id="'+item.program_id+'" data-date_from="'+item.date_from+'" data-date_to="'+item.date_to+'" data-counter="'+course_counter+'"><a href="#course'+course_counter+'" data-toggle="tab" role="tab" align="center">'+item.program_name+'<br/>(<span style="color:#428BCA;font-size:11px;">'+item.date_from+' - '+item.date_to+'</span>)'+'<i class="fa"></i></a></li>');
                                        var html = $("#course1").html();
                                        $("#tab_content_schedule").append('<div role="tabpanel" class="tab-pane" id="course'+course_counter+'" data-date_from="'+item.date_from+'" data-date_to="'+item.date_to+'">'+html+'</div>');
                                        $("#course"+course_counter+" > form")[0].reset();
                                        $("#course"+course_counter+" > form").each(function(){
                                            

                                            // for(var i = 1;i <= 11;i++)
                                            // {
                                            //     $(this).find("#modal_room_id_"+i).select2(
                                            //     {
                                            //       placeholder: "Select Room",
                                            //       allowClear: true
                                            //     });

                                            //     $(this).find("#modal_teacher_name_"+i).select2(
                                            //     {
                                            //       placeholder: "Select Teacher",
                                            //       allowClear: true
                                            //     });
                                            // }

                                                $(this).find("#modal_program_id [value='0']").attr("selected","selected");
                                                $(this).find("#modal_student_personality_id [value='0']").attr("selected","selected");
                                                $(this).find("#modal_start_date").val("");
                                                $(this).find("#modal_end_date").val("");
                                                $(this).find(".modal_course_id").each(function(){
                                                    $(this).empty();
                                                });
                                                $(this).find(".modal_room_id").each(function(){
                                                    $(this).find('.select2-chosen').text("");
                                                });
                                                $(this).find(".modal_teacher_name").each(function(){
                                                    $(this).find('.select2-chosen').text("");
                                                });
                                                $(this).find(".modal_course_capacity_id").each(function(){
                                                    // $(this).empty();
                                                    var form = $(this).closest('form');

                                                    $(form).find(".modal_course_capacity_id").each(function(){
                                                            $(this).removeAttr('selected');
                                                    });

                                                    $(form).find(".modal_course_capacity_id [value='0']").attr("selected","selected");
                                                });
                                                $(this).find(".db_id").each(function(){
                                                    $(this).val(0);
                                                });
                                                    $(this).find("#modal_program_id").change(function(){
                                                        var form = $(this).closest('form');
                                                        var course_capacity_data_arr = [];
                                                        var capacity_count_arr = 0;
                                                        var room_count_arr = 0;
                                                        var teacher_name_count_arr = 0;

                                                        var date = $(form).find("#modal_start_date").val();
                                                        var date_end = $(form).find("#modal_end_date").val();
                                                        // var date = $("#abrod_start").text();
                                                        // date = date.replace("Departure Date: ", "");
                                                        selectListChangeClass('modal_course_id','{{{URL::to("scheduler/programCourseDataJson")}}}',  { 'program_id': $(this).val() },'modal_course_capacity_id' ,'Please select a Program',date,date_end,course_counter);

                                                        // $(form).find(".modal_course_capacity_id").each(function(){
                                                        //     course_capacity_data_arr[capacity_count_arr] = $(this).val();
                                                        //     capacity_count_arr++;
                                                        // });
                                                        // $(form).find(".modal_room_id").each(function(){
                                                        //     if(course_capacity_data_arr[room_count_arr] != undefined)
                                                        //     {
                                                        //         $(this).empty();
                                                        //     }
                                                        //     room_count_arr++;
                                                        // });
                                                        // $(form).find(".modal_teacher_name").each(function(){
                                                        //     if(course_capacity_data_arr[teacher_name_count_arr])
                                                        //     {
                                                        //         $(this).empty();
                                                        //     }
                                                        //     teacher_name_count_arr++;
                                                        // });
                                                    });
                                                    $(this).find(".modal_course_capacity_id").change(function(){
                                                        var counter = $(this).data('counter');

                                                        data = $(this).find("#modal_course_capacity_id_"+counter).val();
                                                        id = $(this).find("#modal_course_capacity_id_"+counter+" option:selected").data('capacity'); 
                                                        if(data != 1 && data != 5 && data != 6)
                                                        {   
                                                            data = 8;
                                                        }
                                                        var date = $("#modal_start_date").val();
                                                        var date_end = $("#modal_end_date").val();
                                                        
                                                        // var date = $("#abrod_start").text();
                                                        // date = date.replace("Departure Date: ", "");
                                                        
                                                            selectListRoomSpaceChange('modal_room_id_'+counter,'{{{URL::to("scheduler/courseCapacityRoomTeacherDataJson")}}}',  { 'course_capacity_id': data, 'class_id': counter, 'date': date,'date_end': date_end } ,'Please select a Class Capacity',counter);
                                                        
                                                    });
                                        });
                                    });
                              }  

                        });

                        // $(".modal_room_id").select2();
                        // $(".modal_teacher_name").select2();
                        // $(".modal_room_id").each(function(){
                        //     $(this).removeClass('form-control');
                        // });
                        // $(".modal_teacher_name").each(function(){
                        //     $(this).removeClass('form-control');
                        // });
                           
                            if(modal_counter == 0)
                            {

                                modal_counter++;
                                $("#course"+course_counter).find(".modal_room_id").change(function(){

                                    counter = $(this).data('counter');
                                    data = $("#course"+course_counter).find("#modal_course_capacity_id_"+counter).val();

                                    $.ajax({
                                        url: "../scheduler/getRoomTeacher",
                                        data: 
                                        {
                                            'room_id' : $(this).val(), 
                                            'class_id' : counter, 
                                        },
                                        type: "GET", 
                                        dataType: "json",
                                        async:false,
                                        success: function (data) 
                                        {

                                            $("#course"+course_counter).find("#modal_teacher_name_"+counter).empty();
                                            $("#course"+course_counter).find("#modal_teacher_name_"+counter).select2('val','');
                                            $("#course"+course_counter).find("#modal_teacher_name_"+counter).val("");
                                            $("#course"+course_counter).find("#modal_teacher_name_id_"+counter).val("");
                                            $("#course"+course_counter).find("#modal_teacher_name_"+counter).removeAttr('data-teacher_id');
                                            if(data[0])
                                            {
                                                if(data[0].first_name != undefined && data[0].first_name != null)
                                                {
                                                    // $("#course"+course_counter).find("#modal_teacher_name_"+counter).val(data.first_name+' '+data.last_name+'('+data.nickname+')');
                                                    $("#course"+course_counter).find("#modal_teacher_name_"+counter).append('<option value="'+data[0].id+'">'+data[0].first_name+" "+data[0].last_name+" - "+data[0].nickname+'</option>');
                                                    $("#course"+course_counter).find("#modal_teacher_name_"+counter+" [value='"+data[0].id+"']").attr("selected","selected");
                                                    $("#course"+course_counter).find("#modal_teacher_name_"+counter).select2().select2('val',data[0].id);
                                                    $("#course"+course_counter).find("#modal_teacher_name_id_"+counter).val(data[0].id);
                                                    $("#course"+course_counter).find("#modal_teacher_name_"+counter).attr('data-teacher_id',data[0].id);
                                                }
                                                $.map(data[1], function (item) 
                                                {      
                                                        $("#course"+course_counter).find("#modal_teacher_name_"+counter).append('<option value="'+item.teacher_id+'">'+item.teacher_name+'</option>');
                                                        
                                                });
                                            }
                                            else
                                            {   
                                                var option_arr = [];
                                                var option_arr_counter = 0;
                                                $.map(data[1], function (item) 
                                                {      
                                                        $("#course"+course_counter).find("#modal_teacher_name_"+counter).append('<option value="'+item.teacher_id+'">'+item.teacher_name+'</option>');
                                                        option_arr[option_arr_counter] = item.teacher_id;
                                                        option_arr_counter++;
                                                        
                                                });

                                                item = get_random(option_arr);
                                                $("#course"+course_counter).find("#modal_teacher_name_"+counter+" [value='"+item+"']").attr("selected","selected");
                                                $("#course"+course_counter).find("#modal_teacher_name_"+counter).select2().select2('val',item);   

                                            }

                                            
                                        }
                                    });
                                });  
                                modal_counter++;
                            }
            //         }
            //         else
            //         {
            //             modal_count = 1;
            //         }
            }

            // if(modal_counter == 0)
            // {
                for(var i = 1;i <= 11;i++)
                {
                    $("#modal_room_id_"+i).select2(
                    {
                      placeholder: "Select Room",
                      allowClear: true
                    });

                    $("#modal_teacher_name_"+i).select2(
                    {
                      placeholder: "Select Teacher",
                      allowClear: true
                    });
                }

            //     modal_counter++;
            // }

                course_counter = 1;
                $("#course"+course_counter).find(".modal_room_id").change(function(){

                    counter = $(this).data('counter');
                    data = $("#course"+course_counter).find("#modal_course_capacity_id_"+counter).val();
                    // selectListRoomSpaceChange('modal_room_id_'+counter,'../scheduler/courseCapacityRoomTeacherDataJson',  { 'course_capacity_id': data, 'class_id': counter } ,'Please select a Class Capacity',counter);
                    // $.ajax({
                    //     url: "../scheduler/getRoomTeacher",
                    //     data: 
                    //     {
                    //         'room_id' : $(this).val(), 
                    //         'class_id' : counter, 
                    //     },
                    //     type: "GET", 
                    //     dataType: "json",
                    //     async:false,
                    //     success: function (data) 
                    //     {
                    //         $("#course"+course_counter).find("#modal_teacher_name_"+counter).val("");
                    //         $("#course"+course_counter).find("#modal_teacher_name_id_"+counter).val("");
                    //         $("#course"+course_counter).find("#modal_teacher_name_"+counter).removeAttr('data-teacher_id');
                    //         if(d.first_name != undefined && data.first_name != null)
                    //         {
                    //             // $("#course"+course_counter).find("#modal_teacher_name_"+counter).val(data.first_name+' '+data.last_name+'('+data.nickname+')');
                    //             $("#modal_teacher_name_"+counter).append('<option value="'+data.id+'">'+data.first_name+" "+data.last_name+" - "+data.nickname+'</option>');
                    //             $("#modal_teacher_name_"+counter+" [value='"+data.id+"']").attr("selected","selected");
                    //             $("#course"+course_counter).find("#modal_teacher_name_id_"+counter).val(data.id);
                    //             $("#course"+course_counter).find("#modal_teacher_name_"+counter).attr('data-teacher_id',data.id);
                    //         }
                    //     }
                    // });
                });  

                $("#modal_print_student_schedule").attr('href','{{ URL::to("student/studentSchedulePdf?student_id=")}}'+student_id+'&program_id='+$("#modal_program_id").val()+'&examination_id=null');


                $("#modal_delete_date").click(function(){

                    var student_id = $("#student_id_save").val();
                    var program_id = $("#modal_program_id").val();
                    var date_from = $("#modal_start_date").val();
                    var date_to = $("#modal_end_date").val();

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
                            url:'{{{ URL::to("scheduler/deleteStudentClassSchedule") }}}',
                            type:'POST',
                            data: {
                                'student_id' : student_id,
                                'program_id' : program_id,
                                'date_from' : date_from,
                                'date_to' : date_to,
                                '_token' : $("input[name=_token]").val(),
                            },
                            dataType: "json",
                            async:false,
                            success: function (data) 
                            {            
                                swal("Deleted!", "Student schedules deleted successfully.", "success");  
                                // location.reload();  
                                timeJsonArray = data[0];
                                var teacher_id = $("#student_id_save").val();
                                var sename = $("#student_color_"+teacher_id).data('student_eng_name');
                                var nickname = $("#student_color_"+teacher_id).data('nickname');
                                var abrod_date = $("#student_color_"+teacher_id).data('abrod_date');
                                var end_date = $("#student_color_"+teacher_id).data('end_date');

                                $('#student_color_'+teacher_id+' > div').each(function(){
                                        $(this).remove();
                                });
                                studentJsonArray = [{'student_id':teacher_id,'sename':sename,'date_from':abrod_date,'date_to':end_date,'nickname':nickname}]

                                var scheduleJsonArray = getScheduleStudentByTeacher($("#date").val(),teacher_id);
                                // populateStudentSchedule(studentJsonArray,scheduleJsonArray, timeJsonArray);
                                populateScheduleByStudent(scheduleJsonArray, timeJsonArray,teacher_id,sename);
                                $("#timeModal").modal('hide');  
                            }  
                        });
                    });
                });
        });
    }


}

function getScheduleJson(date){

    $.ajax({
              url:'{{{ URL::to("scheduler/getScheduleDataJsonStudent") }}}',
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

function datepicker(id) {
    $("#datepicker"+id).datepicker().on('shown.bs.modal', function(event) {
        // prevent datepicker from firing bootstrap modal "shown.bs.modal"
        event.stopPropagation();
    });

    // for(i=1;i<=11;i++)
    // {
        // $("#modal_course_id_"+i).select2();
        // $("#modal_room_id_"+i).select2();
        // $("#modal_teacher_name_"+i).select2();
    // }

    // $('#datepicker'+id).datepicker({
    //     format: "yyyy-mm-dd",
    //     orientation: "auto",
    //     autoclose: true,
    //     startView: 1,
    //     todayHighlight: true,
    //     todayBtn: "linked",
    // });
    $('.datepicker1').datepicker({
        format: "yyyy-mm-dd",
        orientation: "auto",
        autoclose: true,
        startView: 1,
        todayHighlight: true,
        todayBtn: "linked",
    });
}

</script>

@stop