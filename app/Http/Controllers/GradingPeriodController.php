<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\GradingPeriod;
use App\Models\Term;
use App\Http\Requests\GradingPeriodRequest;
use App\Http\Requests\GradingPeriodEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class GradingPeriodController extends RegistrarMainController {

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // $classification_list = Classification::orderBy('classification.id','ASC')->get();
        // $term_list = Term::orderBy('term.term_name','ASC')->get();
        // return view('grading_period.index', compact('classification_list','term_list'));
        return view('grading_period.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        $action = 0;
        // $classification_list = Classification::orderBy('classification.id','ASC')->get();
        // $term_list = Term::orderBy('term.id','DESC')->get();
        // return view('grading_period.create', compact('classification_list','term_list', 'action'));
        return view('grading_period.create',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(GradingPeriodRequest $grading_period_request) {

        $grading_period = new GradingPeriod();
        // $grading_period -> classification_id = $grading_period_request->classification_id;
        $grading_period -> grading_period_name = $grading_period_request->grading_period_name;
        // $grading_period -> description = '-';
        // $grading_period -> default_class_standing_weight = $grading_period_request->default_class_standing_weight;
        // $grading_period -> default_major_exam_weight = $grading_period_request->default_major_exam_weight;
        // $grading_period -> term_id = $grading_period_request->term_id;
        $grading_period -> created_by_id = Auth::id();
        $grading_period -> save();

        $success = \Lang::get('grading_period.create_success').' : '.$grading_period->grading_period_name ; 
        return redirect('grading_period/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $action = 1;
        $grading_period = GradingPeriod::find($id);
        // $classification_list = Classification::orderBy('classification.id','ASC')->get();
        // $term_list = Term::orderBy('term.id','DESC')->get();
       //var_dump($its_customs_office);
        return view('grading_period/edit',compact('grading_period', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(GradingPeriodEditRequest $grading_period_edit_request, $id) {
      
        $grading_period = GradingPeriod::find($id);
        // $grading_period -> classification_id = $grading_period_edit_request->classification_id;
        $grading_period -> grading_period_name = $grading_period_edit_request->grading_period_name;
        // $grading_period -> description = '-';
        // $grading_period -> default_class_standing_weight = $grading_period_edit_request->default_class_standing_weight;
        // $grading_period -> default_major_exam_weight = $grading_period_edit_request->default_major_exam_weight;
        // $grading_period -> term_id = $grading_period_edit_request->term_id;
        $grading_period -> updated_by_id = Auth::id();
        $grading_period -> save();

        return redirect('grading_period');
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
        $grading_period = GradingPeriod::find($id);
        // $classification_list = Classification::orderBy('classification.id','ASC')->get();
        // $term_list = Term::orderBy('term.id','DESC')->get();
        // Show the page
        return view('grading_period/delete', compact('grading_period', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $grading_period = GradingPeriod::find($id);
        $grading_period->delete();
        return redirect('grading_period');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {


        $classification_id = \Input::get('classification_id');
        $term_id = \Input::get('term_id');
        // if($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null) 
        // {

        //     $grading_period_list = GradingPeriod::join('classification','grading_period.classification_id','=','classification.id')
        //         ->join('term','grading_period.term_id','=','term.id')
        //         ->where('grading_period.classification_id','=',$classification_id)
        //         ->where('grading_period.term_id','=',$term_id)
        //         ->select(array('grading_period.id','grading_period.grading_period_name','grading_period.default_class_standing_weight','grading_period.default_major_exam_weight','classification.classification_name','term.term_name'))
        //         ->orderBy('grading_period.id', 'ASC');
        // }
        // elseif($classification_id != "" && $classification_id != null) 
        // {

        //     $grading_period_list = GradingPeriod::join('classification','grading_period.classification_id','=','classification.id')
        //         ->join('term','grading_period.term_id','=','term.id')
        //         ->where('grading_period.classification_id','=',$classification_id)
        //         ->select(array('grading_period.id','grading_period.grading_period_name','grading_period.default_class_standing_weight','grading_period.default_major_exam_weight','classification.classification_name','term.term_name'))
        //         ->orderBy('grading_period.id', 'ASC');
        // }
        // else
        // {
            $grading_period_list = GradingPeriod::select(array('grading_period.id','grading_period.grading_period_name'))
                ->orderBy('grading_period.id', 'ASC');

        // }
        return Datatables::of($grading_period_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'grading_period/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'grading_period/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')            
            ->remove_column('id')
            ->make();




    }


}
