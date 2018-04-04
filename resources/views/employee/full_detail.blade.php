@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
Employee Full Detail :: @parent
@stop
{{-- Content --}}
@section('content')
<style type="text/css">
.img-circle {
    border-radius: 50%;
}
.td_label{
	background-color: #D9E2E1;
	width: 20%;
}
.td_label_1{
	background-color: #fff;
	width: 20%;
}
.td_label_2{
	background-color: #428BCA;
	width: 20%;
	color: #fff;
}
</style>
<div class="form-groupcol-md-12" align="right" style="background-color: #fff;margin-bottom: 0px;padding-right: 20px;padding-top: 20px;">
	<!-- <a href="{{ URL::to('employee/employee201Pdf?id=').$_GET['employee_id'] }}" target="_blank"><button class="btn btn-primary" id="print" value="detailDiv" onclick="PrintElem('#detailDiv')">Print 201</button></a> -->
</div>
<div id="detailDiv" class="form-group col-md-12" style="background-color: #fff;padding-top: 30px;">
<center>
	<input type="hidden" id="employee_id" value="{{$_GET['employee_id']}}"></input>

	<div style="width: 1200px;" align="center">
		<div style="width:300px;background-color: #fff;padding-top: 20px;padding-bottom: 20px;float: left;">
			<div class="col-md-12" style="margin-bottom: 20px">
				@if($img)
				  	<img class="img-circle" id="myImg" src="{{asset($img -> img)}}" alt="image" width="150px" height="150px" data-toggle="modal" data-target="#photo_modal" />
				@else
				  	<img class="img-circle" id="myImg" src="{{asset('assets/site/images/BLANK_IMAGE.jpg')}}" alt="image" width="150px" height="150px" data-toggle="modal" data-target="#photo_modal"/>
				@endif
			</div>
			<div style="width:300px;height:190px;background-color: #D9E2E1;padding-top: 20px;padding-bottom: 20px;float: left">
				<p><span class="fa fa-user"></span> {{ $person->first_name." ".substr($person->middle_name,0,1).". ".$person->last_name }}</p>
				<p><span class="fa fa-phone"></span> {{ $person->contact_no }}</p>
				<p><span class="fa fa-map-marker"></span> {{ $person->address }}</p>
				<p><span class="fa fa-calendar"></span> {{ $person->birthdate }}</p>
				@foreach($civil_status_list as $civil_status)
					@if($civil_status->id == $person->civil_status_id)
						<p><span class="fa fa-male"></span> {{ $civil_status->civil_status_name }}</p>
					@endif
				@endforeach
			</div>
		</div>
		<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 20px;padding-bottom: 20px;float: left">
			<div class="col-md-12" style="height:320px;">
				<h4 style="float:left;padding-left: 20px;"><span class="fa fa-graduation-cap" style="color:#933132"></span> Educational Background</h4><button class="pull-right btn btn-default" data-toggle="modal" data-target="#select_category"><i class="fa fa-print"></i> Print</button>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="right">
						</td>
						<td class="td_label_2" align="center">
							<p>School Name</p>
						</td>
						<td class="td_label_2" align="center">
							<p>Years Attendend</p>
						</td>
						<td class="td_label_2" align="center">
							<p>Honors Received</p>
						</td>
						<td class="td_label_2" align="center">
							<p>Course Graduated</p>
						</td>
					</tr>
					<tbody>
						@foreach($employee_classification_list as $employee_classification)

			        		<?php $person_education_created = false; ?>

			        		@foreach($person_education_list as $person_education)
			        			@if($person_education->employee_classification_id == $employee_classification->id)
								<?php $person_education_created = true; ?>
			        			<tr>
			        				<td class="td_label" align="center"><input type="hidden" id="{{{$employee_classification->id}}}" />
				        				<p>{{$employee_classification->classification_name}}</p>
			        				</td>
			        				<td class="td_label_1" align="center">
			        					<input type="hidden" name="person_education_{{{$employee_classification->id}}}" id="person_education_{{{$employee_classification->id}}}" value="{{{$person_education->id}}}">
			        					<input type="hidden" name="employee_classification_{{{$employee_classification->id}}}" id="employee_classification_{{{$employee_classification->id}}}" value="{{{$employee_classification->id}}}">
			        					<p name="school_name_{{$employee_classification->id}}" id="school_name_{{$employee_classification->id}}">{{$person_education->school_name}}</p>
			        				</td>
			        				<td class="td_label_1" align="center">
			        					<p name="years_attended" id="years_attended_{{$employee_classification->id}}"> {{{ $person_education->years_attended }}}</p>
			        				</td>
			        				<td class="td_label_1" align="center">
			        					<p name="honors_received" id="honors_received_{{$employee_classification->id}}"> {{{ $person_education->honors_received }}} </p>
			        				</td>
			        				<td class="td_label_1" align="center">
			        					<p name="course_graduated" id="course_graduated_{{$employee_classification->id}}">
			        					{{{ $person_education->course_graduated }}} </p>
			        				</td>
			        			</tr>
			        			@endif
			        		@endforeach

			        		@if($person_education_created == false)
			        			<tr>
			        				<td class="td_label" align="center">{{$employee_classification->classification_name}}</td>
			        				<td class="td_label_1" align="center">
			        					<input type="hidden" name="person_education_{{{$employee_classification->id}}}" value="">
			        					<label class="control-label" name="school_name_{{$employee_classification->id}}" id="school_name_{{$employee_classification->id}}"></label>
			        					<input type="hidden" name="employee_classification_{{{$employee_classification->id}}}" id="employee_classification_{{{$employee_classification->id}}}" value="{{{$employee_classification->id}}}">
			        				</td class="td_label_1" align="center">
			        				<td class="td_label_1" align="center">
			        					<p name="years_attended" id="years_attended_{{$employee_classification->id}}"></p>
			        				</td>
			        				<td class="td_label_1" align="center">
			        					<p name="honors_received" id="honors_received_{{$employee_classification->id}}">
			        					</p>
			        				</td>
			        				<td class="td_label_1" align="center">
			        					<p name="course_graduated" id="course_graduated_{{$employee_classification->id}}"></p>
			        				</td>
			        			</tr>
			        		@endif
			        	@endforeach
			        </tbody>
				</table>
			</div>
			<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 10px;margin-bottom: -20px;float: left">
				<h4 style="float:left;padding-left: 20px"><span class="fa fa-align-justify" style="color:#933132"></span> Employee Working Experience</h4>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="center">Company Name</td>
						<td class="td_label_2" align="center">Position</td>
						<td class="td_label_2" align="center">Date From</td>
						<td class="td_label_2" align="center">Date To</td>
					</tr>
					<tbody>
					@foreach($employee_working_experience_list as $employee_working_experience)
						<tr>
							<td class="td_label_1" align="center"><p>{{{ $employee_working_experience->company_name }}}</p></td>
							<td class="td_label_1" align="center"><p>{{{ $employee_working_experience->position }}}</p></td>
							<td class="td_label_1" align="center"><p>{{{ date("M, Y",strtotime($employee_working_experience->date_employed_from)) }}}</p></td>
							<td class="td_label_1" align="center"><p>{{{ date("M, Y",strtotime($employee_working_experience->date_employed_to)) }}}</p></td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 10px;margin-bottom: -42px;float: left">
				<h4 style="float:left;padding-left: 20px"><span class="fa fa-align-justify" style="color:#933132"></span> Position/s Acquired in CIA</h4>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="center">Position</td>
						<td class="td_label_2" align="center">Employment Status</td>
					</tr>
					<tr>
						@foreach($position_list as $position)
							@if($position->id == $employee->position_id)
							<td class="td_label_1" align="center"><p>{{{ $position->position_name }}}</p></td>
							@endif
						@endforeach
						@foreach($employment_status_list as $employment_status)
							@if($employment_status->id == $employee->employment_status_id)
							<td class="td_label_1" align="center"><p>{{{ $employment_status->employment_status_name }}}</p></td>
							@endif
						@endforeach
					</tr>
					<tbody id="position_div">
					</tbody>
				</table>
			</div>
			<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 10px;margin-bottom: -20px;float: left">
				<h4 style="float:left;padding-left: 20px"><span class="fa fa-clipboard" style="color:#933132"></span> Seminar's Attendend</h4>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="center">Date</td>
						<td class="td_label_2" align="center">Title of Training/Seminar</td>
					<!-- 	<td class="td_label_2" align="center">Venue</td>
						<td class="td_label_2" align="center">Remarks</td> -->
					</tr>
					@foreach($person_seminar_list as $person_seminar)
			    		@if($person_seminar -> is_cia == 0)
							<tr>
								<td class="td_label_1" align="center"><p>{{{ date('M d,Y',strtotime($person_seminar->seminar_date)) }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ $person_seminar->seminar_name }}}</p></td>
						<!-- 		<td class="td_label_1" align="center"><p>{{{ $person_seminar->seminar_venue }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ $person_seminar->remark }}}</p></td> -->
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 10px;margin-bottom: -20px;float: left">
				<h4 style="float:left;padding-left: 20px"><span class="fa fa-clipboard" style="color:#933132"></span> CIA Seminar's Attendend</h4>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="center">Date</td>
						<td class="td_label_2" align="center">Title of Training/Seminar</td>
						<!-- <td class="td_label_2" align="center">Venue</td>
						<td class="td_label_2" align="center">Remarks</td> -->
					</tr>
					@foreach($person_seminar_list as $person_seminar)
			    		@if($person_seminar -> is_cia == 1)
							<tr>
								<td class="td_label_1" align="center"><p>{{{ date('M d,Y',strtotime($person_seminar->seminar_date)) }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ $person_seminar->seminar_name }}}</p></td>
								<!-- <td class="td_label_1" align="center"><p>{{{ $person_seminar->seminar_venue }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ $person_seminar->remark }}}</p></td> -->
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 10px;margin-bottom: -20px;float: left">
				<h4 style="float:left;padding-left: 20px"><span class="fa fa-certificate" style="color:#933132"></span> Certificates</h4>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="center">Description</td>
						<td class="td_label_2" align="center">Date</td>
						<td class="td_label_2" align="center">Award By</td> 
					</tr>
					@foreach($employee_certificate_list as $employee_certificate)
			    		@if($employee_certificate -> is_cia == 0)
							<tr>
								<td class="td_label_1" align="center"><p>{{{ $employee_certificate->description }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ date('M d,Y',strtotime($employee_certificate->date)) }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ $employee_certificate->award_by }}}</p></td>
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 10px;margin-bottom: -20px;float: left">
				<h4 style="float:left;padding-left: 20px"><span class="fa fa-certificate" style="color:#933132"></span> CIA Certificates</h4>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="center">Description</td>
						<td class="td_label_2" align="center">Date</td>
						<td class="td_label_2" align="center">Award By</td>
					</tr>
					@foreach($employee_certificate_list as $employee_certificate)
			    		@if($employee_certificate -> is_cia == 1)
							<tr>
								<td class="td_label_1" align="center"><p>{{{ $employee_certificate->description }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ date('M d,Y',strtotime($employee_certificate->date)) }}}</p></td>
								<td class="td_label_1" align="center"><p>{{{ $employee_certificate->award_by }}}</p></td>
							</tr>
						@endif
					@endforeach
				</table>
			</div>
			<div class="col-md-12" style="width:900px;background-color: #fff;padding-top: 10px;padding-bottom: 20px;float: left">
				<h4 style="float:left;padding-left: 20px"><span class="fa fa-building" style="color:#933132"></span> Government Contribution Numbers</h4>
				<br><br>
				<table class="table">
					<tr>
						<td class="td_label_2" align="center">Government Department</td>
						<td class="td_label_2" align="center">Contribution Number</td>
					</tr>
					@foreach($employee_government_contribution_list as $employee_government_contribution)
						<tr>
							<td class="td_label_1" align="center"><p>{{{ $employee_government_contribution->government_department }}}</p></td>
							<td class="td_label_1" align="center"><p>{{{ $employee_government_contribution->employee_contribution_number }}}</p></td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
	</div>
