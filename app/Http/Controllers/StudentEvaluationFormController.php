<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\CurriculumSubject;
use App\Models\StudentCurriculum;
use App\Models\Curriculum;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Grade;
use Datatables;
use Config;    
use Hash;
use Excel;
use Carbon\Carbon;

class StudentEvaluationFormController extends RegistrarMainController {

  	public function index(){
  		$action= 0;
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->select('curriculum.id', 'curriculum.curriculum_name')->get();
        return view('registrar_report/evaluation_form.index',compact('action','curriculum_subject_list'));
  	}

  	public function getDetail(){

        $classification_id = \Input::get('classification_id');
        $curriculum_id = \Input::get('curriculum_id');
        $student_id = \Input::get('student_id');

      	$student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name')->get()->last();


    	   if($classification_id == 5) 
    	   {
    	     $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                    ->join('subject','subject_offered.subject_id','=','subject.id')
                    ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                    ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
          		     	->select('curriculum_subject.id', 'curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name')
          		     	->where('curriculum_subject.classification_id',$classification_id)
          		     	->where('curriculum_subject.curriculum_id',$curriculum_id)
          		     	->groupBy('semester_level.semester_name')
                    ->orderBy('curriculum_subject.id', 'ASC')
    		       	    ->get();
    		}
    		else
    		{
    	    $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                    ->join('subject','subject_offered.subject_id','=','subject.id')
                    ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                    ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
          		     	->select('curriculum_subject.id', 'curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name')
          		     	->where('curriculum_subject.classification_id',$classification_id)
          		     	->where('curriculum_subject.curriculum_id',$curriculum_id)
          		     	->groupBy('classification_level.level')
                    ->orderBy('curriculum_subject.id', 'ASC')
    		       	    ->get();
    		}

        $subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                  ->join('subject','subject_offered.subject_id','=','subject.id')
                  ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                  ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
                  ->where('curriculum_subject.classification_id',$classification_id)
                  ->where('curriculum_subject.curriculum_id',$curriculum_id)
                  ->select('curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'curriculum_subject.subject_offered_id', 'subject.code', 'subject.name', 'subject.credit_unit')
                  ->orderBy('curriculum_subject.id', 'ASC')
                  ->get(); 

  	    $grade_list = Grade::join('term', 'grade.term_id', '=', 'term.id')
                  ->join('class', 'grade.class_id', '=', 'class.id')
                  ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                  ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
        		     	->select('grade.id','grade.remarks', 'term.term_name', 'subject.credit_unit')
                  ->where('grade.student_id',$student_id)
  		       	    ->get();


  	    $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
  	    $curriculum = Curriculum::where('id',$curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();

	      return view('registrar_report/evaluation_form/detail', 
	        compact(
	            'curriculum_subject_list',
	            'subject_list',
	            'classification_id',
	            'classification',
	            'curriculum',
              'curriculum_id',
	            'student',
	            'grade_list',
	            'classification_level',
	            'semester_level'
	        ));
  	}

  	public function pdfEvaluationForm(){

        $classification_id = \Input::get('classification_id');
        $curriculum_id = \Input::get('curriculum_id');
        $student_id = \Input::get('student_id');

	    $logo = str_replace("\\","/",public_path())."/images/logo.png";

      	$student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select('student.id','student.student_no','person.last_name','person.first_name','person.middle_name')->get()->last();


	   if($classification_id == 5) 
	   {
	     $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
      		     	->select('curriculum_subject.id', 'curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name')
      		     	->where('curriculum_subject.classification_id',$classification_id)
      		     	->where('curriculum_subject.curriculum_id',$curriculum_id)
      		     	->groupBy('semester_level.semester_name')
                ->orderBy('curriculum_subject.id', 'ASC')
		          	->get();
		}
		else
		{
	     $curriculum_subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
      		     	->select('curriculum_subject.id', 'curriculum_subject.semester_level_id','curriculum_subject.classification_level_id','classification_level.level', 'semester_level.semester_name')
      		     	->where('curriculum_subject.classification_id',$classification_id)
      		     	->where('curriculum_subject.curriculum_id',$curriculum_id)
      		     	->groupBy('classification_level.level')
                ->orderBy('curriculum_subject.id', 'ASC')
		       	    ->get();
		}
		
        $subject_list = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                  ->join('subject','subject_offered.subject_id','=','subject.id')
                  ->join('classification_level','curriculum_subject.classification_level_id','=','classification_level.id')
                  ->join('semester_level','curriculum_subject.semester_level_id','=','semester_level.id')
                  ->where('curriculum_subject.classification_id',$classification_id)
                  ->where('curriculum_subject.curriculum_id',$curriculum_id)
                  ->select('curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'curriculum_subject.subject_offered_id', 'subject.code', 'subject.name', 'subject.credit_unit')
                  ->orderBy('curriculum_subject.id', 'ASC')
                  ->get(); 

        $grade_list = Grade::join('term', 'grade.term_id', '=', 'term.id')
                  ->join('class', 'grade.class_id', '=', 'class.id')
                  ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                  ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                  ->select('grade.id','grade.remarks', 'term.term_name', 'subject.credit_unit')
                  ->where('grade.student_id',$student_id)
                  ->get();


	    $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
	    $curriculum = Curriculum::where('id',$curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();
	     
	    $pdf = \PDF::loadView('registrar_report/evaluation_form/pdf_evaluation_form', array('logo'=>$logo,'curriculum_subject_list'=>$curriculum_subject_list,'classification'=>$classification,'classification_id'=>$classification_id,'curriculum'=>$curriculum,'curriculum_id'=>$curriculum_id,'subject_list'=>$subject_list,'student'=>$student,'grade_list'=>$grade_list))->setOrientation('portrait');

	    return $pdf->stream();
	    // return $pdf->download('Export_Entry.pdf');

  	}

}