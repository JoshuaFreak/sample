<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\AttendanceRemark;
use App\Models\Classification;
use App\Models\Student;
use App\Models\StudentCurriculum;
use App\Models\Term;
use App\Http\Requests\DeleteRequest;
use Datatables;
use Config;    
use Hash;
class RegistrarController extends RegistrarMainController {
   

    public function index()
    {
        $classification_list = Classification::all();

        return view('registrar/register_student/index',compact('classification_list'));
    }

     public function ERDataJsonCount()
    {
        $condition = \Input::all();
        
        $query = Student::join('yr_n_sec','student.yr_n_sec_id','=','yr_n_sec.id')
                            ->join('term','student.term_id','=','term.id');
        foreach($condition as $column => $value)
        {
            $query->where($column, '=', $value);
        }

        $student_list = $query->count();
    

        return response()->json($student_list);
    }

     public function ERDataJson()
    {
        $condition = \Input::all();
        
        $query = Student::join('yr_n_sec','student.yr_n_sec_id','=','yr_n_sec.id')
                            ->join('term','student.term_id','=','term.id');
                           
        foreach($condition as $column => $value)
        {
            $query->where($column, '=', $value);
        }

        $student_list = $query->select('student.first_name','student.last_name','student.middle_name')->get();
    

        return response()->json($student_list);
    }

    public function getDelete($id)
     {
        $register_student = Student::find($id);

        $student = Student::join('yr_n_sec','student.yr_n_sec_id','=','yr_n_sec.id')
                            ->join('term','student.term_id','=','term.id')
                            ->where('student.id',$register_student->id)->select(['student.id','student.first_name'])->get()->first();
        // Show the page
        return view('register_student/delete', compact('register_student','trainee'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $register_student = Student::find($id);
        $register_student->delete();
        
        return redirect('register_student');
    }



    public function dataAll()
  {
        // $classification_name = \Input::get('classification_name');
       
        $student_curriculum_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
            ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
            ->join('status', 'student.status_id', '=', 'status.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->leftJoin('suffix', 'person.suffix_id', '=', 'suffix.id')
            ->select(['student.school_no','student.student_no','person.last_name','person.first_name','person.middle_name','suffix.suffix_name','status.status_name', 'student_curriculum.id']);
     
        return Datatables::of($student_curriculum_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'registrar/register_student/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'registrar/register_student/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->edit_column('last_name', '{{{$last_name.", ".$first_name." ".$middle_name." ".$suffix_name}}}')
            ->remove_column('middle_name', 'first_name', 'suffix_name')
            ->remove_column('id')
            ->make();
    }

    public function data()
  {
        $classification_name = \Input::get('classification_name');


        if($classification_name == "Resource")
        {
            $student_curriculum_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
            ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
            ->join('status', 'student.status_id', '=', 'status.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->leftJoin('suffix', 'person.suffix_id', '=', 'suffix.id')
            // ->where('student_curriculum.is_sped', '=', 1)
            ->select(array('student.school_no','student.student_no','person.last_name','person.first_name','person.middle_name','suffix.suffix_name','status.status_name', 'student_curriculum.id'));
        }
        else
        {
            $student_curriculum_list = StudentCurriculum::join('student','student_curriculum.student_id','=','student.id')
            ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
            ->join('status', 'student.status_id', '=', 'status.id')
            ->join('person', 'student.person_id', '=', 'person.id')
            ->leftJoin('suffix', 'person.suffix_id', '=', 'suffix.id')
            // ->where('student_curriculum.is_sped', '=', 0)
            ->where('classification.classification_name', '=', $classification_name)
            ->select(array('student.school_no','student.student_no','person.last_name','person.first_name','person.middle_name','suffix.suffix_name','status.status_name', 'student_curriculum.id'));
     
        }
        
        return Datatables::of($student_curriculum_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'registrar/register_student/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'registrar/register_student/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->edit_column('first_name', '{{{$last_name.", ".$first_name." ".$middle_name." ".$suffix_name}}}')
            ->remove_column('middle_name', 'last_name', 'suffix_name')
            ->remove_column('id')
            ->make();
    }
   /**
     * Reorder items
     *
     * @param items list
     * @return items from @param
     */


}   

    