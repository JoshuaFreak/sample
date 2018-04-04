<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class EventController extends BaseController {

  public function dataJson(){
    $limit = \Input::get('limit');
    $offset = \Input::get('offset');

    $query = Event::select(['id', 'date', 'event_name', 'description', 'location', 'is_active'])->limit($limit)->offset($offset)->orderBy('date', 'DESC')->where('is_active',1);
    $count = Event::select()->where('is_active',1)->count();

    $event = $query->get();
    $arr = array($event,$count);
    return response()->json($arr);
  }
 
}
