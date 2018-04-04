<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\Role;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class RoleController extends BaseController {
   

    /*
    return a list of country in json format based on a term
    **/
    public function search(){
      $term = Input::get('term');
      $result = Role::where('role_name','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function dataJson(){
      $result = Role::where('is_active',1)->get();
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
        return view('role.index');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
  
        // Show the page
        return view('role.create');
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(RoleRequest $role_request) {

        $role = new Role();
        $role -> role_name = $role_request->role_name;
        $role -> is_default = $role_request->is_default;
        $role -> is_active = $role_request->is_active;
        // $role -> created_by_id = Auth::id();
        $role -> save();

        $success = \Lang::get('role.create_success').' : '.$role->role_name ; 
        return redirect('role/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $role = Role::find($id);
       //var_dump($its_customs_office);
        return view('role/edit',compact('role'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(RoleEditRequest $role_edit_request, $id) {
      
        $role = Role::find($id);
        $role -> role_name = $role_edit_request->role_name;
        $role -> is_default = $role_edit_request->is_default;
        $role -> is_active = $role_edit_request->is_active;
        // $role -> updated_by_id = Auth::id();
        $role -> save();

        return redirect('role');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $role = Role::find($id);
        // Show the page
        return view('role/delete', compact('role'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect('role');
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
       // $gen_language = GenLanguage::whereNull('gen_language.deleted_at')
          $role_list = Role::select(array('role.id','role.role_name','role.is_default','role.is_active'))
              ->orderBy('role.role_name', 'ASC');
           // ->select(array('gen_language.name', 'gen_language.lang_code',
            //'_gen_language.icon as icon'));
       // $gen_language_list = GenLanguage::all();  //successfully  returned data from db
        //var_dump($gen_language_list);  //i am doing debugging procedure. to check if there is available dataa retreive from db
    
        return Datatables::of($role_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'role/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("its/form.edit") }}</a>
                    <a href="{{{ URL::to(\'role/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("its/form.delete") }}</a>
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
                Role::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
