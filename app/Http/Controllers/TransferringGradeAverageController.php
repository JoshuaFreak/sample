<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
use App\Models\ClassificationLevel;
use App\Models\Generic\GenUser;
use App\Models\Person;
use App\Models\School;
use App\Models\Student;
use App\Models\TransferringGradeAverage;
use App\Http\Requests\TransferringGradeAverageRequest;
use App\Http\Requests\TransferringGradeAverageEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TransferringGradeAverageController extends RegistrarMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // Show the page
        return view('transferring/grade_average.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $classification_level_list = ClassificationLevel::all();
        return view('transferring/grade_average.create', compact('action', 'classification_level_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(TransferringGradeAverageRequest $transferring_grade_average_request) {

        $transferring_grade_average = new TransferringGradeAverage();
        $transferring_grade_average -> school_id = $transferring_grade_average_request->school_id;
        $transferring_grade_average -> student_id = $transferring_grade_average_request->student_id;
        $transferring_grade_average -> admission_level = $transferring_grade_average_request->admission_level;
        $transferring_grade_average -> date_released = $transferring_grade_average_request->date_released;
        $transferring_grade_average -> created_by_id = Auth::id();
        $transferring_grade_average -> save();

        $student = Student::find($transferring_grade_average->student_id);
        $student -> status_id = 2;
        $student ->save();

        $gen_user = GenUser::find($student->gen_user_id);
        $gen_user -> confirmed = 0;
        $gen_user ->save();



        $success = \Lang::get('transferring_grade_average.create_success').' : '.$transferring_grade_average->average ; 
        return redirect('transferring/grade_average/create')->withSuccess( $success);
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
        $transferring_grade_average = TransferringGradeAverage::find($id);
        $school = School::find($transferring_grade_average->school_id);
        $student = Student::find($transferring_grade_average->student_id);
        $person = Person::find($student->person_id);
       //var_dump($its_customs_office);
        return view('transferring/grade_average/edit',compact('transferring_grade_average', 'classification_level_list','request_list','school', 'person', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(TransferringGradeAverageEditRequest $transferring_grade_average_edit_request, $id) {
      
        $transferring_grade_average = TransferringGradeAverage::find($id);
        $transferring_grade_average -> school_id = $transferring_grade_average_edit_request->school_id;
        $transferring_grade_average -> student_id = $transferring_grade_average_edit_request->student_id;
        $transferring_grade_average -> admission_level = $transferring_grade_average_edit_request->admission_level;
        $transferring_grade_average -> date_released = $transferring_grade_average_edit_request->date_released;
        $transferring_grade_average -> updated_by_id = Auth::id();
        $transferring_grade_average -> save();

        return redirect('transferring/grade_average');
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
        $transferring_grade_average = TransferringGradeAverage::find($id);
        $school = School::find($transferring_grade_average->school_id);
        $student = Student::find($transferring_grade_average->student_id);
        $person = Person::find($student->person_id);
        // Show the page
        return view('transferring/grade_average/delete',compact('transferring_grade_average', 'classification_level_list','request_list','school', 'person', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {

        $transferring_grade_average = TransferringGradeAverage::find($id);

        $student = Student::find($transferring_grade_average->student_id);
        $student -> status_id = 1;
        $student ->save();

        $gen_user = GenUser::find($student->gen_user_id);
        $gen_user -> confirmed = 1;
        $gen_user ->save();

        $transferring_grade_average->delete();
        return redirect('transferring/grade_average');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

        $transferring_grade_average_list = TransferringGradeAverage::join('classification_level', 'transferring_grade_average.classification_level_id', '=', 'classification_level.id')
            ->leftJoin('school', 'transferring_grade_average.school_id', '=', 'school.id')
            ->join('student', 'transferring_grade_average.student_id', '=', 'student.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->leftJoin('suffix', 'person.suffix_id', '=', 'suffix.id')
            ->join('classification_level as admission_level', 'transferring_grade_average.classification_level_id', '=', 'admission_level.id')
            ->select('transferring_grade_average.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'suffix.suffix_name', 'transferring_grade_average.admission_level','school.school_name')
            ->orderBy('transferring_grade_average.id', 'ASC');
    
        return Datatables::of( $transferring_grade_average_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'transferring/grade_average/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'transferring/grade_average/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
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
        return view('transferring/master_list.index');
    }


    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function masterListdata()
    {
        $transferring_grade_average_list = TransferringGradeAverage::join('classification_level', 'transferring_grade_average.classification_level_id', '=', 'classification_level.id')
            ->join('leftJoin', 'transferring_grade_average.school_id', '=', 'school.id')
            ->join('student', 'transferring_grade_average.student_id', '=', 'student.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->join('leftJoin', 'person.suffix_id', '=', 'suffix.id')
            ->join('classification_level as admission_level', 'transferring_grade_average.classification_level_id', '=', 'admission_level.id')
            ->select(array('transferring_grade_average.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'suffix.suffix_name', 'transferring_grade_average.admission_level', 'school.school_name','transferring_grade_average.date_released'))
            ->orderBy('transferring_grade_average.id', 'ASC');
    
        return Datatables::of( $transferring_grade_average_list)
            ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name." ".$suffix_name)) }}')
            ->remove_column('id','middle_name','last_name','suffix_name')
            ->make();
    }

}
