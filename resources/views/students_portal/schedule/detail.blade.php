<style type="text/css">
	table{color:#305593;}
	tr:nth-child(even){background-color: #F1F5F6}
	th, td {border: 1px solid #B6CADC; }
</style>
<table frame="void" border="1" cellpadding="0px" cellspacing="0px" width="80%">
	<tr align="center" style="background-color: #B6CADC;">
		<td><b>CODE</b></td>
		<td><b>SUBJECT</b></td>
		<td><b>CREDIT UNIT</b></td>
		<td colspan="3"><b>SCHEDULE</b></td>
	</tr>
	{{--*/ $total_units = 0 /*--}}
	{{--*/ $count = 0 /*--}}
	@foreach($curriculum_subject_list as $curriculum_subject)
	{{--*/ $total_units = $total_units + $curriculum_subject->credit_unit /*--}}
	<tr align="center">
		<td>{{{ $curriculum_subject->code }}}</td>
		<td align="left">&nbsp;{{{ $curriculum_subject->name }}}</td>
		<td>{{{ $curriculum_subject->credit_unit }}}</td>
		@foreach($schedule_list as $schedule)
			@if($schedule->subject_offered_id == $curriculum_subject->subject_offered_id)
			<td>{{{ $schedule->time_start."".$schedule->session_start." - ".$schedule->time_end."".$schedule->session_end }}}</td>
			<td>{{{ $schedule->room_name }}}</td>
			<td>
				@foreach($day_list as $day)
					@if($day->subject_offered_id == $curriculum_subject->subject_offered_id)
						{{{ $day->day_code }}}
					@endif
				@endforeach
			</td>
			{{--*/ $count++ /*--}}
			@endif
		@endforeach
		@if($count == 0)
			<td></td>
			<td></td>
			<td></td>
		@endif
	</tr>
	@endforeach
	<tr>
		<td align="right" colspan="2"><b>Total Units&nbsp;</b></td>
		<td align="center"><b>{{{ $total_units }}}</b></td>
		<td colspan="3"></td>
	</tr>
</table>
<div class="col-md-12">
<br/><br/><br/><br/><br/><br/>
</div>