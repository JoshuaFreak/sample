<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Teacher;
use App\Models\TeacherClassification;
use App\Models\TeacherSubject;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TeacherClassificationController extends BaseController {

    public function dataJson(){
      $condition = \Input::all();
      $query = TeacherClassification::join('classification', 'teacher_classification.classification_id', '=', 'classification.id')
              ->join('teacher','teacher_classification.teacher_id','=','teacher.id')
                ->select('teacher_classification.id', 'classification.classification_name', 'teacher.employee_id');
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $teacher_classification = $query->select(['classification.classification_name'])->get();

      return response()->json($teacher_classification);
    }

    public function subjectDataJson(){
      $condition = \Input::all();
      $query = TeacherSubject::join('classification', 'teacher_subject.classification_id', '=', 'classification.id')
                ->join('teacher','teacher_subject.teacher_id','=','teacher.id')
                ->select('teacher_subject.id', 'classification.classification_name', 'teacher.employee_id');
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $teacher_classification = $query->select(['classification.classification_name'])->groupBy('classification.id')->get();

      return response()->json($teacher_classification);
    }

 }
