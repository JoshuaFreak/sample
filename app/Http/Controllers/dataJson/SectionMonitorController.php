<?php namespace App\Http\Controllers\dataJson;

use App\Http\Controllers\BaseController;
use App\Models\Program;
use App\Models\Section;
use App\Models\SectionMonitor;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SectionMonitorController extends BaseController {
   

     public function dataJson(){
          $condition = \Input::all();
          $query = SectionMonitor::join('section','section_monitor.section_id','=','section.id');

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          // $trainee_requirement = $query->select([ 'id as value','description as text'])->get();
          $section_monitor = $query->select(['section.section_name'])->get();

          return response()->json($section_monitor);
   
    }

}
