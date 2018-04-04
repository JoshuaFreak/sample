<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Generic\GenRole;
use App\Models\Person;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\Auth\LoginRequest as LoginRequest;
use App\Http\Requests\Auth\RegisterRequest as RegisterRequest;
use Hash;

class UserController extends Controller {


    use AuthenticatesAndRegistersUsers;
    protected $gen_user;
    /**
     * Create a new authentication controller instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth            
     * @param \Illuminate\Contracts\Auth\Registrar $registrar            
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar, GenUser $gen_user)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;
        $this->gen_user = $gen_user;
        
        $this->middleware('guest', [
            'except' => 'getLogout'
        ]);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(LoginRequest $request)
	{
		
		$result = 0;
		if ($this->auth->attempt($request->only('username', 'password'))) {

			$result = [];

			$gen_user_role= GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                ->join('gen_user','gen_user_role.gen_user_id','=','gen_user.id')
                ->where('gen_user_role.gen_user_id',$this->auth->id())
                ->select(['gen_user_role.id','gen_role.name'])->get()->last();

            if($gen_user_role->name == 'Guardian'){

            	$result['result'] = 1;
            	$result['id'] = $this->auth->id();
            	$result['name'] = "Guardian";
            }
            elseif($gen_user_role->name == 'Student'){

            	$result['result'] = 1;
            	$result['id'] = $this->auth->id();
            	$result['name'] = "Student";

            }
            else{

            	$result = 0;
            }
			
        }

		return response()->json($result);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$gen_user_list = GenUser::where('gen_user.id','=',$id)->get()->last();

		return response()->json($gen_user_list);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
