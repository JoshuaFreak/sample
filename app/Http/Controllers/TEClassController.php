<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ActionTaken;
use App\Models\GradingPeriod;
use App\Models\Schedule;
use App\Models\TEClass;
use App\Models\Term;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TEClassController extends TeachersPortalMainController {   
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
        $term_list = Term::where('classification_id', '=', 5)
                ->orderBy('term.id','DESC')->get();
        $action_taken_list = ActionTaken::orderBy('action_taken.id','ASC')->get();
        $grading_period_list = GradingPeriod::where('classification_id', '=', 5)
                ->orderBy('grading_period.id','ASC')->get();
        return view('teachers_portal/te_class.index', compact('schedule_list', 'term_list', 'action_taken_list', 'grading_period_list'));
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
      $term_id = \Input::get('term_id');
        if($term_id != "" && $term_id != null) 
        {

            $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->where('class.term_id','=',$term_id)
                ->where('class.teacher_id', '=', Auth::user()->username)
                ->select(array('class.id','section.section_name','subject.code','time_start.time as time_start','time_end.time as time_end', 'time_start.time_session as time_start_session','time_end.time_session as time_end_session','room.room_name','day.day_code','term.term_name','class.term_id'))
                ->orderBy('class.term_id', 'DESC');
        }
        else
        {
            $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->where('section.classification_id','=',5)
                ->where('class.teacher_id', '=', Auth::user()->username)
                ->select(array('class.id','section.section_name','subject.code','time_start.time as time_start','time_end.time as time_end', 'time_start.time_session as time_start_session','time_end.time_session as time_end_session','room.room_name','day.day_code','term.term_name','class.term_id'))
                ->orderBy('class.term_id', 'DESC');
        }
        return Datatables::of($schedule_list)
                ->editColumn('time_start','{{ ucwords(strtolower($time_start." ".$time_start_session." - ".$time_end." ".$time_end_session." - ( ".$room_name." - ".$day_code.")")) }}')
                ->add_column('actions','<button class="btn btn-sm btn-primary" data-id="{{{$id}}}" data-section_name="{{{$section_name}}}" data-code="{{{$code}}}" data-term_id="{{{$term_id}}}" data-toggle="modal" data-target="#gradingperiodmodal"><span class="glyphicon glyphicon-eye-open"></span></button>
                    <button class="btn btn-sm btn-success" data-id="{{{$id}}}" data-section_name="{{{$section_name}}}" data-code="{{{$code}}}" data-toggle="modal" data-target="#gradecomputationModal"><span class="glyphicon glyphicon-eye-open"></span></button>
                    <button class="btn btn-sm btn-info" data-id="{{{$id}}}" data-section_name="{{{$section_name}}}" data-code="{{{$code}}}" data-toggle="modal" data-target="#finalgrademodal"><span class="glyphicon glyphicon-eye-open"></span></button>
                    <button class="btn btn-sm btn-warning" data-id="{{{$id}}}" data-section_name="{{{$section_name}}}" data-code="{{{$code}}}" data-toggle="modal" data-target="#refreshstudentmodal"><span class="glyphicon glyphicon-refresh"></span></button>
                    <button class="btn btn-sm btn-danger" data-id="{{{$id}}}" data-section_name="{{{$section_name}}}" data-code="{{{$code}}}" data-toggle="modal" data-target="#masterlistmodal"><span class="glyphicon glyphicon-list"></span></button>
                    <button class="btn btn-sm btn-secondary" data-id="{{{$id}}}" data-section_name="{{{$section_name}}}" data-code="{{{$code}}}" data-toggle="modal" data-target="#dropstudentmodal"><span class="glyphicon glyphicon-circle-arrow-down"></span></button>
                    <button class="btn btn-sm btn-inverse" data-id="{{{$id}}}" data-section_name="{{{$section_name}}}" data-code="{{{$code}}}" data-toggle="modal" data-target="#interventionmodal"><span class="glyphicon glyphicon-user"></span></button>')
                ->remove_column('id','term_id', 'time_end','time_start_session','time_end_session', 'room_name', 'day_code')
                ->make();

    }
  
}
