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

class UserProfileController extends Controller {


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
		$gen_user = GenUser::join('person','gen_user.person_id','=','person.id')
				->where('gen_user.id','=',$id)->select(['gen_user.username','person.first_name','person.middle_name','person.last_name'])->get()->last();

		return response()->json($gen_user);
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
