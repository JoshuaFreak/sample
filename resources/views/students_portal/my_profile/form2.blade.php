    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('student_no') ? 'has-error' : '' }}}">
                <label for="student_no">{{ Lang::get('student.student_no') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="student_no" name="student_no" value="{{{ Input::old('student_no', isset($student) ? $student->student_no : null) }}}">
                {!! $errors->first('student_no', '<label class="control-label" for="student_no">:message</label>')!!}
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <h4><b style="color:#008cba">{{ Lang::get('student.personal_data') }}</b></h4>
    </div>
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
                <label for="last_name">{{ Lang::get('student.last_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="last_name" name="last_name" value="{{{ Input::old('last_name', isset($student) ? $student->last_name : null) }}}">
                {!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
                <label for="first_name">{{ Lang::get('student.first_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="first_name" name="first_name" value="{{{ Input::old('first_name', isset($student) ? $student->first_name : null) }}}">
                {!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group {{{ $errors->has('middle_name') ? 'has-error' : '' }}}">
                <label for="middle_name">{{ Lang::get('student.middle_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="middle_name" name="middle_name" value="{{{ Input::old('middle_name', isset($student) ? $student->middle_name : null) }}}">
                {!! $errors->first('middle_name', '<label class="control-label" for="middle_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group {{{ $errors->has('suffix_name') ? 'has-error' : '' }}}">
                <label for="suffix_name">{{ Lang::get('student.suffix_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="suffix_name" name="suffix_name" value="{{{ Input::old('suffix_name', isset($student) ? $student->suffix_name : null) }}}">
                {!! $errors->first('suffix_name', '<label class="control-label" for="suffix_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-2">
             <div class="form-group {{{ $errors->has('gender_name') ? 'has-error' : '' }}}">
                <label for="gender_name">{{ Lang::get('student.gender') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="gender_name" name="gender_name" value="{{{ Input::old('gender_name', isset($student) ? $student->gender_name : null) }}}">
                {!! $errors->first('gender_name', '<label class="control-label" for="gender_name">:message</label>')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('birth_place') ? 'has-error' : '' }}}">
                <label for="birth_place">{{ Lang::get('student.birth_place') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="birth_place" name="birth_place" value="{{{ Input::old('birth_place', isset($student) ? $student->birth_place : null) }}}">
                {!! $errors->first('birth_place', '<label class="control-label" for="birth_place">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('birthdate') ? 'has-error' : '' }}}">
                <label for="birthdate">{{ Lang::get('student.birthdate') }}</label>
                <input type="text" readOnly class="typeahead form-control" placeholder="{{ Lang::get('student.date_birth') }}" id="birthdate" name="birthdate" value="{{{ Input::old('birthdate', isset($student) ? $student->birthdate : null) }}}">
                {!! $errors->first('birthdate', '<label class="control-label" for="birthdate">:message</label>')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('address') ? 'has-error' : '' }}}">
                <label for="address">{{ Lang::get('student.address') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="address" name="address" value="{{{ Input::old('address', isset($student) ? $student->address : null) }}}">
                {!! $errors->first('address', '<label class="control-label" for="address">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('contact_no') ? 'has-error' : '' }}}">
                <label for="contact_no">{{ Lang::get('student.contact_no') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="contact_no" name="contact_no" value="{{{ Input::old('contact_no', isset($student) ? $student->contact_no : null) }}}">
                {!! $errors->first('contact_no', '<label class="control-label" for="contact_no">:message</label>')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('citizenship_name') ? 'has-error' : '' }}}">
                <label for="citizenship_name">{{ Lang::get('student.citizenship') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="citizenship_name" name="citizenship_name" value="{{{ Input::old('citizenship_name', isset($student) ? $student->citizenship_name : null) }}}">
                {!! $errors->first('citizenship_name', '<label class="control-label" for="citizenship_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('religion_name') ? 'has-error' : '' }}}">
                <label for="religion_name">{{ Lang::get('student.religion') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="religion_name" name="religion_name" value="{{{ Input::old('religion_name', isset($student) ? $student->religion_name : null) }}}">
                {!! $errors->first('religion_name', '<label class="control-label" for="religion_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('civil_status_name') ? 'has-error' : '' }}}">
                <label for="civil_status_name">{{ Lang::get('student.civil_status') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="civil_status_name" name="civil_status_name" value="{{{ Input::old('civil_status_name', isset($student) ? $student->civil_status_name : null) }}}">
                {!! $errors->first('civil_status_name', '<label class="control-label" for="civil_status_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('blood_type_name') ? 'has-error' : '' }}}">
                <label for="blood_type_name">{{ Lang::get('student.blood_type') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="blood_type_name" name="blood_type_name" value="{{{ Input::old('blood_type_name', isset($student) ? $student->blood_type_name : null) }}}">
                {!! $errors->first('blood_type_name', '<label class="control-label" for="blood_type_name">:message</label>')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <h4><b style="color:#008cba">{{ Lang::get('student.in_case_of_emergency') }}</b></h4>
    </div>
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('guardian_last_name') ? 'has-error' : '' }}}">
                <label for="guardian_last_name">{{ Lang::get('student.guardian_last_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="guardian_last_name" name="guardian_last_name" value="{{{ Input::old('guardian_last_name', isset($student) ? $student->guardian_last_name : null) }}}">
                {!! $errors->first('guardian_last_name', '<label class="control-label" for="guardian_last_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group {{{ $errors->has('guardian_first_name') ? 'has-error' : '' }}}">
                <label for="guardian_first_name">{{ Lang::get('student.guardian_first_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="guardian_first_name" name="guardian_first_name" value="{{{ Input::old('guardian_first_name', isset($student) ? $student->guardian_first_name : null) }}}">
                {!! $errors->first('guardian_first_name', '<label class="control-label" for="guardian_first_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('guardian_middle_name') ? 'has-error' : '' }}}">
                <label for="guardian_middle_name">{{ Lang::get('student.guardian_middle_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="guardian_middle_name" name="guardian_middle_name" value="{{{ Input::old('guardian_middle_name', isset($student) ? $student->guardian_middle_name : null) }}}">
                {!! $errors->first('guardian_middle_name', '<label class="control-label" for="guardian_middle_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group {{{ $errors->has('guardian_suffix_name') ? 'has-error' : '' }}}">
                <label for="guardian_suffix_name">{{ Lang::get('student.suffix_name') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="guardian_suffix_name" name="guardian_suffix_name" value="{{{ Input::old('guardian_suffix_name', isset($student) ? $student->guardian_suffix_name : null) }}}">
                {!! $errors->first('guardian_suffix_name', '<label class="control-label" for="guardian_suffix_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('guardian_contact_no') ? 'has-error' : '' }}}">
                <label for="guardian_contact_no">{{ Lang::get('student.guardian_contact_no') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="guardian_contact_no" name="guardian_contact_no" value="{{{ Input::old('guardian_contact_no', isset($student) ? $student->guardian_contact_no : null) }}}">
                {!! $errors->first('guardian_contact_no', '<label class="control-label" for="guardian_contact_no">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group {{{ $errors->has('guardian_address') ? 'has-error' : '' }}}">
                <label for="guardian_address">{{ Lang::get('student.guardian_address') }}</label>
                <input type="text" readOnly class="typeahead form-control" id="guardian_address" name="guardian_address" value="{{{ Input::old('guardian_address', isset($student) ? $student->guardian_address : null) }}}">
                {!! $errors->first('guardian_address', '<label class="control-label" for="guardian_address">:message</label>')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12"><br><br></div>
