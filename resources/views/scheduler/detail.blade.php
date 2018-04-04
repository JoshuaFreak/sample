<hr>
<table border="1" cellpadding="0px" cellspacing="0px">
	<tr>
		<td align="center" style="background-color: #999999;"><b>Code</b></td>
		<td align="center" style="background-color: #999999;"><b>Subject</b></td>
		<td align="center" style="background-color: #999999;"><b>Credit Unit</b></td>
		<td align="center" style="background-color: #999999;" colspan="3"><b>Shedule</b></td>
	</tr>
	{{--*/ $total_units = 0 /*--}}
	@foreach($curriculum_subject_list as $curriculum_subject)
	{{--*/ $total_units = $total_units + $curriculum_subject->credit_unit /*--}}
	<tr>
		<td align="center" width="200px">{{{ $curriculum_subject->code }}}</td>
		<td width="300px">&nbsp;{{{ $curriculum_subject->name }}}</td>
		<td align="center" width="100px">{{{ $curriculum_subject->credit_unit }}}</td>
		<td align="center" width="150px"><i>Time</i></td>
		<td align="center" width="100px"><i>Room</i></td>
		<td align="center" width="100px"><i>Day</i></td>
	</tr>
		@foreach($schedule_list as $schedule)
			@if($schedule->subject_offered_id == $curriculum_subject->subject_offered_id)
			<tr>
				<td colspan="3"></td>
				<td align="center">{{{ $schedule->time_start."".$schedule->session_start." - ".$schedule->time_end."".$schedule->session_end }}}</td>
				<td align="center">{{{ $schedule->room_name }}}</td>
				<td align="center">
					@foreach($day_list as $day)
						@if(($day->time_start == $schedule->time_start_id) && ($day->time_end == $schedule->time_end_id) && ($day->room_id == $schedule->room_id))
							{{{ $day->day_code }}}
						@endif
					@endforeach

				</td>
			</tr>
			@endif
		@endforeach
	@endforeach
	<tr>
		<td align="right" colspan="2" style="background-color: #999999;"><b>Total Units&nbsp;</b></td>
		<td align="center" style="background-color: #999999;"><b>{{{ $total_units }}}</b></td>
		<td colspan="3" style="background-color: #999999;"></td>
	</tr>
</table>
<div class="col-md-12">
<br/><br/><br/><br/><br/><br/>
</div>