<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Teacher;
use App\Models\TeacherDegree;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\TeacherEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TeacherDegreeController extends BaseController {

   public function dataJson(){
      $condition = \Input::all();
      $query = TeacherDegree::join('degree', 'teacher_degree.degree_id', '=', 'degree.id')
              ->join('teacher','teacher_degree.teacher_id','=','teacher.id')
              ->select('teacher_degree.id', 'degree.description', 'teacher.employee_id');
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $teacher_degree = $query->select(['degree.description'])->get();

      return response()->json($teacher_degree);
    }

 }
