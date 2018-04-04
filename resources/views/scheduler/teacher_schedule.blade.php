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
        <div class="col-md-12">
            <div class="form-group {{{ $errors->has('employee_id') ? 'has-error' : '' }}}">
                  <label class="col-md-2 control-label" for="teacher_name">{!! Lang::get('teacher.filter_teacher') !!}</label>
                  <input type="hidden" name="employee_id" id="employee_id" value="0" />
                  <div class="col-md-5">
                      <input class="typeahead form-control" type="text" name="teacher_name" id="teacher_name" value="" />
                    {!! $errors->first('employee_id', '<label class="control-label" for="teacher_name">:message</label>')!!}

                  </div>
            </div>
        </div>
        <div class="col-md-12"><br/></div>
        <div class="col-md-12"><br/></div>
        <div class="col-md-12">
            <div class="col-md-2"><label class="col-md-2 control-label">{!! Lang::get('teacher.section') !!}</label></div>
            <div class="col-md-3"><label class="col-md-2 control-label">{!! Lang::get('teacher.subject') !!}</label></div>
            <div class="col-md-3"><label class="col-md-2 control-label">{!! Lang::get('teacher.time') !!}</label></div>
            <div class="col-md-2"><label class="col-md-2 control-label">{!! Lang::get('teacher.room') !!}</label></div>
            <div class="col-md-2"><label class="col-md-2 control-label">{!! Lang::get('teacher.day') !!}</label></div>
        </div>
        <div id="schedule_container" class="col-md-12">
        </div>
    </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')

<script>
  /********
                RMD 2015-03-07
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
                        limit: 10,
                       
                        remote:{
                          url:'{{{ URL::to("teacher/dataJson?query=%QUERY") }}}',
                          filter: function (teacher_list) {
                                      // alert('this is an alert script from create');
                                  // console.log(teacher_list); //debugging
                                    // Map the remote source JSON array to a JavaScript object array
                                    return $.map(teacher_list, function (teacher) {
                                        console.log(teacher); //debugging
                                        return {
                                            teacher_name: teacher.first_name + ' '+teacher.middle_name + ' ' + teacher.last_name,
                                            employee_id: teacher.employee_id,
                                            id: teacher.id
                                        };
                                    });
                             }
                        }

                });

                teacher_list.initialize();
                 console.log(teacher_list);

               // $('#teacher_typeahead .typeahead').typeahead(null, {
                  $('#teacher_name').typeahead(
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

                    ).bind("typeahead:selected", function(obj, teacher, teacher_name) {
                        console.log(teacher);
  
                        // $("#id").val(teacher.id);
                        $("#employee_id").val(teacher.employee_id);
                        // $("#teacher_name").val(teacher.teacher_name);
                        data = getTeacherSchedule(teacher.id);
                        $("#schedule_container").empty();
                        $.map(data, function (item) 
                        {      

                            var dayData = getDaySchedule(item.class_id);
                            var day = "";
                            $.map(dayData, function (item) 
                            {   
                                var dayData = item.day_name;
                                if(day != "")
                                {
                                    day = day+"-"+dayData.substr(0,2);
                                }
                                else
                                {
                                    day = dayData.substr(0,2);
                                }
                                
                            });

                            if(item.room_name != "")
                            {
                              $("#schedule_container").append('<div class="col-md-12"><div class="col-md-2" style="border: 1px solid gray;"><label class="control-label">'+item.section_name+' ('+item.level+')'+'</label></div>'
                              +'<div class="col-md-3" style="border: 1px solid gray;"><label class="control-label">'+item.name+'</label></div>'
                              +'<div class="col-md-3" style="border: 1px solid gray;"><label class="control-label">'+item.time_start+' '+item.time_start_session+' - '+item.time_end+' '+item.time_end_session+'</label></div>'
                              +'<div class="col-md-2" style="border: 1px solid gray;"><label class="control-label">'+item.room_name+'</label></div>'
                              +'<div class="col-md-2" style="border: 1px solid gray;"><label class="control-label">'+day+'</label></div></div>');
                            }
                            else
                            {
                              $("#schedule_container").append('<div class="col-md-12"><div class="col-md-2" style="border: 1px solid gray;"><label class="control-label">'+item.section_name+' ('+item.level+')'+'</label></div>'
                              +'<div class="col-md-3" style="border: 1px solid gray;"><label class="control-label">'+item.name+'</label></div>'
                              +'<div class="col-md-3" style="border: 1px solid gray;"><label class="control-label">'+item.time_start+' '+item.time_start_session+' - '+item.time_end+' '+item.time_end_session+'</label></div>'
                              +'<div class="col-md-2" style="border: 1px solid gray;"><label class="control-label"> N/A </label></div>'
                              +'<div class="col-md-2" style="border: 1px solid gray;"><label class="control-label">'+day+'</label></div></div>');
                            }
                            
                        });
                    });

                   /********
                END OF teacher_name ->  typeahead
            *************************************************************************/


    function getTeacherSchedule(id){

        $.ajax({
            url:'{{{ URL::to("scheduler/getTeacherSchedule") }}}',
            type:'GET',
            dataType: "json",
            async:false,
            data:
                {  
                    'teacher_id': id,
                },
            success: function (data) 
            { 

                scheduleJsonArray = data;
            }  

        });

        return scheduleJsonArray;
    }

    function getDaySchedule(id){

        $.ajax({
            url:'{{{ URL::to("scheduler/getDaySchedule") }}}',
            type:'GET',
            dataType: "json",
            async:false,
            data:
                {  
                    'class_id': id,
                },
            success: function (data) 
            { 

                scheduleJsonArray = data;
            }  

        });

        return scheduleJsonArray;
    }
</script>
@stop
    