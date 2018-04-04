 @extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.scheduler_list") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<style>
h2{
    color:#008cba;
}
</style>
<style>

  body {
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 1100px;
    margin: 0 auto;
  }

</style>
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
                <div id="calendar" align="center">
                </div>
            </h2>
        </div>
        <!-- {!! $calendar->calendar() !!} -->
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="timeModal" role="dialog">

    <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title">Create Class Schedule</h4>
            </div>
            <div class="modal-body form-group">
                <form id="myForm" class="form-horizontal">
                    <div class="col-md-6" style="padding:0px;">
                        <div class="col-md-12" style="border:1px solid #D3D3D3;padding:0px;">
                            <div class="col-md-12">
                                <label>Program</label>
                            </div>
                            <div class="col-md-12">
                                <select name="modal_program_id" id="modal_program_id" tabindex="4" style="width:100%;">
                                    <option></option>
                                    @foreach($program_list as $program)
                                        <option value="{{{ $program ->id}}}">{{{ $program -> program_name }}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Teacher</label>
                            </div>
                            <div class="col-md-12">
                                <select name="modal_teacher_id" id="modal_teacher_id" tabindex="4" style="width:100%;">
                                    <option></option>
                                    @foreach($teacher_list as $teacher)
                                        <option value="{{{ $teacher ->id}}}">{{{ $teacher -> first_name." ". $teacher ->last_name }}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Term</label>
                            </div>
                            <div class="col-md-12">
                                <select name="modal_term_id" id="modal_term_id" tabindex="4" style="width:100%;">
                                    <option></option>
                                    @foreach($term_list as $term)
                                        <option value="{{{ $term ->id}}}">{{{ $term -> term_name." ". $term ->classification_name }}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Capacity</label>
                            </div>
                            <div class="col-md-12">
                                <input id="modal_capacity input-sm" type="text" class="form-control input-sm">
                            </div>
                            <div class="col-md-12" style="margin-top:10px;margin-bottom:10px;">
                                <button type="button" class="btn btn-success btn-sm">Create Class</button>
                            </div>
                            
                        </div>
                        <div class="col-md-12" style="border:1px solid #D3D3D3;padding:0px;margin-top:10px;">
                            <div class="col-md-12">
                                <label>Teachers Name:</label>
                                </br>
                                <label>Total Load:</label>
                                </br>
                                <label>Students Enrolled:</label>
                            </div>
                        </div>
                        <div class="col-md-12" style="border:1px solid #D3D3D3;padding:0px;margin-top:10px;">
                            <div class="col-md-12">
                                <label>Course Hrs Lec:</label>
                                </br>
                                <label>Course Hrs Lec:</label>
                                </br>
                                <label>Total Course Hrs:</label>
                            </div>
                        </div>
                        <div class="col-md-12" style="border:1px solid #D3D3D3;padding:0px;margin-top:10px;">
                            <div class="col-md-12">
                                <label>Time Difference:</label>
                                </br>
                                <label>Time Plotted</label>
                                </br>
                                <label>Time UnPlotted:</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" style="border:1px solid #fff;padding:0px;margin-left:0px;">
                        <div class="col-md-12">
                            <label>Schedule Type</label>
                        </div>
                        <div class="col-md-12">
                            <select name="modal_schedule_type_id" id="modal_schedule_type_id" tabindex="4" style="width:100%;">
                                <option></option>
                                @foreach($schedule_type_list as $schedule_type)
                                    <option value="{{{ $schedule_type ->id}}}">{{{ $schedule_type -> schedule_type_name}}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Time Start</label>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12 datetimepicker3 input-group date ">
                                <input id="time_start input-sm" type="text" class="form-control input-sm">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Time End</label>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12 datetimepicker3 input-group date ">
                                <input id="time_end input-sm" type="text" class="form-control input-sm">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Room</label>
                        </div>
                        <div class="col-md-12">
                            <select name="modal_room_id" id="modal_room_id" tabindex="4" style="width:100%;">
                                <option></option>
                                @foreach($room_list as $room)
                                    <option value="{{{ $room ->id}}}">{{{ $room -> room_name}}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Building</label>
                        </div>
                        <div class="col-md-12">
                            <select name="modal_building_id" id="modal_building_id" tabindex="4" style="width:100%;">
                                <option></option>
                                @foreach($building_list as $building)
                                    <option value="{{{ $building ->id}}}">{{{ $building -> building_name}}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Campus</label>
                        </div>
                        <div class="col-md-12">
                            <select name="modal_campus_id" id="modal_campus_id" tabindex="4" style="width:100%;">
                                <option></option>
                                
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top:10px;">
                              <label>Mo &nbsp;&nbsp;Tu &nbsp;&nbsp;&nbsp;&nbsp;We &nbsp;&nbsp;
                                Th &nbsp;&nbsp;&nbsp;&nbsp;Fr &nbsp;&nbsp;&nbsp;&nbsp;Sa &nbsp;&nbsp;&nbsp;&nbsp;Su
                              </label>
                              </br>
                              <input type="checkbox"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="checkbox"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="checkbox"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="checkbox"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="checkbox"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="checkbox"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="checkbox"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                        </div>
                        <div class="col-md-12" style="margin-bottom:10px;">
                            <button type="button" class="btn btn-success btn-sm">Create Schedule</button>
                        </div>
                    </div>
                    </br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
                    </br></br></br></br></br></br></br></br></br>
                </form>
            </div>
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



<!-- {!! $calendar->script() !!} -->
<script type="text/javascript">


  $(document).ready(function() {
    
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      selectable: true,
      selectHelper: true,
      defaultView: 'agendaWeek',
      allDaySlot: false,
      minTime: "06:00:00",
      maxTime: "25:00:00",
      select: function(start, end) {
        // var title = prompt('Event Title:');

        var date_start = new Date(start);
        var day = date_start.getDate();
        var month = date_start.getMonth()+1;
        var year = date_start.getFullYear();

        
        var str_start = start.toString();
        var time_start = str_start.substring(16, 24);

        var str_end = end.toString();
        var time_end = str_end.substring(16, 24);

        // alert(day+" "+month+" "+year+"    "+time_start+"-"+time_end);

        $('#timeModal').modal('show');
        
        var title = $("#modal_program_id").val();

        var eventData;
        if (title) {
          eventData = {
            title: title,
            start: start,
            end: end
          };
          $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
        }
        $('#calendar').fullCalendar('unselect');
      },
      editable: true,
      eventLimit: true, // allow "more" link when too many events
   
        // add event name to title attribute on mouseover
        eventMouseover: function(event, jsEvent, view) {
            if (view.name !== 'agendaDay') {
                $(jsEvent.target).attr('title', event.title);
            }
        },
        // eventDestroy: function(event, element, view)
        // {
        //     alert("removing stuff");
        // },
        eventClick: function(calEvent, jsEvent, view)
        {
            var r=confirm("Delete " + calEvent.title);
            if (r===true)
              {
                  $('#calendar').fullCalendar('removeEvents', calEvent._id);
              }
        },

      events: [
        {
          title: 'All Day Event',
          start: '2015-02-01',
        },
        {
          title: 'Long Event',
          start: '2015-02-07',
          end: '2015-02-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2015-02-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2015-02-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2015-02-11',
          end: '2015-02-13'
        },
        {
          title: 'Meeting',
          start: '2015-13-12T10:30:00',
          end: '2015-16-12T12:30:00',
          dow: [ 1, 4 ]
        },
        {
          title: 'Lunch',
          start: '2015-02-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2015-02-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2015-02-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2015-02-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2015-02-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2015-02-28'
        }
      ]
    });

  });

</script>

<script>

  $('#timeModal').on('show.bs.modal', function(e) {        
        // $(this).find('.btn-ok').attr('onclick', 'SaveSchedule('+$(e.relatedTarget).data('value')+')');
        $('#myForm')[0].reset();
        $(this).find('.btn-ok').attr('onclick', 'SaveSchedule()');
        generateTimePicker();


        $("#modal_program_id").select2(
        {
          placeholder: "Select Program",
          allowClear: true
        });
        $("#modal_teacher_id").select2(
        {
          placeholder: "Select Teacher",
          allowClear: true
        });

        $("#modal_term_id").select2(
        {
          placeholder: "Select Term",
          allowClear: true
        });

        $("#modal_room_id").select2(
        {
          placeholder: "Select Room",
          allowClear: true
        });

        $("#modal_schedule_type_id").select2(
        {
          placeholder: "Select Schedule Type",
          allowClear: true
        });

        $("#modal_building_id").select2(
        {
          placeholder: "Select Building",
          allowClear: true
        });

        $("#modal_campus_id").select2(
        {
          placeholder: "Select Campus",
          allowClear: true
        });
    });

    $("#modal_program_id").change(function()
    {
        alert($(this).val());
    });


    function generateTimePicker() 
    {
        $('.datetimepicker3').datetimepicker({
            format: 'LT'
        })
    }

    function SaveSchedule(){        
        $("#timeModal").hide();
        // $.ajax({
        //     url:'{{{ URL::to("scheduler/training/delete")}}}',
        //     type:'post',
        //     data:{ 
        //         '_token': $("input[name=_token]").val(),
        //         'id':rowId, 
        //     },async:false,
        //     success: function (data) {
        //         alert("Successfully Deleted");                  
        //       },
        //     error: function(){
        //         alert("Delete Unsuccessful")
        //     }
        // }).done(function(){
        //     $("#"+rowId).remove();
        //     $("#delete_training_modal").modal("hide");
        // });

    }
</script>

@stop
