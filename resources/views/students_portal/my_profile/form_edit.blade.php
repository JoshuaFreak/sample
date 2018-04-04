<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<div class="col-md-12">
		<div class="col-md-5">
		    <div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
		        <label class="col-md-3 control-label" for="username">{{ Lang::get('gen_user.username') }}</label>
		        <div class="col-md-9">
		            <input readOnly class="form-control"  type="username" tabindex="1" placeholder="{{ Lang::get('gen_user.username') }}" name="username" id="username" value="{{{ Input::old('username', isset($student) ? $student->student_no : null) }}}" />
		            {!! $errors->first('username', '<label class="control-label" for="username">:message</label>')!!}
		        </div>
		    </div>
	    </div>
	</div>
	<div class="col-md-12">
		<div class="col-md-5">
			<div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
				<label class="col-md-3 control-label" for="password">{{ Lang::get('gen_user.password') }}</label>
				<div class="col-md-9">
					<input class="form-control"  tabindex="2" placeholder="{{ Lang::get('gen_user.password') }}" type="password" name="password" id="password" value="" />
					{!!$errors->first('password', '<label class="control-label" for="password">:message</label>')!!}
				</div>
			</div>
		</div>
	</div>	
	<div class="col-md-12">
		<div class="col-md-5">
			<div class="form-group {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
				<label class="col-md-3 control-label" for="password_confirmation">{{ Lang::get('gen_user.password_confirmation') }}</label>
				<div class="col-md-9">
					<input class="form-control" type="password" tabindex="3" placeholder="{{ Lang::get('gen_user.password_confirmation') }}"  name="password_confirmation" id="password_confirmation" value="" />
					{!!$errors->first('password_confirmation', '<label class="control-label" for="password_confirmation">:message</label>')!!}
				</div>
			</div>
		</div>
	</div>





    