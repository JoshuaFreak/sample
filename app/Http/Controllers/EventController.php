<?php namespace App\Http\Controllers;

use App\Models\Event;
use Datatables;
use finfo;

class EventController extends BaseController {

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(){
    return view('admin/events.index');   
  }

  public function data(){
    $event = Event::select(['id',\DB::raw('DATE_FORMAT(date, "%M %d, %Y")')  , 'event_name', 'description', 'location', 'is_active']);
    return Datatables::of( $event)
        ->add_column('actions', '<a href="{{{ URL::to(\'admin/events/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("modal.edit") }}</a>
                <a href="{{{ URL::to(\'admin/events/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("modal.delete") }}</a>
            ')
        ->edit_column('is_active','@if($is_active == 1) <span class="glyphicon glyphicon-ok"></span> @else <span class="glyphicon glyphicon-remove"></span> @endif')
        ->remove_column('id','updated_at')

        ->make();
  }  

  public function getCreate(){
    $action = 0;
    return view('admin/events.create', compact('action'));
  }

  public function postCreate(){
    $date = \Input::get('datepicker');
    $event_name = \Input::get('event_name');
    $description = \Input::get('description');
    $location = \Input::get('location');
    $photo = \Input::file('photo');
    $is_active = \Input::get('is_active');

    $event = new Event;
    $event -> date = date_create($date);
    $event -> event_name = $event_name;
    $event -> description = $description;
    $event -> location = $location;
    if($photo){
      $event -> photo = $photo->openFile()->fread($photo->getSize());
    }else{
      $event -> photo = "";
    }
    $event -> is_active = $is_active;
    $event -> save();

    return redirect('admin/events');
  }  

  public function getEdit($id){
    $action = 1;
    $event = Event::find($id);
    return view('admin/events.edit', compact('event', 'action'));
  }

  public function postEdit($id){
    $date = \Input::get('datepicker');
    $event_name = \Input::get('event_name');
    $description = \Input::get('description');
    $location = \Input::get('location');
    $photo = \Input::file('photo');
    $is_active = \Input::get('is_active');

    $event = Event::find($id);
    $event -> date = date_create($date);
    $event -> event_name = $event_name;
    $event -> description = $description;
    $event -> location = $location;
    if($photo){
      $event -> photo = $photo->openFile()->fread($photo->getSize());
    }
    $event -> is_active = $is_active;
    $event -> save();

    return redirect('admin/events');
  }

  public function getDelete($id){
    $action = 1;
    $event = Event::find($id);

    return view('admin/events.delete', compact('event', 'action'));
  }

  public function postDelete($id){
    $event = Event::find($id);
    $event -> delete();

    return redirect('admin/events');
  }

  public function getImage($id){
    $events = Event::find($id);

    return response()->make($events->photo, 200, array(
          'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($events->photo)
    ));

  }
}
