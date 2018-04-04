<?php namespace App\Http\Controllers;

use App\Http\Controllers\SchedulerMainController;
use App\Models\ClassComponentCategory;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\CurriculumSubject;
use App\Models\PercentageDistribution;
use App\Models\Subject;
use App\Http\Requests\PercentageDistributionRequest;
use App\Http\Requests\PercentageDistributionEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class PercentageDistributionController extends SchedulerMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $subject_list = Subject::where('is_pace','=',0)->orderBy('subject.id','ASC')->get();
        $class_component_category_list = ClassComponentCategory::where('is_active','=',1)->orderBy('class_component_category.id','ASC')->get();
        return view('percentage_distribution.index', compact('classification_list', 'subject_list','class_component_category_list'));
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
        $subject_list = Subject::all();
        $class_component_category_list = ClassComponentCategory::all();
        return view('percentage_distribution.create', compact('classification_list','subject_list','classification_level_list','class_component_category_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
public function postCreate() {

        $classification_id = \Input::get('classification_id');
        $detail_id = \Input::get('detail_id');
        $subject_id = \Input::get('subject_id');
        $percentage_val = \Input::get('percentage_val');

        $max_detail = sizeof($detail_id);
        

        json_decode(serialize($detail_id));
        json_decode(serialize($subject_id));
        json_decode(serialize($percentage_val));
        for($i= 0; $i < $max_detail; $i++){

        $percentage_distribution = new PercentageDistribution();
        $percentage_distribution ->class_component_category_id =$detail_id[$i];
        $percentage_distribution ->subject_id = $subject_id[$i];
        $percentage_distribution ->classification_id = $classification_id;
        $percentage_distribution ->percentage = $percentage_val[$i].'%';
        $percentage_distribution ->created_by_id = Auth::id();
        $percentage_distribution -> save();

        }

    // return response()->json($percentage_distribution);
        $query = Subject::find($percentage_distribution->subject_id);
        $success = \Lang::get('percentage_distribution.create_success').'  '.$query->name ;
        return redirect('percentage_distribution/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $action = 1;
        $classification_list = Classification::all();
        $subject_list = Subject::all();
        $class_component_category_list = ClassComponentCategory::all();
        $percentage_distribution = PercentageDistribution::find($id);
       //var_dump($its_customs_office);
        return view('percentage_distribution/edit',compact('percentage_distribution','classification_list','subject_list','class_component_category_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(PercentageDistributionEditRequest $percentage_distribution_edit_request, $id) {
      
        $percentage_distribution = PercentageDistribution::find($id);
        $percentage_distribution ->class_component_category_id = $percentage_distribution_edit_request->class_component_category_id;
        $percentage_distribution ->subject_id = $percentage_distribution_edit_request->subject_id;
        $percentage_distribution ->classification_id = $percentage_distribution_edit_request->classification_id;
        $percentage_distribution ->percentage = $percentage_distribution_edit_request->percentage;
        $percentage_distribution ->updated_by_id = Auth::id();
        $percentage_distribution -> save();

        return redirect('percentage_distribution');
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
        $classification_list = Classification::all();
        $subject_list = Subject::all();
        $class_component_category_list = ClassComponentCategory::all();
        $percentage_distribution = PercentageDistribution::find($id);
        
        // Show the page
        return view('percentage_distribution/delete', compact('percentage_distribution','classification_list','subject_list','class_component_category_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $percentage_distribution = PercentageDistribution::find($id);
        $percentage_distribution->delete();
        return redirect('percentage_distribution');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {

    
      $classification_id = \Input::get('classification_id');
      $subject_id = \Input::get('subject_id');
        if($classification_id != "" && $classification_id != null && $subject_id != "" && $subject_id != null) 
        {

          $percentage_distribution_list = PercentageDistribution::join('class_component_category', 'percentage_distribution.class_component_category_id', '=', 'class_component_category.id')
            ->join('subject', 'percentage_distribution.subject_id', '=','subject.id')
            ->where('percentage_distribution.classification_id','=',$classification_id)
            ->where('percentage_distribution.subject_id','=',$subject_id)
            ->select(array('percentage_distribution.id', 'subject.name','class_component_category.class_component_category_name', 'percentage_distribution.percentage'))           
            // ->groupBy('subject.name')
            ->orderBy('percentage_distribution.id', 'DESC');
        }
        elseif($classification_id != "" && $classification_id != null) 
        {

          $percentage_distribution_list = PercentageDistribution::join('class_component_category', 'percentage_distribution.class_component_category_id', '=', 'class_component_category.id')
            ->join('subject', 'percentage_distribution.subject_id', '=','subject.id')
            ->where('percentage_distribution.classification_id','=',$classification_id)
            ->select(array('percentage_distribution.id', 'subject.name','class_component_category.class_component_category_name', 'percentage_distribution.percentage'))     
            // ->groupBy('subject.name')
            ->orderBy('percentage_distribution.id', 'DESC');
        }
    
        else
        {
            $percentage_distribution_list = PercentageDistribution::join('class_component_category', 'percentage_distribution.class_component_category_id', '=', 'class_component_category.id')
            ->join('subject', 'percentage_distribution.subject_id', '=','subject.id')
            ->where('percentage_distribution.classification_id','=',$classification_id)
            ->select(array('percentage_distribution.id', 'subject.name','class_component_category.class_component_category_name', 'percentage_distribution.percentage'))
            // ->groupBy('subject.name')
            ->orderBy('percentage_distribution.id', 'DESC');
        }
    
        return Datatables::of( $percentage_distribution_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'percentage_distribution/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'percentage_distribution/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id')
            ->make();
    }
}
