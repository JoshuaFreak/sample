<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\HrmsMainController;
use App\Models\EmployeeEmploymentDetail;
use App\Http\Requests\EmployeeEmploymentDetailRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class EmployeeEmploymentDetailController extends HrmsMainController {
   
    
    public function postSaveEmployeeEmploymentDetailJson(EmployeeEmploymentDetailRequest $employee_employment_detail_request) {
      

      //check if the employee cop nac is already created if yes - perform update else perform insert/create
      if($employee_employment_detail_request->id == 0 ||  $employee_employment_detail_request->id =="")
      {
        $employee_employment_detail = new EmployeeEmploymentDetail();
        $employee_employment_detail -> employee_id = $employee_employment_detail_request->employee_id;
      }
      else
      {
        $employee_employment_detail = EmployeeEmploymentDetail::find($employee_employment_detail_request->id);
      }

        
        $employee_employment_detail -> rank_id = $employee_employment_detail_request->rank_id;
        $employee_employment_detail -> license_no = $employee_employment_detail_request->license_no;
        $employee_employment_detail -> prc_identification = $employee_employment_detail_request->prc_identification;
        $employee_employment_detail -> board_exam = $employee_employment_detail_request->board_exam;
        $employee_employment_detail -> license_expiry_date = date('Y-m-d', strtotime($employee_employment_detail_request->license_expiry_date));
        $employee_employment_detail -> save();

        return $employee_employment_detail;
    }



}
