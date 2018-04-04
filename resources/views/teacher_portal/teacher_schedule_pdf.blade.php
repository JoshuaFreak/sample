<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
		table{font-size: 15px}
	  	img {  width:80px; height:80px; }
	  	i {  font-size:11px; }
		table{color:#0A0D0F;}
		/*table.table tr:nth-child(even){background-color: #F1F5F6}*/
		/*table.table td {border: 1px solid #B6CADC;padding-left: 10px; }*/
		table.table td {border: 1px solid #000;padding-left: 10px;}
		.data {color:black; text-transform: uppercase;}
		.data-center {color:black; text-align: center; text-transform: uppercase;}
		body {
			font-family: 'Roboto'; 
		}
		@font-face{
			font-family: 'Roboto';
			/*src: url(assets/site/fonts/Roboto/roboto-black-webfont.woff);*/
			/*src: url('assets/site/fonts/Roboto/roboto-black-webfont.woff');*/
		}
		.name{
			color: #923131;
		}
		td >label
		{
			margin-left: -10px !important;
		}
		.time > label
		{
			margin-left: -5px !important;	
		}
	</style>
</head>
<body>

<!-- <div style="border:1px solid #505050;">
	<table width="100%">
		<tr>
			<td rowspan="3" width="27%">
				<img src="{{ asset('assets/site/images/cia_logo.png') }}">
			</td>
			<td width="75%" colspan="4" style="padding-left: 70px;">
				<label class="">Teacher SCHEDULE</label>
			</td>
		</tr>
		<tr style="float: right;" class="tr_right">
			<td width="10%" align="right">
				<label class="">Name:</label>
			</td>
			<td width="40%">
				<label class="name">{{ $teacher -> first_name." ".$teacher -> last_name."(".$teacher_name.")" }}</label>
			</td>
			<td width="10%" align="right">
				<label class="">Course:</label>
			</td>
			<td width="30%">
			</td>
		</tr>
		<tr style="float: right;" class="tr_right">
			<td width="15%" align="right">
				<label class=""></label>
			</td>
			<td width="30%">
				<label class="name"></label>
			</td>
			<td width="10%" align="right">
				<label class=""></label>
			</td>
			<td width="20%">
			</td>
		</tr>
	</table>
</div>
 -->
<div style="margin-bottom: 10px;margin-left: -40px;margin-top: -40px;">
	<!-- <img src="{{ asset('assets/site/images/teacher_header_report.PNG') }}" width="785px;" height="100px;"> -->
	<img src="{{ asset('assets/site/images/teacher_header_report.PNG') }}" width="1040px;" height="140px;">
</div>
<div>
	<table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
		<tr>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Name</label>
			</td>
			<td width="25%">
				<label class="name">{{ $teacher -> first_name." ".$teacher -> last_name }}</label>
			</td>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Nickname</label>
			</td>
			<td width="20%">
				<label class="name">{{ $teacher_name }}</label>
			</td>
			<td width="15%" style="background-color: #B7DEE8;color: #000">
				<label class="">Gender</label>
			</td>
			<td width="20%">
				<label class="name">{{ $teacher -> gender_name }}</label>
			</td>
			<td width="15%" style="background-color: #B7DEE8;color: #000">
				<label class="">Room No.</label>
			</td>
			<td width="20%">
				<label class="name">{{ $teacher -> room_name }}</label>
			</td>
		</tr>
		<tr>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Department</label>
			</td>
			<td width="25%">
				<label class="name">{{$teacher -> department_name}}</label>
			</td>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Skill 1</label>
			</td>
			<td width="20%">
					{{--*/ $skill = "" /*--}}
					{{--*/ $skill_count = 0 /*--}}
				@foreach($teacher_skill as $skill_data)
					{{--*/ $skill_count++ /*--}}
					@if($skill_count == 1)
					{{--*/ $skill = $skill_data -> program_category_name."(".$skill_data -> skill_name.")" /*--}}
					@endif
				@endforeach
				<label class="name">{{$skill}}</label>
			</td>
			<td width="15%" style="background-color: #B7DEE8;color: #000">
				<label class="">Skill 2</label>
			</td>
			<td width="20%">
					{{--*/ $skill = "" /*--}}
					{{--*/ $skill_count = 0 /*--}}
				@foreach($teacher_skill as $skill_data)
					{{--*/ $skill_count++ /*--}}
					@if($skill_count == 2)
					{{--*/ $skill = $skill_data -> program_category_name."(".$skill_data -> skill_name.")" /*--}}
					@endif
				@endforeach
				<label class="name">{{$skill}}</label>
			</td>
			<td width="15%" style="background-color: #B7DEE8;color: #000">
				<label class="">Skill 3</label>
			</td>
			<td width="20%">
					{{--*/ $skill = "" /*--}}
					{{--*/ $skill_count = 0 /*--}}
				@foreach($teacher_skill as $skill_data)
					{{--*/ $skill_count++ /*--}}
					@if($skill_count == 3)
					{{--*/ $skill = $skill_data -> program_category_name."(".$skill_data -> skill_name.")" /*--}}
					@endif
				@endforeach
				<label class="name"></label>
			</td>
		</tr>
		<tr>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Id No</label>
			</td>
			<td width="25%">
				<label class="name"></label>
			</td>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Code</label>
			</td>
			<td width="20%" colspan="5">
				<label class="name"></label>
			</td>
		</tr>
	</table>
</div>
<div align="center"><h3  style="margin-bottom: 10px !important;margin-top: 10px !important">Class Schedule</h3></div>

<table border="1" cellpadding="0px" cellspacing="0px" width="1000px" class="table" style="margin-left: -20px">
	<tr align="center" style="background-color: #B7DEE8;color: #000">
          <td colspan="3" width="10%">Time</td>
          <td rowspan="2" width="10%">Course</td>
          <td rowspan="2" width="10%">Subject</td>
          <td rowspan="2" width="10%">Student/s</td>
          <td rowspan="2" width="8%" style="padding-left: 0px;">Nationality</td>
          <td rowspan="2" width="5%" style="padding-left: 0px;">Level/<br>Score/<br>Band</td>
          <td rowspan="2" width="5%" style="padding-left: 0px;">Room<br>No.</td>
          <td rowspan="2" width="7%" style="padding-left: 0px;">Type of Class</td>
          <td rowspan="2" width="10%">Books</td>
          <td rowspan="2" width="15%">Remarks</td>
    </tr>
    <tr style="background-color: #B7DEE8;color: #000">
    	<td style="font-size: 12px;padding-left: 0px;" width="5%" align="center">Period</td>
    	<td style="font-size: 12px" width="7%" align="center">Mon-Thu</td>
    	<td style="font-size: 12px;border-right: solid 3px #000" width="7%" align="center">Fri</td>
    </tr>
    {{--*/ $time_arr = ['','08:00 ~ 08:50','08:55 ~ 09:45','09:50 ~ 10:40','10:45 ~ 11:35','11:40 ~ 12:30','13:30 ~ 14:20','14:25 ~ 15:15','15:20 ~ 16:10','16:15 ~ 17:05','17:10 ~ 18:00']/*--}}

    {{--*/ $time_fri_arr = ['','08:00 ~ 08:45','08:50 ~ 09:35','09:40 ~ 10:25','10:30 ~ 11:15','11:20 ~ 12:05','13:05 ~ 13:50','13:55 ~ 14:40','14:45 ~ 15:30','15:35 ~ 16:20','16:25 ~ 17:10']/*--}}
    {{--*/	$arr_count = ['','st','nd','rd','th','th','th','th','th','th','th'] /*--}}
    <tbody>
    	@for($i = 1;$i <= 10;$i++)
    		<tr>
		    	<td style="font-size: 10px; height: 40px;padding-left: 0px;" align="center"><label class="">{{$i."".$arr_count[$i]}}</label></td>
		    	<td style="font-size: 10px; height: 20px;" class="time"><label class="">{{$time_arr[$i]}}</label></td>
		    	<td style="font-size: 10px; height: 20px;" class="time"><label class="">{{$time_fri_arr[$i]}}</label></td>
		    	{{--*/	$counter = 0 /*--}}
		    	@foreach($schedule_list as $schedule)
		    		@if($schedule -> class_id == $i)

			    	{{--*/	$counter++ /*--}}
		    			<td align="center" style="font-size: 12px;"><label class="">{{ $schedule -> program_name }}</label></td>
		    			<td align="center" style="font-size: 12px;"><label class="">{{ $schedule -> course_name }}</label></td>
		    			@if($schedule -> course_capacity_id == 8 || $schedule -> course_capacity_id == 5)
			    			<td align="center" style="font-size: 12px;" class="time"><label class="">Group Class</label></td>
		    			@else
		    				<td align="center" style="font-size: 12px;" class="time"><label class="">{{ $schedule -> sename }}</label></td>
		    			@endif
		    			<td align="center" style="font-size: 12px;" class="time"><label class="">{{ $schedule -> nationality_name }}</label></td>
		    			<td align="center" style="font-size: 12px;padding-left: 0px;"><label class="">{{ $level_arr[$schedule -> class_id] }}</label></td>
		    			<td align="center" style="font-size: 12px;padding-left: 0px;"><label class="">{{ $schedule -> room_name }}</label></td>
		    			<td align="center" style="font-size: 12px;padding-left: 0px;"><label class="">{{ $schedule -> capacity_name }}</label></td>
		    			<td align="center" style="font-size: 12px;padding-left: 0px;"><label class="">{{ $book_arr[$schedule -> class_id] }}</label></td>
		    			<td align="center" style="font-size: 12px;"><label class=""></label></td>
		    		@endif
		    	@endforeach

		    	@if($counter == 0)
		    		<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>			    			
	    			<td align="center" style="font-size: 12px;" class="time"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
		    	@endif
		    </tr>
    	@endfor
    </tbody>
</table>
<!-- <table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
	<tr align="center" style="background-color: #B9252C;color: #fff">
          <td colspan="3" width="25%">Time</td>
          <td rowspan="2">Subject</td>
          <td rowspan="2">Type of Class</td>
          <td rowspan="2">Class Room No.</td>
          <td rowspan="2">Student</td>
    </tr>
    <tr style="background-color: #B9252C;color: #fff">
    	<td style="font-size: 12px" width="5%" align="center">Period</td>
    	<td style="font-size: 12px" width="10%" align="center">Mon-Thu</td>
    	<td style="font-size: 12px" width="10%" align="center">Fri</td>
    </tr>
    {{--*/ $time_arr = ['','08:00 ~ 08:50','08:55 ~ 09:45','09:50 ~ 10:40','10:45 ~ 11:35','11:40 ~ 12:30','13:30 ~ 14:20','14:25 ~ 15:15','15:20 ~ 16:10','16:15 ~ 17:05','17:10 ~ 18:00']/*--}}

    {{--*/ $time_fri_arr = ['','08:00 ~ 08:50','08:55 ~ 09:45','09:50 ~ 10:40','10:45 ~ 11:35','11:40 ~ 12:30','13:30 ~ 14:20','14:25 ~ 15:15','15:20 ~ 16:10','16:15 ~ 17:05','17:10 ~ 18:00']/*--}}
    {{--*/	$arr_count = ['','st','nd','rd','th','th','th','th','th','th','th'] /*--}}
    <tbody>
    	@for($i = 1;$i <= 10;$i++)
    		<tr>
		    	<td style="font-size: 10px; height: 20px;" align="center"><label class="">{{$i."".$arr_count[$i]}}</label></td>
		    	<td style="font-size: 10px; height: 20px;" class="time"><label class="">{{$time_arr[$i]}}</label></td>
		    	<td style="font-size: 10px; height: 20px;" class="time"><label class="">{{$time_fri_arr[$i]}}</label></td>
		    	{{--*/	$counter = 0 /*--}}
		    	@foreach($schedule_list as $schedule)
		    		@if($schedule -> class_id == $i)

			    	{{--*/	$counter++ /*--}}
		    			<td align="center"><label class="">{{ $schedule -> course_name }}</label></td>
		    			<td align="center"><label class="">{{ $schedule -> capacity_name }}</label></td>
		    			<td align="center" style="font-size: 12px;" class="time"><label class="">{{ $schedule -> room_name }}</label></td>
		    			<td align="center" style="font-size: 12px;" class="time"><label class="">{{ $schedule -> student_english_name }}</label></td>
		    		@endif
		    	@endforeach

		    	@if($counter == 0)
		    		<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>			    			
	    			<td align="center" style="font-size: 12px;" class="time"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
		    	@endif
		    </tr>
    	@endfor
    </tbody>
</table>
 -->
<!-- <table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
	<tr style="background-color: #3A3F61;color: #fff;" align="center">
          <td>Subject</td>
          <td>Teacher</td>
          <td>Room</td>
          <td>Time In</td>
          <td>Time Out</td>
    </tr>
	@foreach($schedule_list as $schedule)
		<tr style="background-color: #fff;">
			<td >{{ $schedule -> course_name }}</td>
			<td >{{ $schedule -> nickname }}</td>
			<td >{{ $schedule -> room_name }}</td>
			<td >{{ $schedule -> time_in." ".$schedule -> time_in_session }}</td>
			<td >{{ $schedule -> time_out." ".$schedule -> time_out_session }}</td>
		</tr>
	@endforeach  
</table> -->
</body>