<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Room;
use App\Models\ESLBook;
use App\Models\IELTSBook;
use App\Models\TOEICBook;
use App\Models\BusinessBook;
use App\Models\WorkingBook;
use App\Models\RoomBook;
use App\Models\RoomTeacher;
use App\Models\Level;
use App\Models\ProgramCourse;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;
use DB;

class BookController extends SchedulerMainController {
   

    public function index()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $level_list = Level::select(['level.id','level.level_code'])->get();

        $room_list = Room::where('room.course_capacity_id','=',8)
                ->orWhere('room.course_capacity_id','=',5)
                ->select(['room.id','room.room_name','room.room_capacity'])
                ->orderBy('room_name','ASC')
                ->get();

        return view('scheduler/book.index',compact('gen_role','room_list','level_list'));
    }

    public function index1on1()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $esl_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('course.id',1)
                    ->where('program_course.course_capacity_id',1)
                    ->orwhere(function($query){
                          $query->where('course.id', 2)
                                ->where('program_course.course_capacity_id',1);
                    })
                    ->orwhere(function($query){
                          $query->where('course.id', 3)
                                ->where('program_course.course_capacity_id',1);
                    })
                    ->orwhere(function($query){
                          $query->where('course.id', 4)
                                ->where('program_course.course_capacity_id',1);
                    })
                    ->select(['course.course_name','course.id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        return view('scheduler/book1on1.index',compact('gen_role','esl_subject_list'));
    }
    
    public function ieltsBook1on1()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $ielts_reg_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',10)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        $ielts_pre_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',25)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        $ielts_bridge_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',7)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        return view('scheduler/book1on1.ielts',compact('gen_role','ielts_reg_subject_list','ielts_pre_subject_list','ielts_bridge_subject_list'));
    }

    public function toeicBook1on1()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $toeic_reg_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',26)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        $toeic_pre_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',27)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        $toeic_bridge_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',6)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        return view('scheduler/book1on1.toeic',compact('gen_role','toeic_reg_subject_list','toeic_pre_subject_list','toeic_bridge_subject_list'));
    }

    public function businessBook1on1()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $business_pre_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',23)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        $business_reg_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',22)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        return view('scheduler/book1on1.business',compact('gen_role','business_reg_subject_list','business_pre_subject_list'));
    }

    public function workingBook1on1()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $working_au_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',28)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        $working_ca_subject_list = ProgramCourse::leftJoin('program','program_course.program_id','=','program.id')
                    ->leftJoin('course','program_course.course_id','=','course.id')
                    ->where('program.id',29)
                    ->where('program_course.course_capacity_id',1)
                    ->select(['course.course_name','course.id','program.id as program_id'])
                    ->groupBy('program_course.course_id')
                    ->get();

        return view('scheduler/book1on1.working',compact('gen_role','working_au_subject_list','working_ca_subject_list'));
    }

    public function indexTeacher()
    {
    	$username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $room_list = Room::where('room.course_capacity_id','=',8)
        		->select(['room.id','room.room_name','room.room_capacity'])
        		->orderBy('room_name','ASC')
                ->get();

        return view('scheduler/teacher.index',compact('gen_role','room_list'));
    }

    public function getRoomBook()
    {
        $room_book = RoomBook::get();

        return response()->json($room_book);
    }

    public function getESLBook()
    {
        $room_book = ESLBook::get();

        return response()->json($room_book);
    }

    public function getIELTSBook()
    {
        $room_book = IELTSBook::get();

        return response()->json($room_book);
    }

    public function getTOEICBook()
    {
        $room_book = TOEICBook::get();

        return response()->json($room_book);
    }

    public function getBusinessBook()
    {
        $room_book = BusinessBook::get();

        return response()->json($room_book);
    }

    public function getWorkingBook()
    {
        $room_book = WorkingBook::get();

        return response()->json($room_book);
    }

    public function getRoomTeacher()
    {
    	$room_teacher = RoomTeacher::join('teacher','room_teacher.teacher_id','=','teacher.id')
                ->leftJoin('employee','teacher.employee_id','=','employee.id')
                ->leftJoin('person','employee.person_id','=','person.id')
                ->select(['room_teacher.room_id','room_teacher.teacher_id','room_teacher.id','room_teacher.class_id','person.first_name','person.last_name','person.nickname'])
                ->get();

    	return response()->json($room_teacher);
    }

   	public function saveRoomBook()
    {
        $book_arr = \Input::get('book_arr');
        $max = sizeof($book_arr);
        json_decode(serialize($book_arr));

        $book_data = [];
        for ($i=0; $i < $max; $i++) { 

            $room_book_count = RoomBook::where('room_id',$book_arr[$i][1])
                        ->where('class_id',$book_arr[$i][2])
                        ->count();

            if($room_book_count == 0)
            {
                $book_data[$i] = ['room_id' => $book_arr[$i][1],'class_id' => $book_arr[$i][2],'book_title' => $book_arr[$i][0]];
            }
            else
            {
                $room_book_data = RoomBook::where('room_book.room_id',$book_arr[$i][1])
                        ->where('room_book.class_id',$book_arr[$i][2])
                        ->select(['room_book.id'])
                        ->get()->first();

                $book = RoomBook::find($room_book_data -> id);
                $book -> book_title = $book_arr[$i][0];
                $book -> level_from_id = $book_arr[$i][3];
                $book -> level_to_id = $book_arr[$i][4];
                $book -> save();
            }
        }

        DB::table('room_book')->insert($book_data);
        return response()->json($book_data);

    }

    public function saveESLbook()
    {
        $book_arr = \Input::get('book_arr');
        $max = sizeof($book_arr);
        json_decode(serialize($book_arr));

        $book_data = [];
        for ($i=0; $i < $max; $i++) { 

            $room_book_count = ESLBook::where('course_id',$book_arr[$i][1])
                        ->where('level',$book_arr[$i][2])
                        ->count();

            if($room_book_count == 0)
            {
                $book_data[$i] = ['course_id' => $book_arr[$i][1],'level' => $book_arr[$i][2],'book_title' => $book_arr[$i][0]];
            }
            else
            {
                $room_book_data = ESLBook::where('esl_book.course_id',$book_arr[$i][1])
                        ->where('esl_book.level',$book_arr[$i][2])
                        ->select(['esl_book.id'])
                        ->get()->first();

                $book = ESLBook::find($room_book_data -> id);
                $book -> book_title = $book_arr[$i][0];
                $book -> supplementary_book = $book_arr[$i][3];
                $book -> save();
            }
        }

        DB::table('esl_book')->insert($book_data);
        return response()->json($book_data);

    }

    public function saveIELTSbook()
    {
        $book_arr = \Input::get('book_arr');
        $max = sizeof($book_arr);
        json_decode(serialize($book_arr));

        $book_data = [];
        for ($i=0; $i < $max; $i++) { 

            $room_book_count = IELTSBook::where('course_id',$book_arr[$i][1])
                        ->where('month',$book_arr[$i][2])
                        ->where('program_id',$book_arr[$i][3])
                        ->count();

            if($room_book_count == 0)
            {
                $book_data[$i] = ['course_id' => $book_arr[$i][1],'month' => $book_arr[$i][2],'book_title' => $book_arr[$i][0],'program_id' => $book_arr[$i][3]];
            }
            else
            {
                $room_book_data = IELTSBook::where('ielts_book.course_id',$book_arr[$i][1])
                        ->where('ielts_book.program_id',$book_arr[$i][3])
                        ->where('ielts_book.month',$book_arr[$i][2])
                        ->select(['ielts_book.id'])
                        ->get()->first();

                $book = IELTSBook::find($room_book_data -> id);
                $book -> book_title = $book_arr[$i][0];
                if($book_arr[$i][3] == 10 || $book_arr[$i][3] == 25)
                {
                    $book -> book_title_general = $book_arr[$i][4];
                }
                $book -> save();
            }
        }

        DB::table('ielts_book')->insert($book_data);
        return response()->json($book_data);

    }

    public function saveTOEICbook()
    {
        $book_arr = \Input::get('book_arr');
        $max = sizeof($book_arr);
        json_decode(serialize($book_arr));

        $book_data = [];
        for ($i=0; $i < $max; $i++) { 

            $room_book_count = TOEICBook::where('course_id',$book_arr[$i][1])
                        ->where('month',$book_arr[$i][2])
                        ->where('program_id',$book_arr[$i][3])
                        ->count();

            if($room_book_count == 0)
            {
                $book_data[$i] = ['course_id' => $book_arr[$i][1],'month' => $book_arr[$i][2],'book_title' => $book_arr[$i][0],'program_id' => $book_arr[$i][3]];
            }
            else
            {
                $room_book_data = TOEICBook::where('toeic_book.course_id',$book_arr[$i][1])
                        ->where('toeic_book.program_id',$book_arr[$i][3])
                        ->where('toeic_book.month',$book_arr[$i][2])
                        ->select(['toeic_book.id'])
                        ->get()->first();

                $book = TOEICBook::find($room_book_data -> id);
                $book -> book_title = $book_arr[$i][0];
                $book -> save();
            }
        }

        DB::table('toeic_book')->insert($book_data);
        return response()->json($book_data);

    }

    public function saveBusinessbook()
    {
        $book_arr = \Input::get('book_arr');
        $max = sizeof($book_arr);
        json_decode(serialize($book_arr));

        $book_data = [];
        for ($i=0; $i < $max; $i++) { 

            $room_book_count = BusinessBook::where('course_id',$book_arr[$i][1])
                        ->where('month',$book_arr[$i][2])
                        ->where('program_id',$book_arr[$i][3])
                        ->count();

            if($room_book_count == 0)
            {
                $book_data[$i] = ['course_id' => $book_arr[$i][1],'month' => $book_arr[$i][2],'book_title' => $book_arr[$i][0],'program_id' => $book_arr[$i][3]];
            }
            else
            {
                $room_book_data = BusinessBook::where('business_book.course_id',$book_arr[$i][1])
                        ->where('business_book.program_id',$book_arr[$i][3])
                        ->where('business_book.month',$book_arr[$i][2])
                        ->select(['business_book.id'])
                        ->get()->first();

                $book = BusinessBook::find($room_book_data -> id);
                $book -> book_title = $book_arr[$i][0];
                $book -> save();
            }
        }

        DB::table('business_book')->insert($book_data);
        return response()->json($book_data);

    }

    public function saveWorkingbook()
    {
        $book_arr = \Input::get('book_arr');
        $max = sizeof($book_arr);
        json_decode(serialize($book_arr));

        $book_data = [];
        for ($i=0; $i < $max; $i++) { 

            $room_book_count = WorkingBook::where('course_id',$book_arr[$i][1])
                        ->where('month',$book_arr[$i][2])
                        ->where('program_id',$book_arr[$i][3])
                        ->count();

            if($room_book_count == 0)
            {
                $book_data[$i] = ['course_id' => $book_arr[$i][1],'month' => $book_arr[$i][2],'book_title' => $book_arr[$i][0],'program_id' => $book_arr[$i][3]];
            }
            else
            {
                $room_book_data = WorkingBook::where('working_book.course_id',$book_arr[$i][1])
                        ->where('working_book.program_id',$book_arr[$i][3])
                        ->where('working_book.month',$book_arr[$i][2])
                        ->select(['working_book.id'])
                        ->get()->first();

                $book = WorkingBook::find($room_book_data -> id);
                $book -> book_title = $book_arr[$i][0];
                $book -> save();
            }
        }

        DB::table('working_book')->insert($book_data);
        return response()->json($book_data);

    }

    public function saveRoomTeacher()
    {
    	$teacher_arr = \Input::get('teacher_arr');
        $max = sizeof($teacher_arr);
        json_decode(serialize($teacher_arr));

        $teacher_data = [];
        for ($i=0; $i < $max; $i++) { 

        	$room_teacher_count = RoomTeacher::where('room_id',$teacher_arr[$i][1])
        				->where('class_id',$teacher_arr[$i][2])
        				->count();

        	if($room_teacher_count == 0)
        	{
	        	$teacher_data[$i] = ['room_id' => $teacher_arr[$i][1],'class_id' => $teacher_arr[$i][2],'teacher_id' => $teacher_arr[$i][0]];
        	
                $teacher = new RoomTeacher();
                $teacher -> teacher_id = $teacher_arr[$i][0];
                $teacher -> room_id = $teacher_arr[$i][1];
                $teacher -> class_id = $teacher_arr[$i][2];
                $teacher -> save();
            }
        	else
        	{
        		$room_teacher_data = RoomTeacher::where('room_teacher.room_id',$teacher_arr[$i][1])
        				->where('room_teacher.class_id',$teacher_arr[$i][2])
        				->select(['room_teacher.id'])
        				->get()->first();
                        
                if($teacher_arr[$i][3] == "")
                {
                    $teacher = RoomTeacher::find($room_teacher_data -> id);
                    $teacher -> teacher_id = 0;
                    $teacher -> save();
                }
                else
                {
            		$teacher = RoomTeacher::find($room_teacher_data -> id);
            		$teacher -> teacher_id = $teacher_arr[$i][0];
            		$teacher -> save();
                }
        	}
        }

	    // DB::table('room_teacher')->insert($teacher_data);
        return response()->json($teacher_data);

    }

}
