<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\DesirableTraitDetail;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\TransfereeGradeAverage;
use App\Models\TransferringGradeAverage;
use App\Models\GradingPeriod;
use App\Models\MiscellaneousFeeDetail;
use App\Models\Person;
use App\Models\Schedule;
use App\Models\SectionAdviser;
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

class TranscriptOfRecordController extends RegistrarMainController {

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        // Show the page
        return view('registrar_report/form_137.index');
    }
    public function getDetail()
    {

      $classification_id = \Input::get('classification_id');
      $curriculum_id = \Input::get('curriculum_id');
      $student_id = \Input::get('student_id');
      

      $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('term','enrollment.term_id','=','term.id')
            ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->join('program','curriculum.program_id','=','program.id')
            ->where('student_id',$student_id)
            ->select(['enrollment.id','enrollment.classification_level_id','section.section_name','classification_level.level','term.term_name','student_curriculum.curriculum_id','program.program_name'])->get();

      $student = Student::join('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('blood_type','person.blood_type_id','=','blood_type.id')
            ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
            ->leftJoin('religion','person.religion_id','=','religion.id')
            ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
            ->where('student.id',$student_id)
            ->select(['student.id','student.student_no','student.school_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','blood_type.blood_type_name','person.contact_no','citizenship.citizenship_name','person.birth_place','person.birthdate','person.age','person.email_address','religion.religion_name','civil_status.civil_status_name'])->get()->last();
      
      $student_curriculum= StudentCurriculum::where('student_id',$student_id)->get()->last();
      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
            ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
            ->where('curriculum_subject.curriculum_id',$curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)            
            ->select(['curriculum_subject.id','curriculum_subject.subject_offered_id','subject.code','subject.name','subject.credit_unit','subject.hour_unit','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name'])
            ->groupBy('classification_level.level')
            ->orderBy('classification_level.id', 'ASC')
            ->get();

      // $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
      //     ->join('subject','subject_offered.subject_id','=','subject.id')
      //     ->where('curriculum_subject.curriculum_id',$student_curriculum->curriculum_id)
      //     ->where('curriculum_subject.classification_id',$classification_id)
      //     // ->where('curriculum_subject.classification_level_id',$classification_level_id)
      //     ->where('subject.name','!=','Music')
      //     ->where('subject.name','!=','Arts')
      //     ->where('subject.name','!=','Physical Education')
      //     ->where('subject.name','!=','Health')
      //     ->select('curriculum_subject.id','curriculum_subject.subject_offered_id','subject_offered.subject_id','subject.code','subject.name','curriculum_subject.classification_level_id')->orderBy('is_pace', 'DESC')->orderBy('subject_id', 'ASC')->get();


      $grade_list = Grade::join('class','grade.class_id','=','class.id')
            ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('grade.student_id',$student_id)
            ->select('grade.id','grade.computed_grade','subject_offered.subject_id', 'grade.grading_period_id', 'class.classification_level_id', 'subject.name')->get();

      // $grade = Grade::where('student_id',$student_id)->get()->last();

      $subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
              ->join('subject','subject_offered.subject_id','=','subject.id')
              ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
              ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
              ->select(['curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'subject.code', 'subject.name', 'subject.credit_unit', 'subject_offered.subject_id'])
              ->where('curriculum_subject.curriculum_id',$curriculum_id)
              // ->where('curriculum_subject.classification_id',$classification_id)
              // ->where('subject.name', '!=', 'Music')
              // ->where('subject.name', '!=', 'Arts')
              // ->where('subject.name', '!=', 'Physical Education')
              // ->where('subject.name', '!=', 'Health')
              // ->where('subject.name', '!=', 'Break')
              ->orderBy('curriculum_subject.id', 'ASC')
              ->get();


      $employee = Employee::join('person', 'employee.person_id', '=','person.id')
              ->where('employee.gen_role_id', 1) 
              ->select(['employee.id', 'person.first_name', 'person.middle_name', 'person.last_name'])
              ->get()->first(); 

      // $transferee_grade_average_list = TransfereeGradeAverage::join('school','transferee_grade_average.school_id','=','school.id')
      //       ->join('classification_level','transferee_grade_average.classification_level_id','=','classification_level.id')
      //       ->join('student','transferee_grade_average.student_id','=','student.id')
      //       ->join('person','student.person_id','=','person.id')
      //       ->where('transferee_grade_average.student_id', $student_id)
      //       ->select(['transferee_grade_average.id', 'transferee_grade_average.classification_level_id' ,'transferee_grade_average.average' ,'transferee_grade_average.admission_level' ,'transferee_grade_average.remarks' ,'transferee_grade_average.school_id' ,'transferee_grade_average.school_year' ,'person.last_name','person.first_name','person.middle_name','school.school_name'])->get();  

      // $transferring_grade_average_list = TransferringGradeAverage::join('school', 'transferring_grade_average.school_id', '=', 'school.id')
      //       ->where('transferring_grade_average.student_id', $student_id )
      //       ->select(['transferring_grade_average.id', 'transferring_grade_average.student_id', 'transferring_grade_average.admission_level', 'transferring_grade_average.school_id', 'school.school_name'])->get();

      $student_attendance_list = StudentAttendance::join('attendance', 'student_attendance.attendance_id', '=', 'attendance.id')
              ->where('student_attendance.student_id', $student_id)
              ->select(['student_attendance.id', 'student_attendance.attendance_id', 'student_attendance.attendance_remark_id', 'attendance.classification_level_id'])
              ->get();

      $grading_period_list= GradingPeriod::get();
      // $desirable_trait_detail_list= DesirableTraitDetail::get();
      // $student_desirable_trait_list= StudentDesirableTrait::join('desirable_trait_rating','student_desirable_trait.desirable_trait_rating_id','=','desirable_trait_rating.id')
      //       ->where('student_desirable_trait.student_id','=',$student_id)
      //       ->select(['student_desirable_trait.id', 'student_desirable_trait.grading_period_id','student_desirable_trait.classification_level_id', 'student_desirable_trait.desirable_trait_detail_id', 'desirable_trait_rating.code'])->get(); 

      $curriculum = Curriculum::where('id',$curriculum_id)->select(['curriculum.id','curriculum.curriculum_name'])->get()->last();
      $classification = Classification::where('id', $classification_id)->get()->last();

      // $student_achievements_list= StudentAchievements::join('classification_level', 'student_achievements.classification_level_id', '=', 'classification_level.id')
      //     ->join('grading_period', 'student_achievements.grading_period_id', '=', 'grading_period.id')
      //     ->where('student_achievements.student_id','=',$student_id)
      //     ->where('student_achievements.classification_id','=',$classification_id)
      //     ->select(['student_achievements.id', 'student_achievements.classification_level_id',  'student_achievements.grading_period_id', 'classification_level.level', 'student_achievements.achievements', 'grading_period.grading_period_name'])
      //     ->groupBy('student_achievements.grading_period_id')
      //     ->get();


      // if($enrollment_list == null || $curriculum == null || $student == null || $curriculum_subject_list == null || $subject_list == null || $classification_id == null || $employee == null || $grading_period_list == null || $desirable_trait_detail_list == null || $student_desirable_trait_list == null || $classification == null || $transferee_grade_average_list == null ||  $transferring_grade_average_list == null || $grade_list == null || $student_attendance_list == null || $student_achievements_list == null){

      //     return view('registrar_report/form_137/empty');

      // }
      // else{

          return view('registrar_report/form_137/detail', 
            compact(
                'enrollment_list',
                'curriculum',
                'student',
                'curriculum_subject_list',
                'subject_list',
                'classification_id',
                'employee',
                'grading_period_list',
                // 'desirable_trait_detail_list',
                // 'student_desirable_trait_list',    
                'classification',        
                // 'transferee_grade_average_list',        
                // 'transferring_grade_average_list',        
                'grade_list',        
                'student_attendance_list'       
                // 'student_achievements_list'        
                
                ));
        // }

    }

    public function pdfTranscriptofRecord()
    {
      
      $logo = str_replace("\\","/",public_path())."/images/logo.png";

      $classification_id = \Input::get('classification_id');
      $curriculum_id = \Input::get('curriculum_id');
      $student_id = \Input::get('student_id');
      
      $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('term','enrollment.term_id','=','term.id')
            ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->join('program','curriculum.program_id','=','program.id')
            ->where('student_id',$student_id)
            ->select(['enrollment.id','enrollment.classification_level_id','section.section_name','classification_level.level','term.term_name','student_curriculum.curriculum_id','program.program_name'])->get();

      $student = Student::join('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('blood_type','person.blood_type_id','=','blood_type.id')
            ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
            ->leftJoin('religion','person.religion_id','=','religion.id')
            ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
            ->where('student.id',$student_id)
            ->select(['student.id','student.student_no','student.school_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','blood_type.blood_type_name','person.contact_no','citizenship.citizenship_name','person.birth_place','person.birthdate','person.age','person.email_address','religion.religion_name','civil_status.civil_status_name'])->get()->last();
      
      $student_curriculum= StudentCurriculum::where('student_id',$student_id)->get()->last();
      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
            ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
            ->where('curriculum_subject.curriculum_id',$student_curriculum->curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)            
            ->select(['curriculum_subject.id','curriculum_subject.subject_offered_id','subject.code','subject.name','subject.credit_unit','subject.hour_unit','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name'])
            ->groupBy('classification_level.level')
            ->orderBy('curriculum_subject.id', 'ASC')
            ->get();


      $term_list = Term::join('classification', 'term.classification_id', '=', 'classification.id')
              ->where('term.classification_id', $classification_id)
              ->select(['term.id', 'term.classification_id' ,'term.term_name'])->get();

      $subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
              ->join('subject','subject_offered.subject_id','=','subject.id')
              ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
              ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
              ->select(['curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'subject.code', 'subject.name', 'subject.credit_unit', 'subject_offered.subject_id'])
              ->where('curriculum_subject.curriculum_id',$student_curriculum->curriculum_id)
              ->where('curriculum_subject.classification_id',$classification_id)
              ->where('subject.name', '!=', 'Music')
              ->where('subject.name', '!=', 'Arts')
              ->where('subject.name', '!=', 'Physical Education')
              ->where('subject.name', '!=', 'Health')
              ->where('subject.name', '!=', 'Break')
              ->orderBy('curriculum_subject.id', 'ASC')
              ->get();


      $student_attendance_list = StudentAttendance::join('attendance', 'student_attendance.attendance_id', '=', 'attendance.id')
              ->where('student_attendance.student_id', $student_id)
              ->select(['student_attendance.id', 'student_attendance.attendance_id', 'student_attendance.attendance_remark_id', 'attendance.classification_level_id'])
              ->get();


      $employee = Employee::join('person', 'employee.person_id', '=','person.id')
              ->where('employee.gen_role_id', 1) 
              ->select(['employee.id', 'person.first_name', 'person.middle_name', 'person.last_name'])
              ->get()->first();  


      $grade_list = Grade::join('class','grade.class_id','=','class.id')
            ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('grade.student_id',$student_id)
            ->select('grade.id','grade.computed_grade','subject_offered.subject_id', 'grade.grading_period_id', 'class.classification_level_id', 'subject.name')->get(); 
                           
      $grading_period_list= GradingPeriod::get();
      $desirable_trait_detail_list= DesirableTraitDetail::get();
      $student_desirable_trait_list= StudentDesirableTrait::join('desirable_trait_rating','student_desirable_trait.desirable_trait_rating_id','=','desirable_trait_rating.id')
            ->where('student_desirable_trait.student_id','=',$student_id)
            ->select(['student_desirable_trait.id', 'student_desirable_trait.grading_period_id','student_desirable_trait.classification_level_id', 'student_desirable_trait.desirable_trait_detail_id', 'desirable_trait_rating.code'])->get(); 
      // $section_adviser_list = SectionAdviser::join('employee','section_adviser.employee_id','=','employee.id')
      //       ->join('person','employee.person_id','=','person.id')
      //       ->join('classification_level','section_adviser.classification_level_id','=','classification_level.id')
      //       ->select(['section_adviser.id', 'section_adviser.classification_level_id' ,'person.last_name','person.first_name','person.middle_name'])->get();

      $transferee_grade_average_list = TransfereeGradeAverage::join('school','transferee_grade_average.school_id','=','school.id')
            ->join('classification_level','transferee_grade_average.classification_level_id','=','classification_level.id')
            ->join('student','transferee_grade_average.student_id','=','student.id')
            ->join('person','student.person_id','=','person.id')
            ->where('transferee_grade_average.student_id','=',$student_id)
            ->select(['transferee_grade_average.id', 'transferee_grade_average.classification_level_id' ,'transferee_grade_average.average' ,'transferee_grade_average.admission_level' ,'transferee_grade_average.remarks' ,'transferee_grade_average.school_id' ,'transferee_grade_average.school_year' ,'person.last_name','person.first_name','person.middle_name','school.school_name'])->get();

      $transferring_grade_average_list = TransferringGradeAverage::join('school', 'transferring_grade_average.school_id', '=', 'school.id')
            ->where('transferring_grade_average.student_id', $student_id )
            ->select(['transferring_grade_average.id', 'transferring_grade_average.admission_level','transferring_grade_average.student_id', 'transferring_grade_average.school_id', 'school.school_name'])->get();

      $student_achievements_list= StudentAchievements::join('classification_level', 'student_achievements.classification_level_id', '=', 'classification_level.id')
          ->join('grading_period', 'student_achievements.grading_period_id', '=', 'grading_period.id')
          ->where('student_achievements.student_id','=',$student_id)
          ->where('student_achievements.classification_id','=',$classification_id)
          ->select(['student_achievements.id', 'student_achievements.classification_level_id',  'student_achievements.grading_period_id', 'classification_level.level', 'student_achievements.achievements', 'grading_period.grading_period_name'])
          ->groupBy('student_achievements.grading_period_id')
          ->get();


      $classification = Classification::where('id', $classification_id)->get()->last();
      $curriculum = Curriculum::where('id',$curriculum_id)->select(['curriculum.id','curriculum.curriculum_name'])->get()->last();
      $pdf = \PDF::loadView('registrar_report/form_137/pdf_form137_sheet', array('logo'=>$logo,'enrollment_list'=>$enrollment_list,'student'=>$student,'student_curriculum'=>$student_curriculum,'curriculum_subject_list'=>$curriculum_subject_list,'subject_list'=>$subject_list,'classification_id'=>$classification_id,'curriculum'=>$curriculum,'employee'=>$employee,'classification'=>$classification,'grading_period_list'=>$grading_period_list,'desirable_trait_detail_list'=>$desirable_trait_detail_list,'student_desirable_trait_list'=>$student_desirable_trait_list,'term_list'=>$term_list,'transferee_grade_average_list'=>$transferee_grade_average_list,'student_attendance_list'=>$student_attendance_list,'grade_list'=>$grade_list,'transferring_grade_average_list'=>$transferring_grade_average_list,'student_achievements_list'=>$student_achievements_list));

      return $pdf->stream();

    }

}
