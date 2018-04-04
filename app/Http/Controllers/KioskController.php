<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth; 
use App\Models\Student;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;
use Hash;

class KioskController extends BaseController {
   

    public function index()
    {
        return view('kiosk/index');
    }

}
