<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\File;
use App\Models\Term;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Folder;
use App\Models\GradingPeriod;
use App\Models\ActionTaken;
use App\Models\StudentDocument;
use App\Http\Requests\FolderRequest;
use App\Http\Requests\FolderEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentDocumentController extends TeachersPortalMainController {   
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $term_list = Term::orderBy('term.term_name','ASC')->get();
        $action_taken_list = ActionTaken::orderBy('action_taken.id','ASC')->get();
        $grading_period_list = GradingPeriod::orderBy('grading_period.id','ASC')->get();
        $groupby_grading_period_list = GradingPeriod::groupBy('grading_period_name')->orderBy('grading_period.id','ASC')->get();
        return view('teachers_portal/class_advisory.index', compact('classification_list', 'classification_level_list', 'term_list', 'action_taken_list', 'grading_period_list', 'groupby_grading_period_list'));
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function StudentListdata()
    {
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');

        
        $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('student','student_curriculum.student_id','=','student.id')
            ->join('person','student.person_id','=','person.id')
            ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->join('classification','classification_level.classification_id','=','classification.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('term','enrollment.term_id','=','term.id')
            ->where('enrollment.classification_level_id','=',$classification_level_id)
            ->where('enrollment.term_id','=',$term_id)
            ->select(array('student.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.birthdate','student_curriculum.student_id','classification_level.level','section.section_name','term.term_name','classification.classification_name','enrollment.term_id', 'enrollment.section_id','classification_level.classification_id','enrollment.classification_level_id','student_curriculum.curriculum_id'))
            ->orderBy('person.last_name', 'ASC');

        return Datatables::of($enrollment_list)
            ->add_column('document_management', '<button class="btn btn-sm btn-mzed" data-id="{{{$id}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}"   data-classification_level_id="{{{$classification_level_id}}}"  data-toggle="modal" data-target="#uploadDocumentModal"><span class="glyphicon glyphicon-upload"></span> {{ Lang::get("form.upload") }}</button>&nbsp;&nbsp;
                <p class="details-control btn btn-sm btn-danger"><span class="glyphicon glyphicon-folder-open"></span> {{ Lang::get("form.view_file") }}</p>')
            ->editColumn('last_name','{{ ucwords(strtolower($last_name.", ".$first_name." ".$middle_name)) }}')
            ->remove_column('first_name', 'middle_name', 'birthdate', 'student_id', 'level', 'section_name', 'term_name', 'term_id', 'section_id', 'classification_id', 'classification_name','classification_level_id', 'curriculum_id')
            ->make(true);

    }

    public function CreateFolderdataJson() {

      $folder_id = \Input::get('folder_id');
      $student_id = \Input::get('student_id');

      $query = Folder::join('student_document', 'student_document.folder_id', '=', 'folder.id')
            ->where('student_document.student_id', '=', $student_id)
            ->where('folder.parent_folder_id', '=', $folder_id)
            ->select(array('folder.id','folder.folder_name', 'folder.parent_folder_id', 'student_document.student_id'));

      $folder = $query->get();

      return response()->json($folder);
        
    }

    public function StudentDocumentJson() {

      $folder_id = \Input::get('folder_id');
      $student_id = \Input::get('student_id');

      $query = Folder::join('student_document', 'student_document.folder_id', '=', 'folder.id')
            ->where('student_document.student_id', '=', $student_id)
            ->where('folder.parent_folder_id', '!=', 1)
            ->select(array('folder.id','folder.folder_name', 'folder.parent_folder_id', 'student_document.student_id'));

      $folder = $query->get();

      return response()->json($folder);
        
    }

   

    public function studentFolderDataJson() {

      $folder_id = \Input::get('folder_id');

      $query = Folder::where('folder.parent_folder_id', '=', $folder_id)
            ->select(['folder.id','folder.folder_name', 'folder.parent_folder_id'])->get();

      return response()->json($query);
        
    }

    public function postUpdateFolder() {

        $id=\Input::get('id');
        $folder_name=\Input::get('folder_name');
        $student_id=\Input::get('student_id');
        $classification_level_id=\Input::get('classification_level_id');
        $term_id=\Input::get('term_id');

        $employee = Employee::where('employee_no','=',Auth::user()->username)->get()->last();
        // $directory_self = dirname(__FILE__);

        // mkdir($directory_self."files/".$folder_name);
        // mkdir('/files/'.$folder_name);
        // mkdir("mczs-gakkou.com/storage/files/".$folder_name."/".$student_document);
        mkdir('../../public_html/storage/files/'.$folder_name);
        //for local
        // mkdir("../../git-mzed/storage/files/".$folder_name);
        // mkdir('storage/files/'.$folder_name);

        // exit();

  
        if ($id == null){

          $folder = new Folder();
          $folder->folder_name = $folder_name;
          $folder->parent_folder_id = 1;
          $folder->save();

          $student_document = new StudentDocument();
          $student_document->student_id = $student_id;
          $student_document->folder_id = $folder->id;
          $student_document->teacher_id = $employee->id;
          $student_document->term_id = $term_id;
          $student_document->classification_level_id = $classification_level_id;
          $student_document->save();

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

        $student_document=\Input::get('folder_name');
        $student_id=\Input::get('student_id');
        $parent_folder_id=\Input::get('parent_folder_id');

        $parent_folder = Folder::find($parent_folder_id);
        $main_folder = $parent_folder->folder_name;
        $employee = Employee::where('employee_no','=',Auth::user()->username)->get()->last();
        $term_id=\Input::get('term_id');
        $classification_level_id=\Input::get('classification_level_id');

        mkdir("../../public_html/storage/files/".$main_folder."/".$student_document);


        // mkdir("c:/wamp/www/git-mzed/storage/files/".$main_folder."/".$student_document);
       
        //FOR LOCAL
        // mkdir('../../git-mzed/storage/files/'.$main_folder.'/'.$student_document);

        // mkdir('storage/files/'.$main_folder.'/'.$student_document);

        $folder = new Folder();
        $folder->folder_name = $student_document;
        $folder->parent_folder_id = $parent_folder_id;
        $folder->save();
    
        $student_document = new StudentDocument();
        $student_document->student_id = $student_id;
        $student_document->folder_id = $folder->id;
        $student_document->teacher_id = $employee->id;
        $student_document->term_id = $term_id;
        $student_document->classification_level_id = $classification_level_id;
        $student_document->save();

        return response()->json($folder);
    }

   public function fileDataJson(){

      $folder_id = \Input::get('folder_id');
      $student_id = \Input::get('student_id');

      $query = File::join('folder', 'file.folder_id', '=', 'folder.id')
            ->join('student_document', 'student_document.folder_id', '=', 'folder.id')
            ->where('student_document.student_id', '=', $student_id)
            ->where('file.folder_id', '=', $folder_id)
            ->select(array('file.id','file.file_name'));

      $file = $query->get();

      return response()->json($file);
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

    public function removeFile()
    {
        $id = \Input::get('id');

        $file = File::find($id);
        $file -> delete();

        return response()->json($file);
    }



}
