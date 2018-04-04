<div class="col-md-12"><hr/></div>
<ul class="nav nav-tabs">
    <li class="active"><a href="#info_tab" data-toggle="tab">General Information <i class="fa"></i></a></li>
    <li><a href="#working_experience_tab" data-toggle="tab">Working Experience <i class="fa"></i></a></li>
    <li><a href="#contacts_tab" data-toggle="tab">Contacts <i class="fa"></i></a></li>
    <li><a href="#education_tab" data-toggle="tab">Educational Background <i class="fa"></i></a></li>
    <!-- <li><a href="#requirement_tab" data-toggle="tab">Requirements <i class="fa"></i></a></li> -->
    <li><a href="#seminars_attended_tab" data-toggle="tab">Seminars Attended <i class="fa"></i></a></li>
    <li><a href="#certificates_tab" data-toggle="tab">Certificates<i class="fa"></i></a></li>
    <li><a href="#government_contribution_tab" data-toggle="tab">Gov. Contribution No.<i class="fa"></i></a></li>
</ul>


<form id="accountForm" method="post" class="form-horizontal">
	<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
	<input name="employee_id" id="employee_id" type="hidden" value="{{{ $person->id }}}" />
	<input name="sea_service_type_counter" id="sea_service_type_counter" type="hidden" value="0" />
    <div class="tab-content">
    	<input name="position_count" id="position_count" type="hidden" value="0" />
        <div class="tab-pane active" id="info_tab">
        	<fieldset class="scheduler-border">

	        	<br/><!-- <legend class="scheduler-border">Personal Info</legend> -->

	        	<div class="form-group">
		            <div class="col-md-6 form-group">
			            <div class="col-md-12" style="float:right !important;">
			            @if($img)
						  	<img id="myImg" src="{{asset($img -> img)}}" alt="image" width="170px" height="150px" data-toggle="modal" data-target="#photo_modal" />
						@else
						  	<img id="myImg" src="{{asset('assets/site/images/BLANK_IMAGE.jpg')}}" alt="image" width="150px" height="150px" data-toggle="modal" data-target="#photo_modal"/>
						@endif
						</div>
						<input type="hidden" id="image_canvas" name="image_canvas" value="">
					</div>
					<div class="col-md-6 form-group">
		            	<button id="save_changes_personal_info" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
		            		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
		        		</button>
	        		</div>
        		</div>
	          	<div class="form-group" style="">
	            	<input type="file" name="upload_image" onchange="displayImage(this);"></input>
	          	</div>
        		<hr />

            	<h3>Basic Information</h3> 
        		<div class="form-group" id="room_div">
        			<label class="col-xs-1 control-label">Room</label>
		                <div class="col-xs-5">
		                    <select class="form-control" name="room_id" id="room_id" tabindex="4">			
							
								<option name="" value="" selected></option>
								@foreach($room_list as $room)
									@if($employee -> room_id == $room -> id)
										<option name="room_id" value="{{{ $room->id }}}" selected>{{{ $room->room_name }}}</option>
									@else
										<option name="room_id" value="{{{ $room->id }}}">{{{ $room->room_name }}}</option>
									@endif
								@endforeach							
							</select>
		                </div>
		                <!-- <div class="col-xs-1">
			                <button class="btn btn-sm btn-primary glyphicon glyphicon-plus" id="add_room" type="button"></button>
		                </div> -->
		            <!-- <label class="col-xs-1 control-label">Course</label>
		                <div class="col-xs-5">
		                	<select class="form-control" name="course_handle" id="course_handle" tabindex="4">			
							
								<option name="" value="" selected></option>
								@foreach($program_list as $program)
									@if($employee -> program_id == $program -> id)
										<option name="program_id" value="{{{ $program->id }}}" selected>{{{ $program->program_name }}}</option>
									@else
										<option name="program_id" value="{{{ $program->id }}}">{{{ $program->program_name }}}</option>
									@endif
								@endforeach							
							</select>
		                </div> -->
        		</div>

	            <div class="form-group">
		            <label class="col-xs-1 control-label">Role</label>
		                <div class="col-xs-5">
		                    <select class="form-control" name="gen_role_id" id="gen_role_id" tabindex="4">			
							
								<option name="" value="" selected></option>
								@foreach($gen_role_list as $gen_role)
									@if($gen_role->id == $gen_user_role->gen_role_id)
									<option name="gen_role_id" value="{{{ $gen_role->id }}}" selected>{{{ $gen_role->name }}}</option>
									@else
									<option name="gen_role_id" value="{{{ $gen_role->id }}}">{{{ $gen_role->name }}}</option>
									@endif
								@endforeach							
							</select>
		                </div>

		            <label class="col-xs-1 control-label">Campus</label>
		                <div class="col-xs-5">
		                    <select class="form-control" name="campus_id" id="campus_id" tabindex="4">			
							
								<option name="" value="" selected></option>
								@foreach($campus_list as $campus)
									@if($campus->id == $gen_user_role->campus_id)
									<option name="campus_id" value="{{{ $campus->id }}}" selected>{{{ $campus->campus_name }}}</option>
									@else
									<option name="campus_id" value="{{{ $campus->id }}}">{{{ $campus->campus_name }}}</option>
									@endif
								@endforeach							
							</select>
		                </div>
	            </div>
	            <div class="form-group">
		            <label class="col-xs-1 control-label">First Name</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="text" name="first_name" id="first_name" value="{{{ Input::old('first_name', isset($employee) ? $person->first_name : null) }}}" />
		                </div>
		            <label class="col-xs-1 control-label">Middle Name</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="text" name="middle_name" id="middle_name" value="{{{ Input::old('middle_name', isset($employee) ? $person->middle_name : null) }}}" />
		                </div>
	            </div>
	            <div class="form-group">

			      	<input name="person_id" id="person_id" type="hidden" value="{{{$person->id}}}" />	
	                <!-- <label class="col-xs-1 control-label">Name</label> -->
		               <!--  <div class="col-xs-5 col-md-5">
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
	                <label class="col-xs-1 control-label">Last Name</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="text" name="last_name" id="last_name" value="{{{ Input::old('last_name', isset($employee) ? $person->last_name : null) }}}" />
		                </div>
	                <label class="col-xs-1 control-label">Nickname</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="text" name="nickname" id="nickname" value="{{{ Input::old('nickname', isset($employee) ? $person->nickname : null) }}}" />
		                </div>
	            </div>
	            	            
	            <div class="form-group">
		            <label class="col-xs-1 control-label">Contact No.</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="text" name="contact_no" id="contact_no" value="{{{ Input::old('contact_no', isset($employee) ? $person->contact_no : null) }}}" />
		                </div>
		            <label class="col-xs-1 control-label">Address</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="text" name="address" id="address" value="{{{ Input::old('address', isset($employee) ? $person->address : null) }}}" />
		                </div>	                
	            </div>

	            <div class="form-group">
		           <!--  <label class="col-xs-1 control-label">Place of Birth</label>
		                <div class="col-xs-5">
		                    <input type="text" class="form-control" name="place_of_birth" id="place_of_birth" value="{{{ Input::old('place_of_birth', isset($employee) ? $person->place_of_birth : null) }}}" />
		                </div> -->
	                <label class="col-xs-1 control-label">Date of Birth</label>
		                <div class="col-xs-5">
		                    <input class="form-control datepicker" type="text" name="birthdate" id="birthdate" value="{{{ Input::old('birthdate', isset($employee) ? date("F d,Y", strtotime($person->birthdate)) : null) }}}" />
		                </div>
		            <label class="col-xs-1 control-label">Civil Status</label>
		                <div class="col-xs-5">
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
	            	<label class="col-xs-1 control-label">Gender</label>
		                <div class="col-xs-5">
		                    <select class="form-control" name="gender_id" id="gender_id" tabindex="4">			
							
								<option name="" value="" selected></option>
								@foreach($gender_list as $gender)
									@if($gender->id == $person->gender_id)
									<option name="gender_id" value="{{{ $gender->id }}}" selected>{{{ $gender->gender_name }}}</option>
									@else
									<option name="gender_id" value="{{{ $gender->id }}}">{{{ $gender->gender_name }}}</option>
									@endif
								@endforeach							
							</select>
		                </div>
		        </div>
	            <div class="form-group">
	            	<label class="col-xs-1 control-label">Date Hired</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="date" name="date_hired" id="date_hired" value="{{{ Input::old('date_hired', isset($employee) ? $employee->date_hired : null) }}}" />
		                </div>
		            <label class="col-xs-1 control-label">End of Service</label>
		                <div class="col-xs-5">
		                	<input class="form-control" type="date" name="end_of_contract" id="end_of_contract" value="{{{ Input::old('end_of_contract', isset($employee) ? $employee->end_of_contract : null) }}}" />
		                </div>
		        </div>
		        <div class="form-group">
		        	<div class="col-md-1">
		        	</div>
		        	<div class="form-group col-md-11">
				      <label for="comment">End of Service Remarks</label>
				      <textarea class="form-control" rows="5" id="service_remark" name="service_remark">{{{ $employee -> service_remark }}}</textarea>
				    </div>
		        </div>
				<div class="form-group">
		            <label class="col-xs-1 control-label">Length of Service</label>
		                <div class="col-xs-2">
		                	<input class="form-control" type="text" readonly name="length_of_service" id="length_of_service" value="{{{ Input::old('length_of_service', isset($employee) ? $person->length_of_service : null) }}}" />
		                </div>
		            <div id="passport_number_div" class="international_staff">
			            <label class="col-xs-1 control-label">Passport Number</label>
			                <div class="col-xs-2">
			                    <input class="form-control" type="text" name="passport_number" id="passport_number" value="{{{ Input::old('passport_number', isset($employee) ? $employee->passport_number : null) }}}" />
			                </div>

		            <label class="col-xs-1 control-label">Passport Date</label>
					    <div class="input-daterange input-group col-xs-5 datepicker1" id="datepicker" style="padding-left: 15px !important;padding-right: 15px !important;">
					        <input type="text" class="pass_issue_date form-control" name="pass_issue_date" id="pass_issue_date" value="{{{ Input::old('date_hired', isset($employee) ? $employee->pass_issue_date : null) }}}" />
					        <span class="input-group-addon">to</span>
					        <input type="text" class="pass_expiry_date form-control" name="pass_expiry_date" id="pass_expiry_date" value="{{{ Input::old('date_hired', isset($employee) ? $employee->pass_expiry_date : null) }}}" />
					    </div>  

	                </div> 

            	</div>

            	<div class="form-group" id="i_card_div">
	            	<label class="col-xs-1 control-label">I-Card Number</label>
		                <div class="col-xs-5">
		                    <input class="form-control" type="text" name="i_card" id="i_card" value="{{{ Input::old('i_card', isset($employee) ? $employee->i_card : null) }}}" />
		                </div>

		            <label class="col-xs-1 control-label">I-Card Date</label>
					    <div class="input-daterange input-group col-xs-5 datepicker1" id="datepicker" style="padding-left: 15px !important;padding-right: 15px !important;">
					        <input type="text" class="i_card_issue_date form-control" name="i_card_issue_date" id="i_card_issue_date" value="{{{ Input::old('date_hired', isset($employee) ? $employee->i_card_issue_date : null) }}}" />
					        <span class="input-group-addon">to</span>
					        <input type="text" class="i_card_expiry_date form-control" name="i_card_expiry_date" id="i_card_expiry_date" value="{{{ Input::old('date_hired', isset($employee) ? $employee->i_card_expiry_date : null) }}}" />
					    </div> 
            	</div>
            	<div class="form-group" id="visa_div">
	            	<!-- <label class="col-xs-1 control-label">Visa</label>
		                <div class="col-xs-5">
		                    <input class="form-control" type="text" name="visa" id="visa" value="{{{ Input::old('visa', isset($employee) ? $employee->visa : null) }}}" />
		                </div> -->
		            <label class="col-xs-1 control-label">Visa Status</label>
		                <div class="col-xs-2">
		                	<select class="form-control" id="visa_status" name="visa_status">
		                		@foreach($visa_status_list as $visa_status)
		                			@if($employee -> visa_status_id == $visa_status -> id)
			                			<option value="{{$visa_status -> id}}" selected>{{$visa_status -> visa_status_name}}</option>
			                		@else
			                			<option value="{{$visa_status -> id}}">{{$visa_status -> visa_status_name}}</option>
			                		@endif
		                		@endforeach
		                	</select>
		                </div>
		            <label class="col-xs-1 control-label">Visa Date</label>
		                <div class="col-xs-2">
		                    <input class="form-control datepicker" type="text" name="visa_date" id="visa_date" value="{{{ Input::old('visa_date', isset($employee) ? date("F d,Y", strtotime($employee->visa_date)) : null) }}}" />
		                </div>
		            <label class="col-xs-1 control-label">End Date</label>
		                <div class="col-xs-2">
		                    <input class="form-control datepicker" type="text" name="visa_end_date" id="visa_end_date" value="{{{ Input::old('visa_end_date', isset($employee) ? date("F d,Y", strtotime($employee->visa_end_date)) : null) }}}" />
		                </div>

            	</div>
            	<div class="col-md-12">  
            		<hr>  
            	</div>      
            	<h3>Position List</h3>      
            	<div class="form-group">                
		            <label class="col-xs-1 control-label" for="is_active">{!! Lang::get('employee.is_active') !!}</label>
						<div class="col-xs-5" style="padding-top:7px;">
							<div class="checkbox">
									<input type="hidden" name="is_active_val" id="is_active_val" class="reset-data" value="{{$person->is_active}}" />
									<input type="checkbox" data-toggle="toggle" name="is_active" id="is_active" class="reset-data"/>
							</div>
							{!! $errors->first('is_active', '<label class="control-label" for="is_active">:message</label>')!!}

						</div>
					<div class="col-xs-6" style="padding-top:7px;">
					<button type="button" id="add_position" class="btn btn-sm btn-primary pull-right">Add Position</button>
					</div>
		        </div>
            	<div>
	            	<fieldset>
			            <div class="form-group">
			                <label class="col-xs-1 control-label">Employee Type</label>
				                <div class="col-xs-5">
				                    <select class="form-control" name="employee_type_id" id="employee_type_id" tabindex="4">			
									
										<option name="" value="" selected></option>
										@foreach($employee_type_list as $employee_type)
											@if($employee_type->id == $employee->employee_type_id)
											<option name="employee_type_id" value="{{{ $employee_type->id }}}" selected>{{{ $employee_type->employee_type_name }}}</option>
											@else
											<option name="employee_type_id" value="{{{ $employee_type->id }}}">{{{ $employee_type->employee_type_name }}}</option>
											@endif
										@endforeach							
									</select>
				                </div>
			               	<label class="col-xs-1 control-label">Position</label>
				                <div class="col-xs-5">
				                    <select class="form-control" name="position_id" id="position_id" tabindex="4">

										<option name="" value="" selected></option>
										@foreach($position_list as $position)
											@if($position->id == $employee->position_id)
											<option name="position_id" value="{{{ $position->id }}}" selected>{{{ $position->position_name }}}</option>
											@else
											<option name="position_id" value="{{{ $position->id }}}">{{{ $position->position_name }}}</option>
											@endif
										@endforeach
									</select>
				                </div>
			                
			            </div>
			            <div class="form-group">
			            	<label class="col-xs-1 control-label">Employment Status</label>
				                <div class="col-xs-5">
				                    <select class="form-control" name="employment_status_id" id="employment_status_id" tabindex="4">

										<option name="" value="" selected></option>
										@foreach($employment_status_list as $employment_status)
											@if($employment_status->id == $employee->employment_status_id)
											<option name="employment_status_id" value="{{{ $employment_status->id }}}" selected>{{{ $employment_status->employment_status_name }}}</option>
											@else
											<option name="employment_status_id" value="{{{ $employment_status->id }}}">{{{ $employment_status->employment_status_name }}}</option>
											@endif
										@endforeach
									</select>
				                </div>
				            <label class="col-xs-1 control-label">Rate / Salary</label>
				                <div class="col-xs-5">
				                	<input class="form-control" type="text" name="rate" id="rate" value="{{{ Input::old('rate', isset($employee) ? $employee->rate : null) }}}" />
				                </div>	 
			            </div>
			            <div class="form-group">
				            <label class="col-xs-1 control-label">Contract Period</label>
							    <div class="input-daterange input-group col-xs-5 datepicker1" id="datepicker" style="padding-left: 15px !important;padding-right: 15px !important;">
							        <input type="text" class="contract_date_start form-control" name="contract_date_start" id="contract_date_start" value="{{{ Input::old('date_hired', isset($employee) ? $employee->contract_from : null) }}}" />
							        <span class="input-group-addon contract_date">to</span>
							        <input type="text" class="contract_date_end form-control contract_date" name="contract_date_end" id="contract_date_end" value="{{{ Input::old('date_hired', isset($employee) ? $employee->contract_to : null) }}}" />
							    </div>   
				                        
			            </div>
		            </fieldset>
		        </div>
		        <div id="field_position">
		        </div>
            	<div class="col-md-12">  
            		<hr>  
            	</div>
		        <div class="form-group" id="teacher_teaching_skill">
		        	<h3>Teacher Teaching Skill/s</h3>
		        	<div class="form-group col-md-12">
			        	<div class="form-group col-md-4">
			        		<select class="form-control" id="program_category_id">
			        				<option value=""></option>
			        			@foreach($program_category_list as $program_category)
			        				<option value="{{$program_category -> id}}">{{ $program_category -> program_category_name }}</option>
			        			@endforeach
			        		</select>
			        	</div>
			        	<div class="form-group col-md-1">
			        	</div>
			        	<div class="form-group col-md-3">
			        		<select class="form-control" id="program_skill_id">
			        		</select>
			        	</div>
			        	<div class="form-group col-md-3"></div>
			        	<div class="form-group col-md-1">
			        		<button class="btn btn-sm btn-primary pull-right" id="add_skill" type="button">Add Skill</button>
			        	</div>
		        	</div>
		        	<div class="form-group col-xs-12" id="skill_con_db">
		        		@foreach($teacher_skill_list as $teacher_skill)
		        			@if($teacher_skill -> is_active == 1)
			        		<div class="checkbox">
							  <label class="skill" data-id="{{ $teacher_skill -> id }}">
							    <input class="skill_action" data-db="1" data-default="{{ $teacher_skill -> is_default }}" data-skill_id="{{ $teacher_skill -> id }}" id="toggle_trigger_{{ $teacher_skill -> id }}" type="checkbox" data-toggle="toggle" data-on="Default" data-off="Make Default" data-onstyle="success">
							  	{{$teacher_skill -> program_category_name.'('.$teacher_skill -> skill_name.')'}}
							  </label>
							</div>
							@endif
						@endforeach
		        	</div>
		        	<div class="form-group col-xs-12" id="skill_con">

		        	</div>
		        </div>	            
	        </fieldset> <!-- Personal Info End -->
	        
        </div> <!-- info tab end -->


		
	    <div class="tab-pane" id="education_tab">
            <fieldset class="scheduler-border">
            	<br/><!-- <legend class="scheduler-border">Educational Background</legend> -->
	            <table class="table table-bordered table-striped" id="education_table">
	            	<button id="save_changes_education" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            		</button>
	            	<th>Highest Educational Attainment</th>
	            	<th>School Name</th>
	            	<th>Years Attended</th>	            	
	            	<th>Honors Received</th>
	            	<th>Course Graduated</th>

	            	<tbody>
		            	@foreach($employee_classification_list as $employee_classification)

		            		<?php $person_education_created = false; ?>

		            		@foreach($person_education_list as $person_education)
		            			@if($person_education->employee_classification_id == $employee_classification->id)
								<?php $person_education_created = true; ?>
		            			<tr>
		            				<td><input type="hidden" id="{{{$employee_classification->id}}}" />{{$employee_classification->classification_name}}</td>
		            				<td>
		            					<input type="hidden" name="person_education_{{{$employee_classification->id}}}" id="person_education_{{{$employee_classification->id}}}" value="{{{$person_education->id}}}">
		            					<input type="hidden" name="employee_classification_{{{$employee_classification->id}}}" id="employee_classification_{{{$employee_classification->id}}}" value="{{{$employee_classification->id}}}">
		            					<input class="form-control" type="text" name="school_name_{{$employee_classification->id}}" id="school_name_{{$employee_classification->id}}" value="{{{ Input::old('school_name', isset($person_education) ? $person_education->school_name : null) }}}" />
		            				</td>
		            				<td>
		            					<input class="form-control" type="text" name="years_attended" id="years_attended_{{$employee_classification->id}}" value="{{{ Input::old('years_attended', isset($person_education) ? $person_education->years_attended : null) }}}" />
		            				</td>
		            				<td>
		            					<input class="form-control" type="text" name="honors_received" id="honors_received_{{$employee_classification->id}}" value="{{{ Input::old('honors_received', isset($person_education) ? $person_education->honors_received : null) }}}" />
		            				</td>
		            				<td>
		            					<input class="form-control" type="text" name="course_graduated" id="course_graduated_{{$employee_classification->id}}" value="{{{ Input::old('course_graduated', isset($person_education) ? $person_education->course_graduated : null) }}}" />
		            				</td>
		            			</tr>
		            			@endif
		            		@endforeach

		            		@if($person_education_created == false)
		            			<tr>
		            				<td>{{$employee_classification->classification_name}}</td>
		            				<td>
		            					<input type="hidden" name="person_education_{{{$employee_classification->id}}}" value="">
		            					<input class="form-control" type="text" name="school_name_{{$employee_classification->id}}" id="school_name_{{$employee_classification->id}}" value="" />
		            					<input type="hidden" name="employee_classification_{{{$employee_classification->id}}}" id="employee_classification_{{{$employee_classification->id}}}" value="{{{$employee_classification->id}}}">
		            				</td>
		            				<td>
		            					<input class="form-control" type="text" name="years_attended" id="years_attended_{{$employee_classification->id}}" value="" />
		            				</td>
		            				<td>
		            					<input class="form-control" type="text" name="honors_received" id="honors_received_{{$employee_classification->id}}" value="" />
		            				</td>
		            				<td>
		            					<input class="form-control" type="text" name="course_graduated" id="course_graduated_{{$employee_classification->id}}" value="" />
		            				</td>
		            			</tr>
		            		@endif
		            	@endforeach
		            </tbody>
	            </table>
        	</fieldset>        	
	    </div> <!-- education tab end -->

	    <div class="tab-pane" id="seminars_attended_tab">
	    	<fieldset class="scheduler-border">
            <br/><!-- <legend class="scheduler-border">Seminars Attended</legend> -->
	            <table class="table table-bordered table-striped" id="seminar_table">
	            	<h2> Seminars Attendend
		            	<button id="save_changes_seminar" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
	                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
	            		</button>
	            	</h2>
	            	<th>Date</th>
	            	<th>Title of Training/Seminar</th>
	            	<th>Venue</th>
	            	<th>Remarks</th>
	            	<th>Action</th>
	            	<tbody id="seminar_container">
	            		@foreach($person_seminar_list as $person_seminar)
		            		@if($person_seminar -> is_cia == 0)
				            <tr id="person_seminar_{{$person_seminar -> id}}">
					            <td>
					            	<input type="hidden" id="person_seminar_id" name="person_seminar_id" value="{{{$person_seminar->id}}}" />
					            	<input class="form-control datepicker" type="text" name="seminar_date" id="seminar_date" value="{{{ Input::old('seminar_date', isset($person_seminar) ? date("F d,Y", strtotime($person_seminar->seminar_date)) : null) }}}" />
					            </td>
								<td>
									<input class="form-control" type="text" name="seminar_name" id="seminar_name" value="{{{ Input::old('seminar_name', isset($person_seminar) ? $person_seminar->seminar_name : null) }}}" />
								</td>
								<td>
									<input class="form-control" type="text" name="seminar_venue" id="seminar_venue" value="{{{ Input::old('seminar_venue', isset($person_seminar) ? $person_seminar->seminar_venue : null) }}}" />
								</td>
								<td>
									<input class="form-control" type="text" name="remark" id="remark" value="{{{ Input::old('remark', isset($person_seminar) ? $person_seminar->remark : null) }}}" />
								</td>
								<td>
									<button type="button" class="btn btn-danger remove_row" data-table="person_seminar" data-row="person_seminar_" data-id="{{ $person_seminar -> id }}">Remove</button>
								</td>
							</tr>
							@endif
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_seminar_row" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<!-- <button id="remove_seminar_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button> -->
	            </div>
			</fieldset>
			<br/>
			<hr/>
			<br/>
			<fieldset class="scheduler-border">
            <br/><!-- <legend class="scheduler-border">Seminars Attended</legend> -->
	            <table class="table table-bordered table-striped" id="seminar_table1">
		            <h2> CIA Seminars Attendend	
		            	<button id="save_changes_seminar1" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
	                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
	            		</button>
            		</h2>
	            	<th>Date</th>
	            	<th>Title of Training/Seminar</th>
	            	<th>Venue</th>
	            	<th>Remarks</th>
	            	<th>Action</th>
	            	<tbody id="seminar_container1">
	            		@foreach($person_seminar_list as $person_seminar)
		            		@if($person_seminar -> is_cia == 1)
				            <tr id="person_seminar_{{$person_seminar -> id}}">
					            <td>
					            	<input type="hidden" id="person_seminar_id" name="person_seminar_id" value="{{{$person_seminar->id}}}" />
					            	<input class="form-control datepicker" type="text" name="seminar_date" id="seminar_date" value="{{{ Input::old('seminar_date', isset($person_seminar) ? date("F d,Y", strtotime($person_seminar->seminar_date)) : null) }}}" />
					            </td>
								<td>
									<input class="form-control" type="text" name="seminar_name" id="seminar_name" value="{{{ Input::old('seminar_name', isset($person_seminar) ? $person_seminar->seminar_name : null) }}}" />
								</td>
								<td>
									<input class="form-control" type="text" name="seminar_venue" id="seminar_venue" value="{{{ Input::old('seminar_venue', isset($person_seminar) ? $person_seminar->seminar_venue : null) }}}" />
								</td>
								<td>
									<input class="form-control" type="text" name="remark" id="remark" value="{{{ Input::old('remark', isset($person_seminar) ? $person_seminar->remark : null) }}}" />
								</td>
								<td>
									<button type="button" class="btn btn-danger remove_row" data-table="person_seminar" data-row="person_seminar_" data-id="{{ $person_seminar -> id }}">Remove</button>
								</td>
							</tr>
							@endif
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_seminar_row1" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<!-- <button id="remove_seminar_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button> -->
	            </div>
			</fieldset>
	    </div>


	   <!--  <div class="tab-pane" id="dependents_tab">
	    	<fieldset class="scheduler-border">
       		<br/> 
       			<legend class="scheduler-border">Employee Dependents</legend>
	            <table class="table table-bordered table-striped" id="dependents_table">
	            	<button id="save_changes_dependents" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            		</button>
	            	<th>Name</th>
	            	<th>Date of Birth</th>
	            	<th>Relationship</th>
	            	<tbody id="dependents_container">
	            		@foreach($employee_dependent_list as $employee_dependent)
			            <tr>
				            <td>
				            	<input type="hidden" name="employee_dependent_id" value="{{{$employee_dependent->id}}}">
				            	<input class="form-control" type="text" name="dependent_name" id="dependent_name" value="{{{ Input::old('dependent_name', isset($employee_dependent) ? $employee_dependent->dependent_name : null) }}}" />
				            </td>
							<td>
								<input class="form-control datepicker" type="text" name="dependent_birthdate" id="dependent_birthdate" value="{{{ Input::old('dependent_birthdate', isset($employee_dependent) ? date("F d,Y", strtotime($employee_dependent->dependent_birthdate)) : null) }}}" />
							</td>
							<td>
								<select class="form-control" name="dependent_relationship_id" id="dependent_relationship_id" tabindex="4">
									<option name="" value="" selected></option>
									@foreach($dependent_relationship_list as $dependent_relationship)
										@if($dependent_relationship->id == $employee_dependent->dependent_relationship_id)
										<option name="dependent_relationship_id" value="{{{ $dependent_relationship->id }}}" selected>{{{ $dependent_relationship->dependent_relationship_name }}}</option>
										@else
										<option name="dependent_relationship_id" value="{{{ $dependent_relationship->id }}}">{{{ $dependent_relationship->dependent_relationship_name }}}</option>
										@endif
									@endforeach								
								</select>
							</td>
						</tr>
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_dependents_row" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<button id="remove_dependents_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button>
	            </div>
			</fieldset>
	    </div> -->

	    <div class="tab-pane" id="contacts_tab">
	    	<fieldset class="scheduler-border">
            <br/><!-- <legend class="scheduler-border">Employee Contacts</legend> -->
	            <table class="table table-bordered table-striped" id="contacts_table">
	            	<button id="save_changes_contacts" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            		</button>
	            	<th>Name</th>
	            	<th>Relation</th>
	            	<th>Contact Number</th>
	            	<th>Action</th>
	            	<tbody id="contacts_container">
	            		@foreach($employee_contact_list as $employee_contact)
			            <tr id="employee_contact_{{$employee_contact -> id}}">
				            <td>
				            	<input type="hidden" name="employee_contact_id" value="{{{$employee_contact->id}}}">
				            	<input class="form-control" type="text" name="contact_name" id="contact_name" value="{{{ Input::old('contact_name', isset($employee_contact) ? $employee_contact->contact_name : null) }}}" />
				            </td>
							<td>
								<select class="form-control" name="dependent_relationship_id" id="dependent_relationship_id" tabindex="4">
									<option name="" value="" selected></option>
									@foreach($dependent_relationship_list as $dependent_relationship)
										@if($dependent_relationship->id == $employee_contact->dependent_relationship_id)
										<option name="dependent_relationship_id" value="{{{ $dependent_relationship->id }}}" selected>{{{ $dependent_relationship->dependent_relationship_name }}}</option>
										@else
										<option name="dependent_relationship_id" value="{{{ $dependent_relationship->id }}}">{{{ $dependent_relationship->dependent_relationship_name }}}</option>
										@endif
									@endforeach								
								</select>
							</td>
							<td>
								<input class="form-control" type="text" name="employee_contact_no" id="employee_contact_no" value="{{{ Input::old('employee_contact_no',isset($employee_contact) ? $employee_contact->contact_no : null) }}}" />
							</td>
							<td>
								<button type="button" class="btn btn-danger remove_row" data-table="employee_contact" data-row="employee_contact_" data-id="{{ $employee_contact -> id }}">Remove</button>
							</td>
						</tr>
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_contacts_row" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<!-- <button id="remove_contacts_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button> -->
	            </div>
			</fieldset>
	    </div>
	    <div class="tab-pane" id="certificates_tab">
	    	<fieldset class="scheduler-border">
            	<br/><!-- <legend class="scheduler-border">Employee Contacts</legend> -->
	            <table class="table table-bordered table-striped" id="certificates_table">
	            	<button id="save_changes_certificates" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            		</button>
            		<h3>Other Certificate/s</h3>
	            	<th>Description</th>
	            	<th>Date</th>
	            	<th>Award By</th>
	            	<th>Action</th>
	            	<tbody id="certificates_container">
	            		@foreach($employee_certificate_list as $employee_certificate)
		            		@if($employee_certificate -> is_cia == 0)
					            <tr id="certificate_row_{{$employee_certificate -> id}}">
						            <td>
						            	<input type="hidden" name="employee_certificate_id" value="{{{$employee_certificate->id}}}">
						            	<input class="form-control" type="text" name="certificate_description" id="certificate_description" value="{{{ Input::old('certificate_description', isset($employee_certificate) ? $employee_certificate->description : null) }}}" />
						            </td>
						            <td>
										<input class="form-control datepicker" type="text" name="certificate_date" id="certificate_date" value="{{{ Input::old('certificate_date', isset($employee_certificate) ? date("F d,Y", strtotime($employee_certificate->date)) : null) }}}" />
									</td>
									<td>
										<input class="form-control" type="text" name="certificate_award_by" id="certificate_award_by" value="{{{ Input::old('certificate_award_by',isset($employee_certificate) ? $employee_certificate->award_by : null) }}}" />
									</td>
									<td>
										<button type="button" class="btn btn-danger remove_row" data-table="employee_certificate" data-row="certificate_row_" data-id="{{ $employee_certificate -> id }}">Remove</button>
									</td>
								</tr>
							@endif
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_certificates_row" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<!-- <button id="remove_certificates_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button> -->
	            </div>
			</fieldset>
			<br/>
			<hr/>
			<fieldset class="scheduler-border">
            	<br/>
	            <table class="table table-bordered table-striped" id="certificates_table_cia">
	            	<!-- <button id="save_changes_certificates" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            		</button> -->
            		<h3>CIA Certificate/s</h3>
	            	<th>Description</th>
	            	<th>Date</th>
	            	<th>Award By</th>
	            	<th>Action</th>
	            	<tbody id="certificates_container_cia">
	            		@foreach($employee_certificate_list as $employee_certificate)
	            			@if($employee_certificate -> is_cia == 1)
					            <tr id="certificate_row_{{$employee_certificate -> id}}">
						            <td>
						            	<input type="hidden" name="employee_certificate_id" value="{{{$employee_certificate->id}}}">
						            	<input class="form-control" type="text" name="certificate_description" id="certificate_description" value="{{{ Input::old('certificate_description', isset($employee_certificate) ? $employee_certificate->description : null) }}}" />
						            </td>
						            <td>
										<input class="form-control datepicker" type="text" name="certificate_date" id="certificate_date" value="{{{ Input::old('certificate_date', isset($employee_certificate) ? date("F d,Y", strtotime($employee_certificate->date)) : null) }}}" />
									</td>
									<td>
										<input class="form-control" type="text" name="certificate_award_by" id="certificate_award_by" value="{{{ Input::old('certificate_award_by',isset($employee_certificate) ? $employee_certificate->award_by : null) }}}" />
									</td>
									<td>
										<button type="button" class="btn btn-danger remove_row" data-table="employee_certificate" data-row="certificate_row_" data-id="{{ $employee_certificate -> id }}">Remove</button>
									</td>
								</tr>
							@endif
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_certificates_row_cia" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<!-- <button id="remove_certificates_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button> -->
	            </div>
			</fieldset>
	    </div>

	    <div class="tab-pane" id="working_experience_tab">
	    	<fieldset class="scheduler-border">
            <br/><!-- <legend class="scheduler-border">Employee Working Experience</legend> -->
	            <table class="table table-bordered table-striped" id="working_experience_table">
	            	<button id="save_changes_working_experience" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            		</button>
	            	<th>Company Name</th>
	            	<th>Position</th>
	            	<th>Date From</th>
	            	<th>Date To</th>
	            	<th>Rate</th>
	            	<th>Action</th>
	            	<tbody id="working_experience_container">
	            		@foreach($employee_working_experience_list as $employee_working_experience)
			            <tr id="employee_working_experience_{{$employee_working_experience -> id}}">
				            <td>
				            	<input type="hidden" name="employee_working_experience_id" value="{{{$employee_working_experience->id}}}">
				            	<input class="form-control" type="text" name="company_name" id="company_name" value="{{{ Input::old('company_name', isset($employee_working_experience) ? $employee_working_experience->company_name : null) }}}" />
				            </td>
				            <td>
				            	<input class="form-control" type="text" name="experience_position" id="experience_position" value="{{{ Input::old('experience_position', isset($employee_working_experience) ? $employee_working_experience->position : null) }}}" />
				            </td>
				            <td>
								<input class="form-control datepicker2" type="text" name="experience_date_from" id="experience_date_from" value="{{{ Input::old('experience_date_from', isset($employee_working_experience) ? date("F-Y", strtotime($employee_working_experience->date_employed_from)) : null) }}}" />
							</td>
							<td>
								<input class="form-control datepicker2" type="text" name="experience_date_to" id="experience_date_to" value="{{{ Input::old('experience_date_to', isset($employee_working_experience) ? date("F-Y", strtotime($employee_working_experience->date_employed_to)) : null) }}}" />
							</td>
				            <td>
				            	<input class="form-control" type="text" name="experience_rate" id="experience_rate" value="{{{ Input::old('experience_rate', isset($employee_working_experience) ? $employee_working_experience->rate : null) }}}" />
				            </td>
				            <td>
								<button type="button" class="btn btn-danger remove_row" data-table="employee_working_experience" data-row="employee_working_experience_" data-id="{{ $employee_working_experience -> id }}">Remove</button>
							</td>
						</tr>
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_working_experience_row" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<!-- <button id="remove_working_experience_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button> -->
	            </div>
			</fieldset>
	    </div>

	    <div class="tab-pane" id="government_contribution_tab">
	    	<fieldset class="scheduler-border">
            <br/><!-- <legend class="scheduler-border">Employee Contacts</legend> -->
	            <table class="table table-bordered table-striped" id="government_contribution_table">
	            	<button id="save_changes_government_contribution" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            		</button>
	            	<th>Government Department</th>
	            	<th>Contribution Number</th>
	            	<th>Action</th>
	            	<tbody id="government_contribution_container">
	            		@foreach($employee_government_contribution_list as $employee_government_contribution)
			            <tr id="employee_government_contribution_{{$employee_government_contribution -> id}}">
				            <td>
				            	<input type="hidden" name="employee_government_contribution_id" value="{{{$employee_government_contribution->id}}}">
				            	<input class="form-control" type="text" name="employee_government_department" id="employee_government_department" value="{{{ Input::old('employee_government_contribution', isset($employee_government_contribution) ? $employee_government_contribution-> government_department : null) }}}" />
				            </td>
							<td>
								<input class="form-control" type="text" name="employee_government_contribution_no" id="employee_government_contribution_no" value="{{{ Input::old('employee_government_contribution',isset($employee_government_contribution) ? $employee_government_contribution->employee_contribution_number : null) }}}" />
							</td>
							<td>
								<button type="button" class="btn btn-danger remove_row" data-table="employee_contribution_number" data-row="employee_government_contribution_" data-id="{{ $employee_government_contribution -> id }}">Remove</button>
							</td>
						</tr>
						@endforeach
					</tbody>					
				</table>
				<div class="pull-right">
					<button id="add_government_contribution_row" type="button" class="btn btn-sm btn-primary">
	                	<span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
	            	</button>
	            	<!-- <button id="remove_government_contribution_row" type="button" class="btn btn-sm btn-warning">
	                	<span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
	            	</button> -->
	            </div>
			</fieldset>
	    </div>

	</div>


