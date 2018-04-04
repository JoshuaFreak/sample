<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;  
use App\Models\Payment;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;
use Hash;

class CashierController extends BaseController {

    public function dataJson(){
        $condition = \Input::all();
        $query = Payment::join('gen_user','payment.cashier_id','=','gen_user.id')
                ->join('person','gen_user.person_id','=','person.id')
                ->select('gen_user.id','gen_user.username','person.last_name','person.first_name','person.middle_name')
                ->groupBy('cashier_id');
      
        foreach($condition as $column => $value)
        { 
            if($column == 'query')
            {
                $query->where('username', 'LIKE', "%$value%")
                    ->orWhere('middle_name', 'LIKE', "%$value%")
                    ->orWhere('last_name', 'LIKE', "%$value%")
                    ->orWhere('first_name', 'LIKE', "%$value%");
            }
            else
            {
                $query->where($column, '=', $value);
            }   
        }

        $cashier = $query->get();
        return response()->json($cashier);
    }
}
