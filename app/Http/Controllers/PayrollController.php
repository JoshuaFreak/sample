<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PayrollController extends BaseController {

  	public function __construct()
    {
        parent::__construct();
        // $this->middleware('auth');

    }

	public function index()
	{
        return view('payroll.index');
	}
}   

    