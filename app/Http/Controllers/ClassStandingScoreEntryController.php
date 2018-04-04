<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\AttendanceRemark;
use App\Models\ClassStandingComponentDetail;
use App\Models\ClassStandingScoreEntry;
use App\Models\EnrollmentClass;
use App\Http\Requests\ClassStandingScoreEntryEditRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;
use Input;

class ClassStandingScoreEntryController extends TeachersPortalMainController {

    public function dataJson(){

      $condition = \Input::all();
      $query = ClassStandingScoreEntry::leftJoin('student', 'class_standing_score_entry.student_id', '=', 'student.id')
                ->leftJoin('person', 'student.person_id', '=', 'person.id')
                ->leftJoin('attendance_remark', 'class_standing_score_entry.attendance_remarks_id', '=', 'attendance_remark.id')
                ->select(['class_standing_score_entry.id', 'student.student_no','person.last_name', 'person.first_name','person.middle_name', 'class_standing_score_entry.score', 'attendance_remark.attendance_remarks_code']);
      
      foreach($condition as $column => $value)
      {
        if($column == 'query')
        {
          $query->where('student_no', 'LIKE', "%$value%")
                ->orWhere('last_name', 'LIKE', "%$value%")
                ->orWhere('first_name', 'LIKE', "%$value%")
                ->orWhere('middle_name', 'LIKE', "%$value%")
                ->orWhere('score', 'LIKE', "%$value%")
                ->orWhere('attendance_remarks_code', 'LIKE', "%$value%");
        }
        else
        {
          $query->where($column, '=', $value);
        }

          $query->where($column, '=', $value);    
      }

      $class_standing_score_entry = $query->orderBy('class_standing_score_entry.id','ASC')->get();

      return response()->json($class_standing_score_entry);


      $result = ClassStandingScoreEntry::get();
      return response()->json($result);
    }
    
