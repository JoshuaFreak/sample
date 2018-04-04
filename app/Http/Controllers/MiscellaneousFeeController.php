<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\MiscellaneousFee;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MiscellaneousFeeRequest;
use App\Http\Requests\MiscellaneousFeeEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;    
use Hash;

class MiscellaneousFeeController extends AccountingMainController {
  

   public function dataJson(){
          $condition = \Input::all();
          $query = MiscellaneousFee::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
         $miscellaneous_fee = $query->select([ 'id as value','description as text'])->get();

          return response()->json($miscellaneous_fee);
    }


    public function dataJsonTypeahead(){
        $condition = \Input::all();
        $query = MiscellaneousFee::select('miscellaneous_fee.id', 'miscellaneous_fee.description');
      
        foreach($condition as $column => $value)
        { 
            if($column == 'query')
            {
              $query->where('description', 'LIKE', "%$value%");
            }
            else
            {
              $query->where($column, '=', $value);
            }   
        }

        $miscellaneous_fee = $query->get();

        return response()->json($miscellaneous_fee);
    }

    public function index()
    {
        // Show the page
        return view('miscellaneous_fee.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('miscellaneous_fee.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(MiscellaneousFeeRequest $miscellaneous_fee_request) {

        $miscellaneous_fee = new MiscellaneousFee();
        $miscellaneous_fee -> description = $miscellaneous_fee_request->description;
        $miscellaneous_fee -> is_active = $miscellaneous_fee_request->is_active;
        $miscellaneous_fee -> created_by_id = Auth::id();
        $miscellaneous_fee -> save();

        $success = \Lang::get('miscellaneous_fee.create_success').' : '.$miscellaneous_fee->description ; 
        return redirect('miscellaneous_fee/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
      
        $miscellaneous_fee = MiscellaneousFee::find($id);
        return view('miscellaneous_fee/edit',compact('miscellaneous_fee'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(MiscellaneousFeeEditRequest $miscellaneous_fee_edit_request, $id) {
      
        $miscellaneous_fee = MiscellaneousFee::find($id);
        $miscellaneous_fee -> description = $miscellaneous_fee_edit_request->description;
        $miscellaneous_fee -> is_active = $miscellaneous_fee_edit_request->is_active;
        $miscellaneous_fee -> updated_by_id = Auth::id();
        $miscellaneous_fee -> save();

        return redirect('miscellaneous_fee');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $miscellaneous_fee = MiscellaneousFee::find($id);
        // Show the page
        return view('miscellaneous_fee/delete', compact('miscellaneous_fee'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $miscellaneous_fee = MiscellaneousFee::find($id);
        $miscellaneous_fee->delete();
        return redirect('miscellaneous_fee');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */


 public function data()
    {

      $miscellaneous_fee_list = MiscellaneousFee::select(array('miscellaneous_fee.id','miscellaneous_fee.description','miscellaneous_fee.is_active'))
              ->orderBy('miscellaneous_fee.description', 'ASC');
    
      return Datatables::of($miscellaneous_fee_list)
          ->edit_column('is_active', '@if ($is_active=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
          ->add_column('actions', '<a href="{{{ URL::to(\'miscellaneous_fee/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                  <a href="{{{ URL::to(\'miscellaneous_fee/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                  <input type="hidden" name="row" value="{{$id}}" id="row">')
          ->remove_column('id')
          ->make();
    }


}
