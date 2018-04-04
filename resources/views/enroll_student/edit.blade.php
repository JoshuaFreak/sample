@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.edit_student") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<link rel="stylesheet" href="{{asset('assets/site/datepicker/ui/1.11.4/themes/smoothness/jquery-ui.css')}}">
<script src="{{asset('assets/site/datepicker/jquery-1.10.2.js')}}"></script>
<script src="{{asset('assets/site/datepicker/ui/1.11.4/jquery-ui.js')}}"></script>
<script>
    $(function() {
    $( "#birthdate" ).datepicker({
    	dateFormat: "yy-mm-dd",
      	changeMonth: true,
      	changeYear: true
    });
});
</script>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
            @include('enrollment_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header"><br>
			<h3> {{{ Lang::get("student.edit_student") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('enroll_student') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("student.student_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('enroll_student/' . $enrollment->id . '/edit') }}" autocomplete="off">
			@include('enroll_student.form_edit')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-3">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			            </button>	
			 			<a href="{{{ URL::to('enroll_student') }}}" class="btn btn-sm btn-warning close_popup">
			                <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
			       		</a>
			        </div>
			    </div>
			</div>	
		</form>
    </div>
</div>	
@stop
@section('scripts')
<script type="text/javascript">
   $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
	$(function() {
		$("#roles").select2()
          $("#registered_student_classification_id").change(function(){
              selectListChange('classification_level_id','{{{URL::to("classification_level/dataJson")}}}',  { 'is_active':1 , 'classification_id': $("#registered_student_classification_id").val() } ,'Please select a Classification')
          });
          $("#classification_level_id").change(function(){
              selectListChange('semester_level_id','{{{URL::to("semester_level/dataJson")}}}',  { 'is_active':1 , 'classification_level_id': $(this).val() } ,'Please select a Classification Level')
          });
      });

	//term
	$.ajax({
        url:'{{{URL::to("term/dataJson")}}}',
        type:'GET',
        data:
            {  
                'classification_id': $("#classification_id").val(),
            },
        dataType: "json",
        async:false,
        success: function (data) 
        {    
            
            $("#term_id").empty();
            $("#term_id").append('<option value=""></option>');
            $.map(data, function (item) 
            {      
                    $("#term_id").append('<option value="'+item.value+'">'+item.text+'</option>');
            });
        }  

    });
	   
    $("#term_id [value={{{ $term->id }}}]").attr("selected","selected");

    //classification_level
	$.ajax({
        url:'{{{URL::to("classification_level/dataJson")}}}',
        type:'GET',
        data:
            {  
                'classification_id': $("#classification_id").val(),
            },
        dataType: "json",
        async:false,
        success: function (data) 
        {    
            
            $("#classification_level_id").empty();
            $("#classification_level_id").append('<option value=""></option>');
            $.map(data, function (item) 
            {      
                    $("#classification_level_id").append('<option value="'+item.value+'">'+item.text+'</option>');
            });
        }  

    });

    $("#classification_level_id [value={{{ $classification_level->id }}}]").attr("selected","selected");

    //section
	$.ajax({
        url:'{{{URL::to("section/dataJson")}}}',
        type:'GET',
        data:
            {  
                'classification_level_id': $("#classification_level_id").val(),
            },
        dataType: "json",
        async:false,
        success: function (data) 
        {    
            
            $("#section_id").empty();
            $("#section_id").append('<option value=""></option>');
            $.map(data, function (item) 
            {      
                    $("#section_id").append('<option value="'+item.value+'">'+item.text+'</option>');
            });
        }  

    });

    $("#section_id [value={{{ $section->id }}}]").attr("selected","selected");
</script>
@stop
