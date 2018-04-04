@extends('site.layouts.default_full_page')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get("registrar.register_old_student") }}} :: @parent
@stop
{{-- Content --}}
@section('content')
<link rel="stylesheet" href="{{asset('assets/site/datepicker/ui/1.11.4/themes/smoothness/jquery-ui.css')}}">
<script src="{{asset('assets/site/datepicker/jquery-1.10.2.js')}}"></script>
<script src="{{asset('assets/site/datepicker/ui/1.11.4/jquery-ui.js')}}"></script>
<script>
//     $(function() {
//     $( "#birthdate" ).datepicker({
//         dateFormat: "yy-mm-dd",
//         changeMonth: true,
//         changeYear: true,
//         yearRange: '2000:2030',
//     });
// });
 </script>

<div id="full-page-wrapper">
    <div class="row">
        @include('notifications')
        <div class="page-header"><br>
            <h3> {{{ Lang::get("registrar.register_old_student") }}}
                <div class="pull-right">
                    <div class="pull-right">
                        <a href="{{{ URL::to('registrar/register_student') }}}" class="btn btn-sm  btn-primary iframe"><span class="glyphicon glyphicon-list"></span> {{ Lang::get("registrar.registered_student") }}</a>
                    </div>
                </div>
            </h3>
        </div>

        <form class="form-horizontal" method="post" action="{{ URL::to('registrar/register_old_student/create') }}" autocomplete="off">
            @include('registrar/register_old_student.form')
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="actions">&nbsp;</label>
                    <div class="col-md-9">  
                        <button type="submit" class="btn btn-sm btn-success">
                            <span class="glyphicon glyphicon-ok-circle"></span> {{ Lang::get("form.register") }}
                        </button>   
                        <button type="reset" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-remove-circle"></span>  {{ Lang::get("form.reset") }}
                        </button>   
                        <a href="{{{ URL::to('registrar/register_student') }}}" class="btn btn-sm btn-warning close_popup">
                            <span class="glyphicon glyphicon-ban-circle"></span>  {{ Lang::get("form.cancel") }}
                        </a>
                    </div>
                </div>
            </div>  
        </form><br>
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
                var student_list = new Bloodhound({
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
                            url:"{{URL::to('register_student/dataJsonRegisteredOldStudent?query=%QUERY')}}",
                            
                            // the json file contains an array of strings, but the Bloodhound
                            // suggestion engine expects JavaScript objects so this converts all of
                            // those strings
                             filter: function (student_list) {
                                         // alert('this is an alert script from create');
                                    //console.log(student_list); //debugging
                                    // Map the remote source JSON array to a JavaScript object array
                                    return $.map(student_list, function (student) {
                                        // console.log(student.student_no); //debugging

                                        return {

                                            student_name: student.student_no + ' - '+student.first_name + ' ' + student.last_name+' - ('+student.school_no+ ')',
                                            student_id: student.student_id,
                                            name: student.name,
                                            student_no: student.student_no,
                                            school_no: student.school_no,
                                            last_name: student.last_name,
                                            first_name: student.first_name,
                                            middle_name: student.middle_name,
                                            preferred_name: student.preferred_name,
                                            student_email_address: student.student_email_address,
                                            student_mobile_number: student.student_mobile_number,
                                            address: student.address,
                                            home_number: student.home_number,
                                            passport_number: student.passport_number,
                                            icard_number: student.icard_number,
                                            birthdate: student.birthdate,
                                            birth_place: student.birth_place,
                                            religion_name: student.religion_name,
                                            suffix_name: student.suffix_name,
                                            church_affiliation: student.church_affiliation,
                                            gender_name: student.gender_name,
                                            citizenship_name: student.citizenship_name,
                                            living_with_name: student.living_with_name,
                                            name_relation: student.name_relation,
                                            medical_condition: student.medical_condition,
                                            maintenance_medication: student.maintenance_medication,
                                            personal_physician: student.personal_physician,
                                            physician_mobile_number: student.physician_mobile_number,
                                            clinic_address: student.clinic_address,
                                            physician_office_number: student.physician_office_number,
                                            fathers_name: student.fathers_name,
                                            mothers_name: student.mothers_name,
                                            fathers_mobile_number: student.fathers_mobile_number,
                                            mothers_mobile_number: student.mothers_mobile_number,
                                            fathers_occupation: student.fathers_occupation,
                                            mothers_occupation: student.mothers_occupation,
                                            fathers_employer_name: student.fathers_employer_name,
                                            mothers_employer_name: student.mothers_employer_name,
                                            fathers_employer_no: student.fathers_employer_no,
                                            mothers_employer_no: student.mothers_employer_no,
                                            fathers_email_address: student.fathers_email_address,
                                            mothers_email_address: student.mothers_email_address,
                                            parents_marital_status_name: student.parents_marital_status_name,
                                            id: student.id
                                        };
                                    });
                            }
                        }
                });

                student_list.initialize();
                 console.log(student_list);

                //its_customs_office_code_typeahead   -- this is the id of the div element that handles typeahead for export country name
               // $('#student_student_no_typeahead .typeahead').typeahead(null, {
                  $('#registered_student_no_typeahead').typeahead(null, {
                  student_name: 'student_list',
                  displayKey: 'student_name',
                  source: student_list.ttAdapter()
                }).bind("typeahead:selected", function(obj, student, student_name) {
                        console.log(student);
                       $("#student_id").val(student.id);
                       $("#student_id").val(student.student_id);
                       $("#school_no").val(student.school_no);
                       $("#curriculum_id").val(student.curriculum_id);
                       $("#classification_id").val(student.classification_id);
                       $("#registered_student_type").val(student.name);
                       $("#registered_student_classification_name").val(student.classification_name);
                       $("#registered_student_name").val(student.curriculum_name);
                       $("#registered_student_last_name").val(student.last_name);
                       $("#registered_student_first_name").val(student.first_name);
                       $("#registered_student_middle_name").val(student.middle_name);
                       $("#registered_student_suffix_name").val(student.suffix_name);
                       $("#registered_student_preferred_name").val(student.preferred_name);
                       $("#registered_student_email_address").val(student.student_email_address);
                       $("#registered_student_mobile_number").val(student.student_mobile_number);
                       $("#registered_student_address").val(student.address);
                       $("#registered_student_home_number").val(student.home_number);
                       $("#registered_student_passport_number").val(student.passport_number);
                       $("#registered_student_icard_number").val(student.icard_number);
                       $("#registered_student_birthdate").val(student.birthdate);
                       $("#registered_student_birth_place").val(student.birth_place);
                       $("#registered_student_religion_name").val(student.religion_name);
                       $("#registered_student_church_affiliation").val(student.church_affiliation);
                       $("#registered_student_gender_name").val(student.gender_name);
                       $("#registered_student_living_with").val(student.living_with_name);
                       $("#registered_student_citizenship_name").val(student.citizenship_name);
                       $("#registered_student_name_relation").val(student.name_relation);
                       $("#registered_student_medical_condition").val(student.medical_condition);
                       $("#registered_student_maintenance_medication").val(student.maintenance_medication);
                       $("#registered_student_personal_physician").val(student.personal_physician);
                       $("#registered_student_physician_mobile_number").val(student.physician_mobile_number);
                       $("#registered_student_physician_office_number").val(student.physician_office_number);
                       $("#registered_student_clinic_address").val(student.clinic_address);
                       $("#registered_student_fathers_name").val(student.fathers_name);
                       $("#registered_student_mothers_name").val(student.mothers_name);
                       $("#registered_student_fathers_mobile_number").val(student.fathers_mobile_number);
                       $("#registered_student_mothers_mobile_number").val(student.mothers_mobile_number);
                       $("#registered_student_fathers_occupation").val(student.fathers_occupation);
                       $("#registered_student_mothers_occupation").val(student.mothers_occupation);
                       $("#registered_student_fathers_employer_name").val(student.fathers_employer_name);
                       $("#registered_student_mothers_employer_name").val(student.mothers_employer_name);
                       $("#registered_student_fathers_employer_no").val(student.fathers_employer_no);
                       $("#registered_student_mothers_employer_no").val(student.mothers_employer_no);
                       $("#registered_student_fathers_email_address").val(student.fathers_email_address);
                       $("#registered_student_mothers_email_address").val(student.mothers_email_address);
                       $("#registered_student_parents_marital_status").val(student.parents_marital_status_name);

                      loadStudentSiblings($("#student_id").val());

                    });

        $(function() {
            $("#classification_id").change(function(){
                selectListChange('curriculum_id','{{{URL::to("curriculum/dataJson")}}}',  { 'is_active':1 , 'classification_id': $(this).val() } ,'Please select a Curriculum')

            });
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


