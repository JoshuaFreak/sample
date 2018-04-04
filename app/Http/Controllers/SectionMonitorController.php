<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Section;
use App\Models\SectionMonitor;
use App\Models\Term;
use App\Http\Requests\SectionMonitorRequest;
use App\Http\Requests\SectionMonitorEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SectionMonitorController extends DeansPortalMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        // Show the page
        return view('section_monitor.index_adviser');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $section_list = Section::all();
        $term_list = Term::all();
        return view('section_monitor.create_adviser', compact('classification_list','classification_level_list','section_list','term_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(SectionMonitorRequest $section_monitor_request) {

        $section_monitor = new SectionMonitor();
        $section_monitor ->section_id = $section_monitor_request->section_id;
        $section_monitor ->employee_id = $section_monitor_request->employee_id;
        $section_monitor ->classification_level_id = $section_monitor_request->classification_level_id;
        $section_monitor ->classification_id = $section_monitor_request->classification_id;
        $section_monitor ->term_id = $section_monitor_request->term_id;
        $section_monitor ->created_by_id = Auth::id();
        $section_monitor -> save();

        $query = Section::find($section_monitor->section_id);
        $success = \Lang::get('section_monitor.create_success_adviser').'  '.$query->section_name ;
        return redirect('section_monitor/create_adviser')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $action = 1;
        $section_monitor = SectionMonitor::find($id);
        $employee = Employee::find($section_monitor->employee_id);
        $person = Person::find($employee->person_id);
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $section_list = Section::all();
        $term_list = Term::all();
        return view('section_monitor/edit_adviser',compact('section_monitor','employee', 'person', 'classification_level_list', 'classification_list', 'section_list', 'term_list','action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(SectionMonitorEditRequest $section_monitor_edit_request, $id) {
      
        $section_monitor = SectionMonitor::find($id);
        $section_monitor ->employee_id = $section_monitor_edit_request->employee_id;
        $section_monitor ->classification_id = $section_monitor_edit_request->classification_id;
        $section_monitor ->classification_level_id = $section_monitor_edit_request->classification_level_id;
        $section_monitor ->section_id = $section_monitor_edit_request->section_id;
        $section_monitor ->term_id = $section_monitor_edit_request->term_id;
        $section_monitor ->updated_by_id = Auth::id();
        $section_monitor -> save();

        return redirect('section_monitor');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $action = 1;
        $section_monitor = SectionMonitor::find($id);
        $employee = Employee::find($section_monitor->employee_id);
        $person = Person::find($employee->person_id);
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $section_list = Section::all();
        $term_list = Term::all();
        // Show the page
        return view('section_monitor/delete_adviser', compact('section_monitor', 'employee', 'person', 'classification_list', 'classification_level_list', 'section_list', 'term_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $section_monitor = SectionMonitor::find($id);
        $section_monitor->delete();
        return redirect('section_monitor');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $section_monitor_list = SectionMonitor::join('section', 'section_monitor.section_id', '=', 'section.id')
        ->join('term', 'section_monitor.term_id', '=', 'term.id')
        ->join('classification_level', 'section_monitor.classification_level_id', '=', 'classification_level.id')
        ->leftJoin('employee', 'section_monitor.employee_id', '=', 'employee.id')
        ->leftJoin('person', 'employee.person_id', '=', 'person.id')
        ->select(array('section_monitor.id', 'person.first_name','person.middle_name','person.last_name', 'classification_level.level','section.section_name', 'term.term_name'))
        ->orderBy('section_monitor.id', 'ASC');
    
        return Datatables::of( $section_monitor_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'section_monitor/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'section_monitor/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name)) }}')
            ->remove_column('id','middle_name','last_name','suffix_name')
            ->make();
    }

}
