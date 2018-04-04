<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Subject;
use App\Models\SubjectOffered;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\SubjectEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SubjectController extends BaseController {
   

     public function dataJson(){
      $condition = \Input::all();
      $query = Subject::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $subject = $query->select([ 'id as value','code as text'])->get();

      return response()->json($subject);
    }

    public function subjectOfferedDataJson(){
      $condition = \Input::all();
      $query = SubjectOffered::join('subject', 'subject_offered.subject_id', '=', 'subject.id')->select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $subject = $query->select(['id as value','subject.name as text'])->get();

      return response()->json($subject);
    }

     public function dataJsonPrerequisite(){
      $condition = \Input::all();
      $query = Subject::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $subject = $query->select([ 'id as value','code as text'])->get();

      return response()->json($subject);
    }


}
