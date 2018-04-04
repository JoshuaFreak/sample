<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\AttendanceRemark;
use App\Models\EnrollmentClass;
use App\Models\MajorExamScoreEntry;
use App\Models\MajorExamDetail;
use Illuminate\Support\Facades\Auth;
use Datatables;

class MajorExamScoreEntryController extends TeachersPortalMainController {

    public function dataJson(){

      $condition = \Input::all();
      $major_exam_detail_id = \Input::get('major_exam_detail_id');
      $query = MajorExamScoreEntry::leftJoin('student', 'major_exam_score_entry.student_id', '=', 'student.id')
                ->leftJoin('person', 'student.person_id', '=', 'person.id')
                ->leftJoin('attendance_remark', 'major_exam_score_entry.attendance_remark_id', '=', 'attendance_remark.id')
                ->where('major_exam_score_entry.major_exam_detail_id','=',$major_exam_detail_id)
                ->select(array('major_exam_score_entry.id', 'student.student_no','person.last_name', 'person.first_name', 'person.middle_name', 'major_exam_score_entry.score', 'attendance_remark.attendance_remarks_code'));

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

      $major_exam_score_entry = $query->orderBy('major_exam_score_entry.id','ASC')->get();

      return response()->json($major_exam_score_entry);


      $result = MajorExamScoreEntry::get();
      return response()->json($result);
    }
    
    public function dataJsonViewAll(){

      $condition = \Input::all();
      $class_id = \Input::get('class_id');

      $score_entry_id_arr = array();
      $perfect_score_arr = array();

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";

      $major_exam_detail_list = MajorExamDetail::where('major_exam_detail.class_id','=', $class_id)
            ->join('grading_period','major_exam_detail.grading_period_id','=','grading_period.id')
            ->select('major_exam_detail.id','grading_period.grading_period_name','major_exam_detail.perfect_score')
            ->orderBy('grading_period.id', 'ASC')->get();

      $column_no = 5;
      foreach ($major_exam_detail_list as $major_exam_detail) {
        # code...
        $arr[0][$column_no] = $major_exam_detail->grading_period_name.' ('.$major_exam_detail->perfect_score.')';
        $perfect_score_arr[$column_no] =  $major_exam_detail->perfect_score;
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
          foreach ($major_exam_detail_list as $major_exam_detail) {

            $major_exam_score_entry = MajorExamScoreEntry::where('major_exam_detail_id','=', $major_exam_detail->id)
                        ->where('student_id','=', $enrollment_class->id)->get()->first();
            # code...
            $arr[$row_no][$column_no] = $major_exam_score_entry->score;
            $score_entry_id_arr[$row_no][$column_no] = $major_exam_score_entry->id;
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

            $major_exam_score_entry = MajorExamScoreEntry::find($row["id"]);
            $major_exam_score_entry->score = $row["score"];
            $major_exam_score_entry->attendance_remark_id = $attendance_remark->id;
            $major_exam_score_entry-> save();
        }
    }

    public function postUpdateScoreEntry() {

        $id=\Input::get('id');
        $score=\Input::get('score');

        $major_exam_score_entry = MajorExamScoreEntry::find($id);
        $major_exam_score_entry->score = $score;
        $major_exam_score_entry-> save();
    }

}
