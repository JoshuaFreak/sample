<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Employee;
use App\Models\StudentRemarks;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ConductRemarksController extends TeachersPortalMainController {   

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */


        public function ConductRemarksData(){
          
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        
        $student_remarks_list = StudentRemarks::join('grading_period', 'student_remarks.grading_period_id', '=', 'grading_period.id')
                ->join('class','student_remarks.class_id','=','class.id')
                ->join('student','student_remarks.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                // ->where('student_remarks.class_id','=',$class_id)
                ->where('student_remarks.student_id','=',$student_id)
                ->where('student_remarks.term_id','=',$term_id)
                ->where('student_remarks.classification_id','=',$classification_id)
                ->where('student_remarks.classification_level_id','=',$classification_level_id)
                // ->where('student_remarks.subject_id','=',$subject_id)
                ->select(array('student_remarks.id','grading_period.grading_period_name','student_remarks.remarks','person.last_name','person.first_name', 'person.middle_name','student_remarks.classification_id'))
                ->orderBy('grading_period.id', 'ASC');  

        return Datatables::of($student_remarks_list)
                ->add_column('action', '<button class="btn btn-sm btn-success active" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#editstudentremarksmodal"><span class="glyphicon glyphicon-pencil"></span> Edit</a></button>
                    &nbsp;&nbsp;<button class="btn btn-sm btn-danger active" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#deletestudentremarksmodal"><span class="glyphicon glyphicon-pencil"></span> Delete</a></button>')
                ->remove_column('id','last_name','first_name', 'middle_name','classification_id')
                ->make();

    }

    public function postCreate(){

        $remarks=\Input::get('remarks');
        $grading_period_id=\Input::get('grading_period_id');
        $student_id=\Input::get('student_id');
        // $class_id=\Input::get('class_id');
        $classification_id=\Input::get('classification_id');
        $classification_level_id=\Input::get('classification_level_id');
        $term_id=\Input::get('term_id');

        $employee = Employee::where('employee_no','=',Auth::user()->username)->get()->last();

        $student_remarks = new StudentRemarks();
        $student_remarks->remarks = $remarks;
        $student_remarks->grading_period_id = $grading_period_id;
        $student_remarks->classification_level_id = $classification_level_id;
        $student_remarks->student_id = $student_id;
        // $student_remarks->class_id = $class_id;
        $student_remarks->classification_id = $classification_id;
        $student_remarks->term_id = $term_id;
        $student_remarks->teacher_id = $employee->id;
        $student_remarks-> save();
    }
    public function editdataJson(){

      $condition = \Input::all();
      $query = StudentRemarks::leftJoin('grading_period','student_remarks.grading_period_id','=','grading_period.id')
            ->select(array('student_remarks.id',  'student_remarks.grading_period_id', 'student_remarks.remarks'));

      foreach($condition as $column => $value)
      {
        $query->where('student_remarks.id', '=', $value);    
      }

      $student_remarks = $query->orderBy('student_remarks.id','ASC')->get();

      return response()->json($student_remarks);

      $result = StudentRemarks::get();
       return response()->json($result);
    }


    public function postEdit() {

        $id=\Input::get('id');
        $remarks=\Input::get('remarks');
        $grading_period_id=\Input::get('grading_period_id');
      
        $student_remarks = StudentRemarks::find($id);
        $student_remarks->remarks = $remarks;
        $student_remarks->grading_period_id = $grading_period_id;
        $student_remarks -> updated_by_id = Auth::id();
        $student_remarks -> save();

    }

    public function postDelete()
    {
        $id=\Input::get('id');
        
        $student_remarks = StudentRemarks::find($id);
        $student_remarks->delete();
    }
  
}
