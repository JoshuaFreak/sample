<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\Campus;
use App\Models\Classification;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Http\Requests\CampusRequest;
use App\Http\Requests\CampusEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class CampusController extends SchedulerMainController {
   

     public function dataJson(){
      $condition = \Input::all();
      $query = Campus::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $campus = $query->select([ 'id as value','campus_name as text'])->get();

      return response()->json($campus);
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
       
        return view('campus.index',compact('gen_role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;

        return view('campus.create', compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(CampusRequest $campus_request) {

        $campus = new Campus();
        $campus -> campus_name = $campus_request->campus_name;
        $campus -> created_by_id = Auth::id();
        $campus -> save();

        $success = \Lang::get('campus.create_success').' : '.$campus->campus_name ; 
        return redirect('campus/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
       
        $action = 1;
        $campus = Campus::find($id);
        return view('campus/edit',compact('campus', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(CampusEditRequest $campus_edit_request, $id) {
      
        $campus = Campus::find($id);
        $campus -> campus_name = $campus_edit_request->campus_name;
        // $campus -> classification_id = $campus_edit_request->classification_id;
        $campus -> updated_by_id = Auth::id();
        $campus -> save();

        return redirect('campus');
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
        $campus = Campus::find($id);
        // Show the page
        return view('campus/delete', compact('campus', 'classification_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $campus = Campus::find($id);
        $campus->delete();
        return redirect('campus');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

        $campus_list = Campus::where('campus.id', '!=', 0)
                    ->select(array('campus.id','campus.campus_name'))
                    ->orderBy('campus.campus_name', 'DESC');


        return Datatables::of($campus_list)
            // ->add_column('actions', '<a href="{{{ URL::to(\'campus/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
            //         <a href="{{{ URL::to(\'campus/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
            //     ')
            ->add_column('actions', '<a href="{{{ URL::to(\'campus/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                ')            
            ->remove_column('id')
            ->make();
    }

}
