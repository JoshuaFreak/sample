<?php namespace App\Http\Controllers;

use App\Http\Controllers\AdminMainController;
use App\Models\Generic\GenUser;
use App\Models\Generic\GenRole;
use App\Models\Sessions;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\Student;
use App\Models\Section;
use App\Models\Enrollment;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\StudentCurriculum;
use Datatables;


class DashboardAdminController extends AdminMainController {


    public function index()
    {
        $title = "Dashboard";
        
        
        $gen_user_count =GenUser::count();
        $gen_role_count = GenRole::count();

        //var_dump($gen_user_count);
       return view('admin.index',  compact('title','gen_user_count','gen_role_count'));
    }

    public function onlineTeacher()
    {
       
       return view('admin.online_teacher');
    }
    
    public function onlineTeacherDataJson()
    {
        $date_from = date('Y-m-d H:i:s',mktime (date("H"), date("i"), date("s") - 10));
        $date = date('Y-m-d H:i:s',mktime (date("H"), date("i"), date("s")));
        $session_list = Sessions::leftJoin('gen_user','sessions.gen_user_id','=','gen_user.id')
                    ->leftJoin('gen_user_role','sessions.gen_user_id','=','gen_user_role.gen_user_id')
                    ->leftJoin('gen_role','gen_user_role.gen_role_id','=','gen_role.id')
                    ->leftJoin('person','gen_user.person_id','=','person.id')
                    ->whereBetween('sessions.updated_at',array($date_from,$date))
                    ->select(['person.id as person_id','gen_role.name','person.first_name','person.middle_name','person.last_name'])
                    ->groupBy('sessions.gen_user_id')
                    ->get();

        return response()->json($session_list);
    }

    public function chatMonitoring()
    {
       $teacher_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
       						->join('person','employee.person_id','=','person.id')
       						->select(['teacher.id','person.first_name','person.last_name','person.middle_name'])->get();
        //var_dump($gen_user_count);
       return view('admin.chat_monitoring',compact('teacher_list'));
    }

    public function reportsIndex(){
        $title = "Reports";
        return view('admin/reports.index', compact('title'));
    }

    public function collectionReport(){
        $title = "Reports";
        $term_list = Term::all()->sortByDesc('term_name');

        return view('admin/reports.collection', compact('title', 'term_list'));
    }

    public function orReport(){
        $title = "Reports";
        $term_list = Term::all()->sortByDesc('term_name');

        return view('admin/reports.or', compact('title', 'term_list'));
    }

    public function arReport(){
        $title = "Reports";
        $term_list = Term::all()->sortByDesc('term_name');

        return view('admin/reports.ar', compact('title', 'term_list'));
    }

    public function studentContacts(){
        $title = "Reports";
        $classification_level = ClassificationLevel::all();
        $section = Section::all();


        return view('admin/reports.student', compact('title', 'classification_level', 'section'));
    }

    public function studentContactsData(){
        $section_id = \Input::get('section_id');

        if($section_id){
            $student = Student::join('student_curriculum', 'student.id', '=', 'student_curriculum.student_id')
                                         ->join('person', 'student.person_id', '=', 'person.id')
                                         ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
                                         ->join('enrollment', 'student_curriculum.id', '=', 'enrollment.student_curriculum_id')
                                         ->select(['student.id', 'student.student_no', 'person.first_name', 'person.middle_name', 'person.last_name', 'person.address', 'person.contact_no', 'person.home_number', 'person.student_mobile_number', 'person.student_email_address'])
                                         ->where('enrollment.section_id', $section_id);
        }else{
            $student = Student::join('student_curriculum', 'student.id', '=', 'student_curriculum.student_id')
                                         ->join('person', 'student.person_id', '=', 'person.id')
                                         ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
                                         ->join('enrollment', 'student_curriculum.id', '=', 'enrollment.student_curriculum_id')
                                         ->select(['student.id', 'student.student_no', 'person.first_name', 'person.middle_name', 'person.last_name', 'person.address', 'person.contact_no', 'person.home_number', 'person.student_mobile_number', 'person.student_email_address']);
        }

        return Datatables::of($student)
                ->edit_column('first_name', '{{$last_name}}, {{$first_name}} {{$middle_name}}')
                ->edit_column('contact_no', '@if(!$contact_no) {{--*/$contact_no = \'===\' /*--}} @endif @if(!$home_number) {{--*/$home_number = \'===\' /*--}}@endif @if(!$student_mobile_number) {{--*/ $student_mobile_number = \'===\' /*--}} @endif {{$contact_no}}/ {{$home_number}} / {{$student_mobile_number}} ')
                ->remove_column('id', 'middle_name', 'last_name', 'home_number', 'student_mobile_number')
                ->make();
    }

