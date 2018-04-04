<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\DesirableTraitRating;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class DesirableTraitRatingController extends BaseController {

    public function dataJson(){

      $condition = \Input::all();
      $query = DesirableTraitRating::select();

      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $desirable_trait_rating = $query->select(['id as value', 'code as text'])->get();

      return response()->json($desirable_trait_rating);
    }

}
