@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.scheduler_list") }}} :: @parent
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
         @include('notifications')
        <div class="page-header">
            <h2>
                <div class="pull-right">
                </div>
            </h2>
        </div>
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="form-group col-md-12">
            <div class="col-md-3">
                <label class="control-label">Classification</label>
            </div>
            <div class="col-md-3 program_div hidden">
                <label class="control-label" >Program</label>
            </div>
            <div class="col-md-2">
                <label class="control-label">Classification Level</label>
            </div>
            <div class="col-md-3">
                <label class="control-label">Term</label>
            </div>
            <div class="col-md-1">
                <label class="control-label">Day</label>
            </div>
        </div>
        <div class="form-group col-md-12">
            <!-- <div class="col-md-1">
                <label class="control-label">Classification</label>
            </div> -->
            <div class="col-md-3">
                <select class="form-control" name="classification_id" id="classification_id">
                      <option name="" value=""></option>
                      @foreach($classification_list as $classification)
                          <option name="classification_id" value="{{{$classification -> id}}}">{{{ $classification -> classification_name }}}</option>
                      @endforeach
                </select>
            </div>
            <!-- <div class="col-md-1 program_div hidden">
                <label class="control-label" >Program</label>
            </div> -->
            <div class="col-md-3 program_div hidden">
                <select class="form-control" name="program_id" id="program_id">
                </select>
            </div>
            <!-- <div class="col-md-1">
                <label class="control-label">Classification Level</label>
            </div> -->
            <div class="col-md-2">
                <select class="form-control" name="classification_level_id" id="classification_level_id">
                </select>
            </div>
            <!-- <div class="col-md-1">
                <label class="control-label">Term</label>
            </div> -->
            <div class="col-md-3">
                <select class="form-control" name="term_id" id="term_id">
                    <option name="" value=""></option>
                    @foreach($term_list as $term)
                        <option value="{{{ $term ->id}}}">{{{ $term -> term_name." ". $term ->classification_name }}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <select class="form-control" name="day_id" id="day_id">
                    <option name="" value=""></option>
                    @foreach($day_list as $day)
                        <option value="{{{ $day ->id}}}">{{{ $day -> day_code}}}</option>
                    @endforeach
                </select>
            </div>
            <!-- <div class="col-md-1">
                <label class="control-label">Section</label>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="section_id" id="section_id">
                </select>
            </div> -->
        </div>
        <div class="col-md-12">
            <button type="button" id="load" class="btn btn-primary btn-sm">Load</button>
        </div>

        <div class="col-md-12">
            <hr>
        </div>
        <center>
        <div class="col-md-12" >
            <div class="div-table" id="MainDiv">
            </div>
        </div>
      </center>
    </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')

<script src="{{asset('assets/site/js/schedule-index.js')}}"></script>
<script>

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});

</script>
<script>
  $(document).ready(function(){

    var sectionJsonArray = [{"value":1, "text":"Room 1"} , {"value":2, "text":"Room 2"},{"value":3, "text":"Room 3"},
                            {"value":4, "text":"Room 4"} , {"value":5, "text":"Room 5"},{"value":6, "text":"Room 6"},
                            {"value":7, "text":"Room 7"} , {"value":8, "text":"Room 8"},{"value":9, "text":"Room 9"}, 
                            {"value":10, "text":"Room 10"} , {"value":11, "text":"Room 11"},{"value":12, "text":"Room 12"}];

    var scheduleJsonArray = [
      // {"id":1 , "time_start":276, "time_end":280, "class": "Filipino 1", "room_id":1 } ,
      // {"id":2 , "time_start":283, "time_end":286, "class": "Programming 1", "room_id":1},
      // {"id":3 , "time_start":287, "time_end":289, "class": "Science", "room_id":1},
      // {"id":4 , "time_start":274, "time_end":281, "class": "Math 1", "room_id":2},
    ];
    generateSchedule(sectionJsonArray,scheduleJsonArray);

    $("#classification_id").change(function(){

        $(".program_div").removeClass('shown');
        $(".program_div").addClass('hidden');

        var value = $("#classification_id option:selected").text();
        $("#program_id").val(null);
        if(value == "College")
        {

          $(".program_div").removeClass('hidden');
          $(".program_div").addClass('shown');

          selectListChange('program_id','{{{ URL::to("program/dataJson") }}}',  {'classification_id': $(this).val() } ,'Please select a Classification');
          
        }
        
        selectListChange('classification_level_id','{{{ URL::to("classification_level/dataJson") }}}',  {'classification_id': $(this).val() } ,'Please select a Classification');
        // selectListChange('term_id','{{{ URL::to("term/dataJson") }}}',  {'classification_id': $(this).val() } ,'Please select a Classification');
    });

    $("#load").click(function(){

         var classification_level_id = $("#classification_level_id").val();

          if(classification_level_id)
          {
                    var program_id = $("#program_id").val();

                    var roomJsonArray = getRoomJson(program_id,classification_level_id);
                    var scheduleJsonArray = getScheduleJson();
                    generateSchedule(roomJsonArray,scheduleJsonArray);
                  
          }
    });

    $("#term_id").change(function(){
            var term = $("#term_id option:selected").text();
            if(term != "")
            {
                $("#modal_term").text(term);

            }
    });

    function getScheduleJson(){

        $.ajax({
                  url:'{{{ URL::to("scheduler/dataJson") }}}',
                  type:'GET',
                  dataType: "json",
                  async:false,
                  data:
                      {  
                          'day_id': $("#day_id").val(),
                          'term_id': $("#term_id").val(),
                      },
                  success: function (data) 
                  { 

                      scheduleJsonArray = data;
                  }  

              });

        return scheduleJsonArray;

    }

    function getRoomJson(program_id,classification_level_id){

      

              if(program_id == null || program_id == "" || program_id == "undefined")
              {
                  program_id = 0;
              }

              $.ajax({
                  url:'{{{ URL::to("room/dataJson") }}}',
                  type:'GET',
                  data:
                      {  
                          'classification_level_id': classification_level_id,
                          'program_id': program_id,
                      },
                  dataType: "json",
                  async:false,
                  success: function (data) 
                  {
                        sectionJsonArray = data;
                  }  

              }); 

        return sectionJsonArray;

    }
    
    function generateSchedule(sectionJsonArray,scheduleJsonArray)
    {
        $.ajax({
            url:'{{{ URL::to("scheduler/timeDataJson") }}}',
            type:'GET',
            data:
                {  
                    'time_range_id': 2,
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                timeJsonArray = data;
                $("#MainDiv").empty();
                populateTimeHeader(timeJsonArray,"Room");
                populateRoomSchedule(sectionJsonArray, scheduleJsonArray, timeJsonArray);
            }  
        });

    }
   
    function generateTimePicker() 
    {
        $('.datetimepicker3').datetimepicker({
            format: 'LT'
        })
    }

    
    function getSectionName(id)
    {   
        var section_name = "";

        $.ajax({
            url:'{{{ URL::to("scheduler/getSectionName") }}}',
            type:'GET',
            data:
                {  
                    'section_id': id,
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
                $.map(data, function (item) 
                {      
                    section_name = item.section_name;
                });
                
            }  

            
        });

        return section_name;
    }

});


</script>
@stop
    