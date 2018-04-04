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

class StudentProspectusController extends RegistrarMainController {

  	public function index(){
  		$action= 0;
        $classification_list = Classification::all();
        $curriculum_subject_list = CurriculumSubject::join('curriculum', 'curriculum_subject.curriculum_id', '=', 'curriculum.id')
                ->select('curriculum.id', 'curriculum.curriculum_name')->get();
        return view('registrar_report/prospectus.index',compact('action','classification_list','curriculum_subject_list'));
  	}


  	public function getDetail(){

        $classification_id = \Input::get('classification_id');
        $curriculum_id = \Input::get('curriculum_id');

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
		     	->select('curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'subject.code', 'subject.name', 'subject.credit_unit')
		     	->where('curriculum_subject.classification_id',$classification_id)
		     	->where('curriculum_subject.curriculum_id',$curriculum_id)
                ->orderBy('curriculum_subject.id', 'ASC')
		       	->get();

	    $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
	    $curriculum = Curriculum::where('id',$curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();

	      return view('registrar_report/prospectus/detail', 
	        compact(
	            'curriculum_subject_list',
	            'subject_list',
	            'classification_level',
	            'classification_id',
	            'classification',
	            'curriculum',
	            'curriculum_id',
	            'semester_level'
	        )
	      );      	

  	}

  	public function pdfProspectus(){

        $classification_id = \Input::get('classification_id');
        $curriculum_id = \Input::get('curriculum_id');

	    $logo = str_replace("\\","/",public_path())."/images/logo.png";
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
		     	->select('curriculum_subject.id','curriculum_subject.semester_level_id','curriculum_subject.classification_level_id', 'subject.code', 'subject.name', 'subject.credit_unit')
		     	->where('curriculum_subject.classification_id',$classification_id)
		     	->where('curriculum_subject.curriculum_id',$curriculum_id)
                ->orderBy('curriculum_subject.id', 'ASC')
		       	->get();

	    $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
	    $curriculum = Curriculum::where('id',$curriculum_id)->select('curriculum.id','curriculum.curriculum_name')->get()->last();
	     
	    $pdf = \PDF::loadView('registrar_report/prospectus/pdf_prospectus', array('logo'=>$logo,'curriculum_subject_list'=>$curriculum_subject_list,'classification'=>$classification,'classification_id'=>$classification_id,'curriculum'=>$curriculum,'curriculum_id'=>$curriculum_id,'subject_list'=>$subject_list))->setOrientation('portrait');

	    return $pdf->stream();
	    // return $pdf->download('Export_Entry.pdf');

  	}

}