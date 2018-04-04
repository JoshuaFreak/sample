<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Subject;
use App\Models\Pace;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\SubjectEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class PaceController extends BaseController {
   

     public function dataJson(){
      $condition = \Input::all();
      $query = Subject::where('is_pace','=',1)->select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $subject = $query->select([ 'id as value','name as text'])->get();

      return response()->json($subject);
    }
    public function paceDataJson(){
      $condition = \Input::all();
      $query = Pace::where('is_active','=',1)->select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $subject = $query->select([ 'id as value','pace_name as text'])->get();

      return response()->json($subject);
    }
}
