<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
<div class="col-md-12">
    <div class="col-md-7">
        <div class="form-group {{{ $errors->has('student_no') ? 'has-error' : '' }}}">
            <label for="student_no" class="col-md-4">{{ Lang::get('student.student_no') }}:</label>
            <p id="student_no" name="student_no" class="col-md-8">{{{$enrollment->student_no}}}</p>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group {{{ $errors->has('year_level') ? 'has-error' : '' }}}">
            <label for="year_level" class="col-md-4">{{ Lang::get('student.year_level') }}:</label>
            <p id="year_level" name="year_level" class="col-md-8">{{{$enrollment->level}}} {{{$enrollment->section_name}}}</p>
        </div>
    </div>
</div>
<div class="col-md-12">
    <fieldset class="first">
        <legend class="first"><h5><b>{{ Lang::get('student.personal_data') }}</b></h5></legend>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('student_name') ? 'has-error' : '' }}}">
                <label for="student_name" class="col-md-4">{{ Lang::get('student.student_name') }}:</label>
                <p id="student_name" name="student_name" class="col-md-8">{{{$enrollment->last_name}}}, {{{$enrollment->first_name}}} {{{$enrollment->middle_name}}} {{{$enrollment->suffix_name}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('student_type') ? 'has-error' : '' }}}">
                <label for="student_type" class="col-md-4">{{ Lang::get('student.student_type') }}:</label>
                <p id="student_type" name="student_type" class="col-md-8">{{{$enrollment->student_type_name}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('gender') ? 'has-error' : '' }}}">
                <label for="gender" class="col-md-4">{{ Lang::get('student.gender') }}:</label>
                <p id="gender" name="gender" class="col-md-8">{{{$enrollment->gender_name}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('age') ? 'has-error' : '' }}}">
                <label for="age" class="col-md-4">{{ Lang::get('student.age') }}:</label>
                <p id="age" name="age" class="col-md-8">{{ date("Y") - date('Y',strtotime($enrollment->birthdate))}} yrs. old</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('address') ? 'has-error' : '' }}}">
                <label for="address" class="col-md-4">{{ Lang::get('student.address') }}:</label>
                <p id="address" name="address" class="col-md-8">{{{$enrollment->address}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('home_number') ? 'has-error' : '' }}}">
                <label for="home_number" class="col-md-4">{{ Lang::get('student.home_number') }}:</label>
                <p id="home_number" name="home_number" class="col-md-8">{{{$enrollment->home_number}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('preferred_name') ? 'has-error' : '' }}}">
                <label for="preferred_name" class="col-md-4">{{ Lang::get('student.preferred_name') }}:</label>
                <p id="preferred_name" name="preferred_name" class="col-md-8">{{{$enrollment->preferred_name}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('birthdate') ? 'has-error' : '' }}}">
                <label for="birthdate" class="col-md-4">{{ Lang::get('student.birthdate') }}:</label>
                <p id="birthdate" name="birthdate" class="col-md-8">{{date('M d, Y',strtotime($enrollment->birthdate))}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('student_mobile_number') ? 'has-error' : '' }}}">
                <label for="student_mobile_number" class="col-md-4">{{ Lang::get('student.student_mobile_number') }}:</label>
                <p id="student_mobile_number" name="student_mobile_number" class="col-md-8">{{{$enrollment->student_mobile_number}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('birth_place') ? 'has-error' : '' }}}">
                <label for="birth_place" class="col-md-4">{{ Lang::get('student.birth_place') }}:</label>
                <p id="birth_place" name="birth_place" class="col-md-8">{{{$enrollment->birth_place}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('church_affiliation') ? 'has-error' : '' }}}">
                <label for="church_affiliation" class="col-md-4">{{ Lang::get('student.church_affiliation') }}:</label>
                <p id="church_affiliation" name="church_affiliation" class="col-md-8">{{{$enrollment->church_affiliation}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('citizenship_name') ? 'has-error' : '' }}}">
                <label for="citizenship_name" class="col-md-4">{{ Lang::get('student.citizenship') }}:</label>
                <p id="citizenship_name" name="citizenship_name" class="col-md-8">{{{$enrollment->citizenship_name}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('religion_name') ? 'has-error' : '' }}}">
                <label for="religion_name" class="col-md-4">{{ Lang::get('student.religion') }}:</label>
                <p id="religion_name" name="religion_name" class="col-md-8">{{{$enrollment->religion_name}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('student_email_address') ? 'has-error' : '' }}}">
                <label for="student_email_address" class="col-md-4">{{ Lang::get('student.student_email_address') }}:</label>
                <p id="student_email_address" name="student_email_address" class="col-md-8">{{{$enrollment->student_email_address}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('passport_number') ? 'has-error' : '' }}}">
                <label for="passport_number" class="col-md-4">{{ Lang::get('student.passport_number') }} (International):</label>
                <p id="passport_number" name="passport_number" class="col-md-8">{{{$enrollment->passport_number}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('icard_number') ? 'has-error' : '' }}}">
                <label for="icard_number" class="col-md-4">{{ Lang::get('student.icard_number') }} (International):</label>
                <p id="icard_number" name="icard_number" class="col-md-8">{{{$enrollment->icard_number}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('living_with') ? 'has-error' : '' }}}">
                <label for="living_with" class="col-md-4">{{ Lang::get('student.living_with') }}:</label>
                <p id="living_with" name="living_with" class="col-md-8">{{{$enrollment->living_with_name}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('name_relation') ? 'has-error' : '' }}}">
                <label for="name_relation" class="col-md-4">if Guardian: {{ Lang::get('student.name_relation') }}:</label>
                <p id="name_relation" name="name_relation" class="col-md-8">{{{$enrollment->name_relation}}}</p>
            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-12">
    <fieldset class="first">
        <legend class="first"><h5><b>{{ Lang::get('student.medical_information') }}</b></h5></legend>
        <div class="col-md-8">
            <div class="form-group {{{ $errors->has('medical_condition') ? 'has-error' : '' }}}">
                <label for="medical_condition" class="col-md-4">{{ Lang::get('student.medical_condition') }}:</label>
                <p id="medical_condition" name="medical_condition" class="col-md-8">{{{$enrollment->medical_condition}}}</p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group {{{ $errors->has('maintenance_medication') ? 'has-error' : '' }}}">
                <label for="maintenance_medication" class="col-md-4">{{ Lang::get('student.maintenance_medication') }}:</label>
                <p id="maintenance_medication" name="maintenance_medication" class="col-md-8">{{{$enrollment->maintenance_medication}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('personal_physician') ? 'has-error' : '' }}}">
                <label for="personal_physician" class="col-md-4">{{ Lang::get('student.personal_physician') }}:</label>
                <p id="personal_physician" name="personal_physician" class="col-md-8">{{{$enrollment->personal_physician}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('physician_mobile_number') ? 'has-error' : '' }}}">
                <label for="physician_mobile_number" class="col-md-4">{{ Lang::get('student.physician_mobile_number') }}:</label>
                <p id="physician_mobile_number" name="physician_mobile_number" class="col-md-8">{{{$enrollment->physician_mobile_number}}}</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group {{{ $errors->has('clinic_address') ? 'has-error' : '' }}}">
                <label for="clinic_address" class="col-md-4">{{ Lang::get('student.clinic_address') }}:</label>
                <p id="clinic_address" name="clinic_address" class="col-md-8">{{{$enrollment->clinic_address}}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group {{{ $errors->has('physician_office_number') ? 'has-error' : '' }}}">
                <label for="physician_office_number" class="col-md-4">{{ Lang::get('student.physician_office_number') }}:</label>
                <p id="physician_office_number" name="physician_office_number" class="col-md-8">{{{$enrollment->physician_office_number}}}</p>
            </div>
        </div>
    </fieldset>
</div>
<div class="col-md-12">
    <fieldset class="first">
        <legend class="first"><h5><b>{{ Lang::get('student.family_background') }}</b></h5></legend>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_name') ? 'has-error' : '' }}}">
                <label for="fathers_name" class="col-md-4">{{ Lang::get('student.fathers_name') }}:</label>
                <p id="fathers_name" name="fathers_name" class="col-md-8">{{{$enrollment->fathers_name}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_name') ? 'has-error' : '' }}}">
                <label for="mothers_name" class="col-md-4">{{ Lang::get('student.mothers_name') }}:</label>
                <p id="mothers_name" name="mothers_name" class="col-md-8">{{{$enrollment->mothers_name}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_mobile_number') ? 'has-error' : '' }}}">
                <label for="fathers_mobile_number" class="col-md-4">{{ Lang::get('student.fathers_mobile_number') }}:</label>
                <p id="fathers_mobile_number" name="fathers_mobile_number" class="col-md-8">{{{$enrollment->fathers_mobile_number}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_mobile_number') ? 'has-error' : '' }}}">
                <label for="mothers_mobile_number" class="col-md-4">{{ Lang::get('student.mothers_mobile_number') }}:</label>
                <p id="mothers_mobile_number" name="mothers_mobile_number" class="col-md-8">{{{$enrollment->mothers_mobile_number}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_occupation') ? 'has-error' : '' }}}">
                <label for="fathers_occupation" class="col-md-4">{{ Lang::get('student.fathers_occupation') }}:</label>
                <p id="fathers_occupation" name="fathers_occupation" class="col-md-8">{{{$enrollment->fathers_occupation}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_occupation') ? 'has-error' : '' }}}">
                <label for="mothers_occupation" class="col-md-4">{{ Lang::get('student.mothers_occupation') }}:</label>
                <p id="mothers_occupation" name="mothers_occupation" class="col-md-8">{{{$enrollment->mothers_occupation}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_employers_name') ? 'has-error' : '' }}}">
                <label for="fathers_employers_name" class="col-md-4">{{ Lang::get('student.fathers_employers_name') }}:</label>
                <p id="fathers_employers_name" name="fathers_employers_name" class="col-md-8">{{{$enrollment->fathers_employer_name}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_employers_name') ? 'has-error' : '' }}}">
                <label for="mothers_employers_name" class="col-md-4">{{ Lang::get('student.mothers_employers_name') }}:</label>
                <p id="mothers_employers_name" name="mothers_employers_name" class="col-md-8">{{{$enrollment->mothers_employer_name}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_employer_no') ? 'has-error' : '' }}}">
                <label for="fathers_employer_no" class="col-md-4">{{ Lang::get('student.fathers_employer_no') }}:</label>
                <p id="fathers_employer_no" name="fathers_employer_no" class="col-md-8">{{{$enrollment->fathers_employer_no}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_employer_no') ? 'has-error' : '' }}}">
                <label for="mothers_employer_no" class="col-md-4">{{ Lang::get('student.mothers_employer_no') }}:</label>
                <p id="mothers_employer_no" name="mothers_employer_no" class="col-md-8">{{{$enrollment->mothers_employer_no}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('fathers_email_address') ? 'has-error' : '' }}}">
                <label for="fathers_email_address" class="col-md-4">{{ Lang::get('student.fathers_email_address') }}:</label>
                <p id="fathers_email_address" name="fathers_email_address" class="col-md-8">{{{$enrollment->fathers_email_address}}}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{{ $errors->has('mothers_email_address') ? 'has-error' : '' }}}">
                <label for="mothers_email_address" class="col-md-4">{{ Lang::get('student.mothers_email_address') }}:</label>
                <p id="mothers_email_address" name="mothers_email_address" class="col-md-8">{{{$enrollment->mothers_email_address}}}</p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group {{{ $errors->has('parents_marital_status') ? 'has-error' : '' }}}">
                <label for="parents_marital_status" class="col-md-4">{{ Lang::get('student.parents_marital_status') }}:</label>
                <p id="parents_marital_status" name="parents_marital_status" class="col-md-8">{{{$enrollment->parents_marital_status_name}}}</p>
            </div>
        </div>
        <div class="col-md-12">
            <label for="siblings">List of names and ages of your siblings with the current grade/ level and school/ university they are currently enrolled in:</label>
        </div>
        <table class="table table-bordered table-striped" id="student_sibling_table">
            <th style="width:25%"><center>Name</center></th>
            <th style="width:25%"><center>Age</center></th>
            <th style="width:25%"><center>Grade/Level</center></th>
            <th style="width:25%"><center>School/University</center></th>
            <tbody id="student_sibling_container">
                @foreach($student_siblings_list as $student_siblings)
                    @if($student_siblings->student_id == $enrollment->student_id)
                    <tr>
                        <td>
                            <input type="hidden" id="student_siblings_id" name="student_siblings_id" value="{{{$student_siblings->id}}}" />
                            <input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($student_siblings) ? $student_siblings->name : null) }}}" />
                        </td>
                        <td>
                            <input class="form-control" type="text" name="age" id="age" value="{{{ Input::old('age', isset($student_siblings) ? $student_siblings->age : null) }}}" />
                        </td>
                        <td>
                            <input class="form-control" type="text" name="grade_level" id="grade_level" value="{{{ Input::old('grade_level', isset($student_siblings) ? $student_siblings->grade_level : null) }}}" />
                        </td>
                        <td>
                            <input class="form-control" type="text" name="school_university" id="school_university" value="{{{ Input::old('school_university', isset($student_siblings) ? $student_siblings->grade_level : null) }}}" />
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>                    
        </table>
    </fieldset>
</div>
<div class="col-md-12"><br><br></div>
