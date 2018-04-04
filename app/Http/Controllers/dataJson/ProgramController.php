<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\Program;
use App\Models\Room;
use App\Models\ProgramCourse;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\RoomTeacher;
use App\Http\Requests\ProgramRequest;
use App\Http\Requests\ProgramEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ProgramController extends BaseController {
   

    public function dataJson(){

      $condition = \Input::all();
      $query = Program::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $program = $query->select([ 'id as value','program_code as text'])->get();

      return response()->json($program);
    }

    public function programCourseDataJson(){

      $condition = \Input::all();
      $query = ProgramCourse::join('course','program_course.course_id','=','course.id')
                      ->leftJoin('course_capacity','program_course.course_capacity_id','=','course_capacity.id')->select();
      foreach($condition as $column => $value)
      {
          $query->where($column, '=', $value);
      }
      $program = $query->select([ 'program_course.course_id as value','course.course_name as text','program_course.class_id as count','program_course.course_capacity_id as counter','course_capacity.id as text1'])->get();

      return response()->json($program);
    }
    
    public function courseCapacityRoomTeacherDataJson(){

      $class_id = \Input::get('class_id');
      $date = \Input::get('date');
      $date_end = \Input::get('date_end');
      $course_capacity_id = \Input::get('course_capacity_id');

      if($course_capacity_id == 5)
      {
          $course_capacity_id = 5;
      }

      $room_list = Room::leftJoin('employee','room.id','=','employee.room_id')
              ->leftJoin('teacher','employee.id','=','teacher.employee_id')
              ->leftJoin('person','employee.person_id','=','person.id')
              ->where('room.course_capacity_id', '=', $course_capacity_id)
              ->select(['room.id as value','room.room_name as text','teacher.id as teacher_id','person.first_name','person.last_name'])
              ->groupBy('room.id')
              ->orderBy('room.id','ASC')
              ->get();

      $room_teacher_arr = [];
      $available_teacher_arr = [];

      $available_teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
              ->join('person','employee.person_id','=','person.id')
              ->leftJoin('gen_user', 'gen_user.person_id', '=', 'person.id')
              ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
              ->where('teacher.id','!=',0)
              ->where('gen_user_role.gen_role_id',1)
              ->where('person.last_name',"!=", "")
              ->where('person.is_active',1)
              ->select(['teacher.id','person.last_name','person.first_name','person.nickname'])
              ->groupBy('teacher.employee_id')
              ->get();

      foreach ($available_teacher_list as $available_teacher) 
      {
          $available_schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                ->where('schedule.teacher_id',$available_teacher -> id)
                ->where('schedule.class_id',$class_id)
                ->where('batch.date_from','>=',$date)
                ->where('batch.date_to','<=',$date)
                ->count();

          if($available_schedule_count == 0)
          {
              array_push($available_teacher_arr, array('teacher_id' => $available_teacher -> id,'teacher_name' => $available_teacher -> first_name." ".$available_teacher -> last_name." - ".$available_teacher -> nickname));

          }
          else
          {

              if($course_capacity_id == 8 || $course_capacity_id == 5)
              {
                      array_push($available_teacher_arr, array('teacher_id' => $available_teacher -> id,'teacher_name' => $available_teacher -> first_name." ".$available_teacher -> last_name." - ".$available_teacher -> nickname));
              }
          }
      }

      foreach ($room_list as $room) 
      {
          $schedule_count = Schedule::join('batch','schedule.batch_id','=','schedule.batch_id')
                    ->where('schedule.room_id',$room -> value)
                    ->where('schedule.class_id',$class_id)
                    ->where('batch.date_from','>=',$date)
                    ->where('batch.date_to','<=',$date)
                    ->count();

          $room_teacher = RoomTeacher::join('teacher','room_teacher.teacher_id','=','teacher.id')
                    ->join('employee','teacher.employee_id','=','employee.id')
                    ->join('person','employee.person_id','=','person.id')
                    ->where('room_teacher.room_id',$room -> value)
                    ->where('room_teacher.class_id',$class_id)
                    ->select(['teacher.id as teacher_id','person.first_name','person.last_name','person.nickname'])
                    ->get()->last();

          if($schedule_count == 0)
          {
            // array_push($room_teacher_arr, array('value' => $room -> value,'text' => $room -> text,'teacher_id' => $room -> teacher_id,'teacher_name' => $room -> first_name." ".$room ->last_name));

              if($course_capacity_id == 8 || $course_capacity_id == 5)
              {
                  if($room_teacher)
                  {
                    if($room_teacher -> first_name != "")
                    {
                      array_push($room_teacher_arr, array('value' => $room -> value,'text' => $room -> text,'teacher_id' => $room_teacher -> teacher_id,'teacher_name' => $room_teacher -> first_name." ".$room_teacher ->last_name." - ".$room_teacher -> nickname));
                    }
                  }
                  else
                  {
                      array_push($room_teacher_arr, array('value' => $room -> value,'text' => $room -> text,'teacher_id' => $room -> teacher_id,'teacher_name' => $room -> first_name." ".$room ->last_name." - ".$room -> nickname));
                  }
              }
              else
              {
                array_push($room_teacher_arr, array('value' => $room -> value,'text' => $room -> text,'teacher_id' => $room -> teacher_id,'teacher_name' => $room -> first_name." ".$room ->last_name." - ".$room -> nickname));

              }
          }
          else
          {
              if($course_capacity_id == 8 || $course_capacity_id == 5)
              {
                  if($room_teacher)
                  { 
                    if($room_teacher -> first_name != "")
                    {
                      array_push($room_teacher_arr, array('value' => $room -> value,'text' => $room -> text,'teacher_id' => $room_teacher -> teacher_id,'teacher_name' => $room_teacher -> first_name." ".$room_teacher ->last_name." - ".$room_teacher -> nickname));
                    }
                  }
                  else
                  {
                      array_push($room_teacher_arr, array('value' => $room -> value,'text' => $room -> text,'teacher_id' => $room -> teacher_id,'teacher_name' => $room -> first_name." ".$room ->last_name." - ".$room -> nickname));
                  }
              }
          }
      }

      $data[0] = $available_teacher_arr;
      $data[1] = $room_teacher_arr;

      return response()->json($data);
    }

    public function courseCapacityRoomDataJson(){

      $condition = \Input::all();
      $query = Room::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $program = $query->select([ 'room.id as value','room.room_name as text'])->get();

      return response()->json($program);
    }

}
