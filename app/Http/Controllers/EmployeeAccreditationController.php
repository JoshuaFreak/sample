<?php namespace App\Http\Controllers\Hrms;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\Hrms\EmployeeAccreditation;
use App\Http\Requests\Hrms\EmployeeAccreditationRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class EmployeeAccreditationController extends BaseController {
  
    public function postSaveEmployeeAccreditationJson(EmployeeAccreditationRequest $employee_accreditation_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($employee_accreditation_request->id == 0 ||  $employee_accreditation_request->id =="")
      {
        $employee_accreditation = new EmployeeAccreditation();
        $employee_accreditation -> employee_id = $employee_accreditation_request->employee_id;
      }
      else
      {
        $employee_accreditation = EmployeeAccreditation::find($employee_accreditation_request->id);        

      }

        $employee_accreditation -> course_accreditation_id = $employee_accreditation_request->course_accreditation_id;
        $employee_accreditation -> instructor_exp_date = date('Y-m-d', strtotime($employee_accreditation_request->instructor_exp_date));
        $employee_accreditation -> supervisor_exp_date = date('Y-m-d', strtotime($employee_accreditation_request->supervisor_exp_date));
        $employee_accreditation -> assessor_exp_date = date('Y-m-d', strtotime($employee_accreditation_request->assessor_exp_date));
        $employee_accreditation -> remark = $employee_accreditation_request->remark;
        $employee_accreditation -> save();

        return $employee_accreditation;
    }



}
