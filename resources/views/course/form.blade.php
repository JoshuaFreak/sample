<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="col-md-10">
	<div class="form-group {{{ $errors->has('course_name') ? 'has-error' : '' }}}">
		<label class="col-md-2 control_label">{{{ Lang::get('course.course_name') }}}</label>
		<div class="col-md-5">
			<input type="text" class="form-control" name="course_name" id="course_name" value="">
			{!! $errors->first('course_name', '<label class="control-label" for="course_name">:message</label>')!!}
		</div>
	</div>
</div>