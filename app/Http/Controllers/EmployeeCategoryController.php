<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\EmployeeCategory;
use App\Http\Requests\EmployeeCategoryRequest;
use App\Http\Requests\EmployeeCategoryEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class EmployeeCategoryController extends SchedulerMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // Show the page
        return view('employee_category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        return view('employee_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(EmployeeCategoryRequest $employee_category_request) {

        $employee_category = new EmployeeCategory();
        $employee_category -> description = $employee_category_request->description;
        $employee_category -> created_by_id = Auth::id();
        $employee_category -> save();

        $success = \Lang::get('employee_category.create_success').' : '.$employee_category->description ; 
        return redirect('employee_category/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $employee_category = EmployeeCategory::find($id);
       //var_dump($its_customs_office);
        return view('employee_category/edit',compact('employee_category'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(EmployeeCategoryEditRequest $employee_category_edit_request, $id) {
      
        $employee_category = EmployeeCategory::find($id);
        $employee_category -> description = $employee_category_edit_request->description;
        $employee_category -> updated_by_id = Auth::id();
        $employee_category -> save();

        return redirect('employee_category');
    }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
     {
        $employee_category = EmployeeCategory::find($id);
        // Show the page
        return view('employee_category/delete', compact('employee_category'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $employee_category = EmployeeCategory::find($id);
        $employee_category->delete();
        return redirect('employee_category');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $employee_category_list = EmployeeCategory::select(array('employee_category.id', 'employee_category.description'))
        ->orderBy('employee_category.description', 'ASC');
    
        return Datatables::of( $employee_category_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'employee_category/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'employee_category/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }

}
