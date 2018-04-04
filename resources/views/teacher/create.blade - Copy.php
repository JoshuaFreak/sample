@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("teacher.create_new_teacher") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">    
      <ul class="nav" id="side-menu"> 
      @include('hrms_sidebar')
    </ul>
  </div>

</div>
<div id="page-wrapper">
    <div class="row">
    	@include('notifications')
		<div class="page-header">
			<h3> {{{ Lang::get("teacher.create_new_teacher") }}}
				<div class="pull-right">
					<div class="pull-right">
			            <a href="{{{ URL::to('teacher') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("teacher.teacher_list") }}</a>
			        </div>
				</div>
			</h3>
		</div>

		<form class="form-horizontal" method="post" action="{{ URL::to('teacher/create') }}" autocomplete="off">
			@include('teacher.form')
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label" for="actions">&nbsp;</label>
					<div class="col-md-9">	
			 			<button type="submit" class="btn btn-sm btn-success">
			                <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.create") }}
			            </button>	
			            <button type="reset" class="btn btn-sm btn-default">
			                <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
			            </button>	
			 			<a href="{{{ URL::to('teacher') }}}" class="btn btn-sm btn-warning close_popup">
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


            /********
                RMD 2015-03-07
                START OF employee_name ->  typeahead
            *************************************************************************/
                var employee_list = new Bloodhound({
                        datumTokenizer: function (datum) {
                            return Bloodhound.tokenizers.whitespace(datum.employee_name);
                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: 10,
                        remote: {
                            // url points to a json file that contains an array of country names, see
                            // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
                            //url: '../../its/student/dataJson',
                            //url:'http://boc.itechrar.com/its/student/dataJson',
                            url:'../employee/dataJsonTeacher?query=%QUERY',
                            
                            // the json file contains an array of strings, but the Bloodhound
                            // suggestion engine expects JavaScript objects so this converts all of
                            // those strings
                             filter: function (employee_list) {
                                         // alert('this is an alert script from create');
                                    //console.log(employee_list); //debugging
                                    // Map the remote source JSON array to a JavaScript object array
                                    return $.map(employee_list, function (employee) {
                                        // console.log(employee.employee_name); //debugging

                                        return {

                                            employee_name:  employee.last_name + ', '+employee.first_name + ' ' + employee.middle_name,
                                            id: employee.id
                                        };
                                    });
                            }
                        }
                });

                employee_list.initialize();
                 console.log(employee_list);

                //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
               // $('#student_employee_name_typeahead .typeahead').typeahead(null, {
                  $('#employee_name_typeahead').typeahead(null, {
	                  employee_name: 'employee_list',
	                  displayKey: 'employee_name',
	                  source: employee_list.ttAdapter()
	                }).bind("typeahead:selected", function(obj, employee, employee_name) {
	                        console.log(employee);
	                       $("#employee_id").val(employee.id);
	                       $("#employee_name").val(employee.employee_name);
	                    });
   
   $(function() 
    {   

        $("#classification_id").change(function(){

            selectListChange('classification_level_id','{{{ URL::to("classification_level/dataJson") }}}',  {'classification_id': $(this).val() } ,'Please select a Classification');

            var condition = $("#classification_id option:selected").text();

            $("#program_container").empty();

            if(condition == "College")
            {
                $("#program_container").append(''
                    +'<label class="col-md-2 control-label"for="program_id">{{ Lang::get("teacher.program") }}</label>'
                    +'<div class="col-md-4">'
                        +'<select class="form-control" name="program_id" id="program_id" tabindex="4">'
                        +'@if($action == 1)'
                            +'@foreach($program_list as $program)'
                                +'@if($program->id == $teacher->program_id)'
                                +'<option name="program_id" value="{{{ $program->id }}}" selected>{{{ $program->program_name }}}</option>'
                                +'@else'
                                +'<option name="program_id" value="{{{ $program->id }}}">{{{ $program->program_name }}}</option>'
                                +'@endif'
                           +'@endforeach'
                        +'@else'
                            +'<option name="" value="" selected></option>'
                            +'@foreach($program_list as $program)'
                                +'<option name="program_id" value="{{{ $program->id }}}" >{{{ $program->program_name }}}</option>'

                            +'@endforeach'
                        +'@endif'
                        +'</select>'
                    +'</div>'
                    +'');
            }
        });

   $(function() {
        $("#subject").select2()
    });
   
    $(function() {
        $("#degree").select2()
    });

   $(function() {
        $("#classification_id").change(function(){

         selectListChange('subject','{{{URL::to("subject_offered/dataJson")}}}',  { 'is_active':1 ,'is_approved':1 , 'classification_id': $(this).val() } ,'Please select a Subject Offered')

        });
    });

    });
</script>
@stop

