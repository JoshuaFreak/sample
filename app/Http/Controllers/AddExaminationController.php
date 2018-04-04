<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\Campus;
use App\Models\Classification;
use App\Http\Requests\CampusRequest;
use App\Http\Requests\CampusEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class AddExaminationController extends SchedulerMainController {
   


    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
       
        return view('registrar.add_examination');
    }

}
