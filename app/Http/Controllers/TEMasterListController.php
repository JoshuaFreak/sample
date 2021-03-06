<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Enrollment;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TEMasterListController extends TeachersPortalMainController {   
  
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function masterListData()
    {
        $class_id = \Input::get('masterlist_id');
        $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
            ->join('class', 'enrollment_class.class_id', '=', 'class.id')
            ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
            ->join('student', 'student_curriculum.student_id', '=', 'student.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->where('class.id','=',$class_id)
            ->select(array('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
            ->groupBy('student.id');  

        return Datatables::of($enrollment_list)
            ->remove_column('id')
            ->make();  


    }
}