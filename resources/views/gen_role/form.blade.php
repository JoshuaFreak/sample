<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<div class="col-md-12">
		<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
			<label class="col-md-2 control-label" for="name">{!! Lang::get('gen_role.name') !!}</label>
			<div class="col-md-10">
				<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($gen_role) ? $gen_role->name : null) }}}" />
				{!! $errors->first('name', '<label class="control-label" for="email">:message</label>')!!}

			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-2 control-label" for="confirm">{{ Lang::get('gen_role.activate_role') }}</label>
			<div class="col-md-10">
				<select class="form-control" name="is_admin" id="is_admin" tabindex="4">
					<option value="1"{{{ ((isset($gen_role) && $gen_role->is_admin == 1)? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_role.yes') }}}</option>
					<option value="0"{{{ ((isset($gen_role) && $gen_role->is_admin == 0) ? ' selected="selected"' : '') }}}>{{{ Lang::get('gen_role.no') }}}</option>
				</select>
			</div>
		</div>
	</div>