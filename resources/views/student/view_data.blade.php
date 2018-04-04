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
        			{{{ Lang::get("student.student_details") }}}

        		</h2>
        </div>
      	<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      	<div class="col-md-12">
      	     <div class="form-group {{{ $errors->has('student_id') ? 'has-error' : '' }}}">
          			<label class="col-md-1 control-label" for="student_name">{!! Lang::get('student.student') !!}</label>
                <input type="hidden" name="student_id" id="student_id" value="{{$_GET['student_id']}}" />
                <div class="col-md-4">
          					<input class="form-control" type="text" name="student_name" id="student_name" value=""/>
          				{!! $errors->first('student_id', '<label class="control-label" for="student_name">:message</label>')!!}
          			</div>
                <div class="col-md-3">                </div>
      		  </div>
        </div>
    </div>
</div>

@stop

{{-- Scripts --}}
@section('scripts')
<script>
$(function(){

    $.ajax({
        url:'{{ URL::to("http://cebucia.com/api/get_student_data.php") }}',
        type:'GET',
        data: {
            "_token": "L3tM3P@55!",
            "student_id": $("#student_id").val(),
            "check_in_date": $("#check_in_date").val(),
            "check_out_date": $("#check_out_date").val(),
        },
        dataType: "json",
        async:false,
        success: function (data) 
        {                  
            studentJsonArray = data.data;

            $(jQuery.parseJSON(JSON.stringify(studentJsonArray))).each(function() {
                    student_data = this;

                    // alert(student_data[0]);
            });
        }  

    });
});
</script>

@stop
