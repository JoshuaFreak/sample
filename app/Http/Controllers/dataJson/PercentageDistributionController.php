<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassComponentCategory;
use App\Models\CurriculumSubject;
use App\Models\PercentageDistribution;
use App\Models\Subject;
use App\Http\Requests\PercentageDistributionRequest;
use App\Http\Requests\PercentageDistributionEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class PercentageDistributionController extends DeansPortalMainController {
   
   public function dataJson(){
      $condition = \Input::all();
      $query = PercentageDistribution::join('class_component_category', 'percentage_distribution.class_component_category_id', '=', 'class_component_category.id');
     
      foreach($condition as $column => $value)
      {
        $query->where('percentage_distribution.'.$column, '=', $value);
      }
      $percentage_distribution = $query->select(['class_component_category.class_component_category_name', 'percentage_distribution.percentage'])->get();

      return response()->json($percentage_distribution);
    }


     public function SubjectdataJson(){
      $condition = \Input::all();
      $query = Subject::where('is_pace','=',0)->select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $subject = $query->select([ 'id as value','code as text'])->get();

      return response()->json($subject);
    }
}
