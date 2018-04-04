<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class LearnMoreController extends BaseController {  

	public function index()
	{
       return view('learn_more.index');
	}
}   

    