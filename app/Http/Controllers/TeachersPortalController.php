<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Citizenship;
use App\Models\CivilStatus;
use App\Models\Day;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\Gender;
use App\Models\Generic\GenUser;
use App\Models\Person;
use App\Models\Religion;
use App\Models\Schedule;
use App\Models\Suffix;
use App\Models\Teacher;
use App\Models\TeacherCategory;
use App\Models\TeacherDegree;
use App\Models\TeacherSubject;
use App\Models\TEClass;
use App\Models\Term;
use App\Http\Requests\GenUserRequest;
use App\Http\Requests\GenUserEditRequest;
use App\Http\Requests\RegisteredTeacherEditRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TeachersPortalController extends TeachersPortalMainController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */


    public function index()
    {
      
      $teacher = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
          ->join('person','employee.person_id','=','person.id')
          ->leftJoin('suffix','person.suffix_id','=','suffix.id')
          ->join('gen_user','employee.employee_no','=','gen_user.username')
          ->where('gen_user.id','=', Auth::user()->id)
          ->select('gen_user.id','employee.id as employee_id' ,'person.last_name','person.first_name','person.middle_name','suffix.suffix_name')->get()->last();
      // Show the page
      return view('teachers_portal.index',compact('teacher'));

    }

      /**
   * Display a listing of the resource.
   *
   * @return Response
   */
    public function myProfile($username)
      {
         // $employee = Employee::join('person','employee.person_id','=','person.id')
         //    ->join('suffix','person.suffix_id','=','suffix.id')
         //    ->join('gender','person.gender_id','=','gender.id')
         //    ->join('citizenship','person.citizenship_id','=','citizenship.id')
         //    ->join('religion','person.religion_id','=','religion.id')
         //    ->join('civil_status','person.civil_status_id','=','civil_status.id')
         //    ->join('employment_status','employee.employment_status_id','=','employment_status.id')
         //    ->where('employee.employee_no','=', $username)
         //    ->select('employee.id','employee.employee_no','employee.date_employed','person.last_name','person.first_name','person.middle_name','person.birthdate','person.email_address','suffix.suffix_name','gender.gender_name','person.address','person.contact_no','citizenship.citizenship_name','religion.religion_name','civil_status.civil_status_name','employment_status.employment_status_name')->get()->last();

         $teacher = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                ->join('person','employee.person_id','=','person.id')
                ->leftJoin('suffix','person.suffix_id','=','suffix.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
                ->leftJoin('religion','person.religion_id','=','religion.id')
                ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
                ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
                ->where('employee.employee_no','=', $username)
                ->select('teacher.id as teacher_id','employee.id as employee_id','employee.employee_no','employee.date_employed','person.last_name','person.first_name','person.middle_name','person.birthdate','person.email_address','suffix.suffix_name','gender.gender_name','person.address','person.contact_no','citizenship.citizenship_name','religion.religion_name','civil_status.civil_status_name','employment_status.employment_status_name')->get()->last();

        $teacher_degree = TeacherDegree::join('teacher', 'teacher_degree.teacher_id', '=', 'teacher.id')
                ->join('degree', 'teacher_degree.degree_id', '=', 'degree.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('employee.employee_no', '=', $username)
                ->select('teacher_degree.id', 'degree.description')->get()->last();

        $teacher_category = TeacherCategory::join('teacher', 'teacher_category.teacher_id', '=', 'teacher.id')
                ->join('employee_category', 'teacher_category.employee_category_id', '=', 'employee_category.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('employee.employee_no', '=', $username)
                ->select('teacher_category.id', 'employee_category.description')->get()->last();
            
          return view('teachers_portal/my_profile.index',compact('teacher', 'teacher_degree', 'teacher_category'));


      }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
      public function getEdit($username) {

        $teacher = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
            ->join('gen_user','employee.employee_no','=','gen_user.username')
            ->where('employee.employee_no','=', $username)
            ->select('gen_user.id', 'employee.id as employee_id')->get()->last();
       //var_dump($its_customs_office);
        return view('teachers_portal/my_profile/edit',compact('teacher'));
      
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

         return redirect('teachers_portal');
      }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
      public function getEditProfile($username) {

        $action = 1;

        $teacher = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
            ->join('person','employee.person_id','=','person.id')
            ->leftJoin('suffix','person.suffix_id','=','suffix.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
            ->leftJoin('religion','person.religion_id','=','religion.id')
            ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
            ->join('gen_user','employee.employee_no','=','gen_user.username')
            ->where('employee.employee_no','=', $username)
            ->select('gen_user.id', 'teacher.id as teacher_id', 'employee.person_id','employee.id as employee_id','employee.date_employed','employment_status.employment_status_name', 'person.last_name', 'person.first_name', 'person.middle_name','person.birthdate','person.address','person.contact_no','person.email_address', 'suffix.suffix_name', 'gender.gender_name', 'civil_status.civil_status_name', 'citizenship.citizenship_name', 'religion.religion_name')->get()->last();

        $person = Person::find($teacher->person_id);

        $teacher_degree = TeacherDegree::join('teacher', 'teacher_degree.teacher_id', '=', 'teacher.id')
            ->join('degree', 'teacher_degree.degree_id', '=', 'degree.id')
              ->join('employee','teacher.employee_id','=','employee.id')
              ->where('employee.employee_no', '=', $username)
            ->select('teacher_degree.id', 'degree.description')->get()->last();

        $teacher_category = TeacherCategory::join('teacher', 'teacher_category.teacher_id', '=', 'teacher.id')
            ->leftJoin('employee_category', 'teacher_category.employee_category_id', '=', 'employee_category.id')
              ->join('employee','teacher.employee_id','=','employee.id')
              ->where('employee.employee_no', '=', $username)
            ->select('teacher_category.id', 'employee_category.description')->get()->last();


        $suffix_list = Suffix::orderBy('suffix.suffix_name', 'ASC')->get();
        $gender_list = Gender::all();
        $religion_list = Religion::all();
        $civil_status_list = CivilStatus::all();
        $citizenship_list = Citizenship::all();
        $employment_status_list = EmploymentStatus::orderBy('employment_status.employment_status_name', 'ASC')->get();
        
        return view('teachers_portal/my_profile/edit_profile',compact('teacher','action','person','suffix_list','gender_list','religion_list','civil_status_list','citizenship_list','teacher_category','teacher_degree','employment_status_list'));
      
    }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEditProfile(RegisteredTeacherEditRequest $registered_teacher_edit_request, $teacher_id) {

        $teacher = Teacher::find($teacher_id);
        $employee = Employee::find($teacher->employee_id);
        $person = Person::find($employee->person_id);       
        $person->last_name = $registered_teacher_edit_request->last_name;
        $person->first_name = $registered_teacher_edit_request->first_name;
        $person->middle_name = $registered_teacher_edit_request->middle_name;
        $person->birthdate = $registered_teacher_edit_request->birthdate;
        $person->address = $registered_teacher_edit_request->address;
        $person->contact_no = $registered_teacher_edit_request->contact_no;
        $person->gender_id = $registered_teacher_edit_request->gender_id;
        $person->citizenship_id = $registered_teacher_edit_request->citizenship_id;
        $person->religion_id = $registered_teacher_edit_request->religion_id;
        $person->civil_status_id = $registered_teacher_edit_request->civil_status_id;
        $person->suffix_id = $registered_teacher_edit_request->suffix_id;

        $person->save();
        $teacher->save();


        $success = \Lang::get('guardian_portal.create_success').' : '.$person->last_name. ", ".$person->first_name. " ".$person->middle_name; 
        // return redirect('teachers_portal/'.Auth::user()->username.'/my_profile')->withSuccess( $success);
      }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */

    public function schedule($username)
    {
      // Show the page
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $term_list = Term::orderBy('term.id','ASC')->get();
        $day_list = Day::orderBy('day.id','ASC')->get();

        return view('teachers_portal/schedule.index', compact('classification_list', 'classification_level_list', 'term_list', 'day_list'));
    }

       /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function scheduleData()
    {
       $classification_id = \Input::get('classification_id');
       $classification_level_id = \Input::get('classification_level_id');
       $term_id = \Input::get('term_id');
       $day_id = \Input::get('day_id');


        if($classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null && $day_id != "" && $day_id != null)
        {
            $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('class.classification_level_id','=',$classification_level_id)
                ->where('class.term_id','=',$term_id)
                ->where('schedule.day_id','=',$day_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('class.id','time_start.time as time_start','time_start.time_session as time_start_session','time_end.time as time_end','time_end.time_session as time_end_session','room.room_name','day.day_code','section.section_name','classification_level.level','subject.name','term.term_name'))
                ->orderBy('class.term_id', 'DESC');
        }
        elseif($classification_id != "" && $classification_id != null) 
        {

          $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('classification_level.classification_id','=',$classification_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('class.id','time_start.time as time_start','time_start.time_session as time_start_session','time_end.time as time_end','time_end.time_session as time_end_session','room.room_name','day.day_code','section.section_name','classification_level.level','subject.name','term.term_name'))
                ->orderBy('class.term_id', 'DESC');
        }
        elseif($classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null) 
        {

          $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('class.classification_level_id','=',$classification_level_id)
                ->where('class.term_id','=',$term_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('class.id','time_start.time as time_start','time_start.time_session as time_start_session','time_end.time as time_end','time_end.time_session as time_end_session','room.room_name','day.day_code','section.section_name','classification_level.level','subject.name','term.term_name'))
                ->orderBy('class.term_id', 'DESC');
        }
        elseif($classification_level_id != "" && $classification_level_id != null) 
        {

          $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('class.classification_level_id','=',$classification_level_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('class.id','time_start.time as time_start','time_start.time_session as time_start_session','time_end.time as time_end','time_end.time_session as time_end_session','room.room_name','day.day_code','section.section_name','classification_level.level','subject.name','term.term_name'))
                ->orderBy('class.term_id', 'DESC');
        }
        elseif($term_id != "" && $term_id != null) 
        {

          $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('class.term_id','=',$term_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('class.id','time_start.time as time_start','time_start.time_session as time_start_session','time_end.time as time_end','time_end.time_session as time_end_session','room.room_name','day.day_code','section.section_name','classification_level.level','subject.name','term.term_name'))
                ->orderBy('class.term_id', 'DESC');
        }
        elseif($day_id != "" && $day_id != null) 
        {

          $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                ->where('schedule.day_id','=',$day_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('class.id','time_start.time as time_start','time_start.time_session as time_start_session','time_end.time as time_end','time_end.time_session as time_end_session','room.room_name','day.day_code','section.section_name','classification_level.level','subject.name','term.term_name'))
                ->orderBy('class.term_id', 'DESC');
        }
        else
        {
            $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')                
                ->join('classification_level','class.classification_level_id','=','classification_level.id')
                ->join('time as time_start','schedule.time_start','=','time_start.id')
                ->join('time as time_end','schedule.time_end','=','time_end.id')
                ->join('teacher','class.teacher_id','=','teacher.id')
                ->join('employee','teacher.employee_id','=','employee.id')
                // ->where('class.classification_level_id','=',$classification_level_id)
                // ->where('class.term_id','=',$term_id)
                // ->where('schedule.day_id','=',$day_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('class.id','time_start.time as time_start','time_start.time_session as time_start_session','time_end.time as time_end','time_end.time_session as time_end_session','room.room_name','day.day_code','section.section_name','classification_level.level','subject.name','term.term_name'))
                ->orderBy('class.term_id', 'DESC');
        }
        return Datatables::of($schedule_list)
                ->editColumn('time_start','{{{$time_start."".$time_start_session." - ".$time_end."".$time_end_session." - ( ".$room_name." - ".$day_code.")" }}}')
                ->editColumn('section_name','{{{ $level." - ".$section_name}}}')
                ->remove_column('id', 'time_start_session', 'time_end', 'time_end_session', 'room_name', 'day_code', 'level')
                ->make();

    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function subject()
    {     

          $classification_list = Classification::orderBy('classification.id','ASC')->get();
          $term_list = Term::orderBy('term.id','DESC')->get();
          $teacher = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
              ->join('person','employee.person_id','=','person.id')
              ->where('employee.employee_no','=', Auth::user()->username)
              ->select('teacher.id','employee.employee_no')->get()->last();
          // Show the page
          return view('teachers_portal/subject.index',compact('classification_list','term_list','teacher'));

    }


    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
       
      $term_id = \Input::get('term_id');

      if($term_id != "" && $term_id != null) {
          $class_list = TEClass::join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
              ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
              ->join('section', 'class.section_id', '=', 'section.id')
              // ->join('program', 'subject_offered.program_id', '=', 'program.id')
              ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
              ->join('term', 'class.term_id', '=', 'term.id')
              ->select(array('class.id','subject.credit_unit', 'subject.code', 'subject.name', 'section.section_name', 'classification_level.level', 'term.term_name'))
              ->join('teacher','class.teacher_id','=','teacher.id')
              ->join('employee','teacher.employee_id','=','employee.id')
              ->where('employee.employee_no', '=', Auth::user()->username)
              ->where('class.term_id', '=', $term_id)
              ->orderBy('class.id', 'DESC');
      }else{
          $class_list = TEClass::join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
              ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
              ->join('section', 'class.section_id', '=', 'section.id')
              // ->join('program', 'subject_offered.program_id', '=', 'program.id')
              ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
              ->join('term', 'class.term_id', '=', 'term.id')
              ->select(array('class.id','subject.credit_unit', 'subject.code', 'subject.name', 'section.section_name', 'classification_level.level', 'term.term_name'))
              ->join('teacher','class.teacher_id','=','teacher.id')
              ->join('employee','teacher.employee_id','=','employee.id')
              ->where('employee.employee_no', '=', Auth::user()->username)
              ->orderBy('class.id', 'DESC');
      }
  
      return Datatables::of($class_list)
              ->editColumn('level','{{{$level." - ".$section_name}}}')
              ->remove_column('id', 'section_name')
              ->make();
    }
    





}
