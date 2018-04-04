<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\Program;
use App\Models\Subject;
use App\Models\SubjectOffered;
use App\Models\Term;
use App\Http\Requests\SubjectOfferedRequest;
use App\Http\Requests\SubjectOfferedEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SubjectOfferedAccountingController extends BaseController {
   

     public function dataJson(){
      $condition = \Input::all();
      $query = SubjectOffered::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $program = $query->select([ 'id as value','program_name as text'])->get();

      return response()->json($program);
    }


     public function SubjectdataJson(){
          $condition = \Input::all();
          $query = SubjectOffered::join("subject","subject_offered.subject_id","=","subject.id");

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
          $subject_offered = $query->select(['subject.code','subject.name'])->get();

          return response()->json($subject_offered);
    }

 

}
