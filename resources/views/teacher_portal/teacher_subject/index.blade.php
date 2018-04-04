@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("teacher.teacher") }}} :: @parent
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
                @include('scheduler_sidebar')
        </ul>
      </div>
    </div>

<div id="page-wrapper">
    <div class="row">
 
    	<div class="page-header"><br>
    		<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
    			{{{ Lang::get("teacher.teacher_subjects") }}}
    		
    		</h3>
      	</div>
  		  <div class="col-md-12">
      			<div class="form-group {{{ $errors->has('employee_id') ? 'has-error' : '' }}}">
        				<label class="col-md-2 control-label" for="employee_name">Search {!! Lang::get('employee.employee') !!}</label>
        	            <input type="hidden" name="employee_id" id="employee_id" value="0" />
        	            <input type="hidden" name="teacher_id" id="teacher_id" value="0" />
        		        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        				<div class="col-md-4">
        					<input class="typeahead form-control" type="text" name="employee_name" id="employee_name" value="" />
        					{!! $errors->first('employee_id', '<label class="control-label" for="employee_name">:message</label>')!!}
        				</div>
                <label class="col-md-1 control-label" for="">Room</label>
                <div class="col-md-4">
                    <select class="col-md-12" id="room_id">
                        <option value="0"></option>
                          @foreach($room_list as $room)
                            <option class="room_id_data" value="{{$room -> id}}">{{$room -> room_name}}</option>
                          @endforeach
                    </select>
                </div>
      			</div>
        </div>
        <br>
        <br>
        <br>
        <div class="col-md-12 form-group">
	        <div class="col-md-2">
	        	<label class="col-md-12 control-label" for="employee_name">Subject List</label>
	        </div>
	        <div class="col-md-4">
	        	<select class="col-md-12" id="subject_id">
	    			<option value="0"></option>
	        		@foreach($course_list as $course)
	        			<option class="subject_id_data" value="{{$course -> id}}">{{$course -> course_name}}</option>
	        		@endforeach
	        	</select>
	        </div>  
	        <div class="col-md-1 form-group">
	        	<button id="add_subject" type="button" class="btn btn-sm btn-primary">Add Subject</button>
	        </div>  
	        <div class="col-md-12 form-group"><hr></div>
	        <div class="col-md-12">
	        	<h4>Subject/s Handled</h4>
	        	<div class="col-md-12" id="subject_container">
	        	</div>
          </div>
          <div class="col-md-12">
            <hr>
          </div>
          <div class="col-md-12">
            <div class="col-md-2 pull-right">
              <button id="save_subject" type="button" class="btn btn-sm btn-success">Update Teacher Data</button>
            </div>
	        </div>
        </div>  
    </div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')

