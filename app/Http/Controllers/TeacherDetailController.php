<?php namespace App\Http\Controllers;

use App\Http\Controllers\HrmsMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Degree;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\Program;
use App\Models\SubjectOffered;
use App\Models\Teacher;
use App\Models\TeacherCategory;
use App\Models\TeacherClassification;
use App\Models\TeacherDegree;
use App\Models\TeacherSubject;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\TeacherEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config; 
use Input;   
use Datatables;
use Hash;

class TeacherDetailController extends HrmsMainController {
   

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */


    public function postCreateJsonTeacher()
    {
      $employee_id = Input::get('employee_id');
      $teacher = new Teacher();
      $teacher->employee_id = Input::get('employee_id');
      $teacher->save();

      return response()->json($teacher);

    }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    
     public function postCreate() {

        $employee_id = Input::get('employee_id');
        $classification_id = Input::get('classification_id'); 
        $classification_level_id = Input::get('classification_level_id'); 
        $program_id = Input::get('program_id'); 
        $employee_category_id = Input::get('employee_category_id'); 

        $teacher = Teacher::where('employee_id',$employee_id)->get()->first();
        if($program_id)
        {

        }
        else
        {
            $program_id = 0;
        }
        // $teacher_classification = new TeacherClassification();
        // $teacher_classification->classification_id = $classification_id;
        // $teacher_classification->classification_level_id = $classification_level_id;
        // // $teacher_classification->program_id = $program_id;
        // $teacher_classification->teacher_id = $teacher->id;     
        // $teacher_classification->save();

        $teacher_category = new TeacherCategory();
        $teacher_category->employee_category_id = $employee_category_id;
        $teacher_category->teacher_id = $teacher->id;     
        $teacher_category->save();

        return response()->json($teacher);
    
    }
    public function postCreateSubject(TeacherRequest $teacher_request)
    {
        $employee_id = Input::get('employee_id');
        $classification_id = Input::get('classification_id'); 
        $classification_level_id = Input::get('classification_level_id'); 
        $teacher = Teacher::where('employee_id',$employee_id)->get()->first();
        
         if(is_array($teacher_request->subject)){
                 foreach ($teacher_request->subject as $item) {
                     $teacher_subject = new TeacherSubject();
                     $teacher_subject->subject_offered_id = $item;
                     $teacher_subject->teacher_id = $teacher->id;
                     $teacher_subject->classification_id = $classification_id;
                     $teacher_subject->classification_level_id = $classification_level_id;
                     $teacher_subject -> save();
                 }
          }
          return response()->json($classification_id);
    }

    public function postCreateDegree(TeacherRequest $teacher_request)
    {
        $employee_id = Input::get('employee_id');

        $teacher = Teacher::where('employee_id',$employee_id)->get()->first();

         if(is_array($teacher_request->degree_id)){
                 foreach ($teacher_request->degree_id as $item) {
                     $teacher_degree = new TeacherDegree();
                     $teacher_degree->degree_id = $item;
                     $teacher_degree->teacher_id = $teacher->id;
                     $teacher_degree -> save();
                 }
          }
    }
    
  
}
