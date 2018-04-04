<?php namespace App\Http\Controllers;

class RegistrarMainController extends BaseController {

    /**
     * Initializer.
     *
     * @return \AdminController
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('registrar');
    }

}