    public function studentContactsDataJson(){
        $classification_level_id = \Input::get('classification_level_id');

        $section = Section::select(['id as value', 'section_name as text'])
                          ->where('classification_level_id', $classification_level_id)
                          ->get();

        return response()->json($section);

    }

    public function studentPopulation(){
        $title = "Reports";
        $classification_level = ClassificationLevel::all();
        $section = Section::all();
        $term = Term::all()->sortByDesc('term_name');

        return view('admin/reports.student_population', compact('title', 'classification_level', 'section', 'term'));
    }

    public function studentPopulationDataJson(){
        $term_id = \Input::get('term_id');
        $classification_level_arr = array();
        $series = array();
        $classification_level_list = ClassificationLevel::select(['classification_level.id','classification_level.level'])->get();
        
        foreach ($classification_level_list as $classification_level) {

            if($classification_level -> id != 0) 
            {
                
                $enrolled_student = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->where('enrollment.term_id',$term_id)
                        ->where('enrollment.classification_level_id',$classification_level -> id)
                        // ->select(['enrollment.classification_level_id'.'student_curriculum.student_id'])
                        ->count();


                $student_number[] = array('value' => $enrolled_student,'text' => $classification_level->level);

                if($enrolled_student != 0){
                    $classification_level_arr[] = $classification_level->level;
                    $series[] = $enrolled_student;
                }                
            }
        }

        $data[0] = array('labels' => $classification_level_arr, 'series' => $series); 
        $data[1] = $student_number; 

        return response()->json($data);
    }

    public function studentPopulationGender(){
        $title = "Reports";
        $classification_level = ClassificationLevel::all();
        $section = Section::all();
        $term = Term::all()->sortByDesc('term_name');


        return view('admin/reports.student_population_gender', compact('title', 'classification_level', 'section', 'term'));
    }

