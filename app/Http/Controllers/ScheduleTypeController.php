<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\SchedulerMainController;
use App\Models\ScheduleType;
use App\Http\Requests\ScheduleTypeRequest;
use App\Http\Requests\ScheduleTypeEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class ScheduleTypeController extends SchedulerMainController {
   

    /*
    return a list of country in json format based on a term
    **/
    public function search(){
      $term = Input::get('term');
      $result = ScheduleType::where('schedule_type_code','LIKE','%$term%').orWhere('schedule_type_name','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function dataJson(){
      $result = ScheduleType::where('is_active',1)->get();
      return response()->json($result);
    }

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        // Show the page
        return view('schedule_type.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('schedule_type.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(ScheduleTypeRequest $schedule_type_request) {

        $schedule_type = new ScheduleType();
        $schedule_type -> schedule_type_code = $schedule_type_request->schedule_type_code;
        $schedule_type -> schedule_type_name = $schedule_type_request->schedule_type_name;
        // $schedule_type -> created_by_id = Auth::id();
        $schedule_type -> save();

        $success = \Lang::get('schedule_type.create_success').' : '.$schedule_type->schedule_type_name ; 
        return redirect('schedule_type/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $schedule_type = ScheduleType::find($id);
       //var_dump($its_customs_office);
        return view('schedule_type/edit',compact('schedule_type'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(ScheduleTypeEditRequest $schedule_type_edit_request, $id) {
      
        $schedule_type = ScheduleType::find($id);
        $schedule_type -> schedule_type_code = $schedule_type_edit_request->schedule_type_code;
        $schedule_type -> schedule_type_name = $schedule_type_edit_request->schedule_type_name;
        $schedule_type -> save();

        return redirect('schedule_type');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $schedule_type = ScheduleType::find($id);
        // Show the page
        return view('schedule_type/delete', compact('schedule_type'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $schedule_type = ScheduleType::find($id);
        $schedule_type->delete();
        return redirect('schedule_type');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
          $schedule_type_list = ScheduleType::select(array('schedule_type.id','schedule_type.schedule_type_code','schedule_type.schedule_type_name'))
              ->orderBy('schedule_type.schedule_type_name', 'ASC');
    
        return Datatables::of($schedule_type_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'schedule_type/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("its/form.edit") }}</a>
                    <a href="{{{ URL::to(\'schedule_type/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("its/form.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->remove_column('id')
            ->make();
    }
   /**
     * Reorder items
     *
     * @param items list
     * @return items from @param
     */
    public function getReorder(ReorderRequest $request) {
        $list = $request->list;
        $items = explode(",", $list);
        $order = 1;                 
        foreach ($items as $value) {
            if ($value != '') {
                ScheduleType::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
