<?php namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\TEClass;
use App\Models\EnrollmentSection;
use App\Models\Employee;
use App\Models\StudentGuardian;
use App\Models\Guardian;
use App\Models\SectionAdviser;
use App\Models\Sessions;
use App\Models\Teacher;
use App\Models\Generic\GenUserRole;
use App\Models\Generic\GenUser;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Input;

class ChatMessageController extends BaseController {
   

    public function index(){
        
      $gen_user_id = Auth::user()->id;

      $gen_role = GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
              ->where('gen_user_role.gen_user_id',$gen_user_id)
              ->select(['gen_user_role.gen_user_id','gen_role.name'])
              ->get()->last();

      if($gen_role -> name == "Teacher")
      {
          $gen_user = GenUser::find($gen_user_id);
          $employee = Employee::where('employee.employee_no',$gen_user -> username)->select(['employee.id'])->get()->last();
          $user_id = Teacher::where('teacher.employee_id',$employee -> id)->select(['teacher.id'])->get()->last();
      } 
      else
      {
          $user_id = Guardian::where('guardian.gen_user_id',$gen_user_id)->select(['guardian.id'])->get()->last();
      }

        // $result = ChatMessage::select(['chat_message.guardian_id as username','chat_message.message as content'])->get();
      return view('chat_message/index',compact('user_id','gen_role'));
    }

    public function searchStudentDataJson()
    { 
        $condition = \Input::all();     
        $class_list = TEClass::join('section','class.section_id','=','section.id')
                    ->join('teacher','class.teacher_id','=','teacher.id')
                    ->join('employee','teacher.employee_id','=','employee.id')
                    ->where('section.classification_id','!=',6)
                    ->where('employee.employee_no', '=', Auth::user()->username)
                    ->select(array('class.id','class.section_id'))
                    ->get();

        foreach ($class_list as $class) 
        {
            $query = EnrollmentSection::join('student','enrollment_section.student_id','=','student.id')
                              ->join('person','student.person_id','=','person.id')
                              ->where('enrollment_section.section_id',$class->section_id)
                              ->select(['enrollment_section.student_id','person.first_name','person.middle_name','person.last_name','student.student_no']);

            foreach($condition as $column => $value)
            { 
                if($column == 'query')
                {
                    $query->where('first_name', 'LIKE', "%$value%")
                          ->orWhere('middle_name', 'LIKE', "%$value%")
                          ->orWhere('last_name', 'LIKE', "%$value%")
                          ->orWhere('student_no', 'LIKE', "%$value%");
                }
                else
                {
                    $query->where($column, '=', $value);
                }   
            }
        }

        $student_list = $query->get();
        return response()->json($student_list);
    }

    public function getGuardianByStudent()
    {
        $student_id = \Input::get('student_id');

        $student_guardian = StudentGuardian::join('guardian','student_guardian.guardian_id','=','guardian.id')
                        ->join('person','guardian.person_id','=','person.id')
                        ->where('student_guardian.student_id',$student_id)
                        ->select(['student_guardian.guardian_id as id','person.first_name','person.last_name'])
                        ->get();

        return response()->json($student_guardian);
    }

    public function chat(){
        
      $gen_user_id = Auth::user()->id;

      $gen_role = GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
              ->where('gen_user_role.gen_user_id',$gen_user_id)
              ->select(['gen_user_role.gen_user_id','gen_role.name'])
              ->get()->last();

      if($gen_role -> name == "Teacher")
      {
          $gen_user = GenUser::find($gen_user_id);
          $employee = Employee::where('employee.employee_no',$gen_user -> username)->select(['employee.id'])->get()->last();
          $user_id = Teacher::where('teacher.employee_id',$employee -> id)->select(['teacher.id'])->get()->last();
      } 
      else
      {
          $user_id = Guardian::where('guardian.gen_user_id',$gen_user_id)->select(['guardian.id'])->get()->last();
      }

      $teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                              ->join('person','employee.person_id','=','person.id')
                              ->select(['teacher.id','person.first_name','person.last_name'])
                              ->orderBy('person.first_name','ASC')
                              ->get();

        // $result = ChatMessage::select(['chat_message.guardian_id as username','chat_message.message as content'])->get();
      return view('chat_message/chat',compact('user_id','gen_role','teacher_list'));
    }

