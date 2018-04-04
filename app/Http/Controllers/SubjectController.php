<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Subject;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\SubjectEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SubjectController extends RegistrarMainController {
   
    public function index()
    {

        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        return view('subject.index', compact('classification_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $classification_list = Classification::orderBy('classification.order')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id')->get();
        return view('subject.create', compact('classification_list','classification_level_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(SubjectRequest $subject_request) {

        $searched = Subject::where('code', $subject_request->code)->get();
        if($searched->count() == 0){
            $subject = new Subject();
            $subject -> code = $subject_request->code;
            $subject -> name = $subject_request->name;
            $subject -> classification_id = $subject_request->classification_id;
            $subject -> classification_level_id = $subject_request->classification_level_id;
            // $subject -> is_pace = $subject_request->is_pace;
            // $subject -> credit_unit = $subject_request->credit_unit;
            // $subject -> hour_unit = $subject_request->hour_unit;
            $subject -> created_by_id = Auth::id();
            $subject -> save();

            $message = \Lang::get('subject.create_success').' : '.$subject_request->code ; 
            return redirect('subject/create')->withSuccess($message);
        }else{
            $message = \Lang::get('subject.create_failed').' : '.$subject_request->code ;  
            return redirect('subject/create')->with('error' ,$message);
        }
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
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id')->get();
        $subject = Subject::find($id);
       //var_dump($its_customs_office);
        return view('subject/edit',compact('subject', 'classification_list', 'classification_level_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(SubjectEditRequest $subject_edit_request, $id) {
      
        $subject = Subject::find($id);
        $subject -> code = $subject_edit_request->code;
        $subject -> name = $subject_edit_request->name;
        $subject -> classification_id = $subject_edit_request->classification_id;
        $subject -> classification_level_id = $subject_edit_request->classification_level_id;
        // $subject -> is_pace = $subject_edit_request->is_pace;
        // $subject -> credit_unit = $subject_edit_request->credit_unit;
        // $subject -> hour_unit = $subject_edit_request->hour_unit;
        $subject -> updated_by_id = Auth::id();
        $subject -> save();

        return redirect('subject');
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
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id')->get();
        $subject = Subject::find($id);
        // Show the page
        return view('subject/delete', compact('subject', 'classification_list','classification_level_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $subject = Subject::find($id);
        $subject->delete();
        return redirect('subject');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
      
    // $subject_id = \Input::get('subject_id');

      $classification_id = \Input::get('classification_id');
        if($classification_id != "" && $classification_id != null) 
        {

          $subject_list = Subject::join('classification','subject.classification_id','=','classification.id')
                ->leftJoin('classification_level','subject.classification_level_id','=','classification_level.id')
                ->where('subject.classification_id','=',$classification_id)
                ->select(array('subject.id','classification.classification_name','classification_level.level', 'subject.code', 'subject.name'))
                ->orderBy('classification_level.id', 'ASC');

        }
        elseif($classification_id != "" && $classification_id != null && $classification_id != 0)
        {

          $subject_list = Subject::join('classification','subject.classification_id','=','classification.id')
                ->leftJoin('classification_level','subject.classification_level_id','=','classification_level.id')
                ->where('subject.classification_id','=',$classification_id)
                ->select(array('subject.id','classification.classification_name','classification_level.level', 'subject.code', 'subject.name'))
                ->orderBy('classification_level.id', 'ASC');
        }
    
        else
        {
            $subject_list = Subject::join('classification','subject.classification_id','=','classification.id')
                ->leftJoin('classification_level','subject.classification_level_id','=','classification_level.id')
                ->where('subject.classification_id','=',$classification_id)
                ->where('subject.id','!=',0)
                ->select(array('subject.id','classification.classification_name','classification_level.level', 'subject.code', 'subject.name'))
                ->orderBy('classification_level.id', 'ASC');
        }
        return Datatables::of($subject_list)
            // -> edit_column('is_pace', '@if ($is_pace=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
            ->add_column('actions', '<a href="{{{ URL::to(\'subject/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'subject/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')            
            ->remove_column('id')
            ->make();
    }

}
