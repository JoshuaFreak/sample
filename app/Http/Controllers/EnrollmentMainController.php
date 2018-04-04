<?php namespace App\Http\Controllers;

class EnrollmentMainController extends BaseController {

    /**
     * Initializer.
     *
     * @return \AdminController
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('enrollment');
    }

}