<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Enrollment;
use App\Models\MiscellaneousFeeDetail;
use App\Models\Person;
use App\Models\SemesterLevel;
use App\Models\Student;
use App\Models\StudentCurriculum;
use App\Models\StudentLedger;
use App\Models\Term;
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

class StudentAssessmentController extends AccountingMainController {

    /*
    return a list of country in json format based on a term
    **/
    
    public function search(){
      $term = Input::get('term');
      $result = StudentLedger::where('type','LIKE','%$term%').orWhere('credit','LIKE','%$term%')->get();
      return Response::json($result);
    }

   public function dataJson(){

      $condition = \Input::get('student_id');
      $student_assessment_list = StudentLedger::where('student_assessment.student_id','=',$condition)
                    ->select(DB::raw('max(student_assessment.id) as value'))
                    ->where('student_assessment.payment_type_id','=',1)
                    ->groupBy('student_id')->orderBy('student_assessment.id','ASC')->get();
    

      $student = array();

      foreach ($student_assessment_list as $student_balance) {
        $student_bal = $student_balance -> value;

          $value = StudentLedger::where('student_assessment.id','=',$student_bal)->select('student_assessment.id')->get()->first();
          $balance = StudentLedger::where('student_assessment.id','=',$student_bal)->select('student_assessment.balance')->get()->first();

          $student[] = array('value' => $value->id, 'balance' => $balance->balance); 
      }

      
      return response()->json($student);
    }


     public function balanceDataJson(){
          $condition = \Input::all();
          $query = StudentLedger::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          $student_assessment= $query->select(['student_assessment.id as value','student_assessment.balance as text'])->get()->last();

          return response()->json($student_assessment);
    }

    public function orArDataJson(){
          $condition = \Input::get('column');
          $query[] = StudentLedger::where('student_assessment.'.$condition.'','!=','0')->select([$condition])->orderBy('student_assessment.updated_at')->get()->last();

          return response()->json($query);
    }
 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $semester_level_list = SemesterLevel::orderBy('semester_level.id','ASC')->get();
        $term_list = Term::orderBy('term.id','ASC')->get();
        // Show the page
        return view('student_assessment.index',compact('classification_list', 'classification_level_list', 'semester_level_list', 'term_list'));
    }

    public function getDetail()
    {
      $action = 1;
      $student_id = \Input::get('student_id');
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $semester_level_id = \Input::get('semester_level_id');
      $term_id = \Input::get('term_id');

      $enrollment= Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->join('program','curriculum.program_id','=','program.id')
            ->where('student_id',$student_id)
            ->select('enrollment.id','section.section_name','student_curriculum.curriculum_id','curriculum.program_id','program.program_code')->get()->last();

      $term = Term::where('id', $term_id)->get()->last();
      $classification_level = ClassificationLevel::where('id', $classification_level_id)->get()->last();

      $student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name')->get()->last();

      $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
            ->where('classification_id',$classification_id)
            ->where('program_id',$enrollment->program_id)
            ->where('term_id',$term_id)
            ->select('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount')->get();

      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$enrollment->curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('curriculum_subject.semester_level_id',$semester_level_id)
            ->select('curriculum_subject.id','subject.name','subject.credit_unit','subject.hour_unit','subject_offered.lec_fee_per_unit','subject_offered.lab_fee_per_unit')->get();

      $student_ledger = StudentLedger::where('student_id', $student_id)
            ->where('term_id', $term_id)
            ->select('student_ledger.id','student_ledger.total_balance')->get()->last();

      return view('student_assessment/detail', 
        compact(
            'enrollment',
            'term',
            'classification_level',
            'student',
            'miscellaneous_fee_detail_list',
            'curriculum_subject_list',
            'student_ledger',
            'classification_id'
        )
      );

    }

    public function AssessmentReport()
    {
      
      $student_id = \Input::get('student_id');
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $semester_level_id = \Input::get('semester_level_id');
      $term_id = \Input::get('term_id');

      $logo = str_replace("\\","/",public_path())."/images/mzednewlogo.png";

      $enrollment= Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->join('program','curriculum.program_id','=','program.id')
            ->where('student_id',$student_id)
            ->select('enrollment.id','section.section_name','student_curriculum.curriculum_id','curriculum.program_id','program.program_code')->get()->last();

      $term = Term::where('id', $term_id)->get()->last();
      $classification_level = ClassificationLevel::where('id', $classification_level_id)->get()->last();

      $student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name')->get()->last();

      $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
            ->where('classification_id',$classification_id)
            ->where('program_id',$enrollment->program_id)
            ->where('term_id',$term_id)
            ->select('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount')->get();

      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$enrollment->curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('curriculum_subject.semester_level_id',$semester_level_id)
            ->select('curriculum_subject.id','subject.name','subject.credit_unit','subject.hour_unit','subject_offered.lec_fee_per_unit','subject_offered.lab_fee_per_unit')->get();

      $student_ledger = StudentLedger::where('student_id', $student_id)
            ->where('term_id', $term_id)
            ->select('student_ledger.id','student_ledger.total_balance')->get()->last();

      $pdf = \PDF::loadView('student_assessment/student_assessment_report', array('logo'=>$logo,'enrollment'=>$enrollment,'student'=>$student,'miscellaneous_fee_detail_list'=>$miscellaneous_fee_detail_list,'curriculum_subject_list'=>$curriculum_subject_list,'student_ledger'=>$student_ledger,'term'=>$term,'classification_level'=>$classification_level,'classification_id'=>$classification_id));

      return $pdf->stream();

    }


/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $student_id = \Input::get('student_id');   
          $student_assessment_list = StudentLedger::where('student_assessment.student_id',$student_id)
              ->join('term','student_assessment.term_id','=','term.id')
              ->join('payment_type','student_assessment.payment_type_id','=','payment_type.id')
              ->select(array('student_assessment.id','student_assessment.created_at','student_assessment.debit','student_assessment.credit','student_assessment.balance','student_assessment.total_balance','student_assessment.remark','term.term_name','payment_type.description'))
              ->orderBy('student_assessment.id', 'ASC');

        return Datatables::of($student_assessment_list)
            ->remove_column('id')
            ->make();
    }
   /**
     * Reorder items
     *
     * @param items list
     * @return items from @param
     */
    public function getReorder(ReorderRequest $request) {
        $list = $request->list;
        $items = explode(",", $list);
        $order = 1;                 
        foreach ($items as $value) {
            if ($value != '') {
                StudentLedger::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
