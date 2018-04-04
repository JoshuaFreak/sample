<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;  
use App\Models\ClassificationLevel;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;    
use Hash;

class ClassificationLevelController extends BaseController {
   

    public function search(){
        $term = Input::get('term');
        $result = ClassificationLevel::where('level','LIKE','%$term%')->get();
        return Response::json($result);
    }

    public function dataJson(){
      $condition = \Input::all();
      $query = ClassificationLevel::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $classification_level = $query->select([ 'id as value','level as text'])->get();

      return response()->json($classification_level);
    }

}
