<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('campus_name') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="campus_name">{!! Lang::get('campus.campus_name') !!}</label>
			<div class="col-md-9">
				<input class="form-control" type="text" name="campus_name" id="campus_name" value="{{{ Input::old('campus_name', isset($campus) ? $campus->campus_name : null) }}}" />
				{!! $errors->first('campus_name', '<label class="control-label" for="program_name">:message</label>')!!}

			</div>
		</div>
	</div>
</div>