    public function dataJsonAttendance(){

      $condition = \Input::all();
      $class_standing_component_id = \Input::get('class_standing_component_id');
      $class_id = \Input::get('class_id');

      $attendance_id_arr = array();

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";

      $class_standing_component_detail_list = ClassStandingComponentDetail::where('class_standing_component_id','=', $class_standing_component_id)->orderBy('created_at')->get();

      $column_no = 5;
      foreach ($class_standing_component_detail_list as $class_standing_component_detail) {
        # code...
        $arr[0][$column_no] = date('m/d/Y',strtotime($class_standing_component_detail->date));
        $column_no++;
      }

      $enrollment_class_list = EnrollmentClass::where('class_id','=',$class_id)->join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
              ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
              ->join('student','student_curriculum.student_id','=','student.id')
              ->join('person','student.person_id','=','person.id')
              ->select('student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
              ->orderBy('person.last_name', 'ASC')
              ->orderBy('person.first_name', 'ASC')
              ->orderBy('person.middle_name', 'ASC')->get();

      $row_no = 1;
      $count = 1;
      foreach ($enrollment_class_list as $enrollment_class) {
        # code...
        $arr[$row_no][0] = $count;
        $arr[$row_no][1] = $enrollment_class->student_no;
        $arr[$row_no][2] = $enrollment_class->last_name;
        $arr[$row_no][3] = $enrollment_class->first_name;
        $arr[$row_no][4] = substr($enrollment_class->middle_name, 0,1);

          $column_no = 5;
          foreach ($class_standing_component_detail_list as $class_standing_component_detail) {

            $class_standing_score_entry = ClassStandingScoreEntry::where('class_standing_component_detail_id','=', $class_standing_component_detail->id)
                        ->where('student_id','=', $enrollment_class->id)
                        ->join('attendance_remark','class_standing_score_entry.attendance_remarks_id','=','attendance_remark.id')->select('class_standing_score_entry.id','attendance_remark.attendance_remarks_code')->get()->first();
            # code...
            $arr[$row_no][$column_no] = $class_standing_score_entry->attendance_remarks_code;
            $attendance_id_arr[$row_no][$column_no] = $class_standing_score_entry->id;
            $column_no++;
          }

        $row_no++;
        $count++;
      }

      $data = array($arr, $attendance_id_arr);
      return response()->json($data);
    }
    
    public function dataJsonViewAll(){

      $condition = \Input::all();
      $class_standing_component_id = \Input::get('class_standing_component_id');
      $class_id = \Input::get('class_id');

      $score_entry_id_arr = array();
      $perfect_score_arr = array();

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";

      $class_standing_component_detail_list = ClassStandingComponentDetail::where('class_standing_component_id','=', $class_standing_component_id)->orderBy('created_at')->get();

      $column_no = 5;
      foreach ($class_standing_component_detail_list as $class_standing_component_detail) {
        # code...
        $arr[0][$column_no] = $class_standing_component_detail->description.' ('.$class_standing_component_detail->perfect_score.')';
        $perfect_score_arr[$column_no] =  $class_standing_component_detail->perfect_score;
        $column_no++;
      }

      $enrollment_class_list = EnrollmentClass::where('class_id','=',$class_id)->join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
              ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
              ->join('student','student_curriculum.student_id','=','student.id')
              ->join('person','student.person_id','=','person.id')
              ->select('student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
              ->orderBy('person.last_name', 'ASC')
              ->orderBy('person.first_name', 'ASC')
              ->orderBy('person.middle_name', 'ASC')->get();

      $row_no = 1;
      $count = 1;
      foreach ($enrollment_class_list as $enrollment_class) {
        # code...
        $arr[$row_no][0] = $count;
        $arr[$row_no][1] = $enrollment_class->student_no;
        $arr[$row_no][2] = $enrollment_class->last_name;
        $arr[$row_no][3] = $enrollment_class->first_name;
        $arr[$row_no][4] = substr($enrollment_class->middle_name, 0,1);

          $column_no = 5;
          foreach ($class_standing_component_detail_list as $class_standing_component_detail) {

            $class_standing_score_entry = ClassStandingScoreEntry::where('class_standing_component_detail_id','=', $class_standing_component_detail->id)
                        ->where('student_id','=', $enrollment_class->id)->get()->first();
            # code...
            $arr[$row_no][$column_no] = $class_standing_score_entry->score;
            $score_entry_id_arr[$row_no][$column_no] = $class_standing_score_entry->id;
            $column_no++;
          }

        $row_no++;
        $count++;
      }

      $data = array($arr, $score_entry_id_arr, $perfect_score_arr);
      return response()->json($data);
    }

    public function postUpdate() {

        $data=\Input::get('data');
        foreach ($data as $row) {

            $attendance_remark = AttendanceRemark::where('attendance_remarks_code',$row["attendance_remarks_code"])->select(['id'])->get()->last();

            $class_standing_score_entry = ClassStandingScoreEntry::find($row["id"]);
            $class_standing_score_entry->score = $row["score"];
            $class_standing_score_entry->attendance_remarks_id = $attendance_remark->id;
            $class_standing_score_entry-> save();
        }
    }

    public function postupdateA() {

        $data=\Input::get('data');
        foreach ($data as $row) {

            $attendance_remark = AttendanceRemark::where('attendance_remarks_code',$row["attendance_remarks_code"])->select(['id'])->get()->last();

            $class_standing_score_entry = ClassStandingScoreEntry::find($row["id"]);
            if($row["attendance_remarks_code"] == 'P'){
              $class_standing_score_entry->score = 100;
              $class_standing_score_entry->attendance_remarks_id = $attendance_remark->id;
            }else{
              $class_standing_score_entry->score = 85;
              $class_standing_score_entry->attendance_remarks_id = $attendance_remark->id;
            }
            $class_standing_score_entry-> save();
        }
    }

    public function postUpdateScoreEntry() {

        $id=\Input::get('id');
        $score=\Input::get('score');

        $class_standing_score_entry = ClassStandingScoreEntry::find($id);
        $class_standing_score_entry->score = $score;
        $class_standing_score_entry-> save();
    }

    public function postUpdateAttendance() {

        $id=\Input::get('id');
        $attendance_remark=\Input::get('attendance_remark');
        
        $attendance_remark = AttendanceRemark::where('attendance_remarks_code',$attendance_remark)->select(['id'])->get()->last();

        $class_standing_score_entry = ClassStandingScoreEntry::find($id);
        $class_standing_score_entry->attendance_remarks_id = $attendance_remark->id;
        if($attendance_remark == 'P'){
          $class_standing_score_entry->score = 100;
        }else{
          $class_standing_score_entry->score = 85;
        }
        $class_standing_score_entry-> save();
    }
}
