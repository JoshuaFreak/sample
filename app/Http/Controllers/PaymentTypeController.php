<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\PaymentType;
use App\Http\Requests\PaymentTypeRequest;
use App\Http\Requests\PaymentTypeEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class PaymentTypeController extends AccountingMainController {
   


    /*
    return a list of country in json format based on a term
    **/
    public function search(){
      $term = Input::get('term');
      $result = PaymentType::where('description','LIKE','%$term%')->get();
      return Response::json($result);
    }

   public function dataJson(){
          $condition = \Input::all();
          $query = PaymentType::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
         $payment_type = $query->select([ 'id as value','amount as text'])->get();

          return response()->json($payment_type);
    }

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        // Show the page
        return view('payment_type.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('payment_type.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(PaymentTypeRequest $payment_type_request) {

        $payment_type = new PaymentType();
        $payment_type -> description = $payment_type_request->description;
        $payment_type -> amount = $payment_type_request->amount;
        $payment_type -> is_ledger = $payment_type_request->is_ledger;
        $payment_type -> save();

        $success = \Lang::get('payment_type.create_success').' : '.$payment_type->description ; 
        return redirect('payment_type/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $payment_type = PaymentType::find($id);
       //var_dump($its_customs_office);
        return view('payment_type/edit',compact('payment_type'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(PaymentTypeEditRequest $payment_type_edit_request, $id) {
      
        $payment_type = PaymentType::find($id);
        $payment_type -> description = $payment_type_edit_request->description;
        $payment_type -> is_ledger = $payment_type_edit_request->is_ledger;
        $payment_type -> amount = $payment_type_edit_request->amount;
        // $payment_type -> updated_by_id = Auth::id();
        $payment_type -> save();

        return redirect('payment_type');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $payment_type = PaymentType::find($id);
        // Show the page
        return view('payment_type/delete', compact('payment_type'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $payment_type = PaymentType::find($id);
        $payment_type->delete();
        return redirect('payment_type');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
          $payment_type_list = PaymentType::where('payment_type.description', '!=', '')
              ->select(array('payment_type.id','payment_type.description','payment_type.is_ledger'))
              ->orderBy('payment_type.description', 'ASC');
    
        return Datatables::of($payment_type_list)
        ->edit_column('is_ledger', '@if ($is_ledger=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
            ->add_column('actions', '<a href="{{{ URL::to(\'payment_type/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'payment_type/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
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
                PaymentType::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
