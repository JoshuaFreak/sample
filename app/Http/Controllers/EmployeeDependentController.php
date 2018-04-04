<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\EmployeeCertificate;
use App\Models\EmployeeDependent;
use App\Models\EmployeeContact;
use App\Models\EmployeeContributionNumber;
use App\Models\EmployeeWorkingExperience;
use App\Models\Employee;
use App\Http\Requests\EmployeeDependentRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class EmployeeDependentController extends BaseController {
   
    
    public function postSaveEmployeeDependentJson(EmployeeDependentRequest $employee_dependent_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($employee_dependent_request->id == 0 ||  $employee_dependent_request->id =="")
      {
        $employee_dependent = new EmployeeDependent();
        $employee_dependent -> employee_id = $employee_dependent_request->employee_id;
        $employee_dependent -> dependent_name = $employee_dependent_request->dependent_name;
        $employee_dependent -> dependent_birthdate = date('Y-m-d', strtotime($employee_dependent_request->dependent_birthdate));
        $employee_dependent -> dependent_relationship_id = $employee_dependent_request->dependent_relationship_id;
        $employee_dependent -> save();
      }
      else
      {
        $employee_dependent = EmployeeDependent::find($employee_dependent_request->id);
        $employee_dependent -> dependent_name = $employee_dependent_request->dependent_name;
        $employee_dependent -> dependent_birthdate = date('Y-m-d', strtotime($employee_dependent_request->dependent_birthdate));
        $employee_dependent -> dependent_relationship_id = $employee_dependent_request->dependent_relationship_id;
        $employee_dependent -> save();
    }


        return $employee_dependent_request;

  }

  public function postSaveEmployeeContactJson(EmployeeDependentRequest $employee_dependent_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($employee_dependent_request->id == 0 ||  $employee_dependent_request->id =="")
      {
        $employee_contact = new EmployeeContact();
        $employee_contact -> employee_id = $employee_dependent_request->employee_id;
        $employee_contact -> contact_name = $employee_dependent_request->contact_name;
        $employee_contact -> contact_no = $employee_dependent_request->contact_no;
        $employee_contact -> dependent_relationship_id = $employee_dependent_request->dependent_relationship_id;
        $employee_contact -> save();
      }
      else
      {
        $employee_contact = EmployeeContact::find($employee_dependent_request->id);
        $employee_contact -> contact_name = $employee_dependent_request->contact_name;
        $employee_contact -> contact_no = $employee_dependent_request->contact_no;
        $employee_contact -> dependent_relationship_id = $employee_dependent_request->dependent_relationship_id;
        $employee_contact -> save();
    }


        return $employee_dependent_request;

  }

  public function postSaveGovernmentContributionJson(EmployeeDependentRequest $employee_dependent_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($employee_dependent_request->id == 0 ||  $employee_dependent_request->id =="")
      {
        $employee_contact = new EmployeeContributionNumber();
        $employee_contact -> employee_id = $employee_dependent_request->employee_id;
        $employee_contact -> government_department = $employee_dependent_request->employee_government_department;
        $employee_contact ->  employee_contribution_number = $employee_dependent_request-> employee_government_contribution_no;
        $employee_contact -> save();
      }
      else
      {
        $employee_contact = EmployeeContributionNumber::find($employee_dependent_request->id);
        $employee_contact -> government_department = $employee_dependent_request->employee_government_department;
        $employee_contact ->  employee_contribution_number = $employee_dependent_request-> employee_government_contribution_no;
        $employee_contact -> save();
      }


        return $employee_dependent_request;

  }
  public function postSaveEmployeeExperienceJson(EmployeeDependentRequest $employee_dependent_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($employee_dependent_request->id == 0 ||  $employee_dependent_request->id =="")
      {
        $employee_working_experience = new EmployeeWorkingExperience();
        $employee_working_experience -> employee_id = $employee_dependent_request->employee_id;
        $employee_working_experience -> company_name = $employee_dependent_request->company_name;
        $employee_working_experience -> position = $employee_dependent_request->experience_position;
        $employee_working_experience -> date_employed_from = date('Y-m-d', strtotime($employee_dependent_request->experience_date_from));
        $employee_working_experience -> date_employed_to = date('Y-m-d', strtotime($employee_dependent_request->experience_date_to));
        $employee_working_experience -> rate = $employee_dependent_request->experience_rate;
        $employee_working_experience -> save();
      }
      else
      {
        $employee_working_experience = EmployeeWorkingExperience::find($employee_dependent_request->id);
        $employee_working_experience -> company_name = $employee_dependent_request->company_name;
        $employee_working_experience -> position = $employee_dependent_request->experience_position;
        $employee_working_experience -> date_employed_from = date('Y-m-d', strtotime($employee_dependent_request->experience_date_from));
        $employee_working_experience -> date_employed_to = date('Y-m-d', strtotime($employee_dependent_request->experience_date_to));
        $employee_working_experience -> rate = $employee_dependent_request->experience_rate;
        $employee_working_experience -> save();
      }


        return $employee_dependent_request;

  }

  public function postSaveEmployeeCertificateJson(EmployeeDependentRequest $employee_dependent_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($employee_dependent_request->id == 0 ||  $employee_dependent_request->id =="")
      {
        $employee_working_experience = new EmployeeCertificate();
        $employee_working_experience -> employee_id = $employee_dependent_request->employee_id;
        $employee_working_experience -> description = $employee_dependent_request->description;
        $employee_working_experience -> is_cia = $employee_dependent_request->is_cia;
        $employee_working_experience -> date = date('Y-m-d', strtotime($employee_dependent_request->date));
        $employee_working_experience -> award_by = $employee_dependent_request->award_by;
        $employee_working_experience -> save();
      }
      else
      {
        $employee_working_experience = EmployeeCertificate::find($employee_dependent_request->id);
        $employee_working_experience -> description = $employee_dependent_request->description;
        $employee_working_experience -> date = date('Y-m-d', strtotime($employee_dependent_request->date));
        $employee_working_experience -> award_by = $employee_dependent_request->award_by;
        $employee_working_experience -> save();
      }


        return $employee_dependent_request;

  }
}