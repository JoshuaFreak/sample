<style type="text/css">
	table{color:#305593;}
	tr:nth-child(even){background-color: #F1F5F6}
	th, td {border: 1px solid #B6CADC; }
</style>
<table frame="void" border="1" cellpadding="3px" cellspacing="0px" width="80%">	
@foreach($curriculum_subject_list as $curriculum_subject)
	<tr style="background-color: #B6CADC;">
		<td colspan="3">
			<b>&nbsp;&nbsp;&nbsp;Classification Level: </b>
			<span>{{$curriculum_subject->level}}</span>
		</td>
	</tr>
	<tr align="center" >
		<td><b>SUBJECT CODE</b></td>
		<td><b>SUBJECT NAME</b></td>
		<td><b>CREDIT UNITS</b></td>
	</tr>
	{{--*/ $total_unit = 0 /*--}}
	@foreach($subject_list as $subject)
		@if($classification_id == 5)
			@if($subject -> semester_level_id == $curriculum_subject->semester_level_id)
				<tr align="center">
					<td>{{$subject->code}}</td>
					<td>{{$subject->name}}</td>
					<td>{{$subject->credit_unit}}</td>
				</tr>
				{{--*/ $total_unit = $total_unit + $subject->credit_unit /*--}}
			@endif
		@else
			@if($subject -> classification_level_id == $curriculum_subject->classification_level_id)
				<tr align="center">
					<td>{{$subject->code}}</td>
					<td align="left">&nbsp;&nbsp;&nbsp;{{$subject->name}}</td>
					<td>{{$subject->credit_unit}}</td>
				</tr>
				{{--*/ $total_unit = $total_unit + $subject->credit_unit /*--}}
			@endif
		@endif
	@endforeach
	<tr>
		<td align="right" colspan="2"><b>Total Unit&nbsp;</b></td>
		<td align="center"><b>{{$total_unit}}</b></td>
	</tr>
@endforeach
</table><br/>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>