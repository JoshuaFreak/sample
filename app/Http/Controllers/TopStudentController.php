<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ClassificationLevel;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\GradingPeriod;
use App\Models\Person;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectOffered;
use App\Models\TEClass;
use App\Http\Requests\CampusRequest;
use App\Http\Requests\CampusEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TopStudentController extends TeachersPortalMainController {
   
    public function index()
    {
    	$grading_period_list = GradingPeriod::all();
    	$classification_level_list = ClassificationLevel::all();

    	// $class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
     //    						->join('student', 'grade.student_id', '=', 'student.id')
     //    						->join('person', 'student.person_id', '=', 'person.id')
     //    						->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
     //    						->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
     //    						->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
     //    						->join('section', 'class.section_id', '=', 'section.id')
     //    						->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'grade.computed_grade'])
     //    						->orderBy('grade.id', 'ASC');

        						// return response()->json($class_top_student);

        return view('top_student.index', compact('grading_period_list', 'classification_level_list'));
    }

    public function generateIndex()
    {
        $grading_period_list = GradingPeriod::all();
        $classification_level_list = ClassificationLevel::all();

        return view('top_student.generate_index', compact('grading_period_list', 'classification_level_list'));
    }

    public function dataJson(){
        $condition = \Input::all();
        $query = Section::select();
        foreach($condition as $column => $value)
        {
            $query->where($column, '=', $value);
        }
        $classification_level = $query->select([ 'id as value','section_name as text'])->get();

        return response()->json($classification_level);
    }

    public function subjectDataJson(){
        $condition = \Input::all();
        $query = TEClass::join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                        ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')->select();
        foreach($condition as $column => $value)
        {
            $query->where($column, '=', $value);
        }
        $section = $query->select([ 'subject.id as value','subject.name as text'])->get();

        return response()->json($section);
    }

    public function pdfTopStudents()
    {
        $grading_period_id = \Input::get('grading_period_id');
        $classification_level_id = \Input::get('classification_level_id');
        $section_id = \Input::get('section_id');
        $subject_id = \Input::get('subject_id');

        $class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
                                ->join('student', 'grade.student_id', '=', 'student.id')
                                ->join('person', 'student.person_id', '=', 'person.id')
                                ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                                ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
                                ->join('classification', 'classification_level.classification_id', '=', 'classification.id')
                                ->join('section', 'class.section_id', '=', 'section.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
                                ->where('grade.grading_period_id', '=', $grading_period_id)
                                ->where('class.classification_level_id', '=', $classification_level_id)
                                ->where('class.section_id', '=', $section_id)
                                ->where('subject_offered.subject_id', '=', $subject_id)
                                ->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name','grade.computed_grade'])
                                ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC')->get();

                                // print_r($class_top_student);
                                // exit();

        $pdf = \PDF::loadView('top_student.pdf_top_student')->setOrientation('portrait');

        return $pdf->stream();
    }

    public function data()
    {
      
       	$grading_period_id = \Input::get('grading_period_id');
        $classification_level_id = \Input::get('classification_level_id');
        $section_id = \Input::get('section_id');
       	$subject_id = \Input::get('subject_id');



        if($grading_period_id != null && $classification_level_id != null && $section_id != null && $subject_id != null)
        {
            $class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
                                ->join('student', 'grade.student_id', '=', 'student.id')
                                ->join('person', 'student.person_id', '=', 'person.id')
                                ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                                ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
                                ->join('classification', 'classification_level.classification_id', '=', 'classification.id')
                                ->join('section', 'class.section_id', '=', 'section.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
                                ->where('grade.grading_period_id', '=', $grading_period_id)
                                ->where('class.classification_level_id', '=', $classification_level_id)
                                ->where('class.section_id', '=', $section_id)
                                ->where('subject_offered.subject_id', '=', $subject_id)
                                ->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name','grade.computed_grade'])
                                // ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC');
        }
        elseif($grading_period_id != null && $classification_level_id != null && $section_id != null && $subject_id == null)
        {
            $class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
                                ->join('student', 'grade.student_id', '=', 'student.id')
                                ->join('person', 'student.person_id', '=', 'person.id')
                                ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                                ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
                                ->join('classification', 'classification_level.classification_id', '=', 'classification.id')
                                ->join('section', 'class.section_id', '=', 'section.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
                                ->where('grade.grading_period_id', '=', $grading_period_id)
                                ->where('class.classification_level_id', '=', $classification_level_id)
                                ->where('class.section_id', '=', $section_id)
                                ->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name', 'grade.computed_grade'])
                                // ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC');
        }
        elseif($grading_period_id != null && $classification_level_id != null && $section_id == null && $subject_id == null)
        {
            $class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
                                ->join('student', 'grade.student_id', '=', 'student.id')
                                ->join('person', 'student.person_id', '=', 'person.id')
                                ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                                ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
                                ->join('classification', 'classification_level.classification_id', '=', 'classification.id')
                                ->join('section', 'class.section_id', '=', 'section.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
                                ->where('grade.grading_period_id', '=', $grading_period_id)
                                ->where('class.classification_level_id', '=', $classification_level_id)
                                // ->where('class.section_id', '=', $section_id)
                                ->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name', 'grade.computed_grade'])
                                // ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC');
        }
        elseif($grading_period_id != null && $classification_level_id == null && $section_id != null)
        {
            $class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
                                ->join('student', 'grade.student_id', '=', 'student.id')
                                ->join('person', 'student.person_id', '=', 'person.id')
                                ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                                ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
                                ->join('classification', 'classification_level.classification_id', '=', 'classification.id')
                                ->join('section', 'class.section_id', '=', 'section.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
                                ->where('grade.grading_period_id', '=', $grading_period_id)
                                // ->where('class.classification_level_id', '=', $classification_level_id)
                                ->where('class.section_id', '=', $section_id)
                                ->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name','grade.computed_grade'])
                                // ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC');
        }
        elseif($grading_period_id == null && $classification_level_id != null && $section_id != null && $subject_id != null)
       	{
       		$class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
        						->join('student', 'grade.student_id', '=', 'student.id')
        						->join('person', 'student.person_id', '=', 'person.id')
        						->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
        						->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
        						->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
        						->join('section', 'class.section_id', '=', 'section.id')
        						->join('classification', 'classification_level.classification_id', '=', 'classification.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
        						// ->where('grade.computed_grade', '>=', 85)
                                // ->where('grade.grading_period_id', '=', $grading_period_id)
                                ->where('class.classification_level_id', '=', $classification_level_id)
                                ->where('class.section_id', '=', $section_id)
                                ->where('subject_offered.subject_id', '=', $subject_id)
        						->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name','grade.computed_grade'])
                                // ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC');
        						// ->orderBy('grading_period.grading_period_name', 'ASC');
       	}
       	elseif($grading_period_id != null || $classification_level_id == null && $section_id == null && $subject_id == null)
       	{
       		$class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
        						->join('student', 'grade.student_id', '=', 'student.id')
        						->join('person', 'student.person_id', '=', 'person.id')
        						->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
        						->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
        						->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
        						->join('classification', 'classification_level.classification_id', '=', 'classification.id')
        						->join('section', 'class.section_id', '=', 'section.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
        						->where('grade.grading_period_id', '=', $grading_period_id)
        						// ->where('class.classification_level_id', '=', $classification_level_id)
                                // ->where('class.section_id', '=', $section_id)
        						// ->where('grade.computed_grade', '>=', 85)
        						->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name','grade.computed_grade'])
        						// ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC');
       	}
        elseif($grading_period_id == null || $classification_level_id != null && $section_id == null)
        {
            $class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
                                ->join('student', 'grade.student_id', '=', 'student.id')
                                ->join('person', 'student.person_id', '=', 'person.id')
                                ->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                                ->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
                                ->join('classification', 'classification_level.classification_id', '=', 'classification.id')
                                ->join('section', 'class.section_id', '=', 'section.id')
                                ->join('grading_period', 'grade.grading_period_id', '=', 'grading_period.id')
                                // ->where('grade.grading_period_id', '=', $grading_period_id)
                                ->where('class.classification_level_id', '=', $classification_level_id)
                                // ->where('class.section_id', '=', $section_id)
                                // ->where('grade.computed_grade', '>=', 85)
                                ->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'subject.name', 'grading_period.grading_period_name','grade.computed_grade'])
                                // ->take(3)
                                ->orderBy('grade.computed_grade', 'DESC');
        }
       	else
       	{
       		$class_top_student = Grade::join('class', 'grade.class_id', '=', 'class.id')
        						->join('student', 'grade.student_id', '=', 'student.id')
        						->join('person', 'student.person_id', '=', 'person.id')
        						->join('subject_offered', 'class.subject_offered_id', '=', 'subject_offered.id')
        						->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
        						->join('classification_level', 'class.classification_level_id', '=', 'classification_level.id')
        						->join('classification', 'classification_level.classification_id', '=', 'classification.id')
        						->join('section', 'class.section_id', '=', 'section.id')
        						->where('grade.grading_period_id', '=', $grading_period_id)
        						->where('class.classification_level_id', '=', $classification_level_id)
                                ->where('class.section_id', '=', $section_id)
                                ->where('subject_offered.subject_id', '=', $subject_id)
        						// ->where('grade.computed_grade', '>=', 85)
        						->select(['grade.id', 'person.first_name', 'person.middle_name', 'person.last_name', 'classification_level.level', 'section.section_name', 'grade.computed_grade'])
        						->orderBy('grade.id', 'DESC');
       	}
        
        // $top_list = [];
        // $count = 0;
        // foreach ($class_top_student as $student) {
        //     $count++;
        //     $top_list[] = ['first_name' => $student -> first_name.' '.$student -> middle_name.' '.$student -> last_name,'level' => $student ->level,'section_name' => $student ->section_name,'computed_grade'=> $student ->computed_grade,'actions' => 'TOP'.$count];
        // };

        // return response()->json($class_top_student);
        return Datatables::of($class_top_student)
        	->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name)) }}')
        	->remove_column('id', 'middle_name', 'last_name')
        	->make();
    }  

}