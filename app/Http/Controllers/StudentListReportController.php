<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\Classification;
use App\Models\CurriculumSubject;
use App\Models\StudentCurriculum;
use App\Models\Curriculum;
use Datatables;
use Config;    
use Hash;
use Excel;
use Carbon\Carbon;

class StudentListReportController extends RegistrarMainController {

  	public function index(){
  		$action= 0;
        $classification_list = Classification::all();
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->select('curriculum.id', 'curriculum.curriculum_name')->get();
        return view('registrar_report/student_list.index',compact('action','classification_list','curriculum_subject_list'));
  	}


  	public function getDetail(){

        $classification_id = \Input::get('classification_id');
        $curriculum_id = \Input::get('curriculum_id');

	     $logo = str_replace("\\","/",public_path())."/images/logo.png";
	    
	      if($classification_id != "" && $classification_id != null && $curriculum_id != "" && $curriculum_id != null) 
        {
	     $student_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->join('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
		     	->where('student_curriculum.classification_id',$classification_id)
		     	->where('student_curriculum.curriculum_id',$curriculum_id)
		       	->get();
        }
        elseif($classification_id != "" && $classification_id != null) 
        {
	     $student_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->join('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
		     	->where('student_curriculum.classification_id',$classification_id)
		       	->get();
        }
	    else
	    {
	     $student_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->join('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
		       	->get();
		}

	    $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
	    $curriculum = Curriculum::where('id',$curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();
	     
	    return view('registrar_report/student_list/detail',compact('student_list','classification','classification_id','curriculum','curriculum_id'));

  	}


  	public function pdfRegisteredStudentReport(){

        $classification_id = \Input::get('classification_id');
        $curriculum_id = \Input::get('curriculum_id');

	     $logo = str_replace("\\","/",public_path())."/images/logo.png";
	    
	      if($classification_id != "" && $classification_id != null && $curriculum_id != "" && $curriculum_id != null) 
        {
	     $student_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->join('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
		     	->where('student_curriculum.classification_id',$classification_id)
		     	->where('student_curriculum.curriculum_id',$curriculum_id)
		       	->get();
        }
        elseif($classification_id != "" && $classification_id != null) 
        {
	     $student_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->join('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
		     	->where('student_curriculum.classification_id',$classification_id)
		       	->get();
        }
	    else
	    {
	     $student_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->join('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
		       	->get();
		}

	    $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
	    $curriculum = Curriculum::where('id',$curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();
	     
	    $pdf = \PDF::loadView('registrar_report/student_list/pdf_registered_student_report', array('logo'=>$logo,'student_list'=>$student_list,'classification'=>$classification,'classification_id'=>$classification_id,'curriculum'=>$curriculum,'curriculum_id'=>$curriculum_id))->setOrientation('landscape');

	    return $pdf->stream();
	    // return $pdf->download('Export_Entry.pdf');

  	}

}