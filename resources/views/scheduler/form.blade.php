<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<input type="hidden" id="counter_Monday" value="1"/>
<input type="hidden" id="counter_Tuesday" value="1"/>
<input type="hidden" id="counter_Wednesday" value="1"/>
<input type="hidden" id="counter_Thursday" value="1"/>
<input type="hidden" id="counter_Friday" value="1"/>
<input type="hidden" id="counter_Saturday" value="1"/>
<input type="hidden" id="counter_Sunday" value="1"/>
	<div id="message">
	</div>


  <div class="col-md-3">
	  <div class="form-group {{{ $errors->has('classification_id') ? 'has-error' : '' }}}">
	        <label class="col-md-4 control-label" for="classification_id">{{ Lang::get('scheduler.classification') }}</label>
	    	<div class="col-md-8">
	            <select class="form-control" name="classification_id" id="classification_id" tabindex="4">
	            @if($action == 1)
	                @foreach($classification_list as $classification)
	                    @if($classification->id == $schedule->classification_id)
	                    <option name="classification_id" value="{{{ $classification->id }}}" selected>{{{ $classification->classification_name }}}</option>
	                    @else
	                    <option name="classification_id" value="{{{ $classification->id }}}">{{{ $classification->classification_name }}}</option>
	                    @endif
	                @endforeach
	            @else
	                <option name="" value="" selected></option>
	                @foreach($classification_list as $classification)
	                    <option name="classification_id" value="{{{ $classification->id }}}" >{{{ $classification->classification_name }}}</option>

	                @endforeach
	            @endif
	            </select>
	            {!! $errors->first('classification_id', '<label class="control-label" for="classification_id">:message</label>')!!}
	        </div>  
	    </div> 
   </div>
  <div class="col-md-3">
	  <div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
	        <label class="col-md-4 control-label"for="program_id">{{ Lang::get('scheduler.program') }}</label>
	    	<div class="col-md-8">
	            <select class="form-control" name="program_id" id="program_id" tabindex="4">
	            @if($action == 1)
	                @foreach($program_list as $program)
	                    @if($program->id == $schedule->program_id)
	                    <option name="program_id" value="{{{ $program->id }}}" selected>{{{ $program->program_name }}}</option>
	                    @else
	                    <option name="program_id" value="{{{ $program->id }}}">{{{ $program->program_name }}}</option>
	                    @endif
	                @endforeach
	            @else
	                <option name="" value="" selected></option>
	                @foreach($program_list as $program)
	                    <option name="program_id" value="{{{ $program->id }}}" >{{{ $program->program_name }}}</option>

	                @endforeach
	            @endif
	            </select>
	            {!! $errors->first('program_id', '<label class="control-label" for="program_id">:message</label>')!!}
	        </div>  
	    </div> 
   </div>
  <div class="col-md-2">
	  <div class="form-group {{{ $errors->has('semester_level_id') ? 'has-error' : '' }}}">
	        <label class="col-md-4 control-label"for="semester_level_id">{{ Lang::get('scheduler.semester_level') }}</label>
	    	<div class="col-md-8">
	            <select class="form-control" name="semester_level_id" id="semester_level_id" tabindex="4">
	            @if($action == 1)
	                @foreach($semester_level_list as $semester_level)
	                    @if($semester_level->id == $schedule->semester_level_id)
	                    <option name="semester_level_id" value="{{{ $semester_level->id }}}" selected>{{{ $semester_level->semester_name }}}</option>
	                    @else
	                    <option name="semester_level_id" value="{{{ $semester_level->id }}}">{{{ $semester_level->semester_name }}}</option>
	                    @endif
	                @endforeach
	            @else
	                <option name="" value="" selected></option>
	                @foreach($semester_level_list as $semester_level)
	                    <option name="semester_level_id" value="{{{ $semester_level->id }}}" >{{{ $semester_level->semester_name }}}</option>

	                @endforeach
	            @endif
	            </select>
	            {!! $errors->first('semester_level_id', '<label class="control-label" for="semester_level_id">:message</label>')!!}
	        </div>  
	    </div> 
   </div>
  <div class="col-md-2">
	  <div class="form-group {{{ $errors->has('term_id') ? 'has-error' : '' }}}">
	        <label class="col-md-4 control-label"for="term_id">{{ Lang::get('scheduler.term') }}</label>
	    	<div class="col-md-8">
	            <select class="form-control" name="term_id" id="term_id" tabindex="4">
	            @if($action == 1)
	                @foreach($term_list as $term)
	                    @if($term->id == $schedule->term_id)
	                    <option name="term_id" value="{{{ $term->id }}}" selected>{{{ $term->term_name }}}</option>
	                    @else
	                    <option name="term_id" value="{{{ $term->id }}}">{{{ $term->term_name }}}</option>
	                    @endif
	                @endforeach
	            @else
	                <option name="" value="" selected></option>
	                @foreach($term_list as $term)
	                    <option name="term_id" value="{{{ $term->id }}}" >{{{ $term->term_name }}}</option>

	                @endforeach
	            @endif
	            </select>
	            {!! $errors->first('term_id', '<label class="control-label" for="term_id">:message</label>')!!}
	        </div>  
	    </div> 
   </div>
  <div class="col-md-2">
	  <div class="form-group {{{ $errors->has('section_id') ? 'has-error' : '' }}}">
	        <label class="col-md-4 control-label"for="section_id">{{ Lang::get('scheduler.section') }}</label>
	    	<div class="col-md-8">
	            <select class="form-control" name="section_id" id="section_id" tabindex="4">
	            @if($action == 1)
	                @foreach($section_list as $section)
	                    @if($section->id == $schedule->section_id)
	                    <option name="section_id" value="{{{ $section->id }}}" selected>{{{ $section->section_name }}}</option>
	                    @else
	                    <option name="section_id" value="{{{ $section->id }}}">{{{ $section->section_name }}}</option>
	                    @endif
	                @endforeach
	            @else
	                <option name="" value="" selected></option>
	                @foreach($section_list as $section)
	                    <option name="section_id" value="{{{ $section->id }}}" >{{{ $section->section_name }}}</option>

	                @endforeach
	            @endif
	            </select>
	            {!! $errors->first('section_id', '<label class="control-label" for="section_id">:message</label>')!!}
	        </div>  
	    </div> 
   </div>

		<div class="col-md-12"> 
            <div class="col-md-12"><hr></div>

	        <div class="form-group"> 
	            @foreach($day_list as $day)
	            	<div class="col-md-1" style="width:14%;"><label class="control-label">{{{ $day -> day_name }}}</label></div>
	        	@endforeach
        	</div>
   			<div class="form-group">
   					@foreach($day_list as $day)
	    		
	            	<div class="col-md-12" style="width:14%;padding-top:20px;padding-bottom:20px;">

	            		<div class="form-group" id="container_{{{$day->day_name}}}">
	            			<div id="schedule_{{{$day->day_name}}}_1">
			            		<div class="col-md-12" style="padding-top:10px;" id="teacher_{{{$day->day_name}}}" >
									<select class="form-control" name="instructor_id" id="instructor_id" tabindex="4">
							                @if($action == 1)
							                    @foreach($instructor_list as $instructor)
							                        @if($instructor->id == $subject->instructor_id)
							                        <option name="instructor_id" value="{{{ $instructor->id }}}" selected>{{{$instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name}}}</option>
							                        @else
							                        <option name="instructor_id" value="{{{ $instructor->id }}}">{{{$instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name}}}</option>
							                        @endif
							                    @endforeach
							                @else
							                    <option name="" value="" style="display:none" selected>Teacher</option>
							                    @foreach($instructor_list as $instructor)
							                        <option name="instructor_id" value="{{{ $instructor->id }}}" >{{{$instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name}}}</option>

							                    @endforeach
							                @endif
							        </select>
					            </div>
					            <div class="col-md-12" style="padding-top:10px;">
									<select class="room_id form-control" name="room_id" id="room_id_{{{$day->day_name}}}" tabindex="4">
							                @if($action == 1)
							                    @foreach($room_list as $room)
							                        @if($room->id == $subject->room_id)
							                        <option name="room_id" value="{{{ $room->id }}}" selected>{{{ $room->room_name }}}</option>
							                        @else
							                        <option name="room_id" value="{{{ $room->id }}}">{{{ $room->room_name }}}</option>
							                        @endif
							                    @endforeach
							                @else
							                    <option name="" value="" style="display:none" selected>Room</option>
							                    @foreach($room_list as $room)
							                        <option name="room_id" value="{{{ $room->id }}}" >{{{ $room->room_name }}}</option>

							                    @endforeach
							                @endif
							        </select>
					            </div>
					            <div class="col-md-12" style="padding-top:10px;">
									<select class="subject_id form-control" name="subject_id" id="subject_id_{{{$day->day_name}}}" tabindex="4">
							                @if($action == 1)
							                    @foreach($subject_offered_list as $subject)
							                        @if($subject->id == $subject_offered->subject_id)
							                        <option name="subject_id" value="{{{ $subject->id }}}" selected>{{{ $subject->name }}}</option>
							                        @else
							                        <option name="subject_id" value="{{{ $subject->id }}}">{{{ $subject->name }}}</option>
							                        @endif
							                    @endforeach
							                @else
							                    <option name="" value="" style="display:none" selected>Subject</option>
							                    @foreach($subject_offered_list as $subject)
							                        <option name="subject_id" value="{{{ $subject->id }}}" >{{{ $subject->name }}}</option>

							                    @endforeach
							                @endif
							        </select>
					            </div>
					            <div class="datetimepicker3 input-group date col-md-12" style="padding-top:10px; padding-left:14px; padding-right:14px;">
					            	<input type="text" id="time_start" name="time_start"class="form-control" placeholder="Time In" value="Time In"></input>
					            	<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
					            </div>
					            <div class="datetimepicker3 input-group date col-md-12" style="padding:10px 14px;">
					            	<input type="text" id="time_end" name="time_end"class="form-control" placeholder="Time Out" value="Time Out"></input>
					            	<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
					            </div>
					            <div><hr/></div>

				        	</div>
				        </div>
				        <!-- <div class="form-group" id="container_{{{$day->day_name}}}">

				        </div> -->
			            
			            <div style="margin-left:70px;">
			            	<button class="btn btn-sm btn-primary add" data-name="{{{$day->day_name}}}" type="button">Add Schedule</button>
			            </div>
	            	</div>
					
	        	@endforeach
        		
	        </div>      	
       
        </div>
        
	<div class="col-md-12">
		<div class="col-md-12">
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-7">
		</div>
		<div class="col-md-5">
				<button id="save_changes_schedule" type="button" class="btn btn-sm btn-success pull-right" style="margin-bottom: 9px;">
                		<span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.save_changes") }}
            	</button>
		</div>
	</div>
