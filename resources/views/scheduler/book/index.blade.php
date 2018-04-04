@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.schedule") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<style>
#HeaderRow
{   
    width: 2400px;
    top: 0px;
    position: sticky !important;
    position: -webkit-sticky;
}
.div-table-col{
  float:left;/*fix for  buggy browsers*/
  display:table-column;         
  width: 200px;
  height: 40px;
  /*background-color: #646464; */
  background-color: #C0C0C0; 
  /*border:1px solid #DDDDDD;  */
  border:1px solid #000;  
  padding-top: 7px;
  color:#000;  
}
.div-table-col-sec{
  float:left;/*fix for  buggy browsers*/
  display:table-column;         
  width: 200px;
  height: 110px;
  /*background-color: #646464; */
  /*background-color: #fff; */
  /*border:1px solid #DDDDDD;  */
  border:1px solid #6F8C80;  
  padding-top: 7px;
  color:#000;  
}
.color:nth-child(even) .div-table-col-sec {background-color: #F1F1F1 !important}
.color:nth-child(odd) .div-table-col-sec {background-color: #FFF !important}
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
        {{{ Lang::get("scheduler.books_per_room") }}}
          <button id="save_room" type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 20px">Update Room Books</button>
      </h3>
    </div>
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div style="width: 100%;height: 500px;overflow: scroll;z-index: 2">
        <div id="HeaderRow"  style="z-index: 3">
                <div class="div-table-col" align="center">Room Name</div>
                @for($i=1;$i <= 11; $i++)
                    <div class="div-table-col" align="center">Period {{$i}}</div>
                @endfor
        </div>
        <div style="width: 2400px;">
            @foreach($room_list as $room)
                <div class="color">
                <div class="div-table-col-sec" align="center">{{ $room -> room_name }}</div>
                @for($i=1;$i <= 11; $i++)
                    <div class="div-table-col-sec" align="center">
                        <textarea id="room_book_{{ $room -> id.'_'.$i }}" style="font-size: 12px; width:180px " type="text" class="form-control book_title" data-room_id="{{ $room -> id }}" data-class_id="{{ $i }}" name="" ></textarea>
                        <br>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <select name="level_from" id="room_book_from_{{ $room -> id.'_'.$i }}">
                                    <option value="0"></option>
                                    @foreach($level_list as $level)
                                        <option value="{{$level -> id}}">{{$level -> level_code}}</option>
                                    @endforeach                            
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="level_to" id="room_book_to_{{ $room -> id.'_'.$i }}">
                                    <option value="0"></option>
                                    @foreach($level_list as $level)
                                        <option value="{{$level -> id}}">{{$level -> level_code}}</option>
                                    @endforeach                            
                                </select>
                            </div>
                        </div>
                        <!-- <br><textarea type="text" class="form-control" name=""></textarea> -->
                    </div>
                @endfor
                </div>
            @endforeach
        </div>
    </div>
   
  </div>
</div>

@stop
{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">

$(function(){

    $.ajax({
            url:'{{{ URL::to("scheduler/get_room_book") }}}',
            type:'GET',
            data: {
                '_token' : $("input[name=_token]").val(),
            },
            dataType: "json",
            async:false,
            success: function (data) 
            {        
                $.map(data,function(item){
                    $("#room_book_"+item.room_id+"_"+item.class_id).val(item.book_title);
                    $("#room_book_from_"+item.room_id+"_"+item.class_id+" [value='"+item.level_from_id+"']").attr("selected","selected");
                    $("#room_book_to_"+item.room_id+"_"+item.class_id+" [value='"+item.level_from_id+"']").attr("selected","selected");
                });      
            }  
        });

    $("#save_room").click(function(){
        var book_arr = [];
        var count_book = 0;
        var count = 0;
        var count_all = 0;
        $(".book_title").each(function(){
            count_book++;
        });
        $(".book_title").each(function(){

          if(count < 50)
          {
            book = $(this).val();
            room_id = $(this).data('room_id');
            class_id = $(this).data('class_id');
            level_from = $("#room_book_from_"+room_id+"_"+class_id).val();
            level_to = $("#room_book_to_"+room_id+"_"+class_id).val();
            book_arr[count] = [book,room_id,class_id,level_from,level_to];
            count++;
          }
          else
          {
            count = 0;
            $.ajax({
                url:'{{{ URL::to("scheduler/save_room_book") }}}',
                type:'POST',
                data: {
                    '_token' : $("input[name=_token]").val(),
                    'book_arr' : book_arr,
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
          if(count_book == count_all)
          {
            $.ajax({
                url:'{{{ URL::to("scheduler/save_room_book") }}}',
                type:'POST',
                data: {
                    '_token' : $("input[name=_token]").val(),
                    'book_arr' : book_arr,
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
        //     url:'{{{ URL::to("scheduler/save_room_book") }}}',
        //     type:'POST',
        //     data: {
        //         '_token' : $("input[name=_token]").val(),
        //         'book_arr' : book_arr,
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