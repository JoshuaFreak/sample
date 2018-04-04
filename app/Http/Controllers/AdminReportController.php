<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AdminMainController;
use App\Models\Generic\GenRole;
use App\Models\Generic\GenUser;
use App\Models\Enrollment;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Term;
use Datatables;
use Config;    
use Hash;
use Excel;
use Carbon\Carbon;

class AdminReportController extends AdminMainController {

  	public function index(){
  		$action= 0;
        $gen_role_list = GenRole::all();
        return view('admin_report/user_list/index',compact('action','gen_role_list'));
	}

  	public function getDetail(){

        $gen_role_id = \Input::get('gen_role_id');
	    
	     	$gen_user_list = GenUser::join('person','gen_user.person_id','=','person.id')
	     		->join('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
		     	->where('gen_user.username','!=', 'Master Administrator')
		     	->where('gen_user.username','!=', 'MZED Administrator')
		     	->where('gen_user_role.gen_role_id',$gen_role_id)
		     	->select('gen_user.id','person.last_name','person.first_name','person.middle_name','gen_user.username')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();


	    $gen_role = GenRole::where('id',$gen_role_id)->select('gen_role.id','gen_role.name')->get()->last();

	     
	    return view('admin_report/user_list/detail',compact('gen_user_list','gen_role','gen_role_id'));

  	}


  	public function pdfListOfUsers(){

	    $gen_role_id = \Input::get('gen_role_id');
	    $logo = str_replace("\\","/",public_path())."/images/logo.png";
	    
	    if($gen_role_id != "" && $gen_role_id != null) 
        {
	     	$gen_user_list = GenUser::join('person','gen_user.person_id','=','person.id')
	     		->join('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
		     	->where('gen_user.username','!=', 'Master Administrator')
		     	->where('gen_user.username','!=', 'MZED Administrator')
		     	->where('gen_user_role.gen_role_id',$gen_role_id)
		     	->select('gen_user.id','person.last_name','person.first_name','person.middle_name','gen_user.username')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();

        }
	    else
	    {
	     	$gen_user_list = GenUser::join('person','gen_user.person_id','=','person.id')
	     		->join('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
		     	->where('gen_user.username','!=', 'Master Administrator')
		     	->where('gen_user.username','!=', 'MZED Administrator')
		     	->where('gen_user_role.gen_role_id',$gen_role_id)
		     	->select('gen_user.id','person.last_name','person.first_name','person.middle_name','gen_user.username')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();

		}

	    $gen_role = GenRole::where('id',$gen_role_id)->select('gen_role.id','gen_role.name')->get()->last();
	     
	    $pdf = \PDF::loadView('admin_report/user_list/pdf_list_of_users', array('logo'=>$logo,'gen_user_list'=>$gen_user_list,'gen_role'=>$gen_role,'gen_role_id'=>$gen_role_id));

	    return $pdf->stream();
	    // return $pdf->download('Export_Entry.pdf');

  	}


  	public function studentsIndex(){
  		$action= 0;
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $term_list = Term::all();
        return view('admin_report/students_master_list.index',compact('action','classification_list','classification_level_list','term_list'));
  	}


  	public function getstudentsDetail(){

        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');
	    

	    if($classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null) 
        {
	     	$enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
	     		->join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->where('enrollment.classification_level_id',$classification_level_id)
		     	->where('enrollment.term_id',$term_id)
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();
        }
	    elseif ($classification_level_id != "" && $classification_level_id != null) {
	     	$enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
	     		->join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->where('enrollment.classification_level_id',$classification_level_id)
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();
	    }
	    else
	    {
	     	$enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
	     		->join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();
		}
	    $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
	    $classification_level = ClassificationLevel::where('id',$classification_level_id)->select('classification_level.id','classification_level.level')->get()->last();
	    $term = Term::where('id',$term_id)->select('term.id','term.term_name')->get()->last();
	     
	    return view('admin_report/students_master_list/detail',compact('enrollment_list','classification','classification_level','term','classification_id','classification_level_id','term_id'));

  	}


  	public function pdfStudentMasterList(){

	    $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');

	    $logo = str_replace("\\","/",public_path())."/images/logo.png";
	    
	    if($classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null) 
        {
	     	$enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
	     		->join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->where('enrollment.classification_level_id',$classification_level_id)
		     	->where('enrollment.term_id',$term_id)
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();
        }
	    elseif ($classification_level_id != "" && $classification_level_id != null) {
	     	$enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
	     		->join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->where('enrollment.classification_level_id',$classification_level_id)
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();
	    }
	    else
	    {
	     	$enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
	     		->join('student','student_curriculum.student_id','=','student.id')
		     	->join('person','student.person_id','=','person.id')
		     	->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
		     	->select('student_curriculum.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number')
		       	->orderBy('person.last_name', 'ASC')
		       	->get();
		}

	    // $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
	    $classification_level = ClassificationLevel::where('id',$classification_level_id)->select('classification_level.id','classification_level.level')->get()->last();
	    $term = Term::where('id',$term_id)->select('term.id','term.term_name')->get()->last();
	     
	    $pdf = \PDF::loadView('admin_report/students_master_list/pdf_student_list', array('logo'=>$logo,'enrollment_list'=>$enrollment_list,'classification_level'=>$classification_level,'term'=>$term,'classification_level_id'=>$classification_level_id,'term_id'=>$term_id));

	    return $pdf->stream();
	    // return $pdf->download('Export_Entry.pdf');

  	}

}