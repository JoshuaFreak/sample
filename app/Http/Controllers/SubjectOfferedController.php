<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\File;
use App\Models\Folder;
use App\Models\Program;
use App\Models\Subject;
use App\Models\SubjectMaterial;
use App\Models\SubjectOffered;
use App\Models\Term;
use App\Http\Requests\SubjectOfferedRequest;
use App\Http\Requests\SubjectOfferedEditRequest;
use App\Http\Requests\FolderRequest;
use App\Http\Requests\FolderEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;
use Input;
use Validator;
use Redirect;
use Request;
use Session;

class SubjectOfferedController extends DeansPortalMainController {
   
    public function index()
    {
       
         $classification_list = Classification::all();

        return view('subject_offered/index',compact('classification_list'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function getCreate() {

        $action = 0;
        $classification_list = Classification::all();
        $program_list = Program::all();
        $term_list = Term::all();
        $subject_list = Subject::all();
        $subject_offered_list = array();
        return view('subject_offered.create', compact('classification_list','classification_list','program_list','term_list','subject_list','subject_offered_list', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(SubjectOfferedRequest $subject_offered_request) {

        foreach($subject_offered_request->subject as $item)
        {
            $subject_offered = new SubjectOffered();


            $subject_offered->subject_id = $item;
            // $subject_offered ->program_id = $subject_offered_request->program_id;
            $subject_offered ->classification_id = $subject_offered_request->classification_id;
            $subject_offered ->classification_level_id = $subject_offered_request->classification_level_id;
            $subject_offered ->term_id = $subject_offered_request->term_id;
            $subject_offered ->created_by_id = Auth::id();
            $subject_offered -> save();
     
        }

        // $query = Program::find($subject_offered->program_id);
        $success = \Lang::get('subject_offered.create_success').'  '.$subject_offered->name ; 
        return redirect('subject_offered/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $action = 1;
        $subject_offered = SubjectOffered::find($id);
        $classification_list = Classification::all();        
        $classification_level_list = ClassificationLevel::all();        
        $program_list = Program::all();
        $term_list = Term::all();
        $subject_list = Subject::all();
        $subject_offered_list = SubjectOffered::lists('subject_id');
        return view('subject_offered.edit', compact('subject_offered','classification_list','classification_level_list','program_list','term_list','subject_list','subject_offered_list', 'action'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    public function postEdit(SubjectOfferedEditRequest $subject_offered_request, $id) {
      
        $subject_offered = SubjectOffered::find($id);
        foreach($subject_offered_request->subject as $item)
        {
            $subject_offered = new SubjectOffered();


            $subject_offered->subject_id = $item;
            $subject_offered ->program_id = $subject_offered_request->program_id;
            $subject_offered ->term_id = $subject_offered_request->term_id;
            $subject_offered ->created_by_id = Auth::id();
            $subject_offered -> save();

     
        }
        return redirect('subject_offered');
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
        $subject_offered = SubjectOffered::find($id);
        $classification_list = Classification::all(); 
        $classification_level_list = ClassificationLevel::all();         
        $program_list = Program::all();
        $term_list = Term::all();
        $subject_list = Subject::all();
        $subject_offered_list = SubjectOffered::lists('subject_id');
        return view('subject_offered.delete', compact('subject_offered','classification_list','classification_level_list','program_list','term_list','subject_list','subject_offered_list', 'action'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $subject_offered = SubjectOffered::find($id);
        $subject_offered->delete();
        return redirect('subject_offered');
    }

    public function postMultipleUpload(FolderRequest $folder_request) {

          $file = addslashes(file_get_contents($_FILES['file']['tmp_name']));
          $file_name = addslashes($_FILES['file']['name']);
          $file_size = getimagesize($_FILES['file']['tmp_name']);
          $main_folder_id = \Input::get('main_folder_id');
          $folder_id = \Input::get('folder_id');

          $main_folder = Folder::find($main_folder_id);
          $folder = Folder::find($folder_id);


          move_uploaded_file($_FILES["file"]["tmp_name"], "../storage/files/".$main_folder->folder_name."/".$folder->folder_name."/". $_FILES["file"]["name"]);

          $location = "/files/".$main_folder->folder_name."/".$folder->folder_name."/";

          $new_file = new File();
          $new_file -> file_name = $_FILES["file"]["name"];
          $new_file -> folder_id = \Input::get('folder_id');
          $new_file -> path = $location;
          $new_file -> save();

          return response()->json($new_file);
    }

    public function downloadFile($id) {

        $entry = File::where('file.id', '=', $id)->firstOrFail();

        $pathToFile=storage_path().$entry->path.$entry->file_name;

        return response()->download($pathToFile);           
    } 

    public function SubjectMaterialdataJson() {

      $condition = \Input::all();
      $query = Folder::select(array('folder.id','folder.folder_name', 'folder.parent_folder_id'));
      
        foreach($condition as $column => $value)
      {
        if($column == 'query')
        {
          $query->where('folder_name', 'LIKE', "%$value%")
                ->orWhere('id', 'LIKE', "%$value%");  
        }
        else
        {
          $query->where($column, '=', $value);
        }
      }

      $folder = $query->get();

      return response()->json($folder);
        
    }

    public function CreateFolderdataJson() {

      $folder_id = \Input::get('folder_id');
      $subject_offered_id = \Input::get('subject_offered_id');

      $query = Folder::join('subject_material', 'subject_material.folder_id', '=', 'folder.id')
            ->where('subject_material.subject_offered_id', '=', $subject_offered_id)
            ->where('folder.parent_folder_id', '=', $folder_id)
            ->select(array('folder.id','folder.folder_name', 'folder.parent_folder_id', 'subject_material.subject_offered_id'));

      $folder = $query->get();

      return response()->json($folder);
        
    }

    public function SubjectMaterialJson() {

      $folder_id = \Input::get('folder_id');
      $subject_offered_id = \Input::get('subject_offered_id');

      $query = Folder::join('subject_material', 'subject_material.folder_id', '=', 'folder.id')
            ->where('subject_material.subject_offered_id', '=', $subject_offered_id)
            ->where('folder.parent_folder_id', '!=', 1)
            ->select(array('folder.id','folder.folder_name', 'folder.parent_folder_id', 'subject_material.subject_offered_id'));

      $folder = $query->get();

      return response()->json($folder);
        
    }

    public function subjectFolderDataJson() {

      $folder_id = \Input::get('folder_id');

      $query = Folder::where('folder.parent_folder_id', '=', $folder_id)
            ->select(['folder.id','folder.folder_name', 'folder.parent_folder_id'])->get();

      return response()->json($query);
        
    }

    public function postUpdate() {

        $id=\Input::get('id');
        $folder_name=\Input::get('folder_name');
        $subject_offered_id=\Input::get('subject_offered_id');

        mkdir("c:/wamp/www/genericschool/storage/files/".$folder_name);
  
        if ($id == null){

          $folder = new Folder();
          $folder->folder_name = $folder_name;
          $folder->parent_folder_id = 1;
          $folder->save();

          $subject_material = new SubjectMaterial();
          $subject_material->subject_offered_id = $subject_offered_id;
          $subject_material->folder_id = $folder->id;
          $subject_material->save();

        }
        else
        {
          $folder = Folder::find($id);
          $folder->folder_name = $folder_name;
          $folder->save();


        }


      return response()->json($folder);

    }
    public function studentDocumentpostUpdate() {

        $subject_material=\Input::get('folder_name');
        $subject_offered_id=\Input::get('subject_offered_id');
        $parent_folder_id=\Input::get('parent_folder_id');

        $parent_folder = Folder::find($parent_folder_id);
        $main_folder = $parent_folder->folder_name;

        mkdir("c:/wamp/www/genericschool/storage/files/".$main_folder."/".$subject_material);

        $folder = new Folder();
        $folder->folder_name = $subject_material;
        $folder->parent_folder_id = $parent_folder_id;
        $folder->save();
    
        $subject_material = new SubjectMaterial();
        $subject_material->subject_offered_id = $subject_offered_id;
        $subject_material->folder_id = $folder->id;
        $subject_material->save();

        return response()->json($folder);
    }

   public function fileDataJson(){

      $folder_id = \Input::get('folder_id');
      $subject_offered_id = \Input::get('subject_offered_id');

      $query = File::join('folder', 'file.folder_id', '=', 'folder.id')
            ->join('subject_material', 'subject_material.folder_id', '=', 'folder.id')
            ->where('subject_material.subject_offered_id', '=', $subject_offered_id)
            ->where('file.folder_id', '=', $folder_id)
            ->select(array('file.id','file.file_name'));

      $file = $query->get();

      return response()->json($file);
    }
    
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {  
        $classification_id = \Input::get('classification_id');
        if($classification_id != "" && $classification_id != null) 
        {

          $subject_offered_list = SubjectOffered::join('classification','subject_offered.classification_id','=','classification.id')
                ->join('term', 'subject_offered.term_id', '=', 'term.id')
                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                ->where('subject_offered.classification_id','=',$classification_id)
                ->select(array('subject_offered.id', 'term.term_name','subject.code','subject.name'))
                ->orderBy('subject_offered.created_at', 'DESC');

        }
        elseif($classification_id != "" && $classification_id != null && $classification_id != 0)
        {

          $subject_offered_list = SubjectOffered::join('classification','subject_offered.classification_id','=','classification.id')
                ->join('term', 'subject_offered.term_id', '=', 'term.id')
                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                ->where('subject_offered.classification_id','=',$classification_id)
                ->select(array('subject_offered.id', 'term.term_name','subject.code','subject.name'))
                ->orderBy('subject_offered.created_at', 'DESC');
        }
    
        else
        {
            $subject_offered_list = SubjectOffered::join('classification','subject_offered.classification_id','=','classification.id')
                ->join('term', 'subject_offered.term_id', '=', 'term.id')
                ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
                ->where('subject_offered.classification_id','=',$classification_id)
                ->select(array('subject_offered.id', 'term.term_name','subject.code','subject.name'))
                ->orderBy('subject_offered.created_at', 'DESC');
        }
        return Datatables::of($subject_offered_list)
            // ->add_column('document_management', '<button class="btn btn-sm btn-success" data-id="{{{$id}}}" data-program_name="{{{$program_name}}}" data-name="{{{$name}}}" data-toggle="modal" data-target="#uploadModal"><span class="glyphicon glyphicon-upload"></span> {{ Lang::get("form.upload") }}</button>&nbsp;&nbsp;
            //     <p class="details-control btn btn-sm btn-success"><span class="glyphicon glyphicon-folder-open"></span> {{ Lang::get("form.view_file") }}</p>')
            ->add_column('actions', '<a href="{{{ URL::to(\'subject_offered/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>')
            // ->remove_column('id')
            ->make(true);
    }
}
