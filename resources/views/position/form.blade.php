<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-9">
    <div class="form-group {{{ $errors->has('employee_type_id') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="employee_type_id">{{ Lang::get('position.employee_type') }}</label>
            <div class="col-md-9">
            @if($action == 1)
                <select class="form-control" name="employee_type_id" id="employee_type_id" tabindex="4">
                    <option value=""></option>
                    @foreach($employee_type_list as $employee_type)
                        @if($employee_type -> id == $position->employee_type_id)
                            <option value="{{ $employee_type -> id }}" selected="">{{ $employee_type -> employee_type_name }}</option>
                        @else
                            <option value="{{ $employee_type -> id }}">{{ $employee_type -> employee_type_name }}</option>
                        @endif
                    @endforeach
                </select>
            @else
                <select class="form-control" name="employee_type_id" id="employee_type_id" tabindex="4">
                    <option value=""></option>
                    @foreach($employee_type_list as $employee_type)
                        <option value="{{ $employee_type -> id }}">{{ $employee_type -> employee_type_name }}</option>
                    @endforeach
                </select>            
            @endif
            {!! $errors->first('employee_type_id', '<label class="control-label" for="employee_type_id">:message</label>')!!}
        </div>  
    </div>
</div>
<div class="col-md-9">
    <div class="form-group {{{ $errors->has('department_id') ? 'has-error' : '' }}}">
            <label class="col-md-3 control-label" for="department_id">{{ Lang::get('position.department_team') }}</label>
            <div class="col-md-9">
            @if($action == 1)
                <select class="form-control" name="department_id" id="department_id" tabindex="4">
                    <option value=""></option>
                    @foreach($department_list as $department)
                        @if($department -> id == $position->department_id)
                            <option value="{{ $department -> id }}" selected="">{{ $department -> department_name }}</option>
                        @else
                            <option value="{{ $department -> id }}">{{ $department -> department_name }}</option>
                        @endif
                    @endforeach
                </select>
            @else
                <select class="form-control" name="department_id" id="department_id" tabindex="4">
                    <option value=""></option>
                    @foreach($department_list as $department)
                        <option value="{{ $department -> id }}">{{ $department -> department_name }}</option>
                    @endforeach
                </select>
            @endif
            {!! $errors->first('department_id', '<label class="control-label" for="department_id">:message</label>')!!}
        </div>  
    </div>
</div>
<div class="col-md-9">
	<div class="form-group {{{ $errors->has('position_name') ? 'has-error' : '' }}}">
		<label class="col-md-3 control-label" for="position_name">{!! Lang::get('position.position_name') !!}</label>
		<div class="col-md-9">
			<input class="form-control" type="text" name="position_name" id="position_name" value="{{{ Input::old('position_name', isset($position) ? $position->position_name : null) }}}" />
			{!! $errors->first('position_name', '<label class="control-label" for="position_name">:message</label>')!!}

		</div>
	</div>
</div>


@section('scripts')
<script type="text/javascript">
</script>
@stop