    public function studentPopulationGenderDataJson(){
        $section_id = \Input::get('section_id');
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');


        $gender_arr = array('Male','Female');
        $series = array();

        for($i = 0; $i < sizeof($gender_arr); $i++){

            if($classification_level_id == 0 && $section_id == "" && $term_id != ""){
                $count = Student::join('student_curriculum', 'student.id', '=', 'student_curriculum.student_id')
                                                 ->join('person', 'student.person_id', '=', 'person.id')
                                                 ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
                                                 ->join('enrollment', 'student_curriculum.id', '=', 'enrollment.student_curriculum_id')
                                                 ->join('gender', 'person.gender_id', '=', 'gender.id')
                                                 ->select(['enrollment.id', 'student.student_no', 'person.first_name', 'person.middle_name', 'person.last_name', 'person.address', 'person.contact_no', 'person.home_number', 'person.student_mobile_number', 'person.student_email_address', 'gender.gender_name'])
                                                 ->where('enrollment.term_id', $term_id)
                                                 ->where('gender_name', $gender_arr[$i])
                                                 ->count();
            }elseif($classification_level_id != "" && $section_id == "" && $term_id == ""){

                $count = Student::join('student_curriculum', 'student.id', '=', 'student_curriculum.student_id')
                                                 ->join('person', 'student.person_id', '=', 'person.id')
                                                 ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
                                                 ->join('enrollment', 'student_curriculum.id', '=', 'enrollment.student_curriculum_id')
                                                 ->join('gender', 'person.gender_id', '=', 'gender.id')
                                                 ->select(['enrollment.id', 'student.student_no', 'person.first_name', 'person.middle_name', 'person.last_name', 'person.address', 'person.contact_no', 'person.home_number', 'person.student_mobile_number', 'person.student_email_address', 'gender.gender_name'])
                                                 ->where('enrollment.classification_level_id', $classification_level_id)
                                                 ->where('gender_name', $gender_arr[$i])
                                                 ->count();
            }elseif($classification_level_id != "" && $section_id != "" && $term_id == ""){

                $count = Student::join('student_curriculum', 'student.id', '=', 'student_curriculum.student_id')
                                                 ->join('person', 'student.person_id', '=', 'person.id')
                                                 ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
                                                 ->join('enrollment', 'student_curriculum.id', '=', 'enrollment.student_curriculum_id')
                                                 ->join('gender', 'person.gender_id', '=', 'gender.id')
                                                 ->select(['enrollment.id', 'student.student_no', 'person.first_name', 'person.middle_name', 'person.last_name', 'person.address', 'person.contact_no', 'person.home_number', 'person.student_mobile_number', 'person.student_email_address', 'gender.gender_name'])
                                                 ->where('enrollment.classification_level_id', $classification_level_id)
                                                 ->where('enrollment.section_id', $section_id)
                                                 ->where('gender_name', $gender_arr[$i])
                                                 ->count();
            }elseif($classification_level_id != "" && $section_id == "" && $term_id != ""){

                $count = Student::join('student_curriculum', 'student.id', '=', 'student_curriculum.student_id')
                                                 ->join('person', 'student.person_id', '=', 'person.id')
                                                 ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
                                                 ->join('enrollment', 'student_curriculum.id', '=', 'enrollment.student_curriculum_id')
                                                 ->join('gender', 'person.gender_id', '=', 'gender.id')
                                                 ->select(['enrollment.id', 'student.student_no', 'person.first_name', 'person.middle_name', 'person.last_name', 'person.address', 'person.contact_no', 'person.home_number', 'person.student_mobile_number', 'person.student_email_address', 'gender.gender_name'])
                                                 ->where('enrollment.classification_level_id', $classification_level_id)
                                                 ->where('enrollment.term_id', $term_id)
                                                 ->where('gender_name', $gender_arr[$i])
                                                 ->count();
            }elseif($classification_level_id != "" && $section_id != "" && $term_id != ""){

                $count = Student::join('student_curriculum', 'student.id', '=', 'student_curriculum.student_id')
                                                 ->join('person', 'student.person_id', '=', 'person.id')
                                                 ->join('classification', 'student_curriculum.classification_id', '=', 'classification.id')
                                                 ->join('enrollment', 'student_curriculum.id', '=', 'enrollment.student_curriculum_id')
                                                 ->join('gender', 'person.gender_id', '=', 'gender.id')
                                                 ->select(['enrollment.id', 'student.student_no', 'person.first_name', 'person.middle_name', 'person.last_name', 'person.address', 'person.contact_no', 'person.home_number', 'person.student_mobile_number', 'person.student_email_address', 'gender.gender_name'])
                                                 ->where('enrollment.classification_level_id', $classification_level_id)
                                                 ->where('enrollment.term_id', $term_id)
                                                 ->where('enrollment.section_id', $section_id)
                                                 ->where('gender_name', $gender_arr[$i])
                                                 ->count();
            }

            $series[] = $count;

            $student[] = array('value' => $count, 'text' => $gender_arr[$i]);
        }


        // $key = array_search(0, $series);

        // if($key > -1){
        //     unset($series[$key]);
        //     unset($gender_arr[$key]);
        // }
    
        $data[0] = array('labels' => $gender_arr, 'series' => $series);
        $data[1] = $student;

        return response()->json($data);
    }

}