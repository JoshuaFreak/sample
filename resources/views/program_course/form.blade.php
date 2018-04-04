<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="col-md-10">
	<div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label">{{ Lang::get('program.program') }}</label>
		<div class="col-md-5">
			<select class="form-control" name="program_id" id="program_id">
				<option></option>
				@foreach($program_list as $program)
					<option class="form-control" id="program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group {{{ $errors->has('class_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label">{{ Lang::get('class.class_name') }}</label>
		<div class="col-md-5">
			<select class="form-control" name="class_id" id="class_id">
				<option></option>
				@foreach($class_list as $class)
					<option class="form-control" id="class_id" value="{{ $class -> id }}">{{ $class -> class_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group {{{ $errors->has('course_capacity_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label">{{ Lang::get('course_capacity.course_capacity') }}</label>
		<div class="col-md-5">
			<select class="form-control" name="course_capacity_id" id="course_capacity_id">
				<option></option>
				@foreach($course_capacity_list as $course_capacity)
					<option class="form-control" id="course_capacity_id" value="{{ $course_capacity -> id }}">{{ $course_capacity -> capacity_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group {{{ $errors->has('course_id') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label">{{ Lang::get('course_capacity.subject') }}</label>
		<div class="col-md-5">
			<select name="course_id[]" id="course_id" multiple style="width:100%;">
				<option></option>
				@foreach($course_list as $course)
					<option id="course_id" value="{{ $course -> id }}">{{ $course -> course_name }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#course_id').select2();
	});
</script>
@endsection