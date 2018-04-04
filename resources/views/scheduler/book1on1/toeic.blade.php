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
        TOEIC Books
          <button id="save_room" type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 20px">Update TOEIC Books</button>
      </h3>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <h3>TOEIC Books(Regular & Guarantee)</h3>
    <div style="width: 100%;height: 470px;overflow-x: scroll">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                    <th>{{$toeic_reg_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 6; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                        <td>
                            <textarea id="room_book_{{ $toeic_reg_subject -> id.'_'.$i.'_'.$toeic_reg_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $toeic_reg_subject -> program_id }}" data-course_id="{{ $toeic_reg_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>
    <h3>TOEIC Books(TOEIC 700)</h3>
    <div style="width: 100%;height: 470px;overflow-x: scroll">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                    <th>{{$toeic_reg_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 6; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                        <td>
                            <textarea id="room_book_{{ $toeic_reg_subject -> id.'_'.$i.'_'.$toeic_reg_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $toeic_reg_subject -> program_id }}" data-course_id="{{ $toeic_reg_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>
    <h3>TOEIC Books(TOEIC 800)</h3>
    <div style="width: 100%;height: 470px;overflow-x: scroll">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                    <th>{{$toeic_reg_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 6; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                        <td>
                            <textarea id="room_book_{{ $toeic_reg_subject -> id.'_'.$i.'_'.$toeic_reg_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $toeic_reg_subject -> program_id }}" data-course_id="{{ $toeic_reg_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>
    <h3>TOEIC Books(TOEIC 900)</h3>
    <div style="width: 100%;height: 470px;overflow-x: scroll">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                    <th>{{$toeic_reg_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 6; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($toeic_reg_subject_list as $toeic_reg_subject)
                        <td>
                            <textarea id="room_book_{{ $toeic_reg_subject -> id.'_'.$i.'_'.$toeic_reg_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $toeic_reg_subject -> program_id }}" data-course_id="{{ $toeic_reg_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>
    <h3>TOEIC Books(Pre-toeic)</h3>
    <div style="width: 100%;height: 270px;overflow-x: scroll">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($toeic_pre_subject_list as $toeic_pre_subject)
                    <th>{{$toeic_pre_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 3; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($toeic_pre_subject_list as $toeic_pre_subject)
                        <td>
                            <textarea id="room_book_{{ $toeic_pre_subject -> id.'_'.$i.'_'.$toeic_pre_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $toeic_pre_subject -> program_id }}" data-course_id="{{ $toeic_pre_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div>
    <h3>TOEIC Books(Bridge)</h3>
    <div style="width: 100%;height: 270px;overflow-x: scroll">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($toeic_bridge_subject_list as $toeic_bridge_subject)
                    <th>{{$toeic_bridge_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 3; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($toeic_bridge_subject_list as $toeic_bridge_subject)
                        <td>
                            <textarea id="room_book_{{ $toeic_bridge_subject -> id.'_'.$i.'_'.$toeic_bridge_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $toeic_bridge_subject -> program_id }}" data-course_id="{{ $toeic_bridge_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
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
            url:'{{{ URL::to("scheduler/get_toeic_book") }}}',
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
            book_arr[count] = [book,course_id,month,program_id];
            count++;
        });

        $.ajax({
            url:'{{{ URL::to("scheduler/save_toeic_book") }}}',
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