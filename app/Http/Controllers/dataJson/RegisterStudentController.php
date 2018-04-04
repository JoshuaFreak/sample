<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Generic\GenPerson;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\BloodType;
use App\Models\Citizenship;
use App\Models\CivilStatus;
use App\Models\Classification;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Gender;
use App\Models\Guardian;
use App\Models\Person;
use App\Models\Religion;
use App\Models\Student;
use App\Models\StudentCurriculum;
use App\Models\Suffix;
use App\Http\Requests\RegisteredRequest;
use App\Http\Requests\RegisteredEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;
use Hash;

class RegisterStudentController extends BaseController {

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function dataJson(){
      $condition = \Input::all();
      $query = Curriculum::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $curriculum = $query->select([ 'id as value','program_code as text'])->get();

      return response()->json($curriculum);
    }

    public function dataJsonRegisteredStudent(){
        $condition = \Input::all();
        $query = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
                ->join('student_type','student.student_type_id','=','student_type.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
                ->join('classification','student_curriculum.classification_id','=','classification.id')
                ->join('person','student.person_id','=','person.id')
                ->join('student_family_background','person.student_family_background_id','=','student_family_background.id')
                ->leftJoin('religion','person.religion_id','=','religion.id')
                ->leftJoin('suffix','person.suffix_id','=','suffix.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
                ->leftJoin('living_with','person.living_with_id','=','living_with.id')
                ->leftJoin('parents_marital_status','student_family_background.parents_marital_status_id','=','parents_marital_status.id')
                ->select('student_curriculum.id','student_curriculum.is_sped','student_type.name','student_curriculum.curriculum_id','student_curriculum.classification_id','student_curriculum.student_id','student.student_no','student.school_no','student.status_id','classification.classification_name','curriculum.curriculum_name','person.id as person_id','person.last_name','person.first_name','person.middle_name','person.address','person.birthdate','person.birth_place','person.home_number','person.preferred_name','person.student_email_address','person.student_mobile_number','person.passport_number','person.icard_number','person.church_affiliation','person.name_relation','person.medical_condition','person.maintenance_medication','person.personal_physician','person.physician_mobile_number','person.physician_office_number','person.clinic_address','student_family_background.fathers_name','student_family_background.mothers_name','student_family_background.fathers_mobile_number','student_family_background.mothers_mobile_number','student_family_background.fathers_occupation','student_family_background.mothers_occupation','student_family_background.fathers_employer_name','student_family_background.mothers_employer_name','student_family_background.fathers_employer_no','student_family_background.mothers_employer_no','student_family_background.fathers_email_address','student_family_background.mothers_email_address','religion.religion_name','suffix.suffix_name','gender.gender_name','citizenship.citizenship_name','living_with.name as living_with_name', 'parents_marital_status.name as parents_marital_status_name')
                ->orderBy('student_curriculum.student_id');

        foreach($condition as $column => $value)
        {
            if($column == 'query')
              {
                $query->where('first_name', 'LIKE', "%$value%")
                      ->orWhere('middle_name', 'LIKE', "%$value%")
                      ->orWhere('last_name', 'LIKE', "%$value%")
                      ->orWhere('student_no', 'LIKE', "%$value%");
              }
              else
              {
                $query->where($column, '=', $value);
              }
        }

        $student_curriculum = $query->get();

        return response()->json($student_curriculum);
    }


    public function dataJsonRegisteredOldStudent(){
        $condition = \Input::all();
        $query = Student::join('student_type','student.student_type_id','=','student_type.id')
                ->join('person','student.person_id','=','person.id')
                ->join('student_family_background','person.student_family_background_id','=','student_family_background.id')
                ->join('religion','person.religion_id','=','religion.id')
                ->join('suffix','person.suffix_id','=','suffix.id')
                ->join('gender','person.gender_id','=','gender.id')
                ->join('citizenship','person.citizenship_id','=','citizenship.id')
                ->join('living_with','person.living_with_id','=','living_with.id')
                ->join('parents_marital_status','student_family_background.parents_marital_status_id','=','parents_marital_status.id')
                ->where('student.status_id','!=',2)
                ->select('student.id as student_id','student_type.name','student.student_no','student.school_no','person.last_name','person.first_name','person.middle_name','person.address','person.birthdate','person.birth_place','person.home_number','person.preferred_name','person.student_email_address','person.student_mobile_number','person.passport_number','person.icard_number','person.church_affiliation','person.name_relation','person.medical_condition','person.maintenance_medication','person.personal_physician','person.physician_mobile_number','person.physician_office_number','person.clinic_address','student_family_background.fathers_name','student_family_background.mothers_name','student_family_background.fathers_mobile_number','student_family_background.mothers_mobile_number','student_family_background.fathers_occupation','student_family_background.mothers_occupation','student_family_background.fathers_employer_name','student_family_background.mothers_employer_name','student_family_background.fathers_employer_no','student_family_background.mothers_employer_no','student_family_background.fathers_email_address','student_family_background.mothers_email_address','religion.religion_name','suffix.suffix_name','gender.gender_name','citizenship.citizenship_name','living_with.name as living_with_name', 'parents_marital_status.name as parents_marital_status_name');

        foreach($condition as $column => $value)
        {
            if($column == 'query')
              {
                $query->where('first_name', 'LIKE', "%$value%")
                      ->orWhere('middle_name', 'LIKE', "%$value%")
                      ->orWhere('last_name', 'LIKE', "%$value%")
                      ->orWhere('student_no', 'LIKE', "%$value%");
              }
              else
              {
                $query->where($column, '=', $value);
              }
        }

        $student = $query->get();

        return response()->json($student);
    }

    public function dataJsonRegisteredStudentTransfer(){
        $condition = \Input::all();
        $query = Student::join('student_type','student.student_type_id','=','student_type.id')
                ->join('person','student.person_id','=','person.id')
                ->join('suffix','person.suffix_id','=','suffix.id')
                ->join('gender','person.gender_id','=','gender.id')
                ->where('student.status_id','!=',2)
                ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name');

        foreach($condition as $column => $value)
        {
            if($column == 'query')
              {
                $query->where('first_name', 'LIKE', "%$value%")
                      ->orWhere('middle_name', 'LIKE', "%$value%")
                      ->orWhere('last_name', 'LIKE', "%$value%")
                      ->orWhere('student_no', 'LIKE', "%$value%");
              }
              else
              {
                $query->where($column, '=', $value);
              }
        }

        $student = $query->get();

        return response()->json($student);
    }


}
