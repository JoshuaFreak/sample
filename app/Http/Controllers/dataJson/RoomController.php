<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class RoomController extends BaseController {
   

    public function scheduleDataJson(){

      $query = Room::select([ 'id','room_name as name'])->get();

      return response()->json($query);
    }

}
