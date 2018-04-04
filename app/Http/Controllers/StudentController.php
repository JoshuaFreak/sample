<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth; 
use App\Models\IELTSBook;
use App\Models\ESLBook;
use App\Models\BusinessBook;
use App\Models\WorkingBook;
use App\Models\TOEICBook;
use App\Models\Student;
use App\Models\StudentExaminationScore;
use App\Models\BatchStudent;
use App\Models\Program;
use App\Models\RoomBook;
use App\Models\StudentInfo;
use App\Models\Gender;
use App\Models\Nationality;
use App\Models\PercentageLevel;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;
use Hash;
use DB;

class StudentController extends BaseController {
   
    public function __construct()
    {
            parent::__construct();
            $this->middleware('auth');
            // $this->middleware('admin');
    }
    
    public function index()
    {
        return view('student/index');
    }

    public function studentList()
    {
        return view('student/list');
    }

    public function getStudentList()
    {
        $now_start_curr_year = date(Y);
        $now_start_curr_month = date(m);
        $student_info = StudentInfo::
        $now_start_curr_day = date(d);

        $search_student_curr_date = $now_start_curr_year."".$now_start_curr_month."".$now_start_curr_day;

        return view('student/list');
    }

    public function viewData()
    {
        $student_id = \Input::get('student_id');
        return view('student/view_data');

    }

    public function studentToDepart()
    {
        return view('student/student_to_depart');
    }

    public function studentToArrive()
    {
        return view('student/student_to_arrive');
    }

    public function getStudentProgram()
    {
    	$student_id = \Input::get('student_id');

    	$program_list = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
		    	->join('batch','schedule.batch_id','=','batch.id')
                ->join('program','batch.program_id','=','program.id')
    			->join('program_category','program.program_category_id','=','program_category.id')
    			->where('batch_student.student_id',$student_id)
    			->select(['program.id','program.program_category_id','program.program_name','program_category.program_category_name'])
    			->groupBy(['program.id'])
    			->get();

        $examination_list = StudentExaminationScore::join('examination','student_examination_score.examination_id','=','examination.id')
                ->where('student_id',$student_id)
                ->select('student_examination_score.examination_id','examination.examination_name')
                ->groupBy('examination_id')
                ->get();

        $data[0] = $program_list;
        $data[1] = $examination_list;

    	return response()->json($data);
    }

    public function getStudentSchedule()
    {
    	$student_id = \Input::get('student_id');
        $program_id = \Input::get('program_id');
    	// $program_category_id = \Input::get('program_category_id');

    	$batch = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->leftJoin('batch','schedule.batch_id','=','batch.id')
		    	->leftJoin('program','batch.program_id','=','program.id')
    			->where('batch_student.student_id',$student_id)
    			->where('batch.program_id',$program_id)
    			->select(['batch.date_from','batch.date_to'])
    			->get()->last();

    	$schedule_list = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
		    	->leftJoin('batch','schedule.batch_id','=','batch.id')
    			->leftJoin('program','batch.program_id','=','program.id')
    			->leftJoin('course','batch.course_id','=','course.id')
    			->leftJoin('room','schedule.room_id','=','room.id')
    			->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
    			->leftJoin('employee','teacher.employee_id','=','employee.id')
    			->leftJoin('person','employee.person_id','=','person.id')
    			->leftJoin('time as time_in','schedule.time_in_id','=','time_in.id')
    			->leftJoin('time as time_out','schedule.time_out_id','=','time_out.id')
    			->where('batch_student.student_id',$student_id)
    			->where('batch.program_id',$program_id)
                // ->where('program.program_category_id',$program_id)
    			->select(['schedule.id','person.nickname','room.room_name','time_out.time as time_out','time_out.time_session as time_out_session','time_in.time as time_in','time_in.time_session as time_in_session','course.course_name'])
                ->groupBy('time_in.time','time_out.time')
                ->orderBy('schedule.class_id','ASC')
    			->get();

    	$data[0] = $batch;
    	$data[1] = $schedule_list;

    	return response()->json($data);
    }

