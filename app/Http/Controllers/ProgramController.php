<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Http\Controllers\Controller;
use App\Models\Classification;
use App\Models\Program;
use App\Http\Requests\ProgramRequest;
use App\Http\Requests\ProgramEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ProgramController extends Controller {


    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        return view('program.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
         return view('program.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(ProgramRequest $program_request) {

        $program = new Program();
        $program -> program_name = $program_request->program_name;
        $program -> program_color = $program_request->program_color;
        $program -> is_active = 1;
        $program -> save();

        return redirect('program/create');
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
       
        $classification_list = Classification::all();
        $program = Program::find($id);
        return view('program/edit',compact('program', 'classification_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(ProgramEditRequest $program_edit_request, $id) {
      
        $program = Program::find($id);
        $program -> program_name = $program_edit_request->program_name;
        $program -> program_code = $program_edit_request->program_code;
        $program -> classification_id = $program_edit_request->classification_id;
        $program -> updated_by_id = Auth::id();
        $program -> save();

        return redirect('program');
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
        $program = Program::find($id);
        // Show the page
        return view('program/delete', compact('program', 'classification_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $program = Program::find($id);
        $program->delete();
        return redirect('program');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
      $program_list = Program::select(array('program.id', 'program.program_name', 'program.program_color'))
                           ->orderBy('program.category', 'ASC');
    
      return Datatables::of( $program_list)
         ->add_column('Color', '<p style="background-color: {{$program_color}}; width: 20%;">&nbsp;</p>')
         ->remove_column('id', 'program_color')
         ->make();
    }

}
