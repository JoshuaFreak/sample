<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('program_name') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="program_name">{!! Lang::get('program.program_name') !!}</label>
			<div class="col-md-9">
				<input class="form-control" type="text" name="program_name" id="program_name" value="" />
				{!! $errors->first('program_name', '<label class="control-label" for="program_name">:message</label>')!!}

			</div>
		</div>
		<div class="form-group {{{ $errors->has('program_color') ? 'has-error' : '' }}}">
			<label class="col-md-3 control-label" for="program_color">{!! Lang::get('program.program_color') !!}</label>
			<div id="cp2" class="col-md-8 input-group colorpicker-component" style="padding-left: 20px !important;">
				<input class="form-control" type="text" name="program_color" id="program_color" value="#00AABB" />
				<span class="input-group-addon"><i></i></span>
				{!! $errors->first('program_color', '<label class="control-label" for="program_color">:message</label>')!!}
			</div>
		</div>
	</div>
</div>