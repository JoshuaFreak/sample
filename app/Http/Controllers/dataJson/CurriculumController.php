<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\Curriculum;
use App\Models\Classification;
use App\Models\Program;
use App\Http\Requests\CurriculumRequest;
use App\Http\Requests\CurriculumEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class CurriculumController extends BaseController {
   
    public function dataJson(){

      $condition = \Input::all();
      $query = Curriculum::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $curriculum = $query->select([ 'id as value','curriculum_name as text'])->get();

      return response()->json($curriculum);
    }

}
