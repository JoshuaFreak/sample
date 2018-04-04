@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.student") }}} :: @parent
@stop
{{-- Content --}}
@section('content')

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>


<!-- Side Bar -->
    <div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
                @include('student_sidebar')
        </ul>
      </div>
    </div>

<div id="page-wrapper">
    <div class="row">
        <div class="page-header">
            <br/>
        		<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
        			{{{ Lang::get("student.student_list") }}}
                @if(Auth::user()->username)
                <button type="button" class="btn btn-sm btn-default pull-right" style="margin-right: 20px;" id="save_all">Update Student</button>
                @endif
        		</h3>
        </div>
      	<input type="hidden" name="_token" value="{!! csrf_token() !!}" />
        <table id="table" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Student Id</th>
                    <th>Nickname</th>
                    <th>Student English Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- <tbody id="student_list"></tbody> -->
        </table>

    </div>
</div>

@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
$(function(){
        
        $("#save_all").click(function(){
            var studentJsonArray;
            $.ajax({
                url:'{{ URL::to("http://cebucia.com/api/student_list_check_in.php") }}',
                type:'GET',
                data: {
                    "_token": "L3tM3P@55!",
                },
                dataType: "json",
                async:false,
                success: function (data) 
                {                  
                    studentJsonArray = data.data;
                }  
                ,error: function (data)
                {
                    console.log(data);
                    alert("error");
                }

            });
            var arrayLength = studentJsonArray.length;
            var data_arr = [];
            var total_count = 0;
            var count = 0;
            var data_counter = 0;
            for (var i = 0; i < arrayLength; i++) {
                data_counter++;
            }
            for (var i = 0; i < arrayLength; i++) {
                
                // data_arr[i] = { 'abrod_date' : studentJsonArray[i]['abrod_date'],'end_date' : studentJsonArray[i]['end_date'],'gender_id' : studentJsonArray[i]['gender_id'],'nationality_id' : studentJsonArray[i]['nationality_id'],'nick' : studentJsonArray[i]['nick'],'period' : studentJsonArray[i]['period'],'sename' : studentJsonArray[i]['sename'],'state' : studentJsonArray[i]['state'],'student_id' : studentJsonArray[i]['student_id'] };
                if(count < 40)
                {
                    data_arr[count] = { 'abrod_date' : studentJsonArray[i]['abrod_date'],'end_date' : studentJsonArray[i]['end_date'],'gender_id' : studentJsonArray[i]['gender_id'],'nationality_id' : studentJsonArray[i]['nationality_id'],'nick' : studentJsonArray[i]['nick'],'period' : studentJsonArray[i]['period'],'sename' : studentJsonArray[i]['sename'],'semail' : studentJsonArray[i]['semail'],'state' : studentJsonArray[i]['state'],'student_id' : studentJsonArray[i]['student_id'],'refund' : studentJsonArray[i]['refund'] };

                    count++;
                }
                else
                {   
                    count = 0;
                    $.ajax({
                        url:'{{{ URL::to("student/saveAllStudentData") }}}',
                        type:'POST',
                        data: {
                            '_token' : $("input[name=_token]").val(),
                            'studentJsonArray' : data_arr,
                        },
                        dataType: "json",
                        async:false,
                        success: function (data) 
                        {        
                            // swal("Save Successfully!");       
                        }  
                    });
                    data_arr = [];
                }

                total_count++;
                if(data_counter == total_count )
                {
                    $.ajax({
                        url:'{{{ URL::to("student/saveAllStudentData") }}}',
                        type:'POST',
                        data: {
                            '_token' : $("input[name=_token]").val(),
                            'studentJsonArray' : data_arr,
                        },
                        dataType: "json",
                        async:false,
                        success: function (data) 
                        {        
                            swal("Save Successfully!");       
                        }  
                    });
                }

            } 
        });
        // $.ajax({
        //     url:'{{ URL::to("http://cebucia.com/api/student_list_check_in.php") }}',
        //     type:'GET',
        //     data: {
        //         "_token": "L3tM3P@55!",
        //     },
        //     dataType: "json",
        //     async:false,
        //     success: function (data) 
        //     {                  
        //         studentJsonArray = data.data;
        //         alert(studentJsonArray);
        //     }  
        //     ,error: function (data)
        //     {
        //         console.log(data);
        //         alert(data.responseText());
        //     }

        // });

            var table = $('#table').DataTable();
            $.getJSON('http://cebucia.com/api/student_data_list.php?_token=L3tM3P@55!&date='+$("#date").val(), null, function ( json ) {
                table.destroy();
                // $('#table').empty(); // empty in case the columns change

                table = $('#table').DataTable( {
                    //columns: json.columns,
                    //data:    json.rows
                    data:json.data,
                    // "columns": [
                    // { "data": "student_id" },
                    // { "data": "nick" },
                    // { "data": "sename" }
                    // ],
                    "columnDefs": [ {
                        "targets": 3,
                        "data": null,
                        "defaultContent": "<button class='btn btn-sm btn-default view' type='button'>More</button>"
                    } ],
                    "iDisplayLength": 50
                });

                $('#table tbody').on('click', '.view', function (e) {
                    var data = table.row( $(this).parents('tr') ).data();
                    window.open('{{ URL::to("student/view_data?student_id=") }}'+data[0],'_blank');
                });

            });
       // $('#table').DataTable( {
       //    "ajax": {
       //      "url": "{{ URL::to('http://cebucia.com/api/student_list_check_in.php') }}",
       //      "type" :"GET",
       //      "data": {
       //          "_token": "L3tM3P@55!",
       //          "check_in_date": $("#check_in_date").val(),
       //          "check_out_date": $("#check_out_date").val(),
       //      },
       //      "dataType": "json",
       //      "async": false,
       //    }
       //  });

    // $.ajax({
    //     url:'{{ URL::to("http://cebucia.com/api/student_list_check_in.php") }}',
    //     type:'GET',
    //     data: {
    //         "_token": "L3tM3P@55!",
    //         "check_in_date": $("#check_in_date").val(),
    //         "check_out_date": $("#check_out_date").val(),
    //     },
    //     dataType: "json",
    //     async:false,
    //     success: function (data) 
    //     {                  
    //         studentJsonArray = data.data;

    //         $.map(studentJsonArray,function(item){
    //             $("#student_list").append('<tr><td>'+item.student_id+'</td><td>'+item.nick+'</td><td>'+item.sename+'</td><td><button class="btn btn-sm btn-default" type="button" data-student_id="'+item.student_id+'">More</button></td></tr>')
    //         });
    //     }  

    // });
});
</script>
@stop
