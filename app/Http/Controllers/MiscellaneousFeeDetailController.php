<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\Classification;
use App\Models\MiscellaneousFee;
use App\Models\MiscellaneousFeeDetail;
use App\Models\Program;
use App\Models\Term;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MiscellaneousFeeDetailRequest;
use App\Http\Requests\MiscellaneousFeeDetailEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;    
use Hash;

class MiscellaneousFeeDetailController extends AccountingMainController {
  


   public function dataJson(){
          $condition = \Input::all();
          $query = MiscellaneousFeeDetail::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
         $miscellaneous_fee_detail = $query->select([ 'id as value','amount as text'])->get();

          return response()->json($miscellaneous_fee_detail);
    }

   public function dataJsonList(){
          $condition = \Input::all();
          $query = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
                  ->select('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.classification_id');

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
         $miscellaneous_fee_detail = $query->select([ 'miscellaneous_fee_detail.id as value','description as text'])->get();

          return response()->json($miscellaneous_fee_detail);
    }

    public function index()
    {
        // Show the page
        $program_list = Program::where('classification_id',5)->orderBy('program.id','ASC')->get();
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $term_list = Term::orderBy('term.id','DESC')->get();
        return view('miscellaneous_fee_detail.index', compact('program_list', 'classification_list', 'term_list'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
        $action = 0;
        $miscellaneous_fee_list = MiscellaneousFee::all();
        $program_list = Program::all();
        $classification_list = Classification::all();
        $term_list = Term::all();
        // Show the page
        return view('miscellaneous_fee_detail.create',compact('action', 'miscellaneous_fee_list', 'program_list', 'classification_list', 'term_list'));
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(MiscellaneousFeeDetailRequest $miscellaneous_fee_detail_request) {

        $miscellaneous_fee_detail = new MiscellaneousFeeDetail();
        $miscellaneous_fee_detail ->classification_id = $miscellaneous_fee_detail_request->classification_id;
        $miscellaneous_fee_detail ->miscellaneous_fee_id = $miscellaneous_fee_detail_request->miscellaneous_fee_id;
        $miscellaneous_fee_detail ->amount = $miscellaneous_fee_detail_request->amount;
        $miscellaneous_fee_detail ->program_id = $miscellaneous_fee_detail_request->program_id;
        $miscellaneous_fee_detail ->term_id = $miscellaneous_fee_detail_request->term_id;
        $miscellaneous_fee_detail ->created_by_id = Auth::id();
        $miscellaneous_fee_detail -> save();

        $query = MiscellaneousFee::find($miscellaneous_fee_detail->miscellaneous_fee_id);
        $success = \Lang::get('miscellaneous_fee_detail.create_success').' : '.$query->description ; 
        return redirect('miscellaneous_fee_detail/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
      
        $action = 1;
        $miscellaneous_fee_detail = MiscellaneousFeeDetail::find($id);
        $miscellaneous_fee= MiscellaneousFee::find($miscellaneous_fee_detail->miscellaneous_fee_id);

        $miscellaneous_fee_list = MiscellaneousFee::all();
        $classification_list = Classification::all();
        $program_list = Program::all();
        $term_list = Term::all();
        // Show the page
        return view('miscellaneous_fee_detail.edit',compact('action', 'miscellaneous_fee_detail','miscellaneous_fee','miscellaneous_fee_list', 'classification_list', 'program_list', 'term_list'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(MiscellaneousFeeDetailEditRequest $miscellaneous_fee_detail_edit_request, $id) {
      
        $miscellaneous_fee_detail = MiscellaneousFeeDetail::find($id);
        $miscellaneous_fee_detail ->amount = $miscellaneous_fee_detail_edit_request->amount;
        $miscellaneous_fee_detail ->classification_id = $miscellaneous_fee_detail_edit_request->classification_id;
        $miscellaneous_fee_detail ->program_id = $miscellaneous_fee_detail_edit_request->program_id;
        $miscellaneous_fee_detail ->term_id = $miscellaneous_fee_detail_edit_request->term_id;
        $miscellaneous_fee_detail ->updated_by_id = Auth::id();
        $miscellaneous_fee_detail -> save();

        return redirect('miscellaneous_fee_detail');
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
        $miscellaneous_fee_detail = MiscellaneousFeeDetail::find($id);

        $miscellaneous_fee_list = MiscellaneousFee::all();
        $classification_list = Classification::all();
        $program_list = Program::all();
        $term_list = Term::all();
        // Show the page
        return view('miscellaneous_fee_detail.delete',compact('action', 'miscellaneous_fee_detail','miscellaneous_fee_list', 'classification_list', 'program_list', 'term_list'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $miscellaneous_fee_detail = MiscellaneousFeeDetail::find($id);
        $miscellaneous_fee_detail->delete();
        return redirect('miscellaneous_fee_detail');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */


 public function data()
    {
        $program_id = \Input::get('program_id');
        $classification_id = \Input::get('classification_id');
        $term_id = \Input::get('term_id');
        if($classification_id != "" && $classification_id != null && $program_id != "" && $program_id != null && $term_id != "" && $term_id != null) 
        {
            $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
              ->join('program','miscellaneous_fee_detail.program_id','=','program.id')
              ->join('term','miscellaneous_fee_detail.term_id','=','term.id')
              ->select(array('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount','program.program_code','term.term_name'))
              ->where('miscellaneous_fee_detail.classification_id','=',$classification_id)
              ->where('miscellaneous_fee_detail.program_id','=',$program_id)
              ->where('miscellaneous_fee_detail.term_id','=',$term_id)
              ->orderBy('miscellaneous_fee_detail.created_at', 'DESC');
        }
        elseif($classification_id != "" && $classification_id != null && $program_id != "" && $program_id != null) 
        {
            $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
              ->join('program','miscellaneous_fee_detail.program_id','=','program.id')
              ->join('term','miscellaneous_fee_detail.term_id','=','term.id')
              ->select(array('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount','program.program_code','term.term_name'))
              ->where('miscellaneous_fee_detail.classification_id','=',$classification_id)
              ->where('miscellaneous_fee_detail.program_id','=',$program_id)
              ->orderBy('miscellaneous_fee_detail.created_at', 'DESC');
        }
        elseif($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null) 
        {
            $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
              ->join('program','miscellaneous_fee_detail.program_id','=','program.id')
              ->join('term','miscellaneous_fee_detail.term_id','=','term.id')
              ->select(array('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount','program.program_code','term.term_name'))
              ->where('miscellaneous_fee_detail.classification_id','=',$classification_id)
              ->where('miscellaneous_fee_detail.term_id','=',$term_id)
              ->orderBy('miscellaneous_fee_detail.created_at', 'DESC');
        }
        elseif($classification_id != "" && $classification_id != null) 
        {
            $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
              ->join('program','miscellaneous_fee_detail.program_id','=','program.id')
              ->join('term','miscellaneous_fee_detail.term_id','=','term.id')
              ->select(array('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount','program.program_code','term.term_name'))
              ->where('miscellaneous_fee_detail.classification_id','=',$classification_id)
              ->orderBy('miscellaneous_fee_detail.created_at', 'DESC');
        }
        else
        {
            $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')
              ->join('program','miscellaneous_fee_detail.program_id','=','program.id')
              ->join('term','miscellaneous_fee_detail.term_id','=','term.id')
              ->select(array('miscellaneous_fee_detail.id','miscellaneous_fee.description','miscellaneous_fee_detail.amount','program.program_code','term.term_name'))
              ->where('miscellaneous_fee_detail.classification_id','=',$classification_id)
              ->where('miscellaneous_fee_detail.program_id','=',$program_id)
              ->where('miscellaneous_fee_detail.term_id','=',$term_id)
              ->orderBy('miscellaneous_fee_detail.created_at', 'DESC');
        }
    
      return Datatables::of($miscellaneous_fee_detail_list)
          ->add_column('actions', '<a href="{{{ URL::to(\'miscellaneous_fee_detail/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                  <a href="{{{ URL::to(\'miscellaneous_fee_detail/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                  <input type="hidden" name="row" value="{{$id}}" id="row">')
          ->remove_column('id')
          ->make();
    }


}
