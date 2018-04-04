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
                @include('teacher_sidebar')
        </ul>
      </div>
    </div>

<div id="page-wrapper">
    <div class="row">
        <div class="page-header">
            <br/>
        		<h3 style="margin-top: -12px !important;margin-left: 20px !important;" align="left">
        			{{{ Lang::get("teacher.teacher_basic_info") }}}

        		</h2>
        </div>

      	<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

		<form id="accountForm" method="post" class="form-horizontal">
			<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
			<input name="employee_id" id="employee_id" type="hidden" value="{{{ $person->id }}}" />
			<input name="sea_service_type_counter" id="sea_service_type_counter" type="hidden" value="0" />

			<input name="position_count" id="position_count" type="hidden" value="0" />
		    <div id="info_tab" class="col-xs-11">
		    	<fieldset class="scheduler-border">

		        	<br/><!-- <legend class="scheduler-border">Personal Info</legend> -->
		        	<div class="form-group">
			            <div class="col-xs-6 form-group">
				            <div class="col-xs-12" style="float:right !important;">
				            @if($img)
							  	<img id="myImg" src="{{asset($img -> img)}}" alt="image" width="170px" height="150px" data-toggle="modal" data-target="#photo_modal" />
							@else
							  	<img id="myImg" src="{{asset('assets/site/images/BLANK_IMAGE.jpg')}}" alt="image" width="150px" height="150px" data-toggle="modal" data-target="#photo_modal"/>
							@endif
							</div>
							<input type="hidden" id="image_canvas" name="image_canvas" value="">
						</div>
						<div class="col-xs-6 form-group">
			            	<button id="save_changes_personal_info" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
			            		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
			        		</button>
		        		</div>
		    		</div>
		          	<div class="form-group" style="">
		            	<!-- <input type="file" name="upload_image" onchange="displayImage(this);"></input> -->
		          	</div>
		    		<hr />
		    		<div class="form-group">
		    			<label class="col-xs-2 control-label">Room</label>
			                <div class="col-xs-4">
								<input class="form-control" disabled name="room_id" id="room_id" tabindex="4" value="{{{ $employee->room_name }}}"></input>	
			                </div>
			            <label class="col-xs-2 control-label">Campus</label>
			                <div class="col-xs-4">
								<input class="form-control" disabled name="campus_id" id="campus_id" tabindex="4" value="{{{ $gen_user_role->campus_name }}}"></input>
			                </div>
		            </div>
		            <div class="form-group">
			            <label class="col-xs-2 control-label">First Name</label>
			                <div class="col-xs-4">
			                	<input class="form-control" type="text" name="first_name" id="first_name" value="{{{ Input::old('first_name', isset($employee) ? $person->first_name : null) }}}" />
			                </div>
			            <label class="col-xs-2 control-label">Middle Name</label>
			                <div class="col-xs-4">
			                	<input class="form-control" type="text" name="middle_name" id="middle_name" value="{{{ Input::old('middle_name', isset($employee) ? $person->middle_name : null) }}}" />
			                </div>
		            </div>
		            <div class="form-group">

				      	<input name="person_id" id="person_id" type="hidden" value="{{{$person->id}}}" />	
		                <!-- <label class="col-xs-2 control-label">Name</label> -->
			               <!--  <div class="col-xs-4 col-md-5">
			                	<div class="input-group">
			                    	<input name="first_name" id="first_name" type="hidden" value="{{{$person->first_name}}}" />	
			                    	<input name="middle_name" id="middle_name" type="hidden" value="{{{$person->middle_name}}}" />	
			                    	<input name="last_name" id="last_name" type="hidden" value="{{{$person->last_name}}}" />
									<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('first_name', isset($person) ? $person->last_name. ", " .$person->first_name. " " .$person->middle_name : null) }}}" readonly />
								      <span class="input-group-btn">
								        <a class="btn btn-info" href="{{ URL::to('employee/' . $employee->id . '/edit') }}"><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
								      </span>
							    </div>
			                </div> -->
		                <label class="col-xs-2 control-label">Last Name</label>
			                <div class="col-xs-4">
			                	<input class="form-control" type="text" name="last_name" id="last_name" value="{{{ Input::old('last_name', isset($employee) ? $person->last_name : null) }}}" />
			                </div>
		                <label class="col-xs-2 control-label">Nickname</label>
			                <div class="col-xs-4">
			                	<input class="form-control" type="text" name="nickname" id="nickname" value="{{{ Input::old('nickname', isset($employee) ? $person->nickname : null) }}}" />
			                </div>
		            </div>
		            	            
		            <div class="form-group">
			            <label class="col-xs-2 control-label">Contact No.</label>
			                <div class="col-xs-4">
			                	<input class="form-control" type="text" name="contact_no" id="contact_no" value="{{{ Input::old('contact_no', isset($employee) ? $person->contact_no : null) }}}" />
			                </div>
			            <label class="col-xs-2 control-label">Address</label>
			                <div class="col-xs-4">
			                	<input class="form-control" type="text" name="address" id="address" value="{{{ Input::old('address', isset($employee) ? $person->address : null) }}}" />
			                </div>	                
		            </div>

		            <div class="form-group">
			           <!--  <label class="col-xs-2 control-label">Place of Birth</label>
			                <div class="col-xs-4">
			                    <input type="text" class="form-control" name="place_of_birth" id="place_of_birth" value="{{{ Input::old('place_of_birth', isset($employee) ? $person->place_of_birth : null) }}}" />
			                </div> -->
		                <label class="col-xs-2 control-label">Date of Birth</label>
			                <div class="col-xs-4">
			                    <input class="form-control datepicker" type="text" name="birthdate" id="birthdate" value="{{{ Input::old('birthdate', isset($employee) ? date("F d,Y", strtotime($person->birthdate)) : null) }}}" />
			                </div>
			            <label class="col-xs-2 control-label">Civil Status</label>
			                <div class="col-xs-4">
			                    <select class="form-control" name="civil_status_id" id="civil_status_id" tabindex="4">			
								
									<option name="" value="" selected></option>
									@foreach($civil_status_list as $civil_status)
										@if($civil_status->id == $person->civil_status_id)
										<option name="civil_status_id" value="{{{ $civil_status->id }}}" selected>{{{ $civil_status->civil_status_name }}}</option>
										@else
										<option name="civil_status_id" value="{{{ $civil_status->id }}}">{{{ $civil_status->civil_status_name }}}</option>
										@endif
									@endforeach							
								</select>
			                </div>
		            </div>
		            <div class="form-group">
		            	<label class="col-xs-2 control-label">Date Hired</label>
			                <div class="col-xs-4">
			                	<input class="form-control" disabled type="date" name="date_hired" id="date_hired" value="{{{ Input::old('date_hired', isset($employee) ? $employee->date_hired : null) }}}" />
			                </div>
			            <label class="col-xs-2 control-label">End of Service</label>
			                <div class="col-xs-4">
			                	<input disabled class="form-control" type="date" name="end_of_contract" id="end_of_contract" value="{{{ Input::old('end_of_contract', isset($employee) ? $employee->end_of_contract : null) }}}" />
			                </div>
			        </div>
			        <div class="form-group">
			        	<div class="col-md-1">
			        	</div>
			        	<div class="form-group col-md-11">
					      <label for="comment">End of Service Remarks</label>
					      <textarea class="form-control" rows="5" id="service_remark" name="service_remark" disabled>{{{ $employee -> service_remark }}}</textarea>
					    </div>
			        </div>
					<div class="form-group">
			            <label class="col-xs-2 control-label">Length of Service</label>
			                <div class="col-xs-4">
			                	<input class="form-control" type="text" readonly name="length_of_service" id="length_of_service" value="{{{ Input::old('length_of_service', isset($employee) ? $person->length_of_service : null) }}}" />
			                </div>
			            <label class="col-xs-2 control-label">Passport Number</label>
			                <div class="col-xs-4">
			                    <input class="form-control" type="text" name="passport_number" id="passport_number" value="{{{ Input::old('passport_number', isset($employee) ? $employee->passport_number : null) }}}" />
			                </div>

		        	</div>

		        	<div class="form-group">

		            <!-- 	<label class="col-xs-2 control-label">I-Card Number</label>
			                <div class="col-xs-4">
			                    <input class="form-control" type="text" name="i_card" id="i_card" value="{{{ Input::old('i_card', isset($employee) ? $employee->i_card : null) }}}" />
			                </div> -->
		              	

		        	</div>
		        	<div>
		            	<fieldset>
				            <div class="form-group">
				                <label class="col-xs-2 control-label">Employee Type</label>
					                <div class="col-xs-4">		
					                    <input class="form-control" disabled name="employee_type_id" id="employee_type_id" tabindex="4" value="{{{ $employee->employee_type_name }}}"></input>		
					                </div>
				               	<label class="col-xs-2 control-label">Position</label>
					                <div class="col-xs-4">
						                <input class="form-control" disabled name="position_id" id="position_id" tabindex="4" value="{{{ $employee->position_name }}}"></input>	
					                </div>
				                
				            </div>
				            <div class="form-group">
				            	<label class="col-xs-2 control-label">Employment Status</label>
					                <div class="col-xs-4">
					                	<input class="form-control" disabled name="employment_status_id" id="employment_status_id" tabindex="4" value="{{{ $employee->employment_status_name }}}"></input>
					                </div>
					            <label class="col-xs-2 control-label">Rate / Salary</label>
					                <div class="col-xs-4">
					                	<input disabled class="form-control" type="text" name="rate" id="rate" value="{{{ Input::old('rate', isset($employee) ? $employee->rate : null) }}}" />
					                </div>	 
				            </div>
				            <div class="form-group">
					            <label class="col-xs-2 control-label">Contract Period</label>
								    <div class="input-daterange input-group col-xs-4" id="datepicker" style="padding-left: 15px !important;padding-right: 15px !important;">
								        <input disabled type="text" class="contract_date_start form-control" name="contract_date_start" id="contract_date_start" value="{{{ Input::old('date_hired', isset($employee) ? $employee->contract_from : null) }}}" />
								        <span class="input-group-addon">to</span>
								        <input disabled type="text" class="contract_date_end form-control" name="contract_date_end" id="contract_date_end" value="{{{ Input::old('date_hired', isset($employee) ? $employee->contract_to : null) }}}" />
								    </div>   
					                        
				            </div>
			            </fieldset>
			        </div>
			        <div id="field_position">
			        </div>          
		        </fieldset> <!-- Personal Info End -->
		        
		</form>
	</div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')

