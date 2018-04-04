<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<div class="col-md-12">
	    <div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="first_name">{{ Lang::get('gen_person.first_name') }}</label>
	        <div class="col-md-9">
	            <input class="form-control"  type="first_name" tabindex="1" placeholder="{{ Lang::get('person.first_name') }}" name="first_name" id="first_name" value="{{{ Input::old('first_name', isset($person) ? $person->first_name : null) }}}" />
	            {!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
	        </div>
	    </div>
    </div>
    <div class="col-md-12">
	    <div class="form-group {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="last_name">{{ Lang::get('gen_person.last_name') }}</label>
	        <div class="col-md-9">
	            <input class="form-control"  type="last_name" tabindex="1" placeholder="{{ Lang::get('person.last_name') }}" name="last_name" id="last_name" value="{{{ Input::old('last_name', isset($person) ? $person->last_name : null) }}}" />
	            {!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
	        </div>
	    </div>
    </div>
	<div class="col-md-12">
	    <div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
	        <label class="col-md-3 control-label" for="username">{{ Lang::get('registrar.username') }}</label>
	        <div class="col-md-9">
	            <input class="form-control"  type="username" tabindex="1" placeholder="{{ Lang::get('registrar.username') }}" name="username" id="username" value="{{{ Input::old('username', isset($guardian) ? $guardian->username : null) }}}" />
	            {!! $errors->first('username', '<label class="control-label" for="username">:message</label>')!!}
	        </div>
	    </div>
    </div>
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-3 control-label" for="confirm">{{ Lang::get('gen_user.activate_user') }}</label>
			<div class="col-md-9">
				<select class="form-control" name="confirmed" id="confirmed" tabindex="4">
					<option value="1"{{{ ((isset($gen_user) && $gen_user->confirmed == 1)? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_user.yes') }}}</option>
					<option value="0"{{{ ((isset($gen_user) && $gen_user->confirmed == 0) ? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_user.no') }}}</option>
				</select>
			</div>
		</div>
	</div>


    