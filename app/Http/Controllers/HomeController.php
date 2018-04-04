<?php namespace App\Http\Controllers;

use App\Models\Generic\GenUser;

use Illuminate\Support\Facades\DB;

class HomeController extends BaseController {



    /**
     * GenUser \Model
     * @var GenUser
     */
    protected $gen_user;

    /**
     * Inject the models.
     * @param \Post $post
     * @param \User $user
     */
    public function __construct(GenUser $gen_user)
    {
        parent::__construct();
        $this->gen_user = $gen_user;

       
    }

    public function index()
	{
        return view('site.home.index');
        // return view('register.index');
	}


}