</div>
{{-- Scripts --}}
@section('scripts')
	<script id="time_range_template" type="application/html-template">
		// <div class="form-group" id="schedule_{{{$day->day_name}}}">
  //   		<div class="col-md-12" style="padding-top:10px;" id="teacher_{{{$day->day_name}}}">
  //       		<select type="text" class="form-control">
  //       			<option selected style="display:none;">Teacher</option>
		// 			<option name="" value=""></option>
		// 		</select>
  //           </div>
  //           <div class="col-md-12" style="padding-top:10px;">
  //           	<select type="text" class="form-control">
  //           		<option selected style="display:none;">Room</option>
  //           		<option name="" value=""></option>
  //           	</select>
  //           </div>
  //           <div class="col-md-12" style="padding-top:10px;">
  //           	<select type="text" class="form-control">
  //           		<option selected style="display:none;">Subject</option>
  //           		<option name="" value=""></option>
  //           	</select>
  //           </div>
  //           <div class="col-md-12" style="padding-top:10px;">
  //           	<input class="form-control" placeholder="Time-in"/>
  //           </div>
  //           <div class="col-md-12" style="padding-top:10px;">
  //           	<input class="form-control" placeholder="Time-out"/>
  //           </div>
  //       </div>

	</script>


<script>
	$(".add").click(function(){

		var name = $(this).data('name');
		var counter = $("#counter_"+name).val();
		counter = parseInt(counter)+1;
		$("#counter_"+name).val(counter);

		var template = $("#schedule_"+name+"_1").clone().html();
		var html = "<div id='schedule_"+name+"_"+counter+"'>"+template+"</div>";
		$("#container_"+name).append(html);

		generateTimePicker();
	});

	function generateTimePicker() 
	{
        $('.datetimepicker3').datetimepicker({
            format: 'LT'
        })
    }





        $("#save_changes_schedule").click(function(){
		
		//iterate/visit person_education table
		var count = 1;
		$("#schedule_table > tbody > tr").each(function(){
            var instructor_id = $(this).find('select[name="instructor_id"] option:selected').val();
            var subject_id = $(this).find('select[name="subject_id"] option:selected').val();
            var room_id = $(this).find('select[name="room_id"] option:selected').val();
            var time_start = $(this).find('input[name="time_start"]').val();
            var time_end = $(this).find('input[name="time_end"]').val();

     			$.ajax(
				{
					url:'{{{ URL::to("schedule/postSaveScheduleJson") }}}',
					type:'post',
					data:
						{
							'instructor_id':instructor_id,
							'subject_id':subject_id,
							'room_id':room_id,
							'time_start':time_start,
							'time_end': time_end,
							'_token': $('input[name=_token]').val(),
						},
						async:false
				}

				).done(function(data){
					//alert("Saved Changes!");
				});	
      		
      		count++;
		});
		alert("Saved Changes!");
		 location.reload();
	});
    $(function() {
    	generateTimePicker();
        $("#classification_id").change(function(){

            selectListChange('program_id','{{{URL::to("program/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')
            selectListChange('section_id','{{{URL::to("section/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')
            selectListChange('term_id','{{{URL::to("term/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')
            selectListSubjectChange('subject_id','{{{URL::to("subject/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')
            selectListRoomChange('room_id','{{{URL::to("room/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Classification Level')

        });
    });
  



 </script>
@stop 
