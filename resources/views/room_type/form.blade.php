<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('code') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="code">{!! Lang::get('room_type.code') !!}</label>
			<div class="col-md-9">
				<input class="form-control" type="text" name="code" id="code" value="{{{ Input::old('code', isset($room_type) ? $room_type->code : null) }}}" />
				{!! $errors->first('code', '<label class="control-label" for="program_name">:message</label>')!!}

			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="name">{!! Lang::get('room_type.name') !!}</label>
			<div class="col-md-9">
				<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($room_type) ? $room_type->name : null) }}}" />
				{!! $errors->first('name', '<label class="control-label" for="name">:message</label>')!!}

			</div>
		</div>
	</div>
</div>


