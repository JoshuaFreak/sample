<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	<div id="message">
	</div>


	  <div class="col-md-8">

            <div class="form-group {{{ $errors->has('yr_n_sec_id') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="yr_n_sec_id">{{ Lang::get('scheduler.yr_n_sec_subject') }}</label>
                <div class="col-md-9">
	                <select class="form-control" name="yr_n_sec_id" id="yr_n_sec_id" tabindex="4">
	                @if($action == 1)
	                    @foreach($yr_n_sec_list as $yr_n_sec)
	                        @if($yr_n_sec->id == $scheduler->yr_n_sec_id)
	                        <option name="yr_n_sec_id" value="{{{ $yr_n_sec->id }}}" selected>{{{ $yr_n_sec->level }}} - {{{ $yr_n_sec->section_name }}} ({{{ $yr_n_sec->name }}}) - {{{ $yr_n_sec->first_name }}} {{{ $yr_n_sec->middle_name }}} {{{ $yr_n_sec->last_name }}}</option>
	                        @else
	                        <option name="yr_n_sec_id" value="{{{ $yr_n_sec->id }}}">{{{ $yr_n_sec->level }}} - {{{ $yr_n_sec->section_name }}} ({{{ $yr_n_sec->name }}}) - {{{ $yr_n_sec->first_name }}} {{{ $yr_n_sec->middle_name }}} {{{ $yr_n_sec->last_name }}}</option>
	                        @endif
	                    @endforeach
	                @else
	                    <option name="" value="" selected></option>
	                    @foreach($yr_n_sec_list as $yr_n_sec)
	                        <option name="yr_n_sec_id" value="{{{ $yr_n_sec->id }}}" >{{{ $yr_n_sec->level }}} - {{{ $yr_n_sec->section_name }}} ({{{ $yr_n_sec->name }}}) - {{{ $yr_n_sec->first_name }}} {{{ $yr_n_sec->middle_name }}} {{{ $yr_n_sec->last_name }}}</option>

	                    @endforeach
	                @endif
	                </select>
	                {!! $errors->first('yr_n_sec_id', '<label class="control-label" for="yr_n_sec_id">:message</label>')!!}
	            
            	</div>  
           </div>  
       </div>

		<div class="col-md-12"> 
            <div class="col-md-12"><hr></div>
	            <table class="table table-bordered table-striped" id= "schedule_table">
	                <thead>
	                    <tr>
	                        <th> {{ Lang::get("scheduler.no") }}</th>
	                        <th> {{ Lang::get("scheduler.subject_code") }}</th>
	                        <th> {{ Lang::get("scheduler.subject_description") }}</th>
	                        <th> {{ Lang::get("scheduler.instructor") }}</th>
	                        <th> {{ Lang::get("scheduler.time_start") }}</th>
	                        <th> {{ Lang::get("scheduler.time_end") }}</th>
	                        <th> {{ Lang::get("scheduler.day") }}</th>
	                       
	                        <!-- <th>{{ Lang::get("site.action") }}</th> -->
	                    </tr>
	                </thead>
	                <tbody id="subject_list_instructor">
	                </tbody>

	            </table>
           <!--   <div class="col-md-12">
                <button id='save_changes' class='btn btn-sm btn-success pull-right'>{{ Lang::get("form.save_changes") }}</button>
           		<br><br><br>
            </div> -->
        </div>
        




<!-- 	<div class="col-md-8">
		<div class="form-group {{{ $errors->has('employee_id') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="employee_id">{!! Lang::get('scheduler.instructor') !!}</label>
			<div class="col-md-9">
				<select class="form-control" name="employee_id" id="employee_id" tabindex="4">
					@if($action == 1)
						@foreach($instructor_list as $instructor)
							@if($instructor->id == $scheduler->employee_id)
							<option name="employee_id" value="{{{ $instructor->id }}}" selected>{{{ $instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name }}}</option>
							@else
							<option name="employee_id" value="{{{ $instructor->id }}}">{{{ $instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name }}}</option>
							@endif
						@endforeach
					@else
						<option name="" value="" selected></option>
						@foreach($instructor_list as $instructor)
							<option name="employee_id" value="{{{ $instructor->id }}}" >{{{ $instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name }}}</option>

						@endforeach

		
					@endif
				</select>
				{!! $errors->first('employee_id', '<label class="control-label" for="employee_id">:message</label>')!!}

			</div>
		</div>
	</div> -->


	<!--   <div class="col-md-8">
        <div class="form-group {{{ $errors->has('time') ? 'has-error' : '' }}}">
        <label class="col-md-3 control-label" for="time">{{ Lang::get('scheduler.time_start') }}</label>
        <div class="col-md-9">
            <input class="form-control"  type="time" tabindex="1" name="time" id="time" value="{{{ Input::old('time', isset($scheduler) ? $scheduler->time : null) }}}" />
            {!! $errors->first('time', '<label class="control-label" for="time">:message</label>')!!}
        </div>
    </div>
    </div>

    	  <div class="col-md-8">
        <div class="form-group {{{ $errors->has('time') ? 'has-error' : '' }}}">
        <label class="col-md-3 control-label" for="time">{{ Lang::get('scheduler.time_end') }}</label>
        <div class="col-md-9">
            <input class="form-control"  type="time" tabindex="1" name="time" id="time" value="{{{ Input::old('time', isset($scheduler) ? $scheduler->time : null) }}}" />
            {!! $errors->first('time', '<label class="control-label" for="time">:message</label>')!!}
        </div>
    </div>
    </div> -->

	<!-- <div class="form-group {{{ $errors->has('time') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="time">{!! Lang::get('scheduler.time_end') !!}</label>
		<div class="col-md-8">
			 <div class="form-group">
            <input type="text" class="form-control" id="time" name="time" value="{{{ Input::old('time', isset($scheduler) ? $scheduler->time : null) }}}">
        	</div>
		</div>
	</div>
	 -->
	


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
<script>

