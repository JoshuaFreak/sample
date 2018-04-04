<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\ClassificationLevel;
use App\Models\PaymentInstallment;
use App\Models\PaymentSchemeDetails;
use App\Models\Term;
use App\Http\Requests\PaymentSchemeDetailsRequest;
use App\Http\Requests\PaymentSchemeDetailsEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class PaymentSchemeDetailsController extends AccountingMainController {
   


    /*
    return a list of country in json format based on a term
    **/
    // public function search(){
    //   $term = Input::get('term');
    //   $result = PaymentSchemeDetails::where('description','LIKE','%$term%')->get();
    //   return Response::json($result);
    // }

   // public function dataJson(){
   //        $condition = \Input::all();
   //        $query = PaymentSchemeDetails::select();

   //        foreach($condition as $column => $value)
   //        {
   //          $query->where($column, '=', $value);
   //        }
   //        // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
   //       $payment_installment = $query->select([ 'id as value','description as text'])->get();

   //        return response()->json($payment_installment);
   //  }
    public function schemeDataJson(){
          $condition = \Input::all();
          $query = PaymentSchemeDetails::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
         $payment_installment = $query->select([ 'id as value','date','amount as text'])->get();

          return response()->json($payment_installment);
    }

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $term_list = Term::orderBy('term.term_name','ASC')->get();
        // $payment_installment_list = PaymentInstallment::orderBy('payment_installment.name','ASC')->get();
        // Show the page
        return view('payment_installment.index', compact('classification_level_list','term_list'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
        $action = 0;
        $term_list = Term::all();
        $classification_level_list = ClassificationLevel::all();
        $payment_installment_list = PaymentInstallment::all();
  
        // Show the page
        return view('payment_installment.create', compact('term_list','classification_level_list', 'payment_installment_list', 'action'));
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(PaymentSchemeDetailsRequest $payment_scheme_details_request) {

        $payment_scheme_details = new PaymentSchemeDetails();
        $payment_scheme_details -> amount = $payment_scheme_details_request->amount;
        $payment_scheme_details -> date = $payment_scheme_details_request->date;
        $payment_scheme_details -> term_id = $payment_scheme_details_request->term_id;
        $payment_scheme_details -> classification_level_id = $payment_scheme_details_request->classification_level_id;
        $payment_scheme_details -> payment_installment_id = $payment_scheme_details_request->payment_installment_id;
        $payment_scheme_details -> save();

        $success = \Lang::get('payment_scheme_details.create_success').' : '.$payment_scheme_details->amount ; 
        return redirect('payment_installment/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $payment_scheme_details = PaymentSchemeDetails::find($id);
        $action = 1;
        $term_list = Term::all();
        $classification_level_list = ClassificationLevel::all();
        $payment_installment_list = PaymentInstallment::all();
       //var_dump($its_customs_office);
        return view('payment_installment/edit',compact('payment_scheme_details', 'action', 'term_list', 'classification_level_list', 'payment_installment_list'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(PaymentSchemeDetailsEditRequest $payment_scheme_details_edit_request, $id) {
      
        $payment_scheme_details = PaymentSchemeDetails::find($id);
        $payment_scheme_details -> amount = $payment_scheme_details_edit_request->amount;
        $payment_scheme_details -> date = $payment_scheme_details_edit_request->date;
        $payment_scheme_details -> term_id = $payment_scheme_details_edit_request->term_id;
        $payment_scheme_details -> classification_level_id = $payment_scheme_details_edit_request->classification_level_id;
        $payment_scheme_details -> payment_installment_id = $payment_scheme_details_edit_request->payment_installment_id;
        // $payment_scheme_details -> updated_by_id = Auth::id();
        $payment_scheme_details -> save();

        return redirect('payment_installment');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $payment_scheme_details = PaymentSchemeDetails::find($id);
        $action = 1;
        $term_list = Term::all();
        $classification_level_list = ClassificationLevel::all();
        $payment_installment_list = PaymentInstallment::all();
        // Show the page
        return view('payment_installment/delete', compact('payment_scheme_details', 'term_list', 'classification_level_list', 'payment_installment_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $payment_scheme_details = PaymentSchemeDetails::find($id);
        $payment_scheme_details->delete();
        return redirect('payment_installment');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');
        if($classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null) 
        {

          $payment_installment_list = PaymentSchemeDetails::join('term', 'payment_scheme_details.term_id', '=', 'term.id')
                ->join('classification_level', 'payment_scheme_details.classification_level_id', '=', 'classification_level.id')
                ->join('payment_installment', 'payment_scheme_details.payment_installment_id', '=', 'payment_installment.id')
                ->where('payment_scheme_details.classification_level_id','=',$classification_level_id)
                ->where('payment_scheme_details.term_id','=',$term_id)
                ->select(array('payment_scheme_details.id','payment_installment.name','payment_scheme_details.date','payment_scheme_details.amount','term.term_name','classification_level.level'))
                ->orderBy('payment_scheme_details.id', 'ASC');
        }
        elseif($classification_level_id != "" && $classification_level_id != null) 
        {

          $payment_installment_list = PaymentSchemeDetails::join('term', 'payment_scheme_details.term_id', '=', 'term.id')
                ->join('classification_level', 'payment_scheme_details.classification_level_id', '=', 'classification_level.id')
                ->join('payment_installment', 'payment_scheme_details.payment_installment_id', '=', 'payment_installment.id')
                ->where('payment_scheme_details.classification_level_id','=',$classification_level_id)
                ->select(array('payment_scheme_details.id','payment_installment.name','payment_scheme_details.date','payment_scheme_details.amount','term.term_name','classification_level.level'))
                ->orderBy('payment_scheme_details.id', 'ASC');
        }
        elseif($term_id != "" && $term_id != null) 
        {

          $payment_installment_list = PaymentSchemeDetails::join('term', 'payment_scheme_details.term_id', '=', 'term.id')
                ->join('classification_level', 'payment_scheme_details.classification_level_id', '=', 'classification_level.id')
                ->join('payment_installment', 'payment_scheme_details.payment_installment_id', '=', 'payment_installment.id')
                ->where('payment_scheme_details.term_id','=',$term_id)
                ->select(array('payment_scheme_details.id','payment_installment.name','payment_scheme_details.date','payment_scheme_details.amount','term.term_name','classification_level.level'))
                ->orderBy('payment_scheme_details.id', 'ASC');
        }
        else
        {

          $payment_installment_list = PaymentSchemeDetails::join('term', 'payment_scheme_details.term_id', '=', 'term.id')
                ->join('classification_level', 'payment_scheme_details.classification_level_id', '=', 'classification_level.id')
                ->join('payment_installment', 'payment_scheme_details.payment_installment_id', '=', 'payment_installment.id')
                ->where('payment_scheme_details.classification_level_id','=',$classification_level_id)
                ->where('payment_scheme_details.term_id','=',$term_id)
                ->select(array('payment_scheme_details.id','payment_installment.name','payment_scheme_details.date','payment_scheme_details.amount','term.term_name','classification_level.level'))
                ->orderBy('payment_scheme_details.id', 'ASC');

        }
    
        return Datatables::of($payment_installment_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'payment_installment/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'payment_installment/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->remove_column('id')
            ->make();
    }
   /**
     * Reorder items
     *
     * @param items list
     * @return items from @param
     */
    public function getReorder(ReorderRequest $request) {
        $list = $request->list;
        $items = explode(",", $list);
        $order = 1;                 
        foreach ($items as $value) {
            if ($value != '') {
                PaymentSchemeDetails::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
