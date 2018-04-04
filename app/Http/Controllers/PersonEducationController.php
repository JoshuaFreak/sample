<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\HrmsMainController;
use App\Models\PersonEducation;
use App\Http\Requests\PersonEducationRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class PersonEducationController extends HrmsMainController {
   

    public function postSavePersonEducationJson(PersonEducationRequest $person_education_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($person_education_request->id == 0 ||  $person_education_request->id =="")
      {
        $person_education = new PersonEducation();
        $person_education -> person_id = $person_education_request->person_id;
        $person_education -> school_name = $person_education_request->school_name;
        $person_education -> employee_classification_id = $person_education_request->employee_classification_id;
        $person_education -> years_attended = $person_education_request->years_attended;
        $person_education -> honors_received = $person_education_request->honors_received;
        $person_education -> course_graduated = $person_education_request->course_graduated;
        $person_education -> save();
      }
      else
      {
        $person_education = PersonEducation::find($person_education_request->id);
        $person_education -> school_name = $person_education_request->school_name;
        $person_education -> employee_classification_id = $person_education_request->employee_classification_id;
        $person_education -> years_attended = $person_education_request->years_attended;
        $person_education -> honors_received = $person_education_request->honors_received;
        $person_education -> course_graduated = $person_education_request->course_graduated;
        $person_education -> save();

      }
     

      return $person_education_request;
      
    }


}
