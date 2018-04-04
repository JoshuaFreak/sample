<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Enrollment;
use App\Models\StudentIntervention;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TEInterventionController extends TeachersPortalMainController {   
   
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function interventionData()
    {
          
        $class_id = \Input::get('class_id');
        $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->where('class.id','=',$class_id)
                ->select(array('enrollment.id','student_curriculum.student_id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  

        return Datatables::of($enrollment_list)
                ->editColumn('last_name','{{ ucwords(strtolower($last_name.",  ".$first_name."  ".$middle_name)) }}')
                ->editColumn('id', '<button class="btn btn-sm btn-inverse" data-id="{{{$id}}}" data-class_id="'.$class_id.'" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#studentinterventionmodal"><span class="glyphicon glyphicon-user"></span></button>')
                ->add_column('grade', '')
                ->add_column('absences', '')
                ->add_column('late', '')
                ->remove_column('student_id','first_name', 'middle_name')
                ->make();

    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function StudentInterventionData()
    {
          
        $class_id = \Input::get('class_id');
        $student_id = \Input::get('student_id');
        $student_intervention_list = StudentIntervention::join('action_taken', 'student_intervention.action_taken_id', '=', 'action_taken.id')
                ->join('grading_period', 'student_intervention.grading_period_id', '=', 'grading_period.id')
                ->where('student_intervention.class_id','=',$class_id)
                ->where('student_intervention.student_id','=',$student_id)
                ->select(array('student_intervention.id','student_intervention.teacher_comment','action_taken.action_taken_name','student_intervention.created_at','grading_period.grading_period_name'));  

        return Datatables::of($student_intervention_list)
                ->add_column('action', '')
                ->remove_column('id')
                ->make();

    }
}
