<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Term;
use App\Models\SectionAdviser;
use App\Models\SectionAdviserStudent;
use App\Models\SectionMonitor;
use App\Models\Enrollment;
use App\Models\GradingPeriod;
use App\Models\DesirableTrait;
use App\Models\ActionTaken;
use App\Models\Pace;
use App\Models\Attendance;
use App\Models\StudentAttendance;
use App\Models\Subject;
use App\Models\AttendanceRemark;
use App\Models\DesirableTraitDetail;
use App\Models\StudentDesirableTrait;
use App\Models\DesirableTraitRating;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class ClassMonitoringController extends TeachersPortalMainController {   
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
        $subject_list = Subject::orderBy('subject.classification_id','ASC')->get();
        $groupby_grading_period_list = GradingPeriod::groupBy('grading_period_name')->orderBy('grading_period.id','ASC')->get();
        return view('teachers_portal/class_monitoring.index', compact('classification_list', 'classification_level_list', 'term_list', 'action_taken_list', 'grading_period_list', 'groupby_grading_period_list','subject_list'));
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
      // $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $term_id = \Input::get('term_id');

        if($classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null) 
        {
            $section_monitor_list = SectionMonitor::join('classification','section_monitor.classification_id','=','classification.id')
                ->join('classification_level','section_monitor.classification_level_id','=','classification_level.id')
                ->join('term','section_monitor.term_id','=','term.id')
                ->join('section','section_monitor.section_id','=','section.id')
                ->join('employee','section_monitor.employee_id','=','employee.id')
                // ->where('section_monitor.classification_id','=',$classification_id)
                ->where('section_monitor.classification_level_id','=',$classification_level_id)
                ->where('section_monitor.term_id','=',$term_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('section_monitor.id','section_monitor.classification_level_id','section_monitor.classification_id','section_monitor.term_id','classification.classification_name','classification_level.level','section.section_name','term.term_name', 'section_monitor.section_id'))
                ->orderBy('classification_level.id', 'ASC');
        }
        elseif($classification_level_id != "" && $classification_level_id != null) 
        {
            $section_monitor_list = SectionMonitor::join('classification','section_monitor.classification_id','=','classification.id')
                ->join('classification_level','section_monitor.classification_level_id','=','classification_level.id')
                ->join('term','section_monitor.term_id','=','term.id')
                ->join('section','section_monitor.section_id','=','section.id')
                ->join('employee','section_monitor.employee_id','=','employee.id')
                // ->where('section_monitor.classification_id','=',$classification_id)
                ->where('section_monitor.classification_level_id','=',$classification_level_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('section_monitor.id','section_monitor.classification_level_id','section_monitor.classification_id','section_monitor.term_id','classification.classification_name','classification_level.level','section.section_name','term.term_name', 'section_monitor.section_id'))
                ->orderBy('classification_level.id', 'ASC');
        }
        elseif($term_id != "" && $term_id != null) 
        {
            $section_monitor_list = SectionMonitor::join('classification','section_monitor.classification_id','=','classification.id')
                ->join('classification_level','section_monitor.classification_level_id','=','classification_level.id')
                ->join('term','section_monitor.term_id','=','term.id')
                ->join('section','section_monitor.section_id','=','section.id')
                ->join('employee','section_monitor.employee_id','=','employee.id')
                ->where('section_monitor.term_id','=',$term_id)
                ->where('employee.employee_no', '=', Auth::user()->username)
                ->select(array('section_monitor.id','section_monitor.classification_level_id','section_monitor.classification_id','section_monitor.term_id','classification.classification_name','classification_level.level','section.section_name','term.term_name', 'section_monitor.section_id'))
                ->orderBy('classification_level.id', 'ASC');
        }
        // elseif($classification_level_id != "" && $classification_level_id != null) 
        // {
        //     $section_monitor_list = SectionMonitor::join('classification','section_monitor.classification_id','=','classification.id')
        //         ->join('classification_level','section_monitor.classification_level_id','=','classification_level.id')
        //         ->join('term','section_monitor.term_id','=','term.id')
        //         ->join('section','section_monitor.section_id','=','section.id')
        //         ->join('employee','section_monitor.employee_id','=','employee.id')
        //         ->where('section_monitor.classification_level_id','=',$classification_level_id)
        //         ->where('employee.employee_no', '=', Auth::user()->username)
        //         ->select(array('section_monitor.id','section_monitor.classification_level_id','section_monitor.classification_id','section_monitor.term_id','classification.classification_name','classification_level.level','section.section_name','term.term_name', 'section_monitor.section_id'))
        //         ->orderBy('classification_level.id', 'ASC');
        // }
        else
        {
            $section_monitor_list = SectionMonitor::join('classification','section_monitor.classification_id','=','classification.id')
                ->join('classification_level','section_monitor.classification_level_id','=','classification_level.id')
                ->join('term','section_monitor.term_id','=','term.id')
                ->join('section','section_monitor.section_id','=','section.id')
                ->join('employee','section_monitor.employee_id','=','employee.id')
                ->where('employee.id', '=', Auth::user()->username)
                ->select(array('section_monitor.id','section_monitor.classification_level_id','section_monitor.classification_id','section_monitor.term_id','classification.classification_name','classification_level.level','section.section_name','term.term_name', 'section_monitor.section_id'))
                ->orderBy('classification_level.id', 'ASC');
        }
        return Datatables::of($section_monitor_list)
                ->editColumn('level','{{ ucwords(strtolower($level." ".$section_name)) }}')
                ->add_column('actions','
                    <img class="button" src="{{{ asset("assets/site/images/teachers_portal/attendance-icon.png") }}}" data-classification_id="{{{$classification_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-term_id="{{{$term_id}}}" data-section_id="{{{$section_id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-toggle="modal" data-target="#attendancemodal">
                    ')

                    // <img class="button" src="{{{ asset("assets/site/images/teachers_portal/student_list.png") }}}" data-classification_id="{{{$classification_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-term_id="{{{$term_id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-section_id="{{{$section_id}}}" data-term_name="{{{$term_name}}}" data-toggle="modal" data-target="#studentlistmodal">
                    // <img class="button" src="{{{ asset("assets/site/images/teachers_portal/Students Document.png") }}}" data-classification_id="{{{$classification_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-term_id="{{{$term_id}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-toggle="modal" data-target="#studentdocumentlistmodal">
                ->remove_column('id', 'section_name', 'classification_id', 'classification_level_id', 'term_id', 'section_id')
                ->make();

    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function StudentListdata()
    {
        $classification_level_id = \Input::get('student_list_classification_level_id');
        $term_id = \Input::get('student_list_term_id');
        $section_id = \Input::get('student_list_section_id');

        // $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
        //     ->join('student','student_curriculum.student_id','=','student.id')
        //     ->join('person','student.person_id','=','person.id')
        //     ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
        //     ->join('classification','classification_level.classification_id','=','classification.id')
        //     ->join('section','enrollment.section_id','=','section.id')
        //     ->join('term','enrollment.term_id','=','term.id')
        //     ->where('enrollment.classification_level_id','=',$classification_level_id)
        //     ->where('enrollment.term_id','=',$term_id)
        //     ->where('enrollment.section_id',$section_id)
        //     ->select(array('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.birthdate','student_curriculum.student_id','classification_level.level','section.section_name','term.term_name','classification.classification_name','enrollment.term_id', 'enrollment.section_id','classification_level.classification_id','enrollment.classification_level_id','student_curriculum.curriculum_id'))
        //     ->orderBy('person.last_name', 'ASC');

        $section_adviser_student_list = SectionAdviserStudent::join('student','section_adviser_student.student_id','=','student.id')
            ->join('person','student.person_id','=','person.id')
            ->join('student_curriculum','student_curriculum.student_id','=','student.id')
            ->join('section_adviser','section_adviser_student.section_adviser_id','=','section_adviser.id')
            ->join('section_monitor','section_adviser.section_monitor_id','=','section_monitor.id')
            ->join('classification_level','section_monitor.classification_level_id','=','classification_level.id')
            ->join('classification','section_monitor.classification_id','=','classification.id')
            ->join('section','section_monitor.section_id','=','section.id')
            ->join('term','section_monitor.term_id','=','term.id')
            ->where('section_monitor.classification_level_id','=',$classification_level_id)
            ->where('section_monitor.term_id','=',$term_id)
            ->where('section_monitor.section_id',$section_id)
            ->select(array('section_adviser_student.id','section_adviser_student.student_id','student.student_no','person.last_name','person.first_name','person.middle_name','person.birthdate','classification_level.level','section.section_name','term.term_name','classification.classification_name','section_monitor.term_id', 'section_monitor.section_id','classification_level.classification_id','section_monitor.classification_level_id', 'student_curriculum.curriculum_id'))
            ->orderBy('person.last_name', 'ASC');


        return Datatables::of($section_adviser_student_list)
                ->editColumn('last_name','{{ ucwords(strtolower($last_name.", ".$first_name." ".$middle_name)) }}')
                ->add_column('actions','
                      <img class="button" src="{{{ asset("assets/site/images/teachers_portal/pace.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-birthdate="{{{$birthdate}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-classification_name="{{{$classification_name}}}" data-classification_id="{{{$classification_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-toggle="modal" data-target="#academicprojectionmodal">
                      <img class="button" src="{{{ asset("assets/site/images/teachers_portal/spc-icon.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-classification_id="{{{$classification_id}}}" data-classification_name="{{{$classification_name}}}" data-classification_level_id="{{{$classification_level_id}}}" data-toggle="modal" data-target="#spcmodal">
                      <img class="button" src="{{{ asset("assets/site/images/teachers_portal/desirable_traits.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-classification_id="{{{$classification_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-toggle="modal" data-target="#assigndtmodal">
                      <img class="button" src="{{{ asset("assets/site/images/teachers_portal/report_card.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-section_id="{{{$section_id}}}" data-classification_id="{{{$classification_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-curriculum_id="{{{$curriculum_id}}}" data-toggle="modal" data-target="#reportcardmodal">
                      <img class="button" src="{{{ asset("assets/site/images/teachers_portal/awards.jpg") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-classification_id="{{{$classification_id}}}"  data-classification_level_id="{{{$classification_level_id}}}" data-toggle="modal" data-target="#studentachievementsmodal">
                      <img class="button" src="{{{ asset("assets/site/images/teachers_portal/Conduct Remarks.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-classification_id="{{{$classification_id}}}"  data-classification_level_id="{{{$classification_level_id}}}" data-toggle="modal" data-target="#conductremarksmodal">
                      
                      <img class="button" src="{{{ asset("assets/site/images/teachers_portal/pace.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-classification_name="{{{$classification_name}}}" data-classification_id="{{{$classification_id}}}" data-classification_level_id="{{{$classification_level_id}}}" data-toggle="modal" data-target="#studentpacemodal">')
                      // <img class="button" src="{{{ asset("assets/site/images/teachers_portal/intervention.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-classification_id="{{{$classification_id}}}" data-toggle="modal" data-target="#studentinterventionmodal">
                ->remove_column('id', 'first_name', 'middle_name', 'birthdate', 'student_id', 'level', 'section_name', 'term_name', 'term_id', 'section_id', 'classification_id', 'classification_name','classification_level_id', 'curriculum_id')
                ->make();

    }
    
    public function dataJsonDesirableTrait(){

      $condition = \Input::all();
      $term_id = \Input::get('term_id');
      $classification_id = \Input::get('classification_id');
      $classification_level_id = \Input::get('classification_level_id');
      $student_id = \Input::get('student_id');

      $student_desirable_trait_id = array();
      $perfect_score_arr = array();

      $arr[0][0] = "";

      $grading_period_list = GradingPeriod::where('classification_id','=', $classification_id)->orderBy('id')->get();

      $column_no = 1;
      foreach ($grading_period_list as $grading_period) {
        # code...
        $arr[0][$column_no] = $grading_period->grading_period_name;
        $column_no++;
      }

      $desirable_trait_detail_list = DesirableTraitDetail::orderBy('id', 'ASC')->get();

      $row_no = 1;
      foreach ($desirable_trait_detail_list as $desirable_trait_detail) {
        $arr[$row_no][0] = $desirable_trait_detail->desirable_trait_detail_name;

            $column = 1;
            foreach ($grading_period_list as $grading_period) {
                $student_desirable_trait = StudentDesirableTrait::join('desirable_trait_rating','student_desirable_trait.desirable_trait_rating_id','=','desirable_trait_rating.id')->where('student_id','=',$student_id)->where('term_id','=',$term_id)->where('desirable_trait_detail_id','=',$desirable_trait_detail->id)->where('grading_period_id','=',$grading_period->id)->where('classification_level_id','=',$classification_level_id)->orderBy('id', 'ASC')->select('student_desirable_trait.id','desirable_trait_rating.code')->get()->last();
                $arr[$row_no][$column] = $student_desirable_trait->code;
                $student_desirable_trait_id[$row_no][$column] = $student_desirable_trait->id;
                $column++;
            }

        $row_no++;
      }

      $data = array($arr, $student_desirable_trait_id);
      return response()->json($data);
    }

    public function postUpdate() {

        $id=\Input::get('id');
        $rating=\Input::get('rating');
        
        $desirable_trait_rating = DesirableTraitRating::where('code',$rating)->select(['id'])->get()->last();

        $student_desirable_trait = StudentDesirableTrait::find($id);
        $student_desirable_trait->desirable_trait_rating_id = $desirable_trait_rating->id;
        $student_desirable_trait-> save();
    }

    public function postAttendance() {

        $date=\Input::get('date');
        $term_id=\Input::get('term_id');
        $classification_level_id=\Input::get('classification_level_id');
        $section_id=\Input::get('section_id');
      
        $attendance = new Attendance();
        $attendance->date = $date;
        $attendance->term_id = $term_id;
        $attendance->classification_level_id = $classification_level_id;
        $attendance->section_id = $section_id;
        $attendance-> save();

        $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
              ->where('enrollment.term_id','=',$term_id)->where('enrollment.section_id','=',$section_id)
              ->select('enrollment.id','student_curriculum.student_id')->get();

        foreach ($enrollment_list as $enrollment) {
            $student_attendance = new StudentAttendance();
            $student_attendance->student_id = $enrollment->student_id;
            $student_attendance->attendance_remark_id = 1;
            $student_attendance->attendance_id = $attendance->id;
            $student_attendance-> save();
        }
    }

    public function modifyDataJsonStudentAttendance()
    {
        $section_id = \Input::get('section_id');
        $term_id = \Input::get('term_id');
        $date_start = \Input::get('date_start');
        $date_end = \Input::get('date_end');

        $date_start = date('Y-m-d',strtotime($date_start));
        $date_end = date('Y-m-d',strtotime($date_end));

        $student_attendance = Attendance::where('attendance.section_id',$section_id)
                            ->where('attendance.term_id',$term_id)
                            ->whereBetween('attendance.date',array($date_start,$date_end))
                            ->select(['attendance.id','attendance.date'])->orderBy('attendance.date')->get();

        return response()->json($student_attendance);
    }

    public function updateStudentAttendanceDate()
    {
        $id = \Input::get('id');
        $value = \Input::get('value');

        $attendance = Attendance::find($id);
        $attendance -> date = $value;
        $attendance -> save();

        return response()->json($attendance);
    }

    public function removeStudentAttendance()
    {
        $id = \Input::get('id');

        $attendance = Attendance::find($id);
        $attendance -> delete();

        $student_attendance_list = StudentAttendance::where('student_attendance.attendance_id',$id)->get();

        foreach ($student_attendance_list as $student_attendance) {
            $student = StudentAttendance::find($student_attendance -> id);
            $student -> delete();

        }

        return response()->json($attendance);
    }
    
    public function dataJsonStudentAttendance(){

      $condition = \Input::all();
      $term_id = \Input::get('term_id');
      $classification_level_id = \Input::get('classification_level_id');
      $section_id = \Input::get('section_id');

      $id_arr = array();

      $arr[0][0] = "";
      $arr[0][1] = "ID Number";
      $arr[0][2] = "Student";

      $col_no = 3;
      $attendance_list = Attendance::where('classification_level_id','=', $classification_level_id)->where('term_id','=', $term_id)->where('section_id','=', $section_id)->orderBy('attendance.date')->get();
      foreach ($attendance_list as $attendance) {
        $arr[0][$col_no] = date("m/d/y",strtotime($attendance->date));

        $student_attendance_list = StudentAttendance::join('student','student_attendance.student_id','=','student.id')
          ->join('person','student.person_id','=','person.id')
          ->where('student_attendance.attendance_id','=',$attendance->id)
          ->select('student_attendance.id','student_attendance.student_id','student.student_no','person.last_name','person.first_name')
          ->groupBy('student_attendance.student_id')
          ->orderBy('person.last_name')
          ->get();
        $row_no = 1;
        $column_no = 1;
        foreach ($student_attendance_list as $student_attendance) {
          $arr[$row_no][0] = $column_no++;
          $arr[$row_no][1] = $student_attendance->student_no;
          $arr[$row_no][2] = $student_attendance->last_name.', '.$student_attendance->first_name;

            $attendance_remark_list = StudentAttendance::join('attendance_remark','student_attendance.attendance_remark_id','=','attendance_remark.id')
              ->where('student_attendance.attendance_id','=',$attendance->id)
              ->where('student_attendance.student_id','=',$student_attendance->student_id)
              ->select('student_attendance.id','attendance_remark.attendance_remarks_code')->get();

              foreach ($attendance_remark_list as $attendance_remark) {
                $arr[$row_no][$col_no] = $attendance_remark->attendance_remarks_code;
                $id_arr[$row_no][$col_no] = $attendance_remark->id;
              }

          $row_no++;
        }

        $col_no++;
      }


      $data = array($arr, $id_arr);
      return response()->json($data);
    }

    public function postStudentAttendance() {

        $id=\Input::get('id');
        $rating=\Input::get('rating');
        
        $attendance_remark = AttendanceRemark::where('attendance_remarks_code',$rating)->select(['id'])->get()->last();

        $student_attendance = StudentAttendance::find($id);
        $student_attendance->attendance_remark_id = $attendance_remark->id;
        $student_attendance-> save();
    }

  
}
