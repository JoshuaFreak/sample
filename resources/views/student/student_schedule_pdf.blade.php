<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
		table{font-size: 15px}
	  	/*img {  width:80px; height:80px; }*/
	  	img {  width:705px;}
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
			color: #000;
		}
		td >label
		{
			margin-left: -10px !important;
		}
		.time > label
		{
			margin-left: -5px !important;	
		}
		@page { size: a4 portrait; }
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
				<label class="">STUDENT CLASS SCHEDULE</label>
			</td>
		</tr>
		<tr style="float: right;" class="tr_right">
			<td width="15%" align="right">
				<label class="">Student's Name:</label>
			</td>
			<td width="30%">
				<label class="name">{{$student_name}}</label>
			</td>
			<td width="10%" align="right">
				<label class="">Level:</label>
			</td>
			<td width="20%">
				@if($batch)
					<label class="">{{$batch -> level_code}}</label>
				@else
					<label class=""></label>
				@endif
			</td>
		</tr>
		<tr>
			<td  align="right">
				<label class="">Course:</label>
			</td>
			<td>
				<label class="name">{{$program -> program_name}}</label>
			</td>
			<td  align="right">
				<label class="">Code:</label>
			</td>
			<td>
				<label class=""></label>
			</td>
		</tr>
	</table>
</div> -->

<!-- <div style="margin-bottom: 10px;margin-left: -40px;margin-top: -40px;">
 -->
 <div style="margin-bottom: 10px;margin-left: 0px;margin-top: -40px;margin-bottom: 20px;" align="center">
<img src="{{ asset('assets/site/images/header_report1.jpg') }}" width="790px;">
</div>
<div>
	<table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
		<tr>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Name:</label>
			</td>
			<td width="25%" style="font-size: 12px;">
				<label class="name">{{$student_name}}</label>
			</td>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Nickname:</label>
			</td>
			<td width="20%" style="font-size: 12px;">
				<label class="name">{{$nickname}}</label>
			</td>
			<td width="15%" style="background-color: #B7DEE8;color: #000">
				<label class="">Student No:</label>
			</td>
			<td width="20%" style="font-size: 12px;">
				<label class="name">{{$student_id}}</label>
			</td>
		</tr>
		<tr>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Gender:</label>
			</td>
			<td width="25%" style="font-size: 12px;">
				@if($gender_name)
					<label class="name">{{ $gender_name -> gender_name }}</label>
				@else
					<label class="name"></label>
				@endif
			</td>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Nationality:</label>
			</td>
			<td width="20%" style="font-size: 12px;">
				@if($nationality)
					<label class="name">{{ $nationality -> nationality_name }}</label>
				@else
					<label class="name"></label>
				@endif
			</td>
			<td width="15%" style="background-color: #B7DEE8;color: #000">
				<label class="">Course:</label>
			</td>
			<td width="20%" style="font-size: 12px;">
				<label class="name">{{$program -> program_name}}</label>
			</td>
		</tr>
		<tr>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Duration:</label>
			</td>
			<td width="25%" style="font-size: 12px;">
				<label class="name">{{$period."w"}}</label>
			</td>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Start Date:</label>
			</td>
			<td width="20%" style="font-size: 12px;">
				<label class="name">{{$date_from}}</label>
			</td>
			<td width="15%" style="background-color: #B7DEE8;color: #000">
				<label class="">End Date:</label>
			</td>
			<td width="20%" style="font-size: 12px;">
				<label class="name">{{$date_to}}</label>
			</td>
		</tr>
		<tr>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Code:</label>
			</td>
			<td width="25%" style="font-size: 12px;">
				<label class="name">{{$student -> student_personality_name}}</label>
			</td>
			<td width="10%" style="background-color: #B7DEE8;color: #000">
				<label class="">Remarks:</label>
			</td>
			<td width="20%" colspan="3">
				<label class="name"></label>
			</td>
		</tr>
	</table>
</div>