    public function studentSchedulePdf(){


        $student_id = \Input::get('student_id');
        $program_category_id = \Input::get('program_category_id');

        $student_data_detail = StudentInfo::where('student_info.student_id',$student_id)
                        ->select(['student_info.nickname','student_info.gender_id','student_info.nationality_id','student_info.period','student_info.date_from','student_info.date_to'])
                        ->get()->last();
        // $nickname = \Input::get('nickname');
        // $gender_id = \Input::get('gender_id');
        // $nationality_id = \Input::get('nationality_id');
        // $program_id = \Input::get('program_id');
        // $period = \Input::get('period');
        // $examination_id = \Input::get('examination_id');
        // $date_to = \Input::get('date_to');
        // $date_from = \Input::get('date_from');

        $nickname = $student_data_detail ->nickname;
        $gender_id = $student_data_detail ->gender_id;
    	$nationality_id = $student_data_detail ->nationality_id;
        $program_id = \Input::get('program_id');
        // $period = $student_data_detail ->period;
        $examination_id = \Input::get('examination_id');
        $date_to = $student_data_detail ->date_to;
    	$date_from = $student_data_detail ->date_from;

        if($examination_id == null || $examination_id == "null")
        {
            $examination_last = StudentExaminationScore::where('student_examination_score.student_id','student_id')
                    ->select(['student_examination_score.examination_id','student_examination_score.program_category_id'])
                    ->get()
                    ->last();

            if($examination_last)
            {
                $examination_id = $examination_last -> examination_id;
                $program_category_id = $examination_last -> program_category_id;
            }
            else
            {
                $examination_id = 1;
            }
        }

    	$student = BatchStudent::join('batch','batch_student.batch_id','=','batch.id')
                ->leftJoin('student_personality','batch.student_personality_id','=','student_personality.id')
                ->leftJoin('student_info','batch_student.student_id','=','student_info.student_id')
                ->where('batch_student.student_id',$student_id)->get()->last();
    	$program = Program::find($program_id);



        if($program_category_id == "")
        {
            $program_category_id = $program ->program_category_id;
        }

    	$batch = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->leftJoin('batch','schedule.batch_id','=','batch.id')
		    	->leftJoin('level','batch.level_id','=','level.id')
    			->where('batch_student.student_id',$student_id)
    			->where('batch.program_id',$program_id)
    			->select(['batch.date_from','batch.date_to','level.level_code'])
    			->get()->last();

        $period = 0;
        $diff = strtotime($batch -> date_to, 0) - strtotime($batch -> date_from, 0);
        $period = round($diff / 604800);

        if($period <= 1)
        {
            $period = 1;
        }

        $student_examination_score_list = StudentExaminationScore::leftJoin('examination_type','student_examination_score.examination_type_id','=','examination_type.id')
                            ->leftJoin('program','student_examination_score.program_id','=','program.id')
                            ->where('student_examination_score.student_id',$student_id)
                            ->where('student_examination_score.examination_id',$examination_id)
                            // ->where('student_examination_score.program_id',$program_id)
                            ->where('student_examination_score.program_category_id',$program_category_id)
                            ->get();
        $count_score = 0;
        foreach($student_examination_score_list as $student_examination_score)
        {
           $count_score++ ;
        }

        if($count_score == 4)
        {
             $level_label = "Band";
             $target_level_label = "Target Band";
             $code = "" ;
        }
        elseif($count_score == 2)
        {
             $level_label = "Score" ;
             $target_level_label = "Target Score" ;
             $code = "" ;
        }
        else
        {
             $level_label = "Level" ;
             $target_level_label = "Target Level" ;
             $code = "L" ;
        }

        $reading_arr = [];
        $listening_arr = [];
        $grammar_voca_arr = [];
        $speaking_arr = [];
        $writing_arr = [];

        if($count_score == 5)
        {
            $reading_arr = [1,1,1,2,2,3,3,4,4,5,5,6,7,8,9,10];
            $listening_arr = [1,1,1,1,1,1,1,1,1,1,1,2,2,2,2,3,3,3,3,3,4,4,4,4,4,5,5,5,5,6,6,6,6,7,7,7,8,8,9,9,10];
            $grammar_voca_arr = [1,1,1,1,1,1,1,1,1,1,1,2,2,2,2,3,3,3,3,4,4,5,5,6,6,7,7,8,8,9,10];
            $speaking_arr = [1,1,1,1,1,2,2,2,3,3,3,4,4,4,5,5,6,7,8,9,10];
            $writing_arr = [1,1,1,2,2,3,3,4,4,5,5,6,7,8,9,10];
        }
        elseif($count_score == 2)
        {
            $listening_arr = [5,5,5,5,5,5,5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100,110,115,120,125,130,135,140,145,150,160,165,170,175,180,185,190,195,200,210,215,220,230,240,245,250,255,260,270,275,280,290,295,300,310,315,320,325,330,340,345,350,360,365,370,380,385,390,395,400,405,410,420,425,430,440,445,450,460,465,470,475,480,485,490,495,495,495,495,495,495,495,495,495,495,495];

            $reading_arr = [5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,10,15,20,25,30,35,40,45,50,60,65,70,80,85,90,95,100,110,115,120,125,130,135,140,145,150,160,165,170,175,180,190,195,200,210,220,225,230,235,240,250,255,260,265,270,280,285,290,300,305,310,320,325,330,335,340,350,355,360,365,370,380,385,390,395,400,405,410,415,420,425,430,435,445,450,455,465,470,480,485,490,495,495,495,495];
        }
        else
        {
                if($program -> id == 33)
                {
                    $reading_arr = [2.5,2.5,2.5,2.5,2.5,2.5,2.5,2.5,2.5,3,3,3,3.5,3.5,3.5,4,4,4,4,4.5,4.5,4.5,4.5,5,5,5,5,5.5,5.5,5.5,6,6,6.5,6.5,7,7,7.5,8,8,8.5,9];
                    $writing_arr = [2.5,2.5,2.5,2.5,2.5,2.5,2.5,2.5,2.5,3,3,3,3.5,3.5,3.5,4,4,4,4,4.5,4.5,4.5,4.5,5,5,5,5,5.5,5.5,5.5,6,6,6.5,6.5,7,7,7.5,8,8,8.5,9];
                }
                else
                {
                    $reading_arr = [2.5,2.5,2.5,2.5,2.5,2.5,3,3,3.5,3.5,4,4,4,4.5,4.5,5,5,5,5,5.5,5.5,5.5,5.5,6,6,6,6,6.5,6.5,6.5,7,7,7,7.5,7.5,8,8,8.5,8.5,9,9];
                    $writing_arr = [2.5,2.5,2.5,2.5,2.5,2.5,3,3,3.5,3.5,4,4,4,4.5,4.5,5,5,5,5,5.5,5.5,5.5,5.5,6,6,6,6,6.5,6.5,6.5,7,7,7,7.5,7.5,8,8,8.5,8.5,9,9];
                }

                $listening_arr = [2.5,2.5,2.5,2.5,2.5,2.5,3,3,3.5,3.5,4,4,4,4.5,4.5,4.5,5,5,5.5,5.5,5.5,5.5,5.5,6,6,6,6.5,6.5,6.5,6.5,7,7,7.5,7.5,7.5,8,8,8.5,8.5,9,9];
                $speaking_arr = [2.5,2.5,2.5,2.5,2.5,2.5,3,3,3.5,3.5,4,4,4,4.5,4.5,4.5,5,5,5.5,5.5,5.5,5.5,5.5,6,6,6,6.5,6.5,6.5,6.5,7,7,7.5,7.5,7.5,8,8,8.5,8.5,9,9];
        }


        $percentage_level_list = PercentageLevel::join('level','percentage_level.level_id','=','level.id')
                    ->get();

    	$schedule_list = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->leftJoin('batch','schedule.batch_id','=','batch.id')
		    	->leftJoin('course_capacity','batch.course_capacity_id','=','course_capacity.id')
    			->leftJoin('program','batch.program_id','=','program.id')
    			->leftJoin('course','batch.course_id','=','course.id')
    			->leftJoin('room','schedule.room_id','=','room.id')
    			->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
    			->leftJoin('employee','teacher.employee_id','=','employee.id')
    			->leftJoin('person','employee.person_id','=','person.id')
    			->leftJoin('time as time_in','schedule.time_in_id','=','time_in.id')
    			->leftJoin('time as time_out','schedule.time_out_id','=','time_out.id')
    			->where('batch_student.student_id',$student_id)
                ->where('batch.program_id',$program_id)
                ->where('batch.date_from',$batch -> date_from)
    			->where('batch.date_to',$batch -> date_to)
    			->select(['schedule.id','person.nickname','room.id as room_id','room.room_name','room.room_code','time_out.time as time_out','time_out.time_session as time_out_session','time_in.time as time_in','time_in.time_session as time_in_session','course.id as course_id','course.course_name','schedule.class_id','course_capacity.capacity_name'])
                ->groupBy('time_in.time','time_out.time')
                ->orderBy('schedule.class_id','ASC')
    			->get();

        
        if($examination_id == 1)
        {

            $exam = StudentExaminationScore::where('student_examination_score.student_id',$student_id)
                    ->where('student_examination_score.program_category_id',$program_category_id)
                    ->select(['student_examination_score.examination_id'])
                    ->get()->last();
            if($exam)
            {
                $student_level = StudentExaminationScore::where('student_examination_score.student_id',$student_id)
                    ->where('student_examination_score.examination_id',$exam -> examination_id)
                    ->where('student_examination_score.program_category_id',$program_category_id)
                    ->get();
            }
            else
            {
                $student_level = StudentExaminationScore::where('student_examination_score.student_id',$student_id)
                    ->where('student_examination_score.examination_id',$examination_id)
                    ->where('student_examination_score.program_category_id',$program_category_id)
                    ->get();
            }
        }
        else
        {
            $student_level = StudentExaminationScore::where('student_examination_score.student_id',$student_id)
                ->where('student_examination_score.examination_id',$examination_id)
                ->where('student_examination_score.program_category_id',$program_category_id)
                ->get();
        }

        $total_student_level = 0;

        foreach ($student_level as $level) {
            $total_student_level = $total_student_level + $level -> score;
        }


        foreach($percentage_level_list as $percentage_level)
        {   
            $range_from = $percentage_level -> range_from;
            $range_to = $percentage_level -> range_to;
            $range_from = str_replace('%', '', $range_from);
            $range_to = str_replace('%', '', $range_to);

            if($total_student_level >= $range_from && $range_to >= $total_student_level)
            {
                $total_student_level = $percentage_level -> level_code;
            }
        }

        $total_student_level = str_replace('L', '', $total_student_level);

        $book_arr[0] = "";
        $book_arr[1] = "";
        $book_arr[2] = "";
        $book_arr[3] = "";
        $book_arr[4] = "";
        $book_arr[5] = "";
        $book_arr[6] = "";
        $book_arr[7] = "";
        $book_arr[8] = "";
        $book_arr[9] = "";
        $book_arr[10] = "";
        $book_arr[11] = "";

        $count_book = 0;

        if($program_category_id == 3)
        {

            foreach ($schedule_list as $schedule) {
                if($schedule -> capacity_name == "1:1 Class")
                {
                    $month = round($period / 4);

                    if($month <= 1)
                    {
                        $month = 1;
                    }

                    if($program_id == 7)
                    {
                        $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                            ->where('ielts_book.month',$month)
                            ->where('ielts_book.program_id',$program_id)
                            ->select(['ielts_book.book_title'])
                            ->get()->first();

                    }
                    else if($program_id == 22 || $program_id == 23)
                    {
                        
                        $book = BusinessBook::where('business_book.course_id',$schedule -> course_id)
                            ->where('business_book.month',$month)
                            ->where('business_book.program_id',$program_id)
                            ->select(['business_book.book_title'])
                            ->get()->first();
                    }
                    else if($program_id == 28 || $program_id == 29)
                    {
                        
                        $book = WorkingBook::where('working_book.course_id',$schedule -> course_id)
                            ->where('working_book.month',$month)
                            ->where('working_book.program_id',$program_id)
                            ->select(['working_book.book_title'])
                            ->get()->first();
                    }
                    else
                    {
                        $book = ESLBook::where('esl_book.course_id',$schedule -> course_id)
                                ->where('esl_book.level',$total_student_level)
                                ->select(['esl_book.book_title'])
                                ->get()->first();
                    }

                    if($book)
                    {
                        $book_arr[$schedule -> class_id] = $book -> book_title;
                    }
                    else
                    {
                        $book_arr[$schedule -> class_id] = "";
                    }
                }
                else
                {
                    $book = RoomBook::where('room_book.room_id',$schedule -> room_id)
                            ->where('room_book.class_id',$schedule -> class_id)
                            ->select(['room_book.book_title'])
                            ->get()->first();

                    if($book)
                    {
                        $book_arr[$schedule -> class_id] = $book -> book_title;
                    }
                    else
                    {
                        $book_arr[$schedule -> class_id] = "";
                    }
                }
                $count_book++;
            }
        }
        elseif($program_category_id == 1)
        {
            foreach ($schedule_list as $schedule) {
                $month = round($period / 4);
                if($schedule -> capacity_name == "1:1 Class")
                {
                    if($program_id == 26 || $program_id == 11)
                    {
                        $book = TOEICBook::where('toeic_book.course_id',$schedule -> course_id)
                                ->where('toeic_book.month',$month)
                                ->where('toeic_book.program_id',11)
                                ->select(['toeic_book.book_title'])
                                ->get()->first();
                    }
                    else if($program_id == 27)
                    {
                        $book = TOEICBook::where('toeic_book.course_id',$schedule -> course_id)
                                ->where('toeic_book.month',$month)
                                ->where('toeic_book.program_id',$program_id)
                                ->select(['toeic_book.book_title'])
                                ->get()->first();
                    }
                    else
                    {
                        $book = TOEICBook::where('toeic_book.course_id',$schedule -> course_id)
                                ->where('toeic_book.month',$month)
                                ->where('toeic_book.program_id',$program_id)
                                ->select(['toeic_book.book_title'])
                                ->get()->first();
                    }

                    if($book)
                    {
                        $book_arr[$schedule -> class_id] = "";
                    }
                    else
                    {
                        $book_arr[$schedule -> class_id] = "";
                    }
                }
                else
                {
                    $book = RoomBook::where('room_book.room_id',$schedule -> room_id)
                            ->where('room_book.class_id',$schedule -> class_id)
                            ->select(['room_book.book_title'])
                            ->get()->first();

                    if($book)
                    {
                        $book_arr[$schedule -> class_id] = $book -> book_title;
                    }
                    else
                    {
                        $book_arr[$schedule -> class_id] = "";
                    }

                }
            }

        }
        else
        {
            foreach ($schedule_list as $schedule) {
                if($schedule -> capacity_name == "1:1 Class")
                {

                    $month = round($period / 4);
                    if($program_id == 10 || $program_id == 12)
                    {
                        $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                            ->where('ielts_book.month',$month)
                            ->where('ielts_book.program_id',10)
                            ->select(['ielts_book.book_title'])
                            ->get()->first();
                    }
                    else if($program_id == 32 || $program_id == 33)
                    {
                        $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                            ->where('ielts_book.month',$month)
                            ->where('ielts_book.program_id',10)
                            ->select(['ielts_book.book_title_general as book_title'])
                            ->get()->first();
                    }
                    else if($program_id == 25)
                    {
                        $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                            ->where('ielts_book.month',$month)
                            ->where('ielts_book.program_id',25)
                            ->select(['ielts_book.book_title'])
                            ->get()->first();
                    }
                    else if($program_id == 34)
                    {
                        $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                            ->where('ielts_book.month',$month)
                            ->where('ielts_book.program_id',25)
                            ->select(['ielts_book.book_title_general as book_title'])
                            ->get()->first();
                    }
                    else
                    {
                        $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                            ->where('ielts_book.month',$month)
                            ->where('ielts_book.program_id',$program_id)
                            ->select(['ielts_book.book_title'])
                            ->get()->first();

                        $book_arr[$schedule -> class_id] = "";
                    }

                    if($book)
                    {
                        $book_arr[$schedule -> class_id] = $book -> book_title;
                    }
                    else
                    {
                        $book_arr[$schedule -> class_id] = "";
                    }
                }
                else
                {
                    $book = RoomBook::where('room_book.room_id',$schedule -> room_id)
                            ->where('room_book.class_id',$schedule -> class_id)
                            ->select(['room_book.book_title'])
                            ->get()->first();

                    if($book)
                    {
                        $book_arr[$schedule -> class_id] = $book -> book_title;
                    }
                    else
                    {
                        $book_arr[$schedule -> class_id] = "";
                    }

                }
            }

        }

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 2000);
        ini_set('xdebug.max_nesting_level', 300);

        
        $gender_name = Gender::find($gender_id);
        $nationality = Nationality::find($nationality_id);

