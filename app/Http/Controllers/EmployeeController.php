<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\HrmsMainController;
use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Campus;
use App\Models\Citizenship;
use App\Models\CivilStatus;
use App\Models\Course;
use App\Models\CourseAccreditation;
use App\Models\Department;
use App\Models\DependentRelationship;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\EmployeeAccreditation;
use App\Models\EmployeeClassification;
use App\Models\EmployeeCertificate;
use App\Models\EmployeeDependent;
use App\Models\EmployeeContact;
use App\Models\EmployeeContributionNumber;
use App\Models\EmployeePosition;
use App\Models\EmployeeWorkingExperience;
use App\Models\EmployeeEmploymentDetail;
use App\Models\EmployeeRequirement;
use App\Models\EmployeeSeaService;
use App\Models\EmploymentStatus;
use App\Models\Gender;
use App\Models\Generic\GenRole;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Person;
use App\Models\Photo;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\Position;
use App\Models\PersonEducation;
use App\Models\PersonSeminar;
use App\Models\Rank;
use App\Models\Religion;
use App\Models\Requirement;
use App\Models\Room;
use App\Models\Suffix;
use App\Models\Teacher;
use App\Models\TeacherSkill;
use App\Models\VisaStatus;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\EmployeeSaveRequest;
use App\Http\Requests\EmployeeEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;
use Input;
use DB;

class EmployeeController extends HrmsMainController {
 /**
   * Display a listing of the resource.
   *
   * @return Response
   */

    public function index()
    {
        // Show the page
        $employee_id = 0;
        $employee_type_list = EmployeeType::all();
        return view('employee.index',compact('employee_type_list','employee_id'));
    }

    public function indexId($id)
    {
        // Show the page
        $employee_id = $id;
        $employee_type_list = EmployeeType::all();
        return view('employee.index',compact('employee_type_list','employee_id'));
    }

    public function fullDetail()
    {
      $employee_type_list = EmployeeType::all();
      $position_list = Position::all();
      $department_list = Department::all();

      $action = 1;
      $employee_id = \Input::get('employee_id');
      $employee = Employee::find($employee_id);
      $gen_user_role = GenUserRole::join('gen_user','gen_user_role.gen_user_id','=','gen_user.id')
                                ->where('gen_user.person_id',$employee->person_id)
                                ->select(['gen_user_role.gen_role_id','gen_user.campus_id'])
                                ->get()->last();
      
      $person = Person::find($employee->person_id);
      $img = Photo::find($person -> photo_id);
      $person_education_list = PersonEducation::where('person_id',$employee->person_id)->get();
      $gender_list = Gender::orderBy('gender.id','ASC')->get();
      $civil_status_list = CivilStatus::orderBy('civil_status.civil_status_name', 'ASC')->get();
      $blood_type_list = BloodType::orderBy('blood_type.blood_type_name', 'ASC')->get();
      $religion_list = Religion::orderBy('religion.id', 'ASC')->get();
      $citizenship_list = Citizenship::orderBy('citizenship.citizenship_name', 'ASC')->get();
      $employee_classification_list = EmployeeClassification::orderBy('employee_classification.order','ASC')->get();
      $employee_accreditation_list = EmployeeAccreditation::where('employee_id', $employee_id)
                                                            ->orderBy('id','ASC')->get();

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
      // $employee_requirement_list = Requirement::where('requirement_type_id','=','2')
      //                   ->orderBy('requirement.description','ASC')->get();
      $rank_list = Rank::orderBy('rank.rank_name','ASC')->get();
      $campus_list = Campus::orderBy('campus.id','ASC')->get();
      $employee_employment_detail_list = EmployeeEmploymentDetail::where('employee_id',$employee_id)->get();


      return view('employee/full_detail', 
        compact(
            'employee',
            'person',
            'gender_list',
            'campus_list',
            'gen_user_role',
            'employee_type_list',
            'position_list',
            'department_list',
            'civil_status_list',
            'blood_type_list',
            'religion_list',
            'citizenship_list',
            'employee_classification_list',
            'person_education_list',
            'employee_accreditation_list',
            'person_seminar_list',
            'employee_list',
            'employment_status_list',
            'employee_dependent_list',
            'employee_contact_list',
            'employee_government_contribution_list',
            'employee_working_experience_list',
            'employee_certificate_list',
            'gen_role_list',
            'dependent_relationship_list',
            'employee_requirement_list',
            'course_list',
            'rank_list',
            'img',
            'employee_employment_detail_list',
            'action'
        )
      );

    }

    public function employee_list()
    {
      return view('employee.employee_list');

    }

    public function getEmployeeTypeList()
    {
      $id = \Input::get('id');
      $filter = \Input::get('filter');
      $employee_type_list = EmployeeType::all();

      if($id == 0)
      {
        if($filter == "resigned_employee")
        {
          $employee_type = "Resigned Employee/s";
        }
        else
        {
          $employee_type = "All Employee";
        }
      }
      else
      {
        $employee_type = EmployeeType::find($id);
      }

      return view('employee.employee_type_list',compact('employee_type_list','employee_type','id'));
    }

