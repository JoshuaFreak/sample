<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Http\Requests\ClassificationRequest;
use App\Http\Requests\ClassificationEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ClassificationController extends RegistrarMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
       
 
        // Show the page
        return view('classification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('classification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(ClassificationRequest $classification_request) {

        $classification = new Classification();
        $classification -> classification_name = $classification_request->classification_name;
        $classification -> order = $classification_request->order;
        $classification -> created_by_id = Auth::id();
        $classification -> save();

        $success = \Lang::get('classification.create_success').' : '.$classification->classification_name ; 
        return redirect('classification/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $classification = Classification::find($id);
       //var_dump($its_customs_office);
        return view('classification/edit',compact('classification'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(ClassificationEditRequest $classification_edit_request, $id) {
      
        $classification = Classification::find($id);
        $classification -> classification_name = $classification_edit_request->classification_name;
        $classification -> order = $classification_edit_request->order;
        $classification -> updated_by_id = Auth::id();
        $classification -> save();

        return redirect('classification');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $classification = Classification::find($id);
        // Show the page
        return view('classification/delete', compact('classification'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $classification = Classification::find($id);
        $classification->delete();
        return redirect('classification');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $classification_list = Classification::where('classification.id', '!=', 0)
                ->select(array('classification.id', 'classification.classification_name', 'classification.order'))
                ->orderBy('classification.order', 'ASC');
    
        return Datatables::of( $classification_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'classification/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'classification/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