    	$data = ["schedule_list" => $schedule_list, 'percentage_level_list' => $percentage_level_list,'student_name' => $student -> sename,'program' => $program,'nickname' => $nickname,'gender_name' => $gender_name,'nationality' => $nationality,'student_id' => $student_id,'batch' => $batch,'date_from' => $date_from,'date_to' => $date_to,'period' => $period,'student_examination_score_list' => $student_examination_score_list,'examination_id' => $examination_id,'student' => $student,'book_arr' => $book_arr,'reading_arr' => $reading_arr,'speaking_arr' => $speaking_arr,'listening_arr' => $listening_arr,'writing_arr' => $writing_arr,'grammar_voca_arr' => $grammar_voca_arr,'level_label' => $level_label,'target_level_label' => $target_level_label,'code' => $code,'count_score' => $count_score];

    	$pdf = \PDF::loadView('student.student_schedule_pdf', $data)->setPaper('a4', 'portrait');
    	return $pdf->stream();
    }

    public function saveAllStudentData(){
        
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 3000);
        ini_set('xdebug.max_nesting_level', 300);

        $student_array = \Input::get('studentJsonArray');
        $max = count($student_array);
        // json_decode($student_array,true);
        // $student_array = var_export($student_array, true);
        // json_decode(serialize($student_array));
        // $max = count($student_array,1);
        // $max = $max/10;
        // $max = count($student_array);
        $student_arr = [];
        $student_arr_count = 0;
        // echo $max;
        for($i = 0; $i < $max; $i++)    
        {

            $student_info_count = StudentInfo::where('student_id',$student_array[$i]['student_id'])->count();
            
            // echo $i." ".$student_info_count."\n";
            // echo $i."".$student_array[$i]['sename']."\n";

            if($student_info_count != 0)
            {   
                
                $student_info = StudentInfo::where('student_id',$student_array[$i]['student_id'])->select(['student_info.id'])->get()->last();

                $update = StudentInfo::find($student_info -> id);
                $update -> state = $student_array[$i]['state'];
                $update -> refund = $student_array[$i]['refund'];
                // $update -> semail = $student_array[$i]['semail'];
                $update -> date_from = $student_array[$i]['abrod_date'];
                $update -> date_to = $student_array[$i]['end_date'];
                $update -> nickname = $student_array[$i]['nick'];
                $update -> gender_id = $student_array[$i]['gender_id'];
                $update -> nationality_id = $student_array[$i]['nationality_id'];
                $update -> period = $student_array[$i]['period'];
                $update -> save();
            }
            else
            {
                
                // $student_arr_count++;
                // $student_arr[$student_arr_count] = array('student_id'=>$student_array[$i]['student_id'],'state' => $student_array[$i]['state'],'sename'=>$student_array[$i]['sename'],'date_from'=>$student_array[$i]['abrod_date'],'date_to'=>$student_array[$i]['end_date'],'nickname'=>$student_array[$i]['nick'],'gender_id'=>$student_array[$i]['gender_id'],'nationality_id'=>$student_array[$i]['nationality_id'],'period'=>$student_array[$i]['period']);

                // $student_arr[$student_arr_count] = array('student_id'=>$student_array[$i]['student_id'],'state' => $student_array[$i]['state'],'sename'=>$student_array[$i]['sename'],'date_from'=>$student_array[$i]['abrod_date'],'date_to'=>$student_array[$i]['end_date'],'nickname'=>$student_array[$i]['nick'],'gender_id'=>$student_array[$i]['gender_id'],'nationality_id'=>$student_array[$i]['nationality_id'],'period'=>$student_array[$i]['period']);
                
                $update = new StudentInfo();
                $update -> student_id = $student_array[$i]['student_id'];
                $update -> sename = $student_array[$i]['sename'];
                $update -> state = $student_array[$i]['state'];
                $update -> refund = $student_array[$i]['refund'];
                $update -> semail = $student_array[$i]['semail'];
                $update -> date_from = $student_array[$i]['abrod_date'];
                $update -> date_to = $student_array[$i]['end_date'];
                $update -> nickname = $student_array[$i]['nick'];
                $update -> gender_id = $student_array[$i]['gender_id'];
                $update -> nationality_id = $student_array[$i]['nationality_id'];
                $update -> period = $student_array[$i]['period'];
                $update -> save();
            }

        }

        // DB::table('student_info')->insert($student_arr);
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 3000);
        ini_set('xdebug.max_nesting_level', 300);

        return response()->json($update);
    }
}
