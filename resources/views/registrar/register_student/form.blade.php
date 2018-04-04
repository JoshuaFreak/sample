    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="col-md-12">
        <div class="col-md-6">
            <label for="date">{{ Lang::get('student.date') }}:</label>
            {{ date("M d, Y (l) h:i A") }}
        </div> 
    </div>
        <br>
        <br>
    <div class = 'col-md-12'>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('school_no') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="school_no">{!! Lang::get('student.school_no') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="school_no" id="school_no" value="{{{ Input::old('school_no', isset($student) ? $student->school_no : null) }}}" />
                    {!! $errors->first('school_no', '<label class="control-label" for="school_no">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('student_no') ? 'has-error' : '' }}}">
                <label class="col-md-3"  for="student_no">{{ Lang::get('student.student_no') }}</label>
                <div class="col-md-9">
                        <input type="text" class="form-control" id="student_no" name="student_no" value="{{{ Input::old('student_no', isset($student) ? $student->student_no : null) }}}">
                        {!! $errors->first('student_no', '<label class="control-label" for="student_no">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group {{{ $errors->has('lrn_no') ? 'has-error' : '' }}}">
                <label class="col-md-3"  for="lrn_no">{{ Lang::get('student.lrn_no') }}</label>
                <div class="col-md-9">
                        <input type="text" class="form-control" id="lrn_no" name="lrn_no" value="{{{ Input::old('lrn_no', isset($student) ? $student->lrn_no : null) }}}">
                        {!! $errors->first('lrn_no', '<label class="control-label" for="lrn_no">:message</label>')!!}
                </div>
            </div>
        </div>
        <label class="col-md-2" for="sped">{{ Lang::get('registrar.sped') }}</label>
        <div class="col-md-2">
            <select class="form-control" name="sped" id="sped" tabindex="4">
            @if($action == 1 && $student_curriculum -> is_sped == 1)
                    <option name="sped" value="1" selected>{{ Lang::get('form.yes') }}</option>
                    <option name="sped" value="0">{{ Lang::get('form.no') }}</option>
            @else
                <option name="sped" value="0" selected="">{{ Lang::get('form.no') }}</option>
                <option name="sped" value="1" >{{ Lang::get('form.yes') }}</option>
            @endif
            </select>
            {!! $errors->first('sped', '<label class="control-label" for="sped">:message</label>')!!}
        </div> 
     </div> 
    <div class = "col-md-12">
        <div class="col-md-8">
            <div class="form-group {{{ $errors->has('classification_id') ? 'has-error' : '' }}}">
                <label class="col-md-3" for="classification_id">{{ Lang::get('registrar.classification') }}</label>
                <div class="col-md-4">
                    <select class="form-control" name="classification_id" id="classification_id" tabindex="4">
                    @if($action == 1)
                        @foreach($classification_list as $classification)
                            @if($classification -> id != 6)
                                @if($classification->id == $student_curriculum->classification_id)
                                <option name="classification_id" value="{{{ $classification->id }}}" selected>{{{ $classification->classification_name }}}</option>
                                @else
                                <option name="classification_id" value="{{{ $classification->id }}}">{{{ $classification->classification_name }}}</option>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <option name="" value="" selected></option>
                        @foreach($classification_list as $classification)
                            @if($classification -> id != 6)
                                <option name="classification_id" value="{{{ $classification->id }}}" >{{{ $classification->classification_name }}}</option>
                            @endif
                        @endforeach
                    @endif
                    </select>
                    {!! $errors->first('classification_id', '<label class="control-label" for="classification_id">:message</label>')!!}
                </div>
                <label class="col-md-2" for="student_type_id">{!! Lang::get('student.student_type') !!}</label>
                <div class="col-md-3">
                    <select class="form-control" name="student_type_id" id="student_type_id" tabindex="4">
                        @if($action==1) 
                            @foreach($student_type_list as $student_type)
                                @if($student_type->id == $student->student_type_id)
                                <option name="student_type_id" value="{{{ $student_type->id }}}" selected>{{{ $student_type->name  }}}</option>
                                @else
                                <option name="student_type_id" value="{{{ $student_type->id }}}" >{{{ $student_type->name  }}}</option>
                                @endif
                            @endforeach
                        @else
                                <option name="" value="" selected ></option>
                            @foreach($student_type_list as $student_type)
                                <option name="student_type_id" value="{{{ $student_type->id }}}" >{{{ $student_type->name  }}}</option>
                            @endforeach
                        @endif
                    </select>
                        {!! $errors->first('student_type_id', '<label class="control-label" for="student_type_id">:message</label>')!!}
                </div> 
            </div>  
        </div>
         <div class="col-md-4">
            <div class="form-group {{{ $errors->has('curriculum_id') ? 'has-error' : '' }}}">
                <label class="col-md-3" for="curriculum_id">{{ Lang::get('registrar.curriculum') }}</label>
                <div class ="col-md-9">
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
                        <option name="" value="" selected> --Please Select Classification First--</option>
                    @endif
                    </select>
                    {!! $errors->first('curriculum_id', '<label class="control-label" for="curriculum_id">:message</label>')!!}
                </div>  
            </div>  
        </div>
   </div>
    <div class="col-md-8"></div>
    <div class="col-md-12">
        <h4><b style="color:#2a3088">{{ Lang::get('student.personal_data') }}</b></h4>
    </div>
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group {{{ $errors->has('last_name') ? 'has-error' : '' }}}">
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{{ Input::old('last_name', isset($person) ? $person->last_name : null) }}}">
                {!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
               <center> <label for="last_name">{{ Lang::get('student.last_name') }}</label></center>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{{ Input::old('first_name', isset($person) ? $person->first_name : null) }}}">
                {!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
                <center><label for="first_name">{{ Lang::get('student.first_name') }}</label></center>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group {{{ $errors->has('middle_name') ? 'has-error' : '' }}}">
                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{{ Input::old('middle_name', isset($person) ? $person->middle_name : null) }}}">
                {!! $errors->first('middle_name', '<label class="control-label" for="middle_name">:message</label>')!!}
                <center><label for="middle_name">{{ Lang::get('student.middle_name') }}</label></center>
            </div>
        </div> 
         <div class="col-md-2">
            <div class="form-group{{{ $errors->has('suffix_id') ? 'has-error' : '' }}}">
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
                <center><label for="suffix_id">{{ Lang::get('student.suffix_name') }}</label></center>
            </div>  
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('address') ? 'has-error' : '' }}}">
                <input type="text" class="form-control" id="address" name="address" value="{{{ Input::old('address', isset($person) ? $person->address : null) }}}">
                {!! $errors->first('address', '<label class="control-label" for="address">:message</label>')!!}
                <center><label for="address">{{ Lang::get('student.address') }}</label></center>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group {{{ $errors->has('home_number') ? 'has-error' : '' }}}">
                <input type="text" class="form-control" id="home_number" name="home_number" value="{{{ Input::old('home_number', isset($person) ? $person->home_number : null) }}}">
                {!! $errors->first('home_number', '<label class="control-label" for="home_number">:message</label>')!!}
                <center><label for="home_number">{{ Lang::get('student.home_number') }}</label></center>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group col-md-12 {{{ $errors->has('gender_id') ? 'has-error' : '' }}}">
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
                <center><label for="gender_id">{{ Lang::get('student.gender') }}</label></center>
            </div> 
        </div> 
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('preferred_name') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="preferred_name">{!! Lang::get('student.preferred_name') !!}</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" name="preferred_name" id="preferred_name" value="{{{ Input::old('preferred_name', isset($person) ? $person->preferred_name : null) }}}" />
                    {!! $errors->first('preferred_name', '<label class="control-label" for="preferred_name">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('student_email_address') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="student_email_address">{!! Lang::get('student.student_email_address') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="student_email_address" id="student_email_address" value="{{{ Input::old('student_email_address', isset($person) ? $person->student_email_address : null) }}}" />
                    {!! $errors->first('student_email_address', '<label class="control-label" for="student_email_address">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('student_mobile_number') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="student_mobile_number">{!! Lang::get('student.student_mobile_number') !!}</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" name="student_mobile_number" id="student_mobile_number" value="{{{ Input::old('student_mobile_number', isset($person) ? $person->student_mobile_number : null) }}}" />
                    {!! $errors->first('student_mobile_number', '<label class="control-label" for="student_mobile_number">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('birthdate') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="birthdate">{!! Lang::get('student.birthdate') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="date" name="birthdate" id="birthdate" value="{{{ Input::old('birthdate', isset($person) ? $person->birthdate : null) }}}" />
                    {!! $errors->first('birthdate', '<label class="control-label" for="birthdate">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('church_affiliation') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="church_affiliation">{!! Lang::get('student.church_affiliation') !!}</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" name="church_affiliation" id="church_affiliation" value="{{{ Input::old('church_affiliation', isset($person) ? $person->church_affiliation : null) }}}" />
                    {!! $errors->first('church_affiliation', '<label class="control-label" for="church_affiliation">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('birth_place') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="birth_place">{!! Lang::get('student.birth_place') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="birth_place" id="birth_place" value="{{{ Input::old('birth_place', isset($person) ? $person->birth_place : null) }}}" />
                    {!! $errors->first('birth_place', '<label class="control-label" for="birth_place">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('religion_id') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="religion_id">{{ Lang::get('student.religion') }}</label>
                <div class="col-md-8">
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
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('citizenship_id') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="citizenship_id">{{ Lang::get('student.citizenship') }}</label>
                <div class="col-md-9">
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
        </div>
    </div>
    <div class="form-group" id="international_student_container">
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('living_with_id') ? 'has-error' : '' }}}">
                <label class="col-md-4" for="living_with_id">{!! Lang::get('student.living_with') !!}</label>
                <div class="col-md-8">
                    <select class="form-control" name="living_with_id" id="living_with_id" tabindex="4">
                        @if($action==1) 
                            @foreach($living_with_list as $living_with)
                                @if($living_with->id == $person->living_with_id)
                                <option name="living_with_id" value="{{{ $living_with->id }}}" selected>{{{ $living_with->name  }}}</option>
                                @else
                                <option name="living_with_id" value="{{{ $living_with->id }}}" >{{{ $living_with->name  }}}</option>
                                @endif
                            @endforeach
                        @else
                                <option name="" value="" selected ></option>
                            @foreach($living_with_list as $living_with)
                                <option name="living_with_id" value="{{{ $living_with->id }}}" >{{{ $living_with->name  }}}</option>
                            @endforeach
                        @endif
                    </select>
                        {!! $errors->first('living_with_id', '<label class="control-label" for="living_with_id">:message</label>')!!}
                </div>
            </div>
        </div>
        <div class="col-md-6" id="living_with_container">
        </div>
<!--         @if($action==1) 
            <div class="col-md-6">
                <div class="form-group {{{ $errors->has('name_relation') ? 'has-error' : '' }}}">
                    <label class="col-md-3 control-label" for="name_relation">{!! Lang::get('student.name_relation') !!}</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" name="name_relation" id="name_relation" value="{{{ Input::old('name_relation', isset($person) ? $person->name_relation : null) }}}" />
                        {!! $errors->first('name_relation', '<label class="control-label" for="name_relation">:message</label>')!!}

                    </div>
                </div>
            </div>
        @endif -->
    </div>
   <!--  <div class="col-md-8"></div>
    <div class="col-md-12">
        <h4><b style="color:#2a3088">{{ Lang::get('student.academics') }}</b></h4>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('incoming_grade_level') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="incoming_grade_level">{{ Lang::get('student.incoming_grade_level') }}</label>
                <div class="col-md-8">
                    <select class="form-control" name="incoming_grade_level" id="incoming_grade_level" tabindex="4">
                    @if($action == 1)
                        @foreach($classification_level_list as $classification_level)
                            @if($classification_level->id == $student->classification_level_id)
                            <option name="classification_level_name" value="{{{ $classification_level->level }}}" selected>{{{ $classification_level->level }}}</option>
                            @else
                            <option name="classification_level_name" value="{{{ $classification_level->level }}}">{{{ $classification_level->level }}}</option>
                            @endif
                        @endforeach
                    @else
                        <option name="" value="" selected></option>
                        @foreach($classification_level_list as $classification_level)
                            <option name="classification_level_name" value="{{{ $classification_level->level }}}" >{{{ $classification_level->level }}}</option>

                        @endforeach
                    @endif
                    </select>
                    {!! $errors->first('incoming_grade_level', '<label class="control-label" for="incoming_grade_level">:message</label>')!!}
                </div>  
            </div> 
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('level_last_school_year') ? 'has-error' : '' }}}">
                <label class="col-md-4 control-label" for="level_last_school_year">{{ Lang::get('student.last_school_year') }}</label>
                <div class="col-md-8">
                    <select class="form-control" name="level_last_school_year" id="level_last_school_year" tabindex="4">
                    @if($action == 1)
                        @foreach($classification_level_list as $classification_level)
                            @if($classification_level->id == $student->classification_level_id)
                            <option name="classification_level_name" value="{{{ $classification_level->level }}}" selected>{{{ $classification_level->level }}}</option>
                            @else
                            <option name="classification_level_name" value="{{{ $classification_level->level }}}">{{{ $classification_level->level }}}</option>
                            @endif
                        @endforeach
                    @else
                        <option name="" value="" selected></option>
                        @foreach($classification_level_list as $classification_level)
                            <option name="classification_level_name" value="{{{ $classification_level->level }}}" >{{{ $classification_level->level }}}</option>

                        @endforeach
                    @endif
                    </select>
                    {!! $errors->first('level_last_school_year', '<label class="control-label" for="level_last_school_year">:message</label>')!!}
                </div>  
            </div> 
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-12">
            <div class="form-group {{{ $errors->has('last_school_attended') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="last_school_attended">{!! Lang::get('student.last_school_attended') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="last_school_attended" id="last_school_attended" value="{{{ Input::old('last_school_attended', isset($person) ? $person->last_school_attended : null) }}}" />
                    {!! $errors->first('last_school_attended', '<label class="control-label" for="last_school_attended">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="col-md-12 control-label"class="form-group {{{ $errors->has('academic_distinction') ? 'has-error' : '' }}}">
                <label for="academic_distinction">Briefly list any academic distinction or donors you have received in the last school year.</label>
                <textarea class="form-control" rows="3" id="academic_distinction" name="academic_distinction" value="{{{ Input::old('academic_distinction', isset($person) ? $person->academic_distinction : null) }}}">{{{ Input::old('academic_distinction', isset($person) ? $person->academic_distinction : null) }}}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12 control-label"class="form-group {{{ $errors->has('extra_curricular') ? 'has-error' : '' }}}">
                <label for="extra_curricular">{!! Lang::get('student.extra_curricular') !!}:</label>
                <textarea class="form-control" rows="4" id="extra_curricular" name="extra_curricular" value="{{{ Input::old('extra_curricular', isset($person) ? $person->extra_curricular : null) }}}">{{{ Input::old('extra_curricular', isset($person) ? $person->extra_curricular : null) }}}</textarea>
            </div>
        </div>
    </div> -->
    <div class="col-md-8"></div>
  <!--   <div class="col-md-12">
        <h4><b style="color:#2a3088">{{ Lang::get('student.medical_information') }}</b></h4>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="col-md-12 control-label"class="form-group {{{ $errors->has('medical_condition') ? 'has-error' : '' }}}">
                <label for="medical_condition">Do you have any existing medical condition, if so please specify:</label>
                <textarea class="form-control" rows="3" id="medical_condition" name="medical_condition" value="{{{ Input::old('medical_condition', isset($person) ? $person->medical_condition : null) }}}">{{{ Input::old('medical_condition', isset($person) ? $person->medical_condition : null) }}}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12 control-label"class="form-group {{{ $errors->has('maintenance_medication') ? 'has-error' : '' }}}">
                <label for="maintenance_medication">Are you under any maintenance medication, if so please specify:</label>
                <textarea class="form-control" rows="3" id="maintenance_medication" name="maintenance_medication" value="{{{ Input::old('maintenance_medication', isset($person) ? $person->maintenance_medication : null) }}}">{{{ Input::old('maintenance_medication', isset($person) ? $person->maintenance_medication : null) }}}</textarea><br>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('personal_physician') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="personal_physician">{!! Lang::get('student.personal_physician') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="personal_physician" id="personal_physician" value="{{{ Input::old('personal_physician', isset($person) ? $person->personal_physician : null) }}}" />
                    {!! $errors->first('personal_physician', '<label class="control-label" for="personal_physician">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('physician_mobile_number') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="physician_mobile_number">{!! Lang::get('student.physician_mobile_number') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="physician_mobile_number" id="physician_mobile_number" value="{{{ Input::old('physician_mobile_number', isset($person) ? $person->physician_mobile_number : null) }}}" />
                    {!! $errors->first('physician_mobile_number', '<label class="control-label" for="physician_mobile_number">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('clinic_address') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="clinic_address">{!! Lang::get('student.clinic_address') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="clinic_address" id="clinic_address" value="{{{ Input::old('clinic_address', isset($person) ? $person->clinic_address : null) }}}" />
                    {!! $errors->first('clinic_address', '<label class="control-label" for="clinic_address">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('physician_office_number') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="physician_office_number">{!! Lang::get('student.physician_office_number') !!}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="physician_office_number" id="physician_office_number" value="{{{ Input::old('physician_office_number', isset($person) ? $person->physician_office_number : null) }}}" />
                    {!! $errors->first('physician_office_number', '<label class="control-label" for="physician_office_number">:message</label>')!!}

                </div>
            </div>
        </div>
    </div> -->
    <div class="col-md-8"></div>
    <div class="col-md-12">
        <h4><b style="color:#2a3088">{{ Lang::get('student.family_background') }}</b></h4>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_name') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="fathers_name">Father's Name</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="fathers_name" id="fathers_name" value="{{{ Input::old('fathers_name', isset($student_family_background) ? $student_family_background->fathers_name : null) }}}" />
                    {!! $errors->first('fathers_name', '<label class="control-label" for="fathers_name">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_name') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="mothers_name">Mother's Name</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="mothers_name" id="mothers_name" value="{{{ Input::old('mothers_name', isset($student_family_background) ? $student_family_background->mothers_name : null) }}}" />
                    {!! $errors->first('mothers_name', '<label class="control-label" for="mothers_name">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_mobile_number') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="fathers_mobile_number">{{ Lang::get('student.fathers_mobile_number') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="fathers_mobile_number" id="fathers_mobile_number" value="{{{ Input::old('fathers_mobile_number', isset($student_family_background) ? $student_family_background->fathers_mobile_number : null) }}}" />
                    {!! $errors->first('fathers_mobile_number', '<label class="control-label" for="fathers_mobile_number">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_mobile_number') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="mothers_mobile_number">{{ Lang::get('student.mothers_mobile_number') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="mothers_mobile_number" id="mothers_mobile_number" value="{{{ Input::old('mothers_mobile_number', isset($student_family_background) ? $student_family_background->mothers_mobile_number : null) }}}" />
                    {!! $errors->first('mothers_mobile_number', '<label class="control-label" for="mothers_mobile_number">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_occupation') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="fathers_occupation">{{ Lang::get('student.fathers_occupation') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="fathers_occupation" id="fathers_occupation" value="{{{ Input::old('fathers_occupation', isset($student_family_background) ? $student_family_background->fathers_occupation : null) }}}" />
                    {!! $errors->first('fathers_occupation', '<label class="control-label" for="fathers_occupation">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_occupation') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="mothers_occupation">{{ Lang::get('student.mothers_occupation') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="mothers_occupation" id="mothers_occupation" value="{{{ Input::old('mothers_occupation', isset($student_family_background) ? $student_family_background->mothers_occupation : null) }}}" />
                    {!! $errors->first('mothers_occupation', '<label class="control-label" for="mothers_occupation">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_employer_name') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="fathers_employer_name">Employer's Name</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="fathers_employer_name" id="fathers_employer_name" value="{{{ Input::old('fathers_employer_name', isset($student_family_background) ? $student_family_background->fathers_employer_name : null) }}}" />
                    {!! $errors->first('fathers_employer_name', '<label class="control-label" for="fathers_employer_name">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_employer_name') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="mothers_employer_name">Employer's Name</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="mothers_employer_name" id="mothers_employer_name" value="{{{ Input::old('mothers_employer_name', isset($student_family_background) ? $student_family_background->mothers_employer_name : null) }}}" />
                    {!! $errors->first('mothers_employer_name', '<label class="control-label" for="mothers_employer_name">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_employer_no') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="fathers_employer_no">{{ Lang::get('student.fathers_employer_no') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="fathers_employer_no" id="fathers_employer_no" value="{{{ Input::old('fathers_employer_no', isset($student_family_background) ? $student_family_background->fathers_employer_no : null) }}}" />
                    {!! $errors->first('fathers_employer_no', '<label class="control-label" for="fathers_employer_no">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_employer_no') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="mothers_employer_no">{{ Lang::get('student.mothers_employer_no') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="mothers_employer_no" id="mothers_employer_no" value="{{{ Input::old('mothers_employer_no', isset($student_family_background) ? $student_family_background->mothers_employer_no : null) }}}" />
                    {!! $errors->first('mothers_employer_no', '<label class="control-label" for="mothers_employer_no">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_email_address') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="fathers_email_address">{{ Lang::get('student.fathers_email_address') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="fathers_email_address" id="fathers_email_address" value="{{{ Input::old('fathers_email_address', isset($student_family_background) ? $student_family_background->fathers_email_address : null) }}}" />
                    {!! $errors->first('fathers_email_address', '<label class="control-label" for="fathers_email_address">:message</label>')!!}

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_email_address') ? 'has-error' : '' }}}">
                <label class="col-md-3 control-label" for="mothers_email_address">{{ Lang::get('student.mothers_email_address') }}</label>
                <div class="col-md-9">
                    <input class="form-control" type="text" name="mothers_email_address" id="mothers_email_address" value="{{{ Input::old('mothers_email_address', isset($student_family_background) ? $student_family_background->mothers_email_address : null) }}}" />
                    {!! $errors->first('mothers_email_address', '<label class="control-label" for="mothers_email_address">:message</label>')!!}

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('parents_marital_status_id') ? 'has-error' : '' }}}">
                <label class="col-md-3" for="parents_marital_status_id">{!! Lang::get('student.parents_marital_status') !!}</label>
                <div class="col-md-9">
                    <select class="form-control" name="parents_marital_status_id" id="parents_marital_status_id" tabindex="4">
                        @if($action==1) 
                            @foreach($parents_marital_status_list as $parents_marital_status)
                                @if($parents_marital_status->id == $student_family_background->parents_marital_status_id)
                                <option name="parents_marital_status_id" value="{{{ $parents_marital_status->id }}}" selected>{{{ $parents_marital_status->name  }}}</option>
                                @else
                                <option name="parents_marital_status_id" value="{{{ $parents_marital_status->id }}}" >{{{ $parents_marital_status->name  }}}</option>
                                @endif
                            @endforeach
                        @else
                                <option name="" value="" selected ></option>
                            @foreach($parents_marital_status_list as $parents_marital_status)
                                <option name="parents_marital_status_id" value="{{{ $parents_marital_status->id }}}" >{{{ $parents_marital_status->name  }}}</option>
                            @endforeach
                        @endif
                    </select>
                        {!! $errors->first('parents_marital_status_id', '<label class="control-label" for="parents_marital_status_id">:message</label>')!!}
                </div>
            </div>
        </div>
    </div>
<!--     <div class="col-md-12">
        <div class="col-md-12">
            <label class="col-md-12 control-label" for="siblings">Please list the names and ages of your siblings with the current grade/ level and school/ university they are currently enrolled in:</label>
        </div>
    <table class="table table-bordered table-striped" id="student_sibling_table">
        <th style="width:25%"><center>Name</center></th>
        <th style="width:25%"><center>Age</center></th>
        <th style="width:25%"><center>Grade/Level</center></th>
        <th style="width:25%"><center>School/University</center></th>
        <tbody id="student_sibling_container">
        @if($action==1) 
            @foreach($student_siblings_list as $student_siblings)
            @if($student_siblings->student_id == $student_curriculum->student_id)
            <tr>
                <td>
                    <input type="hidden" id="student_siblings_id" name="student_siblings_id[]" value="{{{$student_siblings->id}}}" />
                    <input class="form-control" type="text" name="name[]" id="name" value="{{{ Input::old('name', isset($student_siblings) ? $student_siblings->name : null) }}}" />
                </td>
                <td>
                    <input class="form-control" type="text" name="age[]" id="age" value="{{{ Input::old('age', isset($student_siblings) ? $student_siblings->age : null) }}}" />
                </td>
                <td>
                    <input class="form-control" type="text" name="grade_level[]" id="grade_level" value="{{{ Input::old('grade_level', isset($student_siblings) ? $student_siblings->grade_level : null) }}}" />
                </td>
                <td>
                    <input class="form-control" type="text" name="school_university[]" id="school_university" value="{{{ Input::old('school_university', isset($student_siblings) ? $student_siblings->school_university : null) }}}" />
                </td>
            </tr>
            @endif
            @endforeach
        @endif
        </tbody>                    
    </table>
    <div class="pull-right">
        <button id="add_siblings_row" type="button" class="btn btn-sm btn-primary">
            <span class="glyphicon glyphicon-plus"></span> {{ Lang::get("form.add_row") }}
        </button>
        <button id="remove_siblings_row" type="button" class="btn btn-sm btn-warning">
            <span class="glyphicon glyphicon-remove-circle"></span> {{ Lang::get("form.remove_row") }}
        </button>
    </div>

    </div> -->
    <div class="col-md-12">
        <br/><br/>
    </div>



<div class="col-md-12 form-group">
    <br>
    <div class="col-md-8">
        <label class="control-label" for="brothers_sisters_name">Please list the names and ages of your siblings with the current grade/ level and school/ university they are currently enrolled in:</label>
    </div>

    <input type="hidden" id="counter" value="0" />
    <div class="col-md-4">
        <button type="button" data-toggle="modal" data-target="#add_relative_modal" class="btn btn-primary pull-right">
            <span class="glyphicon glyphicon-plus"></span>&nbsp;{{{ Lang::get('student.add') }}}
        </button>
    </div>
</div>

<div class="col-md-12 form-group">
    <div class="container-fluid">
        <table class="table">
            <tbody>
                <th>Action</th>
                <th>Names</th>
     
            </tbody>

            <tbody id="table_con_relative">
          
            </tbody>
        </table>
    </div>


      
 <!-- MODAL START -->
    <div class="modal fade bs-example-modal-lg" id="add_relative_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="submit" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Relative</h4>
                </div>

                <div class="modal-body">
                    <div class="aui-message aui-message-info">
                        <p>{{{ Lang::get('student.add_message') }}}</p>
                    </div>
                    <br>
          
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="relative_student_id" id="relative_student_id" value="">

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="complete_name_relative">Name: </label>
                            <div class="col-md-6">
                                <input class="typeahead form-control" type="text" name="complete_name_relative" id="complete_name_relative_typeahead" value="">
                            </div>
                        </div>

                        

                        <div class="modal-footer">
                            <button id="cancel" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button id="submit_add_relative" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add</button>
                        </div>
             
                </div>
                <br/>
            </div>
        </div>
    </div>
    <!-- MODAL END -->

<!-- <script id="student_brother_sister_container" type="application/html-template">
    <tr id="<<tr_id>>">
    
        <td><input type="text" readOnly name="name[]" class="form-control" value="<<data2>>"></td>
        <td><input type="text" readOnly name="age[]" class="form-control" value="<<data3>>"></td>

    </tr>
</script> -->
 @section('scripts')
<script id="relative_template" type="application/html-template">
    <tr id="<<tr_id>>">
        <td class="col-md-2"><button type="button" class="btn btn-danger" id="remove">Remove</button></td> 
        <td>
            <input type="hidden" name="relative_id[]" value="<<relativeid>>">
            <input type="text" name="name_relative[]" class="form-control" value="<<data2>>">
        </td>


        
    </tr>
</script>
<script type="text/javascript">
  $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
    $(function() {
        $("#classification_id").change(function(){
            selectListChange('curriculum_id','{{{URL::to("curriculum_subject/dataJsonRegistrar")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Program')
        });
    });

    // $("#add_siblings_row").click(function(){
    //   $("#student_sibling_container").append('<tr>'
    //                     +'<input type="hidden" id="student_siblings_id" name="student_siblings_id[]" value="0" />'
    //                     +'<td><input class="form-control" type="text" name="name[]" id="name" value="" /></td>'
    //                     +'<td><input class="form-control" type="text" name="age[]" id="age" value="" /></td>'
    //                     +'<td><input class="form-control" type="text" name="grade_level[]" id="grade_level" value="" /></td>'
    //                     +'<td><input class="form-control" type="text" name="school_university[]" id="school_university" value="" /></td>'
    //                     +'</tr>');
    // });
    // $("#remove_siblings_row").on('click', function(){
    //     $("#student_sibling_container tr:last-child").remove(); //or like this
    // });

    // $("#student_type_id").change(function(){

    //     var condition = $("#student_type_id option:selected").text();

    //     $("#international_student_container").empty();

    //     if(condition == "International")
    //     {
    //         $("#international_student_container").append(''
    //             +'<div class="col-md-12">'
    //                 +'<div class="col-md-6">'
    //                     +'<label class="col-md-4 control-label" for="passport_number">{!! Lang::get("student.passport_number") !!}</label>'
    //                     +'<div class="col-md-8">'
    //                        +' <input class="form-control" type="text" name="passport_number" id="passport_number" value="{{{ Input::old("passport_number", isset($person) ? $person->passport_number : null) }}}" />'
    //                     +'</div>'
    //                 +'</div>'
    //                 +'<div class="col-md-6">'
    //                     +'<label class="col-md-3 control-label" for="icard_number">{!! Lang::get("student.icard_number") !!}</label>'
    //                     +'<div class="col-md-9">'
    //                     +'<input class="form-control" type="text" name="icard_number" id="icard_number" value="{{{ Input::old("icard_number", isset($person) ? $person->icard_number : null) }}}" />'
    //                     +'</div>'
    //                +' </div>'
    //            +' </div>'
    //             +'');
    //     }
    // });

    // $("#living_with_id").change(function(){

    //     var condition = $("#living_with_id option:selected").text();

    //     $("#living_with_container").empty();

    //     if(condition == "Guardian")
    //     {
    //         $("#living_with_container").append(''
    //                 +'<div class="col-md-12">'
    //                     +'<label class="col-md-3 control-label" for="name_relation">{!! Lang::get("student.name_relation") !!}</label>'
    //                     +'<div class="col-md-9">'
    //                     +'<input class="form-control" type="text" name="name_relation" id="name_relation" value="{{{ Input::old("name_relation", isset($person) ? $person->name_relation : null) }}}" />'
    //                     +'</div>'
    //                +' </div>'
    //             +'');
    //     }
    // });

</script>
<script type="text/javascript">
    // $(function () {
    //     $('#datetimepicker1').datepicker();
    // });
    // $(function () {
    //     $('#datetimepicker2').datepicker();
    // });
    // $(function () {
    //     $('#datetimepicker3').datepicker();
    // });
    // $(function () {
    //     $('#wake_up').timepicker();
    // });
    // $(function () {
    //     $('#sleep').timepicker();
    // });

    // $(function () {
    //     $('#date_enrolled').datepicker({        
    //          "format": 'yyyy-mm-dd',

    //     });
    // });
    var count = 0;

      $('#add_relative_modal').on('shown.bs.modal',function(){

        count++;
        
        if(count == 1)
        {
         var student_relative_list = new Bloodhound({
                  datumTokenizer: function (datum) {
                     return Bloodhound.tokenizers.whitespace(datum.student_no);
                  },
                  queryTokenizer: Bloodhound.tokenizers.whitespace,
                  limit: 10,
                  remote: {
                     // dataJson
                     url:"{{URL::to('register_student/dataJsonRegisteredStudent?query=%QUERY')}}",

                     filter: function (student_relative_list) {

                        return $.map(student_relative_list, function (student_relative) {
                           return {
                              complete_name_relative: student_relative.last_name + ' ' + student_relative.first_name+' '+student_relative.middle_name,
                              student_id: student_relative.student_id,
                    
                           };
                        });

                     }
                  }
               });

               student_relative_list.initialize();
               console.log(student_relative_list);

               $('#complete_name_relative_typeahead').typeahead(null, {
                     complete_name_relative: 'student_relative_list',
                     displayKey: 'complete_name_relative',
                     source: student_relative_list.ttAdapter()
               }).bind("typeahead:selected", function(obj, student_relative, complete_name_relative) {
                     console.log(student_relative);
                     $("#relative_student_id").val(student_relative.student_id);
               });
          // $("#grade_level_relative [value='"+0+"']").attr("selected","selected");
        }
      });







    $(function(){
        $("#submit_add_relative").click(function(){

            // $.ajax({
            //     type:'post',
            //     data:
            //         {  
            //             'name_relative': $("#name_relative").val(),
            //             'relative_relationship': $('#relative_relationship').val(),
            //             'level_grade': $('#level_grade').val(),
            //             'relative_age': $('#relative_age').val(),
            //         },
            //     async:false                         
            // }).done(function( data ) {
            
            // });


            // USE TO ADD NEW DATA IN USER INTERFACE
            var counter = $("#counter").val();
            counter = parseInt(counter) + parseInt(1);
            $("#counter").val(counter);

            var template = $("#relative_template").clone().html();
            var html = template
                .replace('<<data2>>', $("#complete_name_relative_typeahead").val())
                .replace('<<relativeid>>', $("#relative_student_id").val())
                .replace('<<tr_id>>','tr_'+counter);


        // alert($("#complete_name_relative_typeahead").val());

            $("#table_con_relative").append(html);

       
            $("#add_relative_modal").modal('hide');
            
            
        });

        $(document).on('click', 'button.btn-danger', function () { // <-- changes
            $(this).closest('tr').remove();
            return false;
        });
    });


</script>
@stop

