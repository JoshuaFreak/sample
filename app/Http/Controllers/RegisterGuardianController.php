<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use Illuminate\Support\Facades\Auth; 
use App\Models\StudentGuardian;
use App\Models\Generic\GenPerson;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Guardian;
use App\Models\Person; 
use App\Models\Student;
use App\Http\Requests\RegisterGuardianRequest;
use App\Http\Requests\RegisterGuardianEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;    
use Hash;

class RegisterGuardianController extends RegistrarMainController {

   /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // $student_list = Student::all();
        // Show the page
        return view('registrar/register_guardian.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        $action = 0;
        $student_list = Student::join('person', 'student.person_id', '=', 'person.id')
                    ->select('student.id', 'student.student_no','person.last_name', 'person.first_name', 'person.middle_name')->get();
        // Selected groups
        $student_guardian_list = array();
        return view('registrar/register_guardian.create', compact('student_list', 'student_guardian_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(RegisterGuardianRequest $register_guardian_request) {

        $person = new Person();    
        $person->first_name = $register_guardian_request->first_name;
        $person->last_name = $register_guardian_request->last_name;
        $person->save();

        $gen_user = new GenUser();
        $gen_user->person_id = $person->id; 
        $gen_user->username = $register_guardian_request->username;
        $gen_user->confirmation_code = md5(microtime().Config::get('app.key'));
        $gen_user->confirmed = $register_guardian_request->confirmed;
        $gen_user->password = Hash::make($register_guardian_request->username);
        $gen_user->save();
        
        $guardian = new Guardian();
        $guardian->person_id = $person->id;
        $guardian->username = $register_guardian_request->username;
        $guardian->gen_user_id = $gen_user->id;
        $guardian->save();

        $gen_user_role = new GenUserRole();
        $gen_user_role->gen_role_id = 10;
        $gen_user_role->gen_user_id = $gen_user->id;
        $gen_user_role->save();


        foreach($register_guardian_request->student as $item)
        {
            $student_guardian = new StudentGuardian();
            $student_guardian->student_id = $item;
            $student_guardian->guardian_id = $guardian->id;
            $student_guardian->save();
        }


        $success = \Lang::get('registrar.create_success_guardian').' : '.$gen_user->username ; 
        return redirect('registrar/register_guardian/create')->withSuccess( $success);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getEdit($id) {

        $action = 1;
        $guardian = Guardian::find($id);
        $person = Person::find($guardian->person_id);

        $student_list = Student::join('person', 'student.person_id', '=', 'person.id')
                    ->select('student.id', 'student.student_no','person.last_name', 'person.first_name', 'person.middle_name')->get();
        
        $student_guardian_list = StudentGuardian::join('student', 'student_guardian.student_id', '=', 'student.id')
                    ->join('guardian', 'student_guardian.guardian_id', '=' ,'guardian.id')
                    ->join('person', 'student.person_id', '=' ,'person.id')
                    ->where('student_guardian.guardian_id', '=', $id)
                    ->select('student_guardian.id', 'student.id as student_id', 'guardian.id as guardian_id', 'student.student_no' ,'person.last_name', 'person.first_name', 'person.middle_name')
                    ->get();

        return view('registrar/register_guardian.edit', compact('person', 'gen_user','guardian', 'student_list', 'student_guardian_list', 'action'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function postEdit(RegisterGuardianEditRequest $register_guardian_edit_request, $id) {

        $guardian = Guardian::find($id);
        $guardian ->username = $register_guardian_edit_request->username;

        $person = Person::find($guardian->person_id);
        $person ->last_name = $register_guardian_edit_request->last_name;
        $person ->first_name = $register_guardian_edit_request->first_name;

        $gen_user = GenUser::find($guardian->gen_user_id);
        $gen_user->confirmed = $register_guardian_edit_request->confirmed;

        //check if the user changes the existing email
        if($gen_user->username !== $register_guardian_edit_request->username){
            //check if the new email is already present in the database
            if(GenUser::where('username','=',$register_guardian_edit_request->username)->first() != null){
                //create error message here
            }
            else{
                $gen_user->username = $register_guardian_edit_request->username;
            }  
        }

        $password = $register_guardian_edit_request->username;
        $passwordConfirmation = $register_guardian_edit_request->username;

        if (!empty($password)) {
            if ($password === $passwordConfirmation) {
                $gen_user->password = Hash::make($password);
            }
        }
        //var_dump($register_guardian_edit_request);

        $gen_user -> save();
        $guardian -> save();
        $person -> save();


        // StudentGuardian::where('guardian_id', '=', $guardian->id)->delete();
        $value = $register_guardian_edit_request->student;
        if($value)
        {
            foreach($register_guardian_edit_request->student as $item)
            {
                $student_guardian = new StudentGuardian();
                $student_guardian->student_id = $item;
                $student_guardian->guardian_id = $guardian->id;
                $student_guardian->save();
            }
        }
       
        $success_edit = \Lang::get('registrar.create_success_edit_guardian').' : '.$gen_user->username ; 
        return redirect('registrar/register_guardian/'.$id.'/edit')->withSuccess( $success_edit);
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
        $guardian = Guardian::find($id);
        $person = Person::find($guardian->person_id);

        $student_list = Student::join('person', 'student.person_id', '=', 'person.id')
                    ->select('student.id', 'student.student_no','person.last_name', 'person.first_name', 'person.middle_name')->get();
        $student_guardian_list = StudentGuardian::lists('student_id');
        return view('registrar/register_guardian.delete', compact('person','guardian','student_list','student_guardian_list', 'action'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */
    public function postDelete($id)
    {
        $guardian= Guardian::find($id);
        $gen_user= GenUser::find($guardian->gen_user_id);
        $person= Person::find($guardian->person_id);

        $guardian->delete();
        $gen_user->delete();
        $person->delete();

        return redirect('registrar/register_guardian');
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDeleteGuardian(DeleteRequest $request)
    {
        $id = \Input::get('id');
        // $student = Student::find($id);
        $student_guardian = StudentGuardian::find($id);
        $student_guardian->delete();
        return redirect('registrar/register_guardian');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $guardian = Guardian::join('person', 'guardian.person_id', '=', 'person.id')
                    ->join('gen_user', 'guardian.gen_user_id', '=','gen_user.id')
                    ->join('gen_user_role', 'gen_user_role.gen_user_id', '=','gen_user.id')
                    ->join('gen_role', 'gen_user_role.gen_role_id', '=', 'gen_role.id')
                    ->select(array('guardian.id','person.last_name', 'person.first_name' , 'gen_user.username', 'gen_user.confirmed'))
                    ->where('gen_role.id', '=', 10)
                    ->orderBy('guardian.username', 'ASC');
        return Datatables::of( $guardian)
                    ->edit_column('confirmed', '@if ($confirmed=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
                    ->add_column('actions', '<a href="{{{ URL::to(\'registrar/register_guardian/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("modal.edit") }}</a>
                    <a href="{{{ URL::to(\'registrar/register_guardian/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("modal.delete") }}</a>
                ')
                    ->remove_column('id')
                    ->make();
    }


}
