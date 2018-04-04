<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodType; 
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Citizenship;
use App\Models\CivilStatus;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Gender;
use App\Models\Generic\GenPerson;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Guardian;
use App\Models\LivingWith;
use App\Models\ParentsMaritalStatus;
use App\Models\Person; 
use App\Models\Program;
use App\Models\Religion;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentCurriculum;
use App\Models\StudentFamilyBackground;
use App\Models\StudentSiblings;
use App\Models\StudentType;
use App\Models\Suffix;
use App\Http\Requests\RegisteredOldRequest;
use App\Http\Requests\RegisteredOldEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;    
use Hash;

class RegisterOldStudentController extends RegistrarMainController {


    public function getRegister() {
        // Show the page
        $action = 0;
        $classification_list = Classification::orderBy('classification.order')->get();
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->select('curriculum.id', 'curriculum.curriculum_name')->get();
        $student_siblings_list = StudentSiblings::all();
        return view('registrar/register_old_student.create',compact('classification_list','curriculum_subject_list','action'));
    }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postRegister(RegisteredOldRequest $registered_old_request) {

        $student_curriculum = new StudentCurriculum();
        $student_curriculum->student_id = $registered_old_request->student_id;
        $student_curriculum->curriculum_id = $registered_old_request->curriculum_id;
        $student_curriculum->classification_id = $registered_old_request->classification_id;
        $student_curriculum->save();

        $create_success = true;

        $success = \Lang::get('gen_user.create_success').' : '.$student_curriculum->student_id; 
        return redirect('registrar/register_student/create')->withSuccess( $success);
    }


 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $action = 1;
        $student_curriculum = StudentCurriculum::find($id);
        $student = Student::find($student_curriculum->student_id);
        $curriculum = Curriculum::find($student_curriculum->curriculum_id);
        $person = Person::find($student->person_id);
        $guardian = Guardian::find($student->guardian_id);
        $gen_user = GenUser::find($student->gen_user_id);
        $gender_list = Gender::all();
        $suffix_list = Suffix::all();
        $religion_list = Religion::all();
        $blood_type_list = BloodType::all();
        $civil_status_list = CivilStatus::all();
        $citizenship_list = Citizenship::all();

        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $school_year_list = SchoolYear::all();
        $student_type_list = StudentType::all();
        $living_with_list = LivingWith::all();
        $parents_marital_status_list = ParentsMaritalStatus::all();
        $student_family_background = StudentFamilyBackground::find($person->student_family_background_id);

        $student_siblings_list = StudentSiblings::all();
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->select('curriculum.id', 'curriculum.curriculum_name')->get();
                
