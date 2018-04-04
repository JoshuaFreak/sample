<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\DesirableTraitDetail;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\GradingPeriod;
use App\Models\MiscellaneousFeeDetail;
use App\Models\Person;
use App\Models\Schedule;
use App\Models\SectionMonitor;
use App\Models\SemesterLevel;
use App\Models\Student;
use App\Models\StudentAchievements;
use App\Models\StudentAttendance;
use App\Models\StudentCurriculum;
use App\Models\StudentDesirableTrait;
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

class ReportCardController extends BaseController {

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */

   public function index()
    {
        // $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        // $semester_level_list = SemesterLevel::orderBy('semester_level.id','ASC')->get();
        // $term_list = Term::orderBy('term.id','ASC')->get();
        // Show the page
        return view('registrar_report/report_card.index',compact('classification_list', 'classification_level_list', 'semester_level_list', 'term_list'));
    }

    public function getDetail()
    {

      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');
      $classification_id = \Input::get('classification_id');
      $term_id = \Input::get('term_id');
      $section_id = \Input::get('section_id');
      $curriculum_id = \Input::get('curriculum_id');

      $student = Student::join('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('religion','person.religion_id','=','religion.id')
            ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
            ->leftJoin('photo','person.photo_id','=','photo.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','student.school_no','person.last_name','person.first_name','person.middle_name','person.birth_place','person.birthdate','gender.gender_name','religion.religion_name','citizenship.citizenship_name')->get()->last();
            
      $section_monitor = SectionMonitor::join('employee','section_monitor.employee_id','=','employee.id')->join('person','employee.person_id','=','person.id')->where('section_monitor.section_id','=',$section_id)->select('section_monitor.id','person.last_name','person.first_name','person.middle_name')->get()->last();

      $term = Term::where('id', $term_id)->get()->last();
      $classification_level = ClassificationLevel::where('id', $classification_level_id)->get()->last();
      $classification = Classification::where('id', $classification_id)->get()->last();

      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            // ->where('subject.name','!=','Music')
            // ->where('subject.name','!=','Arts')
            // ->where('subject.name','!=','Physical Education')
            // ->where('subject.name','!=','Health')
            // ->where('subject.name','!=','Break')
            ->select('curriculum_subject.id','curriculum_subject.subject_offered_id','subject_offered.subject_id','subject.code','subject.name')->orderBy('subject_id', 'ASC')->get();

      $grade_list = Grade::join('class','grade.class_id','=','class.id')
            ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('grade.student_id',$student_id)
            ->where('grade.term_id',$term_id)
            ->select('grade.id','grade.computed_grade','subject_offered.subject_id', 'grade.grading_period_id','subject.name')->get();

      // $student_attendance_list = StudentAttendance::join('attendance','student_attendance.attendance_id','=','attendance.id')
      //       ->where('student_attendance.student_id',$student_id)
      //       ->where('attendance.term_id',$term_id)
      //       ->where('attendance.classification_level_id',$classification_level_id)
      //       ->where('attendance.section_id',$section_id)
      //       ->select('student_attendance.id','student_attendance.attendance_remark_id')->get();

      $grading_period_list= GradingPeriod::get();
      // $desirable_trait_detail_list= DesirableTraitDetail::get();
      // $student_desirable_trait_list= StudentDesirableTrait::join('desirable_trait_rating','student_desirable_trait.desirable_trait_rating_id','=','desirable_trait_rating.id')
      //       ->where('student_desirable_trait.student_id','=',$student_id)
      //       ->where('student_desirable_trait.classification_level_id','=',$classification_level_id)
      //       ->select('student_desirable_trait.id', 'student_desirable_trait.grading_period_id', 'student_desirable_trait.desirable_trait_detail_id', 'desirable_trait_rating.code')->get();
      
      // $student_achievements_list= StudentAchievements::join('grading_period','student_achievements.grading_period_id','=','grading_period.id')
      //       ->where('student_achievements.student_id','=',$student_id)
      //       ->where('student_achievements.term_id','=',$term_id)
      //       ->where('student_achievements.classification_level_id','=',$classification_level_id)
      //       ->where('student_achievements.classification_id','=',$classification_id)
      //       ->select('student_achievements.id', 'student_achievements.achievements', 'grading_period.grading_period_name')
      //       ->orderBy('grading_period.id', 'ASC')->get();


      return view('registrar_report/report_card/detail', 
        compact(
            'student',
            'section_monitor',
            'term',
            'classification_level',
            'classification',
            'curriculum_subject_list',
            'grade_list',
            // 'student_attendance_list',
            'grading_period_list'
            // 'desirable_trait_detail_list',
            // 'student_desirable_trait_list',
            // 'student_achievements_list'
        )
      );
      

    }

    public function Report()
    {
      
      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');
      $classification_id = \Input::get('classification_id');
      $term_id = \Input::get('term_id');
      $section_id = \Input::get('section_id');
      $curriculum_id = \Input::get('curriculum_id');

      $logo = str_replace("\\","/",public_path())."/images/logo.png";
      
      $student = Student::join('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('religion','person.religion_id','=','religion.id')
            ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
            ->join('photo','person.photo_id','=','photo.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','student.school_no','person.last_name','person.first_name','person.middle_name','person.birth_place','person.birthdate','gender.gender_name','religion.religion_name','citizenship.citizenship_name','photo.img')->get()->last();
            
      $section_monitor = SectionMonitor::join('employee','section_monitor.employee_id','=','employee.id')->join('person','employee.person_id','=','person.id')->where('section_monitor.section_id','=',$section_id)->select('section_monitor.id','person.last_name','person.first_name','person.middle_name')->get()->last();

      $term = Term::where('id', $term_id)->get()->last();
      $classification_level = ClassificationLevel::where('id', $classification_level_id)->get()->last();
      $classification = Classification::where('id', $classification_id)->get()->last();

      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            // ->where('subject.name','!=','Music')
            // ->where('subject.name','!=','Arts')
            // ->where('subject.name','!=','Physical Education')
            // ->where('subject.name','!=','Health')
            // ->where('subject.name','!=','Break')
            ->select('curriculum_subject.id','curriculum_subject.subject_offered_id','subject_offered.subject_id','subject.code','subject.name')->orderBy('subject_id', 'ASC')->get();

      $grade_list = Grade::join('class','grade.class_id','=','class.id')
            ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('grade.student_id',$student_id)
            ->where('grade.term_id',$term_id)
            ->select('grade.id','grade.computed_grade','subject_offered.subject_id', 'grade.grading_period_id','subject.name')->get();

      // $student_attendance_list = StudentAttendance::join('attendance','student_attendance.attendance_id','=','attendance.id')
      //       ->where('student_attendance.student_id',$student_id)
      //       ->where('attendance.term_id',$term_id)
      //       ->where('attendance.classification_level_id',$classification_level_id)
      //       ->where('attendance.section_id',$section_id)
      //       ->select('student_attendance.id','student_attendance.attendance_remark_id')->get();

      $grading_period_list= GradingPeriod::get();
      // $desirable_trait_detail_list= DesirableTraitDetail::get();
      // $student_desirable_trait_list= StudentDesirableTrait::join('desirable_trait_rating','student_desirable_trait.desirable_trait_rating_id','=','desirable_trait_rating.id')
      //       ->where('student_desirable_trait.student_id','=',$student_id)
      //       ->where('student_desirable_trait.classification_level_id','=',$classification_level_id)
      //       ->select('student_desirable_trait.id', 'student_desirable_trait.grading_period_id', 'student_desirable_trait.desirable_trait_detail_id', 'desirable_trait_rating.code')->get();
      
      // $student_achievements_list= StudentAchievements::join('grading_period','student_achievements.grading_period_id','=','grading_period.id')
      //       ->where('student_achievements.student_id','=',$student_id)
      //       ->where('student_achievements.term_id','=',$term_id)
      //       ->where('student_achievements.classification_level_id','=',$classification_level_id)
      //       ->where('student_achievements.classification_id','=',$classification_id)
      //       ->select('student_achievements.id', 'student_achievements.achievements', 'grading_period.grading_period_name')
      //       ->orderBy('grading_period.id', 'ASC')->get();

      $pdf = \PDF::loadView('registrar_report/report_card/report', array('logo'=>$logo,'student'=>$student,'section_monitor'=>$section_monitor,'term'=>$term,'classification_level'=>$classification_level,'classification'=>$classification,'curriculum_subject_list'=>$curriculum_subject_list,'grade_list'=>$grade_list,'grading_period_list'=>$grading_period_list));

      return $pdf->stream();

    }

}
