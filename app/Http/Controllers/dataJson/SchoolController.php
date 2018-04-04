<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class SchoolController extends BaseController {

   public function dataJson(){
      $condition = \Input::all();
      $query = School::select(['school.id','school.school_name']);
     
     // print $condition["query"];
      //$query->where('last_name', 'LIKE', "%get%");  


      foreach($condition as $column => $value)
      {
        if($column == 'query')
        {
          $query->where('school_name', 'LIKE', "%$value%");  
        }
        else
        {
          $query->where($column, '=', $value);
        }
      }

      $school = $query->orderBy('school_name','ASC')->get();

      return response()->json($school);
    }

 
}
