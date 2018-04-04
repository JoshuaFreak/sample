<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Employee;
use App\Models\EnrollmentSection;
use App\Models\StudentSubjectRemarks;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentSubjectRemarksController extends TeachersPortalMainController {   
  
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $classification_level_id = \Input::get('classification_level_id');
        $section_id = \Input::get('section_id');
        $subject_id = \Input::get('subject_id');
        $term_id = \Input::get('term_id');

        $enrollment_list = EnrollmentSection::leftJoin('section','enrollment_section.section_id','=','section.id')
                    ->leftJoin('student','enrollment_section.student_id','=','student.id')
                    ->leftJoin('person','student.person_id','=','person.id')
                    ->leftJoin('classification_level','section.classification_level_id','=','classification_level.id')
                    ->leftJoin('classification','classification_level.classification_id','=','classification.id')
                    ->leftJoin('class','class.section_id','=','section.id')
                    ->leftJoin('term','class.term_id','=','term.id')
                    ->leftJoin('subject_offered','class.subject_offered_id','=','subject_offered.id')
                    ->leftJoin('subject','subject_offered.subject_id','=','subject.id')
                    ->where('section.classification_level_id','=',$classification_level_id)
                    ->where('enrollment_section.section_id','=',$section_id)
                    ->where('subject_offered.subject_id','=',$subject_id)
                    ->where('class.term_id','=',$term_id)
                    ->select(['enrollment_section.id','student.id as student_id','student.student_no','person.last_name','person.first_name','person.middle_name','classification_level.level','class.term_id','subject_offered.subject_id','subject.id as subject_id','section.section_name','classification.classification_name','enrollment_section.section_id','classification_level.classification_id','section.classification_level_id'])
                    ->groupBy('student.id');

        return Datatables::of($enrollment_list)
                    ->add_column('actions','<img class="button" src="{{{ asset("assets/site/images/teachers_portal/Conduct Remarks.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-classification_id="{{{$classification_id}}}"  data-classification_level_id="{{{$classification_level_id}}}" data-subject_id="{{{$subject_id}}}" data-term_id="{{{$term_id}}}" data-toggle="modal" data-target="#studentsubjectremarksmodal">')
                    ->editColumn('last_name','{{ ucwords(strtolower($last_name.", ".$first_name." ".$middle_name)) }}')
                    ->remove_column('id', 'student_id',  'first_name',  'middle_name', 'level', 'section_name', 'section_id','subject_id', 'term_id', 'classification_id', 'classification_name','classification_level_id')
                    ->make();  
    }   /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function ConductStudentRemarksData()
    {
          
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        $subject_id = \Input::get('subject_id');
        
        $student_subject_remarks_list = StudentSubjectRemarks::join('grading_period', 'student_subject_remarks.grading_period_id', '=', 'grading_period.id')
                ->leftJoin('class','student_subject_remarks.class_id','=','class.id')
                ->leftJoin('student','student_subject_remarks.student_id','=','student.id')
                ->leftJoin('person','student.person_id','=','person.id')
                // ->where('student_subject_remarks.class_id','=',$class_id)
                ->where('student_subject_remarks.student_id','=',$student_id)
                ->where('student_subject_remarks.term_id','=',$term_id)
                ->where('student_subject_remarks.classification_id','=',$classification_id)
                ->where('student_subject_remarks.classification_level_id','=',$classification_level_id)
                ->where('student_subject_remarks.subject_id','=',$subject_id)
                ->select(array('student_subject_remarks.id','grading_period.grading_period_name','student_subject_remarks.remarks','person.last_name','person.first_name', 'person.middle_name','student_subject_remarks.classification_id'))
                ->orderBy('grading_period.id', 'ASC');  

        return Datatables::of($student_subject_remarks_list)
                ->add_column('action', '<button class="btn btn-sm btn-success active" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#editstudentsubjectremarksmodal"><span class="glyphicon glyphicon-pencil"></span> Edit</a></button>
                    &nbsp;&nbsp;<button class="btn btn-sm btn-danger active" data-id="{{{$id}}}" data-classification_id="{{{$classification_id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-toggle="modal" data-target="#deletestudentsubjectremarksmodal"><span class="glyphicon glyphicon-pencil"></span> Delete</a></button>')
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
            $subject_id=\Input::get('subject_id');

            $employee = Employee::where('employee_no','=',Auth::user()->username)->get()->last();

            $student_subject_remarks = new StudentSubjectRemarks();
            $student_subject_remarks->remarks = $remarks;
            $student_subject_remarks->grading_period_id = $grading_period_id;
            $student_subject_remarks->classification_level_id = $classification_level_id;
            $student_subject_remarks->student_id = $student_id;
            $student_subject_remarks->classification_id = $classification_id;
            $student_subject_remarks->term_id = $term_id;
            $student_subject_remarks->subject_id = $subject_id;
            $student_subject_remarks->teacher_id = $employee->id;
            $student_subject_remarks-> save();
        }
        public function editdataJson(){

          $condition = \Input::all();
          $query = StudentSubjectRemarks::leftJoin('grading_period','student_subject_remarks.grading_period_id','=','grading_period.id')
                ->select(array('student_subject_remarks.id',  'student_subject_remarks.grading_period_id', 'student_subject_remarks.remarks'));

          foreach($condition as $column => $value)
          {
            $query->where('student_subject_remarks.id', '=', $value);    
          }

          $student_subject_remarks = $query->orderBy('student_subject_remarks.id','ASC')->get();

          return response()->json($student_subject_remarks);

          $result = StudentSubjectRemarks::get();
           return response()->json($result);
        }


        public function postEdit() {

            $id=\Input::get('id');
            $remarks=\Input::get('remarks');
            $grading_period_id=\Input::get('grading_period_id');
          
            $student_subject_remarks = StudentSubjectRemarks::find($id);
            $student_subject_remarks->remarks = $remarks;
            $student_subject_remarks->grading_period_id = $grading_period_id;
            $student_subject_remarks -> updated_by_id = Auth::id();
            $student_subject_remarks -> save();

        }

        public function postDelete()
        {
            $id=\Input::get('id');
            
            $student_subject_remarks = StudentSubjectRemarks::find($id);
            $student_subject_remarks->delete();
        }
  

}