</form>
	
<script>
$(function(){

	var is_active = $("#is_active_val").val();
	if(is_active == 1)
	{
		$("#is_active").bootstrapToggle('on');
	}
	else
	{
		$("#is_active").bootstrapToggle('off');
	}

	var role = $("#gen_role_id").val();
	var employee_type_id = $("#employee_type_id").val();
	$("#teacher_teaching_skill").hide();
	$("#room_div").hide();
	$("#passport_number_div").hide();
	$("#i_card_div").hide();
	$("#visa_div").hide();

	if(role == 1)
	{
		$("#teacher_teaching_skill").show();
		$("#room_div").show();
	}
	if(employee_type_id == 1)
	{
		$("#passport_number_div").show();
		$("#i_card_div").show();
		$("#visa_div").show();
	}

	if($("#employment_status_id").val() == 1)
	{
		$(".contract_date").hide();
	}
	else
	{
		$(".contract_date").show();
	}

	$("#employment_status_id").change(function(){

		if($(this).val() == 1)
		{
			$(".contract_date").hide();
		}
		else
		{
			$(".contract_date").show();
		}
	});
	$("#employee_type_id").change(function(){
		employee_type_id = $("#employee_type_id").val();

		if(employee_type_id == 1)
		{
			$("#passport_number_div").show();
			$("#i_card_div").show();
			$("#visa_div").show();
		}
		else
		{
			$("#passport_number_div").hide();
			$("#i_card_div").hide();
			$("#visa_div").hide();
		}
	});

	$(".skill_action").change(function(){
			var id = $(this).data('skill_id');
		    if($(this).prop("checked") == true){
					$(".skill_action").each(function(){
						data_id = $(this).data('skill_id');
						if(data_id != id)
						{
							$(this).bootstrapToggle('off');
						}
					});
					// $("#toggle_trigger_"+id).bootstrapToggle('on');
					$("#toggle_trigger_"+id).prop('checked', true).change();
			}
		});
});

