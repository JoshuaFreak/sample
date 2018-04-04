<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\Auth\LoginRequest as LoginRequest;
use App\Http\Requests\Auth\RegisterRequest as RegisterRequest;
use App\Models\Generic\GenUser as GenUser;
use App\Models\Generic\GenUserRole as GenUserRole;

class AuthController extends Controller
{
    
    /*
     * |--------------------------------------------------------------------------
     * | Registration & Login Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller handles the registration of new users, as well as the
     * | authentication of existing users. By default, this controller uses
     * | a simple trait to add these behaviors. Why don't you explore it?
     * |
     */
    
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
     * Show the application registration form.
     *
     * @Get("auth/register")
     *
     * @return Response
     */
    public function showRegistrationForm()
    {
        return view('site.auth.register');
    }

    // public function showRegistrationForm()
    // {
    //     return view('site.auth.register');
    // }
    /**
     * Handle a registration request for the application.
     *
     * @Post("auth/register")
     *
     * @param RegisterRequest $request            
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        $this->gen_user->email = $request->email;
       // $this->user->name = $request->name;
        $this->gen_user->password = \Hash::make($request->password);
        $this->gen_user->save();
        
        $this->auth->login($this->gen_user);
        
        return redirect('/');
    }
    /**
     * Show the application login form.
     *
     * @Get("auth/login")
     *
     * @return Response
     */
    public function showLoginForm()
    {
        return view('site.auth.login');
    }
    /**
     * Handle a login request to the application.
     *
     * @Post("auth/login")
     *
     * @param LoginRequest $request            
     * @return Response
     */
    public function login(LoginRequest $request)
    {

        if ($this->auth->attempt($request->only('username', 'password'))) {
            

            $count = GenUserRole::where('gen_user_role.gen_user_id',$this->auth->id())
                ->count();

            if($count>0){
            $gen_user_role= GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                    ->join('gen_user','gen_user_role.gen_user_id','=','gen_user.id')
                    ->where('gen_user_role.gen_user_id',$this->auth->id())
                    ->select('gen_user_role.id','gen_role.name')->get()->last();


                if($gen_user_role->name == 'Admin' ){
                    return redirect('module');
                }
                elseif($gen_user_role->name == 'Hrms'){
                    return redirect('employee');
                }
                elseif($gen_user_role->name == 'Registrar'){
                    return redirect('course');
                }
                elseif($gen_user_role->name == 'Scheduler' || $gen_user_role->name == 'Scheduler Info'){
                    return redirect('scheduler');
                }
                elseif($gen_user_role->name == 'Teacher'){
                    return redirect('teacher_portal');
                }
                else
                {
                }
                // elseif($gen_user_role->name == 'Student'){
                //     return redirect('students_portal');
                // }
                // elseif($gen_user_role->name == 'Guardian'){
                //     return redirect('guardian_portal');
                // }
            }
        }
        
        return redirect('/auth/login')->withErrors([
            'username' => 'These credentials do not match our records.',
            'password' => 'These credentials do not match our records.'
        ]);
                // return redirect('module');
            // }
            // return redirect('/');
            
        
    }
    /**
     * Log the user out of the application.
     *
     * @Get("auth/logout")
     *
     * @return Response
     */
    public function logout()
    {

        $this->auth->logout();
        
        return redirect('/');
    }
}
    