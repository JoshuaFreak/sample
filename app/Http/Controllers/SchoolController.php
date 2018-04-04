<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\School;
use App\Http\Requests\SchoolRequest;
use App\Http\Requests\SchoolEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SchoolController extends RegistrarMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
       
 
        // Show the page
        return view('school.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('school.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(SchoolRequest $school_request) {

        $school = new School();
        $school -> school_name = $school_request->school_name;
        $school -> school_code = $school_request->school_code;
        $school -> school_address = $school_request->school_address;
        $school -> school_contact_no = $school_request->school_contact_no;
        $school -> created_by_id = Auth::id();
        $school -> save();

        $success = \Lang::get('school.create_success').' : '.$school->school_name ; 
        return redirect('school/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $school = School::find($id);
       //var_dump($its_customs_office);
        return view('school/edit',compact('school'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(SchoolEditRequest $school_edit_request, $id) {
      
        $school = School::find($id);
        $school -> school_name = $school_edit_request->school_name;
        $school -> school_code = $school_edit_request->school_code;
        $school -> school_address = $school_edit_request->school_address;
        $school -> school_contact_no = $school_edit_request->school_contact_no;
        $school -> updated_by_id = Auth::id();
        $school -> save();

        return redirect('school');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $school = School::find($id);
        // Show the page
        return view('school/delete', compact('school'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $school = School::find($id);
        $school->delete();
        return redirect('school');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $school_list = School::select(array('school.id', 'school.school_code', 'school.school_name',  'school.school_address', 'school.school_contact_no'))
        ->orderBy('school.school_name', 'ASC');
    
        return Datatables::of( $school_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'school/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'school/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
