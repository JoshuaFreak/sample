<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Generic\GenUserRole as GenUserRole;
use View;

class BaseController extends Controller {

    /**
     * Initializer.
     *
     * @access   public
     * @return \BaseController
     */
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
		
		$gen_user_id = Auth::id();
	

		//$gen_user_id = Auth::gen_user_id();
		//var_dump($gen_user_id);

		//$email = Auth::user()->email;
		//var_dump($email);
		
		//$gen_user = Auth::user()->email;
       // var_dump($gen_user);

		if($gen_user_id>0){
			$result = GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
											->where('gen_user_role.gen_user_id',$gen_user_id)
											->select('name')
											->get();
			//var_dump($result);
			foreach ($result as $row)
			{
				$role_name = str_replace(" ", "_", 	strtolower($row->name));
				// print $role_name."<br/>";
				View::share($role_name,$role_name);
			}
			// $count = GenUserRole::join('gen_permission_role','gen_user_role.gen_role_id','=','gen_permission_role.gen_role_id')
			// 								->join('gen_permission','gen_permission.gen_permission_id','=','gen_permission_role.gen_permission_id')
			// 								->where('gen_user_role.gen_user_id',$gen_user_id)
			// 								->where('gen_permission.is_admin','1')
			// 								->count();
			// if($count>0)
			// {
			// 	View::share('admin',  'admin');
			// 	//View::share('its',  'its');
				
			// }
		}
		
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}



	protected function hasRole($role_arr)
	{

			$gen_user_id = Auth::user()->id;

			$user_role_arr = GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
											->where('gen_user_role.gen_user_id',$gen_user_id)
											->select('name')
											->get();

			 $user_role_counter = 0;

			foreach ($role_arr as $role) {

				foreach ($user_role_arr as $user_role)
				{
					if($role == $user_role->name)
					{
						$user_role_counter++;
					}
					
				}

			}

			if( $user_role_counter > 0){
				return $user_role_counter;
			}
			else
			{
				return 0;
			}


			//return $user_role;
			//var_dump($result);
			/*foreach ($role_arr as $row)
			{
				
			}
			$count = GenUserRole::join('gen_permission_role','gen_user_role.gen_role_id','=','gen_permission_role.gen_role_id')
											->join('gen_permission','gen_permission.gen_permission_id','=','gen_permission_role.gen_permission_id')
											->where('gen_user_role.gen_user_id',$gen_user_id)
											->where('gen_permission.is_admin','1')
											->count();
			if($count>0)
			{
				View::share('admin',  'admin');
				//View::share('its',  'its');
				
			}	*/
	}

}