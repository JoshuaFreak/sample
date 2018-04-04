<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\SchedulerMainController;
use App\Models\Building;
use App\Models\BatchStudent;
use App\Models\Course;
use App\Models\Campus;
use App\Models\Classification;
use App\Models\CourseCapacity;
use App\Models\ClassificationLevel;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Day;
use App\Models\DutyType;
use App\Models\Employee;
use App\Models\EventModel;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Program;
use App\Models\Section;
use App\Models\Person;
use App\Models\Room;
use App\Models\Batch;
use App\Models\Schedule;
use App\Models\Scheduler;
use App\Models\ScheduleType;
use App\Models\SemesterLevel;
use App\Models\StudentCurriculum;
use App\Models\StudentPersonality;
use App\Models\SubjectOffered;
use App\Models\Time;
use App\Models\Teacher;
use App\Models\TeacherClassDuty;
use App\Models\TEClass;
use App\Models\Term;
use App\Models\Level;
use App\Http\Requests\SchedulerRequest;
use App\Http\Requests\SchedulerEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Session;    
use Redirect;    
use DB;    
use Hash;
use Input;
use Excel;

class SchedulerController extends SchedulerMainController {
   

    /*
    return a list of country in json format based on a term
    **/

    public function index()
    { 
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        return view('scheduler.index',compact('gen_role'));
    }

    public function courseStudent()
    { 
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $program_list = Program::orderBy('program.category','ASC')->get();
        return view('scheduler/report/course_student',compact('program_list','gen_role'));
    }

    public function courseStudentData()
    { 
        $program_id = Input::get('id');
        $student_list = BatchStudent::leftJoin('batch','batch_student.batch_id','=','batch.id')
                    ->leftJoin('student_info','student_info.student_id','=','batch_student.student_id')
                    ->where('batch.program_id',$program_id)
                    ->where('batch.date_from','<=',date('Y-m-d'))
                    ->where('batch.date_to','>=',date('Y-m-d'))
                    ->select(['batch_student.student_id','batch_student.student_english_name','student_info.nickname'])
                    ->groupBy('batch_student.student_id')
                    ->orderBy('batch_student.student_english_name');

        return Datatables::of($student_list)
        ->make();

    }


    public function create()
    {
        $room_list = Room::all();
        $duty_type_list = DutyType::all();
        $campus_list = Campus::all();
        $building_list = Building::all();
        $course_capacity_list = CourseCapacity::where('is_active',1)->get();
        $program_list = Program::orderBy('category','ASC')->get();
        $course_list = Course::all();

        $teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                  ->join('person','employee.person_id','=','person.id')
                  ->select('teacher.id','person.first_name','person.middle_name','person.last_name')
                  ->get();
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 2000);
        ini_set('xdebug.max_nesting_level', 300);

