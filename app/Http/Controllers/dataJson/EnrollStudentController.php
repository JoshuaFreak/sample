<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;  
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\StudentCurriculum;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;
use Hash;

class EnrollStudentController extends BaseController {

    public function dataJson(){
        $condition = \Input::all();
        $query = Enrollment::leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
                ->leftJoin('term','enrollment.term_id','=','term.id')
                ->leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
                ->leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->leftJoin('student','student_curriculum.student_id','=','student.id')
                ->leftJoin('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
                // ->leftJoin('program','curriculum.program_id','=','program.id')
                ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
                ->leftJoin('person','student.person_id','=','person.id')
                ->leftJoin('suffix','person.suffix_id','=','suffix.id')
                ->leftJoin('religion','person.religion_id','=','religion.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
                ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
                ->leftJoin('blood_type','person.blood_type_id','=','blood_type.id')
                // ->groupBy('student.student_no')
                ->select('enrollment.id','enrollment.classification_level_id','enrollment.is_new','enrollment.payment_scheme_id','payment_scheme.payment_scheme_name','enrollment.section_id','enrollment.term_id','classification_level.level','student_curriculum.curriculum_id','student_curriculum.id as student_curriculum_id','student_curriculum.student_id as student_id','curriculum.curriculum_name','term.term_name','classification.classification_name','student_curriculum.classification_id','student.student_no','student.school_no','person.id as person_id','person.last_name','person.first_name','person.middle_name','suffix.suffix_name','person.address','person.home_number','person.student_mobile_number','person.student_email_address','person.birthdate','person.birth_place','religion.religion_name','gender.gender_name','civil_status.civil_status_name','citizenship.citizenship_name','blood_type.blood_type_name');
      
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


    public function StudentdataJson(){
      $condition = \Input::all();
      $query = Student::join('person','student.person_id','=','person.id')
                        ->select('student.id','student.student_no','person.first_name','person.middle_name','person.last_name');
     
     // print $condition["query"];
      //$query->where('last_name', 'LIKE', "%get%");  


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

      $student = $query->orderBy('last_name','ASC')->get();

      return response()->json($student);
    }


    public function dataJsonStudent(){

      $condition = \Input::all();

      $query = StudentCurriculum::join('person','student.person_id','=','person.id')
                    ->join('student','student_curriculum.student_id','=','student.id')
                    ->select('student.id','student.student_no','person.first_name','person.middle_name','person.last_name');
      foreach($condition as $column => $value)
      {
        $query->where('student_curriculum.'.$column, '=', $value);
      }

        $student_curriculum = $query->select(['student.id as value','student_no as text'])->get();
      

      return response()->json($student_curriculum);
    }

    public function dataJsonTransferee(){
        $condition = \Input::all();
        $query = Enrollment::join('classification_level','enrollment.classification_level_id','=','classification_level.id')
                ->join('term','enrollment.term_id','=','term.id')
                ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
                // ->join('program','curriculum.program_id','=','program.id')
                ->join('classification','student_curriculum.classification_id','=','classification.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('suffix','person.suffix_id','=','suffix.id')
                ->leftJoin('religion','person.religion_id','=','religion.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
                ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
                ->leftJoin('blood_type','person.blood_type_id','=','blood_type.id')
                // ->groupBy('student.student_no')
                ->select('enrollment.id','enrollment.classification_level_id','enrollment.section_id','enrollment.term_id','classification_level.level','student_curriculum.curriculum_id','student_curriculum.student_id as student_id','curriculum.curriculum_name','term.term_name','classification.classification_name','student_curriculum.classification_id','student.student_no','student.school_no','person.id as person_id','person.last_name','person.first_name','person.middle_name','suffix.suffix_name','person.address','person.home_number','person.student_mobile_number','person.student_email_address','person.birthdate','person.birth_place','religion.religion_name','gender.gender_name','civil_status.civil_status_name','citizenship.citizenship_name','blood_type.blood_type_name')
                ->groupBy('classification.id');
      
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

     public function StudentSectionDataJson()
     {
        $condition = \Input::all();
        $query = Enrollment::join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                      ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                      ->join('person', 'student.person_id', '=', 'person.id');
        
        foreach($condition as $column => $value)
        {
          $query->where('enrollment.'.$column, '=', $value);
        }

        $enrollment = $query->select(['enrollment.id as value', 'person.last_name as text'])->get();

        return response()->json($enrollment);
    }


}
