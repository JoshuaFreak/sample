<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\EnrollmentClass;
use App\Models\MajorExamDetail;
use App\Models\MajorExamScoreEntry;
use Illuminate\Support\Facades\Auth;
use Datatables;

class MajorExamDetailController extends TeachersPortalMainController {

  //   /**
  //    * Show the form for editing the specified resource.
  //    *
  //    * @param $role
  //    * @return Response
  //    */
    public function EditdataJson() {

      $condition = \Input::all();
      $query = MajorExamDetail::select(array('major_exam_detail.id','major_exam_detail.perfect_score','major_exam_detail.date'));
      
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $major_exam_detail = $query->get();

      return response()->json($major_exam_detail);
        
    }

    public function postUpdate() {

        $id=\Input::get('id');
        $perfect_score=\Input::get('perfect_score');
        $date=\Input::get('date');
        $class_id=\Input::get('class_id');
        $grading_period_id=\Input::get('grading_period_id');

        if ($id == null){

          $major_exam_detail = new MajorExamDetail();
          $major_exam_detail->class_id = $class_id;
          $major_exam_detail->grading_period_id = $grading_period_id;
          $major_exam_detail->perfect_score = $perfect_score;
          $major_exam_detail->date = $date;
          $major_exam_detail-> save();

          $class = EnrollmentClass::join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
                      ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                      ->where('enrollment_class.class_id','=', $class_id)
                      ->select('enrollment_class.id','student_curriculum.student_id')->get();

          foreach($class as $item)
          {
              $major_exam_score_entry = new MajorExamScoreEntry();
              $major_exam_score_entry->major_exam_detail_id = $major_exam_detail->id;
              $major_exam_score_entry->student_id = $item->student_id;
              $major_exam_score_entry->score = 0;
              $major_exam_score_entry->attendance_remark_id = 1;
              $major_exam_score_entry->is_allowed_to_edit_score = 1;
              $major_exam_score_entry->save();
          }
        }
        else
        {
          $major_exam_detail = MajorExamDetail::find($id);
          $major_exam_detail->class_id = $class_id;
          $major_exam_detail->grading_period_id = $grading_period_id;
          $major_exam_detail->perfect_score = $perfect_score;
          $major_exam_detail->date = $date;
          $major_exam_detail-> save();
        }
        

    }

}
