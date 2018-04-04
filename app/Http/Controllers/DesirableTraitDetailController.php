<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Desirabletrait;
use App\Models\DesirableTraitDetail;
use App\Http\Requests\DesirableTraitDetailRequest;
use App\Http\Requests\DesirableTraitDetailEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class DesirableTraitDetailController extends DeansPortalMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // $desirable_trait_list = Desirabletrait::all();
        return view('desirable_trait_detail.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        
        // $action = 0;
        // $desirable_trait_list = Desirabletrait::all();

        return view('desirable_trait_detail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(DesirableTraitDetailRequest $desirable_trait_detail_request) {

        $desirable_trait_detail = new DesirableTraitDetail();
        // $desirable_trait_detail ->desirable_trait_id = $desirable_trait_detail_request->desirable_trait_id;
        $desirable_trait_detail ->desirable_trait_detail_name = $desirable_trait_detail_request->desirable_trait_detail_name;
        $desirable_trait_detail ->created_by_id = Auth::id();
        $desirable_trait_detail -> save();

        $success = \Lang::get('desirable_trait_detail.create_success').' : '.$desirable_trait_detail->desirable_trait_detail_name ; 
        return redirect('desirable_trait_detail/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        
        $action = 1;
        // $desirable_trait_list = Desirabletrait::all();
        $desirable_trait_detail = DesirableTraitDetail::find($id);
       //var_dump($its_customs_office);
        return view('desirable_trait_detail/edit',compact('desirable_trait_detail',  'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(DesirableTraitDetailEditRequest $desirable_trait_detail_edit_request, $id) {
      
        $desirable_trait_detail = DesirableTraitDetail::find($id);
        // $desirable_trait_detail ->desirable_trait_id = $desirable_trait_detail_edit_request->desirable_trait_id;
        $desirable_trait_detail ->desirable_trait_detail_name = $desirable_trait_detail_edit_request->desirable_trait_detail_name;
        $desirable_trait_detail ->updated_by_id = Auth::id();
        $desirable_trait_detail -> save();

        return redirect('desirable_trait_detail');
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
        // $desirable_trait_list = Desirabletrait::all();
        $desirable_trait_detail = DesirableTraitDetail::find($id);
        // Show the page
        return view('desirable_trait_detail/delete', compact('desirable_trait_detail', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $desirable_trait_detail = DesirableTraitDetail::find($id);
        $desirable_trait_detail->delete();
        return redirect('desirable_trait_detail');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $desirable_trait_detail_list = DesirableTraitDetail::where('desirable_trait_detail.id', '!=', 0)
            ->select(array('desirable_trait_detail.id','desirable_trait_detail.desirable_trait_detail_name'))
            ->orderBy('desirable_trait_detail.desirable_trait_detail_name', 'ASC');
    
        return Datatables::of( $desirable_trait_detail_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'desirable_trait_detail/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'desirable_trait_detail/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
