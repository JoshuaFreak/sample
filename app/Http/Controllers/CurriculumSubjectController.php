<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Program;
use App\Models\SemesterLevel;
use App\Models\Subject;
use App\Models\SubjectOffered;
use App\Models\Term;
use App\Http\Requests\CurriculumSubjectRequest;
use App\Http\Requests\CurriculumSubjectEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class CurriculumSubjectController extends RegistrarMainController {

    public function index()
    {
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        return view('curriculum_subject.index', compact('classification_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $curriculum_list = Curriculum::all();
        $semester_level_list = SemesterLevel::all();
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $curriculum_subject = CurriculumSubject::groupBy('subject_offered_id')->lists('subject_offered_id');

        $subject_offered_list = SubjectOffered::join('subject', 'subject_offered.subject_id', '=', 'subject.id')
            ->select(['subject_offered.id','subject_offered.subject_id','subject.code', 'subject.name'])
            ->get();
        $curriculum_subject_list = array();
        $program_list = Program::where('program.classification_id','=',5)->get();
        $term_list = Term::where('term.classification_id','=',5)->get();
        return view('curriculum_subject.create', compact('curriculum_list','semester_level_list','classification_level_list','classification_list','subject_offered_list','curriculum_subject_list', 'program_list','term_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(CurriculumSubjectRequest $curriculum_subject_request) {


        foreach($curriculum_subject_request->subject as $item)
        {
            $curriculum_subject = new CurriculumSubject();


            $curriculum_subject->subject_offered_id = $item;
            $curriculum_subject ->curriculum_id = $curriculum_subject_request->curriculum_id;
            $curriculum_subject ->semester_level_id = $curriculum_subject_request->semester_level_id;
            $curriculum_subject ->classification_level_id = $curriculum_subject_request->classification_level_id;
            $curriculum_subject ->classification_id = $curriculum_subject_request->classification_id;
            $curriculum_subject ->created_by_id = Auth::id();
            $curriculum_subject -> save();
     
        }

        $create_success = true;

        $query = Curriculum::find($curriculum_subject->curriculum_id);

        $success = \Lang::get('curriculum_subject.create_success').'  '.$query-> curriculum_name;
        return redirect('curriculum_subject/create')->withSuccess( $success)->withInput();
    }

   /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

         $action = 1;
        $curriculum_subject = CurriculumSubject::find($id);
        $curriculum = Curriculum::find($id);
        // $curriculum_subject_list = CurriculumSubject::all();
        // $semester_level_list = SemesterLevel::all();
        // $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $curriculum_list = Curriculum::all();
        $subject_offered_list = SubjectOffered::join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                        ->join('classification', 'subject_offered.classification_id', '=', 'classification.id')
                        ->select(array('subject_offered.id','subject.name', 'classification.classification_name'))
                        ->get();
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                        ->join('subject_offered', 'curriculum_subject.subject_offered_id', '=', 'subject_offered.id')
                        ->join('classification', 'curriculum_subject.classification_id', '=', 'classification.id')
                        ->join('classification_level', 'curriculum_subject.classification_level_id', '=', 'classification_level.id')
                        ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                        ->where('curriculum_subject.curriculum_id', $id)
                        ->select(array('curriculum_subject.id as curriculum_subject_id', 'curriculum.id' ,'curriculum.curriculum_name', 'classification.classification_name', 'classification_level.level','subject.name'))
                        ->get()->last();

        $curriculum_subject_list = array();
   
       //var_dump($its_customs_office);
        return view('curriculum_subject/edit',compact('curriculum_subject', 'curriculum_list', 'curriculum', 'semester_level_list', 
                'classification_level_list', 'classification_list','subject_offered_list','curriculum_subject_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(CurriculumSubjectEditRequest $curriculum_subject_edit_request, $id) {
      
        $curriculum_subject = CurriculumSubject::find($id);

        $curriculum_subject -> updated_by_id = Auth::id();
        $curriculum_subject -> save();

        return redirect('curriculum_subject');
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

        $curriculum = Curriculum::find($id); 
        $curriculum_subject = CurriculumSubject::find($id); 
        $curriculum_list = Curriculum::all();
        $semester_level_list = SemesterLevel::all();
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $program_list = Program::all();
        $subject_offered_list = SubjectOffered::join('subject', 'subject_offered.subject_id', '=', 'subject.id')
            ->select(array('subject_offered.id','subject.name'))
             ->get();
        $curriculum_subject_list = CurriculumSubject::lists('subject_offered_id');  
       //var_dump($its_customs_office);
        return view('curriculum_subject/delete',compact('curriculum','curriculum_subject', 'curriculum_list', 'semester_level_list', 
                'classification_level_list', 'classification_list','program_list','subject_offered_list','curriculum_subject_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $curriculum_subject = CurriculumSubject::find($id);
        $curriculum_subject->delete();
        return redirect('curriculum_subject');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

        $classification_id = \Input::get('classification_id');
        if($classification_id != "" && $classification_id != null) 
        {

        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->join('subject_offered', 'curriculum_subject.subject_offered_id', '=', 'subject_offered.id')
                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                ->join('classification_level', 'curriculum_subject.classification_level_id', '=', 'classification_level.id')
                ->join('semester_level', 'curriculum_subject.semester_level_id', '=', 'semester_level.id')
                ->where('curriculum.classification_id','=',$classification_id)
                ->select(array('curriculum_subject.id as curriculum_subject_id' ,'curriculum.id', 'curriculum.curriculum_name', 'semester_level.semester_name', 'classification_level.level', 'subject.name'))
                ->groupBy('curriculum_name')
                ->orderBy('curriculum.curriculum_name', 'DESC');

        }
    
        else
        {
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->join('subject_offered', 'curriculum_subject.subject_offered_id', '=', 'subject_offered.id')
                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                ->join('classification_level', 'curriculum_subject.classification_level_id', '=', 'classification_level.id')
                ->join('semester_level', 'curriculum_subject.semester_level_id', '=', 'semester_level.id')
                // ->where('curriculum.classification_id','=',$classification_id)
                ->select(array('curriculum_subject.id as curriculum_subject_id' ,'curriculum.id', 'curriculum.curriculum_name',  'semester_level.semester_name', 'classification_level.level', 'subject.name'))
                ->groupBy('curriculum_name')
                ->orderBy('curriculum.curriculum_name', 'DESC');
        }
        return Datatables::of( $curriculum_subject_list)
                ->add_column('actions', '<a href="{{{ URL::to(\'curriculum_subject/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                        <input type="hidden" name="row" value="{{$id}}" id="row">')
                ->make(true);
    }

}