</center>
</div>

@include('employee.selection_category_modal')
@stop
{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
$(function(){
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
                	$("#position_div").append('<tr><td class="td_label_1" align="center"><p>'+item.position_name+'</p></td><td class="td_label_1" align="center"><p>'+item.employment_status_name+'</p></td></tr>')
                });
            }  
    });
});

function PrintElem(elem)
{
    // Popup($(elem).html());
    // alert();
    
}

function Popup(data)
{
    var mywindow = window.open('', 'my bill', 'height=700,width=1000');

    /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
    mywindow.document.write('</html><body>');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10

    mywindow.print();
    mywindow.close();

    return true;
}

$(function () {
  $('#checkAll').change(function () {
    if($(this).is(':checked') == true) {
        $('input[type="checkbox"]').attr('checked', '');
    }else{
        $('input[type="checkbox"]').removeAttr('checked', '');
    }
  })
});

$(function() {

  $("#printBtn").click(function(){


      if($('#educational_background').is(':checked') == true){
      	var edu_back = 1;
      }else{
      	var edu_back = 0;
      }

      if($('#experience').is(':checked') == true){
      	var exp = 1;
      }else{
      	var exp = 0;
      }

      if($('#position').is(':checked') == true){
      	var pos = 1;
      }else{
      	var pos = 0;
      }

      if($('#seminar_attended').is(':checked') == true){
      	var sem_att = 1;
      }else{
      	var sem_att = 0;
      }

      if($('#cia_seminar_attended').is(':checked') == true){
      	var cia_sem_att = 1;
      }else{
      	var cia_sem_att = 0;
      }

      if($('#certificate').is(':checked') == true){
      	var cert = 1;
      }else{
      	var cert = 0;
      }

      if($('#cia_certificate').is(':checked') == true){
      	var is_cia_cert = 1;
      }else{
      	var is_cia_cert = 0;
      }

      if($('#government').is(':checked') == true){
      	var gov = 1;
      }else{
      	var gov = 0;
      }

      var employee_id = $('#employee_id').val();
      window.open(
        'printPDF?employee_id='+employee_id
        +'&educational_background='+edu_back
        +'&experience='+exp
        +'&position='+pos
        +'&seminar_attended='+sem_att
        +'&is_cia_seminar_attended='+cia_sem_att
        +'&certificate='+cert
        +'&is_cia_certificate='+is_cia_cert
        +'&government='+gov,
        "_blank" // <- This is what makes it open in a new window.
      );
  });
});

</script>
@stop