<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Subject;
use App\Models\SubjectPrerequisite;
use App\Http\Requests\SubjectPrerequisiteRequest;
use App\Http\Requests\SubjectPrerequisiteEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SubjectPrerequisiteController extends RegistrarMainController {
   
    public function index()
    {

        // $classification_list = Classification::orderBy('classification.id','ASC')->get();
        // return view('subject_prerequisite.index', compact('classification_list'));
        return view('subject_prerequisite.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id', 'ASC')->get();
        $subject_list = Subject::orderBy('subject.classification_level_id')->where('subject.id', '!=', 0)->get();
        return view('subject_prerequisite.create', compact('classification_level_list','subject_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(SubjectPrerequisiteRequest $subject_prerequisite_request) {

        $subject_prerequisite = new SubjectPrerequisite();
        $subject_prerequisite -> subject_id = $subject_prerequisite_request->subject_id;
        $subject_prerequisite -> prerequisite_subject_id = $subject_prerequisite_request->prerequisite_subject_id;
        $subject_prerequisite -> created_by_id = Auth::id();
        $subject_prerequisite -> save();

        $success = \Lang::get('subject_prerequisite.create_success').' : '.$subject_prerequisite->subject_id ; 
        return redirect('subject_prerequisite/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $action = 1;
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id')->get();
        $subject_list = Subject::orderBy('subject.classification_level_id')->where('subject.id', '!=', 0)->get();
        $subject_prerequisite = SubjectPrerequisite::find($id);
       //var_dump($its_customs_office);
        return view('subject_prerequisite/edit',compact('subject_prerequisite', 'subject_list', 'classification_level_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(SubjectPrerequisiteEditRequest $subject_prerequisite_edit_request, $id) {
      
        $subject_prerequisite = SubjectPrerequisite::find($id);
        $subject_prerequisite -> subject_id = $subject_prerequisite_edit_request->subject_id;
        $subject_prerequisite -> prerequisite_subject_id = $subject_prerequisite_edit_request->prerequisite_subject_id;
        $subject_prerequisite -> updated_by_id = Auth::id();
        $subject_prerequisite -> save();

        return redirect('subject_prerequisite');
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
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id')->get();
        $subject_list = Subject::orderBy('subject.classification_level_id')->where('subject.id', '!=', 0)->get();
        $subject_prerequisite = SubjectPrerequisite::find($id);
        // Show the page
        return view('subject_prerequisite/delete', compact('subject_prerequisite', 'subject_list','classification_level_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $subject_prerequisite = SubjectPrerequisite::find($id);
        $subject_prerequisite->delete();
        return redirect('subject_prerequisite');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
      
    // $subject_id = \Input::get('subject_id');

      // $classification_id = \Input::get('classification_id');
      //   if($classification_id != "" && $classification_id != null) 
      //   {

      //     $subject_list = SubjectPrerequisite::join('classification','subject.classification_id','=','classification.id')
      //           ->leftJoin('classification_level','subject.classification_level_id','=','classification_level.id')
      //           ->where('subject.classification_id','=',$classification_id)
      //           ->select(array('subject.id','classification.classification_name','classification_level.level', 'subject.code', 'subject.name', 'subject.is_pace'))
      //           ->orderBy('classification_level.id', 'ASC');

      //   }
      //   elseif($classification_id != "" && $classification_id != null && $classification_id != 0)
      //   {

      //     $subject_list = SubjectPrerequisite::join('classification','subject.classification_id','=','classification.id')
      //           ->leftJoin('classification_level','subject.classification_level_id','=','classification_level.id')
      //           ->where('subject.classification_id','=',$classification_id)
      //           ->select(array('subject.id','classification.classification_name','classification_level.level', 'subject.code', 'subject.name', 'subject.is_pace'))
      //           ->orderBy('classification_level.id', 'ASC');
      //   }
    
      //   else
      //   {
            $subject_prerequisite_list = SubjectPrerequisite::join('subject as subject_name','subject_prerequisite.subject_id','=','subject_name.id')
                ->join('classification_level', 'subject_name.classification_level_id', '=', 'classification_level.id')
                ->join('subject as prerequisite_subject','subject_prerequisite.prerequisite_subject_id','=','prerequisite_subject.id')
                // ->select(array('subject_prerequisite.id','subject_name.code','subject_name.name' ))
                ->select(array('subject_prerequisite.id','subject_name.code','subject_name.name', 'prerequisite_subject.code as subject_prerequisite_code', 'prerequisite_subject.name as subject_prerequisite_name'))
                ->orderBy('classification_level.id', 'ASC');
        // }
        return Datatables::of($subject_prerequisite_list)
                ->add_column('actions', '<a href="{{{ URL::to(\'subject_prerequisite/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'subject_prerequisite/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')  
                ->edit_column('code', '{{{$name." - (".$code}}})')       
                ->edit_column('subject_prerequisite_code', '{{{$subject_prerequisite_name." - (".$subject_prerequisite_code}}})')       
                ->remove_column('id', 'name', 'subject_prerequisite_name')
                ->make();
    }

}
