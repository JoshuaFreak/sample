<?php namespace App\Http\Controllers;

use App\Http\Controllers\EnrollmentMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Program;
use App\Models\Section;
use App\Http\Requests\SectionRequest;
use App\Http\Requests\SectionEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SectionController extends EnrollmentMainController {
   

    // public function dataJson(){
    //   $condition = \Input::all();
    //   $query = Section::select();
    //   foreach($condition as $column => $value)
    //   {
    //     $query->where($column, '=', $value);
    //   }
    //   $section = $query->select([ 'id as value','section_name as text'])->get();

    //   return response()->json($section);
    // }

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
       
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $program_list = Program::where('classification_id',5)->orderBy('program.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();

        // Show the page
        return view('section.index', compact('classification_list', 'program_list', 'classification_level_list'));
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
        $program_list = Program::where('program.classification_id','=',5)->get();
        return view('section.create',compact('action','classification_list','classification_level_list','program_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(SectionRequest $section_request) {

        $section = new section();
        $section -> section_name = $section_request->section_name;
        if($section_request->program_id != null)
        {
            $section -> program_id = $section_request->program_id;
        }
        $section -> classification_id = $section_request->classification_id;
        $section -> classification_level_id = $section_request->classification_level_id;
        $section -> is_active = $section_request->is_active;
        $section -> created_by_id = Auth::id();
        $section -> save();

        $success = \Lang::get('section.create_success').' : '.$section->section_name ; 
        return redirect('section/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $action = 1;
        $section = Section::find($id);
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $program_list = Program::where('program.classification_id','=',5)->get();
        return view('section.edit',compact('section','action','classification_list','classification_level_list','program_list'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(SectionEditRequest $section_edit_request, $id) {
      
        $section = section::find($id);
        $section -> section_name = $section_edit_request->section_name;
        $section -> classification_id = $section_edit_request->classification_id;
        $section -> classification_level_id = $section_edit_request->classification_level_id;
        $section -> is_active = $section_edit_request->is_active;
        $section -> updated_by_id = Auth::id();
        $section -> save();

        return redirect('section');
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
        $section = Section::find($id);
        $classification_list = Classification::all();
        $classification_level_list = ClassificationLevel::all();
        $program_list = Program::where('program.classification_id','=',5)->get();
        return view('section.delete',compact('section','action','classification_list','classification_level_list','program_list'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $section = Section::find($id);
        $section->delete();
        return redirect('section');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {   
        $classification_id = \Input::get('classification_id');
        $program_id = \Input::get('program_id');
        $classification_level_id = \Input::get('classification_level_id');

        if($classification_id != "" && $classification_id != null && $classification_level_id != "" && $classification_level_id != null) 
        {
            $section_list = Section::join('classification','section.classification_id','=','classification.id')
                ->join('classification_level','section.classification_level_id','=','classification_level.id')
                ->join('program','section.program_id','=','program.id')
                ->select(array('section.id', 'section.section_name','classification.classification_name','classification_level.level', 'section.is_active'))
                ->where('section.classification_id','=',$classification_id)
                // ->where('section.program_id','=',$program_id)
                ->where('section.classification_level_id','=',$classification_level_id)
                ->orderBy('section.created_at', 'DESC');
        }
        elseif($classification_id != "" && $classification_id !=null) 
        {
            $section_list = Section::join('classification','section.classification_id','=','classification.id')
                ->join('classification_level','section.classification_level_id','=','classification_level.id')
                ->join('program','section.program_id','=','program.id')
                ->select(array('section.id', 'section.section_name','classification.classification_name','classification_level.level', 'section.is_active'))
                ->where('section.classification_id','=',$classification_id)
                // ->where('section.program_id','=',$program_id)
                ->orderBy('section.created_at', 'DESC');
        }
        elseif($classification_level_id != "" && $classification_level_id !=null) 
        {
            $section_list = Section::join('classification','section.classification_id','=','classification.id')
                ->join('classification_level','section.classification_level_id','=','classification_level.id')
                ->join('program','section.program_id','=','program.id')
                ->select(array('section.id', 'section.section_name','classification.classification_name','classification_level.level', 'section.is_active'))
                ->where('section.classification_level_id','=',$classification_level_id)
                // ->where('section.program_id','=',$program_id)
                ->orderBy('section.created_at', 'DESC');
        }
        // elseif($classification_id != "" && $classification_id != null && $classification_level_id != "" && $classification_level_id != null) 
        // {
        //     $section_list = Section::join('classification','section.classification_id','=','classification.id')
        //         ->join('program','section.program_id','=','program.id')
        //         ->join('classification_level','section.classification_level_id','=','classification_level.id')
        //         ->select(array('section.id', 'section.section_name','classification.classification_name','classification_level.level', 'section.is_active'))
        //         ->where('section.classification_id','=',$classification_id)
        //         ->where('section.classification_level_id','=',$classification_level_id)
        //         ->orderBy('section.created_at', 'DESC');
        // }
        else
        {
            $section_list = Section::join('classification','section.classification_id','=','classification.id')
                ->join('program','section.program_id','=','program.id')
                ->join('classification_level','section.classification_level_id','=','classification_level.id')
                ->where('section.id', '!=', 0)
                ->select(array('section.id', 'section.section_name','classification.classification_name', 'classification_level.level','section.is_active'))
                // ->where('section.classification_id','=',$classification_id)
                ->orderBy('section.created_at', 'DESC');
        }

    
        return Datatables::of( $section_list)
            ->edit_column('is_active', '@if ($is_active=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
            ->add_column('actions', '<a href="{{{ URL::to(\'section/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'section/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')
            ->remove_column('id','program_id')
            ->make();
    }

}
