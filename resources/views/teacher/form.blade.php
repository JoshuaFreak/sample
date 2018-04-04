
<div class="form-group {{{ $errors->has('employee_name') ? 'has-error' : '' }}}">
<label class="col-md-3 control-label" for="employee_name">{{ Lang::get('teacher.teacher_search') }}</label>
<div class="col-md-3">
   <input type="text" class="typeahead form-control" id="employee_name" name="employee_name" value="{{{ Input::old('employee_name', isset($employee) ? $employee->employee_name : null) }}}">
   {!! $errors->first('employee_name', '<label class="control-label" for="employee_name">:message</label>')!!}
   <input type="hidden" id="employee_id" name="employee_id" value="{{{ Input::old('employee_id', isset($employee) ? $employee->employee_id : null) }}}">
</div>
</div>

<div class="form-group {{{ $errors->has('default_program_id') ? 'has-error' : '' }}}">
   <label class="control-label col-md-3">{{ Lang::get('teacher.default_program') }}</label>
   <div class="col-md-3">
      <select class="form-control" id="default_program_id" name="default_program_id">
         <option></option>
         @foreach($program_list as $program)
            <option class="form-control" id="default_program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
         @endforeach
      </select>
   </div>
</div>

<div class="form-group {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
   <label class="control-label col-md-3">{{ Lang::get('program.program') }}</label>
   <div class="col-md-3">
      <select name="program_id[]" id="program_id" multiple style="width: 100%;">
         <option></option>
         @foreach($program_list as $program)
            <option id="program_id" value="{{ $program -> id }}">{{ $program -> program_name }}</option>
         @endforeach
      </select>
   </div>
</div>