    public function dataJson(){
        
        $teacher_id = \Input::get('teacher_id');
        $guardian_id = \Input::get('guardian_id');
        $result = ChatMessage::where('chat_message.teacher_id',$teacher_id)
                    ->where('chat_message.guardian_id',$guardian_id)
                    ->select(['chat_message.guardian_id','chat_message.teacher_id','chat_message.message','chat_message.is_sender','chat_message.is_file','chat_message.is_image','chat_message.date_time'])
                    ->get();

        return response()->json($result);
    }

    public function getBadgeDataJson(){
        
        $teacher_id = \Input::get('teacher_id');
        $guardian_id = \Input::get('guardian_id');
        $result = ChatMessage::where('chat_message.teacher_id',$teacher_id)
                    ->where('chat_message.guardian_id',$guardian_id)
                    ->where('chat_message.is_read',0)
                    ->where('chat_message.is_sender',2)
                    // ->select(['chat_message.guardian_id','chat_message.teacher_id','chat_message.message','chat_message.is_sender','chat_message.is_file','chat_message.is_image','chat_message.date_time'])
                    // ->get();
                    ->count();

        return response()->json($result);
    }

    public function getBadgeGuardianDataJson(){
        
        $teacher_id = \Input::get('teacher_id');
        $guardian_id = \Input::get('guardian_id');
        $result = ChatMessage::where('chat_message.teacher_id',$teacher_id)
                    ->where('chat_message.guardian_id',$guardian_id)
                    ->where('chat_message.is_read',0)
                    ->where('chat_message.is_sender',1)
                    // ->select(['chat_message.guardian_id','chat_message.teacher_id','chat_message.message','chat_message.is_sender','chat_message.is_file','chat_message.is_image','chat_message.date_time'])
                    // ->get();
                    ->count();

        return response()->json($result);
    }

    public function setRead(){
        
        $teacher_id = \Input::get('teacher_id');
        $guardian_id = \Input::get('guardian_id');
        $result_list = ChatMessage::where('chat_message.teacher_id',$teacher_id)
                    ->where('chat_message.guardian_id',$guardian_id)
                    ->where('chat_message.is_read',0)
                    ->where('chat_message.is_sender',2)
                    ->select(['chat_message.id'])
                    ->get();

        foreach ($result_list as $result) {
          
            $chat_message = ChatMessage::find($result -> id);
            $chat_message -> is_read = 1;
            $chat_message -> save();

        }

        return response()->json($result_list);
    }

    public function setGuardianRead(){
        
        $teacher_id = \Input::get('teacher_id');
        $guardian_id = \Input::get('guardian_id');
        $result_list = ChatMessage::where('chat_message.teacher_id',$teacher_id)
                    ->where('chat_message.guardian_id',$guardian_id)
                    ->where('chat_message.is_read',0)
                    ->where('chat_message.is_sender',1)
                    ->select(['chat_message.id'])
                    ->get();

        foreach ($result_list as $result) {
          
            $chat_message = ChatMessage::find($result -> id);
            $chat_message -> is_read = 1;
            $chat_message -> save();            

        }

        return response()->json($result_list);
    }

