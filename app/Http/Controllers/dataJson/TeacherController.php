<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\BatchStudent;
use App\Models\ClassComponentCategory;
use App\Models\ClassStandingComponent;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\TeacherSkill;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\TeacherClassification;
use App\Models\TeacherSubject;
use App\Models\TEClass;
use App\Models\EmployeeType;
use App\Models\PercentageLevel;
use App\Models\Program;
use App\Models\PercentageDistribution;
use App\Models\IELTSBook;
use App\Models\ESLBook;
use App\Models\BusinessBook;
use App\Models\WorkingBook;
use App\Models\TOEICBook;
use App\Models\RoomBook;
use App\Models\StudentExaminationScore;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TeacherController extends BaseController {
   

    public function indexReport()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        return view('scheduler/report.teacher_report',compact('gen_role'));
    }

    public function dataJson(){
      $condition = \Input::all();
      $query = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                ->leftJoin('person','employee.person_id','=','person.id')
                ->leftJoin('gen_user', 'gen_user.person_id', '=', 'person.id')
                ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                ->select('teacher.id', 'teacher.employee_id','person.first_name','person.middle_name','person.last_name')
                ->groupBy('teacher.employee_id');
     
     // print $condition["query"];
      //$query->where('last_name', 'LIKE', "%get%");  


          foreach($condition as $column => $value)
          {
            if($column == 'query')
            {
              $query->where('first_name', 'LIKE', "%$value%")
                    ->where('person.is_active',1)
                    ->where('teacher.id','!=',0)
                    ->where('gen_user_role.gen_role_id',1)
                    // ->orWhere('middle_name', 'LIKE', "%$value%")
                    // ->orWhere('last_name', 'LIKE', "%$value%")
                    // ->orWhere('employee_id', 'LIKE', "%$value%")
                    ->orWhere(function($query) use($value){
                            $query->where('person.middle_name', 'LIKE', "%$value%")
                                  ->where('person.is_active',1)
                                  ->where('teacher.id','!=',0)
                                  ->where('gen_user_role.gen_role_id',1);
                          })
                    ->orWhere(function($query) use($value){
                            $query->where('last_name', 'LIKE', "%$value%")
                                  ->where('person.is_active',1)
                                  ->where('teacher.id','!=',0)
                                  ->where('gen_user_role.gen_role_id',1);
                          })
                    ->orWhere(function($query) use($value){
                        $query->where('employee_id', 'LIKE', "%$value%")
                              ->where('person.is_active',1)
                              ->where('teacher.id','!=',0)
                              ->where('gen_user_role.gen_role_id',1);
                          });
            }
            else
            {
              $query->where($column, '=', $value);
            }
          }

          $teacher = $query->orderBy('last_name','ASC')->get();

          return response()->json($teacher);
    }

    public function teacherDataJson(){
      $date = \Input::get('date');
      $date_forward = date("Y-m-d", mktime(0, 0, 0, date("m",strtotime($date)), date("d",strtotime($date))+5, date("Y",strtotime($date))));

      $value = \Input::get('query');
      $query = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                ->join('person','employee.person_id','=','person.id')
                ->select(['teacher.id', 'teacher.employee_id','person.first_name','person.middle_name','person.last_name','person.nickname'])
                ->groupBy('teacher.employee_id');

              $query->where('first_name', 'LIKE', "%$value%")
                    ->orWhere('middle_name', 'LIKE', "%$value%")
                    ->orWhere('last_name', 'LIKE', "%$value%")
                    ->orWhere('employee_id', 'LIKE', "%$value%")
                    ->orWhere('nickname', 'LIKE', "%$value%");

          $teacher_list = $query->orderBy('last_name','ASC')->get();

          $teacher = [];
          $teacher_data_list = [];
          $counter = 0;
          foreach ($teacher_list as $teacher) 
          {
            // $scheduler_count = Schedule::where('schedule.teacher_id',$teacher -> id)
            //             ->where('schedule.date','>',$date)
            //             ->where('schedule.date','<=',$date_forward)
            //             ->count();

            // if($scheduler_count == 0)
            // {   
            //     // $teacher[$counter] = ["id" => $teacher ->id,"first_name" => $teacher -> first_name,"middle_name" => $teacher -> middle_name,"last_name" => $teacher -> last_name];

                $teacher[$counter] = ["teacher_id" => $teacher ->id,"first_name" => $teacher -> first_name,"middle_name" => $teacher -> middle_name,"last_name" => $teacher -> last_name,"nickname" => $teacher -> nickname];
                array_push($teacher_data_list, $teacher[$counter]);
                $counter++;
            // }
          }
          // print_r($teacher);
          // print_r($teacher_data_list);

          return response()->json($teacher_data_list);
    }

    public function scheduleDataJson(){

      $condition = \Input::all();
      // $query = TeacherClassification::join('teacher','teacher_classification.teacher_id','=','teacher.id')
      //                               ->join('employee','teacher.employee_id','=','employee.id')
      //                               ->join('person','employee.person_id','=','person.id');

      $query = TeacherSubject::join('teacher','teacher_subject.teacher_id','=','teacher.id')
                                    ->join('employee','teacher.employee_id','=','employee.id')
                                    ->join('person','employee.person_id','=','person.id');

      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      
      $query->where('teacher.is_active','=','1');
      $room = $query->select(['teacher.id as value','person.last_name','person.first_name'])->groupBy('teacher.id')->get();

      return response()->json($room);
    }

    // public function BEclassStandingJson(){
    //   $query = ClassComponentCategory::where('education_category_id', '=', 1)
    //             ->join('education_category', 'class_component_category.education_category_id', '=', 'education_category.id')
    //             // ->join('class_standing_component', 'class_standing_component.class_component_category_id', '=', 'class_component_category.id')
    //             ->select('class_component_category.id', 'class_component_category.class_component_category_name');

    //   $class_component_category = $query->select(['class_component_category.id as id','class_component_category.class_component_category_name as name'])->get();

    //   return response()->json($class_component_category);

    // }

    public function BEclassStandingJson(){
      $subject_id = \Input::get('subject_id');
      $classification_id = \Input::get('classification_id');

      $query = PercentageDistribution::join('class_component_category', 'percentage_distribution.class_component_category_id', '=', 'class_component_category.id')
              ->where('percentage_distribution.subject_id','=', $subject_id)
              ->where('percentage_distribution.classification_id','=', $classification_id)
              ->select();

      $class_component_category = $query->select(['percentage_distribution.id as id','class_component_category.class_component_category_name as name','percentage_distribution.percentage as component_weight'])->get();

      return response()->json($class_component_category);

    }

    public function TEclassStandingJson(){
      $query = ClassComponentCategory::where('education_category_id', '=', 2)
                ->join('education_category', 'class_component_category.education_category_id', '=', 'education_category.id')
                // ->join('class_standing_component', 'class_standing_component.class_component_category_id', '=', 'class_component_category.id')
                ->select('class_component_category.id', 'class_component_category.class_component_category_name');

      $class_component_category = $query->select(['class_component_category.id as id','class_component_category.class_component_category_name as name'])->get();

      return response()->json($class_component_category);
    }


   public function classComponentWeight(){
    $class_id = \Input::get('class_id');
    $grading_period_id = \Input::get('grading_period_id');
      $query = ClassStandingComponent::where('class_standing_component.class_id', '=', $class_id)
              ->where('class_standing_component.grading_period_id', '=', $grading_period_id)
              ->join('class_component_category', 'class_standing_component.class_component_category_id', '=', 'class_component_category.id')
              ->select();

      $class_standing_component = $query->select(['class_standing_component.id as id','class_component_category.class_component_category_name as name','class_standing_component.component_weight as component_weight'])->get();

      return response()->json($class_standing_component);
    }

    public function teacherSchedulePdf(){

        $date = \Input::get('date');
        $employee_id = \Input::get('employee_id');
        $user_id = Auth::user()->id;

        // $person = GenUser::where('gen_user.id',$user_id)->select(['gen_user.person_id'])->get()->last();

        $teacher = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                    ->leftJoin('person','employee.person_id','=','person.id')
                    ->leftJoin('gender','person.gender_id','=','gender.id')
                    ->leftJoin('room','employee.room_id','=','room.id')
                    ->leftJoin('position','employee.position_id','=','position.id')
                    ->leftJoin('department','position.department_id','=','department.id')
                    // ->where('employee.person_id',$person -> person_id)
                    ->where('employee.id',$employee_id)
                    ->select(['teacher.id','teacher.employee_id','person.nickname','person.last_name','person.first_name','person.middle_name','gender.gender_name','room.room_name','department.department_name'])->get()->last();

        $program = "";

        $teacher_skill = TeacherSkill::leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                ->leftJoin('program_category','program_skill.program_category_id','=','program_category.id')
                ->where('teacher_skill.employee_id',$teacher -> employee_id)
                ->where('program_skill.is_active',1)
                ->select(['program_skill.skill_name','program_category.program_category_name'])
                ->get();

      $batch = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->leftJoin('batch','schedule.batch_id','=','batch.id')
                ->leftJoin('level','batch.level_id','=','level.id')
                ->where('schedule.teacher_id',$teacher -> id)
          // ->where('schedule.date',$date)
                ->where('batch.date_from','<=',$date)
                ->where('batch.date_to','>=',$date)
                ->select(['batch.date_from','batch.date_to','level.level_code'])
                ->get()->last();

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
                ->leftJoin('student_info','batch_student.student_id','=','student_info.student_id')
                ->leftJoin('nationality','student_info.nationality_id','=','nationality.id')
                ->where('schedule.teacher_id',$teacher -> id)
          // ->where('schedule.date',$date)
                ->where('batch.date_from','<=',$date)
                ->where('batch.date_to','>=',$date)
                ->select(['schedule.id','batch.program_id','program.program_name','batch_student.student_id','student_info.sename','student_info.nickname','program.program_category_id','nationality.nationality_name','batch_student.student_english_name','room.room_name','room.room_code','time_out.time as time_out','time_out.time_session as time_out_session','time_in.time as time_in','time_in.time_session as time_in_session','course.course_name','schedule.class_id','schedule.room_id','course_capacity.capacity_name','batch.course_capacity_id','batch.date_from','batch.date_to','batch.course_id'])
                      ->groupBy('time_in.time','time_out.time')
                      ->orderBy('schedule.class_id','ASC')
                ->get();

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

      $level_arr[0] = "";
      $level_arr[1] = "";
      $level_arr[2] = "";
      $level_arr[3] = "";
      $level_arr[4] = "";
      $level_arr[5] = "";
      $level_arr[6] = "";
      $level_arr[7] = "";
      $level_arr[8] = "";
      $level_arr[9] = "";
      $level_arr[10] = "";
      $level_arr[11] = "";

      $percentage_level_list = PercentageLevel::join('level','percentage_level.level_id','=','level.id')
                    ->get();


      foreach ($schedule_list as $schedule) {

          $program = Program::find($schedule -> program_id);
          $period = 0;
          $examination_id = 0;
          $diff = strtotime($batch -> date_to, 0) - strtotime($batch -> date_from, 0);
          $period = round($diff / 604800);

          if($period <= 1)
          {
              $period = 1;
          }

          $student_examination_score_data = StudentExaminationScore::where('student_examination_score.student_id',$schedule -> student_id)
                    ->select(['student_examination_score.examination_id'])
                    ->get()
                    ->last();

          if($student_examination_score_data)
          {
              $examination_id = $student_examination_score_data -> examination_id;
          }
          else
          {
              $examination_id == 1;
          }

          if($examination_id == 1)
          {

              $exam = StudentExaminationScore::where('student_examination_score.student_id',$schedule -> student_id)
                      ->where('student_examination_score.program_category_id',$schedule -> program_category_id)
                      ->select(['student_examination_score.examination_id'])
                      ->get()->last();

              $student_level = StudentExaminationScore::leftJoin('examination_type','student_examination_score.examination_type_id','=','examination_type.id')
                  ->where('student_examination_score.student_id',$schedule -> student_id)
                  ->where('student_examination_score.examination_id',$exam -> examination_id)
                  ->where('student_examination_score.program_category_id',$schedule -> program_category_id)
                  ->get();
          }
          else
          {
              $student_level = StudentExaminationScore::leftJoin('examination_type','student_examination_score.examination_type_id','=','examination_type.id')
                  ->where('student_examination_score.student_id',$schedule -> student_id)
                  ->where('student_examination_score.examination_id',$examination_id)
                  ->where('student_examination_score.program_category_id',$schedule -> program_category_id)
                  ->get();
          }


          $total_student_level = 0;
          $count_score = 0;
          foreach ($student_level as $level) {
              $count_score++;
          }

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


          foreach ($student_level as $level) {
              if(strpos($schedule -> course_name, $level -> examination_type_name) !== false)
              { 
                if($level -> examination_type_id == 1)
                {
                  $total_student_level = $listening_arr[$total_student_level + $level -> score];
                }
                else if($level -> examination_type_id == 2)
                {
                  $total_student_level = $grammar_voca_arr[$total_student_level + $level -> score];
                }
                else if($level -> examination_type_id == 3)
                {
                  $total_student_level = $reading_arr[$total_student_level + $level -> score];
                }
                else if($level -> examination_type_id == 4)
                {
                  $total_student_level = $writing_arr[$total_student_level + $level -> score];
                }
                else if($level -> examination_type_id == 5)
                {
                  $total_student_level = $speaking_arr[$total_student_level + $level -> score];
                }
                else
                {

                }

              }
          }
          if($count_score == 5)
          {
            $level_arr[$schedule -> class_id] = "L".$total_student_level;
          }
          else if($count_score == 2)
          {
            $level_arr[$schedule -> class_id] = $total_student_level;
          }
          else
          {
            $level_arr[$schedule -> class_id] = $total_student_level;
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

          if($schedule -> program_category_id == 3)
          {
              if($schedule -> capacity_name == "1:1 Class")
              {

                  $month = round($period / 4);
                  if($schedule -> program_id == 7)
                  {
                      $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                          ->where('ielts_book.month',$month)
                          ->where('ielts_book.program_id',$schedule -> program_id)
                          ->select(['ielts_book.book_title'])
                          ->get()->first();
                  }
                  else if($schedule -> program_id == 22 || $schedule -> program_id == 23)
                  {
                      
                      $book = BusinessBook::where('business_book.course_id',$schedule -> course_id)
                          ->where('business_book.month',$month)
                          ->where('business_book.program_id',$schedule -> program_id)
                          ->select(['business_book.book_title'])
                          ->get()->first();
                  }
                  else if($schedule -> program_id == 28 || $schedule -> program_id == 29)
                  {
                      
                      $book = WorkingBook::where('working_book.course_id',$schedule -> course_id)
                          ->where('working_book.month',$month)
                          ->where('working_book.program_id',$schedule -> program_id)
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
              
          }
          elseif($schedule -> program_category_id == 1)
          {
              $month = round($period / 4);
              if($schedule -> capacity_name == "1:1 Class")
              {
                  if($schedule -> program_id == 26 || $schedule -> program_id == 11)
                  {
                      $book = TOEICBook::where('toeic_book.course_id',$schedule -> course_id)
                              ->where('toeic_book.month',$month)
                              ->where('toeic_book.program_id',11)
                              ->select(['toeic_book.book_title'])
                              ->get()->first();
                  }
                  else if($schedule -> program_id == 27)
                  {
                      $book = TOEICBook::where('toeic_book.course_id',$schedule -> course_id)
                              ->where('toeic_book.month',$month)
                              ->where('toeic_book.program_id',$schedule -> program_id)
                              ->select(['toeic_book.book_title'])
                              ->get()->first();
                  }
                  else
                  {
                      $book = TOEICBook::where('toeic_book.course_id',$schedule -> course_id)
                              ->where('toeic_book.month',$month)
                              ->where('toeic_book.program_id',$schedule -> program_id)
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
          else
          {
              if($schedule -> capacity_name == "1:1 Class")
              {

                  $month = round($period / 4);
                  if($schedule -> program_id == 10 || $schedule -> program_id == 12)
                  {
                      $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                          ->where('ielts_book.month',$month)
                          ->where('ielts_book.program_id',10)
                          ->select(['ielts_book.book_title'])
                          ->get()->first();
                  }
                  else if($schedule -> program_id == 32 || $schedule -> program_id == 33)
                  {
                      $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                          ->where('ielts_book.month',$month)
                          ->where('ielts_book.program_id',10)
                          ->select(['ielts_book.book_title_general as book_title'])
                          ->get()->first();
                  }
                  else if($schedule -> program_id == 25)
                  {
                      $book = IELTSBook::where('ielts_book.course_id',$schedule -> course_id)
                          ->where('ielts_book.month',$month)
                          ->where('ielts_book.program_id',25)
                          ->select(['ielts_book.book_title'])
                          ->get()->first();
                  }
                  else if($schedule -> program_id == 34)
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
                          ->where('ielts_book.program_id',$schedule -> program_id)
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

      // exit();
      $data = ["schedule_list" => $schedule_list,'teacher_name' => $teacher -> nickname,'teacher' => $teacher,'program' => $program,'batch' => $batch,'teacher_skill' => $teacher_skill,'book_arr' => $book_arr,'level_arr' => $level_arr];

      $pdf = \PDF::loadView('teacher_portal.teacher_schedule_pdf', $data)->setPaper('letter', 'landscape');
      return $pdf->stream();
    }



}
