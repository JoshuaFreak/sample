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
        IELTS Books
          <button id="save_room" type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 20px">Update IELTS Books</button>
      </h3>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <h3>IELTS Books(Regular & Guarantee)</h3>
    <div style="width: 100%;height: 860px;">
        <table class="table">
            <tr>
                <th>Month</th>
                <th>Module</th>
                @foreach($ielts_reg_subject_list as $ielts_reg_subject)
                    <th>{{$ielts_reg_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 6; $i++)
                <tr>
                    <td>{{$i}}</td>
                    <td><p style="color:#003374">Academic</p><br><br><p style="color:#008900">General</p></td>
                    @foreach($ielts_reg_subject_list as $ielts_reg_subject)
                        <td>
                            <textarea id="room_book_{{ $ielts_reg_subject -> id.'_'.$i.'_'.$ielts_reg_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $ielts_reg_subject -> program_id }}" data-course_id="{{ $ielts_reg_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                            <br>
                            <textarea id="room_book_general_{{ $ielts_reg_subject -> id.'_'.$i.'_'.$ielts_reg_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control" name=""></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>
    <h3>IELTS Books(Pre-IELTS)</h3>
    <div style="width: 100%;height: 480px;">
        <table class="table">
            <tr>
                <th>Month</th>
                <th>Module</th>
                @foreach($ielts_pre_subject_list as $ielts_pre_subject)
                    <th>{{$ielts_pre_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 3; $i++)
                <tr>
                    <td>{{$i}}</td>
                    <td><p style="color:#003374">Academic</p><br><br><p style="color:#008900">General</p></td>
                    @foreach($ielts_pre_subject_list as $ielts_pre_subject)
                        <td>
                            <textarea id="room_book_{{ $ielts_pre_subject -> id.'_'.$i.'_'.$ielts_pre_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $ielts_pre_subject -> program_id }}" data-course_id="{{ $ielts_pre_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                            <br>
                            <textarea id="room_book_general_{{ $ielts_pre_subject -> id.'_'.$i.'_'.$ielts_pre_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control" name=""></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>
    <h3>IELTS Books(Bridge)</h3>
    <div style="width: 100%;height: 240px;">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($ielts_bridge_subject_list as $ielts_bridge_subject)
                    <th>{{$ielts_bridge_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 3; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($ielts_bridge_subject_list as $ielts_bridge_subject)
                        <td>
                            <textarea id="room_book_{{ $ielts_bridge_subject -> id.'_'.$i.'_'.$ielts_bridge_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $ielts_bridge_subject -> program_id }}" data-course_id="{{ $ielts_bridge_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
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
            url:'{{{ URL::to("scheduler/get_ielts_book") }}}',
            type:'GET',
            data: {
                '_token' : $("input[name=_token]").val(),
            },
            dataType: "json",
            async:false,
            success: function (data) 
            {        
                $.map(data,function(item){
                    $("#room_book_"+item.course_id+"_"+item.month+"_"+item.program_id).val(item.book_title);
                    $("#room_book_general_"+item.course_id+"_"+item.month+"_"+item.program_id).val(item.book_title_general);
                });      
            }  
        });

    $("#save_room").click(function(){
        var book_arr = [];
        var count = 0;
        $(".book_title").each(function(){
            book = $(this).val();
            course_id = $(this).data('course_id');
            program_id = $(this).data('program_id');
            month = $(this).data('month');
            book2 = $("#room_book_general_"+course_id+"_"+month+"_"+program_id).val();
            book_arr[count] = [book,course_id,month,program_id,book2];
            count++;
        });

        $.ajax({
            url:'{{{ URL::to("scheduler/save_ielts_book") }}}',
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