    public function getGuardianDataJson()
    {
        $teacher_id = \Input::get('teacher_id');
        $user_list = array();
        $user = array();

        $class_list = TEClass::join('section','class.section_id','=','section.id')
                    ->join('teacher','class.teacher_id','=','teacher.id')
                    ->join('employee','teacher.employee_id','=','employee.id')
                    ->where('section.classification_id','!=',6)
                    ->where('class.teacher_id', '=', $teacher_id)
                    ->select(array('class.id','class.section_id'))
                    ->get();


            foreach ($class_list as $class) 
            {
                  $student_list = EnrollmentSection::where('enrollment_section.section_id',$class->section_id)
                              ->select(['enrollment_section.student_id'])
                              ->get();

                  foreach ($student_list as $student) 
                  {
                      $guardian_list = StudentGuardian::join('guardian','student_guardian.guardian_id','=','guardian.id')
                                ->join('person','guardian.person_id','=','person.id')
                                ->where('student_guardian.student_id',$student->student_id)
                                ->select(['student_guardian.guardian_id as id','person.first_name','person.last_name'])
                                ->groupBy('student_guardian.guardian_id')
                                ->get();
                      
                      foreach ($guardian_list as $guardian) 
                      {                 
                          if($guardian)
                          {
                            $value = array_search($guardian->id,$user);

                            $data = strlen($value);
                             
                            if($data == 0)
                            { 
                              array_push($user, $guardian->id);
                              $user_list[] = array('id' => $guardian->id, 'name' => $guardian->first_name." ".$guardian->last_name);
                            }

                          }


                      }
                     
                  }

            }
            $section_adviser_list = SectionAdviser::join('employee','section_adviser.employee_id','=','employee.id')
                          ->where('employee.employee_no', '=', Auth::user()->username)
                          ->select(['section_adviser.section_id'])
                          ->get();

            foreach ($section_adviser_list as $section_adviser) 
            {     
                  $student_list = EnrollmentSection::where('enrollment_section.section_id',$section_adviser->section_id)
                              ->select(['enrollment_section.student_id'])
                              ->get();

                  foreach ($student_list as $student) 
                  {
                      $guardian_list = StudentGuardian::join('guardian','student_guardian.guardian_id','=','guardian.id')
                                    ->join('person','guardian.person_id','=','person.id')
                                    ->where('student_guardian.student_id',$student->student_id)
                                    ->select(['student_guardian.guardian_id as id','person.first_name','person.last_name'])
                                    ->groupBy('student_guardian.guardian_id')
                                    ->get();

                      foreach ($guardian_list as $guardian) 
                      {
                          if($guardian)
                          {
                            $value = array_search($guardian->id,$user);

                            $data = strlen($value);
                             
                            if($data == 0)
                            { 
                              array_push($user, $guardian->id);
                              $user_list[] = array('id' => $guardian->id, 'name' => $guardian->first_name." ".$guardian->last_name);
                            }
                             
                          }
                      }
                  }
            }

            return response()->json($user_list);
    }


