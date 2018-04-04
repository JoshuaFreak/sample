<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\HrmsMainController;
use App\Models\PersonSeminar;
use App\Http\Requests\PersonSeminarRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class PersonSeminarController extends HrmsMainController {
   
   

    public function postSavePersonSeminarJson(PersonSeminarRequest $person_seminar_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($person_seminar_request->id == 0 ||  $person_seminar_request->id =="")
      {
        $person_seminar = new PersonSeminar();        
        $person_seminar -> is_cia = $person_seminar_request->is_cia;
        $person_seminar -> person_id = $person_seminar_request->person_id;
      }
      else
      {
        $person_seminar = PersonSeminar::find($person_seminar_request->id);
      

      }
        
        $person_seminar -> seminar_date = date('Y-m-d', strtotime($person_seminar_request->seminar_date));
        $person_seminar -> seminar_name = $person_seminar_request->seminar_name;
        $person_seminar -> seminar_venue = $person_seminar_request->seminar_venue;
        $person_seminar -> remark = $person_seminar_request->remark;
        $person_seminar -> save();

     // return $person_seminar_request;

        return $person_seminar;
    }



}
