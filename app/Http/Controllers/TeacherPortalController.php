<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth; 
use App\Models\Student;
use App\Models\BatchStudent;
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
use App\Http\Requests\EmployeeSaveRequest;
use App\Http\Controllers\BaseController;
use Datatables;
use Config;
use Hash;
use Input;

class TeacherPortalController extends BaseController {
   

    public function __construct()
    {
            parent::__construct();
            $this->middleware('auth');
            $this->middleware('teachers_portal');
    }

    public function index()
    {

        $user_id = Auth::user()->id;

        $person = GenUser::where('gen_user.id',$user_id)->select(['gen_user.person_id'])->get()->last();

        $teacher = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                    ->where('employee.person_id',$person -> person_id)
                    ->select(['teacher.id','teacher.employee_id'])->get()->last();
    
        $employee_type_list = EmployeeType::all();
        $position_list = Position::all();
        $department_list = Department::all();

        $action = 1;
        $employee_id = $teacher -> employee_id;

        $teacher_skill_list = TeacherSkill::leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                    ->leftJoin('program_category','program_skill.program_category_id','=','program_category.id')
                    ->where('teacher_skill.employee_id',$employee_id)
                    ->select(['teacher_skill.id','teacher_skill.is_default','program_skill.skill_name','program_category.program_category_name','program_skill.is_active'])
                    ->get();

        $employee = Employee::leftJoin('employee_type','employee.employee_type_id','=','employee_type.id')
                            ->leftJoin('room','employee.room_id','=','room.id')
                            ->leftJoin('position','employee.position_id','=','position.id')
                            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
                            ->where('employee.id',$employee_id)
                            ->select(['employee.person_id','employee_type.employee_type_name','room.room_name','position.position_name','employment_status.employment_status_name'])
                            ->get()->last();

        $gen_user_role = GenUserRole::join('gen_user','gen_user_role.gen_user_id','=','gen_user.id')
                                ->leftJoin('campus','gen_user.campus_id','=','campus.id')
                                ->where('gen_user.person_id',$employee->person_id)
                                ->select(['gen_user_role.gen_role_id','gen_user.campus_id','campus.campus_name'])
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
        $room_list = Room::orderBy('room.id','ASC')->get();
        $program_list = Program::orderBy('program.program_name','ASC')->get();
        $program_category_list = ProgramCategory::orderBy('program_category.id','ASC')->get();
        $employee_employment_detail_list = EmployeeEmploymentDetail::where('employee_id',$employee_id)->get();
        return view('teacher_portal/index', 
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
            'rank_list',
            'img',
            'employee_employment_detail_list',
            'action'
        )
      );

    }

    public function createJson(EmployeeSaveRequest $employee_save_request){

      $person = Person::find($employee_save_request -> person_id);
      $person -> first_name = $employee_save_request -> first_name;
      $person -> middle_name = $employee_save_request -> middle_name;
      $person -> last_name = $employee_save_request -> last_name;
      $person -> nickname = $employee_save_request -> nickname;
      $person -> address = $employee_save_request -> address;
      $person -> contact_no = $employee_save_request -> contact_no;
      $person -> birthdate = $employee_save_request -> birthdate;
      $person -> place_of_birth = $employee_save_request -> place_of_birth;
      $person -> civil_status_id = $employee_save_request -> civil_status_id;
      $person -> is_active = 1;
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
      $employee -> i_card = $employee_save_request -> i_card;
      $employee -> save();
    

      $employee -> save();
      $person -> save();

     
    }

    public function schedule()
    {
        return view('teacher_portal/schedule');
    }

    public function getStudentProgram()
    {
    	$student_id = \Input::get('student_id');

    	$program_list = BatchStudent::join('schedule','batch_student.batch_id','=','schedule.batch_id')
		    	->join('batch','schedule.batch_id','=','batch.id')
    			->join('program','batch.program_id','=','program.id')
    			->where('batch_student.student_id',$student_id)
    			->select(['program.id','program.program_name'])
    			->groupBy(['program.id'])
    			->get();

    	return response()->json($program_list);
    }

    public function dataJson()
    {
        $date = Input::get('date');
    	$user_id = Auth::user()->id;

        $person = GenUser::where('gen_user.id',$user_id)->select(['gen_user.person_id'])->get()->last();

        $teacher = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                    ->where('employee.person_id',$person -> person_id)
                    ->select(['teacher.id'])->get()->last();

    	$batch = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
		    	->leftJoin('batch','schedule.batch_id','=','batch.id')
    			->where('schedule.teacher_id',$teacher -> id)
    			->where('schedule.date',$date)
    			->select(['batch.date_from','batch.date_to'])
    			->get()->last();

    	$schedule_list = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
		    	->leftJoin('batch','schedule.batch_id','=','batch.id')
    			->leftJoin('program','batch.program_id','=','program.id')
    			->leftJoin('course','batch.course_id','=','course.id')
    			->leftJoin('room','schedule.room_id','=','room.id')
    			->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
    			->leftJoin('employee','teacher.employee_id','=','employee.id')
    			->leftJoin('person','employee.person_id','=','person.id')
    			->leftJoin('time as time_in','schedule.time_in_id','=','time_in.id')
    			->leftJoin('time as time_out','schedule.time_out_id','=','time_out.id')
    			->where('schedule.teacher_id',$teacher -> id)
    			->where('schedule.date',$date)
    			->select(['schedule.id','batch_student.student_english_name','room.room_name','time_out.time as time_out','time_out.time_session as time_out_session','time_in.time as time_in','time_in.time_session as time_in_session','course.course_name'])
                ->groupBy('time_in.time','time_out.time')
                ->orderBy('schedule.class_id','ASC')
    			->get();

    	$data[0] = $batch;
    	$data[1] = $schedule_list;

    	return response()->json($data);
    }

    public function teacherSchedulePdf(){

        $date = Input::get('date');
        $user_id = Auth::user()->id;

        $person = GenUser::where('gen_user.id',$user_id)->select(['gen_user.person_id'])->get()->last();

        $teacher = Teacher::leftJoin('employee','teacher.employee_id','=','employee.id')
                    ->leftJoin('person','employee.person_id','=','person.id')
                    ->leftJoin('gender','person.gender_id','=','gender.id')
                    ->leftJoin('room','employee.room_id','=','room.id')
                    ->leftJoin('position','employee.position_id','=','position.id')
                    ->leftJoin('department','position.department_id','=','department.id')
                    ->where('employee.person_id',$person -> person_id)
                    ->select(['teacher.id','teacher.employee_id','person.nickname','person.last_name','person.first_name','person.middle_name','gender.gender_name','room.room_name','department.department_name'])->get()->last();

        $program = "";

        $teacher_skill = TeacherSkill::leftJoin('program_skill','teacher_skill.program_skill_id','=','program_skill.id')
                ->leftJoin('program_category','program_skill.program_category_id','=','program_category.id')
                ->where('teacher_skill.employee_id',$teacher -> employee_id)
                ->where('program_skill.is_active',1)
                ->select(['program_skill.skill_name','program_category.program_category_name'])
                ->get();

    	$batch = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->leftJoin('batch','schedule.batch_id','=','batch.id')
		    	->leftJoin('level','batch.level_id','=','level.id')
                ->where('schedule.teacher_id',$teacher -> id)
    			// ->where('schedule.date',$date)
                ->where('batch.date_from','<=',$date)
                ->where('batch.date_to','>=',$date)
    			->select(['batch.date_from','batch.date_to','level.level_code'])
    			->get()->last();

    	$schedule_list = BatchStudent::leftJoin('schedule','batch_student.batch_id','=','schedule.batch_id')
                ->leftJoin('batch','schedule.batch_id','=','batch.id')
		    	->leftJoin('course_capacity','batch.course_capacity_id','=','course_capacity.id')
    			->leftJoin('program','batch.program_id','=','program.id')
    			->leftJoin('course','batch.course_id','=','course.id')
    			->leftJoin('room','schedule.room_id','=','room.id')
    			->leftJoin('teacher','schedule.teacher_id','=','teacher.id')
    			->leftJoin('employee','teacher.employee_id','=','employee.id')
    			->leftJoin('person','employee.person_id','=','person.id')
    			->leftJoin('time as time_in','schedule.time_in_id','=','time_in.id')
                ->leftJoin('time as time_out','schedule.time_out_id','=','time_out.id')
                ->leftJoin('student_info','batch_student.student_id','=','student_info.student_id')
    			->leftJoin('nationality','student_info.nationality_id','=','nationality.id')
                ->where('schedule.teacher_id',$teacher -> id)
    			// ->where('schedule.date',$date)
                ->where('batch.date_from','<=',$date)
                ->where('batch.date_to','>=',$date)
    			->select(['schedule.id','program.program_name','nationality.nationality_name','batch_student.student_english_name','room.room_name','room.room_code','time_out.time as time_out','time_out.time_session as time_out_session','time_in.time as time_in','time_in.time_session as time_in_session','course.course_name','schedule.class_id','course_capacity.capacity_name'])
                ->groupBy('time_in.time','time_out.time')
                ->orderBy('schedule.class_id','ASC')
    			->get();

    	$data = ["schedule_list" => $schedule_list,'teacher_name' => $teacher -> nickname,'teacher' => $teacher,'program' => $program,'batch' => $batch,'teacher_skill' => $teacher_skill];

    	$pdf = \PDF::loadView('teacher_portal.teacher_schedule_pdf', $data)->setPaper('letter', 'landscape');
    	return $pdf->stream();
    }
}
