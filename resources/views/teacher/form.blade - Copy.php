<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
 <div class="form-group {{{ $errors->has('employee_name') ? 'has-error' : '' }}}">
        <label class="col-md-2 control-label" for="employee_name">{{ Lang::get('teacher.teacher') }}</label>
        <div class="col-md-4">
            <input type="text" class="typeahead form-control" id="employee_name_typeahead" name="employee_name_typeahead" value="{{{ Input::old('employee_name', isset($employee) ? $employee->employee_name : null) }}}">
            {!! $errors->first('employee_name', '<label class="control-label" for="employee_name">:message</label>')!!}
            <input type="hidden" id="employee_id" name="employee_id" value="{{{ Input::old('employee_id', isset($employee) ? $employee->employee_id : null) }}}">
        </div>
</div>
<div class="form-group {{{ $errors->has('degree') ? 'has-error' : '' }}}">
    <label class="col-md-2 control-label" for="degree">{{ Lang::get('teacher.degree') }}</label>
    <div class="col-md-4">
        <select name="degree[]" id="degree" multiple style="width:100%;">
            @foreach ($degree_list as $degree)
            <option value="{{{ $degree->id }}}"{{{ ( array_search($degree->degree_id, $teacher_list ) !== false && array_search($degree->degree_id, $teacher_list ) >= 0 ? ' selected="selected"' : '') }}}>{{{ $degree->description }}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group {{{ $errors->has('employee_category_id') ? 'has-error' : '' }}}">
        <label class="col-md-2 control-label" for="employee_category_id">{{ Lang::get('teacher.employee_category') }}</label>
       <div class="col-md-4">
        <select class="form-control" name="employee_category_id" id="employee_category_id" tabindex="4">
        @if($action == 1)
            @foreach($employee_category_list as $employee_category)
                @if($employee_category->id == $teacher_category->employee_category_id)
                <option name="employee_category_id" value="{{{ $employee_category->id }}}" selected>{{{ $employee_category->description }}}</option>
                @else
                <option name="employee_category_id" value="{{{ $employee_category->id }}}">{{{ $employee_category->description }}}</option>
                @endif
            @endforeach
        @else
            <option name="" value="" selected></option>
            @foreach($employee_category_list as $employee_category)
                <option name="employee_category_id" value="{{{ $employee_category->id }}}" >{{{ $employee_category->description }}}</option>

            @endforeach
        @endif
        </select>
        {!! $errors->first('employee_category_id', '<label class="control-label" for="employee_category_id">:message</label>')!!}
    </div>  
</div> 
<div class="form-group {{{ $errors->has('classification_id') ? 'has-error' : '' }}}">
        <label class="col-md-2 control-label" for="classification_id">{{ Lang::get('teacher.classification') }}</label>
       <div class="col-md-4">
        <select class="form-control" name="classification_id" id="classification_id" tabindex="4">
        @if($action == 1)
            @foreach($classification_list as $classification)
                @if($classification->id == $teacher_classification->classification_id)
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
@if($action == 1)
    @foreach($classification_list as $classification)
        @if($classification->id == $teacher->classification_id)
            @if($teacher->classification_id == 5)
                <div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
                    <label class="col-md-2 control-label"for="program_id">{{ Lang::get('teacher.program') }}</label>
                    <div class="col-md-4">
                        <select class="form-control" name="program_id" id="program_id" tabindex="4">
                        @if($action == 1)
                            @foreach($program_list as $program)
                                @if($program->id == $teacher->program_id)
                                <option name="program_id" value="{{{ $program->id }}}" selected>{{{ $program->program_name }}}</option>
                                @else
                                <option name="program_id" value="{{{ $program->id }}}">{{{ $program->program_name }}}</option>
                                @endif
                            @endforeach
                        @else
                            <option name="" value="" selected></option>
                            @foreach($program_list as $program)
                                <option name="program_id" value="{{{ $program->id }}}" >{{{ $program->program_name }}}</option>

                            @endforeach
                        @endif
                        </select>
                        {!! $errors->first('program_id', '<label class="control-label" for="program_id">:message</label>')!!}
                    </div>  
                </div>
            @endif
        @endif
    @endforeach
@endif    
<div class="form-group" id="program_container">
</div>
<div class="form-group {{{ $errors->has('classification_level_id') ? 'has-error' : '' }}}">
    <label class="col-md-2 control-label"for="classification_level_id">{{ Lang::get('teacher.classification_level') }}</label>
    <div class="col-md-4">
        <select class="form-control" name="classification_level_id" id="classification_level_id" tabindex="4">
        </select>
        {!! $errors->first('classification_level_id', '<label class="control-label" for="classification_level_id">:message</label>')!!}
    </div>  
</div> 
<div class="form-group {{{ $errors->has('subject') ? 'has-error' : '' }}}">
        <label class="col-md-2 control-label" for="subject">{{ Lang::get('teacher.subject_offered') }}</label>
        <div class="col-md-4">
            <select name="subject[]" id="subject" multiple style="width:100%;">
                @foreach ($subject_offered_list as $subject)
                <option value="{{{ $subject->id }}}"{{{ ( array_search($subject->subject_id, $teacher_list ) !== false && array_search($subject->subject_id, $teacher_list ) >= 0 ? ' selected="selected"' : '') }}}>{{{ $subject->name }}}</option>
                @endforeach
            </select>
        </div>
</div>

