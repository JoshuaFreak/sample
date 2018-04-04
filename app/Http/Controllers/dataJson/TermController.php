<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\Term;
use App\Http\Requests\TermRequest;
use App\Http\Requests\TermEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TermController extends BaseController {
   

    public function dataJson(){
      $condition = \Input::all();
      $query = Term::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $term = $query->select([ 'id as value','term_name as text'])->orderBy('id','DESC')->get();

      return response()->json($term);
    }



}
