<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController; 
use App\Models\Generic\GenUserRole;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardHomepageController extends BaseController {

  	public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

    }

	public function index()
	{

        $project_list = Project::get();

           # code...

        $app_arr = array();
        $count=1;

        foreach ($project_list as $project) {

            $app_arr[$count]["AppName"] = $project ->name;
            $app_arr[$count]["AppDesc"] = $project ->description;
            $app_arr[$count]["img"] = $project ->img;
            $app_arr[$count]["imgHover"] = $project ->img_hover;
            $app_arr[$count]["AppURL"] = $project ->url;

            $count++;
        }


        // Show the page
        return view('module.index', compact('app_arr'));

       //return view('homepage.index');
	}

  //this function will accept an array of user_role and check if parameter $role_name is present in $user_role_arr - if present return true else return false
  private function userIsAllowed($user_role_arr , $role_name){
    foreach($user_role_arr as $user_role){
      if($user_role->name === $role_name){
        return true;
      }
    }
    return false;

  }
}   

    