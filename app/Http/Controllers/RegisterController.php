<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Agency;
use App\Models\Examination;
use App\Models\ExaminationType;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenUserRole;
use App\Models\Nationality;
use App\Models\Person;
use App\Models\Student;
use App\Models\StudentExamination;
use App\Models\StudentExaminationScore;
use App\Models\StudentProgram;
use App\Models\Program;
use App\Http\Requests\RegisterRequest;
// use App\Http\Requests\RoomEditRequest;
// use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;
use Input;
use Session;
use Redirect;
use Excel;

class RegisterController extends BaseController {
   

    //  public function dataJson(){
    //   $condition = \Input::all();
    //   $query = Register::select();
    //   foreach($condition as $column => $value)
    //   {
    //     $query->where($column, '=', $value);
    //   }
    //   $room = $query->select([ 'id as value','room_name as text'])->get();

    //   return response()->json($room);
    // }


    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        return view('register.index');
    }

    public function import()
    {
        return view('register.import');
    }

    public function importStudentScore()
    {
        $examination_list = Examination::get();
        $program_list = Program::select(array('program.id', 'program.program_name', 'program.program_color'))
                           ->orderBy('program.category', 'ASC')->get();

        $program_category_list = Program::leftJoin('program_category','program.program_category_id','=','program_category.id')
                    ->select(['program_category.id', 'program_category.program_category_name', 'program.program_color'])
                    ->orderBy('program.category', 'ASC')
                    ->groupBy('program_category.id')
                    ->get();

        return view('registrar.import_student_score',compact('examination_list','program_list','program_category_list'));
    }

    public function postImportStudentScore()
    {
        $examination_id = \Input::get('examination_id');
        $program_category_id = \Input::get('program_category_id');

        $file = Input::file('import_file');
        $extension = Input::file('import_file')->getClientOriginalExtension(); // getting image extension

        //check if valid excel file by checking the extension
        if($extension =="xls" || $extension=="xlsx"){
            $destination_path = 'uploads'; // upload path
            $original_file_name_with_extension = Input::file('import_file')->getClientOriginalName();
            $original_file_name_without_extension = pathinfo($original_file_name_with_extension, PATHINFO_FILENAME);
            $file_name = $original_file_name_without_extension.rand(11111,99999).'.'.$extension; // renameing image
            Input::file('import_file')->move($destination_path, $file_name); // uploading file to given path

            //print_r($this->migrateExcelToDB("uploads/".$file_name));

              $this->migrateExcelToDBScore("uploads/".$file_name,$examination_id,$program_category_id);

           Session::flash('success', 'Upload successfully');
          return Redirect::to('registrar/import_student_score');

        }
        if($file == "" && $file== null || $extension =="" && $extension ==null){
            Session::flash('error', 'is_uploaded_file(filename) file is not valid');
            return Redirect::to('registrar/import_student_score');

        }
        else{
            Session::flash('error', 'is_uploaded_file(filename) file is not valid');
             return Redirect::to('registrar/import_student_score');
        }

    }

    public function studentList()
    {
        return view('register.student_list');
    }

    //this function will accept an excel file from upload

    public function postImport(){

      $file = Input::file('import_file');
      $extension = Input::file('import_file')->getClientOriginalExtension(); // getting image extension

      //check if valid excel file by checking the extension
      if($extension =="xls" || $extension=="xlsx"){
        $destination_path = 'uploads'; // upload path
        $original_file_name_with_extension = Input::file('import_file')->getClientOriginalName();
        $original_file_name_without_extension = pathinfo($original_file_name_with_extension, PATHINFO_FILENAME);
        $file_name = $original_file_name_without_extension.rand(11111,99999).'.'.$extension; // renameing image
        Input::file('import_file')->move($destination_path, $file_name); // uploading file to given path

        //print_r($this->migrateExcelToDB("uploads/".$file_name));

          $this->migrateExcelToDB("uploads/".$file_name);

       Session::flash('success', 'Upload successfully');
      return Redirect::to('register/import');

      }
      if($file == "" && $file== null || $extension =="" && $extension ==null){
        Session::flash('error', 'is_uploaded_file(filename) file is not valid');
        return Redirect::to('register/import');

      }


      else{
        Session::flash('error', 'is_uploaded_file(filename) file is not valid');
         return Redirect::to('register/import');
      }

    }

   //will try to extract rows and map columnns with the database table - import_entry
    private function migrateExcelToDB($file_name)
    {

        $import_list = Excel::selectSheetsByIndex(0)->load($file_name, function($reader){})->get()->toArray();

        foreach ($import_list as $import) {

          // this is now the process or inserting / updating import entry record - if awb_hbl_no exists
            $person_data = $this->getPerson($import["name"]);

            //insert to db
            $count = 0;
            $nationality_id = 0;
            if($person_data == null)
            {
                $nationality = Nationality::where('nationality_name',$import['cty'])->get()->last();

                if($nationality != null)
                {
                    $nationality_id = $nationality -> id;
                }
                else
                {
                    $nationality = new Nationality();
                    $nationality -> nationality_name = $import['cty'];
                    $nationality -> is_active = 1;
                    $nationality -> save();
                    $nationality_id = $nationality -> id;
                }

                $person = new Person();
                $count++;
            }
            else
            {
                $person = Person::find($person_data -> id);
            }

            $person -> first_name = $import["name"];
            $person -> nationality_id = $nationality_id;
            $person -> student_english_name = $import["eng_name"];
            $person -> age = $import["age"];
            $person -> is_active = 1;
            $person -> save();

            if($count != 0)
            {

                $gen_user = new GenUser();
                $gen_user -> person_id = $person->id;
                $gen_user -> username = $import["id_no"];
                $gen_user -> password = \Hash::make($import["id_no"]);
                $gen_user -> save();

                $gen_user_role = new GenUserRole();
                $gen_user_role -> gen_user_id = $gen_user->id;
                $gen_user_role -> gen_role_id = 6;
                $gen_user_role -> save();

                $student = new Student();
                $student -> id_no = $import["id_no"];
                $student -> person_id = $person-> id;
                $student -> is_active = 1;
                $student -> save();

                $student_program = new StudentProgram();
                $student_program -> student_id = $student->id;
                $student_program -> program_id = 2;
                $student_program -> date_enrolled = $import["date"];
                $student_program -> save();

                $student_examination = new StudentExamination();
                $student_examination -> student_id = $student->id;
                $student_examination -> examination_id = 1;
                $student_examination -> date_enroll = $import["date"];
                $student_examination -> save();
            }
   
          }

    }

    private function migrateExcelToDBScore($file_name,$examination_id,$program_category_id)
    {

        $listening_excel = Excel::selectSheetsByIndex(0)->load($file_name)->get(['listening'])->last();
        $voca_grammar_excel = Excel::selectSheetsByIndex(0)->load($file_name)->get(['voca_grammar'])->last();
        $reading_excel = Excel::selectSheetsByIndex(0)->load($file_name)->get(['reading'])->last();
        $writing_excel = Excel::selectSheetsByIndex(0)->load($file_name)->get(['writing'])->last();
        $speaking_excel = Excel::selectSheetsByIndex(0)->load($file_name)->get(['speaking'])->last();

        $listening_excel = sizeof($listening_excel);
        $voca_grammar_excel = sizeof($voca_grammar_excel);
        $reading_excel = sizeof($reading_excel);
        $writing_excel = sizeof($writing_excel);
        $speaking_excel = sizeof($speaking_excel);

        $import_list = Excel::selectSheetsByIndex(0)->load($file_name, function($reader){})->get()->toArray();
        // $import_list->skipRows(1); 

        foreach ($import_list as $import) {

          // this is now the process or inserting / updating import entry record - if awb_hbl_no exists

            $id_number = $import["id_number"];
            if($listening_excel != 0)
            {
                $listening = $import["listening"];
            }
            if($voca_grammar_excel != 0)
            {
                $voca_grammar = $import["voca_grammar"];
            }
            if($reading_excel != 0)
            {
                $reading = $import["reading"];
            }
            if($writing_excel != 0)
            {
                $writing = $import["writing"];
            }
            if($speaking_excel != 0)
            {
                $speaking = $import["speaking"];
            }

            $examination_type_list = ExaminationType::get();

            $count = StudentExaminationScore::where('student_id',$id_number)
                                ->where('examination_id',$examination_id)
                                ->where('program_category_id',$program_category_id)
                                ->count();

            if($count == 0)
            {
                foreach ($examination_type_list as $examination_type) {


                
                    if($examination_type -> id == 1 && $listening_excel != 0){

                        if($listening != "" && $id_number != "")
                        {
                                $student_examination_score = new StudentExaminationScore();
                                $student_examination_score -> student_id = $id_number;
                                $student_examination_score -> examination_id = $examination_id;
                                $student_examination_score -> program_category_id = $program_category_id;
                                $student_examination_score -> examination_type_id = $examination_type -> id;
                                $student_examination_score -> score = $listening;
                                $student_examination_score -> save();
                            
                        }
                    }
                    if($examination_type -> id == 2 && $voca_grammar_excel != 0){

                        if($voca_grammar != "" && $id_number != "")
                        {
                            $student_examination_score = new StudentExaminationScore();
                            $student_examination_score -> student_id = $id_number;
                            $student_examination_score -> examination_id = $examination_id;
                            $student_examination_score -> program_category_id = $program_category_id;
                            $student_examination_score -> examination_type_id = $examination_type -> id;
                            $student_examination_score -> score = $voca_grammar;
                            $student_examination_score -> save();
                        }
                    }
                    if($examination_type -> id == 3 && $reading_excel != 0){

                        if($reading != "" && $id_number != "")
                        {
                            $student_examination_score = new StudentExaminationScore();
                            $student_examination_score -> student_id = $id_number;
                            $student_examination_score -> examination_id = $examination_id;
                            $student_examination_score -> program_category_id = $program_category_id;
                            $student_examination_score -> examination_type_id = $examination_type -> id;
                            $student_examination_score -> score = $reading;
                            $student_examination_score -> save();
                        }
                    }
                    if($examination_type -> id == 4 && $writing_excel != 0){

                        if($writing != "" && $id_number != "")
                        {
                            $student_examination_score = new StudentExaminationScore();
                            $student_examination_score -> student_id = $id_number;
                            $student_examination_score -> examination_id = $examination_id;
                            $student_examination_score -> program_category_id = $program_category_id;
                            $student_examination_score -> examination_type_id = $examination_type -> id;
                            $student_examination_score -> score = $writing;
                            $student_examination_score -> save();
                        }
                    }
                    if($examination_type -> id == 5 && $speaking_excel != 0){

                        if($speaking != "" && $id_number != "")
                        {
                            $student_examination_score = new StudentExaminationScore();
                            $student_examination_score -> student_id = $id_number;
                            $student_examination_score -> examination_id = $examination_id;
                            $student_examination_score -> program_category_id = $program_category_id;
                            $student_examination_score -> examination_type_id = $examination_type -> id;
                            $student_examination_score -> score = $speaking;
                            $student_examination_score -> save();
                        }
                    }
                }
            }
    
        }
    }


    //this function will check if the import entry already exist
     private function getPerson($name){
        $gen_person = Person::where('first_name','=', $name)->get()->first();

        return $gen_person;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $action = 0;
        $examination_list= Examination::all();
        $nationality_list= Nationality::all();
        $program_list= Program::all();
        $agency_list= Agency::all();

        return view('register.create', compact('agency_list','action','nationality_list','examination_list','program_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(RegisterRequest $register_request) {


        $person = new Person();
        $person ->first_name = $register_request->first_name;
        $person ->middle_name = $register_request->middle_name;
        $person ->last_name = $register_request->last_name;
        $person ->student_english_name = $register_request->student_english_name;
        $person ->nationality_id = $register_request->nationality_id;
        $person ->is_active = 1;
        $person -> save();


        $student = new Student();
        $student ->person_id = $person->id;
        $student ->is_active = 1;
        $student -> save();

        $student_program = new StudentProgram();
        $student_program -> student_id = $student->id;
        $student_program -> program_id = $register_request->program_id;
        $student_program -> date_enrolled = $register_request->date_enrolled;
        $student_program -> stay_from = $register_request->start;
        $student_program -> stay_to = $register_request->end;
        $student_program -> agency_id = $register_request->agency_id;
        $student_program -> save();

        $student_examination = new StudentExamination();
        $student_examination ->student_id = $student->id;
        $student_examination ->examination_id = $register_request->examination_id;
        $student_examination ->date_enroll = $register_request->date_enrolled;
        $student_examination -> save();

        $gen_user = new GenUser();
        $gen_user ->person_id = $person->id;
        $gen_user ->username = $register_request->username;
        // $gen_user->confirmation_code = md5(microtime().Config::get('app.key'));
        $gen_user->password = Hash::make($register_request->username);
        $gen_user ->secret = $register_request->username;
        $gen_user ->save();

        $success = \Lang::get('register_lang.create_success').' : '.$person->first_name.' '.$person->middle_name.' '.$person->last_name; 
        return redirect('register/create')->withSuccess( $success);
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    // public function getEdit($id) {

    //     $action = 1;
    //     $campus_list = Campus::all();
    //     $classification_list = Classification::all();
    //     $room_type_list = RoomType::all();
    //     $building_list = Building::all();
    //     $room = Room::find($id);
    //     $classification_level_list = ClassificationLevel::all();
    //     $program_list = Program::where('program.classification_id','=',5)->get();
    //     return view('room/edit',compact('room', 'classification_list', 'campus_list','room_type_list','building_list', 'action','classification_level_list','program_list'));
      
    // }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    // public function postEdit(RoomEditRequest $room_edit_request, $id) {
      
    //     $room = Room::find($id);
    //     $room ->room_name = $room_edit_request->room_name;
    //     $room ->classification_id = $room_edit_request->classification_id;
    //     $room ->classification_level_id = $room_edit_request->classification_level_id;
    //     $room ->room_type_id = $room_edit_request->room_type_id;
    //     $room ->building_id = $room_edit_request->building_id;
    //     $room ->updated_by_id = Auth::id();
    //     $room ->save();

    //     return redirect('room');
    // }

  
/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    // public function getDelete($id)
    //  {
    //      $action = 1;
    //     $classification_list = Classification::all();
    //     $room_type_list = RoomType::all();
    //     $building_list = Building::all();
    //     $room = Room::find($id);
    //     $classification_level_list = ClassificationLevel::all();
    //     $program_list = Program::where('program.classification_id','=',5)->get();
    //     // Show the page
    //     return view('room/delete', compact('room', 'classification_list','room_type_list','building_list', 'action','classification_level_list','program_list'));
    // }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    // public function postDelete(DeleteRequest $request,$id)
    // {
    //     $room = Room::find($id);
    //     $room->delete();
    //     return redirect('room');
    // }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        // $room_id = \Input::get('room_id');
        // $program_id = \Input::get('program_id');
        // $classification_level_id = \Input::get('classification_level_id');
        // $classification_id = \Input::get('classification_id');
          $person_list = Person::join('nationality','person.nationality_id','=','nationality.id')
                                ->select('person.id','nationality.nationality_name','person.first_name','person.middle_name','person.last_name','person.student_english_name')->orderBy('person.last_name', 'DESC');

       
        return Datatables::of($person_list)
            // ->add_column('actions', '<a href="{{{ URL::to(\'register/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
            //         <a href="{{{ URL::to(\'register/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
            //     ')
            ->edit_column('first_name', '{{{$last_name." ".$first_name." ".$middle_name}}}')
            ->remove_column('middle_name','last_name')
            ->remove_column('id')
            ->make();
    }

}
