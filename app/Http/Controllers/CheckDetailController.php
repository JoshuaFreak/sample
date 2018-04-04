<?php namespace App\Http\Controllers;

use App\Models\CheckDetail;
use App\Http\Requests\CampusRequest;
use Illuminate\Support\Facades\Auth;

class CheckDetailController extends SchedulerMainController {
   

    public function dataJson(){
        
        $payment_id = \Input::get('payment_id');
        $result = CheckDetail::join('campus','check_detail.campus_id','=','campus.id')
                    ->where('check_detail.payment_id',$payment_id)
                    ->select('check_detail.bank_name','campus.campus_name','check_detail.account_no','check_detail.check_date','check_detail.check_amount')->get();

      return response()->json($result);
    }

     public function saveDataJson(){

      $payment_id = \Input::get('payment_id');
      $bank_name = \Input::get('bank_name');
      $campus_id = \Input::get('campus_id');
      $account_no = \Input::get('account_no');
      $check_date = \Input::get('check_date');
      $check_amount = \Input::get('check_amount');

      $check_detail = new CheckDetail();
      $check_detail -> payment_id = $payment_id;
      $check_detail -> bank_name = $bank_name;
      $check_detail -> campus_id = $campus_id;
      $check_detail -> account_no = $account_no;
      $check_detail -> check_date = $check_date;
      $check_detail -> check_amount = $check_amount;
      $check_detail -> save();

      return response()->json($check_detail);
    }


}
