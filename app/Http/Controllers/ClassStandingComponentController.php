<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ClassComponentCategory;
use App\Models\ClassStandingComponent;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ClassStandingComponentController extends TeachersPortalMainController {

    public function TEpostCreate(){

        $detail = \Input::get('detail');
        $percentage = \Input::get('percentage');
        $class_id = \Input::get('class_id');
        $grading_period_id = \Input::get('grading_period_id');

        $class_component_category = ClassComponentCategory::where('class_component_category_name',$detail)->where('education_category_id',2)->select(['id'])->get()->last();

        $class_standing_component = new ClassStandingComponent();
        $class_standing_component ->class_id = $class_id;
        $class_standing_component ->grading_period_id = $grading_period_id;
        $class_standing_component ->class_component_category_id = $class_component_category -> id;
        $class_standing_component ->component_weight = $percentage;
        $class_standing_component ->save();

        return response()->json($class_standing_component);

    }
    public function BEpostCreate(){

        $detail = \Input::get('detail');
        $percentage = \Input::get('percentage');
        $class_id = \Input::get('class_id');
        $grading_period_id = \Input::get('grading_period_id');

        $class_component_category = ClassComponentCategory::where('class_component_category_name',$detail)->where('education_category_id',1)->select(['id'])->get()->last();

        $class_standing_component = new ClassStandingComponent();
        $class_standing_component ->class_id = $class_id;
        $class_standing_component ->grading_period_id = $grading_period_id;
        $class_standing_component ->class_component_category_id = $class_component_category->id;
        $class_standing_component ->component_weight = $percentage;
        $class_standing_component ->save();

        return response()->json($class_standing_component);

    }

    public function BEpostDelete()
    {
        $id = \Input::get('id');
        $class_standing_component = ClassStandingComponent::find($id);
        $class_standing_component -> delete();

        return response()->json($class_standing_component);        
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request)
    {
        $class_standing_component = ClassStandingComponent::find($request->id);
        $class_standing_component->delete();

       return response()->json();
    }
    

}
