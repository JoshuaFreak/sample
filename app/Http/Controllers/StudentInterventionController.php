<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\StudentIntervention;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentInterventionController extends TeachersPortalMainController {   
   
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function interventionData(){
          
        $class_id = \Input::get('class_id');
        $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                // ->where('class.id','=',$class_id)
                ->select(array('enrollment.id','student_curriculum.student_id','student.student_no','student.classification_id','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  

        return Datatables::of($enrollment_list)
                ->editColumn('last_name','{{ ucwords(strtolower($last_name.",  ".$first_name."  ".$middle_name)) }}')
                ->editColumn('id', '<img class="button" src="{{{ asset("assets/site/images/teachers_portal/intervention.png") }}}" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-class_id="'.$class_id.'" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#studentinterventionmodal">')
                ->add_column('grade', '')
                ->add_column('absences', '')
                ->add_column('late', '')
                ->remove_column('student_id','first_name', 'middle_name', 'classification_id')
                ->make();

    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function StudentInterventionData(){
          
        $class_id = \Input::get('class_id');
        $student_id = \Input::get('student_id');
        $student_intervention_list = StudentIntervention::join('action_taken', 'student_intervention.action_taken_id', '=', 'action_taken.id')
                ->join('grading_period', 'student_intervention.grading_period_id', '=', 'grading_period.id')
                ->leftJoin('class','student_intervention.class_id','=','class.id')
                ->leftJoin('classification_level','class.classification_level_id','=','classification_level.id')
                ->leftJoin('section','class.section_id','=','section.id')
                ->leftJoin('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->leftJoin('subject','subject_offered.subject_id','=','subject.id')
                ->leftJoin('student','student_intervention.student_id','=','student.id')
                ->leftJoin('person','student.person_id','=','person.id')
                // ->where('student_intervention.class_id','=',$class_id)
                ->where('student_intervention.student_id','=',$student_id)
                ->select(array('student_intervention.id','student_intervention.teacher_comment','action_taken.action_taken_name','student_intervention.created_at','subject.name', 'subject.code','person.last_name','person.first_name', 'person.middle_name','classification_level.level', 'section.section_name', 'student_intervention.classification_id'))->orderBy('student_intervention.created_at','DESC');  

        return Datatables::of($student_intervention_list)
                ->add_column('action', '<button class="btn btn-sm btn-success active" data-id="{{{$id}}}" data-code="{{{$code}}}" data-name="{{{$name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#editinterventionmodal"><span class="glyphicon glyphicon-pencil"></span> Edit</a></button>
                    &nbsp;&nbsp;<button class="btn btn-sm btn-danger active" data-id="{{{$id}}}" data-code="{{{$code}}}" data-name="{{{$name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#deleteinterventionmodal"><span class="glyphicon glyphicon-pencil"></span> Delete</a></button>')
                ->edit_column('created_at', '{{{date("M d, Y",strtotime($created_at))}}}')
                ->remove_column('id','name', 'code', 'last_name','first_name', 'middle_name','level', 'section_name', 'classification_id')
                ->make();

    }

    public function postCreate(){

        $teacher_comment=\Input::get('teacher_comment');
        $action_taken_id=\Input::get('action_taken_id');
        $grading_period_id=\Input::get('grading_period_id');
        $student_id=\Input::get('student_id');
        // $class_id=\Input::get('class_id');
        $classification_id=\Input::get('classification_id');
        $term_id=\Input::get('term_id');

        $employee = Employee::where('employee_no','=',Auth::user()->username)->get()->last();

        $student_intervention = new StudentIntervention();
        $student_intervention->teacher_comment = $teacher_comment;
        $student_intervention->action_taken_id = $action_taken_id;
        $student_intervention->grading_period_id = $grading_period_id;
        $student_intervention->student_id = $student_id;
        // $student_intervention->class_id = $class_id;
        $student_intervention->classification_id = $classification_id;
        $student_intervention->term_id = $term_id;
        $student_intervention->teacher_id = $employee->id;
        $student_intervention-> save();
    }

    public function editdataJson(){

      $condition = \Input::all();
      $query = StudentIntervention::leftJoin('action_taken','student_intervention.action_taken_id','=','action_taken.id')
            ->leftJoin('grading_period','student_intervention.grading_period_id','=','grading_period.id')
            ->select(array('student_intervention.id', 'student_intervention.action_taken_id', 'student_intervention.grading_period_id', 'student_intervention.teacher_comment'));

      foreach($condition as $column => $value)
      {
        $query->where('student_intervention.id', '=', $value);    
      }

      $student_intervention = $query->orderBy('student_intervention.id','ASC')->get();

      return response()->json($student_intervention);

      $result = StudentIntervention::get();
       return response()->json($result);
    }

    public function postEdit() {

        $id=\Input::get('id');
        $teacher_comment=\Input::get('teacher_comment');
        $action_taken_id=\Input::get('action_taken_id');
        $grading_period_id=\Input::get('grading_period_id');
      
        $student_intervention = StudentIntervention::find($id);
        $student_intervention->teacher_comment = $teacher_comment;
        $student_intervention->action_taken_id = $action_taken_id;
        $student_intervention->grading_period_id = $grading_period_id;
        $student_intervention -> updated_by_id = Auth::id();
        $student_intervention -> save();

    }

    public function postDelete()
    {
        $id=\Input::get('id');
        
        $student_intervention = StudentIntervention::find($id);
        $student_intervention->delete();
    }
}
