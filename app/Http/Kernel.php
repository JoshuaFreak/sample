<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\VerifyCsrfToken',
		
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
        'admin' => 'App\Http\Middleware\Admin',	
        'homepage' => 'App\Http\Middleware\Homepage',
        'hrms' => 'App\Http\Middleware\Hrms',
        'registrar' => 'App\Http\Middleware\Registrar',
        'scheduler' => 'App\Http\Middleware\Scheduler',
        'enrollment' => 'App\Http\Middleware\Enrollment',
        // 'accounting' => 'App\Http\Middleware\Accounting',
        // 'deans_portal' => 'App\Http\Middleware\DeansPortal',
        'teachers_portal' => 'App\Http\Middleware\TeachersPortal',
        'students_portal' => 'App\Http\Middleware\StudentsPortal',
        // 'guardians_portal' => 'App\Http\Middleware\GuardiansPortal',
        // 'log_last_user_activity' => 'App\Http\Middleware\LogLastUserActivity',
	];
}
