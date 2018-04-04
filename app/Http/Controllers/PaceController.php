<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Pace;
use App\Models\Subject;
use App\Http\Requests\PaceRequest;
use App\Http\Requests\PaceEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class PaceController extends DeansPortalMainController {
   

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $subject_list = Subject::where('is_pace','=',1)->orderBy('subject.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();

        return view('pace.index', compact('subject_list', 'classification_list', 'classification_level_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $subject_list = Subject::where('is_pace','=',1)->get();
        return view('pace.create', compact('action','classification_list','classification_level_list','subject_list'));
    }
    public function postPace($id) {

            $pace_name = \Input::get('pace_name');
            $ap_student_id = \Input::get('ap_student_id');
            $ap_term_id = \Input::get('ap_term_id');
            $ap_classification_id = \Input::get('ap_classification_id');

            $add_product = new StudentAcademicProjection();
            $add_product -> student_id = $ap_student_id;
            // $add_product -> classification_level_id = $quantity;
            // $add_product -> grading_period_id = $quantity;
            // $add_product -> subject_id = $quantity;
            // $add_product -> required_pace = $quantity;
            $add_product -> term_id = $ap_term_id;
            $add_product -> save();
            return view('teachers_portal/{id}/class_advisory', compact('action','classification_list','classification_level_list','subject_list'));
        }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(PaceRequest $pace_request) {

        $pace = new Pace();
        $pace ->classification_level_id = $pace_request->classification_level_id;
        $pace ->pace_name = $pace_request->pace_name;
        $pace ->subject_id = $pace_request->subject_id;
        $pace ->created_by_id = Auth::id();
        $pace -> save();

        $success = \Lang::get('pace.create_success').' : '.$pace->pace_name ; 
        return redirect('pace/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $action = 1;
        $pace = Pace::find($id);
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $subject_list = Subject::all();
        return view('pace/edit',compact('pace','action','classification_level_list','classification_list','subject_list'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(PaceEditRequest $pace_edit_request, $id) {
      
        $pace = Pace::find($id);
        $pace ->pace_name = $pace_edit_request->pace_name;
        $pace ->classification_level_id = $pace_edit_request->classification_level_id;
        $pace ->subject_id = $pace_edit_request->subject_id;
        $pace ->updated_by_id = Auth::id();
        $pace ->save();

        return redirect('pace');
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
        $pace = Pace::find($id);
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $subject_list = Subject::all();
        // Show the page
        return view('pace/delete', compact('pace', 'action','classification_level_list','classification_list','subject_list'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $pace = Pace::find($id);
        $pace->delete();
        return redirect('pace');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

    
      $classification_level_id = \Input::get('classification_level_id');
      $subject_id = \Input::get('subject_id');
        if($classification_level_id != "" && $classification_level_id != null && $subject_id != "" && $subject_id != null) 
        {

          $pace_list = Pace::join('classification_level', 'pace.classification_level_id', '=', 'classification_level.id')
            ->join('subject', 'pace.subject_id', '=','subject.id')
            ->where('pace.classification_level_id','=',$classification_level_id)
            ->where('pace.subject_id','=',$subject_id)
            ->where('pace.id','!=',0)
            ->select(array('pace.id', 'classification_level.level', 'subject.name', 'pace.pace_name'))
            ->orderBy('pace.id', 'DESC');
        }
        elseif($classification_level_id != "" && $classification_level_id != null) 
        {

          $pace_list = Pace::join('classification_level', 'pace.classification_level_id', '=', 'classification_level.id')
            ->join('subject', 'pace.subject_id', '=','subject.id')
            ->where('pace.classification_level_id','=',$classification_level_id)
            ->where('pace.id','!=',0)
            ->select(array('pace.id', 'classification_level.level', 'subject.name', 'pace.pace_name'))
            ->orderBy('pace.id', 'DESC');
        }
    
        else
        {
          $pace_list = Pace::join('classification_level', 'pace.classification_level_id', '=', 'classification_level.id')
            ->join('subject', 'pace.subject_id', '=','subject.id')
            ->where('pace.classification_level_id','=',$classification_level_id)
            ->where('pace.subject_id','=',$subject_id)
            ->where('pace.id','!=',0)
            ->select(array('pace.id', 'classification_level.level', 'subject.name', 'pace.pace_name'))
            ->orderBy('pace.id', 'DESC');
        }
    
        return Datatables::of( $pace_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'pace/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'pace/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }
}
