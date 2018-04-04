<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\GradingPeriod;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class GradingPeriodController extends BaseController {

    public function dataJson(){

      $condition = \Input::all();
      $query = GradingPeriod::select();

      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $grading_period = $query->select(['id as value', 'grading_period_name as text'])->get();

      // $grading = array();
      // foreach ($grading_period as $period) {
        
      //     $text = $period->text;
      //     $text = substr($text, 0,4);
      //     $grading[] = array('value' => $period->value , 'text' => $text);
      // }

      return response()->json($grading_period);
    }

}
