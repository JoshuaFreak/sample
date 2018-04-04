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
        Working Holiday Books
          <button id="save_room" type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 20px">Update Working Holiday Books</button>
      </h3>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <h3>Working Holiday Books(Australia)</h3>
    <div style="width: 100%;height: 290px;overflow-x: scroll;">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($working_au_subject_list as $working_au_subject)
                    <th>{{$working_au_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 3; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($working_au_subject_list as $working_au_subject)
                        <td>
                            <textarea id="room_book_{{ $working_au_subject -> id.'_'.$i.'_'.$working_au_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $working_au_subject -> program_id }}" data-course_id="{{ $working_au_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
                        </td>
                    @endforeach            
                </tr>
            @endfor
        </table>
    </div> 
    <h3>Working Holiday Books(Canada)</h3>
    <div style="width: 100%;height: 290px;overflow-x: scroll;">
        <table class="table">
            <tr>
                <th>Month</th>
                @foreach($working_ca_subject_list as $working_ca_subject)
                    <th>{{$working_ca_subject -> course_name}}</th>
                @endforeach
            </tr>
            @for($i=1; $i <= 3; $i++)
                <tr>
                    <td>{{$i}}</td>
                    @foreach($working_ca_subject_list as $working_ca_subject)
                        <td>
                            <textarea id="room_book_{{ $working_ca_subject -> id.'_'.$i.'_'.$working_ca_subject -> program_id }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-program_id="{{ $working_ca_subject -> program_id }}" data-course_id="{{ $working_ca_subject -> id }}" data-month="{{ $i }}" name="" ></textarea>
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
            url:'{{{ URL::to("scheduler/get_working_book") }}}',
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
            url:'{{{ URL::to("scheduler/save_working_book") }}}',
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