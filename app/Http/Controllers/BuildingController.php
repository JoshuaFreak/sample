<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Classification;
use App\Http\Requests\BuildingRequest;
use App\Http\Requests\BuildingEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class BuildingController extends SchedulerMainController {
   

     public function dataJson(){
      $condition = \Input::all();
      $query = Building::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $building = $query->select([ 'id as value','building_name as text'])->get();

      return response()->json($building);
    }


    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
       

        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        return view('building.index', compact('classification_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $campus_list = Campus::all();
        $classification_list = Classification::all();
        return view('building.create', compact('classification_list', 'action','campus_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(BuildingRequest $building_request) {

        $building = new Building();
        $building -> building_name = $building_request->building_name;
        $building -> campus_id = $building_request->campus_id;
        $building -> classification_id = $building_request->classification_id;
        $building -> created_by_id = Auth::id();
        $building -> save();

        $success = \Lang::get('building.create_success').' : '.$building->building_name ; 
        return redirect('building/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
       
        $action = 1;
        $classification_list = Classification::all();
        $building = Building::find($id);
        $campus_list = Campus::all();
        return view('building/edit',compact('building', 'classification_list', 'action','campus_list'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(BuildingEditRequest $building_edit_request, $id) {
      
        $building = Building::find($id);
        $building -> building_name = $building_edit_request->building_name;
        $building -> campus_id = $building_edit_request->campus_id;
        $building -> classification_id = $building_edit_request->classification_id;
        $building -> updated_by_id = Auth::id();
        $building -> save();

        return redirect('building');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
         $action = 1;
        $classification_list = Classification::all();
        $building = Building::find($id);
        $campus_list = Campus::all();
        // Show the page
        return view('building/delete', compact('building', 'classification_list', 'action','campus_list'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $building = Building::find($id);
        $building->delete();
        return redirect('building');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
    $building_id = \Input::get('building_id');

      $classification_id = \Input::get('classification_id');
        if($classification_id != "" && $classification_id != null) 
        {

          $building_list = Building::join('classification','building.classification_id','=','classification.id')
                        ->join('campus','building.campus_id','=','campus.id')
                        ->where('building.classification_id','=',$classification_id)
                        ->select(array('building.id', 'classification.classification_name','building.building_name','campus.campus_name'))
                        ->orderBy('building.building_name', 'DESC');
        }
        else
        {
            $building_list = Building::join('classification','building.classification_id','=','classification.id')
                        ->join('campus','building.campus_id','=','campus.id')
                        // ->where('building.classification_id','=',$classification_id)
                        ->select(array('building.id', 'classification.classification_name','building.building_name','campus.campus_name'))
                        ->orderBy('building.building_name', 'DESC');

        }
        return Datatables::of($building_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'building/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'building/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')            
            ->remove_column('id')
            ->make();
    }

}
