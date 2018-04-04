<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
		body {
			font-family: 'Myriad Pro', 'Trebuchet MS', sans-serif;
			font-size:13px;
		}
		@font-face{
			font-family: 'Roboto';
		}
		td{
			padding-left:8px;
		}
	</style>
</head>
<body>
	<div style="width: ;">

		<table width="100%">
			<tr>
				<td width="100">
					@if($img)
					  	<img class="img-circle" id="myImg" src="{{asset($img -> img)}}" alt="image" width="150px" height="150px" data-toggle="modal" data-target="#photo_modal" />
					@else
					  	<img class="img-circle" id="myImg" src="{{asset('assets/site/images/BLANK_IMAGE.jpg')}}" alt="image" width="150px" height="150px" data-toggle="modal" data-target="#photo_modal"/>
					@endif
				</td>
				<td width="70%" align="center">
					<table align="center">
						<tr>
							<td style="font-size:20px;" align="center"><strong>{{ $person->first_name." ".substr($person->middle_name,0,1).". ".$person->last_name }}</strong></td>
						</tr>
						<tr>
							<td align="center">Contact #.: {{ $person->contact_no }}</td>
						</tr>
						<tr>
							<td align="center">Address: {{ $person->address }}</td>
						</tr>
						<tr>
							<td align="center">Date of Birth: {{ date("M d, Y",strtotime($person->birthdate)) }}</td>
						</tr>
						<tr>
							<td align="center">Civil Status: @foreach($civil_status_list as $civil_status)
									@if($civil_status->id == $person->civil_status_id)
										{{ $civil_status->civil_status_name }}
									@endif
								@endforeach
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>

		@if($_GET['educational_background'] != 0)
		<div style="background-color:#428BCA;height:40px;color:#fff;">
			<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>Educational Background</strong></p>
		</div>
		
			<div>
				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;">
						</td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>School Name</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Years Attendend</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Honors Received</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Course Graduated</strong></td>
					</tr>
					<tbody>
						@foreach($employee_classification_list as $employee_classification)

			        		<?php $person_education_created = false; ?>

			        		@foreach($person_education_list as $person_education)
			        			@if($person_education->employee_classification_id == $employee_classification->id)
								<?php $person_education_created = true; ?>
			        			<tr>
			        				<td style="border: 1px solid #A9A9A9;"><p>{{$employee_classification->classification_name}}</p></td>
			        				<td style="border: 1px solid #A9A9A9;">{{$person_education->school_name}}</td>
			        				<td style="border: 1px solid #A9A9A9;">{{{ $person_education->years_attended }}}</td>
			        				<td style="border: 1px solid #A9A9A9;">{{{ $person_education->honors_received }}}</td>
			        				<td style="border: 1px solid #A9A9A9;">{{{ $person_education->course_graduated }}}</td>
			        			</tr>
			        			@endif
			        		@endforeach

			        		@if($person_education_created == false)
			        			<tr>
			        				<td class="td_label" align="center">{{$employee_classification->classification_name}}</td>
			        				<td align="center">
			        					<input type="hidden" name="person_education_{{{$employee_classification->id}}}" value="">
			        					<label class="control-label" name="school_name_{{$employee_classification->id}}" id="school_name_{{$employee_classification->id}}"></label>
			        					<input type="hidden" name="employee_classification_{{{$employee_classification->id}}}" id="employee_classification_{{{$employee_classification->id}}}" value="{{{$employee_classification->id}}}">
			        				</td align="center">
			        				<td align="center">
			        					<p name="years_attended" id="years_attended_{{$employee_classification->id}}"></p>
			        				</td>
			        				<td align="center">
			        					<p name="honors_received" id="honors_received_{{$employee_classification->id}}">
			        					</p>
			        				</td>
			        				<td align="center">
			        					<p name="course_graduated" id="course_graduated_{{$employee_classification->id}}"></p>
			        				</td>
			        			</tr>
			        		@endif
			        	@endforeach
			        </tbody>
				</table>
			</div>
			<p></p>
			@endif

			@if($_GET['experience'] != 0)
			<div style="background-color:#428BCA;height:40px;color:#fff;">
				<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>Employee Working Experience</strong></p>
			</div>
			<div>
				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Company Name</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Position</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Date From</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Date To</strong></td>
					</tr>
					<tbody>
					@foreach($employee_working_experience_list as $employee_working_experience)
						<tr>
							<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_working_experience->company_name }}}</p></td>
							<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_working_experience->position }}}</p></td>
							<td style="border: 1px solid #A9A9A9;"><p>{{{ date("M, Y",strtotime($employee_working_experience->date_employed_from)) }}}</p></td>
							<td style="border: 1px solid #A9A9A9;"><p>{{{ date("M, Y",strtotime($employee_working_experience->date_employed_to)) }}}</p></td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<p></p>
			@endif

			@if($_GET['position'] != 0)
			<div style="background-color:#428BCA;height:40px;color:#fff;">
				<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>Position/s Acquired in CIA</strong></p>
			</div>
			<div>
				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Position</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Employment Status</strong></td>
					</tr>
					<tr>
						@foreach($position_list as $position)
							@if($position->id == $employee->position_id)
							<td style="border: 1px solid #A9A9A9;"><p>{{{ $position->position_name }}}</p></td>
							@endif
						@endforeach
						@foreach($employment_status_list as $employment_status)
							@if($employment_status->id == $employee->employment_status_id)
							<td style="border: 1px solid #A9A9A9;"><p>{{{ $employment_status->employment_status_name }}}</p></td>
							@endif
						@endforeach
					</tr>
					<tbody id="position_div">
					</tbody>
				</table>
			</div>
			<p></p>
			@endif

			@if($_GET['seminar_attended'] != 0)
			<div style="background-color:#428BCA;height:40px;color:#fff;">
				<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>Seminar's Attendend</strong></p>
			</div>

			<div>
				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Date</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Title of Training/Seminar</strong></td>
					<!-- 	<td style="border: 1px solid #A9A9A9;">Venue</td>
						<td style="border: 1px solid #A9A9A9;">Remarks</td> -->
					</tr>
					@foreach($person_seminar_list as $person_seminar)
			    		@if($person_seminar -> is_cia == 0)
							<tr>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ date('M d,Y',strtotime($person_seminar->seminar_date)) }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $person_seminar->seminar_name }}}</p></td>
						<!-- 		<td style="border: 1px solid #A9A9A9;"><p>{{{ $person_seminar->seminar_venue }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $person_seminar->remark }}}</p></td> -->
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<p></p>
			@endif

			@if($_GET['is_cia_seminar_attended'] != 0)
			<div style="background-color:#428BCA;height:40px;color:#fff;">
				<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>CIA Seminar's Attendend</strong></p>
			</div>

			<div>
				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Date</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Title of Training/Seminar</strong></td>
						<!-- <td style="border: 1px solid #A9A9A9;">Venue</td>
						<td style="border: 1px solid #A9A9A9;">Remarks</td> -->
					</tr>
					@foreach($person_seminar_list as $person_seminar)
			    		@if($person_seminar -> is_cia == 1)
							<tr>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ date('M d,Y',strtotime($person_seminar->seminar_date)) }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $person_seminar->seminar_name }}}</p></td>
								<!-- <td style="border: 1px solid #A9A9A9;"><p>{{{ $person_seminar->seminar_venue }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $person_seminar->remark }}}</p></td> -->
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<p></p>
			@endif

			@if($_GET['certificate'] != 0)
			<div style="background-color:#428BCA;height:40px;color:#fff;">
				<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>Certificates</strong></p>
			</div>

			<div>
				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Description</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Date</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Award By</strong></td> 
					</tr>
					@foreach($employee_certificate_list as $employee_certificate)
			    		@if($employee_certificate -> is_cia == 0)
							<tr>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_certificate->description }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ date('M d,Y',strtotime($employee_certificate->date)) }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_certificate->award_by }}}</p></td>
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<p></p>
			@endif

			@if($_GET['is_cia_certificate'] != 0)
			<div style="background-color:#428BCA;height:40px;color:#fff;">
				<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>CIA Certificates</strong></p>
			</div>

			<div>
				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Description</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Date</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Award By</strong></td>
					</tr>
					@foreach($employee_certificate_list as $employee_certificate)
			    		@if($employee_certificate -> is_cia == 1)
							<tr>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_certificate->description }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ date('M d,Y',strtotime($employee_certificate->date)) }}}</p></td>
								<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_certificate->award_by }}}</p></td>
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<p></p>
			@endif

			@if($_GET['government'] != 0)
			<div style="background-color:#428BCA;height:40px;color:#fff;">
				<p style="font-size:15px;margin-top: 9px;padding-left: 10px;padding-top:3px;padding-bottom:5px;"><strong>Government Contribution Numbers</strong></p>
			</div>

			<div>

				<table class="table" width="100%" style="border-collapse: collapse;">
					<tr>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Government Department</strong></td>
						<td style="border: 1px solid #A9A9A9;padding-top:8px;padding-bottom:8px;"><strong>Contribution Number</strong></td>
					</tr>
					@foreach($employee_government_contribution_list as $employee_government_contribution)
						<tr>
							<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_government_contribution->government_department }}}</p></td>
							<td style="border: 1px solid #A9A9A9;"><p>{{{ $employee_government_contribution->employee_contribution_number }}}</p></td>
						</tr>
					@endforeach
				</table>
			</div>
			@endif
		
	</div>
</body>

</html>

