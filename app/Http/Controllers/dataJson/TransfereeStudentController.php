<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;  
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\TransfereeGradeAverage;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;
use Hash;

class TransfereeStudentController extends BaseController {

    public function dataJson(){
        $condition = \Input::all();
        $query = TransfereeGradeAverage::join('classification_level','transferee_grade_average.classification_level_id','=','classification_level.id')
                ->join('student','transferee_grade_average.student_id','=','student.id')
                ->leftJoin('school','transferee_grade_average.school_id','=','school.id')
                ->join('request','transferee_grade_average.request_id','=','request.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('suffix','person.suffix_id','=','suffix.id')
                ->select('transferee_grade_average.id','transferee_grade_average.classification_level_id','transferee_grade_average.school_id','transferee_grade_average.student_id','transferee_grade_average.request_id','transferee_grade_average.date_request','classification_level.level','student.student_no','person.id as person_id','person.last_name','person.first_name','person.middle_name','suffix.suffix_name');
      
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

        $transferee_grade_average = $query->get();
        return response()->json($transferee_grade_average);
    }

}
