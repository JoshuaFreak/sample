<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;  
use App\Models\ClassificationLevel;
use App\Models\SemesterLevel;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;    
use Hash;

class SemesterLevelController extends BaseController {
   
    public function search(){
        $term = Input::get('term');
        $result = SemesterLevel::where('term_name','LIKE','%$term%')->get();
        return Response::json($result);
    }

    public function dataJson(){
      // $condition = \Input::all();
      $classification_level_id = \Input::get('classification_level_id');
      $is_active = \Input::get('is_active');
      $semester_level = 0;
      if($classification_level_id != 0)
      {
        $semester_level = ClassificationLevel::find($classification_level_id);
      
        if($semester_level -> level == "First Year" || $semester_level -> level == "Second Year" || $semester_level -> level == "Third Year" || $semester_level -> level == "Fourth Year" || $semester_level -> level == "Fifth Year")
        {
            $query = SemesterLevel::where('semester_level.classification_level_id',$classification_level_id)->where('semester_level.is_active',1);
        }
        else
        {
            $query = SemesterLevel::where('semester_level.id',$semester_level->semester_level_id)->where('semester_level.is_active',1);
        }
        $semester_level = $query->select([ 'id as value','semester_name as text'])->get();
      }
      // foreach($condition as $column => $value)
      // {
      //   $query->where($column, '=', $value);
      // }
      

      return response()->json($semester_level);
    }


}
