<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\FeeType;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MiscellaneousFeeRequest;
use App\Http\Requests\MiscellaneousFeeEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;    
use Hash;

class FeeTypeController extends AccountingMainController {
  

   public function dataJson(){
          $condition = \Input::all();
          $query = FeeType::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','fee_type_name as text'])->get();
         $fee_type = $query->select([ 'id as value','fee_type_name as text'])->get();

          return response()->json($fee_type);
    }


    public function dataJsonTypeahead(){
        $condition = \Input::all();
        $query = FeeType::select('fee_type.id', 'fee_type.fee_type_name');
      
        foreach($condition as $column => $value)
        { 
            if($column == 'query')
            {
              $query->where('fee_type_name', 'LIKE', "%$value%");
            }
            else
            {
              $query->where($column, '=', $value);
            }   
        }

        $fee_type = $query->get();

        return response()->json($fee_type);
    }

    public function index()
    {
        // Show the page
        return view('fee_type.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('fee_type.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(MiscellaneousFeeRequest $fee_type_request) {

        $fee_type = new FeeType();
        $fee_type -> fee_type_name = $fee_type_request->fee_type_name;
        $fee_type -> is_active = $fee_type_request->is_active;
        // $fee_type -> created_by_id = Auth::id();
        $fee_type -> save();

        $success = \Lang::get('fee_type.create_success').' : '.$fee_type->fee_type_name ; 
        return redirect('fee_type/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
      
        $fee_type = FeeType::find($id);
        return view('fee_type/edit',compact('fee_type'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(MiscellaneousFeeEditRequest $fee_type_edit_request, $id) {
      
        $fee_type = FeeType::find($id);
        $fee_type -> fee_type_name = $fee_type_edit_request->fee_type_name;
        $fee_type -> is_active = $fee_type_edit_request->is_active;
        // $fee_type -> updated_by_id = Auth::id();
        $fee_type -> save();

        return redirect('fee_type');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $fee_type = FeeType::find($id);
        // Show the page
        return view('fee_type/delete', compact('fee_type'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $fee_type = FeeType::find($id);
        $fee_type->delete();
        return redirect('fee_type');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */


 public function data()
    {

      $fee_type_list = FeeType::where('fee_type.id', '!=', 0)
              ->select(array('fee_type.id','fee_type.fee_type_name','fee_type.is_active'))
              ->orderBy('fee_type.fee_type_name', 'ASC');
    
      return Datatables::of($fee_type_list)
          ->edit_column('is_active', '@if ($is_active=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
          ->add_column('actions', '<a href="{{{ URL::to(\'fee_type/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                  <a href="{{{ URL::to(\'fee_type/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                  <input type="hidden" name="row" value="{{$id}}" id="row">')
          ->remove_column('id')
          ->make();
    }


}
