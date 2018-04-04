<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ClassStandingComponent;
use App\Models\ClassStandingComponentDetail;
use App\Models\ClassStandingScoreEntry;
use App\Models\Enrollment;
use App\Models\EnrollmentClass;
use App\Models\TEClass;
use App\Http\Requests\ClassStandingComponentDetailRequest;
use App\Http\Requests\ClassStandingComponentDetailEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ClassStandingComponentDetailController extends TeachersPortalMainController {

    public function dataJson(){

      $condition = \Input::all();
      $query = ClassStandingComponentDetail::leftJoin('class_standing_component','class_standing_component_detail.class_standing_component_id','=','class_standing_component.id')
            ->leftJoin('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
            ->select(array('class_standing_component_detail.id', 'class_standing_component_detail.date','class_standing_component_detail.description', 'class_standing_component_detail.perfect_score', 'class_component_category.class_component_category_name'));

      foreach($condition as $column => $value)
      {
        if($column == 'query')
        {
          $query->where('date', 'LIKE', "%$value%")
                ->orWhere('description', 'LIKE', "%$value%")
                ->orWhere('perfect_score', 'LIKE', "%$value%");
        }
        else
        {
          $query->where($column, '=', $value);
        }

          $query->where($column, '=', $value);    
      }

      $class_standing_component_detail = $query->orderBy('class_standing_component_detail.id','ASC')->get();

      return response()->json($class_standing_component_detail);


        $result = ClassStandingComponentDetail::get();
       return response()->json($result);
    }

    public function editdataJson(){

      $condition = \Input::all();
      $query = ClassStandingComponentDetail::leftJoin('class_standing_component','class_standing_component_detail.class_standing_component_id','=','class_standing_component.id')
            ->leftJoin('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
            ->select(array('class_standing_component_detail.id', 'class_standing_component_detail.date','class_standing_component_detail.description', 'class_standing_component_detail.perfect_score', 'class_component_category.class_component_category_name'));

      foreach($condition as $column => $value)
      {

          $query->where('class_standing_component_detail.id', '=', $value);    
      }

      $class_standing_component_detail = $query->orderBy('class_standing_component_detail.id','ASC')->get();

      return response()->json($class_standing_component_detail);


        $result = ClassStandingComponentDetail::get();
       return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(ClassStandingComponentDetailRequest $class_standing_component_detail_request) {

        $class_standing_component_detail = new ClassStandingComponentDetail();
        $class_standing_component_detail ->description = $class_standing_component_detail_request->description;
        $class_standing_component_detail ->class_standing_component_id = $class_standing_component_detail_request->class_standing_component_id;
        $class_standing_component_detail ->perfect_score = $class_standing_component_detail_request->perfect_score;
        $class_standing_component_detail ->date = $class_standing_component_detail_request->date;
        $class_standing_component_detail -> save();

        $class_standing_component = ClassStandingComponent::join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
                    ->where('class_standing_component.id','=', $class_standing_component_detail->class_standing_component_id)
                    ->select('class_standing_component.id','class_component_category.class_component_category_name')->get()->first();

        if($class_standing_component->class_component_category_name == 'Attendance')
        {
          $class = TEClass::where('class.id','=', $class_standing_component_detail_request->class_id)
                      ->select('class.id','class.term_id','class.section_id')->get()->last();

          $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                      ->where('enrollment.term_id','=', $class->term_id)
                      ->where('enrollment.section_id','=', $class->section_id)
                      ->select('enrollment.id','student_curriculum.student_id')->get();

          foreach($enrollment_list as $enrollment)
          {
              $class_standing_score_entry = new ClassStandingScoreEntry();
              $class_standing_score_entry->student_id = $enrollment->student_id;
              $class_standing_score_entry->class_standing_component_detail_id = $class_standing_component_detail->id;
              $class_standing_score_entry->score = 100;
              $class_standing_score_entry->attendance_remarks_id = 1;
              $class_standing_score_entry->save();
          }
        }
        else
        {
          $class = TEClass::where('class.id','=', $class_standing_component_detail_request->class_id)
                      ->select('class.id','class.term_id','class.section_id')->get()->last();

          $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                      ->where('enrollment.term_id','=', $class->term_id)
                      ->where('enrollment.section_id','=', $class->section_id)
                      ->select('enrollment.id','student_curriculum.student_id')->get();

          foreach($enrollment_list as $enrollment)
          {
              $class_standing_score_entry = new ClassStandingScoreEntry();
              $class_standing_score_entry->student_id = $enrollment->student_id;
              $class_standing_score_entry->class_standing_component_detail_id = $class_standing_component_detail->id;
              $class_standing_score_entry->score = 0;
              $class_standing_score_entry->attendance_remarks_id = 1;
              $class_standing_score_entry->save();
          }
        }
        // if($class_standing_component->class_component_category_name == 'Attendance'){
        //   $class = EnrollmentClass::join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
        //               ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
        //               ->where('enrollment_class.class_id','=', $class_standing_component_detail_request->class_id)
        //               ->select('enrollment_class.id','student_curriculum.student_id')->get();

        //   foreach($class as $item)
        //   {
        //       $class_standing_score_entry = new ClassStandingScoreEntry();
        //       $class_standing_score_entry->student_id = $item->student_id;
        //       $class_standing_score_entry->class_standing_component_detail_id = $class_standing_component_detail->id;
        //       $class_standing_score_entry->score = 1;
        //       $class_standing_score_entry->attendance_remarks_id = 1;
        //       $class_standing_score_entry->save();
        //   }
        // }else{
        //   $class = EnrollmentClass::join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
        //               ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
        //               ->where('enrollment_class.class_id','=', $class_standing_component_detail_request->class_id)
        //               ->select('enrollment_class.id','student_curriculum.student_id')->get();

        //   foreach($class as $item)
        //   {
        //       $class_standing_score_entry = new ClassStandingScoreEntry();
        //       $class_standing_score_entry->student_id = $item->student_id;
        //       $class_standing_score_entry->class_standing_component_detail_id = $class_standing_component_detail->id;
        //       $class_standing_score_entry->score = 0;
        //       $class_standing_score_entry->attendance_remarks_id = 1;
        //       $class_standing_score_entry->save();
        //   }
        // }

        return response()->json();
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(ClassStandingComponentDetailEditRequest $class_standing_component_detail_edit_request) {
      
        $class_standing_component_detail = ClassStandingComponentDetail::find($class_standing_component_detail_edit_request->id);
        $class_standing_component_detail -> description = $class_standing_component_detail_edit_request->description;
        $class_standing_component_detail -> perfect_score = $class_standing_component_detail_edit_request->perfect_score;
        $class_standing_component_detail -> date = $class_standing_component_detail_edit_request->date;
        $class_standing_component_detail -> updated_by_id = Auth::id();
        $class_standing_component_detail -> save();

        return response()->json();
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request)
    {
        $class_standing_component_detail = ClassStandingComponentDetail::find($request->id);
        $class_standing_component_detail->delete();

        return response()->json();
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

        $class_standing_component_id = \Input::get('class_standing_component_id');
        $class_standing_component_detail_list = ClassStandingComponentDetail::join('class_standing_component','class_standing_component_detail.class_standing_component_id','=','class_standing_component.id')
                // ->where('class_standing_component_detail.class_standing_component_id','=',$class_standing_component_id)
                ->select(array('class_standing_component_detail.id', 'class_standing_component_detail.date','class_standing_component_detail.description', 'class_standing_component_detail.perfect_score'))
                ->orderBy('class_standing_component_detail.id', 'DESC');
        
        return Datatables::of($class_standing_component_detail_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'class_standing_component_detail/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'class_standing_component_detail/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')            
            ->remove_column('id')
            ->make();
    }


}
