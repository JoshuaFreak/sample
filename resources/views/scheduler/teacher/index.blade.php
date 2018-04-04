@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.schedule") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<style>
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
    <div class="page-header" style="margin-left: 3%;margin-right: 3%;"><br>
      <h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
        {{{ Lang::get("scheduler.teachers_per_room") }}}
          <button id="save_room" type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 20px">Update Room Books</button>
      </h3>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div   style="width: 100%;height: 600px;overflow: scroll;">
    <table class="table">
        <tbody>
            <tr>
                <td>Room Name</td>
                @for($i=1;$i <= 11; $i++)
                    <td>Period {{$i}}</td>
                @endfor
            </tr>
        </tbody>
        <tbody>
            @foreach($room_list as $room)
            <tr>
                <td>{{ $room -> room_name }}</td>
                @for($i=1;$i <= 11; $i++)
                    <td>
                        <input id="teacher_id_{{ $room -> id.'_'.$i }}" type="text" data-counter="{{$i}}" data-teacher_id="0" data-class_id="{{ $i }}" data-room_id="{{ $room -> id }}" class="teacher_id typeahead form-control input-sm" style="margin-bottom:10px;font-size: 12px; width:180px">
                    </td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
   
  </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

$(function(){

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
                url:'{{ URL::to("teacher/teacherDataJson?query=%QUERY") }}',
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
                                teacher_name:  data.nickname+' - '+data.first_name+' '+data.last_name,
                                id: data.teacher_id,
                                // examination_id: teacher.examination_id
                            };
                        });
                 }
            }

    });

    teacher_list.initialize();
    console.log(teacher_list);

    $('.teacher_id').typeahead(
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
        $(this).removeAttr('data-teacher_id');
        $(this).attr('data-teacher_id',teacher.id);
        // $(this).attr('data-teacher_name',teacher.teacher_name);
        // $("#teacher_id").val(teacher.id);
        $(this).val(teacher.teacher_name);
        // $("#examination_id [value='"+teacher.examination_id+"']").attr("selected","selected");
       // selectListChange('examination_id','{{{URL::to("examination/dataJson")}}}',  { 'is_active':1 , 'id': $("#examination_id").val() } ,'Please search teacher') 
        
    });//endbind

    $.ajax({
            url:'{{{ URL::to("scheduler/get_room_teacher") }}}',
            type:'GET',
            data: {
                '_token' : $("input[name=_token]").val(),
            },
            dataType: "json",
            async:false,
            success: function (data) 
            {        
                $.map(data,function(item){
                    $("#teacher_id_"+item.room_id+"_"+item.class_id).val(item.nickname+' - '+item.first_name+''+item.last_name);
                    $("#teacher_id_"+item.room_id+"_"+item.class_id).removeAttr('data-teacher_id');
                    $("#teacher_id_"+item.room_id+"_"+item.class_id).attr('data-teacher_id',item.teacher_id);
                });      
            }  
        });

    $("#save_room").click(function(){
        var teacher_arr = [];
        var count_teacher = 0;
        var count = 0;
        var count_all = 0;
        $(".teacher_id").each(function(){
            count_teacher++;
        });
        $(".teacher_id").each(function(){
            if(count < 100)
            {                
                room_id = $(this).data('room_id');
                class_id = $(this).data('class_id');

                teacher_id = $("#teacher_id_"+room_id+"_"+class_id).data('teacher_id');
                teacher_val = $("#teacher_id_"+room_id+"_"+class_id).val();
                teacher_arr[count] = [teacher_id,room_id,class_id,teacher_val];
                count++;
            }
            else
            {
                count = 0;
                $.ajax({
                    url:'{{{ URL::to("scheduler/save_room_teacher") }}}',
                    type:'POST',
                    data: {
                        '_token' : $("input[name=_token]").val(),
                        'teacher_arr' : teacher_arr,
                    },
                    dataType: "json",
                    async:false,
                    success: function (data) 
                    {        
                        swal('Successfully Saved!');       
                    }  
                });

            }

            count_all++;
            if(count_teacher == count_all)
            {
                $.ajax({
                    url:'{{{ URL::to("scheduler/save_room_teacher") }}}',
                    type:'POST',
                    data: {
                        '_token' : $("input[name=_token]").val(),
                        'teacher_arr' : teacher_arr,
                    },
                    dataType: "json",
                    async:false,
                    success: function (data) 
                    {        
                        swal('Successfully Saved!');       
                    }  
                });
            }
        });

        
        // $.ajax({
        //     url:'{{{ URL::to("scheduler/save_room_teacher") }}}',
        //     type:'POST',
        //     data: {
        //         '_token' : $("input[name=_token]").val(),
        //         'teacher_arr' : teacher_arr,
        //     },
        //     dataType: "json",
        //     async:false,
        //     success: function (data) 
        //     {        
        //         swal('Successfully Saved!');       
        //     }  
        // });
    });
});

</script>
@stop