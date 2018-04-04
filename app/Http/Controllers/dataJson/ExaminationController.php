<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Examination;
use App\Models\StudentExamination;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;


class ExaminationController extends BaseController {
   

     public function dataJson(){
          $student_id = \Input::get('student_id');
          $examination = StudentExamination::join('examination','student_examination.examination_id','=','examination.id')
                                      ->where('student_examination.student_id',$student_id)
                                      ->select(['examination.id as value','examination.examination_name as text'])->get();
          return response()->json($examination);
// 
    }

}

