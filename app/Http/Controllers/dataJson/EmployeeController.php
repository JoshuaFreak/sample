<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Person;
use App\Models\ProgramSkill;
use App\Models\Gender;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Generic\GenRole;
use App\Models\CivilStatus;
use App\Models\Course;
use App\Models\Campus;
use App\Models\EmployeeDependent;
use App\Models\EmployeeContact;
use App\Models\EmployeeContributionNumber;
use App\Models\EmployeeWorkingExperience;
use App\Models\EmployeeCertificate;
use App\Models\PersonSeminar;
use App\Models\EmployeeRequirement;
use App\Models\EmploymentStatus;
use App\Models\PersonEducation;
use App\Models\DependentRelationship;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class EmployeeController extends Controller {

   public function dataJson(){
      $condition = \Input::all();
      $query = Employee::join('person','employee.person_id','=','person.id')
                        ->leftJoin('teacher','employee.id','=','teacher.employee_id')
                        ->select('employee.id as employee_id','employee.employee_no','person.id as person_id','person.first_name','person.middle_name','person.last_name','person.nickname','teacher.id as teacher_id','employee.room_id');
     
     // print $condition["query"];
      //$query->where('last_name', 'LIKE', "%get%");  


      foreach($condition as $column => $value)
      {
        if($column == 'query')
        {
          $query->where('first_name', 'LIKE', "%$value%")
                ->where('person.is_active',1)
                ->orWhere(function($query) use($value){
                      $query->where('middle_name', 'LIKE', "%$value%")
                            ->where('person.is_active',1);
                 })->orwhere(function($query) use($value){
                      $query->where('last_name', 'LIKE', "%$value%")
                            ->where('person.is_active',1);
                 })->orwhere(function($query) use($value){
                      $query->where('employee_no', 'LIKE', "%$value%")
                            ->where('person.is_active',1);
                 })->orwhere(function($query) use($value){
                      $query->where('nickname', 'LIKE', "%$value%")
                            ->where('person.is_active',1);
                 });
        //         ->orWhere('middle_name', 'LIKE', "%$value%")
        //         ->orWhere('last_name', 'LIKE', "%$value%")
        //         ->orWhere('employee_no', 'LIKE', "%$value%") 
        //         ->orWhere('nickname', 'LIKE', "%$value%");  
        }
      }

      $employee = $query->orderBy('last_name','ASC')->get();

      return response()->json($employee);
    }

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */

   public function dataJsonTeacher(){
      $condition = \Input::all();
      $query = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                      ->join('person', 'employee.person_id', '=', 'person.id')
                      ->select(['employee.id', 'teacher.id as teacher_id', 'person.first_name','person.middle_name','person.last_name']);

      foreach($condition as $column => $value)
      {
        if($column == 'query')
        {
          $query->where('first_name', 'LIKE', "%$value%")
                ->orWhere('middle_name', 'LIKE', "%$value%")
                ->orWhere('last_name', 'LIKE', "%$value%");
        }
        else
        {
          $query->where($column, '=', $value);
        }
      }

      $employee = $query->orderBy('last_name','ASC')->get();

      return response()->json($employee);
    }

    public function programCategoryDataJson()
    {
        $condition = \Input::all();
        $query = ProgramSkill::where('program_skill.is_active','!=',0)->select();


        foreach($condition as $column => $value)
        {
            if($column != 'WOLFPAGE')
            {
              $query->where($column, '=', $value);
            }
        }
        $program_skill = $query->select(['program_skill.id as value','program_skill.skill_name as text'])->get();

        return response()->json($program_skill);
    }

    public function employee201Pdf()
    {
        $employee_id = \Input::get('id');

        $employee = Employee::find($employee_id);

        $person = Person::find($employee->person_id);
        $img = 0;
        if($person -> photo_id != 0)
        {
          $img = Photo::find($person -> photo_id);
          $img = $img -> img;
        }

        $person_education_list = PersonEducation::where('person_id', $employee->person_id)->get();
        $gender_list = Gender::orderBy('gender.id','ASC')->get();
        $civil_status_list = CivilStatus::orderBy('civil_status.civil_status_name', 'ASC')->get();
        // $religion_list = Religion::orderBy('religion.id', 'ASC')->get();
        // $citizenship_list = Citizenship::orderBy('citizenship.citizenship_name', 'ASC')->get();
        // $employee_classification_list = EmployeeClassification::orderBy('employee_classification.order','ASC')->get();

        $person_seminar_list = PersonSeminar::where('person_id',$employee->person_id)->get();
        $employment_status_list = EmploymentStatus::orderBy('employment_status.employment_status_name','ASC')->get();
        
        $employee_dependent_list = EmployeeDependent::where('employee_id', $employee_id)->get();
        $employee_contact_list = EmployeeContact::where('employee_id', $employee_id)->get();
        $employee_government_contribution_list = EmployeeContributionNumber::where('employee_id', $employee_id)->get();
        $employee_working_experience_list = EmployeeWorkingExperience::where('employee_id', $employee_id)->get();
        $employee_certificate_list = EmployeeCertificate::where('employee_id', $employee_id)->get();

        $gen_role_list = GenRole::orderBy('gen_role.name','ASC')->get();
        $dependent_relationship_list = DependentRelationship::orderBy('dependent_relationship.dependent_relationship_name','ASC')->get();      
        $course_list = Course::orderBy('course.course_code','ASC')->get();
        $employee_requirement_list = EmployeeRequirement::where('employee_id',$employee_id)->get();
        $campus_list = Campus::orderBy('campus.id','ASC')->get();

        $data = ["img" => $img,"person" => $person,"civil_status_list" => $civil_status_list];

        $pdf = \PDF::loadView('employee.employee_201_pdf', $data);
        return $pdf->stream();
    }
 
}
