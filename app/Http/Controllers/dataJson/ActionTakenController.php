<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\ActionTaken;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ActionTakenController extends BaseController {

    public function dataJson(){

      $condition = \Input::all();
      $query = ActionTaken::select();

      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $action_taken = $query->select(['id as value', 'action_taken_name as text'])->get();

      return response()->json($action_taken);
    }

}
