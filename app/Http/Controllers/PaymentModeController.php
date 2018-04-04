<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\PaymentMode;
use App\Http\Requests\PaymentModeRequest;
use App\Http\Requests\PaymentModeEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class PaymentModeController extends AccountingMainController {
   

    /*
    return a list of country in json format based on a term
    **/
    public function search(){
      $term = Input::get('term');
      $result = PaymentMode::where('payment_mode_name','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function dataJson(){
      $result = PaymentMode::where('is_active',1)->get();
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
        return view('payment_mode.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('payment_mode.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(PaymentModeRequest $payment_mode_request) {

        $payment_mode = new PaymentMode();
        $payment_mode -> payment_mode_name = $payment_mode_request->payment_mode_name;
        $payment_mode -> is_default = $payment_mode_request->is_default;
        // $payment_mode -> created_by_id = Auth::id();
        $payment_mode -> save();

        $success = \Lang::get('payment_mode.create_success').' : '.$payment_mode->payment_mode_name ; 
        return redirect('payment_mode/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $payment_mode = PaymentMode::find($id);
       //var_dump($its_customs_office);
        return view('payment_mode/edit',compact('payment_mode'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(PaymentModeEditRequest $payment_mode_edit_request, $id) {
      
        $payment_mode = PaymentMode::find($id);
        $payment_mode -> payment_mode_name = $payment_mode_edit_request->payment_mode_name;
        $payment_mode -> is_default = $payment_mode_edit_request->is_default;
        // $payment_mode -> updated_by_id = Auth::id();
        $payment_mode -> save();

        return redirect('payment_mode');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $payment_mode = PaymentMode::find($id);
        // Show the page
        return view('payment_mode/delete', compact('payment_mode'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $payment_mode = PaymentMode::find($id);
        $payment_mode->delete();
        return redirect('payment_mode');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
          $payment_mode_list = PaymentMode::select(array('payment_mode.id','payment_mode.payment_mode_name','payment_mode.is_default'))
              ->orderBy('payment_mode.payment_mode_name', 'ASC');

    
        return Datatables::of($payment_mode_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'payment_mode/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("its/form.edit") }}</a>
                    <a href="{{{ URL::to(\'payment_mode/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("its/form.delete") }}</a>
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
                PaymentMode::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
