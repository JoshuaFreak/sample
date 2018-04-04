<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\DesirableTrait;
use App\Http\Requests\DesirableTraitRequest;
use App\Http\Requests\DesirableTraitEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class DesirableTraitController extends DeansPortalMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // Show the page
        return view('desirable_trait.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('desirable_trait.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(DesirableTraitRequest $desirable_trait_request) {

        $desirable_trait = new DesirableTrait();
        $desirable_trait ->desirable_trait_name = $desirable_trait_request->desirable_trait_name;
        $desirable_trait ->created_by_id = Auth::id();
        $desirable_trait -> save();

        $success = \Lang::get('desirable_trait.create_success').' : '.$desirable_trait->desirable_trait_name ; 
        return redirect('desirable_trait/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $desirable_trait = DesirableTrait::find($id);
       //var_dump($its_customs_office);
        return view('desirable_trait/edit',compact('desirable_trait'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(DesirableTraitEditRequest $desirable_trait_edit_request, $id) {
      
        $desirable_trait = DesirableTrait::find($id);
        $desirable_trait ->desirable_trait_name = $desirable_trait_edit_request->desirable_trait_name;
        $desirable_trait ->updated_by_id = Auth::id();
        $desirable_trait -> save();

        return redirect('desirable_trait');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $desirable_trait = DesirableTrait::find($id);
        // Show the page
        return view('desirable_trait/delete', compact('desirable_trait'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $desirable_trait = DesirableTrait::find($id);
        $desirable_trait->delete();
        return redirect('desirable_trait');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $desirable_trait_list = DesirableTrait::select(array('desirable_trait.id', 'desirable_trait.desirable_trait_name'))
        ->orderBy('desirable_trait.desirable_trait_name', 'ASC');
    
        return Datatables::of( $desirable_trait_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'desirable_trait/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'desirable_trait/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
