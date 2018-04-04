<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\SubjectOffered;
use App\Models\CurriculumSubject;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SubjectOfferedController extends BaseController {
   


     public function CurriculumSubjectdataJson()
     {
      $condition = \Input::all();

      $curriculum_subject = CurriculumSubject::lists('subject_offered_id');
      $query = SubjectOffered::join('subject', 'subject_offered.subject_id', '=', 'subject.id');
      
      foreach($condition as $column => $value)
      {
        $query->where('subject_offered.'.$column, '=', $value)
              ->whereNotIn('subject_offered.id', $curriculum_subject);
      }
      // $query->where('.subject_offered.lec_fee_per_unit','!=','0');
      $subject = $query->select([ 'subject_offered.id as value','subject.code as text'])->get();

      return response()->json($subject);
    }


     public function dataJson()
     {
      $condition = \Input::all();

      // $curriculum_subject = CurriculumSubject::lists('subject_offered_id');
      $query = SubjectOffered::join('subject', 'subject_offered.subject_id', '=', 'subject.id');
      
      foreach($condition as $column => $value)
      {
        $query->where('subject_offered.'.$column, '=', $value);
              // ->whereNotIn('subject_offered.id', $curriculum_subject);
      }
      $subject = $query->select([ 'subject_offered.id as value','subject.code as text'])->get();

      return response()->json($subject);
    }


     public function SubjectdataJson()
     {
          $condition = \Input::all();
          $query = SubjectOffered::join('subject','subject_offered.subject_id','=','subject.id');

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }

          $subject_offered = $query->select(['subject.code','subject.name'])->get();

          return response()->json($subject_offered);
    }

    public function subjectOfferedDataJson()
     {
          $condition = \Input::all();
          $query = SubjectOffered::select();

          foreach($condition as $column => $value)
          {
            $query->where('subject_offered.'.$column, '=', $value);
          }

          $subject_offered = $query->select(['subject_id'])->get();

          return response()->json($subject_offered);
    }

   
}
