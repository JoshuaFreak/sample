<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\HrmsMainController;
use App\Models\Generic\GenRole;
use App\Models\EmploymentStatus;
use App\Models\Employee;
use App\Models\Gender;
use Datatables;
use Config;    
use Hash;
use Excel;
use Carbon\Carbon;

class EmployeeReportController extends HrmsMainController {

  	public function index(){

        return view('employee_report.index');
  	}


  	public function pdfEmployeeReport(){


	    // $logo = str_replace("\\","/",public_path())."/images/logo.png";
	    $employee_id = \Input::get('employee_id');

        $filter_data = \Input::get('filter_data');
        $filter = \Input::get('filter');
        
        if($filter_data != "" && $filter_data != null) 
        {
              if($filter == "Position")
              {
    
		     	$employee_list = Employee::join('person','employee.person_id','=','person.id')
			     	->join('gen_role','employee.gen_role_id','=','gen_role.id')
		            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
			     	->select('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name','person.address','gen_role.name', 'employment_status.employment_status_name')
			     	->where('gen_role.id',$filter_data)
                  	->orderBy('person.last_name', 'ASC')
			       	->get();
              } 
              elseif($filter == "EmploymentStatus")
               {
			     $employee_list = Employee::join('person','employee.person_id','=','person.id')
			     	->join('gen_role','employee.gen_role_id','=','gen_role.id')
		            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
			     	->select('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name','person.address','gen_role.name', 'employment_status.employment_status_name')
			     	->where('employment_status.id',$filter_data)
			       	->get();
               } 
              elseif($filter == "Gender")
               {
			     $employee_list = Employee::join('person','employee.person_id','=','person.id')
			     	->join('gen_role','employee.gen_role_id','=','gen_role.id')
		            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
		            ->leftJoin('gender','person.gender_id','=','gender.id')
			     	->select('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name','person.address','gen_role.name', 'employment_status.employment_status_name')
			     	->where('gender.id',$filter_data)
                  	->orderBy('person.last_name', 'ASC')
			       	->get();
               }
           }
           else
           {
			     $employee_list = Employee::join('person','employee.person_id','=','person.id')
			     	->join('gen_role','employee.gen_role_id','=','gen_role.id')
		            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
			     	->select('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name','person.address','gen_role.name', 'employment_status.employment_status_name')
                  	->orderBy('person.last_name', 'ASC')
			       	->get();

           }

	     	    $gen_role = GenRole::where('id',$filter_data)->select('gen_role.id','gen_role.name')->get()->last();
	     	    $employment_status = EmploymentStatus::where('id',$filter_data)->select('employment_status.id','employment_status.employment_status_name')->get()->last();
	     	    $gender = Gender::where('id',$filter_data)->select('gender.id','gender.gender_name')->get()->last();
			    $pdf = \PDF::loadView('employee_report/pdf_employee_report', array('employee_list'=>$employee_list,'filter_data'=>$filter_data,'filter'=>$filter,'gen_role'=>$gen_role,'employment_status'=>$employment_status,'gender'=>$gender))->setOrientation('landscape');

			    return $pdf->stream();
			    // return $pdf->download('Export_Entry.pdf');

  	}

}