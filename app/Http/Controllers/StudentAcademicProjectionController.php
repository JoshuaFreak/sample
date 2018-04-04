<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\StudentAcademicProjection;
use App\Models\Subject;
use App\Http\Requests\StudentAcademicProjectionRequest;
use App\Http\Requests\StudentAcademicProjectionEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentAcademicProjectionController extends DeansPortalMainController {
   

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

        return view('student_academic_projection.index', compact('subject_list', 'classification_list', 'classification_level_list'));
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
        return view('student_academic_projection.create', compact('action','classification_list','classification_level_list','subject_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(StudentAcademicProjectionRequest $student_academic_projection_request) {

        $student_academic_projection = new StudentAcademicProjection();
        $student_academic_projection ->classification_level_id = $student_academic_projection_request->classification_level_id;
        $student_academic_projection ->student_academic_projection_name = $student_academic_projection_request->student_academic_projection_name;
        $student_academic_projection ->subject_id = $student_academic_projection_request->subject_id;
        $student_academic_projection ->created_by_id = Auth::id();
        $student_academic_projection -> save();

        $success = \Lang::get('student_academic_projection.create_success').' : '.$student_academic_projection->student_academic_projection_name ; 
        return redirect('student_academic_projection/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $action = 1;
        $student_academic_projection = StudentAcademicProjection::find($id);
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $subject_list = Subject::all();
        return view('student_academic_projection/edit',compact('student_academic_projection','action','classification_level_list','classification_list','subject_list'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(StudentAcademicProjectionEditRequest $student_academic_projection_edit_request, $id) {
      
        $student_academic_projection = Pace::find($id);
        $student_academic_projection ->student_academic_projection_name = $student_academic_projection_edit_request->student_academic_projection_name;
        $student_academic_projection ->classification_level_id = $student_academic_projection_edit_request->classification_level_id;
        $student_academic_projection ->subject_id = $student_academic_projection_edit_request->subject_id;
        $student_academic_projection ->updated_by_id = Auth::id();
        $student_academic_projection ->save();

        return redirect('student_academic_projection');
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
        $student_academic_projection = StudentAcademicProjection::find($id);
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $subject_list = Subject::all();
        // Show the page
        return view('student_academic_projection/delete', compact('student_academic_projection', 'action','classification_level_list','classification_list','subject_list'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $student_academic_projection = StudentAcademicProjection::find($id);
        $student_academic_projection->delete();
        return redirect('student_academic_projection');
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

          $student_academic_projection_list = StudentAcademicProjection::join('classification_level', 'student_academic_projection.classification_level_id', '=', 'classification_level.id')
            ->join('subject', 'student_academic_projection.subject_id', '=','subject.id')
            ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
            ->where('student_academic_projection.subject_id','=',$subject_id)
            ->where('student_academic_projection.id','!=',0)
            ->select(array('student_academic_projection.id', 'classification_level.level', 'subject.name', 'student_academic_projection.student_academic_projection_name'))
            ->orderBy('student_academic_projection.id', 'DESC');

        return Datatables::of( $student_academic_projection_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'student_academic_projection/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'student_academic_projection/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }
}
