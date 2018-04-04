<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Classification;
use App\Models\CourseCapacity;
use App\Models\ClassificationLevel;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Program;
use App\Models\Room;
use App\Models\RoomType;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class RoomController extends SchedulerMainController {
   

     public function dataJson(){
      $condition = \Input::all();
      $query = Room::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $room = $query->select(['id as value','room_name as text'])->get();

      return response()->json($room);
    }


    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        return view('room.index',compact('gen_role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $action = 0;
        $course_capacity_list = CourseCapacity::get();
        $campus_list = Campus::all();
        return view('room.create', compact('campus_list','course_capacity_list','action','gen_role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(RoomRequest $room_request) {

        $room = new Room();
        $room -> room_name = $room_request->room_name;
        $room -> course_capacity_id = $room_request->course_capacity_id;
        $room -> room_capacity = $room_request->capacity;
        $room -> campus_id = $room_request->campus_id;
        $room -> save();

        $success = \Lang::get('room.create_success').' : '.$room->room_name ; 
        return redirect('room/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();

        $action = 1;
        $campus_list = Campus::all();
        // $classification_list = Classification::all();
        // $room_type_list = RoomType::all();
        // $building_list = Building::all();
        $room = Room::find($id);
        $course_capacity_list = CourseCapacity::all();
        // $classification_level_list = ClassificationLevel::all();
        // $program_list = Program::where('program.classification_id','=',5)->get();
        return view('room/edit',compact('room','campus_list','course_capacity_list','action','gen_role'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(RoomEditRequest $room_edit_request, $id) {
      
        $room = Room::find($id);
        $room ->room_name = $room_edit_request->room_name;
        $room ->classification_id = $room_edit_request->classification_id;
        $room ->classification_level_id = $room_edit_request->classification_level_id;
        $room ->room_type_id = $room_edit_request->room_type_id;
        $room ->building_id = $room_edit_request->building_id;
        $room ->updated_by_id = Auth::id();
        $room ->save();

        return redirect('room');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {

        $username = Auth::user()->username;
        $gen_user = GenUser::where('username', $username)->get()->last('gen_user.id');

        $gen_role = GenUserRole::leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                      ->where('gen_user_role.gen_user_id','=',$gen_user -> id)
                      ->select(['gen_role.name'])
                      ->get()
                      ->last();
         $action = 1;
        $classification_list = Classification::all();
        $room_type_list = RoomType::all();
        $building_list = Building::all();
        $room = Room::find($id);
        $classification_level_list = ClassificationLevel::all();
        $program_list = Program::where('program.classification_id','=',5)->get();
        // Show the page
        return view('room/delete', compact('room', 'classification_list','room_type_list','building_list', 'action','classification_level_list','program_list','gen_role'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $room = Room::find($id);
        $room->delete();
        return redirect('room');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
          $room_list = Room::join('course_capacity', 'room.course_capacity_id', '=', 'course_capacity.id')
                        ->leftJoin('campus', 'room.campus_id', '=', 'campus.id')
                        ->select(['room.id','room.room_name', 'course_capacity.capacity_name' ,'room.room_capacity','campus.campus_name'])
                        ->orderBy('room.created_at', 'DESC');

        return Datatables::of($room_list)
            // ->add_column('actions', '<a href="{{{ URL::to(\'room/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
            //         <a href="{{{ URL::to(\'room/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
            //     ')
            ->add_column('actions', '<a href="{{{ URL::to(\'room/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    ')
            ->remove_column('id')
            ->make();
    }

}
