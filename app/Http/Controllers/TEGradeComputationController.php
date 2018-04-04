<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Enrollment;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TEGradeComputationController extends TeachersPortalMainController { 


    public function dataJson(){
        $condition = \Input::all();
        $query = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->select(array('enrollment.id','student.id as student_id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  

        foreach($condition as $column => $value)
        {
            $query->where('enrollment_class.'.$column, '=', $value);
        }
        $enrollment = $query->get();

        return response()->json($enrollment);
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function gradeComputationData()
    {      
        $class_id = \Input::get('class_id');
        $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->where('class.id','=',$class_id)
                ->select(array('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  

        return Datatables::of($enrollment_list)
                ->add_column('prelim','')
                ->add_column('midterm','')
                ->add_column('final','')
                ->add_column('is_editable','')
                ->add_column('is_graduating','')
                ->remove_column('id')
                ->make();

    }
}
