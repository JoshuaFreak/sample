<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Enrollment;
use App\Models\GradingPeriod;
use App\Models\Pace;
use App\Models\StudentAcademicProjection;
use App\Http\Requests\ClassStandingScoreEntryEditRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;
use Input;

class AcademicProjectionController extends TeachersPortalMainController {
    
    public function dataJson(){

      $condition = \Input::all();
      $term_id = \Input::get('term_id');
      $subject_id = \Input::get('subject_id');
      $section_id = \Input::get('section_id');
      $grading_period_id = \Input::get('grading_period_id');

      $grade_id_arr = array();
      $detail_arr = array();

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";
      $arr[0][5] = "";

      $enrollment_list = Enrollment::where('section_id','=',$section_id)
              ->where('enrollment.term_id','=',$term_id)
              ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
              ->join('student','student_curriculum.student_id','=','student.id')
              ->join('person','student.person_id','=','person.id')
              ->select('enrollment.id','student_curriculum.student_id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
              ->orderBy('person.last_name', 'ASC')
              ->orderBy('person.first_name', 'ASC')
              ->orderBy('person.middle_name', 'ASC')->get();

      $row_no = 1;
      $count = 1;
      foreach ($enrollment_list as $enrollment) {
          # code...
          $arr[$row_no][0] = $count;
          $arr[$row_no][1] = $enrollment->student_no;
          $arr[$row_no][2] = $enrollment->last_name;
          $arr[$row_no][3] = $enrollment->first_name;
          $arr[$row_no][4] = substr($enrollment->middle_name, 0,1);

          $pace_list = StudentAcademicProjection::where('student_academic_projection.student_id','=',$enrollment->student_id)
                ->where('student_academic_projection.term_id','=',$term_id)
                ->where('student_academic_projection.subject_id','=',$subject_id)
                ->where('student_academic_projection.grading_period_id','=',$grading_period_id)
                ->select('student_academic_projection.id','student_academic_projection.required_pace','student_academic_projection.actual_pace','student_academic_projection.date_released','student_academic_projection.date_taken','student_academic_projection.grade')->get();

          $arr[$row_no][5] = "(REQUIRED)";
          $col_no = 6;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->required_pace;
            $arr[0][$col_no] = "";
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'required';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][5] = "(ACTUAL)";
          $col_no = 6;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->actual_pace;
            $arr[0][$col_no] = "";
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'actual';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][5] = "(DATE RELEASED)";
          $col_no = 6;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->date_released;
            $arr[0][$col_no] = "";
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'date_released';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][5] = "(DATE TAKEN)";
          $col_no = 6;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->date_taken;
            $arr[0][$col_no] = "";
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'date_taken';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][5] = "(GRADE)";
          $col_no = 6;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->grade;
            $arr[0][$col_no] = "";
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'grade';
            $col_no++;
          }
          $row_no++;

        $count++;
      }

      $data = array($arr, $grade_id_arr, $detail_arr);
      return response()->json($data);
    }
    
    public function dataJsonStudent(){

      $condition = \Input::all();
      $term_id = \Input::get('term_id');
      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');

      $subject_list = StudentAcademicProjection::join('subject', 'student_academic_projection.subject_id', '=', 'subject.id')
                ->where('student_academic_projection.student_id','=',$student_id)
                ->where('student_academic_projection.term_id','=',$term_id)
                ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
                ->select('subject.id','subject.name', 'student_academic_projection.subject_id')
                ->groupBy('subject.id')->get();

      // $arr[0][0] = "";
      // $arr[0][1] = "";

      $row_no = 0;
      foreach ($subject_list as $subject) {
        $arr[$row_no][0] = $subject->name;

          $pace_list = StudentAcademicProjection::where('student_academic_projection.student_id','=',$student_id)
                ->where('student_academic_projection.term_id','=',$term_id)
                ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
                ->where('student_academic_projection.subject_id','=',$subject->subject_id)
                ->select('student_academic_projection.id','student_academic_projection.required_pace','student_academic_projection.actual_pace','student_academic_projection.date_released','student_academic_projection.date_taken','student_academic_projection.grade','student_academic_projection.subject_id')->get();
        
          $arr[$row_no][1] = "(REQUIRED)";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->required_pace;
            // $arr[0][$col_no] = "";
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'required';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "(ACTUAL)";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->actual_pace;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'actual';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "DATE RELEASED";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->date_released;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'date_released';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "DATE TAKEN";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->date_taken;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'date_taken';
            $col_no++;
          }
          $row_no++;

          $arr[$row_no][1] = "GRADE";
          $col_no = 2;
          foreach ($pace_list as $pace) {
            $arr[$row_no][$col_no] = $pace->grade;
            $grade_id_arr[$row_no][$col_no] = $pace->id;
            $detail_arr[$row_no][$col_no] = 'grade';
            $col_no++;
          }
          $row_no++;

      }

      $data = array($arr, $grade_id_arr, $detail_arr);
      return response()->json($data);
    }

    // public function postUpdate() {

    //     $id=\Input::get('id');
    //     $detail=\Input::get('detail');
    //     $data=\Input::get('data');
    //     $term_id=\Input::get('term_id');
    //     $classification_id=\Input::get('classification_id');
        
    //     if($detail == 'period'){

    //       $grading_period = GradingPeriod::where('grading_period_name',$data.' Quarter')->where('term_id',$term_id)->where('classification_id',$classification_id)->select(['id'])->get()->last();
        
    //       $student_academic_projection = StudentAcademicProjection::find($id);
    //       $student_academic_projection->grading_period_id = $grading_period->id;
    //       $student_academic_projection->save();

    //     }else{

    //       $student_academic_projection = StudentAcademicProjection::find($id);
    //       if($detail == 'date'){
    //         $student_academic_projection->date = $data;
    //         $student_academic_projection->save();
    //       }elseif ($detail == 'score') {
    //         $student_academic_projection->grade = $data;
    //         $student_academic_projection->save();
    //       }

    //     }
    // }

    public function postUpdatePace() {

        $id=\Input::get('id');
        $detail=\Input::get('detail');
        $data=\Input::get('data');


          $student_academic_projection = StudentAcademicProjection::find($id);
          if ($detail == 'actual') {
            $student_academic_projection->actual_pace = $data;
            $student_academic_projection->save();
          }elseif ($detail == 'required') {
            $student_academic_projection->required_pace = $data;
            $student_academic_projection->save();
          }elseif($detail == 'date_released'){
            $student_academic_projection->date_released = $data;
            $student_academic_projection->save();
          }elseif($detail == 'date_taken'){
            $student_academic_projection->date_taken = $data;
            $student_academic_projection->save();
          }elseif ($detail == 'grade') {
            $student_academic_projection->grade = $data;
            $student_academic_projection->save();
          }
    }
    
    public function dataJsonSPC(){

      $condition = \Input::all();
      $classification_id = \Input::get('classification_id');
      $student_id = \Input::get('student_id');
      $term_id = \Input::get('term_id');

      $row_no = 0;
      $pace_classification_list = Pace::join('classification_level','pace.classification_level_id','=','classification_level.id')
            ->select('pace.id','classification_level.level','pace.classification_level_id')
            ->where('classification_level.classification_id','=', $classification_id)
            ->groupBy('pace.classification_level_id')
            ->get();

      foreach ($pace_classification_list as $pace_classification) {
          $arr[$row_no][0] = $pace_classification->level;

          $subject_pace_list = Pace::leftJoin('subject','pace.subject_id','=','subject.id')
              // ->leftJoin('subject_prerequisite','subject_prerequisite.subject_id','=','subject.id')
              ->where('classification_level_id','=', $pace_classification->classification_level_id)
              ->select('pace.id','subject.name','pace.subject_id')->groupBy('subject.id')->get();

          foreach ($subject_pace_list as $subject_pace) {
            $arr[$row_no][1] = $subject_pace->name;

              $col_no = 2;
              $pace_list = Pace::leftJoin('subject', 'pace.subject_id', '=', 'subject.id')
                // ->leftJoin('subject_prerequisite','subject_prerequisite.subject_id','=','subject.id')
                ->where('subject_id','=', $subject_pace->subject_id)
                ->where('classification_level_id','=', $pace_classification->classification_level_id)
                ->get();
              foreach ($pace_list as $pace) {

                $required_pace = StudentAcademicProjection::leftJoin('subject', 'student_academic_projection.subject_id', '=', 'subject.id')
                // ->leftJoin('subject_prerequisite','subject_prerequisite.subject_id','=','subject.id')
                ->where('required_pace','=', $pace->pace_name)
                // ->where('grading_period_id','=', $pace->grading_period_id)
                // ->where('classification_level_id','=', $pace->classification_level_id)
                ->where('subject_id','=', $pace->subject_id)
                ->where('student_id','=', $student_id)
                ->where('term_id','=', $term_id)
                ->get()->last();

                $released_pace = StudentAcademicProjection::leftJoin('subject', 'student_academic_projection.subject_id', '=', 'subject.id')
                // ->leftJoin('subject_prerequisite','subject_prerequisite.subject_id','=','subject.id')
                ->where('actual_pace','=', $pace->pace_name)
                // ->where('grading_period_id','=', $pace->grading_period_id)
                // ->where('classification_level_id','=', $pace->classification_level_id)
                ->where('subject_id','=', $pace->subject_id)
                ->where('student_id','=', $student_id)
                ->where('term_id','=', $term_id)
                ->get()->last();

                $end_pace = StudentAcademicProjection::leftJoin('subject', 'student_academic_projection.subject_id', '=', 'subject.id')
                // ->leftJoin('subject_prerequisite','subject_prerequisite.subject_id','=','subject.id')
                ->where('actual_pace','=', $pace->pace_name)
                // ->where('grading_period_id','=', $pace->grading_period_id)
                // ->where('classification_level_id','=', $pace->classification_level_id)
                ->where('subject_id','=', $pace->subject_id)
                ->where('student_id','=', $student_id)
                ->where('term_id','!=', $term_id)
                ->get()->last();
                    
                    if($required_pace != null && $released_pace != null && $released_pace->grade != null){
                      $arr[$row_no][$col_no] = $released_pace->actual_pace;
                      $pace_arr[$row_no][$col_no] = "finished_pace";

                    }elseif($required_pace != null && $released_pace != null && $released_pace->grade == null){
                      $arr[$row_no][$col_no] = $released_pace->actual_pace;
                      $pace_arr[$row_no][$col_no] = "released_pace";

                    }elseif($required_pace != null){
                      $arr[$row_no][$col_no] = $required_pace->required_pace;
                      $pace_arr[$row_no][$col_no] = "required_pace";

                    }elseif($end_pace != null){
                      $arr[$row_no][$col_no] = $end_pace->actual_pace;
                      $pace_arr[$row_no][$col_no] = "end_pace";

                    }else{
                      $arr[$row_no][$col_no] = $pace->pace_name;
                      $pace_arr[$row_no][$col_no] = "pace";
                    }

                $col_no++;
              }

            $row_no++;
          }
      }

      $data = array($arr, $pace_arr);
      return response()->json($data);
    }

}
