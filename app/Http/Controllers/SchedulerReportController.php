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
use App\Models\Employee;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\EventModel;
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
use App\Models\SubjectOffered;
use App\Models\Time;
use App\Models\Teacher;
use App\Models\TEClass;
use App\Models\Term;
use App\Http\Requests\SchedulerRequest;
use App\Http\Requests\SchedulerEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;
use Excel;

class SchedulerReportController extends SchedulerMainController {
   

    /*
    return a list of country in json format based on a term
    **/

    public function dailySchedules()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        return view('scheduler/report/daily_schedules',compact('gen_role'));
    }

    public function getTimeByOrderNo($orderNo, $timeJsonArray)
    {
        $time = null; 

        foreach ($timeJsonArray as $timedata) {
            if($orderNo == $timedata -> order_no){  
              $time = $timedata; 
            }

        }

      return $time;
    }

    public function dailySchedulesExcel()
    {
        Excel::create('Employee List', function($excel) {

            $time_list = Time::where('time.is_active',1)->get();
            $employee_list = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                                    ->leftJoin('person','employee.person_id','=','person.id')
                                    ->leftJoin('room','employee.room_id','=','room.id')
                                    ->select(['teacher.id','person.last_name','person.first_name','person.nickname','room.room_name'])
                                    ->orderBy('room.room_name','DESC')
                                    ->get();

            $counter =  0;
            $count = 0;

            $time_arr = ["",""];

            foreach ($time_list as $time) {

                  $count++; 
                  $timeStart = $time;
                  $timeEnd = $this->getTimeByOrderNo($timeStart -> order_no + 1, $time_list);

                  if($time -> order_no % 2 > 0)
                  {
                      array_push($time_arr, $timeStart -> time." - ". $timeEnd -> time);
                  }
            }

            $row = 1;

            $date = \Input::get('date');

            $excel->sheet('One on One', function($sheet) use($employee_list,$row,$time_arr,$date) {     
                $time_arr2 = ["","TUTOR"];
                $sheet->setAllBorders('thin');

                for ($i=1; $i <= 10; $i++) { 
                    array_push($time_arr2,'Period '.$i);
                }
                
                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                    $cells->setBackground('#C0C0C0');
                });

                $sheet->setHeight($row, 25);
                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                $sheet->row($row++, $time_arr);

                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                    $cells->setBackground('#C0C0C0');
                });

                $sheet->setHeight($row, 15);
                $sheet->cells('A'.$row.':L'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                $sheet->row($row++, $time_arr2);

                $sheet->setWidth('A', 5);
                $sheet->setWidth('B', 15);
                $sheet->setWidth('C', 15);
                $sheet->setWidth('D', 15);
                $sheet->setWidth('E', 15);
                $sheet->setWidth('F', 15);
                $sheet->setWidth('G', 15);
                $sheet->setWidth('H', 15);
                $sheet->setWidth('I', 15);
                $sheet->setWidth('J', 15);
                $sheet->setWidth('K', 15);
                $sheet->setWidth('L', 15);


                $employee_list = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                          ->leftJoin('room','employee.room_id','=','room.id')
                          ->leftJoin('program','employee.program_id','=','program.id')
                          ->leftJoin('person','employee.person_id','=','person.id')
                          ->orderBy('room.id')
                          ->select(['room.room_name','person.nickname','teacher.id','program.program_color'])
                          ->get();

                $sheet->setFreeze('M3');

                foreach ($employee_list as $employee) {

                    $employee_color = "#FFFFFF";
                    $column_arr = ['1'=>'C','2'=>'D','3'=>'E','4'=>'F','5'=>'G','6'=>'H','7'=>'I','8'=>'J','9'=>'K','10'=>'L'];
                    $blank_arr = ['',''];
                    $schedule_arr = [$employee -> room_name,$employee -> nickname];

                    if($employee -> program_color != "" && $employee -> program_color != null)
                    {
                        $employee_color = $employee -> program_color;
                    }

                    $capacity_arr = ['1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'','9'=>'','10'=>''];
                    $period_arr = ['1'=>'Vacant','2'=>'Vacant','3'=>'Vacant','4'=>'Vacant','5'=>'Vacant','6'=>'Vacant','7'=>'Vacant','8'=>'Vacant','9'=>'Vacant','10'=>'Vacant'];
                    $color_arr = ['1'=>'#00FFFF','2'=>'#00FFFF','3'=>'#00FFFF','4'=>'#00FFFF','5'=>'#00FFFF','6'=>'#00FFFF','7'=>'#00FFFF','8'=>'#00FFFF','9'=>'#00FFFF','10'=>'#00FFFF'];
                    $period_arr1 = ['1'=>'Vacant','2'=>'Vacant','3'=>'Vacant','4'=>'Vacant','5'=>'Vacant','6'=>'Vacant','7'=>'Vacant','8'=>'Vacant','9'=>'Vacant','10'=>'Vacant'];

                    $schedule_list = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                                      ->join('batch','batch_student.batch_id','=','batch.id')
                                      ->join('level','batch.level_id','=','level.id')
                                      ->join('course_capacity','batch.course_capacity_id','=','course_capacity.id')
                                      ->join('program','batch.program_id','=','program.id')
                                      ->where('schedule.teacher_id',$employee -> id)
                                      ->where('schedule.date',$date)
                                      ->select(['level.level_code','schedule.class_id','course_capacity.capacity_name','batch_student.student_english_name','program.program_color'])
                                      ->get();

                    $schedule_count = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                                      ->where('schedule.teacher_id',$employee -> id)
                                      ->where('schedule.date',$date)
                                      ->count();

                    $data = [];
                    $data1 = [];
                    $data2 = [];

                    if($schedule_count)
                    {
                        foreach ($schedule_list as $schedule) 
                        {
                            $data = [$schedule -> class_id => $schedule -> capacity_name];
                            $data1 = [$schedule -> class_id => $schedule -> student_english_name."(".$schedule -> level_code.")"];
                            $data2 = [$schedule -> class_id => $schedule -> program_color];

                            $data = array_replace($capacity_arr,$data);
                            $data1 = array_replace($period_arr,$data1);
                            $data2 = array_replace($color_arr,$data2);
                        }
                    }
                    else
                    {
                        foreach ($period_arr as $period_arr_data => $value) 
                        { 
                            $data = [$period_arr_data => ''];
                            $data1 = [$period_arr_data => 'Vacant'];
                            $data2 = [$period_arr_data => '#00FFFF'];

                            $data = array_replace($capacity_arr,$data);
                            $data1 = array_replace($period_arr,$data1);
                            $data2 = array_replace($color_arr,$data2);
                        }
                    }


                    $arr = array_merge($schedule_arr,$data);
                    $arr1 = array_merge($blank_arr,$data1);
                    $arr2 = array_merge($blank_arr,$data2);

                    $sheet->setHeight($row, 15);
                    $count_color = 0;

                    foreach ($data2 as $arr2_data => $value) 
                    { 
                        $count_color++;
                        if($value != "")
                        {
                            $sheet->cells($column_arr[$count_color].''.$row.':'.$column_arr[$count_color].''.$row, function($cells) use ($value) {
                                  $cells->setFontColor('#FF0000');
                            });
                        }
                    }

                    $sheet->cells('B'.$row, function($cells) use($employee_color){
                        $cells->setBackground($employee_color);
                    });

                    $sheet->row($row++, $arr);

                    $prev_row = $row - 1;
                    $sheet->mergeCells('A'.$prev_row.':A'.$row);
                    $sheet->mergeCells('B'.$prev_row.':B'.$row);

                    $sheet->setHeight($row, 25);
                    $count_color = 0;
                    foreach ($data2 as $arr2_data => $value) 
                    { 
                        $count_color++;
                        if($value != "")
                        {
                            $sheet->cells($column_arr[$count_color].''.$row.':'.$column_arr[$count_color].''.$row, function($cells) use ($value) {
                                $cells->setBackground($value);
                                $cells->setFontSize(8);
                                $cells->setFontWeight('bold');
                            });
                        }
                        else
                        {
                            $sheet->cells($column_arr[$count_color].''.$row.':'.$column_arr[$count_color].''.$row, function($cells) use ($value) {
                                  $cells->setFontColor('#00FFFF');
                                  $cells->setFontSize(8);
                                  $cells->setFontWeight('bold');
                            });
                        }
                    }

                    $sheet->row($row++, $arr1);

                    $sheet->cells('A3:A'.$row, function($cells) {
                        $cells->setBackground('#C0C0C0');
                    });

                    $sheet->cells('A'.$prev_row.':L'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                }
            });

            $time_arr = [""];

            foreach ($time_list as $time) {

                  $count++; 
                  $timeStart = $time;
                  $timeEnd = $this->getTimeByOrderNo($timeStart -> order_no + 1, $time_list);

                  if($time -> order_no % 2 > 0)
                  {
                      array_push($time_arr, $timeStart -> time." - ". $timeEnd -> time);
                  }
            }

            $excel->sheet('Group Class', function($sheet) use($employee_list,$time_arr,$date) {

                $row = 1;
                $time_arr2 = ["Room"];
                $sheet->setAllBorders('thin');

                for ($i=1; $i <= 10; $i++) { 
                    array_push($time_arr2,'Period '.$i);
                }
                
                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                    $cells->setBackground('#C0C0C0');
                });

                $sheet->setHeight($row, 25);
                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                $sheet->row($row++, $time_arr);

                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                    $cells->setBackground('#C0C0C0');
                });

                $sheet->setHeight($row, 15);
                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                $sheet->row($row++, $time_arr2);

                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 15);
                $sheet->setWidth('C', 15);
                $sheet->setWidth('D', 15);
                $sheet->setWidth('E', 15);
                $sheet->setWidth('F', 15);
                $sheet->setWidth('G', 15);
                $sheet->setWidth('H', 15);
                $sheet->setWidth('I', 15);
                $sheet->setWidth('J', 15);
                $sheet->setWidth('K', 15);

                $room_list = Room::join('course_capacity','room.course_capacity_id','=','course_capacity.id')
                          ->where('room.course_capacity_id',8)
                          ->orderBy('room.id')
                          ->select(['room.id','room.room_name','room.room_capacity'])
                          ->get();

                $sheet->setFreeze('L3');

                foreach ($room_list as $room) {

                    $employee_color = "#FFFFFF";
                    $column_arr = ['1'=>'B','2'=>'C','3'=>'D','4'=>'E','5'=>'F','6'=>'G','7'=>'H','8'=>'I','9'=>'J','10'=>'K'];
                    $blank_arr = [''];
                    $schedule_arr = [$room -> room_name];

                    $capacity_arr = ['1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'','9'=>'','10'=>''];
                    $period_arr = ['1'=>'Vacant','2'=>'Vacant','3'=>'Vacant','4'=>'Vacant','5'=>'Vacant','6'=>'Vacant','7'=>'Vacant','8'=>'Vacant','9'=>'Vacant','10'=>'Vacant'];
                    $color_arr = ['1'=>'#00FFFF','2'=>'#00FFFF','3'=>'#00FFFF','4'=>'#00FFFF','5'=>'#00FFFF','6'=>'#00FFFF','7'=>'#00FFFF','8'=>'#00FFFF','9'=>'#00FFFF','10'=>'#00FFFF'];
                    $period_arr1 = ['1'=>'Vacant','2'=>'Vacant','3'=>'Vacant','4'=>'Vacant','5'=>'Vacant','6'=>'Vacant','7'=>'Vacant','8'=>'Vacant','9'=>'Vacant','10'=>'Vacant'];

                    $schedule_list = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                                      ->join('batch','batch_student.batch_id','=','batch.id')
                                      ->join('level','batch.level_id','=','level.id')
                                      ->join('course_capacity','batch.course_capacity_id','=','course_capacity.id')
                                      ->join('program','batch.program_id','=','program.id')
                                      ->where('schedule.room_id',$room -> id)
                                      ->where('schedule.date',$date)
                                      ->select(['level.level_code','schedule.class_id','course_capacity.capacity_name','batch_student.student_english_name','program.program_color'])
                                      ->get();

                    $schedule_count = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                                      ->where('schedule.room_id',$room -> id)
                                      ->where('schedule.date',$date)
                                      ->count();

                    $sheet->setHeight($row, 15);

                    $room_capacity_data = $room -> room_capacity - 1;

                    $sheet->getStyle('A'.$row)->getAlignment()->setWrapText(true);
                    $sheet->row($row, $schedule_arr);

                    $data_row = $row;

                    foreach ($schedule_list as $schedule) {

                            $sheet->cell($column_arr[$schedule -> class_id].''.$data_row, function($cell) use($schedule){
                                $cell->setValue($schedule -> student_english_name."(".$schedule -> level_code.")");
                                $cell->setBackground($schedule -> program_color);
                                $cell->setFontSize(8);
                                $cell->setFontWeight('bold');

                            });

                            $data_row++;
                    }

                    $row+=$room_capacity_data;

                    $prev_row = $row - $room_capacity_data;
                    $sheet->mergeCells('A'.$prev_row.':A'.$row);

                    // $sheet->setHeight($row, 25);


                    $sheet->cells('A3:A'.$row, function($cells) {
                        $cells->setBackground('#FF8080');
                    });

                    $row++;
                    $sheet->cells('A'.$prev_row.':L'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                }
            });


            $excel->sheet('Activity Class', function($sheet) use($employee_list,$time_arr,$date) {

                $row = 1;
                $time_arr2 = ["Room"];
                $sheet->setAllBorders('thin');

                for ($i=1; $i <= 10; $i++) { 
                    array_push($time_arr2,'Period '.$i);
                }
                
                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                    $cells->setBackground('#C0C0C0');
                });

                $sheet->setHeight($row, 25);
                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                $sheet->row($row++, $time_arr);

                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                    $cells->setBackground('#C0C0C0');
                });

                $sheet->setHeight($row, 15);
                $sheet->cells('A'.$row.':K'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                $sheet->row($row++, $time_arr2);

                $sheet->setWidth('A', 10);
                $sheet->setWidth('B', 17);
                $sheet->setWidth('C', 17);
                $sheet->setWidth('D', 17);
                $sheet->setWidth('E', 17);
                $sheet->setWidth('F', 17);
                $sheet->setWidth('G', 17);
                $sheet->setWidth('H', 17);
                $sheet->setWidth('I', 17);
                $sheet->setWidth('J', 17);
                $sheet->setWidth('K', 17);

                $room_list = Room::join('course_capacity','room.course_capacity_id','=','course_capacity.id')
                          ->where('room.course_capacity_id',5)
                          ->orderBy('room.id')
                          ->select(['room.id','room.room_name','room.room_capacity'])
                          ->get();

                $sheet->setFreeze('L3');

                foreach ($room_list as $room) {

                    $employee_color = "#FFFFFF";
                    $column_arr = ['1'=>'B','2'=>'C','3'=>'D','4'=>'E','5'=>'F','6'=>'G','7'=>'H','8'=>'I','9'=>'J','10'=>'K'];
                    $blank_arr = [''];
                    $schedule_arr = [$room -> room_name];

                    $capacity_arr = ['1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'','9'=>'','10'=>''];
                    $period_arr = ['1'=>'Vacant','2'=>'Vacant','3'=>'Vacant','4'=>'Vacant','5'=>'Vacant','6'=>'Vacant','7'=>'Vacant','8'=>'Vacant','9'=>'Vacant','10'=>'Vacant'];
                    $color_arr = ['1'=>'#00FFFF','2'=>'#00FFFF','3'=>'#00FFFF','4'=>'#00FFFF','5'=>'#00FFFF','6'=>'#00FFFF','7'=>'#00FFFF','8'=>'#00FFFF','9'=>'#00FFFF','10'=>'#00FFFF'];
                    $period_arr1 = ['1'=>'Vacant','2'=>'Vacant','3'=>'Vacant','4'=>'Vacant','5'=>'Vacant','6'=>'Vacant','7'=>'Vacant','8'=>'Vacant','9'=>'Vacant','10'=>'Vacant'];

                    $schedule_list = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                                      ->join('batch','batch_student.batch_id','=','batch.id')
                                      ->join('level','batch.level_id','=','level.id')
                                      ->join('course_capacity','batch.course_capacity_id','=','course_capacity.id')
                                      ->join('program','batch.program_id','=','program.id')
                                      ->where('schedule.room_id',$room -> id)
                                      ->where('schedule.date',$date)
                                      ->select(['level.level_code','schedule.class_id','course_capacity.capacity_name','batch_student.student_english_name','program.program_color'])
                                      ->get();

                    $schedule_count = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
                                      ->where('schedule.room_id',$room -> id)
                                      ->where('schedule.date',$date)
                                      ->count();

                    $sheet->setHeight($row, 20);

                    $room_capacity_data = $room -> room_capacity - 1;

                    $sheet->getStyle('A'.$row)->getAlignment()->setWrapText(true);
                    $sheet->row($row, $schedule_arr);

                    $data_row = $row;

                    $row+=$room_capacity_data;

                    $prev_row = $row - $room_capacity_data;
                    $sheet->mergeCells('A'.$prev_row.':A'.$row);

                    // $sheet->setHeight($row, 25);


                    $sheet->cells('A3:A'.$row, function($cells) {
                        $cells->setBackground('#FF8080');
                    });
                    $sheet->cells('B3:B'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('C3:C'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('D3:D'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('E3:E'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('F3:F'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('G3:G'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('H3:H'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('I3:I'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('J3:J'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });
                    $sheet->cells('K3:K'.$row, function($cells) {
                        $cells->setBackground('#00FFFF');
                    });


                    foreach ($schedule_list as $schedule) {

                            $sheet->cell($column_arr[$schedule -> class_id].''.$data_row, function($cell) use($schedule){
                                $cell->setValue($schedule -> student_english_name."(".$schedule -> level_code.")");
                                $cell->setBackground($schedule -> program_color);
                                $cell->setFontSize(8);
                                $cell->setFontWeight('bold');
                            });

                            $data_row++;
                    }
                    
                    $row++;
                    $sheet->cells('A'.$prev_row.':L'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                    });

                }
            });
        })->export('xls');
    }

}
