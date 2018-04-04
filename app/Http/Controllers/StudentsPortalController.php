<?php namespace App\Http\Controllers;

use App\Http\Controllers\StudentsPortalMainController;
use App\Models\BloodType;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Citizenship;
use App\Models\CivilStatus;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\DesirableTraitDetail;
use App\Models\Enrollment;
use App\Models\File;
use App\Models\Folder;
use App\Models\Gender;
use App\Models\GenUser;
use App\Models\Grade;
use App\Models\GradingPeriod;
use App\Models\Guardian;
use App\Models\MiscellaneousFeeDetail;
use App\Models\Pace;
use App\Models\Person;
use App\Models\Religion;
use App\Models\Schedule;
use App\Models\SectionAdviser;
use App\Models\SectionAdviserStudent;
use App\Models\SectionMonitor;
use App\Models\SemesterLevel;
use App\Models\Student;
use App\Models\StudentAcademicProjection;
use App\Models\StudentAchievements;
use App\Models\StudentAttendance;
use App\Models\StudentCurriculum;
use App\Models\StudentDesirableTrait;
use App\Models\StudentDocument;
use App\Models\StudentLedger;
use App\Models\StudentSiblings;
use App\Models\Suffix;
use App\Models\Term;
use App\Http\Requests\GenUserRequest;
use App\Http\Requests\GenUserEditRequest;
use App\Http\Requests\RegisteredEditRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentsPortalController extends StudentsPortalMainController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function index()
    {
      
      $student = Student::join('gen_user','student.student_no','=','gen_user.username')
          ->join('person','student.person_id','=','person.id')
          ->leftJoin('suffix','person.suffix_id','=','suffix.id')
          ->where('gen_user.id','=', Auth::user()->id)
          ->select('student.id','person.last_name','person.first_name','person.middle_name','suffix.suffix_name')->get()->last();
      // Show the page
      return view('students_portal.index',compact('student'));

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function myProfile($username)
    {
      $action = 0;
      $enrollment = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('section','enrollment.section_id','=','section.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->join('program','curriculum.program_id','=','program.id')
          ->join('classification','student_curriculum.classification_id','=','classification.id')
          ->join('person','student.person_id','=','person.id')
          ->leftJoin('suffix','person.suffix_id','=','suffix.id')
          ->leftJoin('religion','person.religion_id','=','religion.id')
          ->leftJoin('gender','person.gender_id','=','gender.id')
          ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
          ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
          ->leftJoin('student_type','student.student_type_id','=','student_type.id')
          ->leftJoin('living_with','person.living_with_id','=','living_with.id')
          ->leftJoin('student_family_background','person.student_family_background_id','=','student_family_background.id')
          ->leftJoin('parents_marital_status','student_family_background.parents_marital_status_id','=','parents_marital_status.id')
          ->where('student.student_no','=', $username)
          ->groupBy('student.student_no')
          ->select('enrollment.id','classification_level.level','section.section_name','enrollment.section_id','student_curriculum.curriculum_id','student_curriculum.student_id as student_id','program.id as program_id','program.program_name','curriculum.curriculum_name','classification.classification_name','student_curriculum.classification_id','student_type.name as student_type_name','student.student_no','student.person_id','person.last_name','person.first_name','person.middle_name','person.preferred_name','suffix.suffix_name','person.address','person.home_number','person.student_mobile_number','person.church_affiliation','person.student_email_address','person.birthdate','person.birth_place','religion.religion_name','gender.gender_name','civil_status.civil_status_name','citizenship.citizenship_name','living_with.name as living_with_name','person.name_relation','person.medical_condition','person.maintenance_medication','person.personal_physician','person.physician_mobile_number','person.clinic_address','person.physician_office_number','student_family_background.fathers_name','student_family_background.fathers_mobile_number','student_family_background.fathers_occupation','student_family_background.fathers_employer_name','student_family_background.fathers_employer_no','student_family_background.fathers_email_address','student_family_background.mothers_name','student_family_background.mothers_mobile_number','student_family_background.mothers_occupation','student_family_background.mothers_employer_name','student_family_background.mothers_employer_no','student_family_background.mothers_email_address','parents_marital_status.name as parents_marital_status_name')->get()->last(); 

        $person = Person::find($enrollment->person_id);
        $student_siblings_list = StudentSiblings::all();


        return view('students_portal/my_profile.index',compact('enrollment','person','student_siblings_list'));


    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postProfile(RegisteredEditRequest $registered_edit_request, $student_id) {
      
        $student = Student::find($student_id);


        $person = Person::find($student->person_id);       
        $person->contact_no = $registered_edit_request->contact_no;

        $person->save();
        $student->save();

        $success = \Lang::get('guardian_portal.create_success').' ->new Contact no :'.$person->contact_no; 
        return redirect('students_portal/'.Auth::user()->username.'/my_profile')->withSuccess( $success);
    }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
      public function getEdit($username) {

        $student = Student::join('gen_user','student.student_no','=','gen_user.username')
            ->where('student.student_no','=', $username)
            ->select('gen_user.id', 'student.student_no')->get()->last();
       //var_dump($its_customs_office);
        return view('students_portal/my_profile/edit',compact('student'));
      
    }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(GenUserEditRequest $gen_user_edit_request, $username) {

          $gen_user = GenUser::find($username);

          //check if the user changes the existing email
          if($gen_user->username !== $gen_user_edit_request->username){
              //check if the new email is already present in the database
              if(GenUser::where('username','=',$gen_user_edit_request->username)->first() != null){
                  //create error message here
              }
              else{
                  $gen_user->username = $gen_user_edit_request->username;
              }  
          }

          $password = $gen_user_edit_request->password;
          $passwordConfirmation = $gen_user_edit_request->password_confirmation;

          if (!empty($password)) {
              if ($password === $passwordConfirmation) {
                  $gen_user->password = Hash::make($password);
              }
          }
          //var_dump($gen_user_edit_request);

          $gen_user -> save();
        $success = \Lang::get('guardian_portal.password_success').' : User '.$gen_user->username ; 
        return redirect('students_portal/'.Auth::user()->username.'/edit')->withSuccess( $success);
      }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function schedule($username)
    {

      $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('term','enrollment.term_id','=','term.id')
          ->where('student.student_no','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.section_id', 'enrollment.term_id','term.term_name','student_curriculum.classification_id','student_curriculum.curriculum_id','student_curriculum.student_id')->get();
      // Show the page
      return view('students_portal/schedule.index',compact('enrollment','classification_level_list'));

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function dataSchedule()
    {
      $student_id = \Input::get('student_id');
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $section_id = \Input::get('section_id');
      $term_id = \Input::get('term_id');
      $curriculum_id = \Input::get('curriculum_id');

      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('subject.name', '!=', 'Break')
            ->select('curriculum_subject.id','curriculum_subject.subject_offered_id','subject.code','subject.name','subject.credit_unit','subject.hour_unit')->get();

      $schedule_list = Schedule::join('class','schedule.class_id','=','class.id')
          ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
          ->join('time as time_start','schedule.time_start','=','time_start.id')
          ->join('time as time_end','schedule.time_end','=','time_end.id')
          ->join('room','schedule.room_id','=','room.id')
          ->where('class.section_id',$section_id)
          ->where('class.term_id',$term_id)
          ->select('schedule.id','schedule.class_id','class.subject_offered_id', 'time_start.id as time_start_id','time_end.id as time_end_id','time_start.time as time_start', 'time_start.time_session as session_start','time_end.time as time_end','time_end.time_session as session_end','room.room_name','schedule.room_id')
          ->groupBy('subject_offered_id')->get();

      $day_list = Schedule::join('class','schedule.class_id','=','class.id')
          ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
          ->join('day','schedule.day_id','=','day.id')
          ->where('class.section_id',$section_id)
          ->where('class.term_id',$term_id)
          ->select('schedule.id','class.subject_offered_id','day.day_code')->orderBy('day_id')->get();

      if($curriculum_subject_list == null || $schedule_list == null || $day_list == null){
              return view('students_portal/empty');

      }else{
              return view('students_portal/schedule/detail', 
                compact(
                    'curriculum_subject_list',
                    'schedule_list',
                    'day_list'
                )
              );
      }

    }
 
   /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function Curriculum()
    {

      $curriculum_subject_list = StudentCurriculum::join('curriculum', 'student_curriculum.curriculum_id', '=', 'curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->where('student.student_no','=', Auth::user()->username)
                ->select('curriculum.id', 'curriculum.curriculum_name', 'student_curriculum.classification_id')->get();
      // Show the page
      return view('students_portal/curriculum.index',compact('student','curriculum_subject_list'));

    }

    public function getDetail()
    {

      $classification_id = \Input::get('classification_id');
      $curriculum_id = \Input::get('curriculum_id');

       $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
                ->select('curriculum_subject.id', 'curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name')
                ->where('curriculum_subject.classification_id',$classification_id)
                ->where('curriculum_subject.curriculum_id',$curriculum_id)
                ->groupBy('classification_level.level')
                ->where('subject.name', '!=', 'Break')
                ->orderBy('curriculum_subject.id', 'ASC')
                ->get();

       $subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
                ->select('curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'subject.code', 'subject.name', 'subject.credit_unit')
                ->where('curriculum_subject.classification_id',$classification_id)
                ->where('curriculum_subject.curriculum_id',$curriculum_id)
                ->where('subject.name', '!=', 'Break')
                ->orderBy('curriculum_subject.id', 'ASC')
                ->get();

      $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
      $curriculum = Curriculum::where('id',$curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();

      if($classification_id == null || $curriculum_id == null || $curriculum_subject_list == null && $subject_list == null || $classification == null || $curriculum == null){
              return view('students_portal/empty');

      }else{
              return view('students_portal/curriculum.detail', compact('classification_id','curriculum_id','curriculum_subject_list','subject_list','classification','curriculum'));
      }

    }
 
   /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function academicProjection()
    {
      // Show the page
      $enrollment = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->where('student.student_no','=', Auth::user()->username)
                ->select('enrollment.id','student_curriculum.student_id','enrollment.term_id')->get()->last();

      $classification_level_list = Enrollment::join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('curriculum', 'student_curriculum.curriculum_id', '=', 'curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('classification_level', 'enrollment.classification_level_id', '=', 'classification_level.id')
                ->where('student.student_no','=', Auth::user()->username)
                ->select('classification_level.id', 'classification_level.level','student_curriculum.student_id','enrollment.term_id')
                ->groupBy('classification_level.level')->get();
      // Show the page
      return view('students_portal/academic_projection.index',compact('enrollment','classification_level_list'));

    }

    public function getacademicProjectionDetail()
    {
      // $condition = \Input::all();
      $term_id = \Input::get('term_id');
      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');

      $subject_list = StudentAcademicProjection::join('subject', 'student_academic_projection.subject_id', '=', 'subject.id')
                ->where('student_academic_projection.student_id','=',$student_id)
                ->where('student_academic_projection.term_id','=',$term_id)
                ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
                ->select('subject.id','subject.name', 'student_academic_projection.subject_id')
                ->groupBy('subject.id')->get();

      // $arr[0][0] = "";
      // $arr[0][1] = "";
                
      $arr = array();
      $grade_id_arr = array();
      $detail_arr = array();

      $row_no = 0;
      foreach ($subject_list as $subject) {
        $arr[$row_no][0] = $subject->name;

          $pace_list = StudentAcademicProjection::where('student_academic_projection.student_id','=',$student_id)
                ->where('student_academic_projection.term_id','=',$term_id)
                ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
                ->where('student_academic_projection.subject_id','=',$subject->subject_id)
                ->select('student_academic_projection.id','student_academic_projection.required_pace','student_academic_projection.actual_pace','student_academic_projection.date_released','student_academic_projection.date_taken','student_academic_projection.grade','student_academic_projection.subject_id')->get();
        
          $arr[$row_no][1] = "(REQUIRED)";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->required_pace;
            // $arr[0][$col_no] = "";
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'required';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "(ACTUAL)";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->actual_pace;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'actual';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "DATE RELEASED";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->date_released;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'date_released';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "DATE TAKEN";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->date_taken;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'date_taken';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "GRADE";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->grade;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'grade';
            $col_no++;
          }
          $row_no++;

      }

      $data = array($arr, $grade_id_arr, $detail_arr);
      return response()->json($data);

    }   
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function Grade($username)
    {

      $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('section','enrollment.section_id','=','section.id')
          ->join('term','enrollment.term_id','=','term.id')
          ->where('student.student_no','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id','term.term_name','enrollment.section_id','section.section_name','student_curriculum.classification_id','student_curriculum.curriculum_id','student_curriculum.student_id')->get();
      // Show the page
      return view('students_portal/grade.index',compact('enrollment','classification_level_list', 'semester_level_list'));

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function dataGrade()
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
            ->join('photo','person.photo_id','=','photo.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','student.school_no','person.last_name','person.first_name','person.middle_name','person.birth_place','person.birthdate','gender.gender_name','religion.religion_name','citizenship.citizenship_name','photo.img')->get()->last();
       
      $section_adviser = SectionAdviserStudent::leftJoin('section_adviser','section_adviser_student.section_adviser_id','=','section_adviser.id')
            ->join('employee','section_adviser.employee_id','=','employee.id')
            ->join('person','employee.person_id','=','person.id')
            ->join('section_monitor','section_adviser.section_monitor_id','=','section_monitor.id')
            ->where('section_monitor.section_id','=',$section_id)
            ->where('section_adviser_student.student_id','=',$student_id)
            ->select(['section_adviser.id','person.last_name','person.first_name','person.middle_name'])->get()->last();

      // $section_adviser = SectionAdviser::join('employee','section_adviser.employee_id','=','employee.id')
      //       ->join('section_monitor','section_adviser.section_monitor_id','=','section_monitor.id')
      //       ->join('person','employee.person_id','=','person.id')
      //       ->where('section_monitor.section_id','=',$section_id)
      //       ->select(['section_adviser.id','person.last_name','person.first_name','person.middle_name'])->get()->last();

      $term = Term::where('id', $term_id)->get()->last();
      $classification_level = ClassificationLevel::where('id', $classification_level_id)->get()->last();
      $classification = Classification::where('id', $classification_id)->get()->last();

      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('subject.name','!=','Music')
            ->where('subject.name','!=','Arts')
            ->where('subject.name','!=','Physical Education')
            ->where('subject.name','!=','Health')
            ->where('subject.name','!=','Break')
            ->select('curriculum_subject.id','curriculum_subject.subject_offered_id','subject_offered.subject_id','subject.code','subject.name','subject.is_pace')->orderBy('is_pace', 'DESC')->orderBy('subject_id', 'ASC')->get();

      $grade_list = Grade::join('class','grade.class_id','=','class.id')
            ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('grade.student_id',$student_id)
            ->where('grade.term_id',$term_id)
            ->select('grade.id','grade.computed_grade','subject_offered.subject_id', 'grade.grading_period_id','subject.name')->get();

      $student_attendance_list = StudentAttendance::join('attendance','student_attendance.attendance_id','=','attendance.id')
            ->where('student_attendance.student_id',$student_id)
            ->where('attendance.term_id',$term_id)
            ->where('attendance.classification_level_id',$classification_level_id)
            ->where('attendance.section_id',$section_id)
            ->select('student_attendance.id','student_attendance.attendance_remark_id')->get();

      $grading_period_list= GradingPeriod::all();
      $desirable_trait_detail_list= DesirableTraitDetail::get();
      $student_desirable_trait_list= StudentDesirableTrait::join('desirable_trait_rating','student_desirable_trait.desirable_trait_rating_id','=','desirable_trait_rating.id')
            ->where('student_desirable_trait.student_id','=',$student_id)
            ->where('student_desirable_trait.classification_level_id','=',$classification_level_id)
            ->select('student_desirable_trait.id', 'student_desirable_trait.grading_period_id', 'student_desirable_trait.desirable_trait_detail_id', 'desirable_trait_rating.code')->get();
      
      $student_achievements_list= StudentAchievements::join('grading_period','student_achievements.grading_period_id','=','grading_period.id')
            ->where('student_achievements.student_id','=',$student_id)
            ->where('student_achievements.term_id','=',$term_id)
            ->where('student_achievements.classification_level_id','=',$classification_level_id)
            ->where('student_achievements.classification_id','=',$classification_id)
            ->select('student_achievements.id', 'student_achievements.achievements', 'grading_period.grading_period_name')
            ->orderBy('grading_period.id', 'ASC')->get();
      
      $section_monitor = SectionMonitor::join('employee','section_monitor.employee_id','=','employee.id')->join('person','employee.person_id','=','person.id')->where('section_monitor.section_id','=',$section_id)->select('section_monitor.id','person.last_name','person.first_name','person.middle_name')->get()->last();

            return view('students_portal/grade/detail', 
              compact(
                  'student',
                  'section_adviser',
                  'term',
                  'classification_level',
                  'classification',
                  'curriculum_subject_list',
                  'grade_list',
                  'student_attendance_list',
                  'grading_period_list',
                  'desirable_trait_detail_list',
                  'student_desirable_trait_list',
                  'student_achievements_list',
                  'section_monitor'
              )
            );

    }


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function studentLedger()
    {

      $term_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->join('term','enrollment.term_id','=','term.id')
          ->where('student.student_no','=',Auth::user()->username)
          ->select(['enrollment.id','enrollment.term_id','term.term_name','student_curriculum.id as student_id'])
          ->groupBy('enrollment.term_id')->get();
      // Show the page
      return view('students_portal/student_ledger.index',compact('enrollment','term_list'));
      // return response()->json($term_list);

    }


    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
     
        $student_ledger_list = StudentLedger::where('student_ledger.student_id',$student_id)
              ->where('student_ledger.term_id',$term_id)
              ->join('payment_type','student_ledger.payment_type_id','=','payment_type.id')
              ->leftJoin('payment','student_ledger.payment_id','=','payment.id')
              ->select(array('student_ledger.id','student_ledger.created_at','payment.or_no','student_ledger.remark','student_ledger.debit','student_ledger.credit'))
              ->orderBy('student_ledger.id', 'ASC');

          return Datatables::of($student_ledger_list)
              ->editColumn('created_at','{{date("m/d/y", strtotime($created_at)) }}')
              ->remove_column('id')
              ->make();
    }
 
    /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function studentDocument($username)
    {

      $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->where('student.student_no','=',Auth::user()->username)
          ->select(['enrollment.id','enrollment.classification_level_id','classification_level.level','student_curriculum.id as student_id'])
          ->groupBy('enrollment.classification_level_id')->get();
      // Show the page
      return view('students_portal/student_document.index',compact('enrollment','classification_level_list'));

    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function StudentDocumentdata()
    {
        $student_id = \Input::get('student_id');
        $classification_level_id = \Input::get('classification_level_id');
     

            $student_document_list = StudentDocument::join('student','student_document.student_id','=','student.id')
              ->join('employee','student_document.teacher_id','=','employee.id')
              ->join('person as student_data','student.person_id','=','student_data.id')
              ->join('person as teacher','employee.person_id','=','teacher.id')
              ->join('student_guardian','student_guardian.student_id','=','student.id')
              ->join('guardian','student_guardian.guardian_id','=','guardian.id')
              ->join('folder','student_document.folder_id','=','folder.id')
              ->where('student_document.student_id',$student_id)
              ->where('student_document.classification_level_id',$classification_level_id)
              ->where('folder.parent_folder_id', '!=', 1)
              ->select(array('student_document.id','student_data.last_name as student_last_name','student_data.first_name as student_first_name','student_data.middle_name as student_middle_name','teacher.last_name as teacher_last_name','teacher.first_name as teacher_first_name','teacher.middle_name as teacher_middle_name','student.id as student_id'))
              ->orderBy('student_document.id', 'DESC')
              ->groupBy('student.id');
        return Datatables::of($student_document_list)
              ->add_column('document_management','<p class="details-control btn btn-sm btn-danger"><span class="glyphicon glyphicon-folder-open"></span> {{ Lang::get("form.view_file") }}</p>')
              ->edit_column('student_last_name', '{{{$student_last_name.", ".$student_first_name." ".$student_middle_name}}}')
              ->edit_column('teacher_last_name', '{{{$teacher_last_name.", ".$teacher_first_name." ".$teacher_middle_name}}}')
              ->remove_column('student_first_name','student_middle_name','teacher_first_name','teacher_middle_name')
              ->make(true);
    }


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */



    public function CreateFolderdataJson() {

      $folder_id = \Input::get('folder_id');
      $student_id = \Input::get('student_id');

      $query = Folder::join('student_document', 'student_document.folder_id', '=', 'folder.id')
            ->where('student_document.student_id', '=', $student_id)
            ->where('folder.parent_folder_id', '=', $folder_id)
            ->select(array('folder.id','folder.folder_name', 'folder.parent_folder_id', 'student_document.student_id'));

      $folder = $query->get();

      return response()->json($folder);
        
    }

    public function StudentDocumentJson() {

      $folder_id = \Input::get('folder_id');
      $student_id = \Input::get('student_id');

      $query = Folder::join('student_document', 'student_document.folder_id', '=', 'folder.id')
            ->where('student_document.student_id', '=', $student_id)
            ->where('folder.parent_folder_id', '!=', 1)
            ->select(array('folder.id','folder.folder_name', 'folder.parent_folder_id', 'student_document.student_id'));

      $folder = $query->get();

      return response()->json($folder);
        
    }


   public function fileDataJson(){

      $folder_id = \Input::get('folder_id');
      $student_id = \Input::get('student_id');

      $query = File::join('folder', 'file.folder_id', '=', 'folder.id')
            ->join('student_document', 'student_document.folder_id', '=', 'folder.id')
            ->where('student_document.student_id', '=', $student_id)
            ->where('file.folder_id', '=', $folder_id)
            ->select(array('file.id','file.file_name'));

      $file = $query->get();

      return response()->json($file);
    }

    public function downloadFile($id) {

        $entry = File::where('file.id', '=', $id)->firstOrFail();

        $pathToFile=storage_path().$entry->path.$entry->file_name;

        return response()->download($pathToFile);           
    } 


    
}
