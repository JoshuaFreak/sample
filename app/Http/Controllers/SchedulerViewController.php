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
use App\Models\StudentInfo;
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

class SchedulerViewController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{

        $is_admin = 0;

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

        return view('viewing/student.index', compact('is_admin','level_list','student_personality_list','course_list','course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list'));

	}

	public function teacher()
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
        return view('viewing/teacher/index', compact('course_list','course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list','duty_type_list'));
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

          return view('viewing/group_class.index',compact('course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list'));
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

          return view('viewing/activity_class/index',compact('course_capacity_list','program_list','section_list', 'room_list', 'campus_list', 'teacher_list'));
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

	public function getTeacherClass()
	{
		$date = \Input::Get('date');

        $teacher_class_duty = TeacherClassDuty::join('duty_type','teacher_class_duty.duty_type_id','=','duty_type.id')
            ->where('teacher_class_duty.date',$date)
            ->get();

        return response()->json($teacher_class_duty);

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

    public function studentListLocal()
    { 
       		$now_start_curr_year = date('Y');
			$now_start_curr_month = date('m');
			$now_start_curr_day = date('d');

			$search_student_curr_date = $now_start_curr_year."-".$now_start_curr_month."-".$now_start_curr_day;

			$student_list = StudentInfo::where("student_info.date_to",">=",$search_student_curr_date)
						->where("student_info.state","!=",1)
						->where("student_info.state","!=",3)
						->where("student_info.state","!=",5)
						->where("student_info.state","!=",6)
						->where("student_info.state","!=",7)
            ->where("student_info.state","!=",8)
						->where("student_info.refund","=",0)
						->select("student_info.student_id","student_info.semail","student_info.sename","student_info.date_from","student_info.date_to","student_info.nickname")
						->orderBy("date_to","ASC")
						->get();

			return response()->json($student_list);
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
}
