<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	   <div class="col-md-6">
            <div class="form-group {{{ $errors->has('classification_id') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="classification_id">{{ Lang::get('subject.classification') }}</label>
	               <div class="col-md-8">
		                <select class="form-control" name="classification_id" id="classification_id" tabindex="4">
		                @if($action == 1)
		                    @foreach($classification_list as $classification)
		                        @if($classification->id == $subject->classification_id)
		                        <option name="classification_id" value="{{{ $classification->id }}}" selected>{{{ $classification->classification_name }}}</option>
		                        @else
		                        <option name="classification_id" value="{{{ $classification->id }}}">{{{ $classification->classification_name }}}</option>
		                        @endif
		                    @endforeach
		                @else
		                    <option name="" value="" selected></option>
		                    @foreach($classification_list as $classification)
		                        <option name="classification_id" value="{{{ $classification->id }}}" >{{{ $classification->classification_name }}}</option>

		                    @endforeach
		                @endif
		                </select>
		                {!! $errors->first('classification_id', '<label class="control-label" for="classification_id">:message</label>')!!}
	            	</div>  
            </div>  
        </div>
</div>
<div class="col-md-12">
	   <div class="col-md-6">
            <div class="form-group {{{ $errors->has('classification_level_id') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="classification_level_id">{{ Lang::get('classification_level.classification_level') }}</label>
	               <div class="col-md-8">
		                <select class="form-control" name="classification_level_id" id="classification_level_id" tabindex="4">
		                @if($action == 1)
		                    @foreach($classification_level_list as $classification_level)
		                        @if($classification_level->id == $subject->classification_level_id)
		                        <option name="classification_level_id" value="{{{ $classification_level->id }}}" selected>{{{ $classification_level->level }}}</option>
		                        @else
		                        <option name="classification_level_id" value="{{{ $classification_level->id }}}">{{{ $classification_level->level }}}</option>
		                        @endif
		                    @endforeach
		                @else
		                    <option name="" value="" selected> --Please Select Classification First--</option>
		                @endif
		                </select>
		                {!! $errors->first('classification_level_id', '<label class="control-label" for="classification_level_id">:message</label>')!!}
	            	</div>  
            </div>  
        </div>
</div>
<div class="col-md-12">
		<div class="col-md-6">
			<div class="form-group {{{ $errors->has('code') ? 'has-error' : '' }}}">
				<label class="col-md-4 control-label" for="code">{!! Lang::get('subject.code') !!}</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="code" id="code" value="{{{ Input::old('code', isset($subject) ? $subject->code : null) }}}" />
					{!! $errors->first('code', '<label class="control-label" for="code">:message</label>')!!}

				</div>
			</div>
		</div>
</div>
<div class="col-md-12">
       <div class="col-md-6">
			<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
				<label class="col-md-4 control-label" for="name">{!! Lang::get('subject.name') !!}</label>
				<div class="col-md-8">
					<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($subject) ? $subject->name : null) }}}" />
					{!! $errors->first('name', '<label class="control-label" for="name">:message</label>')!!}

				</div>
			</div>
		</div>	
</div>	
<!-- <div class="col-md-12">
	<div class="col-md-6">
		<div class="form-group {{{ $errors->has('is_pace') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="is_pace">{{ Lang::get('subject.is_pace') }}</label>
		<div class="col-md-9">
			<select class="form-control" name="is_pace" id="is_pace" tabindex="4">
				<option value="1"{{{ ((isset($subject) && $subject->is_pace == 1)? ' selected="selected"' : '') }}}>{{{ Lang::get('subject.yes') }}}</option>
				<option value="0"{{{ ((isset($subject) && $subject->is_pace == 0) ? ' selected="selected"' : '') }}}>{{{ Lang::get('subject.no') }}}</option>
			</select>
		</div>
		</div>
	</div>
</div> -->
<!-- <div class="col-md-12">
		<div class="col-md-6">
			<div class="form-group {{{ $errors->has('credit_unit') ? 'has-error' : '' }}}">
				<label class="col-md-3 control-label" for="credit_unit">{!! Lang::get('subject.credit_unit') !!}</label>
				<div class="col-md-9">
					<input class="form-control" type="text" name="credit_unit" id="credit_unit" value="{{{ Input::old('credit_unit', isset($subject) ? $subject->credit_unit : null) }}}" />
					{!! $errors->first('credit_unit', '<label class="control-label" for="credit_unit">:message</label>')!!}

				</div>
			</div>
		</div>
</div> -->
<!-- <div class="col-md-12">
		<div class="col-md-6">
			<div class="form-group {{{ $errors->has('hour_unit') ? 'has-error' : '' }}}">
				<label class="col-md-3 control-label" for="hour_unit">{!! Lang::get('subject.hour_unit') !!}</label>
				<div class="col-md-9">
					<input class="form-control" type="text" name="hour_unit" id="hour_unit" value="{{{ Input::old('hour_unit', isset($subject) ? $subject->hour_unit : null) }}}" />
					{!! $errors->first('hour_unit', '<label class="control-label" for="hour_unit">:message</label>')!!}

				</div>
			</div>
		</div>

</div> -->

@section('scripts')
<script type="text/javascript">
    $(function() {
        $("#classification_id").change(function(){
            selectListChange('classification_level_id','{{{ URL::to("classification_level/dataJson") }}}',  {'classification_id': $(this).val() } ,'Please select a Classification Level');
        });
    });
</script>
@stop