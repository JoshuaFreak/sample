<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\PaymentScheme;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class PaymentSchemeController extends BaseController {

    public function dataJson(){

      $condition = \Input::all();
      $query = PaymentScheme::select();

      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $payment_scheme = $query->select(['id as value', 'payment_scheme_name as text'])->get();

      // $grading = array();
      // foreach ($payment_scheme as $period) {
        
      //     $text = $period->text;
      //     $text = substr($text, 0,4);
      //     $grading[] = array('value' => $period->value , 'text' => $text);
      // }

      return response()->json($payment_scheme);
    }

}
