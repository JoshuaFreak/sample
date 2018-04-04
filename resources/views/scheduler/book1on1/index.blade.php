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
        ESL Books
          <button id="save_room" type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 20px">Update Level Books</button>
      </h3>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <h3>ESL Books</h3>
    <div style="width: 100%;height: 1550px;overflow-x: scroll">
        <table class="table">
            <tr>
                <th>Level</th>
                <th>Module</th>
                @foreach($esl_subject_list as $esl_subject)
                    <th>{{$esl_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=0; $i <= 10; $i++)
                <tr>
                    <td>{{$i}}</td>
                    <td><p style="color:#003374">Book Name</p><br><br><p style="color:#008900">Supplementary</p></td>
                    @foreach($esl_subject_list as $esl_subject)
                        <td>
                            <textarea id="room_book_{{ $esl_subject -> id.'_'.$i }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-course_id="{{ $esl_subject -> id }}" data-level="{{ $i }}" name="" ></textarea>
                            <br>
                            <textarea id="room_book_supplementary_{{ $esl_subject -> id.'_'.$i }}" style="font-size: 12px; width:180px " type="text" class="form-control" data-course_id="{{ $esl_subject -> id }}" data-level="{{ $i }}" name="" ></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>   
  </div>
</div>

@stop
{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

$(function(){

    $.ajax({
            url:'{{{ URL::to("scheduler/get_esl_book") }}}',
            type:'GET',
            data: {
                '_token' : $("input[name=_token]").val(),
            },
            dataType: "json",
            async:false,
            success: function (data) 
            {        
                $.map(data,function(item){
                    $("#room_book_"+item.course_id+"_"+item.level).val(item.book_title);
                    $("#room_book_supplementary_"+item.course_id+"_"+item.level).val(item.supplementary_book);
                });      
            }  
        });

    $("#save_room").click(function(){
        var book_arr = [];
        var count = 0;
        $(".book_title").each(function(){
            book = $(this).val();
            course_id = $(this).data('course_id');
            level = $(this).data('level');

            supplementary_book = $("#room_book_supplementary_"+course_id+"_"+level).val();
            book_arr[count] = [book,course_id,level,supplementary_book];
            count++;
        });

        $.ajax({
            url:'{{{ URL::to("scheduler/save_esl_book") }}}',
            type:'POST',
            data: {
                'book_arr' : book_arr,
                '_token' : $("input[name=_token]").val(),
            },
            dataType: "json",
            async:false,
            success: function (data) 
            {        
                swal('Successfully Saved!');       
            }  
        });
    });
});

</script>
@stop