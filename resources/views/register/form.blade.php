<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('date') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="examination_id">Enroll Date</label>
            
            <div class="col-md-8">
				<input class="form-control" type="text" name="date_enrolled" id="date_enrolled" value="" />
				{!! $errors->first('date_enrolled', '<label class="control-label" for="date_enrolled">:message</label>')!!}

            </div>
        </div>
	</div>
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('date') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="examination_id">Length of Stay</label>
            
            <div class="input-daterange input-group col-md-8" style="padding-left: 15px !important;padding-right: 15px !important;" id="datepicker">
                <input type="text" id="date_start" class="form-control" name="start" value="" />
                <span class="input-group-addon">to</span>
                <input type="text" id="date_end" class="form-control" name="end" value="" />
            </div>
        </div>
	</div>
</div>
<!-- <div class="col-md-12">
	
</div>
 -->
 <div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('examination') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="examination_id">{{ Lang::get('register_lang.examination') }}</label>
            <div class="col-md-8">
            <select class="form-control" name="examination_id" id="examination_id">
                @foreach($examination_list as $examination)
                    <option name="examination_id" value="{{{ $examination->id }}}">{{{ $examination->examination_name }}}</option>
                @endforeach    
            </select>
            </div>
        </div>
	</div>
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="program_id">Program</label>
            <div class="col-md-8">
            <select class="form-control" name="program_id" id="program_id">
                @foreach($program_list as $program)
                    <option name="program_id" value="{{{ $program->id }}}">{{{ $program->program_name }}}</option>
                @endforeach    
            </select>
            </div>
        </div>
	</div>
</div>
<!-- <div class="col-md-12">
	
</div> -->
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="first_name">{!! Lang::get('register_lang.first_name') !!}</label>
			<div class="col-md-8">
				<input class="form-control" type="text" name="first_name" id="first_name" value="" />
				{!! $errors->first('first_name', '<label class="control-label" for="program_name">:message</label>')!!}

			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('middle_name') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="middle_name">{!! Lang::get('register_lang.middle_name') !!}</label>
			<div class="col-md-8">
				<input class="form-control" type="text" name="middle_name" id="middle_name" value="" />
				{!! $errors->first('middle_name', '<label class="control-label" for="middle_name">:message</label>')!!}

			</div>
		</div>
	</div>
</div>
<!-- <div class="col-md-12">
	
</div> -->
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="last_name">{!! Lang::get('register_lang.last_name') !!}</label>
			<div class="col-md-8">
				<input class="form-control" type="text" name="last_name" id="last_name" value="" />
				{!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}

			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('student_english_name') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="student_english_name">{!! Lang::get('register_lang.student_english_name') !!}</label>
			<div class="col-md-8">
				<input class="form-control" type="text" name="student_english_name" id="student_english_name" value="" />
				{!! $errors->first('student_english_name', '<label class="control-label" for="student_english_name">:message</label>')!!}

			</div>
		</div>
	</div>
</div>
<!-- <div class="col-md-12">
	
</div> -->
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="email">{!! Lang::get('register_lang.email') !!}</label>
			<div class="col-md-8">
				<input class="form-control" type="email" name="email" id="email" value="" />
				{!! $errors->first('email', '<label class="control-label" for="email">:message</label>')!!}
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="username">{!! Lang::get('register_lang.username') !!}</label>
			<div class="col-md-8">
				<input class="form-control" type="text" name="username" id="username" value="" />
				{!! $errors->first('username', '<label class="control-label" for="username">:message</label>')!!}
			</div>
		</div>
	</div>
</div>
<!-- <div class="col-md-12">
	
</div>
 -->
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('nationality_id') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="nationality_id">{{ Lang::get('register_lang.nationality') }}</label>
            <div class="col-md-8">
            <select class="form-control" name="nationality_id" id="nationality_id">
                @foreach($nationality_list as $nationality)
                    <option name="nationality_id" value="{{{ $nationality->id }}}">{{{ $nationality->nationality_name }}}</option>
                @endforeach    
            </select>
            </div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('agency_id') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="agency_id">{{ Lang::get('register_lang.agency') }}</label>
            <div class="col-md-8">
            <select class="form-control" name="agency_id" id="agency_id">
                @foreach($agency_list as $agency)
                    <option name="agency_id" value="{{{ $agency->id }}}">{{{ $agency->agency_name }}}</option>
                @endforeach    
            </select>
            </div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<hr/>
</div>