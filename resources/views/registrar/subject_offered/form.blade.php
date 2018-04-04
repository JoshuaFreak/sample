<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
	<div class="col-md-12">
	       <div class="col-md-6">
				<div class="form-group {{{ $errors->has('classification_name') ? 'has-error' : '' }}}">
					<label class="col-md-2 control-label" for="classification_name">{!! Lang::get('subject_offered.classification') !!}</label>
					<div class="col-md-10">
						<input type="text" readOnly class="form-control"  name="classification_name" id="classification_name" value="{{{ Input::old('classification_name', isset($classification) ? $classification->classification_name : null) }}}" />
						{!! $errors->first('classification_name', '<label class="control-label" for="classification_name">:message</label>')!!}

					</div>
				</div>
			</div>	
	</div>
	<div class="col-md-12">
	       <div class="col-md-6">
				<div class="form-group {{{ $errors->has('program_name') ? 'has-error' : '' }}}">
					<label class="col-md-2 control-label" for="program_name">{!! Lang::get('subject_offered.program') !!}</label>
					<div class="col-md-10">
						<input type="text" readOnly class="form-control" name="program_name" id="program_name" value="{{{ Input::old('program_name', isset($program) ? $program->program_name : null) }}}" />
						{!! $errors->first('program_name', '<label class="control-label" for="program_name">:message</label>')!!}

					</div>
				</div>
			</div>	
	</div>
	<div class="col-md-12">
	       <div class="col-md-6">
				<div class="form-group {{{ $errors->has('term_name') ? 'has-error' : '' }}}">
					<label class="col-md-2 control-label" for="term_name">{!! Lang::get('subject_offered.term') !!}</label>
					<div class="col-md-10">
						<input type="text" readOnly class="form-control" name="term_name" id="term_name" value="{{{ Input::old('term_name', isset($term) ? $term->term_name : null) }}}" />
						{!! $errors->first('term_name', '<label class="control-label" for="term_name">:message</label>')!!}

					</div>
				</div>
			</div>	
	</div>
	<div class="col-md-12">
	       <div class="col-md-6">
				<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
					<label class="col-md-2 control-label" for="name">{!! Lang::get('subject_offered.subject') !!}</label>
					<div class="col-md-10">
						<input type="text" readOnly class="form-control" name="name" id="name" value="{{{ Input::old('name', isset($subject) ? $subject->name : null) }}}" />
						{!! $errors->first('name', '<label class="control-label" for="name">:message</label>')!!}

					</div>
				</div>
			</div>	
	</div>	
	<div class="col-md-12">
		<div class="col-md-6">
			<div class="form-group {{{ $errors->has('is_approved') ? 'has-error' : '' }}}">
				<label class="col-md-2 control-label" for="confirm">{{ Lang::get('subject_offered.approved_subject') }}</label>
				<div class="col-md-10">
					<select class="form-control" name="is_approved" id="is_approved" tabindex="4">
						<option value="1"{{{ ((isset($subject_offered) && $subject_offered->is_approved == 1)? ' selected="selected"' : '') }}}>{{{ Lang::get('subject_offered.yes') }}}</option>
						<option value="0"{{{ ((isset($subject_offered) && $subject_offered->is_approved == 0) ? ' selected="selected"' : '') }}}>{{{ Lang::get('subject_offered.no') }}}</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	
</div>

 @section('scripts')
<script type="text/javascript">
    $(function() {
        $("#classification_id").change(function(){

            selectListChange('program_id','{{{URL::to("program/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Program')
            selectListChange('term_id','{{{URL::to("term/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Term')
            selectListChange('subject','{{{URL::to("subject/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Subject')


        });
    });
    $(function() {
		$("#subject").select2()
	});
</script>

@stop