<script type="text/javascript">
	$(function(){

		function dateDiffInYears(dateold, datenew) {

				datenew = new Date(datenew);
				dateold = new Date(dateold);
	            var ynew = datenew.getFullYear();
	            var mnew = datenew.getMonth();
	            var dnew = datenew.getDate();
	            var yold = dateold.getFullYear();
	            var mold = dateold.getMonth();
	            var dold = dateold.getDate();
	            var diff = ynew - yold;
	            if (mold > mnew) diff--;
	            else {
	                if (mold == mnew) {
	                    if (dold > dnew) diff--;
	                }
	            }
	            return diff;
	    }

		var date_hired = $("#date_hired").val();
		var end_of_contract = $("#end_of_contract").val();

		if(end_of_contract == "")
		{
			var end_of_contract = new Date();
			var day = end_of_contract.getDate();
			var month = end_of_contract.getMonth() + 1;
			var year = end_of_contract.getFullYear();

			end_of_contract = year+'-'+month+'-'+day;
		}
		if(date_hired != "")
		{
			length_of_service = dateDiffInYears(date_hired,end_of_contract);
			$("#length_of_service").val(length_of_service+' Year/s');
		}
	

		//save single input data
	$("#save_changes_personal_info").click(function(){

		var position_array_con = [];
		
		var count = 0;

		$("#field_position > fieldset").each(function(){
			var employee_type_id = $(this).find('select[name="employee_type_id"]').val();
			if(employee_type_id != "" && employee_type_id != undefined){

				var position_array = [];
				var employee_position_id = $(this).find('input[name="employee_position_id"]').val();
				var position_id = $(this).find('select[name="position_id"]').val();
				var employment_status_id = $(this).find('select[name="employment_status_id"]').val();
				var rate = $(this).find('input[name="rate"]').val();

				position_array[0] = position_id;
				position_array[1] = employee_type_id;
				position_array[2] = employment_status_id;
				position_array[3] = rate;
				position_array[4] = employee_position_id;

				position_array_con[count] = position_array;
				count++;
			}
		});	

		var skill_id = [];
		var skill_data = [];
		var skill_count = 0;
		$(".skill_action").each(function(){
				skill_id[skill_count] = $(this).data('skill_id');
				if($(this).prop("checked") == true)
				{
					skill_data[skill_count] = "1";
				}
				else
				{
					skill_data[skill_count] = "0";
			    }

				skill_count++;
		});

		if(count != 0)
		{
			$.ajax(
				{
					url:'{{{ URL::to("employee/savePosition") }}}',
					type:'post',
					data:
						{
							'position_array': position_array_con,
							'employee_id': $("#employee_id").val(),
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}
			).done(function(data){
			});
		}
		$.ajax(
				{
					url:'{{{ URL::to("teacher_portal/createJson") }}}',
					type:'post',
					data:
						{
							'skill_id': skill_id,
							'skill_data': skill_data,
							'employee_id': $('#employee_id').val(),
							'person_id': $('#person_id').val(),
							'first_name': $('#first_name').val(),
							'middle_name': $('#middle_name').val(),
							'last_name': $('#last_name').val(),
							'nickname': $('#nickname').val(),
							'gen_role_id': $('#gen_role_id').val(),
							'employee_type_id': $('#employee_type_id').val(),
							'position_id': $('#position_id').val(),
							'room_id': $('#room_id').val(),
							'program_id': $('#course_handle').val(),
							'employment_status_id': $('#employment_status_id').val(),
							'address': $('#address').val(),
							'date_hired': $('#date_hired').val(),
							'end_of_contract': $('#end_of_contract').val(),
							'contract_date_start': $('#contract_date_start').val(),
							'contract_date_end': $('#contract_date_end').val(),
							'service_remark': $('#service_remark').val(),
							'rate': $('#rate').val(),
							'civil_status_id': $('#civil_status_id').val(),
							'campus_id': $('#campus_id').val(),
							'image_canvas': $("#image_canvas").val(),
							'birthdate': $('#birthdate').val(),
							'place_of_birth': $('#place_of_birth').val(),
							'passport_number': $('#passport_number').val(),
							'i_card': $('#i_card').val(),
							'is_active': $('#is_active').val(),
							'contact_no': $('#contact_no').val(),
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

		).done(function(data){
			swal("Saved Changes!");
			// swal("Saved!", "Successfully Saved Changes on Employee Detail!", "Success" );
	 		location.reload();
			// swal(date_employed);
		});

	});

	});


</script>
@stop