    public function getUserDataJson()
    {

      $gen_user_id = Auth::user()->id;

      $gen_role = GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
              ->where('gen_user_role.gen_user_id',$gen_user_id)
              ->select(['gen_user_role.gen_user_id','gen_role.name'])
              ->get()->last();

      $user_list = array();
      $user = array();
      $chat = array();

      if($gen_role -> name == "Teacher")
      {
            $class_list = TEClass::join('section','class.section_id','=','section.id')
                    ->join('teacher','class.teacher_id','=','teacher.id')
                    ->join('employee','teacher.employee_id','=','employee.id')
                    ->where('section.classification_id','!=',6)
                    ->where('employee.employee_no', '=', Auth::user()->username)
                    ->select(array('class.id','class.section_id'))
                    ->get();

            $teacher= Teacher::join('employee','teacher.employee_id','=','employee.id')
                          ->where('employee.employee_no', Auth::user()->username)
                          ->select(['teacher.id'])
                          ->get()->last(); 

            $chat_message = ChatMessage::where('chat_message.is_read',0)
                          ->where('chat_message.is_sender',2)
                          ->where('chat_message.teacher_id',$teacher -> id)
                          ->select(['chat_message.guardian_id'])
                          ->groupBy('chat_message.guardian_id')
                          ->get();

            foreach ($chat_message as $message) 
            {
                array_push($chat, $message -> guardian_id);  
            }

            foreach ($class_list as $class) 
            {
                  $student_list = EnrollmentSection::where('enrollment_section.section_id',$class->section_id)
                              ->select(['enrollment_section.student_id'])
                              ->get();

                  foreach ($student_list as $student) 
                  {
                      $guardian_list = StudentGuardian::join('guardian','student_guardian.guardian_id','=','guardian.id')
                                ->join('person','guardian.person_id','=','person.id')
                                ->where('student_guardian.student_id',$student->student_id)
                                ->select(['student_guardian.guardian_id as id','person.first_name','person.last_name'])
                                ->groupBy('student_guardian.guardian_id')
                                ->get();
                      
                      foreach ($guardian_list as $guardian) 
                      {                 
                          if($guardian)
                          {
                            $value = array_search($guardian->id,$user);
                            $value1 = array_search($guardian->id,$chat);

                            $data = strlen($value);
                            $data1 = strlen($value1);
                             
                            if($data == 0 && $data1 != 0)
                            { 
                              array_push($user, $guardian->id);
                              $user_list[] = array('id' => $guardian->id, 'name' => $guardian->first_name." ".$guardian->last_name);
                            }

                          }
                      }
                     
                  }

            }
            $section_adviser_list = SectionAdviser::join('employee','section_adviser.employee_id','=','employee.id')
                          ->where('employee.employee_no', '=', Auth::user()->username)
                          ->select(['section_adviser.section_id'])
                          ->get();

            foreach ($section_adviser_list as $section_adviser) 
            {     
                  $student_list = EnrollmentSection::where('enrollment_section.section_id',$section_adviser->section_id)
                              ->select(['enrollment_section.student_id'])
                              ->get();

                  foreach ($student_list as $student) 
                  {
                      $guardian_list = StudentGuardian::join('guardian','student_guardian.guardian_id','=','guardian.id')
                                    ->join('person','guardian.person_id','=','person.id')
                                    ->where('student_guardian.student_id',$student->student_id)
                                    ->select(['student_guardian.guardian_id as id','person.first_name','person.last_name'])
                                    ->groupBy('student_guardian.guardian_id')
                                    ->get();

                      foreach ($guardian_list as $guardian) 
                      {
                          if($guardian)
                          {
                            $value = array_search($guardian->id,$user);
                            $value1 = array_search($guardian->id,$chat);

                            $data = strlen($value);
                            $data1 = strlen($value1);
                             
                            if($data == 0 && $data1 != 0)
                            { 
                              array_push($user, $guardian->id);
                              $user_list[] = array('id' => $guardian->id, 'name' => $guardian->first_name." ".$guardian->last_name);
                            }
                             
                          }
                      }
                  }
            }

      }
      else
      {
          $teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                            ->join('person','employee.person_id','=','person.id')
                            ->select(['teacher.id','person.first_name','person.last_name'])->get();

          $guardian = Guardian::where('guardian.gen_user_id',$gen_user_id)
                          ->select(['guardian.id'])
                          ->get()->last();

          $chat_message = ChatMessage::where('chat_message.is_read',0)
                          ->where('chat_message.is_sender',1)
                          ->where('chat_message.guardian_id',$guardian -> id)
                          ->select(['chat_message.teacher_id'])
                          ->groupBy('chat_message.teacher_id')
                          ->get();

          foreach ($chat_message as $message) 
          {
              array_push($chat, $message -> teacher_id);  
          }

          foreach ($teacher_list as $teacher) 
          {
              $value1 = array_search($teacher->id,$chat);
              $data1 = strlen($value1);
                             
              if($data1 != 0)
              { 
                  $user_list[] = array('id' => $teacher->id, 'name' => $teacher->first_name." ".$teacher->last_name);
              } 
          }
      }

        return response()->json($user_list);
    }

