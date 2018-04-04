    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
        <div class="col-md-7">
            <label for="date">{{ Lang::get('student.date') }}:</label>
            {{ date("M d, Y (l) h:i A") }}
        </div>
    </div>
        <br>
        <br>
    <div class = 'col-md-12'>
        <div class="col-md-4">
            <div class="form-group {{{ $errors->has('student_no') ? 'has-error' : '' }}}">
                <label for="student_no">{{ Lang::get('student.student_no') }}</label>
                @if($action == 1)
                    <input type="text" class="form-control" id="student_no" name="student_no" value="{{{ Input::old('student_no', isset($student) ? $student->student_no : null) }}}">
                    {!! $errors->first('student_no', '<label class="control-label" for="student_no">:message</label>')!!}
                @else
                    <input type="text" class="form-control" id="student_no" name="student_no" value="">
                    {!! $errors->first('student_no', '<label class="control-label" for="student_no">:message</label>')!!}          
                @endif
            </div>
        </div>
     </div>
     <div class = "col-md-12">
        <div class="col-md-4">
            <div class="form-group col-md-12 {{{ $errors->has('classification_id') ? 'has-error' : '' }}}">
                <label for="classification_id">{{ Lang::get('registrar.classification') }}</label>
                <select class="form-control" name="classification_id" id="classification_id" tabindex="4">
                @if($action == 1)
                    @foreach($classification_list as $classification)
                        @if($classification->id == $student->classification_id)
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

        <div class="col-md-4">
            <div class="form-group col-md-12 {{{ $errors->has('program_id') ? 'has-error' : '' }}}">
                <label for="program_id">{{ Lang::get('registrar.program') }}</label>
                <select class="form-control" name="program_id" id="program_id" tabindex="4">
                @if($action == 1)
                    @foreach($program_list as $program)
                        @if($program->id == $curriculum->program_id)
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

         <div class="col-md-4">
            <div class="form-group col-md-12 {{{ $errors->has('curriculum_id') ? 'has-error' : '' }}}">
                <label for="curriculum_id">{{ Lang::get('registrar.curriculum') }}</label>
                <select class="form-control" name="curriculum_id" id="curriculum_id" tabindex="4">
                @if($action == 1)
                    @foreach($curriculum_subject_list as $curriculum_subject)
                        @if($curriculum_subject->id == $student_curriculum->curriculum_id)
                        <option name="curriculum_id" value="{{{ $curriculum_subject->id }}}" selected>{{{ $curriculum_subject->curriculum_name }}}</option>
                        @else
                        <option name="curriculum_id" value="{{{ $curriculum_subject->id }}}">{{{ $curriculum_subject->curriculum_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($curriculum_subject_list as $curriculum_subject)
                        <option name="curriculum_id" value="{{{ $curriculum_subject->id }}}" >{{{ $curriculum_subject->curriculum_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('curriculum_id', '<label class="control-label" for="curriculum_id">:message</label>')!!}
            </div>  
        </div>
   </div>
    <div class="col-md-8"></div>
    <div class="col-md-12">
        <h3><b style="color:#008cba">{{ Lang::get('student.personal_data') }}</b></h3>
    </div>
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
                <label for="last_name">{{ Lang::get('student.last_name') }}</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{{ Input::old('last_name', isset($person) ? $person->last_name : null) }}}">
                {!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
                <label for="first_name">{{ Lang::get('student.first_name') }}</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{{ Input::old('first_name', isset($person) ? $person->first_name : null) }}}">
                {!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group {{{ $errors->has('middle_name') ? 'has-error' : '' }}}">
                <label for="middle_name">{{ Lang::get('student.middle_name') }}</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{{ Input::old('middle_name', isset($person) ? $person->middle_name : null) }}}">
                {!! $errors->first('middle_name', '<label class="control-label" for="middle_name">:message</label>')!!}
            </div>
        </div>
         <div class="col-md-2">
            <div class="form-group col-md-12 {{{ $errors->has('suffix_id') ? 'has-error' : '' }}}">
                <label for="suffix_id">{{ Lang::get('student.suffix_name') }}</label>
                <select class="form-control" name="suffix_id" id="suffix_id" tabindex="4">
                @if($action == 1)
                    @foreach($suffix_list as $suffix)
                        @if($suffix->id == $person->suffix_id)
                        <option name="suffix_id" value="{{{ $suffix->id }}}" selected>{{{ $suffix->suffix_name }}}</option>
                        @else
                        <option name="suffix_id" value="{{{ $suffix->id }}}">{{{ $suffix->suffix_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($suffix_list as $suffix)
                        <option name="suffix_id" value="{{{ $suffix->id }}}" >{{{ $suffix->suffix_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('suffix_id', '<label class="control-label" for="suffix_id">:message</label>')!!}
            </div>  
        </div>
        <div class="col-md-2">
            <div class="form-group col-md-12 {{{ $errors->has('gender_id') ? 'has-error' : '' }}}">
                <label for="gender_id">{{ Lang::get('student.gender') }}</label>
                <select class="form-control" name="gender_id" id="gender_id" tabindex="4">
                @if($action == 1)
                    @foreach($gender_list as $gender)
                        @if($gender->id == $person->gender_id)
                        <option name="gender_id" value="{{{ $gender->id }}}" selected>{{{ $gender->gender_name }}}</option>
                        @else
                        <option name="gender_id" value="{{{ $gender->id }}}">{{{ $gender->gender_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($gender_list as $gender)
                        <option name="gender_id" value="{{{ $gender->id }}}" >{{{ $gender->gender_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('gender_id', '<label class="control-label" for="gender_id">:message</label>')!!}
            </div>  
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('birth_place') ? 'has-error' : '' }}}">
                <label for="birth_place">{{ Lang::get('student.birth_place') }}</label>
                <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{{ Input::old('birth_place', isset($person) ? $person->birth_place : null) }}}">
                {!! $errors->first('birth_place', '<label class="control-label" for="birth_place">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('birthdate') ? 'has-error' : '' }}}">
                <label for="birthdate">{{ Lang::get('student.birthdate') }}</label>
                <input type="text" class="form-control" placeholder="{{ Lang::get('student.date_birth') }}" id="birthdate" name="birthdate" value="{{{ Input::old('birthdate', isset($person) ? $person->birthdate : null) }}}">
                {!! $errors->first('birthdate', '<label class="control-label" for="birthdate">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('age') ? 'has-error' : '' }}}">
                <label for="age" class="col-md-4">{{ Lang::get('student.age') }}:</label>
                <input type="text" readOnly class="form-control" placeholder="{{ Lang::get('student.age') }}" id="age" name="age" value="{{{ date("Y") - date('Y',strtotime($person->birthdate))}}} yrs. old">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('address') ? 'has-error' : '' }}}">
                <label for="address">{{ Lang::get('student.address') }}</label>
                <input type="text" class="form-control" id="address" name="address" value="{{{ Input::old('address', isset($person) ? $person->address : null) }}}">
                {!! $errors->first('address', '<label class="control-label" for="address">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('contact_no') ? 'has-error' : '' }}}">
                <label for="contact_no">{{ Lang::get('student.contact_no') }}</label>
                <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{{ Input::old('contact_no', isset($person) ? $person->contact_no : null) }}}">
                {!! $errors->first('contact_no', '<label class="control-label" for="contact_no">:message</label>')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group col-md-12 {{{ $errors->has('citizenship_id') ? 'has-error' : '' }}}">
                <label for="citizenship_id">{{ Lang::get('student.citizenship') }}</label>
                <select class="form-control" name="citizenship_id" id="citizenship_id" tabindex="4">
                @if($action == 1)
                    @foreach($citizenship_list as $citizenship)
                        @if($citizenship->id == $person->citizenship_id)
                        <option name="citizenship_id" value="{{{ $citizenship->id }}}" selected>{{{ $citizenship->citizenship_name }}}</option>
                        @else
                        <option name="citizenship_id" value="{{{ $citizenship->id }}}">{{{ $citizenship->citizenship_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($citizenship_list as $citizenship)
                        <option name="citizenship_id" value="{{{ $citizenship->id }}}" >{{{ $citizenship->citizenship_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('citizenship_id', '<label class="control-label" for="citizenship_id">:message</label>')!!}
            </div>  
        </div>
        <div class="col-md-3">
            <div class="form-group col-md-12 {{{ $errors->has('religion_id') ? 'has-error' : '' }}}">
                <label for="religion_id">{{ Lang::get('student.religion') }}</label>
                <select class="form-control" name="religion_id" id="religion_id" tabindex="4">
                @if($action == 1)
                    @foreach($religion_list as $religion)
                        @if($religion->id == $person->religion_id)
                        <option name="religion_id" value="{{{ $religion->id }}}" selected>{{{ $religion->religion_name }}}</option>
                        @else
                        <option name="religion_id" value="{{{ $religion->id }}}">{{{ $religion->religion_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($religion_list as $religion)
                        <option name="religion_id" value="{{{ $religion->id }}}" >{{{ $religion->religion_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('religion_id', '<label class="control-label" for="religion_id">:message</label>')!!}
            </div>  
        </div>
        <div class="col-md-3">
            <div class="form-group col-md-12 {{{ $errors->has('civil_status_id') ? 'has-error' : '' }}}">
                <label for="civil_status_id">{{ Lang::get('student.civil_status') }}</label>
                <select class="form-control" name="civil_status_id" id="civil_status_id" tabindex="4">
                @if($action == 1)
                    @foreach($civil_status_list as $civil_status)
                        @if($civil_status->id == $person->civil_status_id)
                        <option name="civil_status_id" value="{{{ $civil_status->id }}}" selected>{{{ $civil_status->civil_status_name }}}</option>
                        @else
                        <option name="civil_status_id" value="{{{ $civil_status->id }}}">{{{ $civil_status->civil_status_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($civil_status_list as $civil_status)
                        <option name="civil_status_id" value="{{{ $civil_status->id }}}" >{{{ $civil_status->civil_status_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('civil_status_id', '<label class="control-label" for="civil_status_id">:message</label>')!!}
            </div>  
        </div>
        <div class="col-md-3">
            <div class="form-group col-md-12 {{{ $errors->has('blood_type_id') ? 'has-error' : '' }}}">
                <label for="blood_type_id">{{ Lang::get('student.blood_type') }}</label>
                <select class="form-control" name="blood_type_id" id="blood_type_id" tabindex="4">
                @if($action == 1)
                    @foreach($blood_type_list as $blood_type)
                        @if($blood_type->id == $person->blood_type_id)
                        <option name="blood_type_id" value="{{{ $blood_type->id }}}" selected>{{{ $blood_type->blood_type_name }}}</option>
                        @else
                        <option name="blood_type_id" value="{{{ $blood_type->id }}}">{{{ $blood_type->blood_type_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($blood_type_list as $blood_type)
                        <option name="blood_type_id" value="{{{ $blood_type->id }}}" >{{{ $blood_type->blood_type_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('blood_type_id', '<label class="control-label" for="blood_type_id">:message</label>')!!}
            </div>  
        </div>
    </div>
    
    <div class="col-md-12">
        <h4><b style="color:#008cba">{{ Lang::get('student.in_case_of_emergency') }}</b></h4>
    </div>
    <div class="col-md-12">
        @if($action == 1)
        <input type="hidden" id="guardian_id" name="guardian_id" value="{{{ $student->guardian_id }}}"/>
        @endif
        <div class="col-md-4">
            <div class="form-group {{{ $errors->has('guardian_last_name') ? 'has-error' : '' }}}">
                <label for="guardian_last_name">{{ Lang::get('student.guardian_last_name') }}</label>
                <input type="text" class="form-control" id="guardian_last_name" name="guardian_last_name" value="{{{ Input::old('last_name', isset($guardian_person) ? $guardian_person->last_name : null) }}}">
                {!! $errors->first('guardian_last_name', '<label class="control-label" for="guardian_last_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group {{{ $errors->has('guardian_first_name') ? 'has-error' : '' }}}">
                <label for="guardian_first_name">{{ Lang::get('student.guardian_first_name') }}</label>
                <input type="text" class="form-control" id="guardian_first_name" name="guardian_first_name" value="{{{ Input::old('first_name', isset($guardian_person) ? $guardian_person->first_name : null) }}}">
                {!! $errors->first('guardian_first_name', '<label class="control-label" for="guardian_first_name">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group {{{ $errors->has('guardian_middle_name') ? 'has-error' : '' }}}">
                <label for="guardian_middle_name">{{ Lang::get('student.guardian_middle_name') }}</label>
                <input type="text" class="form-control" id="guardian_middle_name" name="guardian_middle_name" value="{{{ Input::old('middle_name', isset($guardian_person) ? $guardian_person->middle_name : null) }}}">
                {!! $errors->first('guardian_middle_name', '<label class="control-label" for="guardian_middle_name">:message</label>')!!}
            </div>  
        </div>
         <div class="col-md-2">
            <div class="form-group col-md-12 {{{ $errors->has('guardian_suffix_id') ? 'has-error' : '' }}}">
                <label for="guardian_suffix_id">{{ Lang::get('student.suffix_name') }}</label>
                <select class="form-control" name="guardian_suffix_id" id="guardian_suffix_id" tabindex="4">
                @if($action == 1)
                    @foreach($suffix_list as $suffix)
                        @if($suffix->id == $guardian_person->suffix_id)
                        <option name="guardian_suffix_id" value="{{{ $suffix->id }}}" selected>{{{ $suffix->suffix_name }}}</option>
                        @else
                        <option name="guardian_suffix_id" value="{{{ $suffix->id }}}">{{{ $suffix->suffix_name }}}</option>
                        @endif
                    @endforeach
                @else
                    <option name="" value="" selected></option>
                    @foreach($suffix_list as $suffix)
                        <option name="guardian_suffix_id" value="{{{ $suffix->id }}}" >{{{ $suffix->suffix_name }}}</option>

                    @endforeach
                @endif
                </select>
                {!! $errors->first('guardian_suffix_id', '<label class="control-label" for="guardian_suffix_id">:message</label>')!!}
            </div>  
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('guardian_contact') ? 'has-error' : '' }}}">
                <label for="guardian_contact">{{ Lang::get('student.guardian_contact_no') }}</label>
                <input type="text" class="form-control" id="guardian_contact" name="guardian_contact" value="{{{ Input::old('contact_no', isset($guardian_person) ? $guardian_person->contact_no : null) }}}">
                {!! $errors->first('guardian_contact', '<label class="control-label" for="guardian_contact">:message</label>')!!}
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group {{{ $errors->has('guardian_address') ? 'has-error' : '' }}}">
                <label for="guardian_address">{{ Lang::get('student.guardian_address') }}</label>
                <input type="text" class="form-control" id="guardian_address" name="guardian_address" value="{{{ Input::old('address', isset($guardian_person) ? $guardian_person->address : null) }}}">
                {!! $errors->first('guardian_address', '<label class="control-label" for="guardian_address">:message</label>')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <br/><br/>
    </div>


 @section('scripts')
<script type="text/javascript">
  $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
    $(function() {
        $("#classification_id").change(function(){

            selectListChange('curriculum_id','{{{URL::to("curriculum_subject/dataJsonCurriculum")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Curriculum')
            selectListChange('program_id','{{{URL::to("program/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Program')


        });
    });
</script>

@stop

  