@if($examination_id != "null" && $examination_id != "")
<div align="center"><h3  style="margin-bottom: 5px !important;margin-top: 5px !important">Entrance Test Data</h3></div>
<table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table" style="font-size: 12px;">
	<tr align="center" style="background-color: #B7DEE8;color: #000">
      	<td width="15%"></td>
		@foreach($student_examination_score_list as $student_examination_score)
          <td>{{$student_examination_score -> examination_type_name}}</td>
        @endforeach
    	<td width="15%">Total Score Average Level</td>
    </tr>
    <tr align="center">
      	<td width="15%">Raw Score</td>
      	{{--*/ $total_score = 0/*--}}
		@foreach($student_examination_score_list as $student_examination_score)
          <td>{{$student_examination_score -> score}}</td>
          {{--*/ $total_score = $total_score + $student_examination_score -> score /*--}}
        @endforeach
    	<td width="15%">{{$total_score}}</td>
    </tr>
    <tr align="center" style="background-color: #B7DEE8;color: #000">
      	<td width="15%">{{ $level_label }}</td>
      	{{--*/ $total_score_toeic = 0/*--}}
      	{{--*/ $score_toeic = 0/*--}}
		@foreach($student_examination_score_list as $student_examination_score)
			@if($student_examination_score -> examination_type_id == 1)
	          <td>{{$code."".$listening_arr[$student_examination_score -> score]}}</td>
	          {{--*/ $score_toeic = intval($listening_arr[$student_examination_score -> score]) /*--}}
	        @elseif($student_examination_score -> examination_type_id == 2)
	          <td>{{$code."".$grammar_voca_arr[$student_examination_score -> score]}}</td>
	          {{--*/ $score_toeic = intval($grammar_voca_arr[$student_examination_score -> score]) /*--}}
	        @elseif($student_examination_score -> examination_type_id == 3)
	          <td>{{$code."".$reading_arr[$student_examination_score -> score]}}</td>
	          {{--*/ $score_toeic = intval($reading_arr[$student_examination_score -> score]) /*--}}
	        @elseif($student_examination_score -> examination_type_id == 4)
	          <td>{{$code."".$writing_arr[$student_examination_score -> score]}}</td>
	          {{--*/ $score_toeic = intval($writing_arr[$student_examination_score -> score]) /*--}}
	        @elseif($student_examination_score -> examination_type_id == 5)
	          <td>{{$code."".$speaking_arr[$student_examination_score -> score]}}</td>
	          {{--*/ $score_toeic = intval($speaking_arr[$student_examination_score -> score]) /*--}}
	        @else
	        	<td></td>
	        @endif
	        {{--*/ $total_score_toeic = $total_score_toeic + $score_toeic /*--}}
        @endforeach
        @if($count_score == 5)
	    	@foreach($percentage_level_list as $percentage_level)
	    		@if($total_score >= $percentage_level -> range_from && $percentage_level -> range_to >= $total_score)
	        	<td>{{ $percentage_level -> level_code}}</td>
	        	@endif
	    	@endforeach
	    @else
	    	<td>{{ $total_score_toeic }}</td>
	    @endif
    </tr>
    <tr align="center">
      	<td width="15%">{{ $target_level_label }}</td>
      	{{--*/ $total_target_score_toeic = 0/*--}}
      	{{--*/ $target_score_toeic = 0/*--}}
		@foreach($student_examination_score_list as $student_examination_score)
	        @if($student_examination_score -> examination_type_id == 1)
	          	{{--*/ $listening_total_level = $listening_arr[$student_examination_score -> score] /*--}}
	          	@if($count_score == 5)
		          	<td>{{$code}}{{ intval($listening_total_level)+1 }}</td>
		      	@else
		      		<td>{{$code}}{{ $listening_arr[intval($student_examination_score -> score + 1)] }}</td>
		      		{{--*/ $target_score_toeic = $listening_arr[intval($student_examination_score -> score +1)] /*--}}
		      	@endif
	        @elseif($student_examination_score -> examination_type_id == 2)
	          	{{--*/ $grammar_voca_total_level = $grammar_voca_arr[$student_examination_score -> score] /*--}}
	          	<td>{{$code}}{{ intval($grammar_voca_total_level)+1 }}</td>
	        @elseif($student_examination_score -> examination_type_id == 3)
	          	{{--*/ $reading_total_level = $reading_arr[$student_examination_score -> score] /*--}}
        		@if($count_score == 5)
	          		<td>{{$code}}{{ intval($reading_total_level)+1 }}</td>
	          	@else
		      		<td>{{$code}}{{ $reading_arr[intval($student_examination_score -> score + 1)] }}</td>
		      		{{--*/ $target_score_toeic = $reading_arr[intval($student_examination_score -> score +1)] /*--}}
		      	@endif
	        @elseif($student_examination_score -> examination_type_id == 4)
	          {{--*/ $writing_total_level = $writing_arr[$student_examination_score -> score] /*--}}
	          <td>{{$code}}{{ intval($writing_total_level)+1 }}</td>
	        @elseif($student_examination_score -> examination_type_id == 5)
	          {{--*/ $speaking_total_level = $speaking_arr[$student_examination_score -> score] /*--}}
	          <td>{{$code}}{{ intval($speaking_total_level)+1 }}</td>
	        @else
	        	<td></td>
	        @endif
	        {{--*/ $total_target_score_toeic = $total_target_score_toeic + $target_score_toeic /*--}}
        @endforeach
        @if($count_score == 5)
	        @foreach($percentage_level_list as $percentage_level)
	    		@if($total_score >= $percentage_level -> range_from && $percentage_level -> range_to >= $total_score)
	        	{{--*/ $total_level = $percentage_level -> level_code /*--}}
	        	@endif
	    	@endforeach
	    		<td>L{{ intval(substr($total_level,1,2))+1 }}</td>
	   	@else
	   		<td>{{ $total_target_score_toeic }}</td>
	   	@endif
    </tr>
</table>
@endif

<div align="center"><h3  style="margin-bottom: 10px !important;margin-top: 5px !important">Class Schedule</h3></div>
<table border="1" cellpadding="0px" cellspacing="0px" width="100%" class="table">
	<tr align="center" style="background-color: #B7DEE8;color: #000">
          <td colspan="3" width="25%">Time</td>
          <td rowspan="2" width="12%" style="padding-left: 0px;">Subject</td>
          <td rowspan="2" width="12%" style="padding-left: 0px;">Teacher</td>
          <td rowspan="2" width="11%" style="padding-left: 0px;">Class Room No.</td>
          <td rowspan="2" width="10%" style="padding-left: 0px;">Type of Class</td>
          <td rowspan="2">Book</td>
    </tr>
    <tr style="background-color: #B7DEE8;color: #000">
    <!-- <tr> -->
    	<td style="font-size: 12px;padding-left: 0px;" width="3%" align="center">Period</td>
    	<td style="font-size: 12px" width="10%" align="center">Mon-Thu</td>
    	<td style="font-size: 12px;position: absolute;" width="10%" align="center">Fri</td>
    </tr>
    {{--*/ $time_arr = ['','08:00 ~ 08:50','08:55 ~ 09:45','09:50 ~ 10:40','10:45 ~ 11:35','11:40 ~ 12:30','13:30 ~ 14:20','14:25 ~ 15:15','15:20 ~ 16:10','16:15 ~ 17:05','17:10 ~ 18:00','18:05 ~ 18:55']/*--}}

    {{--*/ $time_fri_arr = ['','08:00 ~ 08:45','08:50 ~ 09:35','09:40 ~ 10:25','10:30 ~ 11:15','11:20 ~ 12:05','13:05 ~ 13:50','13:55 ~ 14:40','14:45 ~ 15:30','15:35 ~ 16:20','16:25 ~ 17:10','18:35 ~ 19:20']/*--}}
    {{--*/	$arr_count = ['','st','nd','rd','th','th','th','th','th','th','th','th'] /*--}}
    <tbody>
    	@for($i = 1;$i <= 11;$i++)
    		<tr>
		    	<td style="font-size: 11px;height:40px;padding-left: 0px;" align="center"><label class="">{{$i."".$arr_count[$i]}}</label></td>
		    	<td style="font-size: 11px;" class="time"><label class="">{{$time_arr[$i]}}</label></td>
		    	<td style="font-size: 11px;" class="time"><label class="">{{$time_fri_arr[$i]}}</label></td>
		    	{{--*/	$counter = 0 /*--}}
		    	@foreach($schedule_list as $schedule)
		    		@if($schedule -> class_id == $i)
		    		
				    	{{--*/	$counter++ /*--}}
		    			<td align="center" style="font-size: 11px;padding-left: 0px;"><label class="" >{{ $schedule -> course_name }}</label></td>
		    			@if($schedule -> nickname != "" && $schedule -> nickname != "Free Time")
			    			<td align="center" style="font-size: 11px;padding-left: 0px;"><label class="">{{ $schedule -> nickname }}</label></td>
		    			@else
			    			<td align="center" style="font-size: 11px;padding-left: 0px;"><label class="">N/A</label></td>
		    			@endif
		    			@if($schedule -> room_code == "Self-Study")
			    			<td align="center" style="font-size: 11px;padding-left: 0px;" class="time"><label class="">{{ $schedule -> room_code }}</label></td>
			    		@elseif($schedule -> room_name == "")
			    			<td align="center" style="font-size: 11px;padding-left: 0px;" class="time"><label class="">N/A</label></td>
		    			@else
			    			<td align="center" style="font-size: 11px;padding-left: 0px;" class="time"><label class="">{{ $schedule -> room_name }}</label></td>
		    			@endif
		    			@if($schedule -> capacity_name != "")
			    			<td align="center" style="font-size: 11px;padding-left: 0px;"><label class="">{{ $schedule -> capacity_name }}</label></td>
			    		@else
			    			<td align="center" style="font-size: 11px;padding-left: 0px;"><label class="">N/A</label></td>
			    		@endif
		    			@if($book_arr[$i] != "")
			    			<td align="center" style="font-size: 11px;padding-left: 0px;"><label class="">{{ $book_arr[$i] }}</label></td>
			    		@else
			    			<td align="center" style="font-size: 11px;padding-left: 0px;"><label class="">N/A</label></td>
			    		@endif
		    		@endif
		    	@endforeach

		    	@if($counter == 0)
		    		<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>			    			
	    			<td align="center" style="font-size: 11px;" class="time"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
	    			<td align="center"><label class=""></label></td>
		    	@endif
		    </tr>
    	@endfor
    </tbody>
</table>
<img src="{{ asset('assets/site/images/table_th.PNG') }}" width="100%" style="margin-top:-491px">
<div align="center"><h3 style="margin-top: 5px;margin-bottom: 10px !important">Reminders</h3></div>
<div style="border:1px solid #505050;font-size: 12px;padding-left: 10px;margin-top: 10px !important">
	<!-- <p>
	• On the first day, you will mainly focus on your personal introduction, confirm your teachers, subjects and assigned textbooks.<br>
	• If you are a new student, you can change classes after one week.<br>
	• Changing Class: Every Saturdays from 8AM to 12NN(Priority numbers are distributed on Friday from 7 P.M. at the Clinic.)<br>
	• If you are late for more than 10 minutes after the class starts, the class will be automatically cancelled and you will be marked absent.<br>
	• If you are absent in any class more than twice in a week without permission, the class will be automatically deleted.<br>
	• A student who has more than five (5) absences in a week is not allowed to go out of the campus the next weekend.<br>
	</p> -->
	<p>
	• On the first day, you will mainly focus on your personal introduction, setting expectations, and confirming teachers, subjects and assigned textbooks.<br>
	• Refrain from writing on your books (possible book level changes may occur within the first week) unless confirmed by you and the teacher.<br>
	• ESL students may request for schedule/teacher changes on Fridays from 7PM to 10PM. Priority numbers are distributed on Friday from 12:05 to 1:05P.M. at the Clinic.<br>
	• IELTS/TOEIC/Business English Courses may approach the designated academic leaders in the academic office from Monday PM to Thursday PM for schedule/teacher changes. Changes are effective the week after the request is done. <br>
	• Be punctual in class. If you show up in more than 10 minutes after the class starts, the class will be automatically cancelled and you will be marked absent.<br>
	• Any student who accumulates more than five (5) absences in a week will not allowed to go out of the campus the following weekend.<br>
	</p>
</div>
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