<?php namespace App\Http\Controllers;

use App\Models\Sessions;
use App\Http\Requests\CampusRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OnlineController extends BaseController {
   

     public function postCreate(){

      $user_id = Auth::user()->id;
      $if_exist = Sessions::where('sessions.gen_user_id',$user_id)->select(['sessions.id'])->get()->last();
      $count = Sessions::where('sessions.gen_user_id',$user_id)->count();

      if($count == 0)
      {
          $session = new Sessions();
          $session -> gen_user_id = $user_id;
          $session -> save();
      }
      else
      {
          $session = Sessions::find($if_exist -> id);
          $session -> updated_at = Carbon::now();
          $session -> save();
      }
      

      return response()->json($session);
    }

    public function postDelete(){

      $user_id = Auth::user()->id;
      // $date_time = Carbon::now();
      // $date = date('',strtotime(''));

      $date = date('Y-m-d H:i:s',mktime (date("H"), date("i"), date("s") - 5));
      $session_list = Sessions::where('sessions.gen_user_id','=',$user_id)
                    ->where('sessions.created_at','<',$date)
                    ->select(['sessions.id'])->get();

      foreach ($session_list as $session) {
        $session -> delete();
      }
      

      return response()->json($date);
    }


}
