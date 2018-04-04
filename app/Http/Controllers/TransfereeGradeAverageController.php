<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\ClassificationLevel;
use App\Models\Person;
use App\Models\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\TransfereeGradeAverage;
use App\Http\Requests\TransfereeGradeAverageRequest;
use App\Http\Requests\TransfereeGradeAverageEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TransfereeGradeAverageController extends RegistrarMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // Show the page
        return view('transferee/grade_average.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $classification_level_list = ClassificationLevel::all();
        $request_list = Request::orderBy('request.id','ASC')->get();
        return view('transferee/grade_average.create', compact('action', 'classification_level_list', 'request_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(TransfereeGradeAverageRequest $transferee_grade_average_request) {

        $transferee_grade_average = new TransfereeGradeAverage();
        $transferee_grade_average -> average = $transferee_grade_average_request->average;
        $transferee_grade_average -> school_id = $transferee_grade_average_request->school_id;
        $transferee_grade_average -> classification_level_id = $transferee_grade_average_request->classification_level_id;
        $transferee_grade_average -> student_id = $transferee_grade_average_request->student_id;
        $transferee_grade_average -> school_year = $transferee_grade_average_request->school_year;
        $transferee_grade_average -> admission_level = $transferee_grade_average_request->admission_level;
        $transferee_grade_average -> remarks = $transferee_grade_average_request->remarks;
        $transferee_grade_average -> date_request = $transferee_grade_average_request->date_request;
        $transferee_grade_average -> request_id = $transferee_grade_average_request->request_id;
        $transferee_grade_average -> created_by_id = Auth::id();
        $transferee_grade_average -> save();

        $success = \Lang::get('transferee_grade_average.create_success').' : '.$transferee_grade_average->average ; 
        return redirect('transferee/grade_average/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $action = 1;
        $classification_level_list = ClassificationLevel::all();
        $request_list = Request::all();
        $transferee_grade_average = TransfereeGradeAverage::find($id);
        $school = School::find($transferee_grade_average->school_id);
        $student = Student::find($transferee_grade_average->student_id);
        $person = Person::find($student->person_id);
       //var_dump($its_customs_office);
        return view('transferee/grade_average/edit',compact('transferee_grade_average', 'classification_level_list','request_list','school', 'person', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(TransfereeGradeAverageEditRequest $transferee_grade_average_edit_request, $id) {
      
        $transferee_grade_average = TransfereeGradeAverage::find($id);
        $transferee_grade_average -> average = $transferee_grade_average_edit_request->average;
        $transferee_grade_average -> school_id = $transferee_grade_average_edit_request->school_id;
        $transferee_grade_average -> classification_level_id = $transferee_grade_average_edit_request->classification_level_id;
        // $transferee_grade_average -> student_id = $transferee_grade_average_edit_request->student_id;
        $transferee_grade_average -> admission_level = $transferee_grade_average_edit_request->admission_level;
        $transferee_grade_average -> school_year = $transferee_grade_average_edit_request->school_year;
        $transferee_grade_average -> remarks = $transferee_grade_average_edit_request->remarks;
        $transferee_grade_average -> date_request = $transferee_grade_average_edit_request->date_request;
        $transferee_grade_average -> request_id = $transferee_grade_average_edit_request->request_id;
        $transferee_grade_average -> updated_by_id = Auth::id();
        $transferee_grade_average -> save();

        return redirect('transferee/grade_average');
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
        $classification_level_list = ClassificationLevel::all();
        $request_list = Request::all();
        $transferee_grade_average = TransfereeGradeAverage::find($id); 
        $school = School::find($transferee_grade_average->school_id);
        $student = Student::find($transferee_grade_average->student_id);
        $person = Person::find($student->person_id);       

        // Show the page
        return view('transferee/grade_average/delete', compact('transferee_grade_average', 'classification_level_list', 'request_list','action', 'school','student','person'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $transferee_grade_average = TransfereeGradeAverage::find($id);
        $transferee_grade_average->delete();
        return redirect('transferee/grade_average');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $transferee_grade_average_list = TransfereeGradeAverage::join('classification_level', 'transferee_grade_average.classification_level_id', '=', 'classification_level.id')
            ->leftJoin('school', 'transferee_grade_average.school_id', '=', 'school.id')
            ->join('student', 'transferee_grade_average.student_id', '=', 'student.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->leftJoin('suffix', 'person.suffix_id', '=', 'suffix.id')
            ->join('classification_level as admission_level', 'transferee_grade_average.classification_level_id', '=', 'admission_level.id')
            ->select(array('transferee_grade_average.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'suffix.suffix_name', 'classification_level.level','transferee_grade_average.average' ,'school.school_name', 'transferee_grade_average.school_year', 'transferee_grade_average.admission_level', 'transferee_grade_average.remarks'))
            ->orderBy('transferee_grade_average.id', 'ASC');
    
        return Datatables::of( $transferee_grade_average_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'transferee/grade_average/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'transferee/grade_average/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name." ".$suffix_name)) }}')
            ->remove_column('id','middle_name','last_name','suffix_name')
            ->make();
    }
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function masterListIndex()
    {
        // Show the page
        return view('transferee/master_list.index');
    }


    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function masterListdata()
    {
        $transferee_grade_average_list = TransfereeGradeAverage::join('classification_level', 'transferee_grade_average.classification_level_id', '=', 'classification_level.id')
            ->leftJoin('school', 'transferee_grade_average.school_id', '=', 'school.id')
            ->join('student', 'transferee_grade_average.student_id', '=', 'student.id')
            ->leftJoin('request', 'transferee_grade_average.request_id', '=', 'request.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->leftJoin('suffix', 'person.suffix_id', '=', 'suffix.id')
            ->join('classification_level as admission_level', 'transferee_grade_average.classification_level_id', '=', 'admission_level.id')
            ->select(array('transferee_grade_average.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'suffix.suffix_name', 'transferee_grade_average.admission_level', 'transferee_grade_average.school_year',  'school.school_name', 'transferee_grade_average.date_request',  'request.name'))
            ->orderBy('transferee_grade_average.id', 'ASC');
    
        return Datatables::of( $transferee_grade_average_list)
            ->add_column('date_requested', '')
            ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name." ".$suffix_name)) }}')
            ->remove_column('id','middle_name','last_name','suffix_name')
            ->make();
    }

}
