<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\HrmsMainController;
use App\Models\EmploymentStatus;
use App\Http\Requests\EmploymentStatusRequest;
use App\Http\Requests\EmploymentStatusEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class EmploymentStatusController extends HrmsMainController {
   
    
    /*
    return a list of country in json format based on a term
    **/
    public function search(){
      $term = Input::get('term');
      $result = EmploymentStatus::where('employment_status_name','LIKE','%$term%').orWhere('is_active','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function dataJson(){
      $result = EmploymentStatus::where('is_active',1)->get();
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
        return view('hrms/employment_status.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('hrms/employment_status.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(EmploymentStatusRequest $employment_status_request) {

        $employment_status = new EmploymentStatus();
        $employment_status -> employment_status_name = $employment_status_request->employment_status_name;
        $employment_status -> is_active = $employment_status_request->is_active;
        // $employment_status -> created_by_id = Auth::id();
        $employment_status -> save();

        $success = \Lang::get('employment_status.create_success').' : '.$employment_status->employment_status_name ; 
        return redirect('employment_status/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $employment_status = EmploymentStatus::find($id);
       //var_dump($its_customs_office);
        return view('employment_status/edit',compact('employment_status'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(EmploymentStatusEditRequest $employment_status_edit_request, $id) {
      
        $employment_status = EmploymentStatus::find($id);
        $employment_status -> employment_status_name = $employment_status_edit_request->employment_status_name;
        $employment_status -> is_active = $employment_status_edit_request->is_active;
        // $employment_status -> updated_by_id = Auth::id();
        $employment_status -> save();

        return redirect('employment_status');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $employment_status = EmploymentStatus::find($id);
        // Show the page
        return view('employment_status/delete', compact('employment_status'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $employment_status = EmploymentStatus::find($id);
        $employment_status->delete();
        return redirect('employment_status');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
       // $gen_language = GenLanguage::whereNull('gen_language.deleted_at')
          $employment_status_list = EmploymentStatus::select(array('employment_status.id','employment_status.employment_status_name','employment_status.is_active'))
              ->orderBy('employment_status.employment_status_name', 'ASC');
    
        return Datatables::of($employment_status_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'hrms/employment_status/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("its/form.edit") }}</a>
                    <a href="{{{ URL::to(\'hrms/employment_status/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("its/form.delete") }}</a>
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
                EmploymentStatus::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
