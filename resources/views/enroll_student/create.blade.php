@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("enrollment.enroll_student") }}} :: @parent
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
<style type="text/css">
.hidden{
   display:none;
 }
 .shown{
   display:block;
 }
</style>

<div id="full-page-wrapper">
    <div class="row">
        @include('notifications')
        <div class="page-header"><br>
            <h3> {{{ Lang::get("student.enroll_student") }}}
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('enroll_student') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("student.student_list") }}</a>
                    </div>
                </div>
            </h3>
        </div>

        <form class="form-horizontal" method="post" action="{{ URL::to('enroll_student/create') }}" autocomplete="off">
            @include('enroll_student.form')
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <button type="submit" class="btn btn-sm btn-success">
                            <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.enroll") }}
                        </button>   
                        <button type="reset" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
                        </button>   
                        <a href="{{{ URL::to('enroll_student') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                    </div>
                </div>
            </div>  
        </form>
    </div>
</div>  

@stop
@section('scripts')
<script type="text/javascript">
   $(":submit").closest("form").submit(function(){
           $(':submit').attr('disabled', 'disabled');
       });
            /********
                RMD 2015-03-07
                START OF student_no ->  typeahead
            *************************************************************************/
                var student_curriculum_list = new Bloodhound({
                        datumTokenizer: function (datum) {
                            return Bloodhound.tokenizers.whitespace(datum.student_no);
                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        limit: 10,
                        remote: {
                            // url points to a json file that contains an array of country names, see
                            // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
                            //url: '../../its/student/dataJson',
                            //url:'http://boc.itechrar.com/its/student/dataJson',
                            url:"{{URL::to('register_student/dataJsonRegisteredStudent?query=%QUERY')}}",
                            
                            // the json file contains an array of strings, but the Bloodhound
                            // suggestion engine expects JavaScript objects so this converts all of
                            // those strings
                             filter: function (student_curriculum_list) {
                                         // alert('this is an alert script from create');
                                    //console.log(student_curriculum_list); //debugging
                                    // Map the remote source JSON array to a JavaScript object array
                                    return $.map(student_curriculum_list, function (student_curriculum) {
                                        // console.log(student.student_no); //debugging

                                        return {

                                            student_name: student_curriculum.student_no + ' - '+student_curriculum.first_name + ' ' + student_curriculum.last_name+' ('+student_curriculum.classification_name+')',
                                            student_id: student_curriculum.student_id,
                                            is_sped: student_curriculum.is_sped,
                                            curriculum_id: student_curriculum.curriculum_id,
                                            classification_id: student_curriculum.classification_id,
                                            classification_name: student_curriculum.classification_name,
                                            name: student_curriculum.name,
                                            curriculum_name: student_curriculum.curriculum_name,
                                            student_no: student_curriculum.student_no,
                                            last_name: student_curriculum.last_name,
                                            first_name: student_curriculum.first_name,
                                            middle_name: student_curriculum.middle_name,
                                            preferred_name: student_curriculum.preferred_name,
                                            student_email_address: student_curriculum.student_email_address,
                                            student_mobile_number: student_curriculum.student_mobile_number,
                                            address: student_curriculum.address,
                                            home_number: student_curriculum.home_number,
                                            passport_number: student_curriculum.passport_number,
                                            icard_number: student_curriculum.icard_number,
                                            birthdate: student_curriculum.birthdate,
                                            birth_place: student_curriculum.birth_place,
                                            religion_name: student_curriculum.religion_name,
                                            suffix_name: student_curriculum.suffix_name,
                                            church_affiliation: student_curriculum.church_affiliation,
                                            gender_name: student_curriculum.gender_name,
                                            citizenship_name: student_curriculum.citizenship_name,
                                            living_with_name: student_curriculum.living_with_name,
                                            name_relation: student_curriculum.name_relation,
                                            medical_condition: student_curriculum.medical_condition,
                                            maintenance_medication: student_curriculum.maintenance_medication,
                                            personal_physician: student_curriculum.personal_physician,
                                            physician_mobile_number: student_curriculum.physician_mobile_number,
                                            clinic_address: student_curriculum.clinic_address,
                                            physician_office_number: student_curriculum.physician_office_number,
                                            fathers_name: student_curriculum.fathers_name,
                                            mothers_name: student_curriculum.mothers_name,
                                            fathers_mobile_number: student_curriculum.fathers_mobile_number,
                                            mothers_mobile_number: student_curriculum.mothers_mobile_number,
                                            fathers_occupation: student_curriculum.fathers_occupation,
                                            mothers_occupation: student_curriculum.mothers_occupation,
                                            fathers_employer_name: student_curriculum.fathers_employer_name,
                                            mothers_employer_name: student_curriculum.mothers_employer_name,
                                            fathers_employer_no: student_curriculum.fathers_employer_no,
                                            mothers_employer_no: student_curriculum.mothers_employer_no,
                                            fathers_email_address: student_curriculum.fathers_email_address,
                                            mothers_email_address: student_curriculum.mothers_email_address,
                                            parents_marital_status_name: student_curriculum.parents_marital_status_name,
                                            id: student_curriculum.id
                                        };
                                    });
                            }
                        }
                });

                student_curriculum_list.initialize();
                 console.log(student_curriculum_list);

                //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
               // $('#student_student_no_typeahead .typeahead').typeahead(null, {
                  $('#registered_student_no_typeahead').typeahead(null, {
                  student_name: 'student_curriculum_list',
                  displayKey: 'student_name',
                  source: student_curriculum_list.ttAdapter()
                }).bind("typeahead:selected", function(obj, student_curriculum, student_name) {
                        console.log(student_curriculum);
                       $("#student_curriculum_id").val(student_curriculum.id);
                       $("#is_sped").val(student_curriculum.is_sped);
                       $("#student_id").val(student_curriculum.student_id);
                       $("#curriculum_id").val(student_curriculum.curriculum_id);
                       $("#classification_id").val(student_curriculum.classification_id);
                       $("#registered_student_type").val(student_curriculum.name);
                       $("#registered_student_classification_name").val(student_curriculum.classification_name);
                       $("#registered_student_curriculum_name").val(student_curriculum.curriculum_name);
                       $("#registered_student_last_name").val(student_curriculum.last_name);
                       $("#registered_student_first_name").val(student_curriculum.first_name);
                       $("#registered_student_middle_name").val(student_curriculum.middle_name);
                       $("#registered_student_suffix_name").val(student_curriculum.suffix_name);
                       $("#registered_student_preferred_name").val(student_curriculum.preferred_name);
                       $("#registered_student_email_address").val(student_curriculum.student_email_address);
                       $("#registered_student_mobile_number").val(student_curriculum.student_mobile_number);
                       $("#registered_student_address").val(student_curriculum.address);
                       $("#registered_student_home_number").val(student_curriculum.home_number);
                       $("#registered_student_passport_number").val(student_curriculum.passport_number);
                       $("#registered_student_icard_number").val(student_curriculum.icard_number);
                       $("#registered_student_birthdate").val(student_curriculum.birthdate);
                       $("#registered_student_birth_place").val(student_curriculum.birth_place);
                       $("#registered_student_religion_name").val(student_curriculum.religion_name);
                       $("#registered_student_church_affiliation").val(student_curriculum.church_affiliation);
                       $("#registered_student_gender_name").val(student_curriculum.gender_name);
                       $("#registered_student_living_with").val(student_curriculum.living_with_name);
                       $("#registered_student_citizenship_name").val(student_curriculum.citizenship_name);
                       $("#registered_student_name_relation").val(student_curriculum.name_relation);
                       $("#registered_student_medical_condition").val(student_curriculum.medical_condition);
                       $("#registered_student_maintenance_medication").val(student_curriculum.maintenance_medication);
                       $("#registered_student_personal_physician").val(student_curriculum.personal_physician);
                       $("#registered_student_physician_mobile_number").val(student_curriculum.physician_mobile_number);
                       $("#registered_student_physician_office_number").val(student_curriculum.physician_office_number);
                       $("#registered_student_clinic_address").val(student_curriculum.clinic_address);
                       $("#registered_student_fathers_name").val(student_curriculum.fathers_name);
                       $("#registered_student_mothers_name").val(student_curriculum.mothers_name);
                       $("#registered_student_fathers_mobile_number").val(student_curriculum.fathers_mobile_number);
                       $("#registered_student_mothers_mobile_number").val(student_curriculum.mothers_mobile_number);
                       $("#registered_student_fathers_occupation").val(student_curriculum.fathers_occupation);
                       $("#registered_student_mothers_occupation").val(student_curriculum.mothers_occupation);
                       $("#registered_student_fathers_employer_name").val(student_curriculum.fathers_employer_name);
                       $("#registered_student_mothers_employer_name").val(student_curriculum.mothers_employer_name);
                       $("#registered_student_fathers_employer_no").val(student_curriculum.fathers_employer_no);
                       $("#registered_student_mothers_employer_no").val(student_curriculum.mothers_employer_no);
                       $("#registered_student_fathers_email_address").val(student_curriculum.fathers_email_address);
                       $("#registered_student_mothers_email_address").val(student_curriculum.mothers_email_address);
                       $("#registered_student_parents_marital_status").val(student_curriculum.parents_marital_status_name);
                       selectListChange('classification_level_id','{{{URL::to("classification_level/dataJson")}}}',  { 'is_active':1 , 'classification_id': $("#classification_id").val() } ,'Please select a Classification Level')
                       // selectListChange('term_id','{{{URL::to("term/dataJson")}}}',  { 'is_active':1 , 'classification_id': $("#classification_id").val() } ,'Please select a Term')

                      loadStudentSiblings($("#student_id").val());

                    });
      $(function() {
          $("#classification_level_id").change(function(){
                selectListChange('section_id','{{{URL::to("section/dataJson")}}}',  { 'is_sped':$("#is_sped").val(),'is_active':1 , 'classification_level_id': $("#classification_level_id").val() } ,'Please select a Section')});
      });

      function loadStudentSiblings(studentId)
          {
            $.ajax(
                {
                  url: "{{{ URL::to('enroll_student/detail') }}}",
                  data: { 
                    'student_id': studentId
                  },
                }
              ).done(function(student_detail_html){
                $("#studentsiblingContainer").html(student_detail_html);
              });

          }

</script>
@stop