    public function positionDataJson()
    {
      $condition = \Input::all();
      $query = Position::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $curriculum = $query->select([ 'id as value','position_name as text'])->get();

      return response()->json($curriculum);
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
      $gen_role_list = GenRole::all();
      $employee_type_list = EmployeeType::all();
      $employment_status_list = EmploymentStatus::all();
      $position_list = Position::all();
      $department_list = Department::all();
      $civil_status_list = CivilStatus::all();
      $campus_list = Campus::all();
      $gender_list = Gender::all();
        // Show the page
      // echo mt_rand(10000, 99999);
      // exit();
      return view('employee.create', compact('gen_role_list','employee_type_list','employment_status_list','position_list','department_list','civil_status_list','campus_list','gender_list'));
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function createJson(EmployeeSaveRequest $employee_save_request){

      $skill_id_arr = $employee_save_request -> skill_id;
      $skill_data_arr = $employee_save_request -> skill_data;
      $skill_db_arr = $employee_save_request -> skill_db;

      $max_skill = sizeof($skill_id_arr);
      json_decode(serialize($skill_id_arr));
      json_decode(serialize($skill_data_arr));
      json_decode(serialize($skill_db_arr));

      for($i= 0; $i < $max_skill; $i++){

          if($skill_db_arr[$i] == 0)
          {
              $teacher_skill = new TeacherSkill();
              $teacher_skill -> employee_id = $employee_save_request -> employee_id;
              $teacher_skill -> program_skill_id = $skill_id_arr[$i];
              $teacher_skill -> is_default = $skill_data_arr[$i];
              $teacher_skill -> save();
          }
          else
          {
              $teacher_skill = TeacherSkill::find($skill_id_arr[$i]);
              $teacher_skill -> is_default = $skill_data_arr[$i];
              $teacher_skill -> save();
          }
      }

      $image_canvas = \Input::get('image_canvas');

      $person = Person::find($employee_save_request -> person_id);
      $person -> first_name = $employee_save_request -> first_name;
      $person -> middle_name = $employee_save_request -> middle_name;
      $person -> last_name = $employee_save_request -> last_name;
      $person -> nickname = $employee_save_request -> nickname;
      $person -> address = $employee_save_request -> address;
      $person -> contact_no = $employee_save_request -> contact_no;
      $person -> gender_id = $employee_save_request -> gender_id;
      $person -> birthdate = date('Y-m-d',strtotime($employee_save_request -> birthdate));
      $person -> place_of_birth = $employee_save_request -> place_of_birth;
      $person -> civil_status_id = $employee_save_request -> civil_status_id;
      $person -> is_active = $employee_save_request -> is_active;
      $person -> save();

      $employee = Employee::find($employee_save_request -> employee_id);
      $employee -> person_id = $person -> id;
      $employee -> position_id = $employee_save_request -> position_id;
      $employee -> date_hired = $employee_save_request -> date_hired;
      $employee -> end_of_contract = $employee_save_request -> end_of_contract;
      $employee -> service_remark = $employee_save_request -> service_remark;
      $employee -> contract_from = $employee_save_request -> contract_date_start;
      $employee -> contract_to = $employee_save_request -> contract_date_end;
      $employee -> rate = $employee_save_request -> rate;
      $employee -> room_id = $employee_save_request -> room_id;
      $employee -> program_id = $employee_save_request -> program_id;
      $employee -> employment_status_id = $employee_save_request -> employment_status_id;
      $employee -> employee_type_id = $employee_save_request -> employee_type_id;
      $employee -> passport_number = $employee_save_request -> passport_number;
      $employee -> pass_issue_date = $employee_save_request -> pass_issue_date;
      $employee -> pass_expiry_date = $employee_save_request -> pass_expiry_date;
      $employee -> i_card = $employee_save_request -> i_card;
      $employee -> i_card_issue_date = $employee_save_request -> i_card_issue_date;
      $employee -> i_card_expiry_date = $employee_save_request -> i_card_expiry_date;
      // $employee -> visa = $employee_save_request -> visa;
      $employee -> visa_status_id = $employee_save_request -> visa_status;
      $employee -> visa_date = date('Y-m-d',strtotime($employee_save_request -> visa_date));
      $employee -> visa_end_date = date('Y-m-d',strtotime($employee_save_request -> visa_end_date));
      $employee -> save();
    

      $employee -> save();
      $person -> save();

      $gen_user_role_id = GenUserRole::join('gen_user','gen_user_role.gen_user_id','=','gen_user.id')
                      ->where('gen_user.person_id',$employee_save_request -> person_id)
                      ->select(['gen_user_role.id'])
                      ->get()->last();


      $gen_user_role = GenUserRole::find($gen_user_role_id -> id);
      $gen_user_role->gen_role_id = $employee_save_request -> gen_role_id;
      $gen_user_role->save();

      $gen_user = GenUser::find($gen_user_role -> gen_user_id);

      if($employee_save_request -> gen_role_id != 2)
      {
        $gen_user -> campus_id = $employee_save_request -> campus_id; 
      }
      else
      {
        $gen_user -> campus_id = 0; 
      }

      $gen_user -> save();

      $teacher_data = Teacher::where('teacher.employee_id',$employee -> id)->count();

      if($teacher_data == 0)
      {
         $teacher = new Teacher();
         $teacher -> employee_id = $employee -> id;
         $teacher -> save();
      }

      // $image_canvas = str_replace('data:image/png;base64,', '', $image_canvas);
      // $image_canvas = str_replace('data:image/jpeg;base64,', '', $image_canvas);
      // $image_canvas = str_replace('data:image/jpg;base64,', '', $image_canvas);

      if($image_canvas)
      {
        list($type, $image_canvas) = explode(';', $image_canvas);
        list(, $image_canvas)      = explode(',', $image_canvas);
        $image_canvas = base64_decode($image_canvas);
      }
      // file_put_contents('/tmp/image.png', $data);

      $value = $image_canvas;
      if($value != "")
      {
          // $image_canvas = str_replace(' ', '+', $image_canvas);
          // $data = base64_decode($image_canvas);
          $file = 'assets/site/images/person_images/' . ucwords($person->last_name) .''. ucwords($person->first_name).'.png';
          // $file = 'assets/site/images/person_images/' . ucwords($enrollment_request->last_name) . 2 . '.png';
          // try
          // {
            file_put_contents( $file, $image_canvas);
            // move_uploaded_file($image_canvas, $file);
          // }
          // catch(Exception $e)
          // {
          //   echo 'Caught exception: ',  $e->getMessage(), "\n";
          // }
          $this->migrateImageToDB($file, $person -> id);
          return response()->json($person);
      }
    }

    public function getPosition(){

      $employee_id = \Input::get('employee_id');
      $employee_position = EmployeePosition::leftJoin('position','employee_position.position_id','=','position.id')
                ->leftJoin('employment_status','employee_position.employment_status_id','=','employment_status.id')
                ->where('employee_position.employee_id',$employee_id)
                ->select(['employee_position.id','employee_position.employee_type_id','employee_position.position_id','employee_position.employment_status_id','employee_position.rate','position.position_name','employment_status.employment_status_name','employee_position.contract_date_start','employee_position.contract_date_end'])
                ->get();

      return response()->json($employee_position);
    }

    public function savePosition(){

      $employee_id = \Input::get('employee_id');
      $position_array = \Input::get('position_array');

      $max_position = sizeof($position_array);
      json_decode(serialize($position_array));

        for($i= 0; $i < $max_position; $i++){

            $pos_arr = [];
            $pos_arr = $position_array[$i];

            $max_position1 = sizeof($pos_arr);
            json_decode(serialize($pos_arr));

            $id = $pos_arr[4];

            if($id != 0)
            {
                $position = EmployeePosition::find($id);
                $position -> employee_id = $employee_id;
                $position -> position_id = $pos_arr[0];
                $position -> employee_type_id = $pos_arr[1];
                $position -> employment_status_id = $pos_arr[2];
                $position -> rate = $pos_arr[3];
                $position -> contract_date_start = $pos_arr[5];
                $position -> contract_date_end = $pos_arr[6];
                $position -> save();
            }
            else
            {
                $position = new EmployeePosition();
                $position -> employee_id = $employee_id;
                $position -> position_id = $pos_arr[0];
                $position -> employee_type_id = $pos_arr[1];
                $position -> employment_status_id = $pos_arr[2];
                $position -> rate = $pos_arr[3];
                $position -> contract_date_start = $pos_arr[5];
                $position -> contract_date_end = $pos_arr[6];
                $position -> save();
            }
          
        }


    }

   public function postCreate(EmployeeRequest $employee_request) {

      $person = new Person();
      $person -> first_name = $employee_request -> first_name;
      $person -> middle_name = $employee_request -> middle_name;
      $person -> last_name = $employee_request -> last_name;
      $person -> nickname = $employee_request -> nickname;
      $person -> address = $employee_request -> address;
      $person -> contact_no = $employee_request -> contact_no;
      $person -> birthdate = $employee_request -> birthdate;
      $person -> gender_id = $employee_request -> gender_id;
      // $person -> place_of_birth = $employee_request -> place_of_birth;
      $person -> civil_status_id = $employee_request -> civil_status_id;
      $person -> is_active = 1;
      $person -> save();

      $employee = new Employee();
      $employee -> person_id = $person -> id;
      $employee -> position_id = $employee_request -> position_id;
      $employee -> date_hired = $employee_request -> date_hired;
      $employee -> contract_from = $employee_request -> contract_date_start;
      $employee -> contract_to = $employee_request -> contract_date_end;
      $employee -> rate = $employee_request -> rate;
      $employee -> employment_status_id = $employee_request -> employment_status_id;
      $employee -> employee_type_id = $employee_request -> employee_type_id;
      $employee -> passport_number = $employee_request -> passport_number;
      // $employee -> i_card = $employee_request -> i_card;
      $employee -> save();

      // $file = Input::file('image_canvas');
      $image_canvas = $employee_request -> image_canvas;
      $id = $employee-> id;
      $person_id = $person -> id;
      if($image_canvas)
      {
        $image_canvas = str_replace('data:image/png;base64,', '', $image_canvas);
        $image_canvas = str_replace('data:image/jpeg;base64,', '', $image_canvas);
        $image_canvas = str_replace('data:image/jpg;base64,', '', $image_canvas);

        $value = $image_canvas;
        if($value != "")
        {
            $image_canvas = str_replace(' ', '+', $image_canvas);
            $data = base64_decode($image_canvas);
            $file = 'assets/site/images/person_images/' . ucwords($person->last_name) . ucwords($person->first_name).'.png';
            // $file = 'assets/site/images/person_images/' . ucwords($enrollment_request->last_name) . 2 . '.png';
            $success = file_put_contents($file, $data);
            $this->migrateImageToDB($file, $person -> id);
        }
        // $extension = Input::file('image_canvas')->getClientOriginalExtension(); // getting image extension

        // //check if valid image file by checking the extension
        // if($extension =="jpg" || $extension=="jpeg"  || $extension=="png" && $file == ""){
        //   $destination_path = 'assets/site/images/person_images/'; // upload path
        //   $original_file_name_with_extension = Input::file('image_canvas')->getClientOriginalName();
        //   $original_file_name_without_extension = pathinfo($original_file_name_with_extension, PATHINFO_FILENAME);
        //   $file_name = $original_file_name_without_extension.rand(11111,99999).'.'.$extension; // renameing image
        //   Input::file('image_canvas')->move($destination_path, $file_name); // uploading file to given path
       

        //   $this->migrateImageToDB("assets/site/images/person_images/".$file_name, $person_id);

        // //  Session::flash('success', 'Upload successfully'); 
        // // return Redirect::to('registrar_report/upload_image/');
        // }
        
        else
        {
          // Session::flash('error', 'Uploaded file is not valid');
          //  return Redirect::to('registrar_report/upload_image/');  
        }
      }


      $gen_user = new GenUser();
      $gen_user->person_id = $person->id; 
      $gen_user->username = $person -> first_name .''. mt_rand(10000, 99999);
      $gen_user->password = Hash::make($gen_user->username);
      $gen_user->secret = $gen_user->username;
      $gen_user->confirmation_code = md5(microtime().Config::get('app.key'));
      $gen_user->confirmed = 1;

      if($employee_request -> gen_role_id != 2)
      {
        $gen_user -> campus_id = $employee_request -> campus_id; 
      }
      else
      {
        $gen_user -> campus_id = 0; 
      }

      $gen_user->save();

      $gen_user_role = new GenUserRole();
      $gen_user_role->gen_role_id = $employee_request->gen_role_id;
      $gen_user_role->gen_user_id = $gen_user->id;
      $gen_user_role->save();

      if($employee_request->gen_role_id == 1)
      {
         $teacher = new Teacher();
         $teacher -> employee_id = $employee -> id;
         $teacher -> save();
      }

        return redirect('employee');
    }

    private function migrateImageToDB($file_name, $id){

      $photo = new Photo();
      $photo->img = $file_name;
      $photo->save();

      $person = Person::find($id);
      $person -> photo_id = $photo->id;
      $person->save();
    }
 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $employee = Employee::find($id);
        $person = Person::find($employee->person_id);
        $gen_role_list = GenRole::all();
        $suffix_list = Suffix::orderBy('suffix.suffix_name', 'ASC')->get();        
        $action = 1;
       //var_dump($its_customs_office);
        return view('employee/edit',compact('action','employee','person','suffix_list','gen_role_list'));
    }


