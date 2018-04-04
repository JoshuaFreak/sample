<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\RoomType;
use App\Http\Requests\RoomTypeRequest;
use App\Http\Requests\RoomTypeEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class RoomTypeController extends SchedulerMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // Show the page
        return view('room_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('room_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(RoomTypeRequest $room_type_request) {

        $room_type = new RoomType();
        $room_type ->name = $room_type_request->name;
        $room_type ->code = $room_type_request->code;
        $room_type ->created_by_id = Auth::id();
        $room_type -> save();

        $success = \Lang::get('room_type.create_success').' : '.$room_type->name ; 
        return redirect('room_type/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $room_type = RoomType::find($id);
       //var_dump($its_customs_office);
        return view('room_type/edit',compact('room_type'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(RoomTypeEditRequest $room_type_edit_request, $id) {
      
        $room_type = RoomType::find($id);
        $room_type ->name = $room_type_edit_request->name;
        $room_type ->code = $room_type_edit_request->code;
        $room_type ->updated_by_id = Auth::id();
        $room_type -> save();

        return redirect('room_type');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $room_type = RoomType::find($id);
        // Show the page
        return view('room_type/delete', compact('room_type'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $room_type = RoomType::find($id);
        $room_type->delete();
        return redirect('room_type');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $room_type_list = RoomType::where('room_type.id', '!=', 0)
            ->select(array('room_type.id', 'room_type.code', 'room_type.name'))
            ->orderBy('room_type.name', 'ASC');
    
        return Datatables::of( $room_type_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'room_type/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'room_type/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