        // Show the page
        return view('scheduler.scheduler', compact('course_list','course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list','duty_type_list'));
    }

    public function createSchedule()
    {

        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $is_admin = 0;
        if($gen_role -> name == "Admin" || $gen_role -> name == "Scheduler")
        {
          $is_admin = 1;
        }

        $room_list = Room::all();
        $campus_list = Campus::all();
        $building_list = Building::all();
        $course_capacity_list = CourseCapacity::where('is_active',1)->get();

        $level_list = Level::orderBy('level.id','ASC')->get();
        $student_personality_list = StudentPersonality::orderBy('student_personality.id','ASC')->get();
        $program_list = Program::orderBy('category','ASC')->get();

        $course_list = Course::all();

        $teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                  ->join('person','employee.person_id','=','person.id')
                  ->select('teacher.id','person.first_name','person.middle_name','person.last_name')
                  ->get();

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 2000);
        ini_set('xdebug.max_nesting_level', 300);

        return view('scheduler.create_schedule', compact('is_admin','level_list','student_personality_list','course_list','course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list'));
    }

    public function menToMen()
    {
        $room_list = Room::where('room.course_capacity_id','=','1')->get();
        // Show the page
        return view('scheduler.men_to_men', compact('room_list'));
    }

    public function groupClass()
    {   
          $room_list = Room::all();
          $campus_list = Campus::all();
          $building_list = Building::all();
          $course_capacity_list = CourseCapacity::where('is_active',1)->get();
          $program_list = Program::orderBy('category','ASC')->get();

          $teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                    ->join('person','employee.person_id','=','person.id')
                    ->select('teacher.id','person.first_name','person.middle_name','person.last_name')
                    ->get();

          return view('scheduler.group_class',compact('course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list'));
    }

    public function activityClass()
    {   
          $room_list = Room::all();
          $campus_list = Campus::all();
          $building_list = Building::all();
          $course_capacity_list = CourseCapacity::where('is_active',1)->get();
          $program_list = Program::orderBy('category','ASC')->get();

          $teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                    ->join('person','employee.person_id','=','person.id')
                    ->select('teacher.id','person.first_name','person.middle_name','person.last_name')
                    ->get();

          return view('scheduler.activity_class',compact('course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list'));
    }

    public function getTimeDataJson()
    {

      $data = [];
      $room_list = Room::where('room.course_capacity_id','=',8)->select(['room.id as value','room.room_name as text','room_capacity as capacity'])
                ->get();
      $room_list_activity = Room::where('room.course_capacity_id','=',5)->select(['room.id as value','room.room_name as text','room_capacity as capacity'])
                ->orderBy('room.id','DESC')->get();
      $room_list_1to1 = Room::where('room.course_capacity_id',1)->select(['room.id as value','room.room_name as text'])->get();
      $time_list = Time::where('time.is_active',1)->get();
      // $employee_list = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
      //           ->leftJoin('teacher_skill','employee.id','=','teacher_skill.employee_id')
      //           ->leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
      //           ->leftJoin('person','employee.person_id','=','person.id')
      //           ->leftJoin('room','employee.room_id','=','room.id')
      //           ->where('teacher_skill.is_default',1)
      //           ->where('person.is_active',1)
      //           ->select(['teacher.id','person.last_name','person.first_name','person.nickname','room.room_name','program_skill.color as program_color'])
      //           ->orderBy('room.id','ASC')
      //           ->groupBy('employee.id')
      //           ->get();

      $floating_employee_list = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                ->leftJoin('teacher_skill','employee.id','=','teacher_skill.employee_id')
                ->leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                ->leftJoin('person','employee.person_id','=','person.id')
                ->leftJoin('gen_user','person.id','=','gen_user.person_id')
                ->leftJoin('gen_user_role','gen_user.id','=','gen_user_role.gen_user_id')
                // ->where('teacher_skill.is_default',1)
                ->where('person.is_active',1)
                ->where('employee.room_id',0)
                ->where('gen_user_role.gen_role_id',1)
                ->select(['teacher.id','person.last_name','person.first_name','person.nickname','program_skill.color as program_color'])
                ->groupBy('employee.id')
                ->get();

      $room_1_list = Room::where('room.room_capacity',1)
          ->orderBy('room.id','ASC')
          ->get();

      $employee_room = [];
      $employee_count = 0;

      foreach($floating_employee_list as $floating_employee) {
          $employee_room[$employee_count] = ['id' => $floating_employee -> id,'last_name' => $floating_employee -> last_name,'first_name' => $floating_employee -> first_name,'room_name' => null,'nickname' => $floating_employee -> nickname, 'program_color' => $floating_employee -> program_color];
          $employee_count++;
      }


      foreach($room_1_list as $room) {

          $employee_list = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                ->leftJoin('teacher_skill','employee.id','=','teacher_skill.employee_id')
                ->leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                ->leftJoin('person','employee.person_id','=','person.id')
                ->where('teacher_skill.is_default',1)
                ->where('person.is_active',1)
                ->where('employee.room_id',$room -> id)
                ->select(['teacher.id','person.last_name','person.first_name','person.nickname','program_skill.color as program_color'])
                ->groupBy('employee.id')
                ->get();

          $employee_list_count = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                ->leftJoin('teacher_skill','employee.id','=','teacher_skill.employee_id')
                ->leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                ->leftJoin('person','employee.person_id','=','person.id')
                ->where('teacher_skill.is_default',1)
                ->where('person.is_active',1)
                ->where('employee.room_id',$room -> id)
                ->count();

          if($employee_list_count != 0)
          {
              foreach($employee_list as $employee) {
                  $employee_room[$employee_count] = ['id' => $employee -> id,'last_name' => $employee -> last_name,'first_name' => $employee -> first_name,'room_name' => $room -> room_name ,'nickname' => $employee -> nickname, 'program_color' => $employee -> program_color];
                  $employee_count++;
              }
          }
          else
          {
                $employee_room[$employee_count] = ['id' => 'vacant_'.$employee_count,'last_name' => 'Room','first_name' => 'Vacant','room_name' => $room -> room_name ,'nickname' => 'Vacant', 'program_color' => ''];
                $employee_count++;
          }
      }
      
      $data[0] = $time_list ;
      $data[1] = $employee_room;
      $data[2] = $room_list;
      $data[3] = $room_list_1to1;
      $data[4] = $room_list_activity;

      return response()->json($data);
    }


    public function getScheduleDataJson()
    {
        $date = \Input::get('date');
        $schedule = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
          ->leftJoin('batch','schedule.batch_id','=','batch.id')
          ->leftJoin('program','batch.program_id','=','program.id')
          ->leftJoin('course','batch.course_id','=','course.id')
          ->leftJoin('course_capacity','batch.course_capacity_id','=','course_capacity.id')
          ->where('schedule.date',$date)
          ->groupBy('schedule.batch_id')
          ->select(['schedule.batch_id','schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','schedule.teacher_id','batch.program_id','batch.course_id','program.program_name as class','batch.program_id','schedule.room_id','program.program_color','batch.course_capacity_id','course_capacity.capacity as course_capacity_count','course_capacity.capacity_name','schedule.room_id','batch_student.student_english_name'])->get();

        return response()->json($schedule);
    }

    public function getScheduleByGroupDataJson()
    {
        $date = \Input::get('date');
        $schedule = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
          ->leftJoin('student_info','batch_student.student_id','=','student_info.student_id')
          ->leftJoin('batch','schedule.batch_id','=','batch.id')
          ->leftJoin('level','batch.level_id','=','level.id')
          ->leftJoin('student_personality','batch.student_personality_id','=','student_personality.id')
          ->leftJoin('program','batch.program_id','=','program.id')
          ->leftJoin('course','batch.course_id','=','course.id')
          ->leftJoin('course_capacity','batch.course_capacity_id','=','course_capacity.id')
          ->leftJoin('room','schedule.room_id','=','room.id')
          ->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
          ->leftJoin('employee','teacher.employee_id','=','employee.id')
          // ->where('schedule.date',$date)
          ->where('batch.date_from','<=',$date)
          ->where('batch.date_to','>=',$date)
          ->groupBy('schedule.teacher_id','time_in_id','time_out_id')
          ->select(['schedule.batch_id','schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','schedule.teacher_id','batch.program_id','course.course_name','batch.course_id','program.program_name as class','batch.program_id','schedule.room_id','program.program_color','batch.course_capacity_id','course_capacity.capacity as course_capacity_count','course_capacity.capacity_name','course_capacity.course_capacity_code','schedule.room_id','student_info.sename as student_english_name','student_info.nickname','level.level_code','student_personality.student_personality_name','room.room_name','employee.room_id as teacher_room'])->get();

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 2000);
        ini_set('xdebug.max_nesting_level', 300);

        return response()->json($schedule);
    }

    public function getScheduleDataJsonStudent()
    {
        $date = \Input::get('date');
        $schedule = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
          ->join('batch','schedule.batch_id','=','batch.id')
          ->join('program','batch.program_id','=','program.id')
          ->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
          ->leftJoin('employee','teacher.employee_id','=','employee.id')
          ->leftJoin('person','employee.person_id','=','person.id')
          ->leftJoin('room','schedule.room_id','=','room.id')
          ->join('course','batch.course_id','=','course.id')
          ->join('course_capacity','batch.course_capacity_id','=','course_capacity.id')
          // ->where('schedule.date',$date)
          ->where('batch.date_from','<=',$date)
          ->where('batch.date_to','>=',$date)
          // ->groupBy('schedule.batch_id','schedule.time_in_id')
          ->select(['schedule.batch_id','schedule.id','schedule.teacher_id as teacher','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','batch_student.student_id as teacher_id','person.nickname','batch.program_id','batch.course_id','program.program_name as class','batch.program_id','schedule.room_id','program.program_color','batch.course_capacity_id','course_capacity.capacity_name','course_capacity.course_capacity_code as capacity_code','course.course_name','schedule.room_id','room.room_name'])->get();

          ini_set('memory_limit', '2048M');
          ini_set('max_execution_time', 2000);
          ini_set('xdebug.max_nesting_level', 300);

        return response()->json($schedule);
    }

    public function getStudentScheduleByCourse()
    {
        $program_id = \Input::get('program_id');
        $date_from = \Input::get('date_from');
        $date_to = \Input::get('date_to');
        $student_id = \Input::get('student_id');

        $schedule = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
          ->leftJoin('batch','schedule.batch_id','=','batch.id')
          ->leftJoin('program','batch.program_id','=','program.id')
          ->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
          ->leftJoin('employee','teacher.employee_id','=','employee.id')
          ->leftJoin('person','employee.person_id','=','person.id')
          ->leftJoin('room','schedule.room_id','=','room.id')
          ->leftJoin('course','batch.course_id','=','course.id')
          ->leftJoin('course_capacity','batch.course_capacity_id','=','course_capacity.id')
          ->where('batch.date_from',$date_from)
          ->where('batch.date_to',$date_to)
          ->where('batch.program_id',$program_id)
          ->where('batch_student.student_id',$student_id)
          ->select(['schedule.batch_id','batch.level_id','batch.student_personality_id','schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','batch_student.student_id as teacher_id','schedule.teacher_id as teacher','person.nickname','person.first_name','person.last_name','batch.program_id','batch.course_id','program.program_name as class','schedule.class_id','batch.program_id','schedule.room_id','program.program_color','batch.course_capacity_id','course_capacity.capacity_name','schedule.room_id','room.room_name','batch.date_from','batch.date_to'])->get();

        $schedule_data = [];
        $available_teacher_arr_period = [];

        foreach ($schedule as $item) 
        {
            $data = $item -> course_capacity_id;
            if($data != 1 && $data != 5 && $data != 6)
            {   
                $data = 8;
            }

            $room_list = Room::where('room.course_capacity_id', '=', $data)->select([ 'room.id as value','room.room_name as text'])->get();
            $schedule_data[$item -> class_id] = $room_list;
            $available_teacher_arr = [];

            $available_teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                    ->join('person','employee.person_id','=','person.id')
                    ->leftJoin('gen_user', 'gen_user.person_id', '=', 'person.id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->where('teacher.id','!=',0)
                    ->where('gen_user_role.gen_role_id',1)
                    ->where('person.last_name',"!=", "")
                    ->select(['teacher.id','person.last_name','person.first_name','person.nickname'])
                    ->groupBy('teacher.employee_id')
                    ->get();

              if($data == 8)
              {
                  foreach ($available_teacher_list as $available_teacher) 
                  {
                      $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                            ->where('schedule.teacher_id',$available_teacher -> id)
                            ->where('schedule.room_id',$item -> room_id)
                            ->where('schedule.class_id',$item -> class_id)
                            ->where('batch.date_from','<=',date('Y-m-d'))
                            ->where('batch.date_to','>=',date('Y-m-d'))
                            ->count();

                      $room_capacity = Room::find($item -> room_id);
                      // if($available_schedule_count == 0)
                      // {
                      if($room_capacity)
                      {
                          if($room_capacity -> room_capacity >= $available_schedule_count)
                          {
                            array_push($available_teacher_arr, array('teacher_id' => $available_teacher -> id,'teacher_name' => $available_teacher -> first_name." ".$available_teacher -> last_name." - ".$available_teacher -> nickname));
                          }
                      }
                      // }
                  }
              }
              elseif($data == 1)
              {
                  foreach ($available_teacher_list as $available_teacher) 
                  {
                      $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                            ->where('schedule.teacher_id',$available_teacher -> id)
                            ->where('schedule.class_id',$item -> class_id)
                            ->where('batch.date_from','<=',date('Y-m-d'))
                            ->where('batch.date_to','>=',date('Y-m-d'))
                            ->count();

                      if($available_schedule_count == 0)
                      {
                          array_push($available_teacher_arr, array('teacher_id' => $available_teacher -> id,'teacher_name' => $available_teacher -> first_name." ".$available_teacher -> last_name." - ".$available_teacher -> nickname));
                      }
                  }
              }
              else
              {
              }

              $available_teacher_arr_period[$item -> class_id] = $available_teacher_arr;
        }

        return response()->json(array('data' => $schedule,'schedule' => $schedule_data,'available_teacher_arr_period' => $available_teacher_arr_period));
    }

    public function getStudentScheduleToday()
    {
        $date = \Input::get('date');
        $student_id = \Input::get('student_id');

        $schedule = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
          ->join('batch','schedule.batch_id','=','batch.id')
          ->join('program','batch.program_id','=','program.id')
          ->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
          ->leftJoin('employee','teacher.employee_id','=','employee.id')
          ->leftJoin('person','employee.person_id','=','person.id')
          ->leftJoin('room','schedule.room_id','=','room.id')
          ->join('course','batch.course_id','=','course.id')
          ->join('course_capacity','batch.course_capacity_id','=','course_capacity.id')
          // ->where('schedule.date',$date)
          ->where('batch.date_from','<=',$date)
          ->where('batch.date_to','>=',$date)
          ->where('batch_student.student_id',$student_id)
          // ->groupBy('schedule.batch_id')
          ->select(['schedule.batch_id','batch.level_id','batch.student_personality_id','schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','batch_student.student_id as teacher_id','schedule.teacher_id as teacher','person.nickname','person.first_name','person.last_name','batch.program_id','batch.course_id','program.program_name as class','schedule.class_id','batch.program_id','schedule.room_id','program.program_color','batch.course_capacity_id','course_capacity.capacity_name','schedule.room_id','room.room_name','batch.date_from','batch.date_to'])->get();

          
          $schedule_data = [];    
          $available_teacher_arr_period = [];
          $schedule_list = [];
          $main_program_name = "";

          foreach ($schedule as $item) 
          {
              $data = $item -> course_capacity_id;
              if($data != 1 && $data != 5 && $data != 6)
              {   
                  $data = 8;
              }

              // if($data == 7)
              // {
              //     $data = 5;
              // }

              $room_list = Room::where('room.course_capacity_id', '=', $data)->select([ 'room.id as value','room.room_name as text'])->get();

              // $sched = [];
              // foreach ($room_list as $room) {
              //     $sched = ['value' => $room ->value,'text' => $room ->text];
              // }
              $schedule_data[$item -> class_id] = $room_list;     

              $schedule_list = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->join('batch','schedule.batch_id','=','batch.id')
                ->join('program','batch.program_id','=','program.id')
                ->where('batch_student.student_id',$student_id)
                ->where('batch.date_to','!=',$item -> date_to)
                ->where('batch.date_from','!=',$item -> date_from)
                ->select(['batch.program_id','program.program_name','batch.date_from','batch.date_to'])
                ->groupBy('batch.date_from')
                ->orderBy('batch.date_from','DESC')
                ->get();

              $main_program_name =  BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->join('batch','schedule.batch_id','=','batch.id')
                ->join('program','batch.program_id','=','program.id')
                ->where('batch_student.student_id',$student_id)
                ->where('batch.program_id',$item -> program_id)
                ->where('batch.date_to',$item -> date_to)
                ->where('batch.date_from',$item -> date_from)
                ->select(['batch.program_id','program.program_name','batch.date_from','batch.date_to'])
                ->groupBy('batch.date_from')
                ->get()->last();


              $available_teacher_arr = [];

              $available_teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                      ->join('person','employee.person_id','=','person.id')
                      ->leftJoin('gen_user', 'gen_user.person_id', '=', 'person.id')
                      ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                      ->where('teacher.id','!=',0)
                      ->where('gen_user_role.gen_role_id',1)
                      ->where('person.last_name',"!=", "")
                      ->select(['teacher.id','person.last_name','person.first_name','person.nickname'])
                      ->groupBy('teacher.employee_id')
                      ->get();

              // foreach ($available_teacher_list as $available_teacher) 
              // {
              //     $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
              //           ->where('schedule.teacher_id',$available_teacher -> id)
              //           ->where('schedule.class_id',$item -> class_id)
              //           ->where('batch.date_from','<=',$date)
              //           ->where('batch.date_to','>=',$date)
              //           ->count();
              //     if($available_schedule_count == 0)
              //     { 
              //         array_push($available_teacher_arr, array('teacher_id' => $available_teacher -> id,'teacher_name' => $available_teacher -> first_name." ".$available_teacher -> last_name." - ".$available_teacher -> nickname));
              //     }
              // }

              
              if($data == 8)
              {
                  foreach ($available_teacher_list as $available_teacher) 
                  {
                      $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                            ->where('schedule.teacher_id',$available_teacher -> id)
                            ->where('schedule.room_id',$item -> room_id)
                            ->where('schedule.class_id',$item -> class_id)
                            ->where('batch.date_from','<=',date('Y-m-d'))
                            ->where('batch.date_to','>=',date('Y-m-d'))
                            ->count();

                      $room_capacity = Room::find($item -> room_id);
                      // if($available_schedule_count == 0)
                      // {
                      if($room_capacity)
                      {
                          if($room_capacity -> room_capacity >= $available_schedule_count)
                          {
                            array_push($available_teacher_arr, array('teacher_id' => $available_teacher -> id,'teacher_name' => $available_teacher -> first_name." ".$available_teacher -> last_name." - ".$available_teacher -> nickname));
                          }
                      }
                      // }
                  }
              }
              elseif($data == 1)
              {
                  foreach ($available_teacher_list as $available_teacher) 
                  {
                      $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                            ->where('schedule.teacher_id',$available_teacher -> id)
                            ->where('schedule.class_id',$item -> class_id)
                            ->where('batch.date_from','<=',date('Y-m-d'))
                            ->where('batch.date_to','>=',date('Y-m-d'))
                            ->count();

                      if($available_schedule_count == 0)
                      {
                          array_push($available_teacher_arr, array('teacher_id' => $available_teacher -> id,'teacher_name' => $available_teacher -> first_name." ".$available_teacher -> last_name." - ".$available_teacher -> nickname));
                      }
                  }
              }
              else
              {
              }

              $available_teacher_arr_period[$item -> class_id] = $available_teacher_arr;

          }
          if($main_program_name)
          {
            $main_program_name = $main_program_name;
          }
          else
          {
            $main_program_name = "";
          }

          ini_set('memory_limit', '2048M');
          ini_set('max_execution_time', 2000);
          ini_set('xdebug.max_nesting_level', 300);

        return response()->json(array('data' => $schedule,'schedule' => $schedule_data,'available_teacher_arr_period' => $available_teacher_arr_period,'schedule_list' => $schedule_list, 'main_program_name' => $main_program_name));
    }

    public function getBatchStudentDataJson()
    {
        $batch_id = \Input::get('batch_id');
        $batch_student = BatchStudent::where('batch_student.batch_id',$batch_id)
                          ->select(['batch_student.student_id','batch_student.student_english_name'])
                          ->get();

        return response()->json($batch_student);
    }

    public function getScheduleSelfDataJson()
    {
        $date = \Input::get('date');
        $schedule = BatchStudent::leftJoin('schedule','schedule.batch_id','=','batch_student.batch_id')
          ->leftJoin('batch','schedule.batch_id','=','batch.id')
          ->leftJoin('student_info','batch_student.student_id','=','student_info.student_id')
          ->leftJoin('level','batch.level_id','=','level.id')
          ->leftJoin('student_personality','batch.student_personality_id','=','student_personality.id')
          ->leftJoin('program','batch.program_id','=','program.id')
          ->leftJoin('course','batch.course_id','=','course.id')
          ->leftJoin('course_capacity','batch.course_capacity_id','=','course_capacity.id')
          ->leftJoin('room','schedule.room_id','=','room.id')
          ->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
          ->leftJoin('teacher_skill','teacher.employee_id','=','teacher_skill.employee_id')
          ->leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
          ->leftJoin('employee','teacher.employee_id','=','employee.id')
          ->leftJoin('person','employee.person_id','=','person.id')
          // ->leftJoin('program as teacher_program','employee.program_id','=','teacher_program.id')
          // ->join('batch_student','schedule.batch_id','=','batch_student.batch_id')
          // ->join('student','batch_student.student_id','=','student.id')
          // ->join('person','student.person_id','=','person.id')
          // ->groupBy('batch_student.student_id')
          // ->where('schedule.date',$date)
          ->where('batch.date_from','<=',$date)
          ->where('batch.date_to','>=',$date)
          ->where('room.course_capacity_id','!=',1)
          ->where('room.course_capacity_id','!=',8)
          ->where('room.course_capacity_id','!=',6)
          // ->where('teacher_skill.is_default',1)
          ->groupBy('schedule.id')
          ->groupBy('batch_student.student_id','schedule.class_id')
          // ->select(['person.student_english_name','schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','schedule.teacher_id','program.program_name as class','program.program_color','course_capacity.capacity_name','schedule.room_id'])->get();
          ->select(['schedule.id','student_info.sename as student_english_name','student_info.nickname as student_nickname','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','schedule.teacher_id','program.program_name as class','course.course_name','program.program_color','course_capacity.capacity_name','schedule.room_id','person.nickname','program_skill.color as teacher_program_color','level.level_code','student_personality.student_personality_name'])
          ->get();

          ini_set('memory_limit', '2048M');
          ini_set('max_execution_time', 2000);
          ini_set('xdebug.max_nesting_level', 300);

        return response()->json($schedule);
    }

    public function getScheduleGroupDataJson()
    {
        $date = \Input::get('date');
        $schedule = BatchStudent::leftJoin('schedule','schedule.batch_id','=','batch_student.batch_id')
          ->leftJoin('batch','schedule.batch_id','=','batch.id')
          ->leftJoin('student_info','batch_student.student_id','=','student_info.student_id')
          ->leftJoin('level','batch.level_id','=','level.id')
          ->leftJoin('student_personality','batch.student_personality_id','=','student_personality.id')
          ->leftJoin('program','batch.program_id','=','program.id')
          ->leftJoin('course','batch.course_id','=','course.id')
          ->leftJoin('course_capacity','batch.course_capacity_id','=','course_capacity.id')
          ->leftJoin('room','schedule.room_id','=','room.id')
          ->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
          ->leftJoin('teacher_skill','teacher.employee_id','=','teacher_skill.employee_id')
          ->leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
          ->leftJoin('employee','teacher.employee_id','=','employee.id')
          ->leftJoin('person','employee.person_id','=','person.id')
          // ->leftJoin('room_book','room.id','=','room_book.room_id')
          ->leftJoin('room_book', function($join)
          {
              $join->on('schedule.room_id', '=', 'room_book.room_id');
              $join->on('schedule.class_id', '=', 'room_book.class_id');
          })
          ->leftJoin('level as level1','room_book.level_to_id','=','level1.id')
          // ->leftJoin('program as teacher_program','employee.program_id','=','teacher_program.id')
          // ->join('batch_student','schedule.batch_id','=','batch_student.batch_id')
          // ->join('student','batch_student.student_id','=','student.id')
          // ->join('person','student.person_id','=','person.id')
          // ->groupBy('batch_student.student_id')
          // ->where('schedule.date',$date)
          ->where('batch.date_from','<=',$date)
          ->where('batch.date_to','>=',$date)
          ->where('room.course_capacity_id','!=',1)
          ->where('room.course_capacity_id','!=',5)
          ->where('room.course_capacity_id','!=',6)
          // ->where('teacher_skill.is_default',1)
          ->groupBy('schedule.id')
          ->groupBy('batch_student.student_id','schedule.class_id')
          // ->select(['person.student_english_name','schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','schedule.teacher_id','program.program_name as class','program.program_color','course_capacity.capacity_name','schedule.room_id'])->get();
          ->select(['schedule.id','student_info.sename as student_english_name','student_info.nickname as student_nickname','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','schedule.teacher_id','program.id as program_id','program.program_name as class','batch.course_id','course.course_name','program.program_color','course_capacity.capacity_name','schedule.room_id','person.nickname','program_skill.color as teacher_program_color','level.level_code','student_personality.student_personality_name','room_book.book_title','program.program_category_id','level1.level_value'])
          ->get();

          ini_set('memory_limit', '2048M');
          ini_set('max_execution_time', 2000);
          ini_set('xdebug.max_nesting_level', 300);

        return response()->json($schedule);
    }

    public function getScheduleDataJsonByTeacher()
    {
        $date = \Input::get('date');
        $teacher_id = \Input::get('teacher_id');
        $schedule = Schedule::join('batch','schedule.batch_id','=','batch.id')
          ->join('program','batch.program_id','=','program.id')
          ->join('course','batch.course_id','=','course.id')
          ->where('schedule.date',$date)
          ->where('schedule.teacher_id',$teacher_id)
          ->select(['schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','schedule.teacher_id','program.program_name as class'])->get();

          ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 2000);
        ini_set('xdebug.max_nesting_level', 300);

        return response()->json($schedule);
    }

    public function postCreate()
    {
        $time_id = \Input::get('time_id');
        $time_out = \Input::get('time_out');
        $teacher_id = \Input::get('teacher_id');
        $student_name_arr = \Input::get('student_name_arr');
        $student_eng_name_arr = \Input::get('student_eng_name_arr');
        $date_start = \Input::get('start_arr');
        $date_end = \Input::get('end_arr');
        $course_capacity_id = \Input::get('course_capacity_id');
        $program_id = \Input::get('program_id');
        $course_id = \Input::get('course_id');
        $room_id = \Input::get('room_id');
        // $date_start = \Input::get('date_start');
        // $date_end = \Input::get('date_end');

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 2000);
        ini_set('xdebug.max_nesting_level', 300);

        $max = sizeof($student_name_arr);

        json_decode(serialize($student_name_arr));
        json_decode(serialize($student_eng_name_arr));
        json_decode(serialize($date_start));
        json_decode(serialize($date_end));

        $batch = new Batch();
        $batch -> program_id = $program_id;
        $batch -> course_id = $course_id;
        $batch -> course_capacity_id = $course_capacity_id;
        // $batch -> date_from = $date_start;
        // $batch -> date_to = $date_end;
        $batch -> save();

        for($i = 0; $i < $max; $i++)
        {
            $batch_student = new BatchStudent();
            $batch_student -> batch_id = $batch -> id;
            $batch_student -> student_id = $student_name_arr[$i];
            $batch_student -> student_english_name = $student_eng_name_arr[$i];
            $batch_student -> save();

            // $startdate = strtotime($date_start[$i]);
            // $enddate = strtotime($date_end[$i]);
            // $loopdate = $startdate;

            // while($loopdate <= $enddate) {

            //   $loopdate = date('Y-m-d',$loopdate);

              $schedule = new Schedule();
              $schedule -> batch_id = $batch -> id;
              // $schedule -> date = $loopdate;
              $schedule -> room_id = $room_id;
              $schedule -> teacher_id = $teacher_id;
              $schedule -> time_in_id = $time_id;
              $schedule -> time_out_id = $time_out;
              $schedule -> save();

            //   $loopdate = strtotime($loopdate . ' +1 day');

            // }
        }


    }

    public function postCreateStudent()
    {
        $time_id = \Input::get('time_id');
        $time_out = \Input::get('time_out');
        $student_id = \Input::get('student_id');
        $student_nickname = \Input::get('student_nickname');
        $teacher_name_arr = \Input::get('teacher_name_arr');
        $student_eng_name = \Input::get('student_eng_name');
        $batch_id_arr = \Input::get('batch_id_arr');
        $db_id_arr = \Input::get('db_id_arr');
        $date_start = \Input::get('start_arr');
        $date_end = \Input::get('end_arr');
        $modal_time_arr = \Input::get('modal_time_arr');
        $modal_time_next_arr = \Input::get('modal_time_next_arr');
        $modal_course_arr = \Input::get('modal_course_arr');
        $modal_room_arr = \Input::get('modal_room_arr');
        $class_period_arr = \Input::get('class_period_arr');
        $modal_course_capacity_arr = \Input::get('modal_course_capacity_arr');
        // $course_capacity_id = \Input::get('course_capacity_id');
        $program_id = \Input::get('program_id');
        $student_personality_id = \Input::get('student_personality_id');
        $course_id = \Input::get('course_id');
        $room_id = \Input::get('room_id');
        // $date_start = \Input::get('date_start');
        // $date_end = \Input::get('date_end');

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 2000);
        ini_set('xdebug.max_nesting_level', 300);
       

        $max = sizeof($class_period_arr);

        json_decode(serialize($teacher_name_arr));
        json_decode(serialize($db_id_arr));
        json_decode(serialize($batch_id_arr));
        json_decode(serialize($date_start));
        json_decode(serialize($date_end));
        json_decode(serialize($modal_room_arr));
        json_decode(serialize($modal_course_arr));
        json_decode(serialize($modal_course_capacity_arr));
        json_decode(serialize($modal_time_arr));
        json_decode(serialize($modal_time_next_arr));
        json_decode(serialize($class_period_arr));


        for($i = 0; $i < $max; $i++)
        { 
            
            if($db_id_arr[$i] != 0)
            {
                  $batch = Batch::find($batch_id_arr[$i]);
                  $batch -> program_id = $program_id;
                  $batch -> student_personality_id = $student_personality_id;
                  $batch -> course_id = $modal_course_arr[$i];
                  $batch -> course_capacity_id = $modal_course_capacity_arr[$i];
                  $batch -> date_from = $date_start[0];
                  $batch -> date_to = $date_end[0];
                  $batch -> save();

                  $schedule = Schedule::where('schedule.batch_id',$batch -> id)->get()->last();

                  $update_schedule = Schedule::find($schedule -> id);
                  $update_schedule -> teacher_id = $teacher_name_arr[$i];
                  $update_schedule -> room_id = $modal_room_arr[$i];
                  $update_schedule -> save();
            }
            else if($modal_course_capacity_arr[$i] == 5 && $modal_room_arr[$i] != "" && $modal_room_arr[$i] != 0 && $modal_room_arr[$i] != null && $modal_room_arr[$i] != "undefined")
            {
                  // $date_arr = [];
                  // $pass = 1;

                  // if ($modal_course_capacity_arr[$i] != 1 && $modal_course_capacity_arr[$i] != 5 && $modal_course_capacity_arr[$i] != 6)
                  // {
                  //     $is_exist = 0;

                  //     $startdate = strtotime($date_start[$i]);
                  //     $enddate = strtotime($date_end[$i]);
                  //     $loopdate = $startdate;
                  //     $loop_count = 0;

                  //     while($loopdate <= $enddate) {

                  //       $loopdate = date('Y-m-d',$loopdate);

                  //       $schedule_find = BatchStudent::join('schedule','schedule.batch_id','=','batch_student.batch_id')
                  //               ->join('batch','batch_student.batch_id','=','batch.id')
                  //               ->where('batch_student.student_id',$student_id)
                  //               ->where('schedule.date',$loopdate)
                  //               ->where('schedule.room_id',$modal_room_arr[$i])
                  //               ->where('schedule.teacher_id',$teacher_name_arr[$i])
                  //               ->count();

                  //       if($schedule_find == 0)
                  //       {
                  //           $date_arr[$loop_count] = ["date" => $loopdate];
                  //           $loop_count++;
                  //       }

                  //       $loopdate = strtotime($loopdate . ' +1 day');
                  //     }


                  //     if($modal_course_capacity_arr[$i] != 5 && $modal_course_capacity_arr[$i] != 6)
                  //     {
                  //         $batch_count = Batch::where('batch.program_id',$program_id)
                  //                 ->where('batch.course_id',$modal_course_arr[$i])
                  //                 ->where('batch.date_from',$date_start[0])
                  //                 ->where('batch.date_to',$date_end[0])
                  //                 ->count();

                  //         if($batch_count > 0)
                  //         {

                  //             $batch_find = Batch::where('batch.program_id',$program_id)
                  //                     ->where('batch.course_id',$modal_course_arr[$i])
                  //                     ->where('batch.date_from',$date_start[0])
                  //                     ->where('batch.date_to',$date_end[0])
                  //                     ->select(['batch.id'])->get()->last();

                  //             $is_exist = 1;
                  //             $batch_id = $batch_find -> id;
                  //         }
                  //     }
                  //     else
                  //     {
                  //         $batch_count = Schedule::join('batch','schedule.batch_id','=','batch.id')
                  //           ->where('batch.program_id',$program_id)
                  //           ->where('batch.course_id',$modal_course_arr[$i])
                  //           ->where('batch.date_from',$date_start[0])
                  //           ->where('batch.date_to',$date_end[0])
                  //           ->count();

                  //         if($batch_count > 0)
                  //         {
                  //           $pass = 0;
                  //         }
                  //     }
                  // }
                  // else
                  // {
                  //     $batch_count = Batch::where('batch.program_id',$program_id)
                  //             ->where('batch.course_id',$modal_course_arr[$i])
                  //             ->where('batch.date_from',$date_start[0])
                  //             ->where('batch.date_to',$date_end[0])
                  //             ->count();

                  //     if($batch_count > 0)
                  //     {
                  //       $pass = 0;
                  //     }
                  // }

                  // $startdate = strtotime($date_start[$i]);
                  // $enddate = strtotime($date_end[$i]);
                  // $loopdate = $startdate;

                  // while($loopdate <= $enddate) {

                  //   $loopdate = date('Y-m-d',$loopdate);

                  //   $schedule_find = Schedule::where('schedule.date',$loopdate)
                  //           ->where('schedule.date',$loopdate)
                  //           ->where('schedule.date',$loopdate)
                  //           ->count();

                  //   $loopdate = strtotime($loopdate . ' +1 day');

                  $batch_check = BatchStudent::join('batch','batch_student.batch_id','=','batch.id')
                          ->join('schedule','batch_student.batch_id','=','schedule.batch_id')
                          ->where('batch_student.student_id',$student_id)
                          ->where('schedule.class_id',$class_period_arr[$i])
                          ->whereBetween('batch.date_from',[$date_start[0],$date_end[0]])
                          ->count();

                  if($batch_check == 0)
                  {
                  // }
                  // if($pass == 1)
                  // {
                  //     if($is_exist == 0)
                  //     {
                        $batch = new Batch();
                        $batch -> program_id = $program_id;
                        $batch -> student_personality_id = $student_personality_id;
                        $batch -> course_id = $modal_course_arr[$i];
                        $batch -> course_capacity_id = $modal_course_capacity_arr[$i];
                        $batch -> date_from = $date_start[0];
                        $batch -> date_to = $date_end[0];
                        $batch -> save();

                        $batch_id = $batch -> id;
                      // }


                      $batch_student = new BatchStudent();
                      $batch_student -> batch_id = $batch_id;
                      $batch_student -> student_id = $student_id;
                      $batch_student -> student_english_name = $student_eng_name;
                      $batch_student -> nickname = $student_nickname;
                      $batch_student -> save();

                      // $startdate = strtotime($date_start[0]);
                      // $enddate = strtotime($date_end[0]);
                      // $loopdate = $startdate;

                      // $schedule_arr = [];
                      // $schedule_arr_count = 0;

                      // while($loopdate <= $enddate) {

                      //   $loopdate = date('Y-m-d',$loopdate);

                      //   $schedule_find = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                      //           ->join('batch','schedule.batch_id','=','batch.id')
                      //           ->where('schedule.time_in_id',$modal_time_arr[$i])
                      //           ->where('schedule.time_out_id',$modal_time_next_arr[$i])
                      //           ->where('batch_student.student_id',$student_id)
                      //           ->where('schedule.date',$loopdate)
                      //           ->count();

                      //   if($schedule_find == 0 && $loopdate != "1970-01-01")
                      //   {
                          $schedule = new Schedule();
                          $schedule -> batch_id = $batch_id;
                          // $schedule -> date = $loopdate;
                          $schedule -> room_id = $modal_room_arr[$i];
                          $schedule -> teacher_id = $teacher_name_arr[$i];
                          $schedule -> time_in_id = $modal_time_arr[$i];
                          $schedule -> time_out_id = $modal_time_next_arr[$i];
                          $schedule -> class_id = $class_period_arr[$i];
                          $schedule -> save();

                      //     $schedule_arr[$schedule_arr_count] = array('batch_id'=>$batch_id, 'date'=> $loopdate, 'room_id'=> $modal_room_arr[$i], 'teacher_id'=> $teacher_name_arr[$i], 'time_in_id'=> $modal_time_arr[$i], 'time_out_id'=> $modal_time_next_arr[$i], 'class_id'=> $class_period_arr[$i]);

                      //     $schedule_arr_count++;
                      //   }

                      //   $loopdate = strtotime($loopdate . ' +1 day');

                      // }

                      // DB::table('schedule')->insert($schedule_arr);
                  // }
                    }
            }
            else if($modal_course_capacity_arr[$i] == 6)
            {
                  $batch_check = BatchStudent::join('batch','batch_student.batch_id','=','batch.id')
                          ->join('schedule','batch_student.batch_id','=','schedule.batch_id')
                          ->where('batch_student.student_id',$student_id)
                          ->where('schedule.class_id',$class_period_arr[$i])
                          ->whereBetween('batch.date_from',[$date_start[0],$date_end[0]])
                          ->count();

                  if($batch_check == 0)
                  {
                      $batch = new Batch();
                      $batch -> program_id = $program_id;
                      $batch -> student_personality_id = $student_personality_id;
                      $batch -> course_id = $modal_course_arr[$i];
                      $batch -> course_capacity_id = $modal_course_capacity_arr[$i];
                      $batch -> date_from = $date_start[0];
                      $batch -> date_to = $date_end[0];
                      $batch -> save();

                      $batch_id = $batch -> id;

                      $batch_student = new BatchStudent();
                      $batch_student -> batch_id = $batch_id;
                      $batch_student -> student_id = $student_id;
                      $batch_student -> student_english_name = $student_eng_name;
                      $batch_student -> nickname = $student_nickname;
                      $batch_student -> save();

                      // $startdate = strtotime($date_start[0]);
                      // $enddate = strtotime($date_end[0]);
                      // $loopdate = $startdate;

                      // $schedule_arr = [];
                      // $schedule_arr_count = 0;
                      // while($loopdate <= $enddate) {

                      //   $loopdate = date('Y-m-d',$loopdate);

                      //   $schedule_find = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                      //           ->join('batch','schedule.batch_id','=','batch.id')
                      //           ->where('schedule.time_in_id',$modal_time_arr[$i])
                      //           ->where('schedule.time_out_id',$modal_time_next_arr[$i])
                      //           ->where('batch_student.student_id',$student_id)
                      //           ->where('schedule.date',$loopdate)
                      //           ->count();

                      //   if($schedule_find == 0 && $loopdate != "1970-01-01")
                      //   {
                          $schedule = new Schedule();
                          $schedule -> batch_id = $batch_id;
                          // $schedule -> date = $loopdate;
                          $schedule -> room_id = $modal_room_arr[$i];
                          $schedule -> teacher_id = $teacher_name_arr[$i];
                          $schedule -> time_in_id = $modal_time_arr[$i];
                          $schedule -> time_out_id = $modal_time_next_arr[$i];
                          $schedule -> class_id = $class_period_arr[$i];
                          $schedule -> save();
                      //     $schedule_arr[$schedule_arr_count] = array('batch_id'=>$batch_id, 'date'=> $loopdate, 'room_id'=> $modal_room_arr[$i], 'teacher_id'=> $teacher_name_arr[$i], 'time_in_id'=> $modal_time_arr[$i], 'time_out_id'=> $modal_time_next_arr[$i], 'class_id'=> $class_period_arr[$i]);

                      //     $schedule_arr_count++;
                      //   }
                                                
                      //   $loopdate = strtotime($loopdate . ' +1 day');

                      // }

                      // // Model::insert($schedule_arr); // Eloquent
                      // DB::table('schedule')->insert($schedule_arr); 
                  }

            }
            else if($teacher_name_arr[$i] != "" && $teacher_name_arr[$i] != null && $teacher_name_arr[$i] != "undefined" && $modal_room_arr[$i] != "" && $modal_room_arr[$i] != null && $modal_room_arr[$i] != "undefined" && $modal_room_arr[$i] != 0)
            {     
                  $batch_check = BatchStudent::join('batch','batch_student.batch_id','=','batch.id')
                          ->join('schedule','batch_student.batch_id','=','schedule.batch_id')
                          ->where('batch_student.student_id',$student_id)
                          ->where('schedule.class_id',$class_period_arr[$i])
                          ->whereBetween('batch.date_from',[$date_start[0],$date_end[0]])
                          ->count();

                  if($batch_check == 0)
                  {
                      $batch = new Batch();
                      $batch -> program_id = $program_id;
                      $batch -> student_personality_id = $student_personality_id;
                      $batch -> course_id = $modal_course_arr[$i];
                      $batch -> course_capacity_id = $modal_course_capacity_arr[$i];
                      $batch -> date_from = $date_start[0];
                      $batch -> date_to = $date_end[0];
                      $batch -> save();

                      $batch_id = $batch -> id;

                      $batch_student = new BatchStudent();
                      $batch_student -> batch_id = $batch_id;
                      $batch_student -> student_id = $student_id;
                      $batch_student -> student_english_name = $student_eng_name;
                      $batch_student -> nickname = $student_nickname;
                      $batch_student -> save();

                      // $startdate = strtotime($date_start[0]);
                      // $enddate = strtotime($date_end[0]);
                      // $loopdate = $startdate;

                      // $schedule_arr = [];
                      // $schedule_arr_count = 0;
                      // while($loopdate <= $enddate) {

                      //   $loopdate = date('Y-m-d',$loopdate);

                      //   $schedule_find = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                      //           ->join('batch','schedule.batch_id','=','batch.id')
                      //           ->where('schedule.time_in_id',$modal_time_arr[$i])
                      //           ->where('schedule.time_out_id',$modal_time_next_arr[$i])
                      //           ->where('batch_student.student_id',$student_id)
                      //           ->where('schedule.date',$loopdate)
                      //           ->count();

                      //   $schedule_find_batch_id = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                      //           ->join('batch','schedule.batch_id','=','batch.id')
                      //           ->where('schedule.time_in_id',$modal_time_arr[$i])
                      //           ->where('schedule.time_out_id',$modal_time_next_arr[$i])
                      //           ->where('batch_student.student_id',$student_id)
                      //           ->where('schedule.date',$loopdate)
                      //           ->select(['batch_student.batch_id']);

                      //   // if($schedule_find == 0)
                      //   // {

                          $schedule = new Schedule();
                          $schedule -> batch_id = $batch_id;
                          // $schedule -> date = $loopdate;
                          $schedule -> room_id = $modal_room_arr[$i];
                          $schedule -> teacher_id = $teacher_name_arr[$i];
                          $schedule -> time_in_id = $modal_time_arr[$i];
                          $schedule -> time_out_id = $modal_time_next_arr[$i];
                          $schedule -> class_id = $class_period_arr[$i];
                          $schedule -> save();
                      //     $schedule_arr[$schedule_arr_count] = array('batch_id'=>$batch_id, 'date'=> $loopdate, 'room_id'=> $modal_room_arr[$i], 'teacher_id'=> $teacher_name_arr[$i], 'time_in_id'=> $modal_time_arr[$i], 'time_out_id'=> $modal_time_next_arr[$i], 'class_id'=> $class_period_arr[$i]);

                      //     $schedule_arr_count++;
                      //   // }

                      //   $loopdate = strtotime($loopdate . ' +1 day');

                      // }


                      // DB::table('schedule')->insert($schedule_arr);
                  }
            }
            else
            {
                   
            }

        }

    }

    public function getTeacherClass()
    {
        $date = \Input::Get('date');

        $teacher_class_duty = TeacherClassDuty::join('duty_type','teacher_class_duty.duty_type_id','=','duty_type.id')
            ->where('teacher_class_duty.date',$date)
            ->get();

        return response()->json($teacher_class_duty);

    }

    public function updateTeacherClass()
    {
        $teacher_id = \Input::Get('teacher_id');
        $class_id = \Input::Get('class_id');
        $date = \Input::Get('date');
        $duty_type_id = \Input::Get('duty_type_id');

        $duty_exist = TeacherClassDuty::where('teacher_id',$teacher_id)
            ->where('class_id',$class_id)
            ->where('date',$date)
            ->count();

        if($duty_exist == 0)
        {
          $teacher_class_duty = new TeacherClassDuty();
          $teacher_class_duty -> teacher_id = $teacher_id;
          $teacher_class_duty -> duty_type_id = $duty_type_id;
          $teacher_class_duty -> class_id = $class_id;
          $teacher_class_duty -> date = $date;
          $teacher_class_duty -> save();
        }
        else
        {
          $duty_exist = TeacherClassDuty::where('teacher_id',$teacher_id)
            ->where('class_id',$class_id)
            ->where('date',$date)
            ->get()->last();

          $teacher_class_duty = TeacherClassDuty::find($duty_exist -> id);
          $teacher_class_duty -> duty_type_id = $duty_type_id;
          $teacher_class_duty -> save();
        }

        return response()->json($teacher_class_duty);

    }

    public function deleteClass()
    {
        $schedule_id = \Input::get('schedule_id');

        $schedule = Schedule::find($schedule_id);
        $schedule -> delete();

        return response()->json($schedule);
    }

    public function deleteStudentClassSchedule()
    {
        $student_id = \Input::get('student_id');
        $program_id = \Input::get('program_id');
        $date_from = \Input::get('date_from');
        $date_to = \Input::get('date_to');

        $data_list = BatchStudent::leftJoin('batch','batch_student.batch_id','=','batch.id')
                  ->leftJoin('schedule','batch.id','=','schedule.batch_id')
                  ->where('batch_student.student_id', $student_id)
                  ->where('batch.program_id', $program_id)
                  ->where('batch.date_from', $date_from)
                  ->where('batch.date_to', $date_to)
                  ->select(['batch_student.id as batch_student_id','batch.id as batch_id','schedule.id as schedule_id'])
                  ->get();

        foreach ($data_list as $data) {
          
            $batch = Batch::find($data -> batch_id);
            $batch -> delete();

            $batch_student = BatchStudent::find($data -> batch_student_id);
            $batch_student -> delete();

            $schedule = Schedule::find($data -> schedule_id);
            $schedule -> delete();
        }

        return response()->json($schedule);
    }

    public function deleteClassDates()
    {
        $date = \Input::get('date');
        $student_id = \Input::get('student_id');
        $program_id = \Input::get('program_id');

        $max = sizeof($date);

        json_decode(serialize($date));

        for ($i=0; $i < $max; $i++) { 

              $schedule_list = BatchStudent::leftJoin('batch','batch_student.batch_id','=','batch.id')
                            ->leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
                            ->where('batch_student.student_id',$student_id)
                            ->where('batch.program_id',$program_id)
                            ->where('schedule.date',$date[$i])
                            ->select(['schedule.id as schedule_id','schedule.batch_id'])
                            ->get();

              foreach ($schedule_list as $schedule) 
              {

                $batch_student = BatchStudent::where('batch_student.student_id',$student_id)
                            ->where('batch_student.batch_id',$schedule -> batch_id)
                            ->select(['batch_student.id'])
                            ->get()->last();
                            
                $batch_student_delete = BatchStudent::find($batch_student -> id);
                $batch_student_delete -> delete();

                $schedule_delete = Schedule::find($schedule -> schedule_id);
                $schedule_delete -> delete();
              }
        }

        return response()->json($schedule_list);
    }

    public function deleteClassAll()
    {
        $schedule_id = \Input::get('schedule_id');

        $schedule = Schedule::find($schedule_id);
        $batch = Batch::find($schedule -> batch_id);
        $batch_student_list = BatchStudent::where('batch_student.batch_id', $schedule -> batch_id)->get();

        foreach ($batch_student_list as $batch_student) 
        {
          $batch_student -> delete();
        }

        $batch -> delete();
        $schedule -> delete();

        return response()->json($schedule);
    }
    
    public function postEdit()
    {
        $schedule_id = \Input::get('schedule_id');
        $batch_id = \Input::get('batch_id');
        $time_id = \Input::get('time_id');
        $time_out = \Input::get('time_out');
        $teacher_id = \Input::get('teacher_id');
        $student_name_arr = \Input::get('student_name_arr');
        $student_eng_name_arr = \Input::get('student_eng_name_arr');
        $date_start = \Input::get('start_arr');
        $date_end = \Input::get('end_arr');
        $course_capacity_id = \Input::get('course_capacity_id');
        $program_id = \Input::get('program_id');
        $course_id = \Input::get('course_id');
        $room_id = \Input::get('room_id');
        $date = \Input::get('date');
        // $date_start = \Input::get('date_start');
        // $date_end = \Input::get('date_end');       

        $max = sizeof($student_name_arr);

        json_decode(serialize($student_name_arr));
        json_decode(serialize($student_eng_name_arr));
        json_decode(serialize($date_start));
        json_decode(serialize($date_end));

        // $schedule_data = Schedule::where('batch_id',$batch_id)
        //             ->where('date',$date)
        //             ->get();

        // foreach ($schedule_data as $schedule) {
        //     $data = Schedule::find($schedule->id);
        //     $data -> room_id = $room_id;
        //     $data -> teacher_id = $teacher_id;
        //     $data -> save();
        // }

        $schedule = Schedule::find($schedule_id);
        $schedule -> room_id = $room_id;
        $schedule -> teacher_id = $teacher_id;
        $schedule -> save();

        $batch_data = Batch::find($batch_id);
        $batch_data -> program_id;
        $batch_data -> save();

        for($i = 0; $i < $max; $i++)
        {
            $batch_student = new BatchStudent();
            $batch_student -> batch_id = $batch_id;
            $batch_student -> student_id = $student_name_arr[$i];
            $batch_student -> student_english_name = $student_eng_name_arr[$i];
            $batch_student -> save();

            // $startdate = strtotime($date_start[$i]);
            // $enddate = strtotime($date_end[$i]);
            // $loopdate = $startdate;

            // while($loopdate <= $enddate) {

            //   $loopdate = date('Y-m-d',$loopdate);

              $schedule = new Schedule();
              $schedule -> batch_id = $batch_id;
              // $schedule -> date = $loopdate;
              $schedule -> room_id = $room_id;
              $schedule -> teacher_id = $teacher_id;
              $schedule -> time_in_id = $time_id;
              $schedule -> time_out_id = $time_out;
              $schedule -> save();

            //   $loopdate = strtotime($loopdate . ' +1 day');

            // }
        }


    }

    public function importClass()
    {
       $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();
      return view('scheduler/import_class',compact('gen_role'));
    }

    public function postImportClass(){

    $file = Input::file('import_students_file');
    $extension = Input::file('import_students_file')->getClientOriginalExtension();

    if($extension =="xls" || $extension=="xlsx"){
      $destination_path = 'uploads';
      $original_file_name_with_extension = Input::file('import_students_file')->getClientOriginalName();
      $original_file_name_without_extension = pathinfo($original_file_name_with_extension, PATHINFO_FILENAME);
      $file_name = $original_file_name_without_extension.rand(11111,99999).'.'.$extension;
      Input::file('import_students_file')->move($destination_path, $file_name);
      $this->migrateExcelToDB("uploads/".$file_name);

          Session::flash('success', 'Upload successfully');
          return Redirect::to('scheduler/import_class');
    }

      if($file == "" && $file== null || $extension =="" && $extension ==null){
          Session::flash('error', 'is_uploaded_file(filename) file is not valid');
          return Redirect::to('scheduler/import_class');
        }

      else{
          Session::flash('error', 'is_uploaded_file(filename) file is not valid');
          return Redirect::to('scheduler/import_class');
      }

  }

  private function migrateExcelToDB($file_name){

        $import_students_list = Excel::selectSheetsByIndex(0)->load($file_name, function($reader){})->get()->toArray();

        $count = 1;

        foreach ($import_students_list as $import_students) {



            if($import_students["student_id"] != "") 
            { 
              if($import_students["student_id"] != "Exit")
              {
                  $date_from = $import_students["date_from"];
                  $date_to = $import_students["date_to"];
                  $b = 1;
                  $c = 1;
                  for ($i=1; $i <= 10 ; $i++) 
                  {
                      $program = $this->getProgram($import_students["course"]);
                      $subject = $this->getCourse($import_students["subject".$i]);
                      $course_capacity = $this->getCourseCapacity($import_students["period".$i]);
                      $room = $this->getRoom($import_students["room".$i]);

                      if($subject)
                      {
                          $subject_id = $subject -> id;
                      }
                      else
                      {
                          $subject_id = 0;
                      }

                      if($course_capacity)
                      {
                          $course_capacity_id = $course_capacity -> id;
                      }
                      else
                      {
                          $course_capacity_id = 0;
                      }

                      if($room)
                      {
                          $room_id = $room -> id;
                      }
                      else
                      {
                          $room_id = 0;
                      }

                      if($import_students["teacher".$i] != "" && $import_students["teacher".$i] != "-" && $import_students["teacher".$i] != " - ")
                      {
                        $teacher = $this->getTeacher($import_students["teacher".$i]);

                        if($teacher)
                        {
                          $teacher_id = $teacher -> id;
                        }
                        else
                        {
                          $teacher_id = 0;
                        }
                      }
                      else
                      {
                        $teacher_id = "";
                      }

                      if($program)
                      {

                        if($subject_id != 0 && $course_capacity_id != 0)
                        {
                            $batch = new Batch();
                            $batch -> program_id = $program -> id;
                            $batch -> student_personality_id = 0;
                            $batch -> course_id = $subject_id;
                            $batch -> course_capacity_id = $course_capacity_id;
                            $batch -> date_from = $date_from;
                            $batch -> date_to = $date_to;
                            $batch -> save();

                            $batch_id = $batch -> id;

                            $batch_student = new BatchStudent();
                            $batch_student -> batch_id = $batch_id;
                            $batch_student -> student_id = $import_students["student_id"];
                            $batch_student -> student_english_name = $import_students["student_name"];
                            $batch_student -> nickname = "";
                            $batch_student -> save();

                            // $startdate = strtotime($date_from);
                            // $enddate = strtotime($date_to);
                            // $loopdate = $startdate;

                            // $schedule_arr = [];
                            // $schedule_arr_count = 0;
                            // while($loopdate <= $enddate) {

                            //   $loopdate = date('Y-m-d',$loopdate);

                            //   $schedule_find = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                            //           ->join('batch','schedule.batch_id','=','batch.id')
                            //           ->where('schedule.time_in_id',$b++)
                            //           ->where('schedule.time_out_id',$b++)
                            //           ->where('batch_student.student_id',$import_students["student_id"])
                            //           ->where('schedule.date',$loopdate)
                            //           ->count();

                            //   $time_in = $c;
                            //   $time_out = $c + 1;

                            //   $schedule_find_batch_id = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                            //           ->join('batch','schedule.batch_id','=','batch.id')
                            //           ->where('schedule.time_in_id',$c++)
                            //           ->where('schedule.time_out_id',$c++)
                            //           ->where('batch_student.student_id',$import_students["student_id"])
                            //           ->where('schedule.date',$loopdate)
                            //           ->select(['batch_student.batch_id']);

                            //   // if($schedule_find == 0)
                            //   // {

                                $schedule = new Schedule();
                                $schedule -> batch_id = $batch_id;
                                // $schedule -> date = $loopdate;
                                $schedule -> room_id = $room_id;
                                $schedule -> teacher_id = $teacher_id;
                                // $schedule -> time_in_id = $time_in;
                                // $schedule -> time_out_id = $time_out_id;
                                $schedule -> class_id = $i;
                                $schedule -> save();
                            //     $schedule_arr[$schedule_arr_count] = array('batch_id'=>$batch_id, 'date'=> $loopdate, 'room_id'=> $room_id, 'teacher_id'=> $teacher_id, 'time_in_id'=> $time_in, 'time_out_id'=> $time_out, 'class_id'=> $i);

                            //     $schedule_arr_count++;
                            //   // }

                            //   $loopdate = strtotime($loopdate . ' +1 day');

                            // }


                            // DB::table('schedule')->insert($schedule_arr);


                        }
                      }
                  }

              }
              else
              {
                return;
              }
            }

          $count++;
        }

  }


  private function getProgram($program){
      $program = Program::where('program.program_code','LIKE', '%'.$program.'%')
              ->get()->first();

      return $program;
  }

  private function getCourse($subject){
      $subject = Course::where('course_name','Like', '%'.$subject.'%')->get()->first();

      return $subject;
  }

  private function getCourseCapacity($course_capacity){
      $course_capacity = CourseCapacity::where('capacity_name','Like','%'.$course_capacity.'%')->get()->first();

      return $course_capacity;
  }

  private function getRoom($room){
      $room = Room::where('room_name','Like','%'.$room.'%')->get()->first();

      return $room;
  }

  private function getTeacher($teacher){
      $teacher = Teacher::join('employee','teacher.employee_id','=','employee.id')
            ->join('person','employee.person_id','=','person.id')
            ->where('person.nickname','Like', '%'.$teacher.'%')
            ->select(['teacher.id','person.first_name'])->get()->first();

      return $teacher;
  }
}
