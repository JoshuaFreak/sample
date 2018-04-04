<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\ClassComponentCategory;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\SubjectEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ClassComponentCategoryController extends BaseController {
   

     public function dataJson(){
      $condition = \Input::all();
      $query = ClassComponentCategory::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $class_component_category = $query->select([ 'id as value','class_component_category_name as text'])->get();

      return response()->json($class_component_category);
    }

    

}