    public function getDetail()
    {
      $employee_type_list = EmployeeType::all();
      $position_list = Position::all();
      $department_list = Department::all();

      $action = 1;
      $employee_id = \Input::get('employee_id');

      $teacher_skill_list = TeacherSkill::leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                    ->leftJoin('program_category','program_skill.program_category_id','=','program_category.id')
                    ->where('teacher_skill.employee_id',$employee_id)
                    ->select(['teacher_skill.id','teacher_skill.is_default','program_skill.skill_name','program_category.program_category_name','program_skill.is_active'])
                    ->get();

      $employee = Employee::find($employee_id);
      $gen_user_role = GenUserRole::join('gen_user','gen_user_role.gen_user_id','=','gen_user.id')
                                ->where('gen_user.person_id',$employee->person_id)
                                ->select(['gen_user_role.gen_role_id','gen_user.campus_id'])
                                ->get()->last();
      
      $person = Person::find($employee->person_id);
      $img = Photo::find($person -> photo_id);
      $person_education_list = PersonEducation::where('person_id',$employee->person_id)->get();
      $gender_list = Gender::orderBy('gender.id','ASC')->get();
      $civil_status_list = CivilStatus::orderBy('civil_status.civil_status_name', 'ASC')->get();
      $blood_type_list = BloodType::orderBy('blood_type.blood_type_name', 'ASC')->get();
      $religion_list = Religion::orderBy('religion.id', 'ASC')->get();
      $citizenship_list = Citizenship::orderBy('citizenship.citizenship_name', 'ASC')->get();
      $employee_classification_list = EmployeeClassification::orderBy('employee_classification.order','ASC')->get();
      $employee_accreditation_list = EmployeeAccreditation::where('employee_id', $employee_id)
                                                            ->orderBy('id','ASC')->get();

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
      // $employee_requirement_list = Requirement::where('requirement_type_id','=','2')
      //                   ->orderBy('requirement.description','ASC')->get();
      $visa_status_list = VisaStatus::orderBy('visa_status.id','ASC')->get();
      $rank_list = Rank::orderBy('rank.rank_name','ASC')->get();
      $campus_list = Campus::orderBy('campus.id','ASC')->get();
      $room_list = Room::orderBy('room.id','ASC')->get();
      $program_list = Program::orderBy('program.program_name','ASC')->get();
      $program_category_list = ProgramCategory::orderBy('program_category.id','ASC')->get();
      $employee_employment_detail_list = EmployeeEmploymentDetail::where('employee_id',$employee_id)->get();


      return view('employee/detail', 
        compact(
            'employee',
            'teacher_skill_list',
            'person',
            'gender_list',
            'room_list',
            'campus_list',
            'gen_user_role',
            'employee_type_list',
            'position_list',
            'department_list',
            'civil_status_list',
            'blood_type_list',
            'religion_list',
            'citizenship_list',
            'employee_classification_list',
            'person_education_list',
            'employee_accreditation_list',
            'person_seminar_list',
            'employee_list',
            'employment_status_list',
            'employee_dependent_list',
            'employee_contact_list',
            'employee_government_contribution_list',
            'employee_working_experience_list',
            'employee_certificate_list',
            'gen_role_list',
            'dependent_relationship_list',
            'employee_requirement_list',
            'course_list',
            'program_list',
            'program_category_list',
            'visa_status_list',
            'rank_list',
            'img',
            'employee_employment_detail_list',
            'action'
        )
      );

    }

