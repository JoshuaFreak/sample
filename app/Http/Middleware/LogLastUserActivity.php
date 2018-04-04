<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\Generic\GenUserRole;
use Carbon\Carbon;
use Cache;


class LogLastUserActivity implements Middleware {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @param  ResponseFactory  $response
     * @return void
     */
    public function __construct(Guard $auth,
                                ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->response = $response;


    }
    /**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

// public function handle($request, Closure $next)
//     {
//         //var_dump($this->auth);

//         if(Auth::check()) {
// 		    $expiresAt = Carbon::now()->addMinutes(5);
// 		    Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
// 		}
//     }

}
