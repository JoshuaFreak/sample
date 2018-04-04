<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Program;
use App\Models\Section;
use App\Models\SectionMonitor;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SectionController extends BaseController {
   

    public function dataJson(){
      // $condition = \Input::all();

      $is_sped = \Input::get('is_sped');
      $classification_level_id = \Input::get('classification_level_id');
      $is_active = \Input::get('is_active');

      if($is_sped != "" && $is_sped != 0)
      {
          $section = Section::join('classification','section.classification_id','=','classification.id')
                    ->where('classification.classification_name','Resource')
                    ->where('section.is_active',$is_active)
                    ->select([ 'section.id as value','section.section_name as text'])->get();
      }
      else
      {
           $section = Section::join('classification','section.classification_id','=','classification.id')
                    ->where('section.classification_level_id',$classification_level_id)
                    ->where('section.is_active',$is_active)
                    ->select([ 'section.id as value','section.section_name as text'])->get();
      }

      return response()->json($section);
    }

    public function dataJsonMonitor(){
      $condition = \Input::all();
      $section_monitor = SectionMonitor::lists('section_id');
      $query = Section::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value)
              ->whereNotIn('id', $section_monitor);
      }
      $section = $query->select([ 'id as value','section_name as text'])->get();

      return response()->json($section);
    }

    public function dataJsonMonitorSelect(){
      $condition = \Input::all();
      $section_monitor = SectionMonitor::lists('section_id');
      $query = Section::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $section = $query->select([ 'id as value','section_name as text'])->get();

      return response()->json($section);
    }

    public function scheduleDataJson(){

      $condition = \Input::all();
      $classification_level_id = \Input::get('classification_level_id');
      $query = Section::join('classification_level','section.classification_level_id','=','classification_level.id')->select();

      if($classification_level_id == 0)
      {
          $query->where('classification_level_id', '!=', 0);
      }
      else
      {
        foreach($condition as $column => $value)
        {
          $query->where($column, '=', $value);
        }
      }
      
      $program = $query->select([ 'section.id','section_name as name','classification_level.level as level'])->get();

      return response()->json($program);
    }

}
