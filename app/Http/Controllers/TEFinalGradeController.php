<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Schedule;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TEFinalGradeController extends TeachersPortalMainController {   
   
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function finalGradeData()
    {

        $schedule_list = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('enrollment_class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('enrollment', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->select(array('schedule.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  
        return Datatables::of($schedule_list)
                ->add_column('prelim','')
                ->add_column('midterm','')
                ->add_column('final','')
                ->add_column('remarks','')
                ->remove_column('id')
                ->make();

    }
}
