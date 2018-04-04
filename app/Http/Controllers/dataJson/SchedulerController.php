<?php namespace App\Http\Controllers\dataJson;

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
use App\Models\Employee;
use App\Models\EventModel;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Program;
use App\Models\ProgramClassCapacity;
use App\Models\Section;
use App\Models\Person;
use App\Models\Room;
use App\Models\RoomTeacher;
use App\Models\Batch;
use App\Models\Schedule;
use App\Models\Scheduler;
use App\Models\ScheduleType;
use App\Models\SemesterLevel;
use App\Models\StudentCurriculum;
use App\Models\SubjectOffered;
use App\Models\StudentInfo;
use App\Models\Time;
use App\Models\Teacher;
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

    public function getScheduleStudentByTeacher()
    {
    	
    	$student_id = \Input::get('student_id');
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
          ->where('batch_student.student_id',$student_id)
          ->groupBy('schedule.batch_id','schedule.time_in_id')
          ->select(['schedule.batch_id','schedule.id','schedule.time_in_id as time_start','schedule.time_out_id as time_end ','batch_student.student_id as teacher_id','person.nickname','batch.program_id','batch.course_id','program.program_name as class','batch.program_id','schedule.room_id','program.program_color','batch.course_capacity_id','course_capacity.capacity_name','course_capacity.course_capacity_code as capacity_code','course.course_name','schedule.room_id','room.room_name'])->get();

         return response()->json($schedule);
    }

   	public function getRoomTeacher()
    {
      $room_id = \Input::get('room_id');
    	$class_id = \Input::get('class_id');
      $teacher = [];
      $available_teacher_arr = [];

      if($room_id != 0)
      {
          $course_capacity_id = Room::where('room.id',$room_id)->select(['room.course_capacity_id'])->get()->last();

          if($course_capacity_id -> course_capacity_id == 8 || $course_capacity_id -> course_capacity_id == 5)
          {
              $teacher = RoomTeacher::join('teacher','room_teacher.teacher_id','=','teacher.id')
                        ->join('employee','teacher.employee_id','=','employee.id')
                        ->join('person','employee.person_id','=','person.id')
                        ->where('room_teacher.room_id',$room_id)
                        ->where('room_teacher.class_id',$class_id)
                        ->where('person.is_active',1)
                        ->select(['teacher.id','person.first_name','person.last_name','person.nickname'])
                        ->get()->last();
          }
          else
          {
            	$teacher = Employee::join('teacher','employee.id','=','teacher.employee_id')
            			->join('person','employee.person_id','=','person.id')
            			->where('employee.room_id',$room_id)
                  ->where('person.is_active',1)
            			->select(['teacher.id','person.first_name','person.last_name','person.nickname'])
            			->get()->last();
          }


          $available_teacher_list = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                  ->leftJoin('person','employee.person_id','=','person.id')
                  ->leftJoin('gen_user', 'gen_user.person_id', '=', 'person.id')
                  ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                  ->where('teacher.id','!=',0)
                  ->where('gen_user_role.gen_role_id',1)
                  ->where('person.last_name',"!=", "")
                  ->where('person.is_active',1)
                  ->select(['teacher.id','person.last_name','person.first_name','person.nickname'])
                  ->groupBy('teacher.employee_id')
                  ->get();

          if($course_capacity_id -> course_capacity_id == 8)
          {
              foreach ($available_teacher_list as $available_teacher) 
              {
                  $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                        ->where('schedule.teacher_id',$available_teacher -> id)
                        ->where('schedule.room_id',$room_id)
                        ->where('schedule.class_id',$class_id)
                        ->where('batch.date_from','>=',date('Y-m-d'))
                        ->where('batch.date_to','<=',date('Y-m-d'))
                        ->count();

                  $room_capacity = Room::find($room_id);
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
          elseif($course_capacity_id -> course_capacity_id == 1)
          {
              foreach ($available_teacher_list as $available_teacher) 
              {
                  $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                        ->where('schedule.teacher_id',$available_teacher -> id)
                        ->where('schedule.class_id',$class_id)
                        ->where('batch.date_from','>=',date('Y-m-d'))
                        ->where('batch.date_to','<=',date('Y-m-d'))
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
      }
      $data[0] = $teacher;
      $data[1] = $available_teacher_arr;

    	return response()->json($data);
    }

    public function checkVacantSchedule()
    {
    	$program_id = \Input::get('program_id');

    	$program_class_capacity_list = ProgramClassCapacity::where('program_class_capacity.program_id','=',$program_id)
    				->get();

    	foreach ($program_class_capacity_list as $program_class_capacity) 
    	{
    		echo $program_class_capacity -> course_capacity_id;
    	}

    	exit();
    }
}