<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\AttendanceRemark;
use App\Models\ClassStandingScoreEntry;
use App\Models\ClassStandingComponent;
use App\Models\ClassStandingComponentDetail;
use App\Models\EnrollmentClass;
use App\Models\EnrollmentSection;
use App\Models\TEClass;
use App\Http\Requests\ClassStandingScoreEntryEditRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;
use Input;

class ClassRecordController extends TeachersPortalMainController {
    
    public function dataJson(){

      $grading_period_id = \Input::get('grading_period_id');
      $class_id = \Input::get('class_id');

      $score_entry_id_arr = array();
      $perfect_score_arr = array();

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";

      $section_id = TEClass::where('class.id','=',$class_id)
                          ->select('class.section_id')
                          ->get()->last();

      $enrollment_class_list = EnrollmentSection::join('student','enrollment_section.student_id','=','student.id')
              ->join('person','student.person_id','=','person.id')
              ->where('enrollment_section.section_id','=', $section_id -> section_id)
              ->select(['student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name'])
              ->orderBy('person.last_name', 'ASC')
              ->orderBy('person.first_name', 'ASC')
              ->orderBy('person.middle_name', 'ASC')->get();

      $class_standing_component_list = ClassStandingComponent::where('class_standing_component.class_id','=', $class_id)
              ->where('class_standing_component.grading_period_id','=', $grading_period_id)
              ->join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
              ->select('class_standing_component.id','class_component_category_name','class_standing_component.component_weight')
              ->orderBy('class_standing_component.created_at')->get();

      $column_no = 5;
      foreach ($class_standing_component_list as $class_standing_component) {
        # code...        
        $class_standing_component_detail_list = ClassStandingComponentDetail::where('class_standing_component_id','=', $class_standing_component->id)
            ->join('class_standing_component','class_standing_component_detail.class_standing_component_id','=','class_standing_component.id')
            ->join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
            ->select('class_standing_component_detail.id','class_standing_component_detail.date','class_standing_component_detail.description','class_standing_component_detail.perfect_score','class_component_category_name','class_standing_component.component_weight')
            ->get();

        if($class_standing_component_detail_list != "[]")
        {
        } 
        else
        {
            $arr[1][$column_no] = "Please Add Component Detail!";
            $arr[2][$column_no] = "";
        } 

        foreach ($class_standing_component_detail_list as $class_standing_component_detail) {
          $arr[0][$column_no] = $class_standing_component->class_component_category_name.' - '.$class_standing_component->component_weight.'%';
          if($class_standing_component->class_component_category_name == 'Attendance'){
            $arr[1][$column_no] = date('m/d',strtotime($class_standing_component_detail->date));
          }else{
            $arr[1][$column_no] = $class_standing_component_detail->description.' ('.date('m/d',strtotime($class_standing_component_detail->date)).')';
          }
          $arr[2][$column_no] = $class_standing_component_detail->perfect_score;
          $perfect_score_arr[$column_no] =  $class_standing_component_detail->perfect_score;
          $column_no++;

        }
      }

      $row_no = 3;
      $count = 1;
      $detail_class = 0;

      foreach ($enrollment_class_list as $enrollment_class) {
          # code...
          $arr[$row_no][0] = $count;
          $arr[$row_no][1] = $enrollment_class->student_no;
          $arr[$row_no][2] = $enrollment_class->last_name;
          $arr[$row_no][3] = $enrollment_class->first_name;
          $arr[$row_no][4] = substr($enrollment_class->middle_name, 0,1);

          $column_no = 5;
          $class_standing_component_list = ClassStandingComponent::where('class_standing_component.class_id','=', $class_id)->where('class_standing_component.grading_period_id','=', $grading_period_id)->orderBy('class_standing_component.created_at')->get();
          
          foreach ($class_standing_component_list as $class_standing_component) {    
            $class_standing_component_detail_list = ClassStandingComponentDetail::where('class_standing_component_id','=', $class_standing_component->id)->get(); 
            
            if($class_standing_component_detail_list != "[]")
            {
                $detail_class = 1;
            } 
            else
            {
                $arr[$row_no][$column_no] = "";
            } 

            foreach ($class_standing_component_detail_list as $class_standing_component_detail) {

              $class_standing_score_entry = ClassStandingScoreEntry::where('class_standing_component_detail_id','=', $class_standing_component_detail->id)
                          ->where('student_id','=', $enrollment_class->id)
                          ->select('class_standing_score_entry.id','class_standing_score_entry.score')->get()->first();
              if($class_standing_score_entry)
              {
                $arr[$row_no][$column_no] = $class_standing_score_entry->score;
                $score_entry_id_arr[$row_no][$column_no] = $class_standing_score_entry->id;
              }
              
              $column_no++;
            }
          }
        $row_no++;
        $count++;
      }

      $data = array($arr, $score_entry_id_arr, $perfect_score_arr);
      return response()->json($data);
    }

