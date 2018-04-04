<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\ClassificationLevel;
use App\Models\Employee;
use App\Models\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\TransfereeGradeAverage;
use App\Http\Requests\StudentLedgerRequest;
use App\Http\Requests\StudentLedgerEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;
use DB;
use Excel;

class RequestFormController extends RegistrarMainController {

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
    
        $request_list = Request::orderBy('request.id','ASC')->get();
        // Show the page
        return view('transferee/request_form.index', compact('request_list'));
    }

    public function getDetail()
    {

      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');
      $school_id = \Input::get('school_id');
      $request_id = \Input::get('request_id');
      
      $transferee_grade_average= TransfereeGradeAverage::join('request', 'transferee_grade_average.request_id', '=', 'request.id')
            ->where('student_id',$student_id)
            ->where('classification_level_id',$classification_level_id)
            ->where('school_id',$school_id)
            ->select(['transferee_grade_average.id','transferee_grade_average.classification_level_id','transferee_grade_average.school_id','transferee_grade_average.student_id','transferee_grade_average.school_year','transferee_grade_average.date_request','transferee_grade_average.request_id','request.name','transferee_grade_average.admission_level'])->get()->last();

      $student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select(['student.id','student.student_no','person.last_name','person.first_name','person.middle_name'])->get()->last();

      $classification_level = ClassificationLevel::where('id', $classification_level_id)
            ->select(['classification_level.id', 'classification_level.level'])->get()->last();

      $school = School::where('id', $school_id)
            ->select(['school.id', 'school.school_name', 'school.school_address'])->get()->last();

      $request = Request::where('id', $request_id)
            ->select(['request.id', 'request.name'])
            ->get()->last();

      $employee = Employee::join('person', 'employee.person_id', '=','person.id')
              ->where('employee.gen_role_id', 1) 
              ->select(['employee.id', 'person.first_name', 'person.middle_name', 'person.last_name'])
              ->get()->first(); 
              
 
      //  if($transferee_grade_average == null || $student == null || $school == null || $classification_level == null || $employee == null || $request == null){

      //     return view('transferee/request_form/empty');

      // }
      // else{


      return view('transferee/request_form/detail', 
        compact(
            'transferee_grade_average',
            'student',
            'school',
            'classification_level',
            'employee',
            'request'
        )
      );

    // }
  }

    public function pdfRequestForm()
    {
      
      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');
      $school_id = \Input::get('school_id');

      $logo = str_replace("\\","/",public_path())."/images/logo.png";
      $transferee_grade_average= TransfereeGradeAverage::join('request', 'transferee_grade_average.request_id', '=', 'request.id')
            ->where('student_id',$student_id)
            ->where('classification_level_id',$classification_level_id)
            ->where('school_id',$school_id)
            ->select(['transferee_grade_average.id','transferee_grade_average.classification_level_id','transferee_grade_average.school_id','transferee_grade_average.student_id','transferee_grade_average.school_year','transferee_grade_average.date_request','transferee_grade_average.request_id','request.name','transferee_grade_average.admission_level'])->get()->last();
            
      $student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select(['student.id','student.student_no','person.last_name','person.first_name','person.middle_name'])->get()->last();

      $classification_level = ClassificationLevel::where('id', $classification_level_id)
            ->select(['classification_level.id', 'classification_level.level'])->get()->last();

      $school = School::where('id', $school_id)
            ->select(['school.id', 'school.school_name', 'school.school_address'])->get()->last();

      $employee = Employee::join('person', 'employee.person_id', '=','person.id')
              ->where('employee.gen_role_id', 1) 
              ->select(['employee.id', 'person.first_name', 'person.middle_name', 'person.last_name'])
              ->get()->first(); 

      $pdf = \PDF::loadView('transferee/request_form/request_form_sheet', array('logo'=>$logo,'transferee_grade_average'=>$transferee_grade_average,'student'=>$student,'classification_level'=>$classification_level,'school'=>$school,'employee'=>$employee));

      return $pdf->stream();

    }

}
