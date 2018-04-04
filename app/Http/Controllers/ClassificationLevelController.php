<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\SemesterLevel;
use App\Http\Requests\ClassificationLevelRequest;
use App\Http\Requests\ClassificationLevelEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ClassificationLevelController extends RegistrarMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // Show the page
        return view('classification_level.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $classification_list = Classification::all();
        $semester_level_list = SemesterLevel::all();
        return view('classification_level.create', compact('action', 'classification_list', 'semester_level_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(ClassificationLevelRequest $classification_level_request) {

        $classification_level = new ClassificationLevel();
        $classification_level -> level = $classification_level_request->level;
        $classification_level -> semester_level_id = $classification_level_request->semester_level_id;
        $classification_level -> classification_id = $classification_level_request->classification_id;
        $classification_level -> created_by_id = Auth::id();
        $classification_level -> save();

        $success = \Lang::get('classification_level.create_success').' : '.$classification_level->level ; 
        return redirect('classification_level/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $action = 1;
        $classification_list = Classification::orderBy('classification.order')->get();
        $semester_level_list = SemesterLevel::all();
        $classification_level = ClassificationLevel::find($id);
       //var_dump($its_customs_office);
        return view('classification_level/edit',compact('classification_level', 'classification_list','semester_level_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(ClassificationLevelEditRequest $classification_level_edit_request, $id) {
      
        $classification_level = ClassificationLevel::find($id);
        $classification_level -> level = $classification_level_edit_request->level;
        $classification_level -> semester_level_id = $classification_level_edit_request->semester_level_id;
        $classification_level -> classification_id = $classification_level_edit_request->classification_id;
        $classification_level -> updated_by_id = Auth::id();
        $classification_level -> save();

        return redirect('classification_level');
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
        $classification_list = Classification::orderBy('classification.order')->get();
        $semester_level_list = SemesterLevel::all();
        $classification_level = ClassificationLevel::find($id);
        // Show the page
        return view('classification_level/delete', compact('classification_level', 'classification_list', 'semester_level_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $classification_level = ClassificationLevel::find($id);
        $classification_level->delete();
        return redirect('classification_level');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $classification_level_list = ClassificationLevel::join('classification', 'classification_level.classification_id', '=', 'classification.id')
            ->join('semester_level', 'classification_level.semester_level_id', '=', 'semester_level.id')
            ->where('classification_level.id', '!=', 0)
            ->select(array('classification_level.id', 'classification.classification_name', 'classification_level.level'))
            ->orderBy('classification.order', 'ASC');
    
        return Datatables::of( $classification_level_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'classification_level/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'classification_level/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
