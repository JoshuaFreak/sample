    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('student_name') ? 'has-error' : '' }}}">
              <label class="col-md-3 control-label" for="student_name">{!! Lang::get('transferring_grade_average.student_name') !!}</label>
              <div class="col-md-6">
                @if($action == 1)     
                  <input type="hidden" name="student_id" id="student_id" value="{{{ Input::old('student_id', isset($student_curriculum) ? $student_curriculum->student_id : null) }}}" />
                  <input class="typeahead form-control" type="text" name="student_name" id="student_name" value="{{{ Input::old('last_name', isset($person) ? $person->last_name : null) }}}, {{{ Input::old('first_name', isset($person) ? $person->first_name : null) }}} {{{ Input::old('middle_name', isset($person) ? $person->middle_name : null) }}}" />
                  {!! $errors->first('student_name', '<label class="control-label" for="student_name">:message</label>')!!}
                @else
                @endif
              </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('payment_scheme_id') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="payment_scheme_id">{{ Lang::get('student.pament_scheme') }}</label>
                <div class="col-md-6">
                    <select class="form-control" name="payment_scheme_id" id="payment_scheme_id" tabindex="4">
                    @if($action == 1)
                        @foreach($payment_scheme_list as $payment_scheme)
                            @if($payment_scheme->id == $enrollment->payment_scheme_id)
                            <option name="payment_scheme_id" value="{{{ $payment_scheme->id }}}" selected>{{{ $payment_scheme->payment_scheme_name }}}</option>
                            @else
                            <option name="payment_scheme_id" value="{{{ $payment_scheme->id }}}">{{{ $payment_scheme->payment_scheme_name }}}</option>
                            @endif
                        @endforeach
                    @else
                            <option name="" value="" selected></option>
                            @foreach($payment_scheme_list as $payment_scheme)
                                <option name="payment_scheme_id" value="{{{ $payment_scheme->id }}}" >{{{ $payment_scheme->payment_scheme_name }}}</option>
                            @endforeach
                    @endif
                    </select>
                    {!! $errors->first('payment_scheme_id', '<label class="control-label" for="payment_scheme_id">:message</label>')!!}
                </div>  
            </div>  
        </div>  
    </div>  