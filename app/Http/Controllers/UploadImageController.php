<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\RegistrarMainController;
use App\Models\Photo;
use App\Models\Person;
use App\Http\Requests\PhotoStudentRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;
use Input;
use Session;
use Redirect;
use Excel;

class UploadImageController extends RegistrarMainController {


  public function getUpload(){
        return view('registrar_report/upload_image.index');
      }


  public function postUpload(PhotoStudentRequest $photo_request) {

        $file = Input::file('image');
        $id = $photo_request->student_id;
        $person_id = $photo_request->person_id;
        $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension

        //check if valid image file by checking the extension
        if($extension =="jpg" || $extension=="jpeg"  || $extension=="png" && $file == ""){
          $destination_path = 'assets/site/images/person_images/'; // upload path
          $original_file_name_with_extension = Input::file('image')->getClientOriginalName();
          $original_file_name_without_extension = pathinfo($original_file_name_with_extension, PATHINFO_FILENAME);
          $file_name = $original_file_name_without_extension.rand(11111,99999).'.'.$extension; // renameing image
          Input::file('image')->move($destination_path, $file_name); // uploading file to given path
       

          $this->migrateImageToDB("assets/site/images/person_images/".$file_name, $person_id);

         Session::flash('success', 'Upload successfully'); 
        return Redirect::to('registrar_report/upload_image/');
        }
        
        else
        {
          Session::flash('error', 'Uploaded file is not valid');
           return Redirect::to('registrar_report/upload_image/');  
        }


  }

    private function migrateImageToDB($file_name, $id){

      $photo = new Photo();
      $photo->img = $file_name;
      $photo->save();

      $person = Person::find($id);
      $person -> photo_id = $photo->id;
      $person->save();
    }

}