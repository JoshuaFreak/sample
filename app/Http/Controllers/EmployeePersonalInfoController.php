<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\Employee;
use App\Models\PersonEducation;
use Datatables;
use Config;    
use Hash;
use Excel;
use Carbon\Carbon;

class EmployeePersonalInfoController extends RegistrarMainController {

  	public function index(){

        return view('employee_report.personal_info_index');
  	}



    public function getDetail(){

        $employee_no = \Input::get('employee_no');
        $employee = Employee::join('person','employee.person_id','=','person.id')
          ->leftJoin('gender','person.gender_id','=','gender.id')
          ->leftJoin('blood_type','person.blood_type_id','=','blood_type.id')
          ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
          ->leftJoin('religion','person.religion_id','=','religion.id')
          ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
          ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
          ->select('employee.id','employee.employee_no','employee.person_id','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','blood_type.blood_type_name','person.contact_no','citizenship.citizenship_name','person.birth_place','person.birthdate','person.age','person.email_address','religion.religion_name','civil_status.civil_status_name','employee.sss','employee.tin','employee.pagibig','employee.philhealth','employee.date_employed','employment_status.employment_status_name')
          ->where('employee.employee_no','=',$employee_no)
          ->get()->last();

        $primary = PersonEducation::select('person_education.id', 'person_education.school_name')
          ->where('person_education.employee_classification_id','=',1)
          ->where('person_education.person_id','=',$employee->person_id)
          ->get()->last();
        $secondary = PersonEducation::select('person_education.id', 'person_education.school_name')
          ->where('person_education.employee_classification_id','=',2)
          ->where('person_education.person_id','=',$employee->person_id)
          ->get()->last();
        $tertiary = PersonEducation::select('person_education.id', 'person_education.school_name')
          ->where('person_education.employee_classification_id','=',3)
          ->where('person_education.person_id','=',$employee->person_id)
          ->get()->last();

       return view('employee_report/detail', 
          compact(
              'employee',
              'primary',
              'secondary',
              'tertiary'
          )
        );  



        }

  	public function pdfEmployeePersonalInfo(){

  		$employee_no = \Input::get('employee_no');
	    $logo = str_replace("\\","/",public_path())."/images/logo.png";
	    $employee = Employee::join('person','employee.person_id','=','person.id')
     		->leftJoin('gender','person.gender_id','=','gender.id')
     		->leftJoin('blood_type','person.blood_type_id','=','blood_type.id')
     		->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
     		->leftJoin('religion','person.religion_id','=','religion.id')
     		->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
     		->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
      	->select('employee.id','employee.employee_no','employee.person_id','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','blood_type.blood_type_name','person.contact_no','citizenship.citizenship_name','person.birth_place','person.birthdate','person.age','person.email_address','religion.religion_name','civil_status.civil_status_name','employee.sss','employee.tin','employee.pagibig','employee.philhealth','employee.date_employed','employment_status.employment_status_name')
    		->where('employee.employee_no','=',$employee_no)
       	->get()->last();

	    $primary = PersonEducation::select('person_education.id', 'person_education.school_name')
    		->where('person_education.employee_classification_id','=',1)
    		->where('person_education.person_id','=',$employee->person_id)
       	->get()->last();
	    $secondary = PersonEducation::select('person_education.id', 'person_education.school_name')
    		->where('person_education.employee_classification_id','=',2)
    		->where('person_education.person_id','=',$employee->person_id)
       	->get()->last();
	    $tertiary = PersonEducation::select('person_education.id', 'person_education.school_name')
    		->where('person_education.employee_classification_id','=',3)
    		->where('person_education.person_id','=',$employee->person_id)
       	->get()->last();
     
	    $pdf = \PDF::loadView('employee_report/pdf_personal_info_sheet', array('logo'=>$logo,'employee'=>$employee,'primary'=>$primary,'secondary'=>$secondary,'tertiary'=>$tertiary))->setOrientation('portrait');

	    return $pdf->stream();
	    // return $pdf->download('Export_Entry.pdf');

  	}

}