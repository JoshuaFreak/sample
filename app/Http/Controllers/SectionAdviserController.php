<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\EnrollmentSection;
use App\Models\Person;
use App\Models\Section;
use App\Models\SectionAdviser;
use App\Models\SectionAdviserStudent;
use App\Models\SectionMonitor;
use App\Models\Term;
use App\Http\Requests\SectionAdviserRequest;
use App\Http\Requests\SectionAdviserEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SectionAdviserController extends DeansPortalMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // Show the page
        return view('section_adviser.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function dataJson(){
          $condition = \Input::all();

          $query = SectionAdviserStudent::join('student', 'section_adviser_student.student_id', '=', 'student.id')
              ->join('section_adviser', 'section_adviser_student.section_adviser_id', '=' ,'section_adviser.id')
              ->join('person', 'student.person_id', '=' ,'person.id')
              ->select();

          foreach($condition as $column => $value)
          {
            $query->where('section_adviser.'.$column, '=', $value);
          }

         $section_adviser_student = $query->select(['section_adviser_student.id as value', 'student.student_no as student_no','person.last_name as last_name','person.first_name as first_name', 'person.middle_name as middle_name'])->groupBy('person.last_name')->get();

          return response()->json($section_adviser_student);
    }

    public function getCreate() {

        $term_id = \Input::get('term_id');
        $section_id = \Input::get('section_monitor_id');

        $action = 0;
        $section_monitor_list = SectionMonitor::join('section', 'section_monitor.section_id', '=', 'section.id')
                    ->join('classification_level', 'section_monitor.classification_level_id', '=', 'classification_level.id')
                    ->join('term', 'section_monitor.term_id', '=', 'term.id')
                    ->select(['section_monitor.id', 'section_monitor.term_id', 'section_monitor.section_id', 'classification_level.level', 'section.section_name', 'term.term_name'])
                    ->orderBy('section_monitor.classification_level_id', 'ASC')->get();

        $section_term_list = SectionMonitor::join('section', 'section_monitor.section_id', '=', 'section.id')
                    ->join('term', 'section_monitor.term_id', '=', 'term.id')
                    ->select(['section_monitor.id', 'section.section_name','section_monitor.term_id', 'term.term_name'])
                    ->groupBy('section_monitor.term_id')->get();


        $section_term = SectionMonitor::join('term', 'section_monitor.term_id', '=', 'term.id')
                    ->select(['section_monitor.id', 'section_monitor.term_id'])
                    ->get()->last();


        $term_list = Term::select('term.id','term.term_name')->where('is_active','=',1)->get();

        $section_list = Section::select('section.id','section.section_name')->get();

        $classification_level_list = ClassificationLevel::select('classification_level.id','classification_level.level')->get();

        // $enrollment_section_list = EnrollmentSection::join('student', 'enrollment_section.student_id', '=', 'student.id')
        //             ->join('person', 'student.person_id', '=', 'person.id')
        //             ->join('section', 'enrollment_section.section_id', '=', 'section.id')
        //             ->select(['student.id', 'student.student_no','person.last_name', 'person.first_name', 'person.middle_name'])->get();

        $enrollment_section_list = Enrollment::join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                    ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                    ->join('person', 'student.person_id', '=', 'person.id')
                    ->join('section', 'enrollment.section_id', '=', 'section.id')
                    ->join('term', 'enrollment.term_id', '=', 'term.id')
                    ->join('classification_level', 'enrollment.classification_level_id', '=', 'classification_level.id')
                    // ->where('enrollment.term_id', '=', $term_id)
                    // ->where('enrollment.section_id', '=', $section_id)
                    ->select(['student.id', 'student.student_no', 'classification_level.level','person.last_name', 'person.first_name', 'person.middle_name', 'enrollment.term_id', 'enrollment.section_id'])->get();
        // Selected groups
        $section_adviser_student_list = array();
        return view('section_adviser.create', compact('enrollment_section_list', 'term_list','classification_level_list',  'section_list', 'section_monitor_list','section_term','section_term_list', 'section_adviser_student_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(SectionAdviserRequest $section_adviser_request) {

        $section_adviser = new SectionAdviser();
        $section_adviser ->section_id = $section_adviser_request->section_id;
        $section_adviser ->term_id = $section_adviser_request->term_id;
        $section_adviser ->classification_level_id = $section_adviser_request->classification_level_id;
        $section_adviser ->employee_id = $section_adviser_request->employee_id;
        $section_adviser ->created_by_id = Auth::id();
        $section_adviser -> save();

        
        // foreach($section_adviser_request->student as $item)
        // {
        //     $section_adviser_student = new SectionAdviserStudent();


        //     $section_adviser_student->student_id = $item;
            
        //     $section_adviser_student->section_adviser_id = $section_adviser->id;
            
        //     $section_adviser_student->save();


        // }

        // $success = \Lang::get('campus.create_success').' : '.$campus->campus_name ; 
        // return redirect('campus/create')->withSuccess( $success);

        // $query = Section::find($section_advsi->section_id);                
        $success = \Lang::get('section_adviser.create_success').'  '.$section_adviser->employee_id ;
        return redirect('section_adviser/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $action = 1;
        $section_adviser = SectionAdviser::find($id);
        $employee = Employee::find($section_adviser->employee_id);
        $person = Person::find($employee->person_id);
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $section_list = Section::all();
        $term_list = Term::all();
        return view('section_adviser/edit',compact('section_adviser','employee', 'person', 'classification_level_list', 'classification_list', 'section_list', 'term_list','action'));
 

        // $action = 1;
        // // $section_adviser = SectionAdviser::where('section_adviser.id',$id)
        // //                                 ->select(['section_adviser.id','section_adviser.employee_id','section_adviser.section_id','section_adviser.classification_level_id'])
        // //                                 ->get()->last();

        // // $section_monitor_list = SectionMonitor::join('section', 'section_monitor.section_id', '=', 'section.id')
        // //             ->join('classification_level', 'section_monitor.classification_level_id', '=', 'classification_level.id')
        // //             ->join('term', 'section_monitor.term_id', '=', 'term.id')
        // //             ->select(['section_monitor.id', 'classification_level.level', 'section.section_name', 'term.term_name'])
        // //             ->orderBy('section_monitor.classification_level_id', 'ASC')->get();

        // // $section_term_list = SectionMonitor::join('section', 'section_monitor.section_id', '=', 'section.id')
        // //             ->join('term', 'section_monitor.term_id', '=', 'term.id')
        // //             ->select(['section_monitor.id', 'section.section_name', 'term.term_name'])
        // //             ->groupBy('section_monitor.term_id')->get();

        // // $section_id = SectionMonitor::where('section_monitor.id',$section_adviser->section_monitor_id)
        // //                                 ->select(['section_monitor.section_id'])
        // //                                 ->get()->last();

        // $section_adviser_list = SectionAdviser::join('employee', 'section_adviser.employee_id', '=', 'employee.id')
        //             ->join('person', 'employee.person_id', '=', 'person.id')
        //             // ->where('section_adviser.employee_id', '=', $section_adviser -> employee_id)
        //             ->select(['section_adviser.id', 'section_adviser.employee_id' ,'person.last_name', 'person.first_name', 'person.middle_name'])
        //             ->orderBy('section_adviser.employee_id', 'ASC')->get();

        // $section_adviser_student_list = SectionAdviserStudent::join('student', 'section_adviser_student.student_id', '=', 'student.id')
        //             ->join('section_adviser', 'section_adviser_student.section_adviser_id', '=' ,'section_adviser.id')
        //             ->join('person', 'student.person_id', '=' ,'person.id')
        //             ->where('section_adviser_student.section_adviser_id',$id)
        //             // ->where('section_adviser.section_monitor_id',$section_adviser->section_monitor_id)
        //             ->select('section_adviser_student.id','student.id as student_id', 'student.student_no' ,'person.last_name', 'person.first_name', 'person.middle_name')
        //             ->get();

        // $enrollment_section_list = EnrollmentSection::join('student', 'enrollment_section.student_id', '=', 'student.id')
        //             ->join('person', 'student.person_id', '=', 'person.id')
        //             ->join('section', 'enrollment_section.section_id', '=', 'section.id')
        //             ->select(['enrollment_section.id', 'student.id as student_id','student.student_no','person.last_name', 'person.first_name', 'person.middle_name'])->get();

      
        // $section_list = Section::select('section.id','section.section_name')->get();

      
        // $term_list = Term::select('term.id','term.term_name')->where('is_active','=',1)->get();
      
        // $classification_level_list = ClassificationLevel::select('classification_level.id','classification_level.level')->get();
        // // $enrollment_section_list = Enrollment::join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
        // //             ->join('student', 'student_curriculum.student_id', '=', 'student.id')
        // //             ->join('person', 'student.person_id', '=', 'person.id')
        // //             ->join('section', 'enrollment.section_id', '=', 'section.id')
        // //             ->join('classification_level', 'enrollment.classification_level_id', '=', 'classification_level.id')
        // //             ->select(['student.id', 'student.student_no', 'classification_level.level','person.last_name', 'person.first_name', 'person.middle_name'])->get();


        // // $person = Person::join('employee', 'person.id','=','employee.person_id')
        // //                 ->where('employee.id',$section_adviser -> employee_id)
        // //                 ->select('person.first_name','person.last_name','person.middle_name')
        // //                 ->get()->last();


        // $section_adviser = SectionAdviser::find($id);
        // $employee = Employee::find($section_adviser->employee_id);
        // $person = Person::find($employee->person_id);


        // return view('section_adviser/edit',compact('person','section_list','term_list','classification_level_list','section_term_list','section_monitor_list','section_adviser_list','section_adviser_student_list' ,'action', 'section_adviser', 'enrollment_section_list'));

      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(SectionAdviserEditRequest $section_adviser_edit_request, $id) {
      
        $section_adviser = SectionAdviser::find($id);
        $section_adviser ->employee_id = $section_adviser_edit_request->employee_id;
        // $section_adviser ->classification_id = $section_adviser_edit_request->classification_id;
        $section_adviser ->classification_level_id = $section_adviser_edit_request->classification_level_id;
        $section_adviser ->section_id = $section_adviser_edit_request->section_id;
        $section_adviser ->term_id = $section_adviser_edit_request->term_id;
        $section_adviser ->updated_by_id = Auth::id();
        $section_adviser -> save();



        // if($section_adviser_edit_request->student == "" || $section_adviser_edit_request->student == null){

        // }
        // else{
        //   foreach($section_adviser_edit_request->student as $item)
        //     {
        //         $section_adviser_student = new SectionAdviserStudent();


        //         $section_adviser_student->student_id = $item;
                
        //         $section_adviser_student->section_adviser_id = $section_adviser->id;
                
        //         $section_adviser_student->save();


        //     }

        // }
        
        


        return redirect('section_adviser');
        // dd($section_adviser);
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
        $section_adviser = SectionAdviser::find($id);
        $employee = Employee::find($section_adviser->employee_id);
        $person = Person::find($employee->person_id);
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $section_list = Section::all();
        $term_list = Term::all();
        $section_monitor_list = SectionMonitor::join('section', 'section_monitor.section_id', '=', 'section.id')
                    ->join('classification_level', 'section_monitor.classification_level_id', '=', 'classification_level.id')
                    ->select(['section_monitor.id', 'classification_level.level', 'section.section_name'])
                    ->orderBy('section_monitor.classification_level_id', 'ASC')->get();
        $enrollment_section_list = EnrollmentSection::join('student', 'enrollment_section.student_id', '=', 'student.id')
                    ->join('person', 'student.person_id', '=', 'person.id')
                    ->join('section', 'enrollment_section.section_id', '=', 'section.id')
                    ->select(['enrollment_section.id', 'student.id as student_id','student.student_no','person.last_name', 'person.first_name', 'person.middle_name'])->get();
         $section_adviser_student_list = SectionAdviserStudent::join('student', 'section_adviser_student.student_id', '=', 'student.id')
                    ->join('section_adviser', 'section_adviser_student.section_adviser_id', '=' ,'section_adviser.id')
                    ->join('person', 'student.person_id', '=' ,'person.id')
                    ->select('section_adviser_student.id', 'student.id as student_id', 'student.student_no' ,'person.last_name', 'person.first_name', 'person.middle_name')
                    ->get();
        // Show the page
        return view('section_adviser/delete', compact('section_adviser_student_list','enrollment_section_list','section_monitor_list','section_adviser', 'employee', 'person', 'classification_list', 'classification_level_list', 'section_list', 'term_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $section_adviser = SectionAdviser::find($id);
        $section_adviser->delete();
        return redirect('section_adviser');
    }
    public function postDeleteSectionAdviser()
    {
        $id = \Input::get('id');
        // $student = Student::find($id);
        $section_adviser_student = SectionAdviserStudent::find($id);
        $section_adviser_student->delete();
        return redirect('section_adviser');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $section_adviser_list = SectionAdviser::join('section', 'section_adviser.section_id', '=', 'section.id')
        ->join('term', 'section_adviser.term_id', '=', 'term.id')
        ->join('classification_level', 'section_adviser.classification_level_id', '=', 'classification_level.id')
        ->join('employee', 'section_adviser.employee_id', '=', 'employee.id')
        ->join('person', 'employee.person_id', '=', 'person.id')
        ->select(array('section_adviser.id','section_adviser.employee_id','person.first_name','person.middle_name','person.last_name', 'section.section_name', 'classification_level.level', 'term.term_name'))
        ->orderBy('section_adviser.id', 'ASC')
        ->groupBy('section_adviser.employee_id');
    
        return Datatables::of( $section_adviser_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'section_adviser/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'section_adviser/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name)) }}')
            ->editColumn('section_name','{{ ucwords(strtolower($level." - ".$section_name)) }}')
            ->remove_column('id','employee_id','middle_name','last_name','suffix_name', 'level')
            ->make();
    }

}
