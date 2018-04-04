<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\Term;
use App\Http\Requests\TermRequest;
use App\Http\Requests\TermEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TermController extends RegistrarMainController {

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        return view('term.index', compact('classification_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        $action = 0;
        $classification_list = Classification::orderBy('classification.order')->get();
        return view('term.create', compact('classification_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(TermRequest $term_request) {

        $term = new Term();
        // $term -> classification_id = $term_request->classification_id;
        $term -> term_name = $term_request->term_name;
        $term -> date_start = $term_request->date_start;
        $term -> date_end = $term_request->date_end;
        $term -> created_by_id = Auth::id();
        $term -> save();

        $success = \Lang::get('term.create_success').' : '.$term->term_name ; 
        return redirect('term/create')->withSuccess( $success);
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
        $term = Term::find($id);
       //var_dump($its_customs_office);
        return view('term/edit',compact('term', 'classification_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(TermEditRequest $term_edit_request, $id) {
      
        $term = Term::find($id);
        // $term -> classification_id = $term_edit_request->classification_id;
        $term -> term_name = $term_edit_request->term_name;
        $term -> date_start = $term_edit_request->date_start;
        $term -> date_end = $term_edit_request->date_end;
        $term -> updated_by_id = Auth::id();
        $term -> save();

        return redirect('term');
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
        $term = Term::find($id);
        // Show the page
        return view('term/delete', compact('term', 'classification_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $term = Term::find($id);
        $term->delete();
        return redirect('term');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {


    $term_id = \Input::get('term_id');
    // $classification_id = \Input::get('classification_id');
    //     if($classification_id != "" && $classification_id != null) 
    //     {

    //       $term_list = Term::join('classification','term.classification_id','=','classification.id')
    //             ->where('term.classification_id','=',$classification_id)
    //             ->select(array('term.id','classification.classification_name', 'term.term_name', 'term.date_start', 'term.date_end'))
    //             ->orderBy('term.term_name', 'DESC');
    //     }
    //     elseif($classification_id != "" && $classification_id != null && $classification_id != 0)
    //     {

    //       $term_list = Term::join('classification','term.classification_id','=','classification.id')
    //             ->where('term.classification_id','=',$classification_id)
    //             ->select(array('term.id','classification.classification_name', 'term.term_name', 'term.date_start', 'term.date_end'))
    //             ->orderBy('term.term_name', 'DESC');
    //     }
    
    //     else
    //     {
            $term_list = Term::join('classification','term.classification_id','=','classification.id')
                // ->where('term.classification_id','=',$classification_id)
                ->where('term.id','!=',0)
                ->select(array('term.id','term.term_name', 'term.date_start', 'term.date_end'))
                ->orderBy('term.term_name', 'DESC');

        // }
        return Datatables::of($term_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'term/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'term/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')            
            ->remove_column('id')
            ->make();




    }


}
