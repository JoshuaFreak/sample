@extends('site/layouts/default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.registrar") }}} :: @parent
@stop
{{-- Content --}}
@section('content')

<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
      @include('registrar_sidebar')
    </ul>
  </div>

</div>
<div id="page-wrapper">
    <div class="row">
<!-- Content Header -->
         <div class="col-md-12 page-header">
            <h2 class="col-md-8">{{ Lang::get("registrar.student_attendance")}}</h2>
            
        </div>
<!-- Table Section -->
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="col-md-12">
            <div class="form-group col-md-4 {{{ $errors->has('yr_n_sec_id') ? 'has-error' : '' }}}">
                    <label class="col-md-3 control-label" for="yr_n_sec_id">{!! Lang::get('registrar.yr_n_sec') !!}</label>
                    <div class="col-md-9">
                        <select class="form-control" name="yr_n_sec_id" id="yr_n_sec_id" tabindex="4">
                            @foreach($yr_n_sec_list as $yr_n_sec)
                                <option value="{{{$yr_n_sec->id}}}">{{{ $yr_n_sec->grade." ".$yr_n_sec->section }}}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('yr_n_sec_id', '<label class="control-label" for="yr_n_sec_id">:message</label>')!!}

                    </div>
                </div>  
              <!--   <div class="form-group col-md-3 {{{ $errors->has('month') ? 'has-error' : '' }}}">
                    <label class="col-md-3 control-label" for="month">{!! Lang::get('registrar.month') !!}</label>
                    <div class="col-md-9">
                        <input class="form-control datepicker" type="text" name="month" id="month" value="" />
                        {!! $errors->first('month', '<label class="control-label" for="month">:message</label>')!!}

                    </div>
                </div> -->
                <div class="form-group col-md-3 {{{ $errors->has('school_year_id') ? 'has-error' : '' }}}">
                    <label class="col-md-3 control-label" for="school_year_id">{!! Lang::get('registrar.school_year') !!}</label>
                    <div class="col-md-9">
                       <select class="form-control" name="school_year_id" id="school_year_id" tabindex="4">
                            @foreach($school_year_list as $school_year)
                                <option value="{{{$school_year->id}}}">{{{ $school_year->year }}}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('school_year_id', '<label class="control-label" for="school_year_id">:message</label>')!!}

                    </div>
                </div> 
                <div class="form-group col-md-2 {{{ $errors->has('school_year_id') ? 'has-error' : '' }}}">
                    <button id="load"type="button" class="btn btn-sm btn-success">
                    Load
                    </button>   
                </div>  
  
        </div> 
        <div class="col-md-12"><hr></div>
        <div class="col-md-12"> 
            <div class="col-md-2 pull-right">
                <button type="button" id="save_changes" class="col-md-8 btn btn-sm btn-primary">
                    <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
                </button>
            </div>
            <input type="hidden" id="day_counter" value=""></input>
            <div class="col-md-12"><hr></div>
            <table id="table" class="table table-bordered table-striped" >
                <thead>
                    <tr id="schedule_container">
                    </tr>
                </thead>
                <tbody id="student_attendance_container">
                </tbody>

            </table>
        </div>
    </div>
</div>
    

{{-- Scripts --}}
@section('scripts')

<script type="text/javascript">
        // var oTable;
        // $(document).ready(function() {
        //     oTable = $('#table').dataTable( {
        //         "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        //         "sPaginationType": "bootstrap",

        //         "bProcessing": true,
        //         "bServerSide": true,
        //         "sAjaxSource": "{{ URL::to('information/dataER?') }}"+"yr_n_sec_id="+$("#yr_n_sec_id").val()+"&school_year="+0,
        //         "fnDrawCallback": function ( oSettings ) {
                    
        //         }
        //     });

        // });
</script>


<script>
$(document).ready(function() {
    $("#table").hide();
});


// $(function() {