    public function postCreate(){
        
        $teacher_id = \Input::get('teacher_id');
        $guardian_id = \Input::get('guardian_id');
        $message = \Input::get('message');

      $gen_user_id = Auth::user()->id;

      $gen_role = GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
              ->where('gen_user_role.gen_user_id',$gen_user_id)
              ->select(['gen_user_role.gen_user_id','gen_role.name'])
              ->get()->last();

      if($gen_role -> name == "Teacher")
      {
        $is_sender = 1;
        // $date_from = date('Y-m-d H:i:s',mktime (date("H"), date("i"), date("s") - 10));
        // $date = date('Y-m-d H:i:s',mktime (date("H"), date("i"), date("s")));

        // $guardian= Guardian::find($guardian_id);

        // $count = Sessions::where('gen_user_id',$guardian -> gen_user_id)
        //             ->whereBetween('sessions.updated_at',array($date_from,$date))
        //             ->count();
        // if($count == 0)
        // {
        //     $is_read = 0;
        // }
        // else
        // {
        //     $is_read = 1;
        // }

      }
      else
      {
        $is_sender = 2;
        // $date_from = date('Y-m-d H:i:s',mktime (date("H"), date("i"), date("s") - 10));
        // $date = date('Y-m-d H:i:s',mktime (date("H"), date("i"), date("s")));

        // $teacher = Teacher::join('employee','teacher.employee_id','=','employee.id')
        //             ->join('gen_user','employee.employee_no','=','gen_user.username')
        //             ->where('teacher.id',$teacher_id)
        //             ->select(['gen_user.id'])->get()->last();

        // $count = Sessions::where('gen_user_id',$teacher -> id)
        //             ->whereBetween('sessions.updated_at',array($date_from,$date))
        //             ->count();
        // if($count == 0)
        // {
        //     $is_read = 0;
        // }
        // else
        // {
        //     $is_read = 1;
        // }
      }

        $result = new ChatMessage();
        $result -> teacher_id = $teacher_id;
        $result -> guardian_id = $guardian_id;
        $result -> message = $message;
        $result -> is_sender = $is_sender;
        // $result -> is_read = $is_read;
        $result -> date_time = Carbon::now();
        $result -> save();

      return response()->json($result);
    }

    public function postUploadCreate(){
        
        $teacher_id = \Input::get('teacher_id');
        $guardian_id = \Input::get('guardian_id');
        $message = \Input::get('message');
        $file_name = addslashes($_FILES['file']['name']);

         $destination_path = '../storage/chat_files/'; // upload path
         //  $original_file_name_with_extension = Input::file('image')->getClientOriginalName();
         //  $original_file_name_without_extension = pathinfo($original_file_name_with_extension, PATHINFO_FILENAME);
         //  $file_name = $original_file_name_without_extension.rand(11111,99999).'.'.$extension; // renameing image

          Input::file('file')->move($destination_path, $file_name); // uploading file to given path

        // move_uploaded_file($_FILES["file"]["tmp_name"], "../storage/chat_files/". $_FILES["file"]["name"]);

      $gen_user_id = Auth::user()->id;

      $gen_role = GenUserRole::join('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
              ->where('gen_user_role.gen_user_id',$gen_user_id)
              ->select(['gen_user_role.gen_user_id','gen_role.name'])
              ->get()->last();

      if($gen_role -> name == "Teacher")
      {
        $is_sender = 1;
      }
      else
      {
        $is_sender = 2;
      }
      $result = getimagesize('../storage/chat_files/'.$file_name) ? true : false;
      $is_image = 0;
      if($result == 'true')
      {
        $is_image = 1;
      }

        $result = new ChatMessage();
        $result -> teacher_id = $teacher_id;
        $result -> guardian_id = $guardian_id;
        $result -> message = '../storage/chat_files/'.$file_name;
        $result -> is_sender = $is_sender;
        $result -> is_file = 1;
        $result -> is_image = $is_image;
        $result -> date_time = Carbon::now();
        $result -> save();

      return response()->json($result);
    }


} 
