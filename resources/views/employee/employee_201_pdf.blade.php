<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
		.img-circle {
		    border-radius: 50%;
		}
	</style>
</head>
<body>
<table style="width: 700px;">
<tr style="background-color: #D9E2E1">
	<td style="width: 50px">
	@if($img != 0)
		<img src="{{ asset('.$img.') }}"/>
	@else
		<img class="img-circle" src="{{ asset('assets/site/images/BLANK_IMAGE.jpg') }}" width="150px" height="150px">
	@endif
	</td>
	<td align="center">
			<span class="fa fa-user"></span> {{ $person->first_name." ".substr($person->middle_name,0,1).". ".$person->last_name }}<br/>
				<span class="fa fa-phone"></span> {{ $person->contact_no }}<br/>
				<span class="fa fa-map-marker"></span> {{ $person->address }}<br/>
				<span class="fa fa-calendar"></span> {{ $person->birthdate }}<br/>
				@foreach($civil_status_list as $civil_status)
					@if($civil_status->id == $person->civil_status_id)
						<span class="fa fa-male"></span> {{ $civil_status->civil_status_name }}<br/>
					@endif
				@endforeach
	</td>
</tr>
</table>
</body>