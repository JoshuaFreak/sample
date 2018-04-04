@extends('site/layouts/default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("scheduler.scheduler") }}} :: @parent
@stop
{{-- Content --}}
@section('content')

<div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">    
          <ul class="nav" id="side-menu"> 
          @include('scheduler_sidebar')
        </ul>
      </div>

</div>
<div id="page-wrapper">
    <div class="row">

        <div class="page-header">
          <h2>{{ Lang::get("scheduler.filter_instructor")}}</h2>
        </div>
<!-- Table Section -->
        <div class="col-md-12">
                <div class="form-group col-md-12" >
<!-- 
                    <div class="col-md-1">
                        <label class="label-control" for="date">Date:</label>
                    </div>
                    <div class="col-md-5" >
                        <div class="col-md-12 input-daterange input-group" id="datepicker">
                            <input type="text" class="input-sm form-control" id="date_start" name="start" value="" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="date_end" name="end" value="" />
                        </div>  
                    </div> -->
                    <div class="form-group col-md-4 {{{ $errors->has('month') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="month">{!! Lang::get('scheduler.instructor') !!}</label>
                        <div class="col-md-9">
                            <select id="instructor_id" class="form-control">
                                <option name="" value="" ></option>
                                @foreach($instructor_list as $instructor)
                                    <option name="instructor_id" value="{{{ $instructor->id }}}" >{{{ $instructor->first_name." ".$instructor->last_name }}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" id="load" class="btn btn-sm btn-success">
                            <span class="glyphicon glyphicon-ok-circle"></span> Load
                        </button>
                    </div>
                    
                </div>

        </div>
        <div class="form-group col-md-12">
                <table class="table table-bordered ">
                	<th>Grade & Section</th>
                    <th>Teacher</th>
                    <th>Subject</th>
                	<th>Time</th>
                <tbody id="table_container">

                </tbody >
                </table>
        </div>
    </div>
</div>

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript"> 
    $(function(){
        $('#datepicker').datepicker({
            format: "yyyy-mm-dd",
            orientation: "top left",
            autoclose: true,
        });

    });

    $("#load").click(function(){
        $.ajax({
                    url:'{{{ URL::to("instructorSchedulerDataJson")}}}',
                    data: {
                            // 'date_start': $("#date_start").val(),
                            // 'date_end': $("#date_end").val(),
                            'instructor_id': $("#instructor_id option:selected").val(),
                        },
                    type: "GET", 
                    dataType: "json",
                    async:false,
                    success: function (data) 
                    {
                        $("#table_container").empty();
                        if (!$.trim(data)){
                            alert("No Schedule");
                        }

                         var count = 0;
                       
                        $.map(data, function (item) 
                        {

                               count = count + 1;
                           
                                $("#table_container").append('<tr>'
                                +'<td><input id="yr_n_sec_'+count+'" name="date" class="form-control" type="hidden" value="'+item.id+'"/>'
                                +'<td><input id="instructor_'+count+'" name="instructor" class="form-control" type="text" value="'+item.first_name+' '+item.middle_name+' '+item.last_name+' '+item.suffix_name+'"/></td>'
                                +'<td><input id="yr_n_sec_'+count+'" name="yr_n_sec" class="form-control" type="text" value="'+item.yr_n_sec+'"/></td>'
                                +'<input id="subject_'+count+'" name="subject" class="form-control" type="text" value="'+item.subject+'"/></td>'
                                +'<td><input id="time_'+count+'" name="time" class="form-control" type="text" value="'+item.time_start+'"/></td>'
                                +'</tr>');
                                
                        });
                        
                       
                    }
            });
    }); 
</script>
@stop
@stop