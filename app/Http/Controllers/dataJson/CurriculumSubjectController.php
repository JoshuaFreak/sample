<?php namespace App\Http\Controllers\dataJson;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Program;
use App\Models\SemesterLevel;
use App\Models\Subject;
use App\Models\SubjectOffered;
use App\Http\Requests\CurriculumSubjectRequest;
use App\Http\Requests\CurriculumSubjectEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class CurriculumSubjectController extends BaseController {
   

    public function dataJsonGroup(){
      $condition = \Input::all();
      $query = CurriculumSubject::join("subject_offered","curriculum_subject.subject_offered_id","=","subject_offered.id")
                                ->join('subject', 'subject_offered.subject_id', '=','subject.id')
                                ->join('classification_level', 'curriculum_subject.classification_level_id', '=', 'classification_level.id')
                                ->join('semester_level', 'curriculum_subject.semester_level_id', '=', 'semester_level.id');
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }

       $check = $query;

        $check = $check->select([ 'subject.code','subject.name', 'subject.credit_unit', 'semester_level.id', 'classification_level.level', 'semester_level.semester_name'])
                        ->get()->last();

        if($check ->semester_name == "Whole Year")
        {
            $curriculum_subject = $query->select([ 'subject.code','subject.name', 'subject.credit_unit', 'semester_level.id' , 'classification_level.id as classification_level_id' , 'classification_level.level', 'semester_level.semester_name'])
                        ->orderBy('classification_level.id')
                        ->groupBy('curriculum_subject.classification_level_id')
                        ->get();
        }
        else
        {
            $curriculum_subject = $query->select([ 'subject.code','subject.name', 'subject.credit_unit', 'semester_level.id', 'classification_level.id as classification_level_id' , 'classification_level.level', 'semester_level.semester_name'])
                        ->orderBy('classification_level.id')
                        ->groupBy('curriculum_subject.semester_level_id')
                        ->get();
        }

      return response()->json($curriculum_subject);
    }

    public function dataJson(){

      $condition = \Input::all();

      $query = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=','subject.id')
                                ->join('semester_level', 'curriculum_subject.semester_level_id', '=', 'semester_level.id');
      foreach($condition as $column => $value)
      {
        $query->where('curriculum_subject.'.$column, '=', $value);
      }

        $curriculum_subject = $query->select([ 'subject.code','subject.name', 'subject.credit_unit'])
                        ->orderBy('subject.code')
                        ->get();
      

      return response()->json($curriculum_subject);
    }

    public function termDataJson(){

      $classification_level_id = \Input::get('classification_level_id');
      $term_id = \Input::get('term_id');
      $program_id = \Input::get('program_id');

      if($program_id == "")
      {
          $query = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                                    ->join('subject', 'subject_offered.subject_id', '=','subject.id')
                                    ->join('semester_level', 'curriculum_subject.semester_level_id', '=', 'semester_level.id')
                                    ->where('subject_offered.term_id','=', $term_id)
                                    ->where('curriculum_subject.classification_level_id','=', $classification_level_id);
      }
      else
      {
          $query = CurriculumSubject::join('subject_offered','curriculum_subject.subject_offered_id','=','subject_offered.id')
                                ->join('subject', 'subject_offered.subject_id', '=','subject.id')
                                ->join('semester_level', 'curriculum_subject.semester_level_id', '=', 'semester_level.id')
                                ->where('subject_offered.term_id','=', $term_id)
                                ->where('subject_offered.program_id','=', $program_id)
                                ->where('curriculum_subject.classification_level_id','=', $classification_level_id);
      }

      $curriculum_subject = $query->select([ 'curriculum_subject.subject_offered_id as id','subject.code','subject.name', 'subject.credit_unit'])
                      ->orderBy('subject.code')
                      ->get();

      return response()->json($curriculum_subject);
    }


    public function dataJsonCurriculum(){

      $condition = \Input::all();

      $query = CurriculumSubject::join('curriculum','curriculum_subject.curriculum_id','=','curriculum.id')
                    ->select('curriculum.id', 'curriculum.curriculum_name')
                    ->groupBy('curriculum_subject.curriculum_id');
      foreach($condition as $column => $value)
      {
        $query->where('curriculum_subject.'.$column, '=', $value);
      }

        $curriculum_subject = $query->select(['curriculum.id as value','curriculum_name as text'])->get();
      

      return response()->json($curriculum_subject);
    }

    public function dataJsonRegistrar(){

      $condition = \Input::all();

      $query = Curriculum::select();

      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $curriculum = $query->select([ 'id as value','curriculum_name as text'])->orderBy('id','DESC')->get();

      return response()->json($curriculum);
    }

    public function dataJsonCurriculumSubject(){

      $condition = \Input::all();

      $query = CurriculumSubject::join('subject_offered', 'curriculum_subject.subject_offered_id', '=', 'subject_offered.id')
              ->select('curriculum_subject.id', 'subject_offered.id', 'curriculum_subject.subject_offered_id');

      foreach($condition as $column => $value)
      {
        $query->where('curriculum_subject.'.$column, '=', $value);
      }
      $curriculum_subject = $query->select(['subject_id'])->get();

      return response()->json($curriculum_subject);
    }

    // public function dataJsonCurriculumSubject(){

    //   $condition = \Input::all();

    //   $query = CurriculumSubject::join('subject_offered', 'curriculum_subject.subject_offered_id', '=', 'subject_offered.id')
    //           ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
    //           ->select('curriculum_subject.id', 'subject_offered.id', 'subject_offered.subject_id', 'curriculum_subject.subject_offered_id');

    //   foreach($condition as $column => $value)
    //   {
    //     $query->where('curriculum_subject.'.$column, '=', $value);
    //   }
    //   $curriculum_subject = $query->select(['subject_id'])->get();

    //   return response()->json($curriculum_subject);
    // }


}
