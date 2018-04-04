<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\StudentAcademicProjection;
use App\Models\ClassificationLevel;
use App\Models\Student;
use App\Models\GradingPeriod;
use App\Models\Subject;
use App\Models\Term;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\AddPaceRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentPaceController extends TeachersPortalMainController {   
   
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    // public function interventionData(){
          
    //     $class_id = \Input::get('class_id');
    //     $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
    //             ->join('class', 'enrollment_class.class_id', '=', 'class.id')
    //             ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
    //             ->join('student', 'student_curriculum.student_id', '=', 'student.id')
    //             ->join('person', 'student.person_id', '=', 'person.id')
    //             // ->where('class.id','=',$class_id)
    //             ->select(array('enrollment.id','student_curriculum.student_id','student.student_no','student.classification_id','person.last_name','person.first_name','person.middle_name'))
    //             ->groupBy('student.id');  

    //     return Datatables::of($enrollment_list)
    //             ->editColumn('last_name','{{ ucwords(strtolower($last_name.",  ".$first_name."  ".$middle_name)) }}')
    //             ->editColumn('id', '<img class="button" src="{{{ asset("assets/site/images/teachers_portal/intervention.png") }}}" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-class_id="'.$class_id.'" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#studentinterventionmodal">')
    //             ->add_column('grade', '')
    //             ->add_column('absences', '')
    //             ->add_column('late', '')
    //             ->remove_column('student_id','first_name', 'middle_name', 'classification_id')
    //             ->make();

    // }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function StudentAcademicProjectionData(){
          
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        
        $student_academic_projection_list = Student::join('student_academic_projection','student.id','=','student_academic_projection.student_id')
                                            ->leftJoin('classification_level','student_academic_projection.classification_level_id','=','classification_level.id')
                                            ->leftJoin('grading_period','student_academic_projection.grading_period_id','=','grading_period.id')
                                            ->leftJoin('subject','student_academic_projection.subject_id','=','subject.id')
                                            ->leftJoin('term','student_academic_projection.term_id','=','term.id')
                                            ->where('student_academic_projection.student_id','=',$student_id)
                                            ->where('student_academic_projection.classification_level_id','=',$classification_level_id)
                                            ->select(['student_academic_projection.student_id','student_academic_projection.id','grading_period.grading_period_name','student_academic_projection.required_pace','student_academic_projection.classification_level_id','student_academic_projection.grading_period_id','student_academic_projection.subject_id','subject.name','student_academic_projection.term_id'])
                                            ->orderBy('student_academic_projection.id','DESC')
                                            ->groupBy('student_academic_projection.id'); 

        return Datatables::of($student_academic_projection_list)
                ->add_column('action', '<button class="btn btn-sm btn-success active" data-id="{{{$id}}}" data-student_id="{{{$student_id}}} "data-classification_level_id="{{{$classification_level_id}}}" data-grading_period_id="{{{$grading_period_id}}}" data-subject_id="{{{$subject_id}}}" data-required_pace_name="{{{$required_pace}}}" data-term_id="{{{$term_id}}}" data-name="{{{$name}}}" data-toggle="modal" data-target="#neweditpacemodal"><span class="glyphicon glyphicon-pencil"></span> Edit</a></button>

                    &nbsp;&nbsp;<button class="btn btn-sm btn-danger active" data-id="{{{$id}}}" data-student_id="{{{$student_id}}} "data-classification_level_id="{{{$classification_level_id}}}" data-grading_period_id="{{{$grading_period_id}}}" data-subject_id="{{{$subject_id}}}" data-required_pace_name="{{{$required_pace}}}" data-term_id="{{{$term_id}}}" data-name="{{{$name}}}" data-toggle="modal" data-target="#newdeletepacemodal"><span class="glyphicon glyphicon-remove"></span> Delete</a></button>')
                ->remove_column('id','student_id','classification_level_id','grading_period_id','term_id','subject_id')
                ->make();

    }

    public function postCreate(AddPaceRequest $add_pace){

        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        $grading_period_id = \Input::get('grading_period_id');
        $subject_id = \Input::get('subject_id');


        foreach($add_pace->pace as $pace)
        {
            $student_academic_projection = new StudentAcademicProjection();
            $student_academic_projection->student_id = $student_id;
            $student_academic_projection->classification_level_id = $classification_level_id;
            $student_academic_projection->grading_period_id = $grading_period_id;
            $student_academic_projection->subject_id = $subject_id;
            $student_academic_projection->required_pace = $pace;
            $student_academic_projection->term_id = $term_id;
            $student_academic_projection->save();
        }

  

            $student_academic_projection = new StudentAcademicProjection();
            $student_academic_projection->student_id = $student_id;
            $student_academic_projection->classification_level_id = $classification_level_id;
            $student_academic_projection->grading_period_id = $grading_period_id;
            $student_academic_projection->subject_id = $subject_id;
            $student_academic_projection->required_pace = "";
            $student_academic_projection->term_id = $term_id;
            $student_academic_projection->save();

            $student_academic_projection = new StudentAcademicProjection();
            $student_academic_projection->student_id = $student_id;
            $student_academic_projection->classification_level_id = $classification_level_id;
            $student_academic_projection->grading_period_id = $grading_period_id;
            $student_academic_projection->subject_id = $subject_id;
            $student_academic_projection->required_pace = "";
            $student_academic_projection->term_id = $term_id;
            $student_academic_projection->save();

            $student_academic_projection = new StudentAcademicProjection();
            $student_academic_projection->student_id = $student_id;
            $student_academic_projection->classification_level_id = $classification_level_id;
            $student_academic_projection->grading_period_id = $grading_period_id;
            $student_academic_projection->subject_id = $subject_id;
            $student_academic_projection->required_pace = "Exam";
            $student_academic_projection->term_id = $term_id;
            $student_academic_projection->save();
    }



    public function postEdit() {

        $id=\Input::get('id');
        $grading_period_id=\Input::get('grading_period_id');
      
        $student_academic_projection = StudentAcademicProjection::find($id);
        $student_academic_projection->grading_period_id = $grading_period_id;
        $student_academic_projection->save();
    }
    public function postDelete()
    {
        $id=\Input::get('id');
      
        $student_academic_projection = StudentAcademicProjection::find($id);
        $student_academic_projection->delete();
    }
}