<script type="text/javascript">
  $(function() {

      $("#subject_id").select2(
      {
        placeholder: "Select Subject",
        allowClear: true
      });
      $("#room_id").select2(
      {
        placeholder: "Select Room",
        allowClear: true
      });

  		$("#add_subject").click(function(){
  			var subject_id = $("#subject_id").val();
  			var subject_name = $("#subject_id option:selected").text();
  			if(subject_id != 0)
  			{
  	  			$("#subject_container").append('<div class="col-md-4 form-group" id="subject_'+subject_id+'">'
    	  				+'<label class="control-label subject_label" data-subject_id="'+subject_id+'"><span class="fa fa-circle-o" style="color:#428BCA"></span> '+subject_name+'</label>'
    	  				+'&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger remove_subject" data-subject_id="'+subject_id+'"><span class="fa fa-close"></span></button>'
    				+'</div>');

    				$("#subject_id option:selected").attr('disabled','disabled');
    				$("#subject_id [value='"+0+"']").attr("selected","selected");

            $(".remove_subject").click(function(){
                var id = $(this).data('subject_id');
                $("#subject_"+id).remove();
                $("#subject_id [value='"+id+"']").removeAttr('disabled');
            });

  			}
  		});

  		$("#save_subject").click(function(){
			counter_subject = 0;
            subject = [];

  			$(".subject_label").each(function(){
                id = $(this).data('subject_id');
                subject[counter_subject] = id;
                counter_subject++;
  			});

  			$.ajax({
                url:'{{{ URL::to("teacher_subject/dataJson") }}}',
                type:'POST',
                data: {
                    'subject' : subject,
                    'room_id' : $("#room_id").select2("val"),
                    'teacher_id' : $("#teacher_id").val(),
                    '_token' : $("input[name=_token]").val(),
                },
                dataType: "json",
                async:false,
                success: function (data) 
                {            
                    swal("Your data saved successfully!");  
                    location.reload();    
                }  
            });
  		});

            var substringMatcher = function(strs) {
                      return function findMatches(q, cb) {
                        var matches, substringRegex;
                     
                        // an array that will be populated with substring matches
                        matches = [];
                     
                        // regex used to determine if a string contains the substring `q`
                        substrRegex = new RegExp(q, 'i');
                     
                        // iterate through the pool of strings and for any string that
                        // contains the substring `q`, add it to the `matches` array
                        $.each(strs, function(i, str) {
                          if (substrRegex.test(str)) {
                            matches.push(str);
                          }
                        });
                     
                        cb(matches);
                      };
                    };


            /********
                START OF employee_name ->  typeahead
            *************************************************************************/
                var employee_list = new Bloodhound({
                        datumTokenizer: function (datum) {
                            //return Bloodhound.tokenizers.whitespace(datum.employee_name);
                               var tokens = [];
                                //the available string is 'name' in your datum
                                var stringSize = datum.employee_name.length;
                                //multiple combinations for every available size
                                //(eg. dog = d, o, g, do, og, dog)
                                for (var size = 1; size <= stringSize; size++){          
                                  for (var i = 0; i+size<= stringSize; i++){
                                      tokens.push(datum.employee_name.substr(i, size));
                                  }
                                }

                                return tokens;    


                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: 10,
                       
                        remote:{
                        	url:'employee/dataJson?query=%QUERY',
                        	filter: function (employee_list) {
                                      // alert('this is an alert script from create');
                                      // console.log(employee_list); //debugging
                                      // Map the remote source JSON array to a JavaScript object array
                                    return $.map(employee_list, function (employee) {
                                        console.log(employee); //debugging
                                        return {
                                            employee_name: employee.first_name +' '+ employee.middle_name +' '+ employee.last_name+' - '+employee.nickname,
                                            id: employee.employee_id,
                                            teacher_id: employee.teacher_id,
                                            room_id: employee.room_id,
                                        };
                                    });
                             }
                        }

                });

                employee_list.initialize();
                 console.log(employee_list);

                //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
               // $('#employee_employee_no_typeahead .typeahead').typeahead(null, {
                  $('#employee_name').typeahead(
                    {
                      hint: true,
                      highlight: true,
                      minLength: 1
                    }, 
                    {
                        employee_name: 'employee_list',
                         displayKey: 'employee_name',
                        source: employee_list.ttAdapter()
                        

                    }

                    ).bind("typeahead:selected", function(obj, employee, employee_no) {

                    	$("#subject_container").empty();
                    	$(".subject_id_data").each(function(){
                    		$(this).removeAttr('disabled');
                    	});

                      $("#room_id").select2().select2('val',employee.room_id);
                        //console.log(employee);
                      $("#teacher_id").val(employee.teacher_id);
                      $.ajax({
                          url:'{{{ URL::to("teacher_subject/getDataJson") }}}',
                          type:'GET',
                          data: {'teacher_id':$("#teacher_id").val()},
                          dataType: "json",
                          async:false,
                          success: function (data) 
                          {   
                              $.map(data,function(item){
                                  $("#subject_container").append('<div class="col-md-4 form-group" id="subject_'+item.course_id+'">'
                                      +'<label class="control-label" data-subject_id="'+item.course_id+'"><span class="fa fa-circle-o" style="color:#428BCA"></span> '+item.course_name+'</label>'
                                      +'&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger remove_subject" data-subject_id="'+item.course_id+'" data-id="'+item.id+'"><span class="fa fa-close"></span></button>'
                                  +'</div>');

                                  $("#subject_id [value='"+item.course_id+"']").attr('disabled','disabled');
                                  $("#subject_id [value='"+0+"']").attr("selected","selected");

                              });

                              $(".remove_subject").click(function(){

                                    var id = $(this).data('id');

                                    swal({
                                      title: "Are you sure?",
                                      text: "You will not be able to recover this data!",
                                      type: "warning",
                                      showCancelButton: true,
                                      confirmButtonColor: "#DD6B55",
                                      confirmButtonText: "Yes, delete it!",
                                      closeOnConfirm: false
                                    },
                                    function(){
                                        $.ajax({
                                            url:'{{{ URL::to("employee/deleteRow") }}}',
                                            type:'POST',
                                            data: {
                                                'id' : id,
                                                'table' : 'teacher_subject',
                                                '_token' : $("input[name=_token]").val(),
                                            },
                                            dataType: "json",
                                            async:false,
                                            success: function (data) 
                                            {            
                                                swal("Deleted!", "Your data has been deleted.", "success");  
                                                location.reload();    
                                                // $("#"+row+""+id).remove();
                                            }  
                                        });
                                    });
                                });
                          }
                      });

                      // $("#employee_id").val(employee.id);
                      // $("#view_full_detail").removeAttr('href');
                      // $("#view_full_detail").attr('href','{{{ URL::to("employee/full_detail") }}}'+'?employee_id='+employee.id);
                      // $("#view_full_detail").attr('target','_blank');
                      //$("#employee_name").val(employee.employee_name);

                     // alert(employee.employee_name);
                     
                    });

                   /********
                END OF employee_name ->  typeahead
            *************************************************************************/


                   
    });
</script>
@stop