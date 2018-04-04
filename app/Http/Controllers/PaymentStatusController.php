<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\PaymentStatus;
use App\Http\Requests\PaymentStatusRequest;
use App\Http\Requests\PaymentStatusEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class PaymentStatusController extends AccountingMainController {

    /*
    return a list of country in json format based on a term
    **/
    public function search(){
      $term = Input::get('term');
      $result = PaymentStatus::where('payment_status_no','LIKE','%$term%').orWhere('payment_status_name','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function dataJson(){
      $result = PaymentStatus::where('is_active',1)->get();
      return response()->json($result);
    }

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        // Show the page
        return view('payment_status.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('payment_status.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(PaymentStatusRequest $payment_status_request) {

        $payment_status = new PaymentStatus();
        $payment_status -> payment_status_name = $payment_status_request->payment_status_name;
        // $payment_status -> created_by_id = Auth::id();
        $payment_status -> save();

        $success = \Lang::get('payment_status.create_success').' : '.$payment_status->payment_status_name ; 
        return redirect('payment_status/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $payment_status = PaymentStatus::find($id);
       //var_dump($its_customs_office);
        return view('payment_status/edit',compact('payment_status'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(PaymentStatusEditRequest $payment_status_edit_request, $id) {
      
        $payment_status = PaymentStatus::find($id);
        $payment_status -> payment_status_name = $payment_status_edit_request->payment_status_name;
        // $payment_status -> updated_by_id = Auth::id();
        $payment_status -> save();

        return redirect('payment_status');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $payment_status = PaymentStatus::find($id);
        // Show the page
        return view('payment_status/delete', compact('payment_status'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $payment_status = PaymentStatus::find($id);
        $payment_status->delete();
        return redirect('payment_status');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
          $payment_status_list = PaymentStatus::select(array('payment_status.id','payment_status.payment_status_name'))
              ->orderBy('payment_status.payment_status_name', 'ASC');

    
        return Datatables::of($payment_status_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'payment_status/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("its/form.edit") }}</a>
                    <a href="{{{ URL::to(\'payment_status/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("its/form.delete") }}</a>
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
                PaymentStatus::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
