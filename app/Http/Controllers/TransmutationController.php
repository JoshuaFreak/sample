<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Transmutation;
use App\Http\Requests\TransmutationRequest;
use App\Http\Requests\TransmutationEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TransmutationController extends BaseController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
  
        $transmutation_list = Transmutation::groupBy('transmutation.perfect_score')->orderBy('transmutation.id','ASC')->get();
        return view('transmutation.index', compact('transmutation_list'));
        // Show the page
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('transmutation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(TransmutationRequest $transmutation_request) {

        $transmutation = new Transmutation();
        $transmutation -> perfect_score = $transmutation_request->perfect_score;
        $transmutation -> score = $transmutation_request->score;
        $transmutation -> equivalent = $transmutation_request->equivalent;
        $transmutation -> created_by_id = Auth::id();
        $transmutation -> save();

        $success = \Lang::get('transmutation.create_success').' '.$transmutation->perfect_score. ", Score of ".$transmutation->score." and the Equivalent Grade is ".$transmutation->equivalent. ".";
        return redirect('transmutation/create')->withSuccess( $success);
    }

    /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $transmutation = Transmutation::find($id);
       //var_dump($its_customs_office);
        return view('transmutation/edit',compact('transmutation'));
      
    }

    /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(TransmutationEditRequest $transmutation_edit_request, $id) {
      
        $transmutation = Transmutation::find($id);
        $transmutation -> perfect_score = $transmutation_edit_request->perfect_score;
        $transmutation -> score = $transmutation_edit_request->score;
        $transmutation -> equivalent = $transmutation_edit_request->equivalent;
        $transmutation -> updated_by_id = Auth::id();
        $transmutation -> save();

        return redirect('transmutation');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $transmutation = Transmutation::find($id);
        // Show the page
        return view('transmutation/delete', compact('transmutation'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $transmutation = Transmutation::find($id);
        $transmutation->delete();
        return redirect('transmutation');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

      // $id = \Input::get('transmutation_id');
      //   if($id != "" && $id != null) 
      //   {

      //     $transmutation_list = Transmutation::where('transmutation.id','=',$id)
      //           ->select(array('transmutation.id', 'transmutation.perfect_score', 'transmutation.score', 'transmutation.equivalent'))
      //           ->orderBy('transmutation.perfect_score', 'ASC');
      //   }
      //   elseif($id != "" && $id != null && $id != 0)
      //   {

      //     $transmutation_list = Transmutation::where('transmutation.id','=',$id)
      //           ->select(array('transmutation.id', 'transmutation.perfect_score', 'transmutation.score', 'transmutation.equivalent'))
      //           ->orderBy('transmutation.perfect_score', 'ASC');
      //    }
    
      //   else
      //   {
      //     $transmutation_list = Transmutation::where('transmutation.id','=',$id)
      //           ->select(array('transmutation.id', 'transmutation.perfect_score', 'transmutation.score', 'transmutation.equivalent'))
      //           ->orderBy('transmutation.perfect_score', 'ASC');
      //   }

         $transmutation_list = Transmutation::select(array('transmutation.id', 'transmutation.perfect_score', 'transmutation.score', 'transmutation.equivalent'))
            ->orderBy('transmutation.perfect_score', 'ASC');

        return Datatables::of( $transmutation_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'transmutation/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'transmutation/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