    public function dataJsonEdit(){

      $condition = \Input::all();
      $grading_period_id = \Input::get('grading_period_id');
      $class_id = \Input::get('class_id');

      $score_entry_id_arr = array();
      $perfect_score_arr = array();

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";

      $section_id = TEClass::where('class.id','=',$class_id)
                          ->select('class.section_id')
                          ->get()->last();

      $enrollment_class_list = EnrollmentSection::join('student','enrollment_section.student_id','=','student.id')
              ->join('person','student.person_id','=','person.id')
              ->where('enrollment_section.section_id','=', $section_id -> section_id)
              ->select(['student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name'])
              ->orderBy('person.last_name', 'ASC')
              ->orderBy('person.first_name', 'ASC')
              ->orderBy('person.middle_name', 'ASC')->get();

      // $enrollment_class_list = EnrollmentClass::where('class_id','=',$class_id)->join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
      //         ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
      //         ->join('student','student_curriculum.student_id','=','student.id')
      //         ->join('person','student.person_id','=','person.id')
      //         ->select('student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
      //         ->orderBy('person.last_name', 'ASC')
      //         ->orderBy('person.first_name', 'ASC')
      //         ->orderBy('person.middle_name', 'ASC')->get();

      $class_standing_component_list = ClassStandingComponent::where('class_standing_component.class_id','=', $class_id)->where('class_standing_component.grading_period_id','=', $grading_period_id)
              ->join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
              ->select('class_standing_component.id','class_component_category_name','class_standing_component.component_weight')
              ->orderBy('class_standing_component.created_at')->get();

      $column_no = 5;
      foreach ($class_standing_component_list as $class_standing_component) {
        # code...        
        $class_standing_component_detail_list = ClassStandingComponentDetail::where('class_standing_component_id','=', $class_standing_component->id)
            ->join('class_standing_component','class_standing_component_detail.class_standing_component_id','=','class_standing_component.id')
            ->join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
            ->select('class_standing_component_detail.id','class_standing_component_detail.date','class_standing_component_detail.description','class_standing_component_detail.perfect_score','class_component_category_name','class_standing_component.component_weight')
            ->get();

        foreach ($class_standing_component_detail_list as $class_standing_component_detail) {
          $arr[0][$column_no] = $class_standing_component->class_component_category_name;
          $arr[1][$column_no] = $class_standing_component_detail->description;
          if($class_standing_component->class_component_category_name == 'Attendance'){
            $arr[2][$column_no] = '';
          }else{
            $arr[2][$column_no] = $class_standing_component_detail->perfect_score;
          }
          $perfect_score_arr[$column_no] =  $class_standing_component_detail->perfect_score;
          $column_no++;

        }
      }

      
      $row_no = 3;
      $count = 1;
      foreach ($enrollment_class_list as $enrollment_class) {
          # code...
          $arr[$row_no][0] = $count;
          $arr[$row_no][1] = $enrollment_class->student_no;
          $arr[$row_no][2] = $enrollment_class->last_name;
          $arr[$row_no][3] = $enrollment_class->first_name;
          $arr[$row_no][4] = substr($enrollment_class->middle_name, 0,1);

          $column_no = 5;
          $class_standing_component_list = ClassStandingComponent::where('class_standing_component.class_id','=', $class_id)->where('class_standing_component.grading_period_id','=', $grading_period_id)
              ->join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
              ->select('class_standing_component.id','class_component_category_name','class_standing_component.component_weight')
              ->orderBy('class_standing_component.created_at')->get();
          foreach ($class_standing_component_list as $class_standing_component) {    
            $class_standing_component_detail_list = ClassStandingComponentDetail::where('class_standing_component_id','=', $class_standing_component->id)->get();   
            foreach ($class_standing_component_detail_list as $class_standing_component_detail) {

              $class_standing_score_entry = ClassStandingScoreEntry::where('class_standing_component_detail_id','=', $class_standing_component_detail->id)
                          ->where('student_id','=', $enrollment_class->id)
                          ->join('attendance_remark','class_standing_score_entry.attendance_remarks_id','=','attendance_remark.id')
                          ->select('class_standing_score_entry.id','class_standing_score_entry.score','attendance_remark.attendance_remarks_code')->get()->first();
              if($class_standing_component->class_component_category_name == 'Attendance'){
                $arr[$row_no][$column_no] = $class_standing_score_entry->attendance_remarks_code;
              }else{
                $arr[$row_no][$column_no] = $class_standing_score_entry->score;
              }
              $score_entry_id_arr[$row_no][$column_no] = $class_standing_score_entry->id;
              $column_no++;
            }
          }
        $row_no++;
        $count++;
      }

      $data = array($arr, $score_entry_id_arr, $perfect_score_arr);


      
      return response()->json($data);
    }


