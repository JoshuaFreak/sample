<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ActionTaken;
use App\Models\GradingPeriod;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Schedule;
use App\Models\Term;
use App\Models\TEClass;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class BEClassController extends TeachersPortalMainController {   
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('enrollment_class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('enrollment', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->groupBy('student.id')
                ->select(array('schedule.id','student.student_no','person.last_name','person.first_name','person.middle_name'))->get();
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $term_list = Term::orderBy('term.term_name','ASC')->get();
        $action_taken_list = ActionTaken::orderBy('action_taken.id','ASC')->get();
        $grading_period_list = GradingPeriod::orderBy('grading_period.id','ASC')->get();
        return view('teachers_portal/be_class.index', compact('term_list', 'classification_list', 'classification_level_list', 'schedule_list', 'action_taken_list', 'grading_period_list'));
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
      $term_id = \Input::get('term_id');
      $classification_level_id = \Input::get('classification_level_id');

        if($term_id != "" && $term_id != null && $classification_level_id != "" && $classification_level_id != null) 
        {
          $class_list = TEClass::join('term','class.term_id','=','term.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->where('class.term_id','=',$term_id)
                ->where('class.classification_level_id','=',$classification_level_id)
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->where('subject.is_pace', '=', 0)
                ->select(array('class.id','classification_level.level','section.section_name','subject.name','term.term_name','class.term_id','subject.is_pace','subject_offered.subject_id','class.classification_level_id','class.section_id','subject_offered.classification_id','class.term_id'))
                ->orderBy('class.term_id', 'DESC')                
                ->groupBy('subject.id')
                ->groupBy('classification_level.id')
                ->groupBy('term.id');
        }
        elseif($classification_level_id != "" && $classification_level_id != null) 
        {
          $class_list = TEClass::join('term','class.term_id','=','term.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->where('class.classification_level_id','=',$classification_level_id)
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->where('subject.is_pace', '=', 0)
                ->select(array('class.id','classification_level.level','section.section_name','subject.name','term.term_name','class.term_id','subject.is_pace','subject_offered.subject_id','class.classification_level_id','class.section_id','subject_offered.classification_id','class.term_id'))
                ->orderBy('class.term_id', 'DESC')
                ->groupBy('subject.id')
                ->groupBy('classification_level.id')
                ->groupBy('term.id');
        }
        elseif($term_id != "" && $term_id != null) 
        {
          $class_list = TEClass::join('term','class.term_id','=','term.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->where('class.term_id','=',$term_id)
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->where('subject.is_pace', '=', 0)
                ->select(array('class.id','classification_level.level','section.section_name','subject.name','term.term_name','class.term_id','subject.is_pace','subject_offered.subject_id','class.classification_level_id','class.section_id','subject_offered.classification_id','class.term_id'))
                ->orderBy('class.term_id', 'DESC')
                ->groupBy('subject.id')
                ->groupBy('classification_level.id')
                ->groupBy('term.id');
        }
        else
        {
            $class_list = TEClass::join('term','class.term_id','=','term.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                // ->where('section.classification_id','!=',5)
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->where('subject.is_pace', '=', 0)
                ->select(array('class.id','classification_level.id as classification_level_id','classification_level.level','section.section_name','subject.name','term.term_name','class.term_id','subject.is_pace','subject_offered.subject_id','class.section_id','subject.id as subject_id','subject_offered.classification_id'))
                ->orderBy('classification_level.id', 'ASC')                
                ->groupBy('subject.id')
                ->groupBy('classification_level.id')
                ->groupBy('term.id');
        }
        return Datatables::of($class_list)
                ->editColumn('level','{{ ucwords(strtolower($level." ".$section_name)) }}')
                ->add_column('actions','@if($is_pace == 1)<img class="button" src="{{{ asset("assets/site/images/teachers_portal/grading-period-icon.png") }}}" data-id="{{{$id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-name="{{{$name}}}" data-term_id="{{{$term_id}}}" data-subject_id="{{{$subject_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-classification_id="{{{$classification_id}}}" data-section_id="{{{$section_id}}}" data-toggle="modal" data-target="#pacegradingperiodmodal">
                    <img class="button" src="{{{ asset("assets/site/images/teachers_portal/summary-grading-period-icon.png") }}}" data-id="{{{$id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-name="{{{$name}}}" data-classification_id="{{{$classification_id}}}" data-term_id="{{{$term_id}}}" data-subject_id="{{{$subject_id}}}" data-toggle="modal" data-target="#gradecomputationpacemodal">
                    @else<img class="button" src="{{{ asset("assets/site/images/teachers_portal/grading-period-icon.png") }}}" data-id="{{{$id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-name="{{{$name}}}" data-term_id="{{{$term_id}}}" data-subject_id="{{{$subject_id}}}" data-classification_id="{{{$classification_id}}}" data-toggle="modal" data-target="#gradingperiodmodal">
                    <img class="button" src="{{{ asset("assets/site/images/teachers_portal/summary-grading-period-icon.png") }}}" data-id="{{{$id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-name="{{{$name}}}" data-term_id="{{{$term_id}}}" data-subject_id="{{{$subject_id}}}" data-classification_id="{{{$classification_id}}}" data-toggle="modal" data-target="#gradecomputationmodal">@endif
                    <img class="button" src="{{{ asset("assets/site/images/teachers_portal/refresh-studentlist-icon.png") }}}" data-id="{{{$id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-name="{{{$name}}}" data-toggle="modal" data-target="#refreshstudentmodal">
                    <img class="button" src="{{{ asset("assets/site/images/teachers_portal/masterlist-icon.png") }}}" data-id="{{{$id}}}" data-level="{{{$level}}}" data-level_id="{{{$classification_level_id}}}"  data-section_id="{{{ $section_id }}}" data-section_name="{{{$section_name}}}" data-name="{{{$name}}}" data-subject_id="{{{$subject_id}}}" data-term_id="{{{$term_id}}}" data-toggle="modal" data-target="#studentlistremarksmodal">')
                    // <img class="button" src="{{{ asset("assets/site/images/teachers_portal/masterlist-icon.png") }}}" data-id="{{{$id}}}" data-level="{{{$level}}}" data-level_id="{{{$classification_level_id}}}"  data-section_id="{{{ $section_id }}}" data-section_name="{{{$section_name}}}" data-name="{{{$name}}}" data-toggle="modal" data-target="#masterlistmodal">
                    
                ->remove_column('id', 'section_name','is_pace','subject_id','classification_level_id','section_id','classification_id','term_id')
                ->make();

    }
  
}
