<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\Curriculum;
use App\Models\Program;
use App\Http\Requests\CurriculumRequest;
use App\Http\Requests\CurriculumEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class CurriculumController extends RegistrarMainController {


     public function index()
    {

        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        return view('curriculum.index', compact('classification_list'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

       $action = 0;
        $classification_list = Classification::all();
        $program_list = Program::all();
        return view('curriculum.create', compact('classification_list','program_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(CurriculumRequest $curriculum_request) {

        $curriculum = new Curriculum();
        $curriculum -> curriculum_name = $curriculum_request->curriculum_name;
        $curriculum -> classification_id = $curriculum_request->classification_id;
        $curriculum -> program_id = $curriculum_request->program_id;
        $curriculum -> created_by_id = Auth::id();
        $curriculum -> save();

        $success = \Lang::get('curriculum.create_success').' : '.$curriculum->curriculum_name ; 
        return redirect('curriculum/create')->withSuccess( $success);
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
        $program_list = Program::all();
        $curriculum = Curriculum::find($id);
       //var_dump($its_customs_office);
        return view('curriculum/edit',compact('curriculum','classification_list','program_list', 'action' ));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(CurriculumEditRequest $curriculum_edit_request, $id) {
      
        $curriculum = Curriculum::find($id);
        $curriculum -> curriculum_name = $curriculum_edit_request->curriculum_name;
        $curriculum -> classification_id = $curriculum_edit_request->classification_id;
        $curriculum -> program_id = $curriculum_edit_request->program_id;
        $curriculum -> updated_by_id = Auth::id();
        $curriculum -> save();

        return redirect('curriculum');
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
         $program_list = Program::all();
        $curriculum = Curriculum::find($id);
        // Show the page
        return view('curriculum/delete', compact('curriculum', 'classification_list', 'program_list' ,'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $curriculum = Curriculum::find($id);
        $curriculum->delete();
        return redirect('curriculum');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

        $curriculum_id = \Input::get('curriculum_id');

      $classification_id = \Input::get('classification_id');
        if($classification_id != "" && $classification_id != null) 
        {

          $curriculum_list = Curriculum::join('classification','curriculum.classification_id','=','classification.id')
                ->where('curriculum.classification_id','=',$classification_id)
                ->select(array('curriculum.id','classification.classification_name', 'curriculum.curriculum_name'))
                ->orderBy('curriculum.curriculum_name', 'DESC');

        }
        elseif($classification_id != "" && $classification_id != null && $classification_id != 0)
        {

          $curriculum_list = Curriculum::join('classification','curriculum.classification_id','=','classification.id')
            ->where('curriculum.classification_id','=',$classification_id)
            ->select(array('curriculum.id','classification.classification_name', 'curriculum.curriculum_name'))
            ->orderBy('curriculum.curriculum_name', 'DESC');
        }
    
        else
        {
            $curriculum_list = Curriculum::join('classification','curriculum.classification_id','=','classification.id')
            // ->where('curriculum.classification_id','=',$classification_id)
            ->where('curriculum.id','!=',0)
            ->select(array('curriculum.id','classification.classification_name', 'curriculum.curriculum_name'))
            ->orderBy('curriculum.curriculum_name', 'DESC');
        }

        return Datatables::of($curriculum_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'curriculum/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'curriculum/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')            ->remove_column('id')
            ->make();
    }

}