    public function pdfReport()
    {
      
      $class_id = \Input::get('class_id');
      $grading_period_id = \Input::get('grading_period_id');

      $logo = str_replace("\\","/",public_path())."/images/logo.png";

      $class_standing_component = ClassStandingComponent::where('class_standing_component.class_id','=',$class_id)->where('class_standing_component.grading_period_id','=',$grading_period_id)
            ->join('class','class_standing_component.class_id','=','class.id')
            ->join('grading_period','class_standing_component.grading_period_id','=','grading_period.id')
            ->join('subject_offered','class.subject_offered_id','=','subject_offered.id')
            ->join('subject','subject_offered.subject_id','=','subject.id')
            ->join('term','class.term_id','=','term.id')
            ->join('section','class.section_id','=','section.id')
            ->join('classification_level','class.classification_level_id','=','classification_level.id')
            ->join('teacher','class.teacher_id','=','teacher.id')
            ->join('employee','teacher.employee_id','=','employee.id')
            ->join('person','employee.person_id','=','person.id')
            ->select('class_standing_component.id', 'classification_level.classification_id','classification_level.level', 'section.section_name', 'term.term_name', 'subject.name', 'subject.code', 'person.last_name', 'person.first_name', 'person.middle_name', 'grading_period.grading_period_name')->get()->first();
      
      $section_id = TEClass::where('class.id','=',$class_id)
                          ->select('class.section_id')
                          ->get()->last();

      $enrollment_class_list = EnrollmentSection::join('student','enrollment_section.student_id','=','student.id')
            ->join('person','student.person_id','=','person.id')
            ->select('student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
            ->where('enrollment_section.section_id','=', $section_id -> section_id)
            ->orderBy('person.last_name', 'ASC')
            ->orderBy('person.first_name', 'ASC')
            ->orderBy('person.middle_name', 'ASC')->get();
      
      // $enrollment_class_list = EnrollmentClass::where('class_id','=',$class_id)->join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
      //       ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
      //       ->join('student','student_curriculum.student_id','=','student.id')
      //       ->join('person','student.person_id','=','person.id')
      //       ->select('student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
      //       ->orderBy('person.last_name', 'ASC')
      //       ->orderBy('person.first_name', 'ASC')
      //       ->orderBy('person.middle_name', 'ASC')->get();

      $class_standing_component_list = ClassStandingComponent::where('class_standing_component.class_id','=', $class_id)->where('class_standing_component.grading_period_id','=', $grading_period_id)
            ->join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
            ->select('class_standing_component.id','class_component_category_name','class_standing_component.component_weight')
            ->orderBy('class_standing_component.created_at')->get();

      $class_standing_component_detail_list = ClassStandingComponentDetail::join('class_standing_component','class_standing_component_detail.class_standing_component_id','=','class_standing_component.id')
            ->join('class_component_category','class_standing_component.class_component_category_id','=','class_component_category.id')
            ->select('class_standing_component_detail.id','class_standing_component_detail.class_standing_component_id','class_standing_component_detail.date','class_standing_component_detail.description','class_standing_component_detail.perfect_score','class_component_category_name','class_standing_component.component_weight')
            ->get();

      $class_standing_score_entry_list = ClassStandingScoreEntry::join('attendance_remark','class_standing_score_entry.attendance_remarks_id','=','attendance_remark.id')
            ->select('class_standing_score_entry.id','class_standing_score_entry.class_standing_component_detail_id','class_standing_score_entry.student_id','class_standing_score_entry.score','attendance_remark.attendance_remarks_code')->get();

      $pdf = \PDF::loadView('teachers_portal/be_class/class_record_report', array('logo'=>$logo,'class_standing_component'=>$class_standing_component,'enrollment_class_list'=>$enrollment_class_list,'class_standing_component_list'=>$class_standing_component_list,'class_standing_component_detail_list'=>$class_standing_component_detail_list,'class_standing_score_entry_list'=>$class_standing_score_entry_list))->setOrientation('landscape');

      return $pdf->stream();

    }
}
