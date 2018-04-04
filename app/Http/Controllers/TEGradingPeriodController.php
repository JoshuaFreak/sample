<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ClassComponentCategory;
use App\Models\ClassStandingComponent;
use App\Models\GradingPeriod;
use App\Models\Schedule;
use App\Models\Subject;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class BEGradingPeriodController extends TeachersPortalMainController {   
 
     /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function gradingPeriod()
    {
      $condition = \Input::all();
      $query = Schedule::join('class', 'schedule.class_id', '=', 'class.id')
                ->join('term','class.term_id','=','term.id')
                ->join('section','class.section_id','=','section.id')
                ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
                ->join('subject','subject_offered.subject_id','=','subject.id')
                ->join('room','schedule.room_id','=','room.id')
                ->join('day','schedule.day_id','=','day.id')
                ->select(array('schedule.id','section.section_name','subject.code','schedule.time_start','schedule.time_end','room.room_name','day.day_code','term.term_name'));
      
      foreach($condition as $column => $value)
      {
        $query->where('schedule.'.$column, '=', $value);
      }
      $schedule = $query->select([ 'schedule.id as id', 'section.section_name as section', 'subject.code as subject_code'])->get();

      return response()->json($schedule);
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
      $class_id = \Input::get('class_id');
      $term_id = \Input::get('term_id');
      $grading_period_list = GradingPeriod::select(array('grading_period.id','grading_period.grading_period_name','grading_period.description','grading_period.default_class_standing_weight','grading_period.default_major_exam_weight'))
                ->where('classification_id','!=',5)
                ->where('term_id','=',$term_id);  
      
      return Datatables::of($grading_period_list)
              ->add_column('scsc','<button class="btn btn-sm btn-primary active" data-id="{{{$id}}}" data-class_id="'.$class_id.'" data-grading_period_name="{{{$grading_period_name}}}" data-toggle="modal" data-target="#scscmodal"><span class="glyphicon glyphicon-edit"></span></button>')
              ->add_column('cscd','<button class="btn btn-sm btn-success active" data-id="{{{$id}}}" data-class_id="'.$class_id.'" data-grading_period_name="{{{$grading_period_name}}}" data-toggle="modal" data-target="#cscdmodal"><span class="glyphicon glyphicon-edit"></span></button>')
              ->add_column('med','<button class="btn btn-sm btn-info active" data-id="{{{$id}}}" data-class_id="'.$class_id.'" data-grading_period_name="{{{$grading_period_name}}}" data-toggle="modal" data-target="#medmodal"><span class="glyphicon glyphicon-edit"></span></button>')
              ->add_column('cr','<button class="btn btn-sm btn-warning active" data-id="{{{$id}}}" data-class_id="'.$class_id.'" data-grading_period_name="{{{$grading_period_name}}}" data-toggle="modal" data-target="#classrecordmodal"><span class="glyphicon glyphicon-file"></span></button>')
              ->add_column('ecr','<button class="btn btn-sm btn-danger active" data-id="{{{$id}}}" data-class_id="'.$class_id.'" data-grading_period_name="{{{$grading_period_name}}}" data-toggle="modal" data-target="#editclassrecordmodal"><span class="glyphicon glyphicon-file"></span></button>')
              ->remove_column('id')
              ->make();
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function CSCDdata()
    {
      $class_id = \Input::get('class_id');
      $grading_period_id = \Input::get('grading_period_id');
      $class_standing_component_list = ClassStandingComponent::join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
              ->join('grading_period','class_standing_component.grading_period_id','=','grading_period.id')
              ->where('class_standing_component.class_id','=',$class_id)
              ->where('class_standing_component.grading_period_id','=',$grading_period_id)
              ->select(array('class_standing_component.id','class_component_category.class_component_category_name','class_standing_component.component_weight','class_standing_component.class_id','grading_period.grading_period_name'))
              ->orderBy('class_standing_component.id');  
      
      return Datatables::of($class_standing_component_list)
              ->editColumn('component_weight','{{{ $component_weight."%"}}}')
              ->make(true);
    }


}
