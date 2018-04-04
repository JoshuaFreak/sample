<?php namespace App\Http\Controllers;

use App\Http\Controllers\AdminMainController;
use App\Models\Generic\GenRole;
use App\Models\Generic\GenPermission;
use App\Models\Generic\GenPermissionRole;
use App\Http\Requests\GenRoleRequest;
use App\Http\Requests\GenRoleEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;

class GenRoleController extends AdminMainController {
   
    
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // Show the page
        return view('gen_role.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
        $permissions_admin = GenPermission::where('is_admin','=',1)->get();
        $permissions_user = GenPermission::where('is_admin','=',0)->get();
        // Selected permissions
        $permisions_add =array();

        // Show the page
        return view('gen_role.create', compact('permissions_admin', 'permissions_user','permisions_add'));
    }
  //   /**
  //    * Store a newly created resource in storage.
  //    *
  //    * @return Response
  //    */
    public function postCreate(GenRoleRequest $gen_role_request) {

  //       $is_admin = 0;
  //       // Check if role is for admin user
  //       if(!empty($gen_role_request->permission)){
	 //    	$permissions_admin = GenPermission::where('is_admin','=',1)->get();
		//     foreach ($permissions_admin as $perm){
	 //            foreach($gen_role_request->permission as $item){
	 //                if($item==$perm['id'] && $perm['is_admin']=='1')
	 //                {
	 //                    $is_admin = 1;
	 //                }
	 //            }
	 //        }
		// }
      
		// if(is_array($gen_role_request->permission)){
	 //        foreach ($gen_role_request->permission as $item) {
	 //            $gen_permission = new GenPermissionRole();
	 //            $gen_permission->gen_permission_id = $item;
	 //            $gen_permission->gen_role_id = $gen_role->id;
	 //            $gen_permission -> save();
	 //        }
		// }
      $gen_role = new GenRole();
        $gen_role ->is_admin = $gen_role_request->is_admin;
        $gen_role ->name = $gen_role_request->name;
        $gen_role -> save();

        $success = \Lang::get('gen_role.create_success').' : '.$gen_role->name ; 
        return redirect('gen_role/create')->withSuccess( $success);
    }

  //   /**
  //    * Show the form for editing the specified resource.
  //    *
  //    * @param $role
  //    * @return Response
  //    */
    public function getEdit($id) {
        $gen_role = GenRole::find($id);
        //var_dump($gen_role);
        $permissions_admin = GenPermission::where('is_admin',1)->get();
        $permissions_user = GenPermission::where('is_admin',0)->get();
        $permisions_add = GenPermissionRole::where('gen_role_id','=',$id)->select('gen_permission_id')->get();

       //  Show the page
        return view('gen_role.edit', compact('gen_role', 'permissions_admin', 'permissions_user','permisions_add'));
        
    }

  //   /**
  //    * Update the specified resource in storage.
  //    *
  //    * @param $role
  //    * @return Response
  //    */
    public function postEdit(GenRoleEditRequest $gen_role_request, $id) {
      //   $is_admin = 0;

      //   //var_dump($gen_role_request);
        
    		// if(!empty($gen_role_request->permission)){
    	 //        $permissions_admin = GenPermission::where('is_admin','=',1)->get();
    	 //        foreach ($permissions_admin as $perm){
    	 //            foreach($gen_role_request->permission as $item){
    	 //                if($item==$perm['id'] && $perm['is_admin']=='1')
    	 //                {
    	 //                    $is_admin = 1;
    	 //                }
    	 //            }
    	 //        }
    		// }
            $gen_role = GenRole::find($id);
            $gen_role ->is_admin = $gen_role_request->is_admin;
            $gen_role ->name = $gen_role_request->name;
            $gen_role -> save();

      //       GenPermissionRole::where('gen_role_id','=',$id) -> delete();
    		
    		// if(is_array($gen_role_request->permission)){
    	 //        foreach ($gen_role_request->permission as $item) {	        	
    	 //            $gen_permission = new GenPermissionRole();
    	 //            $gen_permission->gen_permission_id = $item;
    	 //            $gen_permission->gen_role_id = $gen_role->id;
    	 //            $gen_permission -> save();
    	 //        }
    		//}

        return redirect('gen_role');
    }

  //   /**
  //    * Remove the specified resource from storage.
  //    *
  //    * @param $role
  //    * @return Response
  //    */

    public function getDelete($id)
    {
        $gen_role = GenRole::find($id); 
        $permissions_admin = GenPermission::where('is_admin',1)->get();
        $permissions_user = GenPermission::where('is_admin',0)->get();
        $permisions_add = GenPermissionRole::where('gen_role_id','=',$id)->select('gen_permission_id')->get();

       //  Show the page
        return view('gen_role.delete', compact('gen_role', 'permissions_admin', 'permissions_user','permisions_add'));
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $post
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $gen_role = GenRole::find($id);
        $gen_role->delete();
        return redirect('gen_role');
    }
  //   /**
  //    * Show a list of all the languages posts formatted for Datatables.
  //    *
  //    * @return Datatables JSON
  //    */
    public function data()
    {
      $id = Auth::user()->id;
        $gen_role_list = GenRole::select(array('gen_role.id','gen_role.name','gen_role.created_at'));
                        
        //var_dump($gen_role_list);
    $role_arr = array('Master Administrator');
      if($this->hasRole($role_arr) == true )
      {
          return Datatables::of($gen_role_list)
              ->add_column('actions', '<a href="{{{ URL::to(\'gen_role/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                      <a href="{{{ URL::to(\'gen_role/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                  ')
              ->remove_column('id')
              ->make();
        }
      else
        {
           return Datatables::of($gen_role_list)
              ->add_column('', '')
              ->remove_column('id')
              ->make();
        }
    }

}
