<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\StudentLedger;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\SemesterLevel;
use App\Models\Term;
use App\Models\StudentCurriculum;
use App\Models\Student;
use App\Models\Person;
use App\Models\Enrollment;
use App\Models\Curriculum;
use App\Models\Schedule;
use App\Models\MiscellaneousFeeDetail;
use App\Models\CurriculumSubject;
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

class CertificateofRegistrationController extends RegistrarMainController {

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
        return view('registrar_report/cor.index',compact('classification_list', 'classification_level_list', 'semester_level_list', 'term_list'));
    }

    public function getDetail()
    {

      $student_id = \Input::get('student_id');
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $semester_level_id = \Input::get('semester_level_id');
      $term_id = \Input::get('term_id');
      $program_id = \Input::get('program_id');
      $section_id = \Input::get('section_id');
      
      $enrollment= Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->join('program','curriculum.program_id','=','program.id')
            ->where('student_id',$student_id)
            ->select('enrollment.id','section.section_name','student_curriculum.curriculum_id','program.program_code')->get()->last();

      $student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name')->get()->last();
      $term = Term::where('id', $term_id)->get()->last();
      $classification_level = ClassificationLevel::where('id', $classification_level_id)->get()->last();

      $student_curriculum= StudentCurriculum::where('student_id',$student_id)->get()->last();
      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$student_curriculum->curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('curriculum_subject.semester_level_id',$semester_level_id)
            ->select('curriculum_subject.id','curriculum_subject.subject_offered_id','subject.code','subject.name','subject.credit_unit','subject.hour_unit')->get();

      $schedule_list = Schedule::join('class','schedule.class_id','=','class.id')
          ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
          ->join('time as time_start','schedule.time_start','=','time_start.id')
          ->join('time as time_end','schedule.time_end','=','time_end.id')
          ->join('room','schedule.room_id','=','room.id')
          ->where('subject_offered.program_id',$program_id)
          ->where('class.section_id',$section_id)
          ->where('class.term_id',$term_id)
          ->select('schedule.id','schedule.class_id','class.subject_offered_id', 'time_start.id as time_start_id','time_end.id as time_end_id','time_start.time as time_start', 'time_start.time_session as session_start','time_end.time as time_end','time_end.time_session as session_end','room.room_name','schedule.room_id')
          ->groupBy('time_start','time_end','room_id')->get();

      $day_list = Schedule::join('class','schedule.class_id','=','class.id')
          ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
          ->join('day','schedule.day_id','=','day.id')
          ->join('room','schedule.room_id','=','room.id')
          ->where('subject_offered.program_id',$program_id)
          ->where('class.section_id',$section_id)
          ->where('class.term_id',$term_id)
          ->select('schedule.id','schedule.time_start','schedule.time_end','schedule.room_id','day.day_code')->orderBy('day_id')->get();

      $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
            ->where('classification_id',$classification_id)
            ->where('program_id',$program_id)
            ->where('term_id',$term_id)
            ->select('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount')->get();

      return view('registrar_report/cor/detail', 
        compact(
            'enrollment',
            'student',
            'term',
            'classification_level',
            'classification_id',
            'curriculum_subject_list',
            'schedule_list',
            'day_list',
            'miscellaneous_fee_detail_list'
        )
      );

    }

    public function CORreport()
    {
      
      $student_id = \Input::get('student_id');
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $semester_level_id = \Input::get('semester_level_id');
      $term_id = \Input::get('term_id');
      $program_id = \Input::get('program_id');
      $section_id = \Input::get('section_id');


      $logo = str_replace("\\","/",public_path())."/images/logo.png";
      
      $enrollment= Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->join('program','curriculum.program_id','=','program.id')
            ->where('student_id',$student_id)
            ->select('enrollment.id','section.section_name','student_curriculum.curriculum_id','program.program_code')->get()->last();

      $student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name')->get()->last();
      $term = Term::where('id', $term_id)->get()->last();
      $classification_level = ClassificationLevel::where('id', $classification_level_id)->get()->last();

    

      $student_curriculum= StudentCurriculum::where('student_id',$student_id)->get()->last();
      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$student_curriculum->curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('curriculum_subject.semester_level_id',$semester_level_id)
            ->select('curriculum_subject.id','curriculum_subject.subject_offered_id','subject.code','subject.name','subject.credit_unit','subject.hour_unit')->get();

      $schedule_list = Schedule::join('class','schedule.class_id','=','class.id')
          ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
          ->join('time as time_start','schedule.time_start','=','time_start.id')
          ->join('time as time_end','schedule.time_end','=','time_end.id')
          ->join('room','schedule.room_id','=','room.id')
          ->where('subject_offered.program_id',$program_id)
          ->where('class.section_id',$section_id)
          ->where('class.term_id',$term_id)
          ->select('schedule.id','schedule.class_id','class.subject_offered_id', 'time_start.id as time_start_id','time_end.id as time_end_id', 'time_start.time as time_start', 'time_start.time_session as session_start','time_end.time as time_end','time_end.time_session as session_end','room.room_name','schedule.room_id')
          ->groupBy('time_start','time_end','room_id')->get();

      $day_list = Schedule::join('class','schedule.class_id','=','class.id')
          ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
          ->join('day','schedule.day_id','=','day.id')
          ->join('room','schedule.room_id','=','room.id')
          ->where('subject_offered.program_id',$program_id)
          ->where('class.section_id',$section_id)
          ->where('class.term_id',$term_id)
          ->select('schedule.id','schedule.time_start','schedule.time_end','schedule.room_id','day.day_code')->orderBy('day_id')->get();

      $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
            ->where('classification_id',$classification_id)
            ->where('program_id',$program_id)
            ->where('term_id',$term_id)
            ->select('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount')->get();

      $pdf = \PDF::loadView('registrar_report/cor/certificate_of_registration_report', array('logo'=>$logo,'classification_id'=>$classification_id,'enrollment'=>$enrollment,'student'=>$student,'term'=>$term,'classification_level'=>$classification_level,'student_curriculum'=>$student_curriculum,'curriculum_subject_list'=>$curriculum_subject_list,'schedule_list'=>$schedule_list,'day_list'=>$day_list,'miscellaneous_fee_detail_list'=>$miscellaneous_fee_detail_list));

      return $pdf->stream();

    }

}
