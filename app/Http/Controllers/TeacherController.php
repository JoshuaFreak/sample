<?php namespace App\Http\Controllers;

use App\Http\Controllers\HrmsMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Degree;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Person;
use App\Models\Program;
use App\Models\SubjectOffered;
use App\Models\Teacher;
use App\Models\TeacherCategory;
use App\Models\TeacherClassification;
use App\Models\TeacherDegree;
use App\Models\TeacherSubject;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\TeacherEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config; 
use Input;   
use Datatables;
use Hash;

class TeacherController extends Controller {
    
    public function index()
    {
        $employee_type_list = EmployeeType::all();
        return view('teacher.index',compact('employee_type_list'));
    }
    public function getTeacherList()
    {

        $classification_list = Classification::orderBy('classification.order','ASC')->get();
        $program_list = Program::where('classification_id',5)->orderBy('program.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $employee_category_list = EmployeeCategory::orderBy('employee_category.id','ASC')->get();     
        $subject_offered_list = SubjectOffered::join('subject', 'subject_offered.subject_id', '=', 'subject.id')
             ->select(array('subject_offered.id','subject.name'))
             ->get();
        // Show the page
        return view('teacher.teacher_list', compact('classification_list', 'program_list', 'classification_level_list', 'employee_category_list', 'subject_offered_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        $program_list = Program::all();
        return view('teacher.create', compact('program_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */


    public function postCreateJsonTeacher()
    {
      $employee_id = Input::get('employee_id');
      $teacher = new Teacher();
      $teacher->employee_id = Input::get('employee_id');
      $teacher->save();

      return response()->json($teacher);

    }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    
    public function postCreate(TeacherRequest $teacher_request) {

        foreach($teacher_request -> program_id as $subject)
        {
            $teacher_subject = new TeacherSubject(); 
            $teacher_subject -> teacher_id = $teacher_request -> teacher_id;
            $teacher_subject -> program_id = $subject;
            $teacher_subject -> is_default = $teacher_request -> default_program_id;
            $teacher_subject -> save();
        }
            
        
        return redirect('teacher/');

    }

    public function defaultProgramDataJson(){
        $condition = \Input::all();
        $query = TeacherSubject::join('teacher','teacher_subject.teacher_id','=','teacher.id')
                ->join('program', 'teacher_subject.program_id', '=', 'program.id')
                ->select('program.id', 'program.program_name', 'teacher.id as teacher_id', 'teacher_subject.is_default', 'teacher_subject.program_id');


        foreach($condition as $column => $value)
        {
          $query->where($column, '=', $value);
        }
        $teacher_default_program = $query->select(['teacher_subject.is_default'])->get()->first();
        $default = TeacherSubject::join('program', 'teacher_subject.is_default', '=', 'program.id')
                                ->where('teacher_subject.is_default', '=', $teacher_default_program -> is_default)
                                ->select(['program.program_name'])->get()->first();

        return response()->json($default);
    }

    public function programdataJson() {
        $condition = \Input::all();
        $query = TeacherSubject::join('teacher','teacher_subject.teacher_id','=','teacher.id')
                ->join('program', 'teacher_subject.program_id', '=', 'program.id')
                ->select('program.id', 'program.program_name', 'teacher.id as teacher_id', 'teacher_subject.is_default', 'teacher_subject.program_id');


        foreach($condition as $column => $value)
        {
          $query->where($column, '=', $value);
        }
        $teacher_program = $query->select(['program.program_name'])->get();

        return response()->json($teacher_program);
    }

    // public function postCreateSubject(TeacherRequest $teacher_request)
    // {
    //     $employee_id = Input::get('employee_id');
    //     $classification_id = Input::get('classification_id'); 
    //     $classification_level_id = Input::get('classification_level_id'); 
    //     $teacher = Teacher::where('employee_id',$employee_id)->get()->first();
        
    //      if(is_array($teacher_request->subject)){
    //              foreach ($teacher_request->subject as $item) {
    //                  $teacher_subject = new TeacherSubject();
    //                  $teacher_subject->subject_offered_id = $item;
    //                  $teacher_subject->teacher_id = $teacher->id;
    //                  $teacher_subject->classification_id = $classification_id;
    //                  $teacher_subject->classification_level_id = $classification_level_id;
    //                  $teacher_subject -> save();
    //              }
    //       }
    //       return response()->json($classification_id);
    // }

    // public function postCreateDegree(TeacherRequest $teacher_request)
    // {
    //     $employee_id = Input::get('employee_id');

    //     $teacher = Teacher::where('employee_id',$employee_id)->get()->first();

    //      if(is_array($teacher_request->degree_id)){
    //              foreach ($teacher_request->degree_id as $item) {
    //                  $teacher_degree = new TeacherDegree();
    //                  $teacher_degree->degree_id = $item;
    //                  $teacher_degree->teacher_id = $teacher->id;
    //                  $teacher_degree -> save();
    //              }
    //       }
    // }
    
   /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */

    public function getEdit($id) {
        
        $action = 1;
        $teacher = Teacher::find($id); 
        $employee = Employee::find($teacher->employee_id);
        $person = Person::find($employee->person_id);
        $teacher_name = $person->last_name. ', '. $person->first_name.' '. $person->middle_name;
        $default_program = TeacherSubject::join('program', 'teacher_subject.is_default', '=', 'program.id')
                                        ->join('teacher', 'teacher_subject.teacher_id', '=', 'teacher.id')
                                        ->where('teacher_subject.teacher_id', '=', $teacher->id)
                                        ->select(['program.id', 'program.program_name'])
                                        ->get()->last();

        $teacher_id = Teacher::where('teacher.id', '=', $id)->get()->last();
      
                                       
        $teacher_program_list = TeacherSubject::join('program', 'teacher_subject.program_id', '=', 'program.id')
                                        ->where('teacher_subject.teacher_id', '=', $teacher_id -> id)
                                        ->select(['teacher_subject.id','program.program_name'])->get();
                                        
                                        // echo $teacher_program_list;
                                        // exit();
        $program_list = Program::all();
        
        
        $teacher_list = array();

        return view('teacher.edit', compact('action','teacher' ,'employee','person','teacher_program_list','default_program','teacher_name','program_list'));
    }
      
    public function deleteTeacherProgram() {
        $id = \Input::get('id');
        
        $teacher_subject = TeacherSubject::find($id);
        $teacher_subject -> delete();

        return response()->json($teacher_subject);
    }

   /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(TeacherEditRequest $teacher_edit_request, $id) {
      
        $teacher = Teacher::where('teacher.id', '=', $id)->get()->last();
        // $default_program = $teacher_edit_request -> default_program_id;

            // $teacher_subject = TeacherSubject::find($teacher -> id);
            // $teacher_subject-> is_default = $teacher_edit_request -> default_program_id;
            // $teacher_subject -> save();
        $teacher_default = TeacherSubject::join('teacher', 'teacher_subject.teacher_id', '=', 'teacher.id')
                                        ->where('teacher_subject.teacher_id', '=', $teacher -> id)
                                        ->select(['teacher_subject.id', 'teacher_subject.teacher_id'])->get()->last();
        $teacher_default_count = TeacherSubject::join('teacher', 'teacher_subject.teacher_id', '=', 'teacher.id')
                                        ->where('teacher_subject.teacher_id', '=', $teacher -> id)
                                        ->select(['teacher_subject.id'])
                                        ->count();
        // $teacher_default_program = TeacherSubject::find($teacher_default -> id)->teacher_id;


            $teacher_default_program = TeacherSubject::with('teacher')->find($teacher_default -> id)->teacher;
            $teacher_default_program -> is_default = 2;
            // $teacher_default_program -> save();
            
                         
        
        echo $teacher_default_program;
        exit();


        if($teacher_edit_request->program_id != null) {
            foreach($teacher_edit_request->program_id as $program) {
                $teacher_subject = new TeacherSubject();
                $teacher_subject->program_id = $program;
                $teacher_subject->teacher_id = $teacher -> id;
                $teacher_subject -> save();
            }
        }
            
        
        // return redirect('teacher/'.$teacher->id.'/edit');
    }

    public function teacherprogramdataJson() {
        $condition = \Input::all();
        $query = TeacherSubject::select();

        foreach($condition as $column => $value) {
            $query->where('teacher_subject.'.$column, '=', $value);
        }

        $teacher_subject = $query->select(['program_id'])->get();

        return response()->json($teacher_subject);
    }


    public function subjectOfTeacher(){
    
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $subject_offered_id = \Input::get('subject_offered_id');

      $teacher_list = TeacherSubject::join('teacher','teacher_subject.teacher_id','=','teacher.id') 
                                    ->join('employee','teacher.employee_id', '=', 'employee.id')
                                    ->join('person','employee.person_id', '=', 'person.id')
                                    ->where('teacher_subject.classification_id',$classification_id)
                                    ->where('teacher_subject.classification_level_id',$classification_level_id)
                                    ->where('teacher_subject.subject_offered_id',$subject_offered_id)
                                    ->select(['teacher.id','person.first_name','person.last_name'])
                                    ->get();

      return response()->json($teacher_list);

    }

  
    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $action = 1;
        $employee_list = Employee::all();
        $teacher = Teacher::find($id);
        $teacher_classification_list = TeacherClassification::all();
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $program_list = Program::where('program.classification_id','=',5)->get();
        $teacher_list = array();
        return view('teacher/delete',compact('teacher','teacher_list','employee_list','action','classification_level_list','program_list','teacher_classification_list','classification_list'));
    }
 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request)
    {
        $id = \Input::get('id');
        $teacher_degree = TeacherDegree::find($id);
        $teacher_degree-> delete();
        
        return response()->json($teacher_degree);
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $employee_id = \Input::get('employee_id');

        if($employee_id)
            {
                $teacher_list = Teacher::where('teacher.employee_id', $employee_id)
                    ->leftJoin('employee', 'teacher.employee_id', '=', 'employee.id')
                    ->leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('photo', 'person.photo_id', '=', 'photo.id')
                    ->leftJoin('civil_status', 'person.civil_status_id', '=', 'civil_status.id')
                    ->leftJoin('employment_status', 'employee.employment_status_id', '=', 'employment_status.id')
                    ->leftJoin('gen_user', 'gen_user.person_id', '=', 'person.id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('teacher.id','!=',0)
                    ->where('gen_user_role.gen_role_id',1)
                    ->where('person.last_name',"!=", "")
                    ->where('person.is_active',1)
                    // ->where('employee.employee_type_id','=',3)
                    ->select(['photo.img','person.last_name','teacher.id', 'person.middle_name','person.first_name','person.nickname','person.contact_no','person.birthdate','person.address','civil_status.civil_status_name','position.position_name','employment_status.employment_status_name','department.department_name','teacher.employee_id'])
                    ->groupBy('teacher.employee_id');
                    // ->orderBy('person.last_name', 'ASC');
            }
        else
            {
                $teacher_list = Teacher::leftJoin('employee', 'teacher.employee_id', '=', 'employee.id')
                    ->leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('photo', 'person.photo_id', '=', 'photo.id')
                    ->leftJoin('civil_status', 'person.civil_status_id', '=', 'civil_status.id')
                    ->leftJoin('employment_status', 'employee.employment_status_id', '=', 'employment_status.id')
                    ->leftJoin('gen_user', 'gen_user.person_id', '=', 'person.id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('teacher.id','!=',0)
                    ->where('gen_user_role.gen_role_id',1)
                    ->where('person.last_name',"!=", "")
                    ->where('person.is_active',1)
                    // ->where('employee.employee_type_id','=',3)
                    ->select(['photo.img','person.last_name','teacher.id', 'person.middle_name','person.first_name','person.nickname','person.contact_no','person.birthdate','person.address','civil_status.civil_status_name','position.position_name','employment_status.employment_status_name','department.department_name','teacher.employee_id'])
                    ->groupBy('teacher.employee_id');
                    // ->orderBy('person.last_name', 'ASC');
            }
        return Datatables::of( $teacher_list)
            // ->add_column('actions', '<a href="{{{ URL::to(\'teacher/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
            //         <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->addColumn('action','<a href="{{ URL::to(\'employee\') }}{{\'/\'}}{{$employee_id}}" target="_blank"><button type="button" class="btn btn-sm btn-primary">View</button></a>')
            ->editColumn('img','@if($img != "")
                                    <img src="{{asset($img)}}" width="20px;"/>
                            @else
                                    <img src="{{asset(\'assets/site/images/BLANK_IMAGE.jpg\')}}" width="20px;"/>
                            @endif')
            ->add_column('actions', '')
            ->editColumn('last_name','{{ ucwords(strtolower(str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$last_name)).",  ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$first_name))." ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$middle_name)))) }}')
            ->remove_column('middle_name', 'first_name','employee_id')
            ->make(true);
    }

      /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
      public function getTeacherData()
    {
        // $program_id = \Input::get('program_id');
        $classification_level_id = \Input::get('classification_level_id');
        $classification_id = \Input::get('classification_id');
        $subject_id = \Input::get('subject_id');
        $employee_id = \Input::get('employee_id');

        if($classification_id != "" && $classification_id != null && $classification_level_id != "" && $classification_level_id != null) 
        {

          $teacher_list = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                        ->join('person', 'employee.person_id', '=', 'person.id')
                        ->join('teacher_subject', 'teacher_subject.teacher_id', '=', 'teacher.id')
                        ->join('classification', 'teacher_subject.classification_id', '=', 'classification.id')
                        ->join('classification_level','teacher_subject.classification_level_id','=','classification_level.id')
                        ->join('subject_offered', 'teacher_subject.subject_offered_id', '=', 'subject_offered.id')
                        ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                        // ->join('program','teacher_subject.program_id','=','program.id')
                        ->where('teacher_subject.classification_id','=',$classification_id)
                        // ->where('teacher_subject.program_id','=',$program_id)
                        ->where('teacher_subject.classification_level_id','=',$classification_level_id)
                        ->select(array('teacher.id','person.first_name', 'person.middle_name', 'person.last_name', 'classification.classification_name','classification_level.level', 'subject.name'))
                        ->orderBy('teacher_subject.id', 'DESC')
                        ->groupBy('teacher_subject.teacher_id', 'teacher_subject.classification_level_id');
        }
        elseif($classification_id != "" && $classification_id != null)
        {

          $teacher_list = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                        ->join('person', 'employee.person_id', '=', 'person.id')
                        ->join('teacher_subject', 'teacher_subject.teacher_id', '=', 'teacher.id')
                        ->join('classification', 'teacher_subject.classification_id', '=', 'classification.id')
                        ->join('classification_level','teacher_subject.classification_level_id','=','classification_level.id')
                        ->join('subject_offered', 'teacher_subject.subject_offered_id', '=', 'subject_offered.id')
                        ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                        // ->join('program','teacher_subject.program_id','=','program.id')
                        ->where('teacher_subject.classification_id','=',$classification_id)
                        // ->where('teacher_subject.program_id','=',$program_id)
                        ->select(array('teacher.id','person.first_name', 'person.middle_name', 'person.last_name', 'classification.classification_name','classification_level.level', 'subject.name'))
                        ->orderBy('teacher_subject.id', 'DESC')
                        ->groupBy('teacher_subject.teacher_id', 'teacher_subject.classification_level_id');
        }

        elseif($classification_id != "" && $classification_id != null && $classification_level_id != "" && $classification_level_id != null) 
        {
            $teacher_list = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                        ->join('person', 'employee.person_id', '=', 'person.id')
                        ->join('teacher_subject', 'teacher_subject.teacher_id', '=', 'teacher.id')
                        ->join('classification', 'teacher_subject.classification_id', '=', 'classification.id')
                        ->join('classification_level','teacher_subject.classification_level_id','=','classification_level.id')
                        ->join('subject_offered', 'teacher_subject.subject_offered_id', '=', 'subject_offered.id')
                        ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                        // ->join('program','teacher_subject.program_id','=','program.id')
                        ->where('teacher_subject.classification_id','=',$classification_id)
                        ->where('teacher_subject.classification_level_id','=',$classification_level_id)
                        ->select(array('teacher.id','person.first_name', 'person.middle_name', 'person.last_name', 'classification.classification_name','classification_level.level', 'subject.name'))
                        ->orderBy('teacher_subject.id', 'DESC')
                        ->groupBy('teacher_subject.teacher_id', 'teacher_subject.classification_level_id');
        }
    
        else
        {
          $teacher_list = Teacher::join('employee', 'teacher.employee_id', '=', 'employee.id')
                        ->join('person', 'employee.person_id', '=', 'person.id')
                        ->join('teacher_subject', 'teacher_subject.teacher_id', '=', 'teacher.id')
                        ->join('classification', 'teacher_subject.classification_id', '=', 'classification.id')
                        ->join('classification_level','teacher_subject.classification_level_id','=','classification_level.id')
                        ->join('subject_offered', 'teacher_subject.subject_offered_id', '=', 'subject_offered.id')
                        ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                        // ->join('program','teacher_subject.program_id','=','program.id')
                        // ->where('teacher_subject.classification_id','=',$classification_id)
                        ->select(array('teacher.id','person.first_name', 'person.middle_name', 'person.last_name', 'classification.classification_name','classification_level.level', 'subject.name'))
                        ->orderBy('teacher_subject.id', 'DESC')
                        ->groupBy('teacher_subject.teacher_id', 'teacher_subject.classification_level_id');

        }
        return Datatables::of($teacher_list)
            ->editColumn('first_name','{{ ucwords(strtolower(str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$first_name))." ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$middle_name))." ".str_replace("ñ","&#241;",str_replace("Ñ","&#209;",$last_name)))) }}')
            // ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name)) }}')
            ->remove_column('id', 'middle_name', 'last_name')
            ->make();
    }
   
}
