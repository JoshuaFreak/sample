@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("student.student_profile") }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<link rel="stylesheet" href="{{asset('assets/site/datepicker/ui/1.11.4/themes/smoothness/jquery-ui.css')}}">
<script src="{{asset('assets/site/datepicker/jquery-1.10.2.js')}}"></script>
<script src="{{asset('assets/site/datepicker/ui/1.11.4/jquery-ui.js')}}"></script>
<script>
    $(function() {
    $( "#birthdate" ).datepicker({
    	dateFormat: "yy-mm-dd",
      	changeMonth: true,
      	changeYear: true
    });
});
</script>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">    
        <ul class="nav" id="side-menu"> 
            @include('enrollment_sidebar')
        </ul>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
		<div class="page-header"><br>
			<h3> {{{ Lang::get("student.student_profile") }}}</h3>
		</div>
		<form class="form-horizontal" method="post" action="{{ URL::to('enroll_student/' . $enrollment->id . '/edit') }}" autocomplete="off">
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <div class="col-md-12">
                <div class="col-md-7">
                    <label for="date">{{ Lang::get('student.date') }}:</label>
                    {{ date("M d, Y (l) h:i A") }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('student_curriculum_id') ? 'has-error' : '' }}}">
                        <label for="student_no">{{ Lang::get('student.student_no') }}</label>
                        @if($action == 1)
                            <input type="text" readOnly class="typeahead form-control" id="registered_student_no_typeahead" name="registered_student_no_typeahead" value="{{{ Input::old('student_no', isset($student) ? $student->student_no : null) }}}">
                            {!! $errors->first('student_curriculum_id', '<label class="control-label" for="student_curriculum_id">:message</label>')!!}
                            <input type="hidden" id="student_curriculum_id" name="student_curriculum_id" value="{{{ Input::old('student_curriculum_id', isset($student) ? $student->student_curriculum_id : null) }}}">
                            <input type="hidden" id="student_id" name="student_id" value="{{{ Input::old('student_id', isset($student_curriculum) ? $student_curriculum->student_id : null) }}}">
                            <input type="hidden" id="curriculum_id" name="curriculum_id" value="{{{ Input::old('curriculum_id', isset($student_curriculum) ? $student_curriculum->curriculum_id : null) }}}">
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('student_type') ? 'has-error' : '' }}}">
                        <label class="control-label" for="student_type">{{ Lang::get('student.student_type') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_type" name="registered_student_type" value="{{{ Input::old('name', isset($student_type) ? $student_type->name : null) }}}">
                        {!! $errors->first('student_type', '<label class="control-label" for="student_type">:message</label>')!!}
                        <input type="hidden" id="student_type_id" name="student_type_id" value="{{{ Input::old('student_type_id', isset($student_type) ? $student_type->student_type_id : null) }}}">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('classification_name') ? 'has-error' : '' }}}">
                        <label class="control-label" for="classification_name">{{ Lang::get('student.classification_name') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_classification_name" name="registered_student_classification_name" value="{{{ Input::old('classification_name', isset($classification) ? $classification->classification_name : null) }}}">
                        {!! $errors->first('classification_name', '<label class="control-label" for="classification_name">:message</label>')!!}
                        <input type="hidden" id="classification_id" name="classification_id" value="{{{ Input::old('id', isset($classification) ? $classification->id : null) }}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('curriculum_name') ? 'has-error' : '' }}}">
                        <label for="curriculum_name">{{ Lang::get('student.curriculum_name') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_curriculum_name" name="registered_student_curriculum_name" value="{{{ Input::old('curriculum_name', isset($curriculum) ? $curriculum->curriculum_name : null) }}}">
                        {!! $errors->first('curriculum_name', '<label class="control-label" for="curriculum_name">:message</label>')!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('term_name') ? 'has-error' : '' }}}">
                        <label for="term_name">{{ Lang::get('student.school_year') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_term_name" name="registered_student_term_name" value="{{{ Input::old('term_name', isset($term) ? $term->term_name : null) }}}">
                        {!! $errors->first('term_name', '<label class="control-label" for="term_name">:message</label>')!!}
                    </div>
                </div>
            </div>
            <div class="col-md-8"></div>
            <div class="col-md-12">
                <h4><b style="color:#2a3088">{{ Lang::get('student.academics') }}</b></h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('classification_level_name') ? 'has-error' : '' }}}">
                        <label for="classification_level_name">{{ Lang::get('student.incoming_grade_level') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_classification_level_name" name="registered_student_classification_level_name" value="{{{ Input::old('level', isset($classification_level) ? $classification_level->level : null) }}}">
                        {!! $errors->first('classification_level_name', '<label class="control-label" for="classification_level_name">:message</label>')!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('level_last_school_year') ? 'has-error' : '' }}}">
                        <label for="level_last_school_year">{{ Lang::get('student.last_school_year') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_level_last_school_year" name="registered_student_level_last_school_year" value="{{{ Input::old('level', isset($level_last_school_year) ? $level_last_school_year->level : null) }}}">
                        {!! $errors->first('level_last_school_year', '<label class="control-label" for="level_last_school_year">:message</label>')!!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{{ $errors->has('section_name') ? 'has-error' : '' }}}">
                        <label for="section_name">{{ Lang::get('student.last_school_year') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_section_name" name="registered_student_section_name" value="{{{ Input::old('section_name', isset($section) ? $section->section_name : null) }}}">
                        {!! $errors->first('section_name', '<label class="control-label" for="section_name">:message</label>')!!}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group {{{ $errors->has('last_school_attended') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="last_school_attended">{!! Lang::get('student.last_school_attended') !!}</label>
                        <div class="col-md-9">
                            @if($action == 1)
                                <input type="text" readOnly class="form-control" name="last_school_attended" id="last_school_attended" value="{{{ Input::old('last_school_attended', isset($enrollment) ? $enrollment->last_school_attended : null) }}}" />
                            @endif
                            {!! $errors->first('last_school_attended', '<label class="control-label" for="last_school_attended">:message</label>')!!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-12 control-label"class="form-group {{{ $errors->has('academic_distinction') ? 'has-error' : '' }}}">
                        <label for="academic_distinction">Briefly list any academic distinction or honors you have received in the last school year.</label>
                        @if($action == 1)
                            <textarea readOnly class="form-control" rows="3" id="academic_distinction" name="academic_distinction" value="{{{ Input::old('academic_distinction', isset($enrollment) ? $enrollment->academic_distinction : null) }}}">{{{ Input::old('academic_distinction', isset($enrollment) ? $enrollment->academic_distinction : null) }}}</textarea>
                         @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12 control-label"class="form-group {{{ $errors->has('extra_curricular') ? 'has-error' : '' }}}">
                        <label for="extra_curricular">{!! Lang::get('student.extra_curricular') !!}:</label>
                        @if($action == 1)
                            <textarea readOnly class="form-control" rows="4" id="extra_curricular" name="extra_curricular" value="{{{ Input::old('extra_curricular', isset($enrollment) ? $enrollment->extra_curricular : null) }}}">{{{ Input::old('extra_curricular', isset($enrollment) ? $enrollment->extra_curricular : null) }}}</textarea>
                       @endif
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
                        <label for="last_name">{{ Lang::get('student.last_name') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_last_name" name="registered_student_last_name" value="{{{ Input::old('last_name', isset($person) ? $person->last_name : null) }}}">
                        {!! $errors->first('last_name', '<label class="control-label" for="last_name">:message</label>')!!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{{ $errors->has('first_name') ? 'has-error' : '' }}}">
                        <label for="first_name">{{ Lang::get('student.first_name') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_first_name" name="registered_student_first_name" value="{{{ Input::old('first_name', isset($person) ? $person->first_name : null) }}}">
                        {!! $errors->first('first_name', '<label class="control-label" for="first_name">:message</label>')!!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{{ $errors->has('middle_name') ? 'has-error' : '' }}}">
                        <label for="middle_name">{{ Lang::get('student.middle_name') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_middle_name" name="registered_student_middle_name" value="{{{ Input::old('middle_name', isset($person) ? $person->middle_name : null) }}}">
                        {!! $errors->first('middle_name', '<label class="control-label" for="middle_name">:message</label>')!!}
                    </div>
                </div> 
                <div class="col-md-2">
                    <div class="form-group {{{ $errors->has('suffix_name') ? 'has-error' : '' }}}">
                        <label for="suffix_name">{{ Lang::get('student.suffix_name') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_suffix_name" name="registered_student_suffix_name" value="{{{ Input::old('suffix_name', isset($suffix) ? $suffix->suffix_name : null) }}}">
                        {!! $errors->first('suffix_name', '<label class="control-label" for="suffix_name">:message</label>')!!}
                    </div>
                </div> 
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('address') ? 'has-error' : '' }}}">
                        <label for="address">{{ Lang::get('student.address') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_address" name="registered_student_address" value="{{{ Input::old('address', isset($person) ? $person->address : null) }}}">
                        {!! $errors->first('address', '<label class="control-label" for="address">:message</label>')!!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{{ $errors->has('home_number') ? 'has-error' : '' }}}">
                        <label for="home_number">{{ Lang::get('student.home_number') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_home_number" name="registered_student_home_number" value="{{{ Input::old('home_number', isset($person) ? $person->home_number : null) }}}">
                        {!! $errors->first('home_number', '<label class="control-label" for="home_number">:message</label>')!!}
                    </div>
                </div>
                <div class="col-md-3">
                     <div class="form-group {{{ $errors->has('gender_name') ? 'has-error' : '' }}}">
                        <label for="gender_name">{{ Lang::get('student.gender') }}</label>
                        <input type="text" readOnly class="typeahead form-control" id="registered_student_gender_name" name="registered_student_gender_name" value="{{{ Input::old('gender_name', isset($gender) ? $gender->gender_name : null) }}}">
                        {!! $errors->first('gender_name', '<label class="control-label" for="gender_name">:message</label>')!!}
                    </div>
                </div> 
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('preferred_name') ? 'has-error' : '' }}}">
                        <label class="col-md-4 control-label" for="preferred_name">{!! Lang::get('student.preferred_name') !!}</label>
                        <div class="col-md-8">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_preferred_name" id="registered_student_preferred_name" value="{{{ Input::old('preferred_name', isset($person) ? $person->preferred_name : null) }}}" />
                            {!! $errors->first('preferred_name', '<label class="control-label" for="preferred_name">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('student_email_address') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="student_email_address">{!! Lang::get('student.student_email_address') !!}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_email_address" id="registered_student_email_address" value="{{{ Input::old('student_email_address', isset($person) ? $person->student_email_address : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_mobile_number" id="registered_student_mobile_number" value="{{{ Input::old('student_mobile_number', isset($person) ? $person->student_mobile_number : null) }}}" />
                            {!! $errors->first('student_mobile_number', '<label class="control-label" for="student_mobile_number">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('birthdate') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="birthdate">{!! Lang::get('student.birthdate') !!}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_birthdate" id="registered_student_birthdate" value="{{{ Input::old('birthdate', isset($person) ? $person->birthdate : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_church_affiliation" id="registered_student_church_affiliation" value="{{{ Input::old('church_affiliation', isset($person) ? $person->church_affiliation : null) }}}" />
                            {!! $errors->first('church_affiliation', '<label class="control-label" for="church_affiliation">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('birth_place') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="birth_place">{!! Lang::get('student.birth_place') !!}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_birth_place" id="registered_student_birth_place" value="{{{ Input::old('birth_place', isset($person) ? $person->birth_place : null) }}}" />
                            {!! $errors->first('birth_place', '<label class="control-label" for="birth_place">:message</label>')!!}

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('religion_name') ? 'has-error' : '' }}}">
                        <label class="col-md-4 control-label" for="religion_name">{{ Lang::get('student.religion') }}</label>
                        <div class="col-md-8">
                            <input type="text" readOnly class="typeahead form-control" id="registered_student_religion_name" name="registered_student_religion_name" value="{{{ Input::old('religion_name', isset($religion) ? $religion->religion_name : null) }}}">
                            {!! $errors->first('religion_name', '<label class="control-label" for="religion_name">:message</label>')!!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('citizenship_name') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="citizenship_name">{{ Lang::get('student.citizenship') }}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" id="registered_student_citizenship_name" name="registered_student_citizenship_name" value="{{{ Input::old('citizenship_name', isset($citizenship) ? $citizenship->citizenship_name : null) }}}">
                            {!! $errors->first('citizenship_name', '<label class="control-label" for="citizenship_name">:message</label>')!!}
                        </div> 
                    </div> 
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('passport_number') ? 'has-error' : '' }}}">
                        <label class="col-md-4 control-label" for="passport_number">{{ Lang::get('student.passport_number') }} (Internationl)</label>
                        <div class="col-md-8">
                            <input type="text" readOnly class="typeahead form-control" id="registered_student_passport_number" name="registered_student_passport_number" value="{{{ Input::old('passport_number', isset($person) ? $person->passport_number : null) }}}">
                            {!! $errors->first('passport_number', '<label class="control-label" for="passport_number">:message</label>')!!}
                        </div> 
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('icard_number') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="icard_number">{{ Lang::get('student.icard_number') }} (Internationl)</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" id="registered_student_icard_number" name="registered_student_icard_number" value="{{{ Input::old('icard_number', isset($person) ? $person->icard_number : null) }}}">
                            {!! $errors->first('icard_number', '<label class="control-label" for="icard_number">:message</label>')!!}
                        </div> 
                    </div> 
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('living_with') ? 'has-error' : '' }}}">
                        <label class="col-md-4 control-label" for="living_with">{!! Lang::get('student.living_with') !!}</label>
                        <div class="col-md-8">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_living_with" id="registered_student_living_with" value="{{{ Input::old('name', isset($living_with) ? $living_with->name : null) }}}" />
                            {!! $errors->first('living_with', '<label class="control-label" for="living_with">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('name_relation') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="name_relation">if Guardian : {!! Lang::get('student.name_relation') !!}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_name_relation" id="registered_student_name_relation" value="{{{ Input::old('name_relation', isset($person) ? $person->name_relation : null) }}}" />
                            {!! $errors->first('name_relation', '<label class="control-label" for="name_relation">:message</label>')!!}

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8"></div>
            <div class="col-md-12">
                <h4><b style="color:#2a3088">{{ Lang::get('student.medical_information') }}</b></h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-12 control-label"class="form-group {{{ $errors->has('medical_condition') ? 'has-error' : '' }}}">
                        <label for="medical_condition">Do you have any existing medical condition, if so please specify:</label>
                        <textarea readOnly class="form-control" rows="3" id="registered_student_medical_condition" name="registered_student_medical_condition" value="{{{ Input::old('medical_condition', isset($person) ? $person->medical_condition : null) }}}">{{{ Input::old('medical_condition', isset($person) ? $person->medical_condition : null) }}}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12 control-label"class="form-group {{{ $errors->has('maintenance_medication') ? 'has-error' : '' }}}">
                        <label for="maintenance_medication">Are you under any maintenance medication, if so please specify:</label>
                        <textarea readOnly class="form-control" rows="3" id="registered_student_maintenance_medication" name="registered_student_maintenance_medication" value="{{{ Input::old('maintenance_medication', isset($person) ? $person->maintenance_medication : null) }}}">{{{ Input::old('maintenance_medication', isset($person) ? $person->maintenance_medication : null) }}}</textarea><br>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('personal_physician') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="personal_physician">{!! Lang::get('student.personal_physician') !!}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_personal_physician" id="registered_student_personal_physician" value="{{{ Input::old('personal_physician', isset($person) ? $person->personal_physician : null) }}}" />
                            {!! $errors->first('personal_physician', '<label class="control-label" for="personal_physician">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('physician_mobile_number') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="physician_mobile_number">{!! Lang::get('student.physician_mobile_number') !!}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_physician_mobile_number" id="registered_student_physician_mobile_number" value="{{{ Input::old('physician_mobile_number', isset($person) ? $person->physician_mobile_number : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_clinic_address" id="registered_student_clinic_address" value="{{{ Input::old('clinic_address', isset($person) ? $person->clinic_address : null) }}}" />
                            {!! $errors->first('clinic_address', '<label class="control-label" for="clinic_address">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('physician_office_number') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="physician_office_number">{!! Lang::get('student.physician_office_number') !!}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_physician_office_number" id="registered_student_physician_office_number" value="{{{ Input::old('physician_office_number', isset($person) ? $person->physician_office_number : null) }}}" />
                            {!! $errors->first('physician_office_number', '<label class="control-label" for="physician_office_number">:message</label>')!!}

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8"></div>
            <div class="col-md-12">
                <h4><b style="color:#2a3088">{{ Lang::get('student.family_background') }}</b></h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('fathers_name') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="fathers_name">Father's Name</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_fathers_name" id="registered_student_fathers_name" value="{{{ Input::old('fathers_name', isset($student_family_background) ? $student_family_background->fathers_name : null) }}}" />
                            {!! $errors->first('fathers_name', '<label class="control-label" for="fathers_name">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('mothers_name') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="mothers_name">Mother's Name</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_mothers_name" id="registered_student_mothers_name" value="{{{ Input::old('mothers_name', isset($student_family_background) ? $student_family_background->mothers_name : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_fathers_mobile_number" id="registered_student_fathers_mobile_number" value="{{{ Input::old('fathers_mobile_number', isset($student_family_background) ? $student_family_background->fathers_mobile_number : null) }}}" />
                            {!! $errors->first('fathers_mobile_number', '<label class="control-label" for="fathers_mobile_number">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('mothers_mobile_number') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="mothers_mobile_number">{{ Lang::get('student.mothers_mobile_number') }}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_mothers_mobile_number" id="registered_student_mothers_mobile_number" value="{{{ Input::old('mothers_mobile_number', isset($student_family_background) ? $student_family_background->mothers_mobile_number : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_fathers_occupation" id="registered_student_fathers_occupation" value="{{{ Input::old('fathers_occupation', isset($student_family_background) ? $student_family_background->fathers_occupation : null) }}}" />
                            {!! $errors->first('fathers_occupation', '<label class="control-label" for="fathers_occupation">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('mothers_occupation') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="mothers_occupation">{{ Lang::get('student.mothers_occupation') }}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_mothers_occupation" id="registered_student_mothers_occupation" value="{{{ Input::old('mothers_occupation', isset($student_family_background) ? $student_family_background->mothers_occupation : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_fathers_employer_name" id="registered_student_fathers_employer_name" value="{{{ Input::old('fathers_employer_name', isset($student_family_background) ? $student_family_background->fathers_employer_name : null) }}}" />
                            {!! $errors->first('fathers_employer_name', '<label class="control-label" for="fathers_employer_name">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('mothers_employer_name') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="mothers_employer_name">Employer's Name</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_mothers_employer_name" id="registered_student_mothers_employer_name" value="{{{ Input::old('mothers_employer_name', isset($student_family_background) ? $student_family_background->mothers_employer_name : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_fathers_employer_no" id="registered_student_fathers_employer_no" value="{{{ Input::old('fathers_employer_no', isset($student_family_background) ? $student_family_background->fathers_employer_no : null) }}}" />
                            {!! $errors->first('fathers_employer_no', '<label class="control-label" for="fathers_employer_no">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('mothers_employer_no') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="mothers_employer_no">{{ Lang::get('student.mothers_employer_no') }}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_mothers_employer_no" id="registered_student_mothers_employer_no" value="{{{ Input::old('mothers_employer_no', isset($student_family_background) ? $student_family_background->mothers_employer_no : null) }}}" />
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
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_fathers_email_address" id="registered_student_fathers_email_address" value="{{{ Input::old('fathers_email_address', isset($student_family_background) ? $student_family_background->fathers_email_address : null) }}}" />
                            {!! $errors->first('fathers_email_address', '<label class="control-label" for="fathers_email_address">:message</label>')!!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('mothers_email_address') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="mothers_email_address">{{ Lang::get('student.mothers_email_address') }}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_mothers_email_address" id="registered_student_mothers_email_address" value="{{{ Input::old('mothers_email_address', isset($student_family_background) ? $student_family_background->mothers_email_address : null) }}}" />
                            {!! $errors->first('mothers_email_address', '<label class="control-label" for="mothers_email_address">:message</label>')!!}

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group {{{ $errors->has('parents_marital_status') ? 'has-error' : '' }}}">
                        <label class="col-md-3 control-label" for="parents_marital_status">{{ Lang::get('student.parents_marital_status') }}</label>
                        <div class="col-md-9">
                            <input type="text" readOnly class="typeahead form-control" name="registered_student_parents_marital_status" id="registered_student_parents_marital_status" value="{{{ Input::old('name', isset($parents_marital_status) ? $parents_marital_status->name : null) }}}" />
                            {!! $errors->first('parents_marital_status', '<label class="control-label" for="parents_marital_status">:message</label>')!!}

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-12">
                    <label class="col-md-12 control-label" for="siblings">List of names and ages of your siblings with the current grade/ level and school/ university they are currently enrolled in:</label>
                </div>
                @if($action == 1)
                    <table class="table table-bordered table-striped" id="student_sibling_table">
                        <th style="width:25%"><center>Name</center></th>
                        <th style="width:25%"><center>Age</center></th>
                        <th style="width:25%"><center>Grade/Level</center></th>
                        <th style="width:25%"><center>School/University</center></th>
                        <tbody id="student_sibling_container">
                            @foreach($student_siblings_list as $student_siblings)
                                @if($student_siblings->student_id == $student_curriculum->student_id)
                                <tr>
                                    <td>
                                        <input type="hidden" id="student_siblings_id" name="student_siblings_id" value="{{{$student_siblings->id}}}" />
                                        <input readOnly class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($student_siblings) ? $student_siblings->name : null) }}}" />
                                    </td>
                                    <td>
                                        <input readOnly class="form-control" type="text" name="age" id="age" value="{{{ Input::old('age', isset($student_siblings) ? $student_siblings->age : null) }}}" />
                                    </td>
                                    <td>
                                        <input readOnly class="form-control" type="text" name="grade_level" id="grade_level" value="{{{ Input::old('grade_level', isset($student_siblings) ? $student_siblings->grade_level : null) }}}" />
                                    </td>
                                    <td>
                                        <input readOnly class="form-control" type="text" name="school_university" id="school_university" value="{{{ Input::old('school_university', isset($student_siblings) ? $student_siblings->grade_level : null) }}}" />
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>                    
                    </table>
                @endif
                <div id="studentsiblingContainer" class="col-md-12">
            </div>
            <div class="col-md-12">
                <br/><br/>
            </div>
		</form>
    </div>
</div>	
@stop