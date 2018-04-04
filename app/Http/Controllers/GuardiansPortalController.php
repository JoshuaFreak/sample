<?php namespace App\Http\Controllers;

use App\Http\Controllers\GuardiansPortalMainController;
use App\Models\ActionTaken;
use App\Models\Attendance;
use App\Models\BloodType;
use App\Models\Citizenship;
use App\Models\CivilStatus;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\DesirableTraitDetail;
use App\Models\Employee;
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
use App\Models\Section;
use App\Models\SectionAdviser;
use App\Models\SectionAdviserStudent;
use App\Models\SectionMonitor;
use App\Models\SemesterLevel;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentAcademicProjection;
use App\Models\StudentAchievements;
use App\Models\StudentAttendance;
use App\Models\StudentCurriculum;
use App\Models\StudentDesirableTrait;
use App\Models\StudentDocument;
use App\Models\StudentGuardian;
use App\Models\StudentIntervention;
use App\Models\StudentLedger;
use App\Models\Suffix;
use App\Models\Term;
use App\Http\Requests\GenUserRequest;
use App\Http\Requests\GenUserEditRequest;
use App\Http\Requests\RegisterGuardianEditRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class GuardiansPortalController extends GuardiansPortalMainController {

    public function dataJson(){
        $condition = \Input::all();
        $query = StudentGuardian::join('student','student_guardian.student_id','=','student.id')
                ->join('guardian','student_guardian.guardian_id','=','guardian.id')
                ->join('student_curriculum','student_curriculum.student_id','=','student.id')
                ->join('enrollment','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
                ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
                ->join('classification','student_curriculum.classification_id','=','classification.id')
                ->join('person','student.person_id','=','person.id')
                // ->groupBy('student.id')
                ->select('student_guardian.id', 'student_guardian.student_id', 'student_curriculum.curriculum_id', 'enrollment.section_id', 'enrollment.term_id', 'enrollment.classification_level_id', 'curriculum.classification_id', 'classification_level.level', 'student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name','guardian.username');
      
        foreach($condition as $column => $value)
        { 
            if($column == 'query')
              {
                $query->where(function($query) use($value){
                          $query->where('Student_no', 'LIKE', "%$value%")
                                ->where('guardian.username', '=',Auth::user()->username);
                      })
                      ->orwhere(function($query) use($value){
                            $query->where('first_name', 'LIKE', "%$value%")
                                  ->where('guardian.username', '=',Auth::user()->username);
                        })
                      ->orwhere(function($query) use($value){
                            $query->where('middle_name', 'LIKE', "%$value%")
                                  ->where('guardian.username', '=',Auth::user()->username);
                        })
                      ->orwhere(function($query) use($value){
                            $query->where('last_name', 'LIKE', "%$value%")
                                  ->where('guardian.username', '=',Auth::user()->username);
                        });
              }
              else
              {
                $query->where($column, '=', $value);
              }   
        }
   

        $student_guardian = $query->get();

        return response()->json($student_guardian);
    }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function index()
    {
      
      $student_guardian = StudentGuardian::join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->join('gen_user','guardian.username','=','gen_user.username')
          ->join('person','guardian.person_id','=','person.id')
          ->where('gen_user.id','=', Auth::user()->id)
          ->select('student_guardian.id','person.last_name','person.first_name')->get()->last();
      // Show the page
      return view('guardian_portal.index',compact('student_guardian'));

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function getProfile($username)
    {
        $action = 1;
        $guardian = Guardian::join('gen_user','guardian.username','=','gen_user.username')
          ->where('guardian.username','=', $username)
            ->select('guardian.id', 'guardian.username', 'guardian.person_id')->get()->last();

        $person = Person::find($guardian->person_id);

        $gender_list = Gender::all();
        $suffix_list = Suffix::all();
        $religion_list = Religion::all();
        $blood_type_list = BloodType::all();
        $civil_status_list = CivilStatus::all();
        $citizenship_list = Citizenship::all();

        return view('guardian_portal/my_profile.index',compact('guardian','person','gender_list','suffix_list','religion_list','blood_type_list','civil_status_list','citizenship_list','action'));


    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postProfile(RegisterGuardianEditRequest $registered_edit_request, $id) {
      
        $guardian = Guardian::find($id);


        $person = Person::find($guardian->person_id);       
        $person->last_name = $registered_edit_request->last_name;
        $person->first_name = $registered_edit_request->first_name;
        $person->middle_name = $registered_edit_request->middle_name;
        $person->birthdate = $registered_edit_request->birthdate;
        $person->birth_place = $registered_edit_request->birth_place;
        $person->address = $registered_edit_request->address;
        $person->contact_no = $registered_edit_request->contact_no;
        $person->gender_id = $registered_edit_request->gender_id;
        $person->citizenship_id = $registered_edit_request->citizenship_id;
        $person->religion_id = $registered_edit_request->religion_id;
        $person->blood_type_id = $registered_edit_request->blood_type_id;
        $person->civil_status_id = $registered_edit_request->civil_status_id;
        $person->suffix_id = $registered_edit_request->suffix_id;

        $person->save();
        $guardian->save();

        $success = \Lang::get('guardian_portal.create_success').' : '.$person->last_name. ", ".$person->first_name. " ".$person->middle_name; 
        return redirect('guardian_portal/'.Auth::user()->username.'/my_profile')->withSuccess( $success);
    }
    /**
   * Display a listing of the resource.
   *
   * @return Response
   */
      public function getEdit($username) {

        $guardian = Guardian::join('gen_user','guardian.username','=','gen_user.username')
          ->where('guardian.username','=', $username)
            ->select('gen_user.id', 'guardian.username')->get()->last();
       //var_dump($its_customs_office);
        return view('guardian_portal/my_profile/edit',compact('guardian'));
      
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
        return redirect('guardian_portal/'.Auth::user()->username.'/edit')->withSuccess( $success);
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
          ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('student_guardian','student_guardian.student_id','=','student.id')
          ->join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->where('guardian.username','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id','enrollment.section_id','enrollment.classification_level_id','student_curriculum.classification_id','student_curriculum.classification_id','student_curriculum.curriculum_id','student.id as student_id' ,'student.student_no' ,'person.last_name' ,'person.first_name' ,'person.middle_name')->get();
      // Show the page
      return view('guardian_portal/schedule.index',compact('classification_level_list'));

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

      $student_curriculum= StudentCurriculum::where('student_id',$student_id)->get()->last();
      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('subject.name','!=','Break')
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

      return view('guardian_portal/schedule/detail', 
        compact(
            'curriculum_subject_list',
            'schedule_list',
            'day_list'
        )
      );

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function Curriculum($username)
    {
      
      $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('student_guardian','student_guardian.student_id','=','student.id')
          ->join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->where('guardian.username','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id','enrollment.section_id','enrollment.classification_level_id','student_curriculum.classification_id','student_curriculum.classification_id','student_curriculum.curriculum_id','student.id as student_id' ,'student.student_no' ,'person.last_name' ,'person.first_name' ,'person.middle_name')->get();
      // Show the page
      return view('guardian_portal/curriculum.index',compact('classification_level_list'));

    }

    public function getDetail()
    {
      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');
      $term_id = \Input::get('term_id');

      $enrollment = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->where('student_curriculum.student_id',$student_id)
          ->where('enrollment.classification_level_id',$classification_level_id)
          ->where('enrollment.term_id',$term_id)
          ->select('enrollment.id','student_curriculum.curriculum_id','student_curriculum.classification_id')->get()->last();

      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
          ->join('subject','subject_offered.subject_id','=','subject.id')
          ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
          ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
          ->select('curriculum_subject.id', 'curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name')
          ->where('curriculum_subject.classification_id',$enrollment->classification_id)
          ->where('curriculum_subject.curriculum_id',$enrollment->curriculum_id)
          ->where('subject.name','!=','Break')
          ->groupBy('classification_level.level')
          ->orderBy('curriculum_subject.id', 'ASC')
          ->get();

      $subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
          ->join('subject','subject_offered.subject_id','=','subject.id')
          ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
          ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
          ->select('curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'subject.code', 'subject.name', 'subject.credit_unit')
          ->where('curriculum_subject.classification_id',$enrollment->classification_id)
          ->where('curriculum_subject.curriculum_id',$enrollment->curriculum_id)
          ->where('subject.name','!=','Break')
          ->orderBy('curriculum_subject.id', 'ASC')
          ->get();

      $classification = Classification::where('id',$enrollment->classification_id)->select('classification.id','classification.classification_name')->get()->last();
      $curriculum = Curriculum::where('id',$enrollment->curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();

      return view('guardian_portal/curriculum.detail', compact('curriculum_subject_list','subject_list','classification','curriculum'));

    }
 
   /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    // public function academicProjection($username)
    // {

    //   $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
    //       ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
    //       ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
    //       ->join('student','student_curriculum.student_id','=','student.id')
    //       ->join('person','student.person_id','=','person.id')
    //       ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
    //       ->join('student_guardian','student_guardian.student_id','=','student.id')
    //       ->join('guardian','student_guardian.guardian_id','=','guardian.id')
    //       ->where('guardian.username','=', $username)
    //       ->select('classification_level.id','classification_level.level','enrollment.term_id','enrollment.section_id','enrollment.classification_level_id','student_curriculum.classification_id','student_curriculum.classification_id','student_curriculum.curriculum_id','student.id as student_id' ,'student.student_no' ,'person.last_name' ,'person.first_name' ,'person.middle_name')->groupBy('level')->get();
    //   // Show the page
    //   return view('guardian_portal/academic_projection.index',compact('classification_level_list'));

    // }

    // public function getacademicProjectionDetail()
    // {
    //   // $condition = \Input::all();
    //   $term_id = \Input::get('term_id');
    //   $student_id = \Input::get('student_id');
    //   $classification_level_id = \Input::get('classification_level_id');

    //   $subject_list = StudentAcademicProjection::join('subject', 'student_academic_projection.subject_id', '=', 'subject.id')
    //             ->where('student_academic_projection.student_id','=',$student_id)
    //             ->where('student_academic_projection.term_id','=',$term_id)
    //             ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
    //             ->select('subject.id','subject.name', 'student_academic_projection.subject_id')
    //             ->groupBy('subject.id')->get();

    //   // $arr[0][0] = "";
    //   // $arr[0][1] = "";
                
    //   $arr = array();
    //   $grade_id_arr = array();
    //   $detail_arr = array();

    //   $row_no = 0;
    //   foreach ($subject_list as $subject) {
    //     $arr[$row_no][0] = $subject->name;

    //       $pace_list = StudentAcademicProjection::where('student_academic_projection.student_id','=',$student_id)
    //             ->where('student_academic_projection.term_id','=',$term_id)
    //             ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
    //             ->where('student_academic_projection.subject_id','=',$subject->subject_id)
    //             ->select('student_academic_projection.id','student_academic_projection.required_pace','student_academic_projection.actual_pace','student_academic_projection.date_released','student_academic_projection.date_taken','student_academic_projection.grade','student_academic_projection.subject_id')->get();
        
    //       $arr[$row_no][1] = "(REQUIRED)";
    //       $col_no = 2;
    //       foreach ($pace_list as $pace) {
    //         $arr[$row_no][$col_no] = $pace->required_pace;
    //         // $arr[0][$col_no] = "";
    //         $grade_id_arr[$row_no][$col_no] = $pace->id;
    //         $detail_arr[$row_no][$col_no] = 'required';
    //         $col_no++;
    //       }
    //       $row_no++;

    //       $arr[$row_no][1] = "(ACTUAL)";
    //       $col_no = 2;
    //       foreach ($pace_list as $pace) {
    //         $arr[$row_no][$col_no] = $pace->actual_pace;
    //         $grade_id_arr[$row_no][$col_no] = $pace->id;
    //         $detail_arr[$row_no][$col_no] = 'actual';
    //         $col_no++;
    //       }
    //       $row_no++;

    //       $arr[$row_no][1] = "DATE RELEASED";
    //       $col_no = 2;
    //       foreach ($pace_list as $pace) {
    //         $arr[$row_no][$col_no] = $pace->date_released;
    //         $grade_id_arr[$row_no][$col_no] = $pace->id;
    //         $detail_arr[$row_no][$col_no] = 'date_released';
    //         $col_no++;
    //       }
    //       $row_no++;

    //       $arr[$row_no][1] = "DATE TAKEN";
    //       $col_no = 2;
    //       foreach ($pace_list as $pace) {
    //         $arr[$row_no][$col_no] = $pace->date_taken;
    //         $grade_id_arr[$row_no][$col_no] = $pace->id;
    //         $detail_arr[$row_no][$col_no] = 'date_taken';
    //         $col_no++;
    //       }
    //       $row_no++;

    //       $arr[$row_no][1] = "GRADE";
    //       $col_no = 2;
    //       foreach ($pace_list as $pace) {
    //         $arr[$row_no][$col_no] = $pace->grade;
    //         $grade_id_arr[$row_no][$col_no] = $pace->id;
    //         $detail_arr[$row_no][$col_no] = 'grade';
    //         $col_no++;
    //       }
    //       $row_no++;

    //   }

    //   $data = array($arr, $grade_id_arr, $detail_arr);
    //   return response()->json($data);

    // }    


    public function academicProjection($username)
    {

      // $classification_list = Classification::all();
      // // Show the page
      // return view('guardian_portal/academic_projection.index_projection',compact('classification_list'));

    $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('student_guardian','student_guardian.student_id','=','student.id')
          ->join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->where('guardian.username','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id','enrollment.section_id','enrollment.classification_level_id','student_curriculum.classification_id','student_curriculum.classification_id','student_curriculum.curriculum_id','student.id as student_id' ,'student.student_no' ,'person.last_name' ,'person.first_name' ,'person.middle_name')->get();
      // Show the page
      return view('guardian_portal/academic_projection.index_projection',compact('classification_level_list'));

    }
    public function getacademicProjectionDetail()
    {

 
      $condition = \Input::all();
      $classification_id = \Input::get('classification_id');
      $student_id = \Input::get('student_id');
      $term_id = \Input::get('term_id');

      $row_no = 0;
      $pace_classification_list = Pace::join('classification_level','pace.classification_level_id','=','classification_level.id')
            ->select('pace.id','classification_level.level','pace.classification_level_id')
            ->where('classification_level.classification_id','=', $classification_id)
            ->groupBy('pace.classification_level_id')
            ->get();

      foreach ($pace_classification_list as $pace_classification) {
          $arr[$row_no][0] = $pace_classification->level;

          $subject_pace_list = Pace::join('subject','pace.subject_id','=','subject.id')
              ->where('classification_level_id','=', $pace_classification->classification_level_id)
              ->select('pace.id','subject.name','pace.subject_id')->groupBy('subject.id')->get();

          foreach ($subject_pace_list as $subject_pace) {
            $arr[$row_no][1] = $subject_pace->name;

              $col_no = 2;
              $pace_list = Pace::where('subject_id','=', $subject_pace->subject_id)
                ->where('classification_level_id','=', $pace_classification->classification_level_id)
                ->get();
              foreach ($pace_list as $pace) {

                $required_pace = StudentAcademicProjection::where('required_pace','=', $pace->pace_name)
                // ->where('grading_period_id','=', $pace->grading_period_id)
                // ->where('classification_level_id','=', $pace->classification_level_id)
                ->where('subject_id','=', $pace->subject_id)
                ->where('student_id','=', $student_id)
                ->where('term_id','=', $term_id)
                ->get()->last();

                $released_pace = StudentAcademicProjection::where('actual_pace','=', $pace->pace_name)
                // ->where('grading_period_id','=', $pace->grading_period_id)
                // ->where('classification_level_id','=', $pace->classification_level_id)
                ->where('subject_id','=', $pace->subject_id)
                ->where('student_id','=', $student_id)
                ->where('term_id','=', $term_id)
                ->get()->last();

                $end_pace = StudentAcademicProjection::where('actual_pace','=', $pace->pace_name)
                // ->where('grading_period_id','=', $pace->grading_period_id)
                // ->where('classification_level_id','=', $pace->classification_level_id)
                ->where('subject_id','=', $pace->subject_id)
                ->where('student_id','=', $student_id)
                ->where('term_id','!=', $term_id)
                ->get()->last();
                    
                    if($required_pace != null && $released_pace != null && $released_pace->grade != null){
                      $arr[$row_no][$col_no] = $released_pace->actual_pace;
                      $pace_arr[$row_no][$col_no] = "finished_pace";

                    }elseif($required_pace != null && $released_pace != null && $released_pace->grade == null){
                      $arr[$row_no][$col_no] = $released_pace->actual_pace;
                      $pace_arr[$row_no][$col_no] = "released_pace";

                    }elseif($required_pace != null){
                      $arr[$row_no][$col_no] = $required_pace->required_pace;
                      $pace_arr[$row_no][$col_no] = "required_pace";

                    }elseif($end_pace != null){
                      $arr[$row_no][$col_no] = $end_pace->actual_pace;
                      $pace_arr[$row_no][$col_no] = "end_pace";

                    }else{
                      $arr[$row_no][$col_no] = $pace->pace_name;
                      $pace_arr[$row_no][$col_no] = "pace";
                    }

                $col_no++;
              }

            $row_no++;
          }
      }

      $data = array($arr, $pace_arr);
      return response()->json($data);
    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function Grade($username)
    {

      // $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          $classification_level_list = StudentGuardian::leftJoin('student','student_guardian.student_id','=','student.id')
                ->leftJoin('guardian','student_guardian.guardian_id','=','guardian.id')
                ->leftJoin('student_curriculum','student_curriculum.student_id','=','student.id')
                ->leftJoin('enrollment','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->leftJoin('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
                ->leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
                ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
                ->leftJoin('person','student.person_id','=','person.id')
          ->where('guardian.username','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id','enrollment.section_id','enrollment.classification_level_id','student_curriculum.classification_id','student_curriculum.classification_id','student_curriculum.curriculum_id','student.id as student_id' ,'student.student_no' ,'person.last_name' ,'person.first_name' ,'person.middle_name')->get();
      // Show the page
      return view('guardian_portal/grade.index',compact('classification_level_list'));
    

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
            
      // $section_adviser = SectionAdviser::join('employee','section_adviser.employee_id','=','employee.id')
      //       ->join('person','employee.person_id','=','person.id')
      //       ->join('section_monitor','section_adviser.section_monitor_id','=','section_monitor.id')
      //       ->where('section_monitor.section_id','=',$section_id)
      //       ->select(['section_adviser.id','person.last_name','person.first_name','person.middle_name'])->get()->last();      

      $section_adviser = SectionAdviserStudent::leftJoin('section_adviser','section_adviser_student.section_adviser_id','=','section_adviser.id')
            ->join('employee','section_adviser.employee_id','=','employee.id')
            ->join('person','employee.person_id','=','person.id')
            ->join('section_monitor','section_adviser.section_monitor_id','=','section_monitor.id')
            ->where('section_monitor.section_id','=',$section_id)
            ->where('section_adviser_student.student_id','=',$student_id)
            ->select(['section_adviser.id','person.last_name','person.first_name','person.middle_name'])->get()->last();

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


            return view('guardian_portal/grade/detail', 
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

    public function studentLedger($username)
    {
      
      $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('student_guardian','student_guardian.student_id','=','student.id')
          ->join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->where('guardian.username','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id')->get();
      // Show the page
      return view('guardian_portal/student_ledger.index',compact('classification_level_list'));

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

    public function studentAssessment()
    {

        $student_guardian_list = StudentGuardian::join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->join('student','student_guardian.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->where('guardian.username','=', Auth::user()->username)
          ->select('student_guardian.id','student_guardian.student_id','student.student_no','person.last_name','person.first_name','person.middle_name','student_curriculum.classification_id')->get();

        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $semester_level_list = SemesterLevel::orderBy('semester_level.id','ASC')->get();
        $term_list = Term::orderBy('term.id','ASC')->get();

        // Show the page
        return view('guardian_portal/student_assessment.index',compact('enrollment','classification_level_list', 'semester_level_list', 'term_list'));
    }

    public function getDetailAssessment()
    {
      $action = 1;
      $student_id = \Input::get('student_id');
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $semester_level_id = \Input::get('semester_level_id');
      $term_id = \Input::get('term_id');
      $student_curriclum= StudentCurriculum::where('student_id',$student_id)->get()->last();
      $student_list = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name')->get();
      $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
            ->where('classification_id',$classification_id)
            // ->where('program_id',$curriculum->program_id)
            ->where('term_id',$term_id)
            ->select('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount')->get();
      $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->where('curriculum_subject.curriculum_id',$student_curriclum->curriculum_id)
            ->where('curriculum_subject.classification_id',$classification_id)
            ->where('curriculum_subject.classification_level_id',$classification_level_id)
            ->where('curriculum_subject.semester_level_id',$semester_level_id)
            ->select('curriculum_subject.id','subject.name','subject.credit_unit','subject.hour_unit')->get();
      $student_ledger = StudentLedger::where('student_id', $student_id)
            ->where('term_id', $term_id)
            ->select('student_ledger.id','student_ledger.total_balance')->get()->last();

      return view('guardian_portal/student_assessment/detail', 
        compact(
            'student_list',
            'miscellaneous_fee_detail_list',
            'curriculum_subject_list',
            'student_ledger'
        )
      );

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function attendance($username)
    {
      
      $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('student_guardian','student_guardian.student_id','=','student.id')
          ->join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->where('guardian.username','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id','enrollment.section_id','enrollment.classification_level_id','student_curriculum.classification_id','student_curriculum.classification_id','student_curriculum.curriculum_id','student.id as student_id' ,'student.student_no' ,'person.last_name' ,'person.first_name' ,'person.middle_name')->get();
      // Show the page
      return view('guardian_portal/attendance.index',compact('classification_level_list'));

    }

    public function attendancedata()
    {
      
      
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
        $classification_level_id = \Input::get('classification_level_id');
        $section_id = \Input::get('section_id');

        // if($student_id == "" && $student_id == null && $term_id == "" && $term_id == null && $classification_level_id == "" && $classification_level_id == null && $section_id == "" && $section_id == null)
        // {
        //     $student_attendance_list = StudentAttendance::join('attendance','student_attendance.attendance_id','=','attendance.id')
        //       ->join('student','student_attendance.student_id','=','student.id')
        //       ->join('attendance_remark','student_attendance.attendance_remark_id','=','attendance_remark.id')
        //       ->join('student_guardian','student_guardian.student_id','=','student.id')
        //       ->join('guardian','student_guardian.guardian_id','=','guardian.id')
        //       ->where('guardian.username','=', Auth::user()->username)
        //       ->where('student_attendance.student_id',$student_id)
        //       ->where('attendance.term_id',$term_id)
        //       ->where('attendance.classification_level_id',$classification_level_id)
        //       ->where('attendance.section_id',$section_id)
        //       ->select(array('student_attendance.id','attendance.date','attendance_remark.attendance_remarks_name'))
        //       ->orderBy('student_attendance.id', 'DESC');
        // }
        // else
        // {
            $student_attendance_list = StudentAttendance::join('attendance','student_attendance.attendance_id','=','attendance.id')
              ->join('student','student_attendance.student_id','=','student.id')
              ->join('attendance_remark','student_attendance.attendance_remark_id','=','attendance_remark.id')
              ->join('student_guardian','student_guardian.student_id','=','student.id')
              ->join('guardian','student_guardian.guardian_id','=','guardian.id')
              // ->where('guardian.username','=', Auth::user()->username)
              ->where('student_attendance.student_id',$student_id)
              // ->where('attendance.term_id',$term_id)
              // ->where('attendance.classification_level_id',$classification_level_id)
              // ->where('attendance.section_id',$section_id)
              ->select(array('student_attendance.id','attendance.date','attendance_remark.attendance_remarks_name'))
              ->orderBy('student_attendance.id', 'DESC');
        // }
        return Datatables::of($student_attendance_list)
              // ->edit_column('date', '{{{date("M d, Y",strtotime($date))}}}')
              ->remove_column('id')
              ->make();

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function intervention()
    {
      // Show the page
      return view('guardian_portal/intervention.index');

    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function Interventiondata()
    {
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');

        if($student_id == "" && $student_id == null && $term_id == "" && $term_id == null)
        {
            $student_intervention_list = StudentIntervention::join('student','student_intervention.student_id','=','student.id')
              ->join('employee','student_intervention.teacher_id','=','employee.id')
              ->join('person as student_data','student.person_id','=','student_data.id')
              ->join('person as teacher','employee.person_id','=','teacher.id')
              ->where('student_intervention.student_id',$student_id)
              ->where('student_intervention.term_id',$term_id)
              ->select(array('student_intervention.id','student_intervention.created_at','student_data.last_name as student_last_name','student_data.first_name as student_first_name','student_data.middle_name as student_middle_name','teacher.last_name as teacher_last_name','teacher.first_name as teacher_first_name','teacher.middle_name as teacher_middle_name'))->orderBy('student_intervention.id', 'DESC');
        }
        else
        {
            $student_intervention_list = StudentIntervention::join('student','student_intervention.student_id','=','student.id')
              ->join('employee','student_intervention.teacher_id','=','employee.id')
              ->join('person as student_data','student.person_id','=','student_data.id')
              ->join('person as teacher','employee.person_id','=','teacher.id')
              ->join('student_guardian','student_guardian.student_id','=','student.id')
              ->join('guardian','student_guardian.guardian_id','=','guardian.id')
              ->where('guardian.gen_user_id','=', Auth::user()->id)
              ->where('student_intervention.student_id',$student_id)
              ->where('student_intervention.term_id',$term_id)
              ->select(array('student_intervention.id','student_intervention.created_at','student_data.last_name as student_last_name','student_data.first_name as student_first_name','student_data.middle_name as student_middle_name','teacher.last_name as teacher_last_name','teacher.first_name as teacher_first_name','teacher.middle_name as teacher_middle_name'))->orderBy('student_intervention.id', 'DESC');
        }
        return Datatables::of($student_intervention_list)
              ->add_column('actions', '<a href="{{{ URL::to(\'guardian_portal/intervention/\' . $id . \'/view\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-eye-open"></span> {{ Lang::get("form.view") }}</a>')
              ->edit_column('created_at', '{{{date("M d, Y",strtotime($created_at))}}}')
              ->edit_column('student_last_name', '{{{$student_last_name.", ".$student_first_name." ".$student_middle_name}}}')
              ->edit_column('teacher_last_name', '{{{$teacher_last_name.", ".$teacher_first_name." ".$teacher_middle_name}}}')
              ->remove_column('id','student_first_name','student_middle_name','teacher_first_name','teacher_middle_name')
              ->make();
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function ViewIntervention($id) {

        $student_intervention = StudentIntervention::find($id);
        $student = Student::find($student_intervention->student_id);
        $person = Person::find($student->person_id);
        $employee = Employee::find($student_intervention->teacher_id);
        $teacher = Person::find($employee->person_id);
        $grading_period = GradingPeriod::find($student_intervention->grading_period_id);
        $action_taken = ActionTaken::find($student_intervention->action_taken_id);
        $classification = Classification::find($student_intervention->classification_id);
        $term = Term::find($student_intervention->term_id);
        $username = Auth::user()->username;
       
        return view('guardian_portal/intervention/view',compact('username', 'student_intervention', 'student', 'person', 'teacher', 'grading_period', 'action_taken', 'classification', 'term'));

    }

    public function studentDocument($username)
    {
      $classification_level_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
          ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
          ->leftJoin('classification','student_curriculum.classification_id','=','classification.id')
          ->join('student','student_curriculum.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
          ->join('student_guardian','student_guardian.student_id','=','student.id')
          ->join('guardian','student_guardian.guardian_id','=','guardian.id')
          ->where('guardian.username','=', $username)
          ->select('classification_level.id','classification_level.level','enrollment.term_id','enrollment.section_id','enrollment.classification_level_id','student_curriculum.classification_id','student_curriculum.classification_id','student_curriculum.curriculum_id','student.id as student_id' ,'student.student_no' ,'person.last_name' ,'person.first_name' ,'person.middle_name')->get();
      // Show the page
      return view('guardian_portal/student_document.index',compact('classification_level_list'));

    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function StudentDocumentdata()
    {
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');

        // if($student_id == "" && $student_id == null && $term_id == "" && $term_id == null)
        // {
            // $student_document_list = StudentDocument::join('student','student_document.student_id','=','student.id')
            //   ->join('employee','student_document.teacher_id','=','employee.id')
            //   ->join('person as student_data','student.person_id','=','student_data.id')
            //   ->join('person as teacher','employee.person_id','=','teacher.id')
            //   ->join('folder','student_document.folder_id','=','folder.id')
            //   ->where('student_document.student_id',$student_id)
            //   // ->where('student_document.term_id',$term_id)
            //   ->where('folder.parent_folder_id', '!=', 1)
            //   ->select(array('student_document.id','student_data.last_name as student_last_name','student_data.first_name as student_first_name','student_data.middle_name as student_middle_name','teacher.last_name as teacher_last_name','teacher.first_name as teacher_first_name','teacher.middle_name as teacher_middle_name', 'student.id as student_id'))
            //   ->orderBy('student_document.id', 'DESC')
            //   ->groupBy('student.id');
        // }
        // else
        // {
            $student_document_list = StudentDocument::join('student','student_document.student_id','=','student.id')
              ->join('employee','student_document.teacher_id','=','employee.id')
              ->join('person as student_data','student.person_id','=','student_data.id')
              ->join('person as teacher','employee.person_id','=','teacher.id')
              ->join('student_guardian','student_guardian.student_id','=','student.id')
              ->join('guardian','student_guardian.guardian_id','=','guardian.id')
              ->join('folder','student_document.folder_id','=','folder.id')
              ->where('guardian.gen_user_id','=', Auth::user()->id)
              ->where('student_document.student_id',$student_id)
              // ->where('student_document.term_id',$term_id)
              ->where('folder.parent_folder_id', '!=', 1)
              ->select(array('student_document.id','student_data.last_name as student_last_name','student_data.first_name as student_first_name','student_data.middle_name as student_middle_name','teacher.last_name as teacher_last_name','teacher.first_name as teacher_first_name','teacher.middle_name as teacher_middle_name','student.id as student_id'))
              ->orderBy('student_document.id', 'DESC')
              ->groupBy('student.id');
        // }
        return Datatables::of($student_document_list)
              ->add_column('document_management','<p class="details-control btn btn-sm btn-danger"><span class="glyphicon glyphicon-folder-open"></span> {{ Lang::get("form.view_file") }}</p>')
                // <button class="btn btn-sm btn-success" data-id="{{{$id}}}" data-student_id="{{{$student_id}}}" data-last_name="{{{$student_last_name}}}" data-first_name="{{{$student_first_name}}}" data-middle_name="{{{$student_middle_name}}}"  data-folder_name="{{{$folder_name}}}" data-toggle="modal" data-target="#viewFileModal"><span class="glyphicon glyphicon-folder-open"></span> {{ Lang::get("form.view_file") }}</button>
               
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


    // public function dataJsonFile($username)
    // {
      
      // $file_list = File::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
      //     ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
      //     ->join('student','student_curriculum.student_id','=','student.id')
      //     ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
      //     ->join('student_guardian','student_guardian.student_id','=','student.id')
      //     ->join('guardian','student_guardian.guardian_id','=','guardian.id')
      //     ->where('guardian.username','=', $username)
      //     ->select('classification_level.id','classification_level.level','enrollment.term_id')->groupBy('level')->get();
      // // Show the page
      // return view('guardian_portal/student_document.index',compact('file_list'));

  // } 

    // public function dataJsonFile(){
   
    // $folder_id = \Input::get('folder_id');
    //   $query = File::join('folder', 'file.folder_id', '=', 'folder.id')
    //           ->where('folder.id', '=', $folder_id)
    //           ->select();

    //   $file = $query->select(['file.id as id','file.file_name as name'])->get();

    //   return response()->json($file);
    // }

} 