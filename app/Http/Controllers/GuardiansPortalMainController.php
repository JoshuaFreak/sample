<?php namespace App\Http\Controllers;

class GuardiansPortalMainController extends BaseController {

    /**
     * Initializer.
     *
     * @return \AdminController
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('guardians_portal');
    }

}