<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\StudentAchievements;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentAchievementsController extends TeachersPortalMainController {   
   
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
    public function StudentAchievementsData(){
          
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        
        $student_achievements_list = StudentAchievements::join('grading_period', 'student_achievements.grading_period_id', '=', 'grading_period.id')
                ->leftJoin('class','student_achievements.class_id','=','class.id')
                ->leftJoin('student','student_achievements.student_id','=','student.id')
                ->leftJoin('person','student.person_id','=','person.id')
                // ->where('student_achievements.class_id','=',$class_id)
                ->where('student_achievements.student_id','=',$student_id)
                ->where('student_achievements.term_id','=',$term_id)
                ->where('student_achievements.classification_id','=',$classification_id)
                ->where('student_achievements.classification_level_id','=',$classification_level_id)
                ->select(array('student_achievements.id','grading_period.grading_period_name','student_achievements.achievements','person.last_name','person.first_name', 'person.middle_name','student_achievements.classification_id'))->orderBy('student_achievements.created_at','DESC');  

        return Datatables::of($student_achievements_list)
                ->add_column('action', '<button class="btn btn-sm btn-success active" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#editstudentachievementsmodal"><span class="glyphicon glyphicon-pencil"></span> Edit</a></button>
                    &nbsp;&nbsp;<button class="btn btn-sm btn-danger active" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#deletestudentachievementsmodal"><span class="glyphicon glyphicon-pencil"></span> Delete</a></button>')
                ->remove_column('id','last_name','first_name', 'middle_name','classification_id')
                ->make();

    }

    public function postCreate(){

        $achievements=\Input::get('achievements');
        $grading_period_id=\Input::get('grading_period_id');
        $student_id=\Input::get('student_id');
        // $class_id=\Input::get('class_id');
        $classification_id=\Input::get('classification_id');
        $classification_level_id=\Input::get('classification_level_id');
        $term_id=\Input::get('term_id');

        $employee = Employee::where('employee_no','=',Auth::user()->username)->get()->last();

        $student_achievements = new StudentAchievements();
        $student_achievements->achievements = $achievements;
        $student_achievements->grading_period_id = $grading_period_id;
        $student_achievements->classification_level_id = $classification_level_id;
        $student_achievements->student_id = $student_id;
        // $student_achievements->class_id = $class_id;
        $student_achievements->classification_id = $classification_id;
        $student_achievements->term_id = $term_id;
        $student_achievements->teacher_id = $employee->id;
        $student_achievements-> save();
    }

    public function editdataJson(){

      $condition = \Input::all();
      $query = StudentAchievements::leftJoin('grading_period','student_achievements.grading_period_id','=','grading_period.id')
            ->select(array('student_achievements.id',  'student_achievements.grading_period_id', 'student_achievements.achievements'));

      foreach($condition as $column => $value)
      {
        $query->where('student_achievements.id', '=', $value);    
      }

      $student_achievements = $query->orderBy('student_achievements.id','ASC')->get();

      return response()->json($student_achievements);

      $result = StudentAchievements::get();
       return response()->json($result);
    }

    public function postEdit() {

        $id=\Input::get('id');
        $achievements=\Input::get('achievements');
        $grading_period_id=\Input::get('grading_period_id');
      
        $student_achievements = StudentAchievements::find($id);
        $student_achievements->achievements = $achievements;
        $student_achievements->grading_period_id = $grading_period_id;
        $student_achievements -> updated_by_id = Auth::id();
        $student_achievements -> save();

    }

    public function postDelete()
    {
        $id=\Input::get('id');
        
        $student_achievements = StudentAchievements::find($id);
        $student_achievements->delete();
    }
}
