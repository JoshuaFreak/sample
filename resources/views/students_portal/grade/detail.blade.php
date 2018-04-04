<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
		table{font-size: 12px}
	  	i {  font-size:11px; }
		table{color:#305593;}
		table.table tr:nth-child(even){background-color: #F1F5F6}
		table.table td {border: 1px solid #B6CADC; }
		.data {color:black; text-transform: uppercase;}
		.data-center {color:black; text-align: center; text-transform: uppercase;}
		body {
			font-family: Helvetica;
		}
	</style>
	</style>
</head>
<body>
	<table align="center">
		<tr>
			<td align="center"><img src="{{{ asset('assets/site/images/logo.png') }}}" width="120px"/></td>
			<td width="10px"></td>
			<td align="center">
			<b style="font-size:20">GAKKOU SYSTEM</b><br>
				Gorordo Avenue, Cebu City, Cebu 6000<br>
				<i style="font-size:10">Tel No. (032) 340-1867&nbsp;&nbsp;&nbsp;https://www.gakkou.edu.ph</i>
			</td>
			<td width="100px">&nbsp;</td>
		</tr>
	</table><br/>
	<table align="center">
		<tr align="center">
			<td><b>REPORT CARD</b></td>
		</tr>
		<tr align="center">
			<td><b>{{{ $term->term_name}}}</b></td>
		</tr>
	</table>
	<br/>
	<table>
		<tr>
			<td><b style="font-size:10px">BASIC INFORMATION</b></td>
		</tr>
	</table>
	<table frame="box" width="100%">
		<tr>
			<td width="20%"><b>STUDENT NO:</b></td>
			<td class="data">{{{ $student->student_no }}}</td>
			<td width="10%"><b>LEVEL:</b></td>
			<td class="data">{{{ $classification->classification_name}}} - {{{ $classification_level->level}}}</td>
			<td rowspan="5" width="15%"></td>
		</tr>
		<tr>
			<td><b>COMPLETE NAME:</b></td>
			<td class="data">{{{ $student-> last_name }}}, {{{ $student-> first_name }}} {{{ $student-> middle_name }}}</td>
			<td><b>SUPERVISOR:</b></td>
			<td class="data"></td>
		</tr>
		<tr>
			<td><b>PLACE OF BIRTH:</b></td>
			<td class="data">{{{ $student-> birth_place}}}</td>
			<td><b>RELIGION:</b></td>
			<td class="data">{{{ $student-> religion_name}}}</td>
		</tr>
		<tr>
			<td><b>DATE OF BIRTH:</b></td>
			<td class="data">{{ date("M d, Y ",strtotime($student-> birthdate)) }}</td>
			<td><b>NATIONALITY:</b></td>
			<td class="data">{{{ $student-> citizenship_name}}}</td>
		</tr>
		<tr>
			<td><b>GENDER:</b></td>
			<td class="data">{{{ $student-> gender_name}}}</td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<br/>
	<table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
		<tr style="background-color: #B6CADC;" align="center">
			<td><b>LEARNING AREAS</b></td>
			@foreach($grading_period_list as $grading_period)
				<td width="50px"><b>&nbsp;{{{substr($grading_period->grading_period_name, 0,1)}}}&nbsp;</b></td>
			@endforeach
			<td width="50px" style="font-size:9px"><b>FINAL<BR/>RATINGS</b></td>
			<td width="50px"><b>UNITS</b></td>
			<td><b>REMARKS</b></td>
		</tr>
		{{--*/ $general_average = 0 /*--}}
		{{--*/ $general_average_count = 0 /*--}}
		@foreach($curriculum_subject_list as $curriculum_subject)
		<tr align="center">
			<td align="left">&nbsp;{{{ $curriculum_subject->name }}}</td>
			{{--*/ $sub_grade = 0 /*--}}
			{{--*/ $sub_grade_count = 0 /*--}}
			{{--*/ $final_grade = 0 /*--}}
			{{--*/ $count = 0 /*--}}
			@foreach($grade_list as $grade)
				@foreach($grading_period_list as $grading_period)
					@if($grade->subject_id == $curriculum_subject->subject_id && $grade->grading_period_id == $grading_period->id)
						@if($grade->computed_grade == 0)
						<td class="data-center"></td>
						@else
						<td class="data-center">{{{ number_format($grade->computed_grade, 0) }}}</td>
						{{--*/ $sub_grade = $sub_grade + $grade->computed_grade /*--}}
						{{--*/ $sub_grade_count++ /*--}}
						@endif
						{{--*/ $count++ /*--}}
					@endif
				@endforeach
			@endforeach
			@if($count == null)
				@foreach($grading_period_list as $grading_period)
					<td></td>
				@endforeach
			@endif
			@if($sub_grade_count != null && $sub_grade_count >= 4)
			{{--*/ $final_grade = $sub_grade / $sub_grade_count /*--}}
			<td class="data-center">{{{ number_format($final_grade, 2) }}}</td>
			{{--*/ $general_average = $general_average + $final_grade /*--}}
			{{--*/ $general_average_count++ /*--}}
			@else
			<td class="data-center"></td>
			@endif
			<td></td>
			<td></td>
		</tr>
		@endforeach
		<tr align="center">
			<td align="left">&nbsp;MAPEH</td>
			{{--*/ $music_grade = 0 /*--}}
			{{--*/ $art_grade = 0 /*--}}
			{{--*/ $pe_grade = 0 /*--}}
			{{--*/ $health_grade = 0 /*--}}
			{{--*/ $mapeh_sub_grade = 0 /*--}}
			{{--*/ $mapeh_sub_grade_count = 0 /*--}}
			{{--*/ $mapeh_final_grade = 0 /*--}}
			@foreach($grading_period_list as $grading_period)
			{{--*/ $count = 0 /*--}}
				@foreach($grade_list as $grade)
					@if($grade->grading_period_id == $grading_period->id)
						@if($grade->name == 'Music')
						{{--*/ $music_grade = $grade->computed_grade /*--}}
							@if($music_grade != 0)
							{{--*/ $count++ /*--}}
							@endif
						@endif
						@if($grade->name == 'Arts')
						{{--*/ $art_grade = $grade->computed_grade /*--}}
							@if($art_grade != 0)
							{{--*/ $count++ /*--}}
							@endif
						@endif
						@if($grade->name == 'Physical Education')
						{{--*/ $pe_grade = $grade->computed_grade /*--}}
							@if($pe_grade != 0)
							{{--*/ $count++ /*--}}
							@endif
						@endif
						@if($grade->name == 'Health')
						{{--*/ $health_grade = $grade->computed_grade /*--}}
							@if($health_grade != 0)
							{{--*/ $count++ /*--}}
							@endif
						@endif
					@endif
				@endforeach
				@if($count != null)
				{{--*/ $mapeh_grade = ($music_grade + $art_grade + $pe_grade + $health_grade) / $count /*--}}
					@if($mapeh_grade != null)
					<td class="data-center">{{{ number_format($mapeh_grade, 0) }}}</td>
					{{--*/ $mapeh_sub_grade = $mapeh_sub_grade + $mapeh_grade /*--}}
					{{--*/ $mapeh_sub_grade_count++ /*--}}
					@else
					<td class="data-center"></td>
					@endif
				@else
					<td class="data-center"></td>
				@endif
			@endforeach
			@if($mapeh_sub_grade_count != null && $mapeh_sub_grade_count >= 4)
			{{--*/ $mapeh_final_grade = $mapeh_sub_grade / $mapeh_sub_grade_count /*--}}
				<td class="data-center">{{{ number_format($mapeh_final_grade, 2) }}}</td>
			{{--*/ $general_average = $general_average + $mapeh_final_grade /*--}}
			{{--*/ $general_average_count++ /*--}}
			@else
			<td class="data-center"></td>
			@endif
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr align="center">
			<td align="left">&nbsp;GENERAL AVERAGE</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			@if($general_average != null && $general_average_count != null)
			{{--*/ $general_average_total = $general_average / $general_average_count /*--}}
			<td class="data-center">{{{ number_format($general_average_total, 2) }}}</td>
			@else
			<td class="data-center"></td>
			@endif
			<td></td>
			<td></td>
		</tr>
	</table>
    <div  class="col-md-12">
	    <div  class="col-md-8">
			<table align="center">
				<tr>
					<td><b>ATTENDANCE RECORD</b></td>
				</tr>
			</table>
	        <table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
				<tr style="background-color: #B6CADC; font-size:9px" align="center">
					<td>NO. OF SCHOOL DAYS</td>
					<td>NO. OF SCHOOL DAYS ABSENT</td>
					<td>CAUSE</td>
					<td>NO. OF SCHOOL DAYS TARDY</td>
					<td>CAUSE</td>
					<td>NO. OF SCHOOL DAYS PRESENT</td>
				</tr>
				{{--*/ $attendance_count = 0 /*--}}
				{{--*/ $absent_count = 0 /*--}}
				{{--*/ $tardy_count = 0 /*--}}
				{{--*/ $present_count = 0 /*--}}
				@foreach($student_attendance_list as $student_attendance)
					{{--*/ $attendance_count++ /*--}}
					@if($student_attendance->attendance_remark_id == 2)
					{{--*/ $absent_count++ /*--}}
					@endif
					@if($student_attendance->attendance_remark_id == 5)
					{{--*/ $tardy_count++ /*--}}
					@endif
					@if($student_attendance->attendance_remark_id == 1)
					{{--*/ $present_count++ /*--}}
					@endif
				@endforeach
				<tr class="data-center">
					<td>{{{ $attendance_count }}}</td>
					<td>{{{ $absent_count }}}</td>
					<td>&nbsp;-&nbsp;</td>
					<td>{{{ $tardy_count }}}</td>
					<td>&nbsp;-&nbsp;</td>
					<td>{{{ $present_count }}}</td>
				</tr>
			</table>
		</div>
	</div>
    <div class="col-md-12">
	    <div class="col-md-8">
			<br/><br/>
			<table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
				<tr style="background-color: #B6CADC; font-size:10px" align="center">
					<td><b>ELIGIBLE FOR TRANSFER AND ADMISSION TO</b></td>
				</tr>
				<tr align="center">
					<td class="data"><b>&nbsp;</b></td>
				</tr>
			</table><br/><br/><br/><br/><br/><br/>
		</div>
	</div>
    <div class="col-md-12">
		<table align="left" width="50%">
			<tr>
				<td class="data-center"><span><u>{{{ $section_monitor-> first_name}}} {{{ $section_monitor-> middle_name}}} {{{ $section_monitor-> last_name}}}</u></span></td>
			</tr>
			<tr>
				<td align="center"><span>Adviser</span></td>
			</tr>
			<tr><td>&nbsp;&nbsp;&nbsp;<br></td></tr>
			<tr>
				<td class="data-center"><span><u>&nbsp;________________&nbsp;</u></span></td>
			</tr>
			<tr>
				<td align="center"><span>Principal</span></td>
			</tr>
		</table>
		<table align="center" width="50%">
			<tr>
				<td class="data-center"><span><u>&nbsp;MR/MS {{{ $student-> last_name}}}&nbsp;</u></span></td>
			</tr>
			<tr>
				<td align="center"><span>Parent/Guardian</span></td>
			</tr>
			<tr><td>&nbsp;&nbsp;&nbsp;<br></td></tr>
			<tr>
				<td class="data-center"><span><u>&nbsp;{{ date("F d, Y") }}&nbsp;</u></span></td>
			</tr>
			<tr>
				<td align="center"><span>Date</span></td>
			</tr>
		</table><br/><br/>
    </div>
</body>
</html>