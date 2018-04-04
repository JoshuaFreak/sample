<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\Discount;
use App\Http\Requests\DiscountRequest;
use App\Http\Requests\DiscountEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class DiscountController extends AccountingMainController {
  

    /*
    return a list of country in json format based on a term
    **/
    public function search(){
      $term = Input::get('term');
      $result = Discount::where('discount_name','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function dataJson(){
          $condition = \Input::all();
          $query = Discount::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
         $discount = $query->select([ 'id as value','percentage_discount as text'])->get();

          return response()->json($discount);
    }
 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        // Show the page
        return view('discount.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('discount.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(DiscountRequest $discount_request) {

        $discount = new Discount();
        $discount -> discount_name = $discount_request->discount_name;
        $discount -> percentage_discount = $discount_request->percentage_discount;
        $discount -> is_active = $discount_request->is_active;
        // $discount -> created_by_id = Auth::id();
        $discount -> save();

        $success = \Lang::get('discount.create_success').' : '.$discount->discount_name ; 
        return redirect('discount/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $discount = Discount::find($id);
       //var_dump($its_customs_office);
        return view('discount/edit',compact('discount'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(DiscountEditRequest $discount_edit_request, $id) {
      
        $discount = Discount::find($id);
        $discount -> discount_name = $discount_edit_request->discount_name;
        $discount -> percentage_discount = $discount_edit_request->percentage_discount;
        $discount -> is_active = $discount_edit_request->is_active;
        // $discount -> updated_by_id = Auth::id();
        $discount -> save();

        return redirect('discount');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $discount = Discount::find($id);
        // Show the page
        return view('discount/delete', compact('discount'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $discount = Discount::find($id);
        $discount->delete();
        return redirect('discount');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
       // $gen_language = GenLanguage::whereNull('gen_language.deleted_at')
          $discount_list = Discount::select(array('discount.id','discount.discount_name','discount.percentage_discount','discount.is_active'))
              ->orderBy('discount.discount_name', 'ASC');
    
        return Datatables::of($discount_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'discount/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'discount/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->remove_column('id')
            ->editColumn('is_active','@if($is_active == 1)
                                        <i class="glyphicon glyphicon-ok"></i>
                                      @else
                                        <i class="glyphicon glyphicon-remove"></i>
                                      @endif

                        ')
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
                Discount::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