$(function() {

        $('.datepicker').datepicker(
        { 
            startView: "months", 
            minViewMode: "months",
            format: 'MM ,yyyy',
            orientation: "top left",
            autoclose: true,

        })

    });


    $("#yr_n_sec_id").change(function(){

        var yr_n_sec_id = $("#yr_n_sec_id").val();

            $.ajax( 
            {
                url:'{{{ URL::to("yr_n_sec_subject/SubjectdataJson") }}}',
                type:'GET',
                data:
                    {  
                        'yr_n_sec_id': yr_n_sec_id,
                       
                    },
                dataType: "json",
                async:false,
                success: function (data) 
                {       
                    $("#subject_list_instructor").empty();
                    var count = 1;

                    if(data == "")
                    {
                        //alert("No Trainee");
                    }
                    $.each(data, function(key, value) {

                        $("#subject_list_instructor").append(''
                            +'<tr><td><input type="hidden" name="yr_n_sec_subject_id" value="'+value.id+'"/>'+count+'.</td>'
                            +'<td><input type="text" id="code" name="code" class="form-control" value="'+value.code+'"></input></td>'
                            +'<td><input type="text" id="name" name="name"class="form-control" value="'+value.name+'"></input></td>'
                            +'<td><select class="form-control" id="instructor_id" name="instructor_id" tabindex="4">'
								+'<option name="" value=""></option>'
				            	+'@foreach($instructor_list as $instructor)'
				            	+'<option type="text" name="instructor_id" value="{{{ $instructor->id }}}">{{$instructor->first_name}} {{$instructor->middle_name}} {{$instructor->last_name}}</option>'		            	
				            	+'@endforeach'
			            	+'</select></td>'
                            +'<td><div class="datetimepicker3 input-group date col-md-12" style="width:170px;">'
                            	+'<input type="text" id="time_start" name="time_start"class="form-control" value="'+value.time_start+'"></input>'
                            	+'<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span></div></td>'
                            +'<td><div class="datetimepicker3 input-group date col-md-12"style="width:170px;">'
                            	+'<input type="text" id="time_end" name="time_end"class="form-control" value="'+value.time_end+'"></input>'
                            	+'<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span></div></td>'
                            +'<td><select class="form-control" id="day_id" name="day_id" tabindex="4">'
								+'<option name="" value=""></option>'
				            	+'@foreach($day_list as $day)'
				            	+'<option type="text" name="day_id" value="{{{ $day->id }}}">{{$day->day_code}}</option>'		            	
				            	+'@endforeach'
			            	+'</select></td>'
                            +'</tr>');
                        count++;
                        generateTimePicker();
                    });

                },

            });

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
            var yr_n_sec_subject_id = $(this).find('input[name="yr_n_sec_subject_id"]').val();
            var code = $(this).find('input[name="code"]').val();
            var name = $(this).find('input[name="name"]').val();
            var instructor_id = $(this).find('select[name="instructor_id"] option:selected').val();
            var time_start = $(this).find('input[name="time_start"]').val();
            var time_end = $(this).find('input[name="time_end"]').val();
            var day_id = $(this).find('select[name="day_id"] option:selected').val();

     			$.ajax(
				{
					url:'{{{ URL::to("schedule/postSaveScheduleJson") }}}',
					type:'post',
					data:
						{
							'yr_n_sec_id': $("#yr_n_sec_id").val(),
							'yr_n_sec_subject_id': yr_n_sec_subject_id,
							'code': code,
							'name': name,
							'instructor_id':instructor_id,
							'time_start':time_start,
							'time_end': time_end,
							'day_id': day_id,
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




 </script>
@stop 