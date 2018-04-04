<style type="text/css">
	table{color:#305593; text-align: center;}
	td.data{color:#333333; background-color: #F0FF32}
	td.subject_data{text-transform: uppercase;}
	th, td {border: 1px solid #B6CADC; }
</style>
<table border="1" cellpadding="3px" cellspacing="0px" width="80%">	

@foreach($pace_classification_list as $pace_classification)
	<tr style="background-color: #F1F5F6;">
		<td class="subject_data" align="left">&nbsp;<b>{{$pace_classification->level}}</b></td>
	</tr>
	@foreach($subject_pace_list as $subject_pace)
		<tr>
			@if($subject_pace->classification_level_id == $pace_classification->classification_level_id)
				<td>&nbsp;<b></b></td>
				<td>&nbsp;<b>{{$subject_pace->name}}</b></td>
				@foreach($pace_list as $pace)
					
					@if($pace->classification_level_id == $subject_pace->classification_level_id && 
						$pace->subject_id == $subject_pace->subject_id)

						@foreach($required_pace_list as $required_pace)
							@if($required_pace->required_pace == $pace->pace_name && 
								$required_pace->grading_period_id == $pace->grading_period_id && 
								$required_pace->classification_level_id == $pace->classification_level_id && 
								$required_pace->subject_id == $subject_pace->subject_id)

								<td class="data">&nbsp;<b>{{$required_pace->required_pace}}</b></td>

							@else

								<!-- <td>&nbsp;<b>{{$pace->pace_name}}</b></td> -->

							@endif

						@endforeach

					@endif

				@endforeach
			@endif
		</tr>
	@endforeach
@endforeach

</table><br/>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>