    public function printDetail()
    {

      $employee_type_list = EmployeeType::all();
      $position_list = Position::all();
      $department_list = Department::all();

      $employee_id = \Input::get('employee_id');

      $teacher_skill_list = TeacherSkill::leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                    ->leftJoin('program_category','program_skill.program_category_id','=','program_category.id')
                    ->where('teacher_skill.employee_id',$employee_id)
                    ->select(['teacher_skill.id','teacher_skill.is_default','program_skill.skill_name','program_category.program_category_name','program_skill.is_active'])
                    ->get();

      $employee = Employee::find($employee_id);
      $gen_user_role = GenUserRole::join('gen_user','gen_user_role.gen_user_id','=','gen_user.id')
                                ->where('gen_user.person_id',$employee->person_id)
                                ->select(['gen_user_role.gen_role_id','gen_user.campus_id'])
                                ->get()->last();
      
      $person = Person::find($employee->person_id);
      $img = Photo::find($person -> photo_id);
      $person_education_list = PersonEducation::where('person_id',$employee->person_id)->get();
      $gender_list = Gender::orderBy('gender.id','ASC')->get();
      $civil_status_list = CivilStatus::orderBy('civil_status.civil_status_name', 'ASC')->get();
      $blood_type_list = BloodType::orderBy('blood_type.blood_type_name', 'ASC')->get();
      $religion_list = Religion::orderBy('religion.id', 'ASC')->get();
      $citizenship_list = Citizenship::orderBy('citizenship.citizenship_name', 'ASC')->get();
      $employee_classification_list = EmployeeClassification::orderBy('employee_classification.order','ASC')->get();
      $employee_accreditation_list = EmployeeAccreditation::where('employee_id', $employee_id)
                                                            ->orderBy('id','ASC')->get();

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
      // $employee_requirement_list = Requirement::where('requirement_type_id','=','2')
      //                   ->orderBy('requirement.description','ASC')->get();
      $visa_status_list = VisaStatus::orderBy('visa_status.id','ASC')->get();
      $rank_list = Rank::orderBy('rank.rank_name','ASC')->get();
      $campus_list = Campus::orderBy('campus.id','ASC')->get();
      $room_list = Room::orderBy('room.id','ASC')->get();
      $program_list = Program::orderBy('program.program_name','ASC')->get();
      $program_category_list = ProgramCategory::orderBy('program_category.id','ASC')->get();
      $employee_employment_detail_list = EmployeeEmploymentDetail::where('employee_id',$employee_id)->get();


      $data = [
            'employee'=>$employee,
            'teacher_skill_list'=>$teacher_skill_list,
            'person'=>$person,
            'gender_list'=>$gender_list,
            'room_list'=>$room_list,
            'campus_list'=>$campus_list,
            'gen_user_role'=>$gen_user_role,
            'employee_type_list'=>$employee_type_list,
            'position_list'=>$position_list,
            'department_list'=>$department_list,
            'civil_status_list'=>$civil_status_list,
            'blood_type_list'=>$blood_type_list,
            'religion_list'=>$religion_list,
            'citizenship_list'=>$citizenship_list,
            'employee_classification_list'=>$employee_classification_list,
            'person_education_list'=>$person_education_list,
            'employee_accreditation_list'=>$employee_accreditation_list,
            'person_seminar_list'=>$person_seminar_list,
            'employment_status_list'=>$employment_status_list,
            'employee_dependent_list'=>$employee_dependent_list,
            'employee_contact_list'=>$employee_contact_list,
            'employee_government_contribution_list'=>$employee_government_contribution_list,
            'employee_working_experience_list'=>$employee_working_experience_list,
            'employee_certificate_list'=>$employee_certificate_list,
            'gen_role_list'=>$gen_role_list,
            'dependent_relationship_list'=>$dependent_relationship_list,
            'employee_requirement_list'=>$employee_requirement_list,
            'course_list'=>$course_list,
            'program_list'=>$program_list,
            'program_category_list'=>$program_category_list,
            'visa_status_list'=>$visa_status_list,
            'rank_list'=>$rank_list,
            'img'=>$img,
            'employee_employment_detail_list'=>$employee_employment_detail_list
      ];

      $pdf = \PDF::loadView('employee.employee_detail_pdf',$data)->setPaper('letter', 'portrait');
      return $pdf->stream();
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(EmployeeEditRequest $employee_edit_request, $id) {
      
        $employee = Employee::find($id);
        $employee -> person_id = $employee_edit_request->person_id;
        $employee -> gen_role_id = $employee_edit_request->gen_role_id;
        
        $employee -> is_active = $employee_edit_request->is_active;
        // $employee -> updated_by_id = Auth::id();

        $person = Person::find($employee->person_id);
        $person -> first_name = $employee_edit_request->first_name;
        $person -> middle_name = $employee_edit_request->middle_name;
        $person -> last_name = $employee_edit_request->last_name;
        $person -> suffix_id = $employee_edit_request->suffix_id;

               
        $gen_user = new GenUser();
        $gen_user->person_id = $person->id; 
        $gen_user->username = $employee->employee_no;
        $gen_user->confirmation_code = md5(microtime().Config::get('app.key'));
        $gen_user->confirmed = 1;
        $gen_user->password = Hash::make($employee->employee_no);

        $gen_user->save();
        
        $gen_user_role = new GenUserRole();
        $gen_user_role->gen_role_id = $employee_edit_request->gen_role_id;
        $gen_user_role->gen_user_id = $gen_user->id;
        $gen_user_role->save();

        $person -> save();
        $employee -> save();

        return redirect('employee/employee_list');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function setToActive()
    {
        $employee_id = \Input::get('employee_id');
        $employee = Employee::find($employee_id);
        $person = Person::find($employee -> person_id);
        $person -> is_active = 1;
        $person -> save();

        return response()->json($person);

    }

    public function getDelete($id)
     {
        // Show the page
        $employee = Employee::find($id);
        $person = Person::find($employee->person_id);
        $suffix_list = Suffix::orderBy('suffix.suffix_name', 'ASC')->get();
        $gen_role_list = GenRole::orderBy('gen_role.name', 'ASC')->get();
        $religion_list = Religion::orderBy('religion.id', 'ASC')->get();
        $civil_status_list = CivilStatus::orderBy('civil_status.civil_status_name', 'ASC')->get();
        $citizenship_list = Citizenship::orderBy('citizenship.citizenship_name', 'ASC')->get();
        $blood_type_list = BloodType::orderBy('blood_type.blood_type_name', 'ASC')->get();
        $employment_status_list = EmploymentStatus::orderBy('employment_status.employment_status_name','ASC')->get();
        $tax_status_code_list = TaxStatusCode::orderBy('tax_status_code.tax_status_code_name','ASC')->get();
        
        $gender_list = Gender::orderBy('gender.id','ASC')->get();
        $action = 1;

        return view('employee/delete', compact('employee', 'action','person','suffix_list','gen_role_list','religion_list','civil_status_list','citizenship_list','blood_type_list','employment_status_list','tax_status_code_list','gender_list'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function deleteRow(DeleteRequest $request)
    {
        $id = \Input::get('id');
        $table = \Input::get('table');
        // $employee = $table::find($id);
        $table = DB::table($table)->where('id',$id)->delete();
        // $table -> delete();

        return response()->json($table);
    }

    public function deleteEmployeeData(DeleteRequest $request)
    {
        $id = \Input::get('id');
        // $employee = $table::find($id);
        $employee = Employee::where('id',$id)->delete();
        $teacher = Teacher::where('employee_id',$id)->delete();
        // $table -> delete();

        return response()->json($teacher);
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data() {

      $id = \Input::get('id');
      $filter = \Input::get('filter');
      if($id == 0)
      {
          if($filter == "resigned_employee")
          {
            $employee_list = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                ->leftJoin('photo', 'person.photo_id', '=', 'photo.id')
                ->leftJoin('civil_status', 'person.civil_status_id', '=', 'civil_status.id')
                ->leftJoin('employment_status', 'employee.employment_status_id', '=', 'employment_status.id')
                ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                ->leftJoin('department', 'position.department_id', '=', 'department.id')
                ->where('person.last_name',"!=", "")
                ->where('person.last_name',"!=", "Administrator")
                ->where('person.last_name',"!=", "Administrator")
                ->where('person.is_active', 0)
                ->select(array('photo.img','person.last_name','employee.id','person.middle_name','person.first_name','person.nickname','person.contact_no','person.birthdate','person.address','civil_status.civil_status_name','position.position_name','employment_status.employment_status_name','department.department_name','person.is_active'));
          }
          else
          { 
            $employee_list = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                ->leftJoin('photo', 'person.photo_id', '=', 'photo.id')
                ->leftJoin('civil_status', 'person.civil_status_id', '=', 'civil_status.id')
                ->leftJoin('employment_status', 'employee.employment_status_id', '=', 'employment_status.id')
                ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                ->leftJoin('department', 'position.department_id', '=', 'department.id')
                ->where('person.last_name',"!=", "")
                ->where('person.last_name',"!=", "Administrator")
                ->where('person.last_name',"!=", "Administrator")
                ->where('person.is_active', 1)
                ->select(array('photo.img','person.last_name','employee.id','person.middle_name','person.first_name','person.nickname','person.contact_no','person.birthdate','person.address','civil_status.civil_status_name','position.position_name','employment_status.employment_status_name','department.department_name'));
          }
                // ->orderBy('department.department_name', 'ASC')
                // ->orderBy('person.last_name', 'ASC');
      }
      elseif($filter == "al")
      {
        $employee_list = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                ->leftJoin('photo', 'person.photo_id', '=', 'photo.id')
                ->leftJoin('civil_status', 'person.civil_status_id', '=', 'civil_status.id')
                ->leftJoin('employment_status', 'employee.employment_status_id', '=', 'employment_status.id')
                ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                ->leftJoin('department', 'position.department_id', '=', 'department.id')
                ->where('employee.employee_type_id',$id)
                ->where('person.last_name',"!=", "")
                ->where('person.last_name',"!=", "Administrator")
                ->where('position.position_name',"=", "Academic Leader")
                ->where('person.is_active', 1)
                ->orWhere('position.position_name',"=", "Academic Team Leader")
                ->orWhere('position.position_name',"=", "IELTS/ Academic Leader")
                ->select(array('photo.img','person.last_name','employee.id','person.middle_name','person.first_name','person.nickname','person.contact_no','person.birthdate','person.address','civil_status.civil_status_name','position.position_name','employment_status.employment_status_name','department.department_name'));
      }
      elseif($filter == "as")
      {
        $employee_list = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                ->leftJoin('photo', 'person.photo_id', '=', 'photo.id')
                ->leftJoin('civil_status', 'person.civil_status_id', '=', 'civil_status.id')
                ->leftJoin('employment_status', 'employee.employment_status_id', '=', 'employment_status.id')
                ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                ->leftJoin('department', 'position.department_id', '=', 'department.id')
                ->where('employee.employee_type_id',$id)
                ->where('person.last_name',"!=", "")
                ->where('person.last_name',"!=", "Administrator")
                ->where('person.is_active', 1)
                ->where('position.position_name',"=", "Academic Administrative Assistant")
                ->select(array('photo.img','person.last_name','employee.id','person.middle_name','person.first_name','person.nickname','person.contact_no','person.birthdate','person.address','civil_status.civil_status_name','position.position_name','employment_status.employment_status_name','department.department_name'));
      }
      else
      {
        $employee_list = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                ->leftJoin('photo', 'person.photo_id', '=', 'photo.id')
                ->leftJoin('civil_status', 'person.civil_status_id', '=', 'civil_status.id')
                ->leftJoin('employment_status', 'employee.employment_status_id', '=', 'employment_status.id')
                ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                ->leftJoin('department', 'position.department_id', '=', 'department.id')
                ->where('employee.employee_type_id',$id)
                ->where('person.last_name',"!=", "")
                ->where('person.last_name',"!=", "Administrator")
                ->where('person.is_active', 1)
                ->select(array('photo.img','person.last_name','employee.id','person.middle_name','person.first_name','person.nickname','person.contact_no','person.birthdate','person.address','civil_status.civil_status_name','position.position_name','employment_status.employment_status_name','department.department_name'));
                // ->orderBy('department.department_name', 'ASC')
                // ->orderBy('person.last_name', 'ASC');
      }

      if($filter == "resigned_employee")
      {
          return Datatables::of($employee_list)
            // ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name)) }}')
            ->editColumn('is_active','<input type="hidden" name="is_active_val" id="is_active_val" class="reset-data active" value="{{$is_active}}" data-id="{{$id}}"/>
                      <input type="checkbox" data-toggle="toggle" name="is_active" data-id="{{$id}}" id="is_active_{{$id}}" class="is_active reset-data"/>')
            ->editColumn('img','@if($img != "")
                                        <img src="{{asset($img)}}" width="50px;" style="border-radius:50px"/>
                                @else
                                        <img src="{{asset(\'assets/site/images/BLANK_IMAGE.jpg\')}}" width="50px;" height="50px;" style="border-radius:50px"/>
                                @endif')
            ->editColumn('last_name','{{ ucwords(strtolower(str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$last_name)).",  ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$first_name))." ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$middle_name)))) }}')
            ->remove_column('id','middle_name','first_name')
            ->make(true);
      }
      else
      {
          return Datatables::of($employee_list)
            // ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name)) }}')
            ->addColumn('action','<a href="{{ URL::to(\'employee\') }}{{\'/\'}}{{$id}}" target="_blank"><button type="button" class="btn btn-sm btn-primary">View</button></a>')
            ->editColumn('img','@if($img != "")
                                        <img src="{{asset($img)}}" width="50px;" style="border-radius:50px"/>
                                @else
                                        <img src="{{asset(\'assets/site/images/BLANK_IMAGE.jpg\')}}" width="50px;" height="50px;" style="border-radius:50px"/>
                                @endif')
            ->editColumn('last_name','{{ ucwords(strtolower(str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$last_name)).",  ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$first_name))." ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$middle_name)))) }}')
            ->remove_column('id','middle_name','first_name')
            ->make(true);
      }
    }

// public function data()
//     {
//         $employee_id = \Input::get('employee_id');

//         $filter_data = \Input::get('filter_data');
//         $filter = \Input::get('filter');
        
//         if($filter_data != "" && $filter_data != null) 
//         {

//               if($filter == "GenRole")
//               {
//                 $employee_list = Employee::join('person','employee.person_id','=','person.id')
//                   ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//                   ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//                   ->where('gen_role.id',$filter_data)
//                   ->where('gen_role.name', '!=', 'Student')
//                   ->where('gen_role.name', '!=', 'Guardian')
//                   ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                                 'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//                   ->orderBy('employee.employee_no', 'ASC');
//               }
//               elseif($filter == "Gender")
//                {
//                 $employee_list = Employee::join('person','employee.person_id','=','person.id')
//                   ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//                   ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//                   ->leftJoin('gender','person.gender_id','=','gender.id')
//                   ->where('gender.id',$filter_data)
//                   ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                                 'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//                   ->orderBy('employee.employee_no', 'ASC');            
//                } 
//               elseif($filter == "EmploymentStatus")
//                {
//                 $employee_list = Employee::join('person','employee.person_id','=','person.id')
//                   ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//                   ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//                   ->join('employment_status','employee.employment_status_id','=','employment_status.id')
//                   ->where('employment_status.id',$filter_data)
//                   ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                                 'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//                   ->orderBy('employee.employee_no', 'ASC');            
//                } 
//               elseif($filter == "BloodType")
//                {
//                 $employee_list = Employee::join('person','employee.person_id','=','person.id')
//                   ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//                   ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//                   ->leftJoin('blood_type','person.blood_type_id','=','blood_type.id')
//                   ->where('blood_type.id',$filter_data)
//                   ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                                 'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//                   ->orderBy('employee.employee_no', 'ASC');            
//                } 
//               elseif($filter == "Citizenship")
//                {
//                 $employee_list = Employee::join('person','employee.person_id','=','person.id')
//                   ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//                   ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//                   ->leftJoin('citizenship','person.citizenship_id','=','citizenship.id')
//                   ->where('citizenship.id',$filter_data)
//                   ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                                 'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//                   ->orderBy('employee.employee_no', 'ASC');            
//                }  
//               elseif($filter == "Religion")
//                {
//                 $employee_list = Employee::join('person','employee.person_id','=','person.id')
//                   ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//                   ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//                   ->leftJoin('religion','person.religion_id','=','religion.id')
//                   ->where('religion.id',$filter_data)
//                   ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                                 'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//                   ->orderBy('employee.employee_no', 'ASC');            
//                }  
//               elseif($filter == "CivilStatus")
//                {
//                 $employee_list = Employee::join('person','employee.person_id','=','person.id')
//                   ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//                   ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//                   ->leftJoin('civil_status','person.civil_status_id','=','civil_status.id')
//                   ->where('civil_status.id',$filter_data)
//                   ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                                 'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//                   ->orderBy('employee.employee_no', 'ASC');            
//                } 
//                else
//                {

//                }
//         }
      
//         else
//         {
//           $employee_list = Employee::join('person','employee.person_id','=','person.id')

//               ->leftJoin('suffix','person.suffix_id','=','suffix.id')
//               ->join('gen_role','employee.gen_role_id','=','gen_role.id')
//               ->select(array('employee.id','employee.employee_no','person.first_name','person.middle_name','person.last_name',
//                             'suffix.suffix_name','gen_role.name', 'employee.is_active'))
//               ->orderBy('employee.employee_no', 'ASC');

//         }
//         return Datatables::of($employee_list)
//               ->add_column('actions', '<a href="{{{ URL::to(\'employee/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
//                       <a href="{{{ URL::to(\'employee/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
//                       <input type="hidden" name="row" value="{{$id}}" id="row">')
//               ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name." ".$suffix_name)) }}')
//               ->remove_column('id','middle_name','last_name','suffix_name')
//               ->editColumn('is_active','@if($is_active == 1)
//                                           <i class="glyphicon glyphicon-ok"></i>
//                                         @else
//                                           <i class="glyphicon glyphicon-remove"></i>
//                                         @endif')
//               ->make();

//     }
// }
}
