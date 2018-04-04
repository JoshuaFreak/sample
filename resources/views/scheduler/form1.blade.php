<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	<div id="message">
	</div>
	<div class="form-group">
		<div class="col-md-3"></div>
		<div class="col-md-3" >
            <label class="control-label"> Date Start</label>
        </div>
        <div class="col-md-1" >
            <label class="control-label"> Date End</label>
        </div>
	</div>
	<div class="form-group" id="date_range_container">
		<div class="col-md-3"></div>
		<div class="col-md-5" >
            <div class="col-md-12 input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control" id="date_range_from" name="date_range_from" value="" />
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm form-control" id="date_range_to" name="date_range_to" value="" />
            </div>  
        </div>
        <div class="col-md-2">
        	<button type="button" class="btn btn-primary iframe"  id="date_check" >Check</button>
        </div>
	</div>
	<div class="form-group {{{ $errors->has('course_id') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="course_id">{!! Lang::get('scheduler/scheduler.course') !!}</label>
		<div class="col-md-8">
			<select class="form-control" name="course_id" id="course_id" tabindex="4">
				@if($action == 1)
					@foreach($course_list as $course)
						@if($course->id == $schedule->course_id)
						<option name="course_id" value="{{{ $course->id }}}" selected>{{{ $course->course_code }}}</option>
						@else
						<option name="course_id" value="{{{ $course->id }}}">{{{ $course->course_code }}}</option>
						@endif
					@endforeach
				@else
					<option name="" value="" selected></option>
					@foreach($course_list as $course)
						<option name="course_id" value="{{{ $course->id }}}" >{{{ $course->course_code }}}</option>

					@endforeach

	
				@endif
			</select>
			{!! $errors->first('course_id', '<label class="control-label" for="course_id">:message</label>')!!}

		</div>
	</div>
	<input type="hidden" name="batch" id="batch" value="" />
	<div class="form-group {{{ $errors->has('batch') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="batch">{!! Lang::get('scheduler/scheduler.batch') !!}</label>
		<div class="col-md-8">
			<input class="form-control" type="text" name="batch_no" disabled="disabled" id="batch_no" value="{{{ Input::old('batch', isset($scheduler) ? $scheduler->batch : null) }}}" />
			{!! $errors->first('batch', '<label class="control-label" for="batch">:message</label>')!!}

		</div>
	</div>
	
	<div class="form-group {{{ $errors->has('instructor') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="instructor">{!! Lang::get('scheduler/scheduler.instructor') !!}</label>
		<div class="col-md-8">
			<select class="form-control" name="instructor_id" id="instructor_id" tabindex="4">
				@if($action == 1)
					@foreach($instructor_list as $instructor)
						@if($instructor->id == $schedule->instructor_id)
						<option name="instructor_id" value="{{{ $instructor->id }}}" selected>{{{ ucwords(strtolower($instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name)) }}}</option>
						@else
						<option name="instructor_id" value="{{{ $instructor->id }}}">{{{ ucwords(strtolower($instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name)) }}}</option>
						@endif
					@endforeach
				@else
					<option name="" value="" selected></option>
					@foreach($instructor_list as $instructor)
						<option name="instructor_id" value="{{{ $instructor->id }}}" >{{{  ucwords(strtolower($instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name)) }}}</option>

					@endforeach

	
				@endif
			</select>
			{!! $errors->first('instructor', '<label class="control-label" for="instructor">:message</label>')!!}

		</div>
	</div>
	<div class="form-group {{{ $errors->has('assessor_id') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="assessor_id">{!! Lang::get('scheduler/scheduler.assessor') !!}</label>
		<div class="col-md-8">
			<select class="form-control" name="assessor_id" id="assessor_id" tabindex="4">
				@if($action == 1)
					@foreach($instructor_list as $instructor)
						@if($instructor->id == $schedule->instructor_id)
						<option name="assessor_id" value="{{{ $instructor->id }}}" selected>{{{ ucword(strtolower($instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name)) }}}</option>
						@else
						<option name="assessor_id" value="{{{ $instructor->id }}}">{{{ ucword(strtolower($instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name)) }}}</option>
						@endif
					@endforeach
				@else
					<option name="" value="" selected></option>
					@foreach($instructor_list as $instructor)
						<option name="assessor_id" value="{{{ $instructor->id }}}" >{{{  ucwords(strtolower($instructor->first_name." ".$instructor->middle_name." ".$instructor->last_name)) }}}</option>

					@endforeach

	
				@endif
			</select>
			{!! $errors->first('assessor', '<label class="control-label" for="assessor">:message</label>')!!}

		</div>
	</div>
	<div class="form-group pull-right">
		<button id="add_time" type="button" class="btn btn-sm btn-success">
            <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("scheduler/scheduler.add_time_range") }}
        </button>
    </div>
    <div class="col-md-12"><hr></div>
	<div class="form-group">
		<div class="col-md-2">
	    </div>

		<div id="schedule_container" style="overflow-x:scroll; overflow-y:visible;" class="form-group col-md-9">
			<div class="form-group" id="week_container">

			</div>
		</div>
		
	</div>
	
	<!-- <div class="form-group col-md-12">
			<div class="col-md-5">
				<label class="col-md-12 control-label" ></label>
			</div>
			<div class="col-md-3">
				<label class="col-md-12 control-label" ></label>
			</div>
			<div class="col-md-2"></div>
			<div class="col-md-2">
				<div class="col-md-2"></div>
				<button type="button" id="add_date" class="btn btn-sm btn-success" >
		            <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("scheduler.add_date_range") }}
		        </button>
		    </div>
	</div> -->
	
	<div class="col-md-12">
		<div class="col-md-12">
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-7">
		</div>
		<div class="col-md-5">
			<button type="button" class="btn btn-sm  btn-primary iframe" id="save_update">
				<!-- <span class="glyphicon glyphicon-list"></span> -->
				 {{ Lang::get("scheduler/scheduler.save") }}
	        </button>
		</div>
	</div>
</div>
