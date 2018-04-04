<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Course;
use App\Models\Employee;
use App\Models\ProgramCourse;
use App\Models\Room;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;
use DB;

class TeacherSubjectController extends BaseController {
   

    public function index()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        // $course_list = Course::where('course.is_active','=',1)->get();
        $course_list = ProgramCourse::join('course','program_course.course_id','=','course.id')
                  ->select(['course.id','course.course_name'])
                  ->groupBy('program_course.course_id')
                  ->where('program_course.is_active','=',1)->get();

        $room_list = Room::where('room.course_capacity_id',1)->get();
        return view('teacher_portal/teacher_subject/index',compact('gen_role','course_list','room_list')); 
    }
    public function dataJson()
    {
        $subject_list = \Input::get('subject');
        $room_id = \Input::get('room_id');
        $teacher_id = \Input::get('teacher_id');
        $max = sizeof($subject_list);
        json_decode(serialize($subject_list));

        $employee = Teacher::join('employee','teacher.employee_id','=','employee.id')
                        ->where('teacher.id',$teacher_id)
                        ->select(['employee.id'])->get()->last();
        $room_update = Employee::find($employee -> id);
        $room_update -> room_id = $room_id;
        $room_update -> save();

        $subject_arr =[];
        $subject_arr_count = 0;

        if($teacher_id != null && $teacher_id != "" && $teacher_id != "undefined" && $teacher_id != 0)
        {
          for($i = 0; $i < $max; $i++)
          {
            $subject_arr[$subject_arr_count] = array('teacher_id'=>$teacher_id,'course_id'=> $subject_list[$i]);
            $subject_arr_count++;
          }
        }

        DB::table('teacher_subject')->insert($subject_arr);
    }
    public function getDataJson()
    {
      $teacher_id = \Input::get('teacher_id');
      $teacher_subject_list = TeacherSubject::join('course','teacher_subject.course_id','=','course.id')
              ->where('teacher_subject.teacher_id',$teacher_id)
              ->select(['teacher_subject.id','course.course_name','teacher_subject.course_id'])
              ->get();

      return response()->json($teacher_subject_list);
    }
}