//         $('.datepicker').datepicker(
//         { 
//             startView: "months", 
//             minViewMode: "months",
//             format: 'MM ,yyyy',
//             orientation: "top left",
//             autoclose: true,

//         })

//     });


    $("#load").click(function(){
        $("#table").show();

        var yr_n_sec_id = $("#yr_n_sec_id").val();
        var school_year = $("#school_year").val();


        // var date = $("#month").val();
        // var date = new Date(date);
        // month = date.getMonth()+1;
        // year = date.getFullYear();
        // var batch = $("#batch_container").val();

        // var school_year = year+"-"+month+"-"+batch;

            
            $.ajax({
                url:'{{{ URL::to("student_attendance/dateDataJson") }}}',
                type:'GET',
                data:
                    {  
                        'yr_n_sec_id': yr_n_sec_id,
                        'school_year': school_year,
                    },
                dataType: "json",
                async:false,
                success: function (data) 
                {   


                    var count = 1;
                    $("#schedule_container").empty();
                    if(data == "")
                    {
                        alert("No Student");
                        $("#schedule_container").empty();
                        $("#table").hide();
                    }
                    $("#schedule_container").append(' <th>No.</th><th>Name</th>');

                    $.each(data, function(key, value) {
                        $("#schedule_container").append('<th id="day_'+count+'" data-id="'+value.day_id+'">'+value.day_name+'</th>');
                        $("#day_counter").val(count);
                        count++;
                    });

                    $.ajax( 
                    {
                        url:'{{{ URL::to("student_attendance/StudentDataJson") }}}',
                        type:'GET',
                        data:
                            {  
                                'yr_n_sec_id': yr_n_sec_id,
                                'school_year': school_year,
                            },
                        dataType: "json",
                        async:false,
                        success: function (data) 
                        {       
                            var count = 1;

                            $("#student_attendance_container").empty();


                            if(data == "")
                            {
                                // alert("No Trainee");
                            }
                            $.each(data, function(key, value) {
                                
                                $("#student_attendance_container").append(''
                                    +'<tr id="student_attendance_'+value.student_id+'"><td>'+count+'.<input name="student_id" type="hidden" value="'+value.student_id+'" /></td><td>'+value.last_name+' '+value.suffix_name+' '+value.suffix_name+' '+value.first_name+' '+value.middle_name+'</td>'
                                    +'</tr>');


                                    var day_counter = $("#day_counter").val();
                                    day_counter = parseInt(day_counter);

                                    for(var i = 1; day_counter >= i; i++)
                                    {
                                        var day_id = $("#day_"+i).data('id');
                                        $("#student_attendance_"+count).append('<td id="day_'+day_id+'"></td>');
                                    }

                                    // $.ajax({
                                    //     url:'{{{ URL::to("student_attendance/dateDataJson") }}}',
                                    //     type:'GET',
                                    //     data:
                                    //         {  
                                    //             'yr_n_sec_id': yr_n_sec_id,
                                    //             'school_year': school_year,
                                    //         },
                                    //     dataType: "json",
                                    //     async:false,
                                    //     success: function (data) 
                                    //     {   
                                    //         if(data == "")
                                    //         {
                                    //             // alert("No Trainee");
                                    //         }
                                    //         $.each(data, function(key, value) {
                                    //             $("#student_attendance_"+count).append('<td id="day_'+value.day_id+'"></td>');

                                    //         });
                                    //     }

                                    // });

                                count++;
                            });
                            
                            $.ajax( 
                            {
                                url:'{{{ URL::to("student_attendance/dataJson") }}}',
                                type:'GET',
                                data:
                                    {  
                                        'yr_n_sec_id': yr_n_sec_id,
                                        'school_year': school_year,
                                    },
                                dataType: "json",
                                async:false,
                                success: function (data) 
                                {       
                                    var count = 1;
                                    if(data == "")
                                    {
                                        // alert("No Trainee");
                                    }
                                    $.each(data, function(key, value) {

                                        // $("#student_attendance_"+value.student_id+" > #day_"+value.day_id).append(''
                                        //             +'<select class="form-control" name="attendance_remark_id" id="attendance_remark_id" tabindex="4">'
                                        //                 +'@foreach($attendance_remark_list as $attendance_remark)'
                                        //                     +'@if($attendance_remark->id == '+value.day_id+')'
                                        //                     +'<option name="attendance_remark_id" value="{{{ $attendance_remark->id }}}" selected>{{{ $attendance_remark->attendance_remark_code }}}</option>'
                                        //                     +'@else'
                                        //                     +'<option name="attendance_remark_id" value="{{{ $attendance_remark->id }}}">{{{ $attendance_remark->attendance_remark_code }}}</option>'
                                        //                     +'@endif'
                                        //                 +'@endforeach'
                                        //             +'</select>');
                                        // $("#student_attendance_"+value.student_id+" > #day_"+value.day_id).append('<div col-md-12 class="day_id" ><input id="student_attendance_id" type="hidden" value="'+value.id+'">'
                                        //     +'<label class="control-label col-md-2">AM<input id="am" name="am" type="text" class="am form-control col-md-2" value="'+value.am+'"><span><button type="button" class="edit_am btn btn-sm btn-primary">edit</button></span></input></label><label class="control-label col-md-2">PM<input id="pm" name="pm" type="text" class="pm form-control" value="'+value.pm+'">'
                                        //     +'<span><button type="button" class="edit_pm btn btn-sm btn-primary">edit</button></span></input></label></div>');
                                        $("#student_attendance_"+value.student_id+" > #day_"+value.day_id).append('<div col-md-12 class="day_id" ><input id="student_attendance_id" name="student_attendance_id" type="hidden" value="'+value.id+'">'
                                            +'<label class="control-label col-md-2">AM<input id="am" name="am" type="text" class="am form-control col-md-2" value="'+value.am+'"></input></label><label class="control-label col-md-2">PM<input id="pm" name="pm" type="text" class="pm form-control" value="'+value.pm+'">'
                                            +'</input></label></div>');
                                        count++;
                                    });
                                },

                            });

                        },

                    });

                    
                }
            });

         
            

            
    });

    // $("#month").change(function(){

    //     var date = $("#month").val();
    //     var date = new Date(date);
    //     month = date.getMonth()+1;
    //     year = date.getFullYear();

    //     var counter = weekCount(year, month);

    //     $("#batch_container").empty();

    //     var prefix = ['0','st','nd','rd','th','th','th','th'];
    //     for(var i=1; i <= counter; i++)
    //     {

    //         $("#batch_container").append('<option value="'+i+'">'+i+''+prefix[i]+' week</option>');
    //     }
    // });

    // function weekCount(year, month_number) {

    //     // month_number is in the range 1..12

    //     var firstOfMonth = new Date(year, month_number-1, 1);
    //     var lastOfMonth = new Date(year, month_number, 0);

    //     var used = firstOfMonth.getDay() + lastOfMonth.getDate();

    //     return Math.ceil( used / 7);
    // }


    $("#save_changes").click(function(){

        $("#table > tbody > tr > td >.day_id").each(function(){
            
                var am = $(this).find('input[name="am"]').val();
                var pm = $(this).find('input[name="pm"]').val();

                var student_attendance_id = $(this).find('input[name="student_attendance_id"]').val();
                
                $.ajax(
                {
                    url:'{{{ URL::to("student_attendance/saveAttendance") }}}',
                    type:'post',
                    data:
                        {   'student_attendance_id': student_attendance_id,
                            'am': am,
                            'pm': pm,
                            '_token': $('input[name=_token]').val(),
                        },
                        async:false,
                    success: function(){
                       
                    }
                }).done(function(){
                     
                }); 
        });
        alert("Successfully Save!");
    });

</script>
@stop
@stop

