<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\Degree;
use App\Http\Requests\DegreeRequest;
use App\Http\Requests\DegreeEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class DegreeController extends SchedulerMainController {
   

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // Show the page
        return view('degree.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('degree.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(DegreeRequest $degree_request) {

        $degree = new Degree();
        $degree -> code = $degree_request->code;
        $degree -> description = $degree_request->description;
        $degree -> created_by_id = Auth::id();
        $degree -> save();

        $success = \Lang::get('degree.create_success').' : '.$degree->description ; 
        return redirect('degree/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $degree = Degree::find($id);
       //var_dump($its_customs_office);
        return view('degree/edit',compact('degree'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(DegreeEditRequest $degree_edit_request, $id) {
      
        $degree = Degree::find($id);
        $degree -> code = $degree_edit_request->code;
        $degree -> description = $degree_edit_request->description;
        $degree -> updated_by_id = Auth::id();
        $degree -> save();

        return redirect('degree');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $degree = Degree::find($id);
        // Show the page
        return view('degree/delete', compact('degree'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $degree = Degree::find($id);
        $degree->delete();
        return redirect('degree');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $degree_list = Degree::select(array('degree.id', 'degree.code', 'degree.description'))
        ->orderBy('degree.description', 'ASC');
    
        return Datatables::of( $degree_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'degree/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'degree/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