$(":submit").closest("form").submit(function(){
       $(':submit').attr('disabled', 'disabled');
   });

$("#program_category_id").change(function(){
	selectListRoomSpaceChangeEmployee('program_skill_id','{{{URL::to("employee/programCategoryDataJson")}}}',  { 'program_category_id': $(this).val() } ,'Please select a Program Category')
});

$("#add_skill").click(function(){
		id = $("#program_skill_id").val();
		data = $("#program_skill_id option:selected").text();
		course = $("#program_category_id option:selected").text();

		if(data != "" && data != 0 && course != "" && course != 0)
		{

			$("#skill_con").append('<div class="checkbox">'
			  +'<label class="skill" data-id="'+id+'">'
			    +'<input class="skill_action" data-db="0" data-skill_id="'+id+'" id="toggle_trigger_'+id+'" type="checkbox" data-toggle="toggle" data-on="Default" data-off="Make Default" data-onstyle="success">'+course+' ('+data+')'
			  +'</label>'
			+'</div>');

			$("#program_skill_id [value='"+id+"']").attr('disabled','disabled');
			$("#program_skill_id [value='0']").attr('selected','selected');

			$('#toggle_trigger_'+id).bootstrapToggle('off');

			$("#toggle_trigger_"+id).change(function(){
				var id = $(this).data('skill_id');
			    if($(this).prop("checked") == true){
						$(".skill_action").each(function(){
							data_id = $(this).data('skill_id');
							if(data_id != id)
							{
								$(this).bootstrapToggle('off');
							}
						});
						// $("#toggle_trigger_"+id).bootstrapToggle('on');
						$("#toggle_trigger_"+id).prop('checked', true).change();
				}
			});
		}
});
	
	$(".remove_row").click(function(){
		var id = $(this).data("id");
		var row = $(this).data("row");
		var table = $(this).data("table");

		// if(id == 0)
		// {
		// 	// $("#"+row+""+id).remove();
		// }
		// else
		// {
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
                        'table' : table,
                        '_token' : $("input[name=_token]").val(),
                    },
                    dataType: "json",
                    async:false,
                    success: function (data) 
                    {            
                        swal("Deleted!", "Your data has been deleted.", "success");  
                        // location.reload();    
                        $("#"+row+""+id).remove();
                    }  
                });
            });
		// }

	});

	function displayImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#myImg').attr('src', e.target.result);
                $('#image_canvas').val(e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

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


	function fieldPosition(position_count,id) {

		$("#field_position").append(''
			+'<fieldset>'
			            +'<div class="form-group">'
			            	+'<input type="hidden" name="employee_position_id" id="employee_position_id_'+position_count+'" value="'+id+'"/>'
			                +'<label class="col-xs-1 control-label">Employee Type</label>'
				                +'<div class="col-xs-5">'
				                    +'<select class="form-control" name="employee_type_id" id="employee_type_id_'+position_count+'" tabindex="4">'			
										+'<option name="" value="" selected></option>'
										+'@foreach($employee_type_list as $employee_type)'
											+'<option name="employee_type_id" value="{{{ $employee_type->id }}}">{{{ $employee_type->employee_type_name }}}</option>'
										+'@endforeach'							
									+'</select>'
				                +'</div>'
			               	+'<label class="col-xs-1 control-label">Position</label>'
				                +'<div class="col-xs-5">'
				                    +'<select class="form-control" name="position_id" id="position_id_'+position_count+'" tabindex="4">'
										+'<option name="" value="" selected></option>'
										+'@foreach($position_list as $position)'
											+'<option name="position_id" value="{{{ $position->id }}}">{{{ $position->position_name }}}</option>'
										+'@endforeach'
									+'</select>'
				                +'</div>'
			            +'</div>'
			            +'<div class="form-group">'
			            	+'<label class="col-xs-1 control-label">Employment Status</label>'
				                +'<div class="col-xs-5">'
				                    +'<select class="form-control employment_status_id" data-id="'+position_count+'" name="employment_status_id" id="employment_status_id_'+position_count+'" tabindex="4">'
										+'<option name="" value="" selected></option>'
										+'@foreach($employment_status_list as $employment_status)'
											+'<option name="employment_status_id" value="{{{ $employment_status->id }}}">{{{ $employment_status->employment_status_name }}}</option>'
										+'@endforeach'
									+'</select>'
				                +'</div>'
				            +'<label class="col-xs-1 control-label">Rate / Salary</label>'
				                +'<div class="col-xs-5">'
				                	+'<input class="form-control" type="text" name="rate" id="rate_'+position_count+'" value="" />'
				                +'</div>' 				            
			            +'</div>'
			            +'<div class="form-group">'
				            +'<label class="col-xs-1 control-label">Contract Period</label>'
							    +'<div class="input-daterange input-group col-xs-5 datepicker1" id="datepicker" style="padding-left: 15px !important;padding-right: 15px !important;">'
							        +'<input type="text" class="contract_date_start form-control" name="contract_date_start" id="contract_date_start_'+position_count+'" value="" />'
							        +'<span class="input-group-addon contract_date_'+position_count+'">to</span>'
							        +'<input type="text" class="contract_date_end form-control contract_date_'+position_count+'" name="contract_date_end" id="contract_date_end_'+position_count+'" value="" />'
							    +'</div>' 	  
			            +'</div>'
		            +'</fieldset>'
			+'');

		$(".employment_status_id").change(function(){

			var id = $(this).data('id');
			if($(this).val() == 1)
			{
				$(".contract_date_"+id).hide();
			}
			else
			{
				$(".contract_date_"+id).show();
			}
		});
		$('.datepicker1').datepicker({
            format: "yyyy-mm-dd",
            orientation: "auto",
            autoclose: true,
            startView: 1,
            todayHighlight: true,
            todayBtn: "linked",
          });
	}

	$.ajax({
    		url:'{{{ URL::to("employee/getPosition") }}}',
            type:'GET',
            data:
                {  
                    'employee_id': $("#employee_id").val(),
                },
            dataType: "json",
            async:false,
            success: function (data) 
            {    
            	var position_count = $("#position_count").val();
                $.map(data, function (item) 
                {      
					position_count++;
					fieldPosition(position_count,item.id);

					$("#employee_type_id_"+position_count+" [value='"+item.employee_type_id+"']").attr("selected","selected");
		
					$("#employee_type_id_"+position_count).change(function(){
			                selectListChange('position_id_'+position_count,'{{{URL::to("employee/positionDataJson")}}}',  { 'employee_type_id': $("#employee_type_id_"+position_count).val() } ,'Please select a Employee Type')
			        });

			        $("#position_id_"+position_count+" [value='"+item.position_id+"']").attr("selected","selected");
			        $("#employment_status_id_"+position_count+" [value='"+item.employment_status_id+"']").attr("selected","selected");
			        $("#rate_"+position_count).val(item.rate);
			        $("#contract_date_start_"+position_count).val(item.contract_date_start);
			        $("#contract_date_end_"+position_count).val(item.contract_date_end);


					if($("#employment_status_id_"+position_count).val() == 1)
					{
						$(".contract_date_"+position_count).hide();
					}
					else
					{
						$(".contract_date_"+position_count).show();
					}

                });
                $("#position_count").val(position_count);
            }  
    });

	$("#add_position").click(function(){

		var position_count = $("#position_count").val();
		position_count++;
		$("#position_count").val(position_count);

		fieldPosition(position_count,0);
		$("#employee_type_id_"+position_count).change(function(){
                selectListChange('position_id_'+position_count,'{{{URL::to("employee/positionDataJson")}}}',  { 'employee_type_id': $("#employee_type_id_"+position_count).val() } ,'Please select a Employee Type')
        });
	});
	//employee_cop_nac table
	//save table data by iterating rows
	$("#save_changes_requirement").click(function(){
		//iterate/visit employee_cop_nac table
		//swal("save_changes_requirement clicked");
		$("#requirement_table > tbody > tr").each(function(){
            var employee_id = $("#employee_id").val();
            var course_cop_nac_id = $(this).find('select[name="course_cop_nac_id"]').val();
            if(typeof course_cop_nac_id !== "undefined"){	
     			var employee_cop_nac_id = $(this).find('input[name="employee_cop_nac_id"]').val();
     			var date_of_issue = $(this).find('input[name="date_of_issue"]').val();
     			var remark = $(this).find('input[name="remark"]').val();

     			$.ajax(
				{
					url:'{{{ URL::to("employee_cop_nac/postSaveCopNacRequirementJson") }}}',
					type:'post',
					data:
						{
							'employee_id': employee_id,
							'id': employee_cop_nac_id,
							'course_cop_nac_id':course_cop_nac_id,
							'date_of_issue': date_of_issue,
							'remark': remark,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					
				});
      		}
		});
		swal("Saved Changes!");
		 // location.reload();
	});

	//employee_dependent table
	// $("#save_changes_dependent").click(function(){
	// 	//iterate/visit employee_dependent table
	// 	$("#dependent_table > tbody > tr").each(function(){
 //            var employee_id = $("#employee_id").val();
 //            var dependent_name = $(this).find('input[name="dependent_name"]').val();
 //            // var dependent_name = $(this).find('input[name="dependent_name"]').val();
 //            // var dependent_birthdate = $(this).find('input[name="dependent_birthdate"]').val();
 //            // var dependent_relationship_id = $(this).find('select[name="dependent_relationship_id"]').val();
 //            if(typeof dependent_name !== "undefined"){	
 //     			var employee_dependent_id = $(this).find('input[name="employee_dependent_id"]').val();
	//             var dependent_birthdate = $(this).find('input[name="dependent_birthdate"]').val();
	//             var dependent_relationship_id = $(this).find('select[name="dependent_relationship_id"]').val();

 //     			$.ajax(
	// 			{
	// 				url:'{{{ URL::to("employee_dependent/postSaveEmployeeDependentJson") }}}',
	// 				type:'post',
	// 				data:
	// 					{
	// 						'employee_id': employee_id,
	// 						'id': employee_dependent_id,
	// 						'dependent_name':dependent_name,
	// 						'dependent_birthdate': dependent_birthdate,
	// 						'dependent_relationship_id': dependent_relationship_id,
	// 						'_token': $('input[name=_token]').val(),
	// 					},
	// 					async:false
	// 			}

	// 			).done(function(data){
					
	// 			});	
	// 		}      		
	// 	});
	// 	swal("Saved Changes!");
	// 	 location.reload();
	// });
	
	//person_education table
	$("#save_changes_education").click(function(){
		
		//iterate/visit person_education table
		var count = 1;
		$("#education_table > tbody > tr").each(function(){
            var person_id = $("#person_id").val();
            var employee_classification_id = $("#employee_classification_"+count).val();
           	// var dependent_birthdate = $("#dependent_birthdate_"+count).val();
           	var person_education_id = $("#person_education_"+count).val();

            if(employee_classification_id){            
	            var years_attended = $("#years_attended_"+count).val();
	            var honors_received = $("#honors_received_"+count).val();
	            var course_graduated = $("#course_graduated_"+count).val();
	            var school_name = $("#school_name_"+count).val();
	            
     			$.ajax(
				{
					url:'{{{ URL::to("person_education/postSavePersonEducationJson") }}}',
					type:'post',
					data:
						{
							'person_id': person_id,
							'id': person_education_id,
							// 'dependent_birthdate':dependent_birthdate,
							'employee_classification_id':employee_classification_id,
							'school_name':school_name,
							'years_attended': years_attended,
							'honors_received': honors_received,
							'course_graduated': course_graduated,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					//swal("Saved Changes!");
				});	
      		}
      		count++;
		});
		swal("Saved Changes!");
		// location.reload();
	});

	

	//person_seminar table
	$("#save_changes_seminar").click(function(){
		//iterate/visit person_seminar table
		$("#seminar_table > tbody > tr").each(function(){

            var employee_id = $("#employee_id").val();
            var seminar_name = $(this).find('input[name="seminar_name"]').val();
            // var seminar_name = $(this).find('input[name="seminar_name"]').val();
            // swal(seminar_name);
            if(typeof seminar_name !== "undefined" &&  seminar_name != ""){
     			var seminar_date = $(this).find('input[name="seminar_date"]').val();
	            var person_id = $("#person_id").val();
	            var seminar_venue = $(this).find('input[name="seminar_venue"]').val();
	            var remark = $(this).find('input[name="remark"]').val();
	            var person_seminar_id = $(this).find('input[name="person_seminar_id"]').val();
	            // swal(person_seminar_id);
     			$.ajax(
				{
					url:'{{{ URL::to("person_seminar/postSavePersonSeminarJson") }}}',
					type:'post',
					data:
						{	'employee_id': employee_id,
							'id': person_seminar_id,
							'person_id': person_id,
							'is_cia': 0,
							'seminar_date': seminar_date,
							'seminar_name': seminar_name,
							'seminar_venue': seminar_venue,
							'remark': remark,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					// swal(JSON.stringify(data));
					
				});	

			}
		});
			swal("Saved Changes!");
		 // location.reload();
	});
	
	//person_seminar table
	$("#save_changes_seminar1").click(function(){
		//iterate/visit person_seminar table
		$("#seminar_table1 > tbody > tr").each(function(){

            var employee_id = $("#employee_id").val();
            var seminar_name = $(this).find('input[name="seminar_name"]').val();
            // var seminar_name = $(this).find('input[name="seminar_name"]').val();
            // swal(seminar_name);
            if(typeof seminar_name !== "undefined" &&  seminar_name != ""){
     			var seminar_date = $(this).find('input[name="seminar_date"]').val();
	            var person_id = $("#person_id").val();
	            var seminar_venue = $(this).find('input[name="seminar_venue"]').val();
	            var remark = $(this).find('input[name="remark"]').val();
	            var person_seminar_id = $(this).find('input[name="person_seminar_id"]').val();
	            // swal(person_seminar_id);
     			$.ajax(
				{
					url:'{{{ URL::to("person_seminar/postSavePersonSeminarJson") }}}',
					type:'post',
					data:
						{	'employee_id': employee_id,
							'id': person_seminar_id,
							'person_id': person_id,
							'is_cia': 1,
							'seminar_date': seminar_date,
							'seminar_name': seminar_name,
							'seminar_venue': seminar_venue,
							'remark': remark,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					// swal(JSON.stringify(data));
					
				});	

			}
		});
			swal("Saved Changes!");
		 // location.reload();
	});
	

	//person_seminar table
	// $("#save_changes_dependents").click(function(){
	// 	//iterate/visit person_seminar table
	// 	$("#dependents_table > tbody > tr").each(function(){

 //            var employee_id = $("#employee_id").val();
 //            var dependent_name = $(this).find('input[name="dependent_name"]').val();
 //            // swal(dependent_name);
 //            if(typeof dependent_name !== "undefined" &&  dependent_name != ""){
 //     			var dependent_birthdate = $(this).find('input[name="dependent_birthdate"]').val();
	//             var dependent_relationship_id = $(this).find('select[name="dependent_relationship_id"]').val();
	//             var employee_dependent_id = $(this).find('input[name="employee_dependent_id"]').val();
	//             // swal(person_seminar_id);
 //     			$.ajax(
	// 			{
	// 				url:'{{{ URL::to("employee_dependent/postSaveEmployeeDependentJson") }}}',
	// 				type:'post',
	// 				data:
	// 					{	'employee_id': employee_id,
	// 						'id': employee_dependent_id,
	// 						'dependent_name': dependent_name,
	// 						'dependent_birthdate': dependent_birthdate,
	// 						'dependent_relationship_id': dependent_relationship_id,
	// 						'_token': $('input[name=_token]').val(),
	// 					},
	// 					async:false
	// 			}

	// 			).done(function(data){
	// 				// swal(JSON.stringify(data));
					
	// 			});	

	// 		}
	// 	});
	// 		swal("Saved Changes!");
	// 	 location.reload();
	// });

	$("#save_changes_contacts").click(function(){
		//iterate/visit person_seminar table
		$("#contacts_table > tbody > tr").each(function(){

            var employee_id = $("#employee_id").val();
            var contact_name = $(this).find('input[name="contact_name"]').val();
            // swal(contact_name);
            if(typeof contact_name !== "undefined" &&  contact_name != ""){
     			var employee_contact_no = $(this).find('input[name="employee_contact_no"]').val();
	            var dependent_relationship_id = $(this).find('select[name="dependent_relationship_id"]').val();
	            var employee_contact_id = $(this).find('input[name="employee_contact_id"]').val();
	            // swal(person_seminar_id);
     			$.ajax(
				{
					url:'{{{ URL::to("employee_dependent/postSaveEmployeeContactJson") }}}',
					type:'post',
					data:
						{	'employee_id': employee_id,
							'id': employee_contact_id,
							'contact_name': contact_name,
							'contact_no': employee_contact_no,
							'dependent_relationship_id': dependent_relationship_id,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					// swal(JSON.stringify(data));
					
				});	

			}
		});
			swal("Saved Changes!");
		 // location.reload();
	});

	$("#save_changes_government_contribution").click(function(){
		//iterate/visit person_seminar table
		$("#government_contribution_table > tbody > tr").each(function(){

            var employee_id = $("#employee_id").val();
            var employee_government_department = $(this).find('input[name="employee_government_department"]').val();
            // swal(contact_name);
            if(typeof employee_government_department !== "undefined" &&  employee_government_department != ""){
     			var employee_government_contribution_no = $(this).find('input[name="employee_government_contribution_no"]').val();
	            var employee_government_contribution_id = $(this).find('input[name="employee_government_contribution_id"]').val();
	            // swal(person_seminar_id);
     			$.ajax(
				{
					url:'{{{ URL::to("employee_dependent/postSaveGovernmentContributionJson") }}}',
					type:'post',
					data:
						{	'employee_id': employee_id,
							'id': employee_government_contribution_id,
							'employee_government_department': employee_government_department,
							'employee_government_contribution_no': employee_government_contribution_no,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					// swal(JSON.stringify(data));
					
				});	

			}
		});
			swal("Saved Changes!");
		 // location.reload();
	});
	
	$("#save_changes_certificates").click(function(){
		//iterate/visit person_seminar table
		$("#certificates_table > tbody > tr").each(function(){

            var employee_id = $("#employee_id").val();
            var certificate_description = $(this).find('input[name="certificate_description"]').val();
            // swal(contact_name);
            if(typeof certificate_description !== "undefined" &&  certificate_description != ""){
     			var employee_certificate_id = $(this).find('input[name="employee_certificate_id"]').val();
	            var certificate_date = $(this).find('input[name="certificate_date"]').val();
	            var certificate_award_by = $(this).find('input[name="certificate_award_by"]').val();
	            // swal(person_seminar_id);
     			$.ajax(
				{
					url:'{{{ URL::to("employee_dependent/postSaveEmployeeCertificateJson") }}}',
					type:'post',
					data:
						{	'employee_id': employee_id,
							'id': employee_certificate_id,
							'description': certificate_description,
							'date': certificate_date,
							'is_cia': 0,
							'award_by': certificate_award_by,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					// swal(JSON.stringify(data));
					
				});	

			}
		});

		$("#certificates_table_cia > tbody > tr").each(function(){

            var employee_id = $("#employee_id").val();
            var certificate_description = $(this).find('input[name="certificate_description"]').val();
            // swal(contact_name);
            if(typeof certificate_description !== "undefined" &&  certificate_description != ""){
     			var employee_certificate_id = $(this).find('input[name="employee_certificate_id"]').val();
	            var certificate_date = $(this).find('input[name="certificate_date"]').val();
	            var certificate_award_by = $(this).find('input[name="certificate_award_by"]').val();
	            // swal(person_seminar_id);
     			$.ajax(
				{
					url:'{{{ URL::to("employee_dependent/postSaveEmployeeCertificateJson") }}}',
					type:'post',
					data:
						{	'employee_id': employee_id,
							'id': employee_certificate_id,
							'description': certificate_description,
							'date': certificate_date,
							'is_cia': 1,
							'award_by': certificate_award_by,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					// swal(JSON.stringify(data));
					
				});	

			}
		});
			swal("Saved Changes!");
		 // location.reload();
	});
	
	$("#save_changes_working_experience").click(function(){
		//iterate/visit person_seminar table
		$("#working_experience_table > tbody > tr").each(function(){

            var employee_id = $("#employee_id").val();
            var company_name = $(this).find('input[name="company_name"]').val();
            // swal(contact_name);
            if(typeof company_name !== "undefined" &&  company_name != ""){
     			var employee_working_experience_id = $(this).find('input[name="employee_working_experience_id"]').val();
	            var experience_position = $(this).find('input[name="experience_position"]').val();
	            var experience_date_from = $(this).find('input[name="experience_date_from"]').val();
	            var experience_date_to = $(this).find('input[name="experience_date_to"]').val();
	            var experience_rate = $(this).find('input[name="experience_rate"]').val();
	            // swal(person_seminar_id);
     			$.ajax(
				{
					url:'{{{ URL::to("employee_dependent/postSaveEmployeeExperienceJson") }}}',
					type:'post',
					data:
						{	'employee_id': employee_id,
							'id': employee_working_experience_id,
							'company_name': company_name,
							'experience_position': experience_position,
							'experience_date_from': experience_date_from,
							'experience_date_to': experience_date_to,
							'experience_rate': experience_rate,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					// swal(JSON.stringify(data));
					
				});	

			}
		});
			swal("Saved Changes!");
		 // location.reload();
	});

	
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
				var contract_date_start = $(this).find('input[name="contract_date_start"]').val();
				var contract_date_end = $(this).find('input[name="contract_date_end"]').val();

				position_array[0] = position_id;
				position_array[1] = employee_type_id;
				position_array[2] = employment_status_id;
				position_array[3] = rate;
				position_array[4] = employee_position_id;
				position_array[5] = contract_date_start;
				position_array[6] = contract_date_end;

				position_array_con[count] = position_array;
				count++;
			}
		});	

		var skill_id = [];
		var skill_db = [];
		var skill_data = [];
		var skill_count = 0;
		$(".skill_action").each(function(){
				skill_id[skill_count] = $(this).data('skill_id');
				skill_db[skill_count] = $(this).data('db');
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

		if($("#is_active").prop("checked") == true)
		{
			is_active = "1";
		}
		else
		{
			is_active = "0";
	    }

		$.ajax(
				{
					url:'{{{ URL::to("employee/createJson") }}}',
					type:'post',
					data:
						{

							'_token': $('input[name=_token]').val(),
							'skill_id': skill_id,
							'skill_data': skill_data,
							'skill_db': skill_db,
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
							'gender_id': $('#gender_id').val(),
							'campus_id': $('#campus_id').val(),
							'image_canvas': $("#image_canvas").val(),
							'birthdate': $('#birthdate').val(),
							'place_of_birth': $('#place_of_birth').val(),
							'passport_number': $('#passport_number').val(),
							'pass_issue_date': $('#pass_issue_date').val(),
							'pass_expiry_date': $('#pass_expiry_date').val(),
							'i_card': $('#i_card').val(),
							'i_card_issue_date': $('#i_card_issue_date').val(),
							'i_card_expiry_date': $('#i_card_expiry_date').val(),
							// 'visa': $('#visa').val(),
							'visa_status': $('#visa_status').val(),
							'visa_date': $('#visa_date').val(),
							'visa_end_date': $('#visa_end_date').val(),
							'is_active': is_active,
							'contact_no': $('#contact_no').val(),
						},
						async:false
				}

		).done(function(data){
			swal("Saved Changes!");
			// swal("Saved!", "Successfully Saved Changes on Employee Detail!", "Success" );
	 		// location.reload();
			// swal(date_employed);
		});

	});




    $("#add_seminar_row").click(function(){
      $("#seminar_container").append('<tr>'
      					+'<input type="hidden" id="person_seminar_id" name="person_seminar_id" value="0" />'
			            +'<td><input class="form-control datepicker" type="text" name="seminar_date" id="seminar_date" value="" /></td>'
						+'<td><input class="form-control" type="text" name="seminar_name" id="seminar_name" value="" /></td>'
						+'<td><input class="form-control" type="text" name="seminar_venue" id="seminar_venue" value="" /></td>'
						+'<td><input class="form-control" type="text" name="remark" id="remark" value="" /></td>'
						+'<td>'
						+'</td>'
						+'</tr>');
      					datepickerClick();
    });
    $("#remove_seminar_row").on('click', function(){
    	$("#seminar_container tr:last-child").remove(); //or like this
    });
    $("#add_seminar_row1").click(function(){
      $("#seminar_container1").append('<tr>'
      					+'<input type="hidden" id="person_seminar_id" name="person_seminar_id" value="0" />'
			            +'<td><input class="form-control datepicker" type="text" name="seminar_date" id="seminar_date" value="" /></td>'
						+'<td><input class="form-control" type="text" name="seminar_name" id="seminar_name" value="" /></td>'
						+'<td><input class="form-control" type="text" name="seminar_venue" id="seminar_venue" value="" /></td>'
						+'<td><input class="form-control" type="text" name="remark" id="remark" value="" /></td>'
						+'<td>'
						+'</td>'
						+'</tr>');
      					datepickerClick();
    });
    $("#remove_seminar_row").on('click', function(){
    	$("#seminar_container tr:last-child").remove(); //or like this
    });

    // $("#add_dependents_row").click(function(){
    //   $("#dependents_container").append('<tr>'
    //   					+'<input type="hidden" id="employee_dependent_id" name="employee_dependent_id" value="0" />'
				// 		+'<td><input class="form-control" type="text" name="dependent_name" id="dependent_name" value="" /></td>'
			 //            +'<td><input class="form-control datepicker" type="text" name="dependent_birthdate" id="dependent_birthdate" value="" /></td>'
				// 		+'<td><select class="form-control" id="dependent_relationship_id" name="dependent_relationship_id" tabindex="4">'
				// 				+'<option name="" value=""></option>'
				//             	+'@foreach($dependent_relationship_list as $dependent_relationship)'
				//             	+'<option type="text" name="dependent_relationship_id" value="{{{ $dependent_relationship->id }}}">{{$dependent_relationship->dependent_relationship_name}}</option>'		            	
				//             	+'@endforeach'
			 //            	+'</select></td>'						
				// 		+'</tr>');
    //   					datepickerClick();
    // });
    // $("#remove_dependents_row").on('click', function(){
    // 	$("#dependents_container tr:last-child").remove(); //or like this
    // });
    $("#add_contacts_row").click(function(){
      $("#contacts_container").append('<tr>'
      					+'<input type="hidden" id="employee_contact_id" name="employee_contact_id" value="0" />'
						+'<td><input class="form-control" type="text" name="contact_name" id="contact_name" value="" /></td>'
			            +'<td><select class="form-control" id="dependent_relationship_id" name="dependent_relationship_id" tabindex="4">'
								+'<option name="" value=""></option>'
				            	+'@foreach($dependent_relationship_list as $dependent_relationship)'
				            	+'<option type="text" name="dependent_relationship_id" value="{{{ $dependent_relationship->id }}}">{{$dependent_relationship->dependent_relationship_name}}</option>'		            	
				            	+'@endforeach'
			            	+'</select></td>'
						+'<td><input class="form-control" type="text" name="employee_contact_no" id="employee_contact_no" value="" /></td>'
						+'<td>'
						+'</td>'
						+'</tr>');
    });
    $("#remove_contacts_row").on('click', function(){
    	$("#contacts_container tr:last-child").remove(); //or like this
    });

    $("#add_government_contribution_row").click(function(){
      $("#government_contribution_container").append('<tr>'
      					+'<input type="hidden" id="employee_government_contribution_id" name="employee_government_contribution_id" value="0" />'
						+'<td><input class="form-control" type="text" name="employee_government_department" id="employee_government_department" value="" /></td>'
						+'<td><input class="form-control" type="text" name="employee_government_contribution_no" id="employee_government_contribution_no" value="" /></td>'
						+'<td>'
						+'</td>'
						+'</tr>');
    });

    $("#remove_government_contribution_row").on('click', function(){
    	$("#government_contribution_container tr:last-child").remove(); //or like this
    });

    $("#add_certificates_row").click(function(){
      $("#certificates_container").append('<tr>'
      						+'<td>'
				            	+'<input type="hidden" name="employee_certificate_id" value="0">'
				            	+'<input class="form-control" type="text" name="certificate_description" id="certificate_description" value="" />'
				            +'</td>'
				            +'<td>'
								+'<input class="form-control datepicker" type="text" name="certificate_date" id="certificate_date" value="" />'
							+'</td>'
							+'<td>'
								+'<input class="form-control" type="text" name="certificate_award_by" id="certificate_award_by" value="" />'
							+'</td>'
							+'<td>'
								// +'<button type="button" class="btn btn-danger" data-id="0">Remove</button>'
							+'</td>'
      					+'</tr>');
      					datepickerClick();
    });

    $("#add_certificates_row_cia").click(function(){
      $("#certificates_container_cia").append('<tr>'
      						+'<td>'
				            	+'<input type="hidden" name="employee_certificate_id" value="0">'
				            	+'<input class="form-control" type="text" name="certificate_description" id="certificate_description" value="" />'
				            +'</td>'
				            +'<td>'
								+'<input class="form-control datepicker" type="text" name="certificate_date" id="certificate_date" value="" />'
							+'</td>'
							+'<td>'
								+'<input class="form-control" type="text" name="certificate_award_by" id="certificate_award_by" value="" />'
							+'</td>'
							+'<td>'
								// +'<button type="button" class="btn btn-danger" data-id="0">Remove</button>'
							+'</td>'
      					+'</tr>');
      					datepickerClick();
    });

    $("#remove_contacts_row").on('click', function(){
    	$("#contacts_container tr:last-child").remove(); //or like this
    });
    
    $("#add_working_experience_row").click(function(){
      $("#working_experience_container").append('<tr>'
				            +'<td>'
				            	+'<input type="hidden" name="employee_working_experience_id" value="0">'
				            	+'<input class="form-control" type="text" name="company_name" id="company_name" value="" />'
				            +'</td>'
				            +'<td>'
				            	+'<input class="form-control" type="text" name="experience_position" id="experience_position" value="" />'
				            +'</td>'
				            +'<td>'
								+'<input class="form-control datepicker" type="text" name="experience_date_from" id="experience_date_from" value="" />'
							+'</td>'
							+'<td>'
								+'<input class="form-control datepicker" type="text" name="experience_date_to" id="experience_date_to" value="" />'
							+'</td>'
				            +'<td>'
				            	+'<input class="form-control" type="text" name="experience_rate" id="experience_rate" value="" />'
				            +'</td>'
				            +'<td>'
				            +'</td>'
						+'</tr>');
			      datepickerClickNoDay();
    });
    $("#remove_contacts_row").on('click', function(){
    	$("#contacts_container tr:last-child").remove(); //or like this
    });


	$(function(){
		datepickerClick();
	});

	function datepickerClick(){
	    $(".datepicker").datepicker({ 
		format: "MM d, yyyy",
		orientation: "top left",
		autoclose: true,
		startView: 2,
		todayHighlight: true,
		todayBtn: "linked"
		})
   	}

   	function datepickerClickNoDay(){
	    $(".datepicker").datepicker({ 
		format: "MM-yyyy",
		orientation: "top left",
		autoclose: true,		
	    startView: "months", 
	    minViewMode: "months",
		todayHighlight: true,
		todayBtn: "linked"
		})
   	}
</script>
<script>

    // function callReport(reportId)
    // {
    //   var url = $("#"+reportId).data('url');
    //   var id  = $("#employee_id").val(); 
    //   // var curriculum_id = $("#curriculum_id").val();

    //   url = url +"?id="+id;
      
    //   window.open(url);
    // }

     // $(function() {
     //      $("#classification_id").change(function(){
     //        selectListChange('curriculum_id','{{{URL::to("curriculum_subject/dataJsonCurriculum")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Curriculum')
     //      });

     //  });

</script>

