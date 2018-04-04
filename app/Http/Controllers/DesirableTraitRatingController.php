<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\DesirableTraitRating;
use App\Http\Requests\DesirableTraitRatingRequest;
use App\Http\Requests\DesirableTraitRatingEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class DesirableTraitRatingController extends DeansPortalMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // Show the page
        return view('desirable_trait_rating.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('desirable_trait_rating.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(DesirableTraitRatingRequest $desirable_trait_rating_request) {

        $desirable_trait_rating = new DesirableTraitRating();
        $desirable_trait_rating ->code = $desirable_trait_rating_request->code;
        $desirable_trait_rating ->name = $desirable_trait_rating_request->name;
        $desirable_trait_rating ->created_by_id = Auth::id();
        $desirable_trait_rating -> save();

        $success = \Lang::get('desirable_trait_rating.create_success').' : '.$desirable_trait_rating->name ; 
        return redirect('desirable_trait_rating/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $desirable_trait_rating = DesirableTraitRating::find($id);
       //var_dump($its_customs_office);
        return view('desirable_trait_rating/edit',compact('desirable_trait_rating'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(DesirableTraitRatingEditRequest $desirable_trait_rating_edit_request, $id) {
      
        $desirable_trait_rating = DesirableTraitRating::find($id);
        $desirable_trait_rating ->code = $desirable_trait_rating_edit_request->code;
        $desirable_trait_rating ->name = $desirable_trait_rating_edit_request->name;
        $desirable_trait_rating ->updated_by_id = Auth::id();
        $desirable_trait_rating -> save();

        return redirect('desirable_trait_rating');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $desirable_trait_rating = DesirableTraitRating::find($id);
        // Show the page
        return view('desirable_trait_rating/delete', compact('desirable_trait_rating'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $desirable_trait_rating = DesirableTraitRating::find($id);
        $desirable_trait_rating->delete();
        return redirect('desirable_trait_rating');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $desirable_trait_rating_list = DesirableTraitRating::where('desirable_trait_rating.id', '!=', 0)
            ->select(array('desirable_trait_rating.id', 'desirable_trait_rating.code', 'desirable_trait_rating.name'))
            ->orderBy('desirable_trait_rating.name', 'ASC');
    
        return Datatables::of( $desirable_trait_rating_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'desirable_trait_rating/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'desirable_trait_rating/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