        return view('registrar/register_student/edit',compact('student','student_curriculum','curriculum','person', 'student_type_list','parents_marital_status_list','living_with_list','student_family_background','student_siblings_list','gender_list','school_year_list','curriculum_subject_list','classification_level_list','suffix_list', 'classification_list', 'religion_list','blood_type_list','civil_status_list','citizenship_list','gen_user','action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(RegisteredOldEditRequest $registered_old_edit_request, $id) {
      
        $student_curriculum = StudentCurriculum::find($id);
        $student_curriculum->curriculum_id = $registered_old_edit_request->curriculum_id;

        $student = Student::find($student_curriculum->student_id);   
        $student->student_no = $registered_old_edit_request->student_no;
        $student->student_no = $registered_old_edit_request->student_no;
        $student->classification_id = $registered_old_edit_request->classification_id;
        $student->student_type_id = $registered_old_edit_request->student_type_id;


        $person = Person::find($student->person_id);
        $person->last_name = $registered_old_edit_request->last_name;
        $person->first_name = $registered_old_edit_request->first_name;
        $person->middle_name = $registered_old_edit_request->middle_name;
        $person->birthdate = $registered_old_edit_request->birthdate;        
        $person->birth_place = $registered_old_edit_request->birth_place;        
        $person->address = $registered_old_edit_request->address;
        $person->home_number = $registered_old_edit_request->home_number;
        $person->preferred_name = $registered_old_edit_request->preferred_name;
        $person->gender_id = $registered_old_edit_request->gender_id;
        $person->suffix_id = $registered_old_edit_request->suffix_id;
        $person->citizenship_id = $registered_old_edit_request->citizenship_id;
        $person->religion_id = $registered_old_edit_request->religion_id;
        $person->icard_number = $registered_old_edit_request->icard_number;
        $person->passport_number = $registered_old_edit_request->passport_number;
        $person->student_email_address = $registered_old_edit_request->student_email_address;
        $person->student_mobile_number = $registered_old_edit_request->student_mobile_number;
        $person->church_affiliation = $registered_old_edit_request->church_affiliation;
        $person->living_with_id = $registered_old_edit_request->living_with_id;
        $person->name_relation = $registered_old_edit_request->name_relation;
        $person->personal_physician = $registered_old_edit_request->personal_physician;
        $person->physician_mobile_number = $registered_old_edit_request->physician_mobile_number;
        $person->clinic_address = $registered_old_edit_request->clinic_address;
        $person->physician_office_number = $registered_old_edit_request->physician_office_number;

        $gen_user= GenUser::find($student->gen_user_id);
        $gen_user->person_id = $person->id; 
        $gen_user->username = $student->student_no;
        $gen_user->confirmation_code = md5(microtime().Config::get('app.key'));
        $gen_user->confirmed = 1;
        $gen_user->password = Hash::make($student->student_no);

        $student_family_background = StudentFamilyBackground::find($person->student_family_background_id);
        $student_family_background->fathers_name = $registered_old_edit_request->fathers_name;
        $student_family_background->mothers_name = $registered_old_edit_request->mothers_name;
        $student_family_background->fathers_mobile_number = $registered_old_edit_request->fathers_mobile_number;
        $student_family_background->mothers_mobile_number = $registered_old_edit_request->mothers_mobile_number;
        $student_family_background->fathers_occupation = $registered_old_edit_request->fathers_occupation;
        $student_family_background->mothers_occupation = $registered_old_edit_request->mothers_occupation;
        $student_family_background->fathers_employer_name = $registered_old_edit_request->fathers_employer_name;
        $student_family_background->mothers_employer_name = $registered_old_edit_request->mothers_employer_name;
        $student_family_background->fathers_employer_no = $registered_old_edit_request->fathers_employer_no;
        $student_family_background->mothers_employer_no = $registered_old_edit_request->mothers_employer_no;
        $student_family_background->fathers_email_address = $registered_old_edit_request->fathers_email_address;
        $student_family_background->mothers_email_address = $registered_old_edit_request->mothers_email_address;
        $student_family_background->parents_marital_status_id = $registered_old_edit_request->parents_marital_status_id;
        $student_family_background->save();



        $person->save();
        $student->save();
        $student_curriculum->save();
        $gen_user->save();

        if(is_array($registered_old_edit_request->student_siblings_id)){
              $student_siblings_id_arr = [];
              $name_arr = [];
              $age_arr = [];
              $level_arr = [];
              $school_arr = [];

              foreach ($registered_old_edit_request->student_siblings_id as $student_siblings) {

                if($student_siblings == 0)
                {
                    $student_siblings = new StudentSiblings();
                    $student_siblings->student_id = $student->id;
                    $student_siblings -> save();

                    $student_siblings_id_arr[] = $student_siblings -> id;
                }
                else
                {
                    $student_siblings_id_arr[] = $student_siblings;
                }
              }

              foreach ($registered_old_edit_request->name as $name)
              {
                $name_arr[] = $name;
              }

              foreach ($registered_old_edit_request->age as $age)
              {
                $age_arr[] = $age;
              }

              foreach ($registered_old_edit_request->grade_level as $level)
              {
                $level_arr[] = $level;
              }

              foreach ($registered_old_edit_request->school_university as $school)
              {
                $school_arr[] = $school;
              }

              $count = 0;

              foreach($student_siblings_id_arr as $student_siblings_id[])
              {
                $student_name = StudentSiblings::find($student_siblings_id[$count]);
                $student_name ->name = $name_arr[$count];
                $student_name ->age = $age_arr[$count];
                $student_name ->grade_level = $level_arr[$count];
                $student_name ->school_university = $school_arr[$count];
                $student_name -> save();
                $count++;
              }

        }
             
        return redirect('registrar/register_student');
    }

  
    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $action = 1;
        $student_curriculum = StudentCurriculum::find($id);
        $student = Student::find($student_curriculum->student_id);
        $person = Person::find($student->person_id);
        $gen_user = GenUser::find($student->gen_user_id);
        $gender_list = Gender::all();
        $suffix_list = Suffix::all();
        $classification_list = Classification::all();
        $religion_list = Religion::all();
        $blood_type_list = BloodType::all();
        $civil_status_list = CivilStatus::all();
        $citizenship_list = Citizenship::all();
        $classification_level_list = ClassificationLevel::all();
        $school_year_list = SchoolYear::all();
        $student_type_list = StudentType::all();
        $student_siblings_list = StudentSiblings::all();
        $living_with_list = LivingWith::all();
        $parents_marital_status_list = ParentsMaritalStatus::all();
        $student_family_background = StudentFamilyBackground::find($person->student_family_background_id);
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->select('curriculum.id', 'curriculum.curriculum_name')->get();

        return view('registrar/register_student/delete',compact('student','student_curriculum','person','gender_list','student_type_list','student_siblings_list','student_family_background','parents_marital_status_list','living_with_list','suffix_list','curriculum_subject_list', 'classification_list', 'classification_level_list','school_year_list', 'religion_list','blood_type_list','civil_status_list','citizenship_list','gen_user','action'));
      }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $student = Student::find($id);
        $student->delete();
        return redirect('registrar/register_student');
    }


}
