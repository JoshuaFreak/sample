<?php namespace App\Http\Controllers;

use App\Http\Controllers\AdminMainController;
use App\Models\EmployeeType;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Generic\GenRole;
use App\Models\Person;
use App\Http\Requests\GenUserRequest;
use App\Http\Requests\GenUserEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class GenUserController extends AdminMainController {
   

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
         $gen_role_list = GenRole::all();
         $employee_type_list = EmployeeType::all();
        // Show the page
        return view('gen_user.index', compact('gen_role_list','employee_type_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        $gen_role_list = GenRole::where('gen_role.is_active', 1)->get();
        // Selected groups
        $gen_user_role_list = array();
        return view('gen_user.create', compact('gen_role_list', 'gen_user_role_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(GenUserRequest $gen_user_request) {
        

        // foreach($gen_user_request->roles as $item)
        // {
        //     print $item."<br>";
        // }

        $person = new Person();    
        $person->first_name = $gen_user_request->first_name;
        $person->last_name = $gen_user_request->last_name;
        $person->save();
        

        $gen_user = new GenUser();
        $gen_user->person_id = $person->id; 
        $gen_user->username = $gen_user_request->username;
        $gen_user->confirmation_code = md5(microtime().Config::get('app.key'));
        $gen_user->confirmed = $gen_user_request->confirmed;
        $gen_user->password = Hash::make($gen_user_request->password);
        $gen_user->save();
        
        foreach($gen_user_request->roles as $item)
        {
            $gen_user_role = new GenUserRole();


            $gen_user_role->gen_role_id = $item;
            
            $gen_user_role->gen_user_id = $gen_user->id;
            
            $gen_user_role->save();
        }

        $create_success = true;
       // $gen_role_list = GenRole::all();
       // $gen_user_role_list = array();


        $success = \Lang::get('gen_user.create_success').' : '.$gen_user->username ; 
        return redirect('gen_user/create')->withSuccess( $success);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getEdit($id) {
        
        $action = 1;
        $gen_user = GenUser::find($id);
        $person = Person::find($gen_user->person_id);
        $gen_role_list = GenRole::all();
        $employee_type_list = EmployeeType::all();

        // $gen_user_role_list = GenUserRole::where('gen_user_id','=',$gen_user->id)->lists('gen_role_id');
        
        $gen_user_role_list = GenUserRole::join('gen_role', 'gen_user_role.gen_role_id', '=', 'gen_role.id')
                    ->join('gen_user', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->join('person', 'gen_user.person_id', '=', 'person.id')
                    ->where('gen_user_role.gen_user_id', '=', $id)
                    ->select('gen_user_role.id', 'gen_role.id as gen_role_id','gen_user.id as gen_user_id', 'gen_role.name', 'person.last_name', 'person.first_name', 'person.middle_name')
                    ->get();

        //var_dump($gen_user_role_list);
        return view('gen_user.edit', compact('gen_user', 'gen_role_list', 'gen_user_role_list', 'action', 'person','employee_type_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function postEdit(GenUserEditRequest $gen_user_edit_request, $id) {
        $gen_user = GenUser::find($id);
        $gen_user->confirmed = $gen_user_edit_request->confirmed;

        //check if the user changes the existing email
        if($gen_user->username !== $gen_user_edit_request->username){
            //check if the new email is already present in the database
            if(GenUser::where('username','=',$gen_user_edit_request->username)->first() != null){
                //create error message here
            }
            else{
                $gen_user->username = $gen_user_edit_request->username;
            }  
        }

        $password = $gen_user_edit_request->password;

        if (!empty($password)) {
                $gen_user->password = Hash::make($password);
                $gen_user->secret = $password;        }
        //var_dump($gen_user_edit_request);
        $gen_user -> save();
        
        // GenUserRole::where('gen_user_id','=',$gen_user->id)->delete();
        // foreach($gen_user_edit_request->roles as $item)
        // {
        //     $gen_user_role = new GenUserRole;
        //     $gen_user_role->gen_role_id = $item;
        //     $gen_user_role->gen_user_id = $gen_user->id;
        //     $gen_user_role-> save();
        // }

        // return redirect('gen_user');



        $value = $gen_user_edit_request->roles;
        if($value)
        {
            foreach($gen_user_edit_request->roles as $item)
            {
                $gen_user_role = new GenUserRole();
                $gen_user_role->gen_role_id = $item;
                $gen_user_role->gen_user_id = $gen_user->id;
                $gen_user_role->save();
            }
        }

        $success_edit = \Lang::get('gen_user.edit_success').' : '.$gen_user->username ; 
        return redirect('gen_user/'.$id.'/edit')->withSuccess( $success_edit);
       
        // $success_edit = \Lang::get('registrar.create_success_edit_guardian').' : '.$gen_user->username ; 
        // return redirect('registrar/register_guardian/'.$id.'/edit')->withSuccess( $success_edit);
        // return redirect('registrar/register_guardian/'.$id.'/edit');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */

    public function getDelete($id)
    {
        $action = 1;
        $gen_user = GenUser::find($id);
        $person = Person::find($gen_user->person_id);
        $gen_role_list = GenRole::all();

        // $gen_user_role_list = GenUserRole::where('gen_user_id','=',$gen_user->id)->lists('gen_role_id');
        
        $gen_user_role_list = GenUserRole::join('gen_role', 'gen_user_role.gen_role_id', '=', 'gen_role.id')
                    ->join('gen_user', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->join('person', 'gen_user.person_id', '=', 'person.id')
                    ->where('gen_user_role.gen_user_id', '=', $id)
                    ->select('gen_user_role.id', 'gen_role.id as gen_role_id','gen_user.id as gen_user_id', 'gen_role.name', 'person.last_name', 'person.first_name', 'person.middle_name')
                    ->get();
        
        return view('gen_user.delete', compact('gen_user','gen_role_list','gen_user_role_list', 'action', 'person'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $gen_user= GenUser::find($id);
        $gen_user->delete();
        return redirect('gen_user');
    }

         /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDeleteRole(DeleteRequest $request)
    {
        $id = \Input::get('id');
        // $student = Student::find($id);
        $gen_user_role = GenUserRole::find($id);
        $gen_user_role->delete();
        return redirect('gen_user');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
            $name = \Input::get('name');
            $gen_user_role_list = GenUserRole::join('gen_user', 'gen_user_role.gen_user_id', '=','gen_user.id')
                        ->join('gen_role', 'gen_user_role.gen_role_id', '=', 'gen_role.id')
                        ->join('person', 'gen_user.person_id', '=', 'person.id')
                        ->select(array('gen_user.id','person.last_name', 'person.first_name' , 'gen_user.username','gen_user.secret', 'gen_user.confirmed'))
                        ->where('gen_role.name', '=', $name)
                        ->orderBy('gen_user.created_at', 'DESC');
            return Datatables::of( $gen_user_role_list)
                -> edit_column('confirmed', '@if ($confirmed=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
                ->add_column('actions', '<a href="{{{ URL::to(\'gen_user/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("gen_person.edit") }}</a>
                    ')
                //->add_column('actions', '<a href="{{{ URL::to(\'gen_user/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("modal.edit") }}</a>
                //         <a href="{{{ URL::to(\'gen_user/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("modal.delete") }}</a>
                //     ')
                ->remove_column('id')

                ->make();
    }

}
