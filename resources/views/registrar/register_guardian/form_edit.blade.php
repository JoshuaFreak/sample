<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<div class="col-md-12">
	    <div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="first_name">{{ Lang::get('gen_person.first_name') }}</label>
	        <div class="col-md-6">
	            <input class="form-control"  type="first_name" tabindex="1" placeholder="{{ Lang::get('person.first_name') }}" name="first_name" id="first_name" value="{{{ Input::old('first_name', isset($person) ? $person->first_name : null) }}}" />
	            {!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
	        </div>
	    </div>
    </div>
    <div class="col-md-12">
	    <div class="form-group {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="last_name">{{ Lang::get('gen_person.last_name') }}</label>
	        <div class="col-md-6">
	            <input class="form-control"  type="last_name" tabindex="1" placeholder="{{ Lang::get('person.last_name') }}" name="last_name" id="last_name" value="{{{ Input::old('last_name', isset($person) ? $person->last_name : null) }}}" />
	            {!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
	        </div>
	    </div>
    </div>
	<div class="col-md-12">
	    <div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="username">{{ Lang::get('registrar.username') }}</label>
	        <div class="col-md-6">
	            <input class="form-control"  type="username" tabindex="1" placeholder="{{ Lang::get('registrar.username') }}" name="username" id="username" value="{{{ Input::old('username', isset($guardian) ? $guardian->username : null) }}}" />
	            {!! $errors->first('username', '<label class="control-label" for="username">:message</label>')!!}
	        </div>
	    </div>
    </div>
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-3 control-label" for="confirm">{{ Lang::get('gen_user.activate_user') }}</label>
			<div class="col-md-6">
				<select class="form-control" name="confirmed" id="confirmed" tabindex="4">
					<option value="1"{{{ ((isset($gen_user) && $gen_user->confirmed == 1)? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_user.yes') }}}</option>
					<option value="0"{{{ ((isset($gen_user) && $gen_user->confirmed == 0) ? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_user.no') }}}</option>
				</select>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		@if($action == 1)
		<div class="form-group {{{ $errors->has('student') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="student">{!! Lang::get('student.student') !!}</label>
			<div class="col-md-8">
				<?php $count = 1?>
				@foreach($student_guardian_list as $student_guardian)

				<div class="form-group" id="student_container_{{{$student_guardian->id}}}" >
					<div class="col-md-8"><label class="control-label" id="student_id_{{{$student_guardian->id}}}" >{{{ $student_guardian->student_no }}} - {{{ $student_guardian->last_name }}}, {{{ $student_guardian->first_name }}} {{{ $student_guardian->middle_name }}}</label></div>
					<div class="col-md-1"><a data-href="{{{ URL::to('student_guardian/delete') }}}" data-value="{{{$student_guardian->id}}}" data-toggle="modal" data-target="#student_guardian_modal" class="btn btn-sm btn-danger close_popup delete_button" ><span class="glyphicon glyphicon-remove"></span></a></div>
				</div>
				@endforeach

				{!! $errors->first('student', '<label class="control-label" for="student">:message</label>')!!}

			</div>
		</div>
	@endif
		<div class="form-group {{{ $errors->has('student') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="student">{!! Lang::get('student.add_student') !!}</label>
			<div class="col-md-8">
				<select name="student[]" id="student" multiple style="width: 75%">
					@foreach($student_list as $student)
						<option value="{{{ $student->id }}}">{{{$student->student_no}}} - {{{$student->last_name}}}, {{{$student->first_name}}} {{{$student->middle_name}}}</option>
					@endforeach

				</select>

				{!! $errors->first('student', '<label class="control-label" for="student">:message</label>')!!}

			</div>
		</div>

<!-- MODAL -->
	<div class="modal fade" id="student_guardian_modal" role="dialog">

	    <div class="modal-dialog">

	      <!-- Modal content-->
	     	<div class="modal-content">
		        <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" >&times;</button>
			        <h4 class="modal-title">Confirm Delete</h4>
		        </div>
		        <div class="modal-body">
		        	<ul style="list-style-type:none;">
		        		Are you sure you want to <strong>delete this item?</strong><br/><br/>
		          		<li class="debug-url" ><span id="student_item"></span></li>
		          	</ul>
		        </div>
		        <div class="modal-footer">
		          	<button class="btn btn-default" data-dismiss="modal">Close</button>
		          	<a id="delete" class="btn btn-danger btn-ok">Delete</a>

		        </div>
	      	</div>

	    </div>
	</div>
<!-- END MODAL -->

    </div>


    