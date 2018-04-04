<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ActionTaken;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\GradingPeriod;
use App\Models\Schedule;
use App\Models\TEClass;
use App\Models\Term;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ResourceClassController extends TeachersPortalMainController {   
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
       
        return view('teachers_portal/resource_class.index');
    }
  
}
