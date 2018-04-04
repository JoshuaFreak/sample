<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Teacher;
use App\Models\TeacherCategory;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\TeacherEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TeacherCategoryController extends BaseController {
   
   public function dataJson(){
      $condition = \Input::all();
      $query = TeacherCategory::join('employee_category', 'teacher_category.employee_category_id', '=', 'employee_category.id')
              ->join('teacher','teacher_category.teacher_id','=','teacher.id')
                ->select('teacher_category.id', 'employee_category.description', 'teacher.employee_id');
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $teacher_category = $query->select(['employee_category.description'])->get();

      return response()->json($teacher_category);
    }

}
