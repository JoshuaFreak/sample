<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Enrollment;
use App\Models\Employee;
use App\Models\Person;
use App\Models\Section;
use App\Models\SectionMonitor;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Term;
use App\Http\Requests\SectionMonitorRequest;
use App\Http\Requests\SectionMonitorEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class StudentAttendanceController extends DeansPortalMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        $classification_level_list = ClassificationLevel::all();
        $section_list = Section::all();
        $term_list = Term::all();

        return view('student_attendance_report.index',compact('classification_level_list', 'term_list','section_list'));
    }


    public function attendanceDetail(){

        $date_start = \Input::get('date_start');
        $date_from = date('Y-m-d',strtotime($date_start));
        $date_end = \Input::get('date_end');
        $date_to = date('Y-m-d',strtotime($date_end));
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');
        $section_id = \Input::get('section_id');
        
        $attendance_list = 0;
        // if($date_start != "" && $date_end != "" && $classification_level_id != "" && $term_id != "" && $section_id != "" ){
        if($classification_level_id != "" && $term_id != "" && $section_id != "" ){

            $attendance_list = Enrollment::leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                                    ->leftJoin('student','student_curriculum.student_id','=','student.id')
                                    ->leftJoin('person','student.person_id','=','person.id')
                                    ->leftJoin('section','enrollment.section_id','=','section.id')
                                    ->leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
                                    ->where('enrollment.term_id',$term_id)
                                    ->where('enrollment.section_id',$section_id)
                                    ->where('enrollment.classification_level_id',$classification_level_id)
                                    // ->whereBetween('attendance.date',array($date_from, $date_to))
                                    ->select(['student_curriculum.student_id as student_id','person.last_name','person.first_name','person.middle_name','student.student_no','section.section_name','classification_level.level','enrollment.term_id','classification_level.id as classification_level_id'])
                                    ->orderBy('person.last_name', 'ASC')
                                    ->get();

        }



        return response()->json($attendance_list);
    }


    public function attendancePresentDataJson(){
    
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');
        $student_id = \Input::get('student_id');
        

         $attendance_list = StudentAttendance::leftJoin('attendance','student_attendance.attendance_id','=','attendance.id')
                                    ->leftJoin('attendance_remark','student_attendance.attendance_remark_id','=','attendance_remark.id')
                                    ->leftJoin('classification_level','attendance.classification_level_id','=','classification_level.id')
                                    ->where('attendance.term_id',$term_id)
                                    ->where('student_attendance.student_id',$student_id)
                                    ->where('attendance.classification_level_id',$classification_level_id)
                                    // ->where('attendance_remark.attendance_remark_code','=','P')
                                    ->select(['attendance_remark.attendance_remarks_code','student_attendance.student_id'])
                                    ->get();

        $p_count = 0;
        $a_count = 0;
        $student_id = 0;
        $count = [];
        foreach ($attendance_list as $attendance) {

            $student_id = $attendance -> student_id;
            if($attendance -> attendance_remarks_code == "P")
            {
                $p_count++;
            }
            elseif($attendance -> attendance_remarks_code == "A")
            {
                $a_count++;
            }
        }       
        $count = ['student_id' => $student_id,'present' => $p_count, 'absent' => $a_count];

        return response()->json($count);
    }
     public function pdfAttendanceReport(){

        $date_start = \Input::get('date_start');
        $date_from = date('Y-m-d',strtotime($date_start));
        $date_end = \Input::get('date_end');
        $date_to = date('Y-m-d',strtotime($date_end));

        $logo = str_replace("\\","/",public_path())."/images/mzednewlogo.png";
        
        $payment_list = Payment::join('student','payment.student_id','=','student.id')
            ->join('person','student.person_id','=','person.id')
            ->select('payment.id','student.student_no','person.last_name','person.first_name','person.middle_name','payment.or_no','payment.ar_no','payment.cash_tendered','payment.amount_paid','payment.change')
            ->whereBetween('payment.transaction_date',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
            ->where('payment.void','!=',1)
            ->orderBy('payment.transaction_date')->get();

         
        $pdf = \PDF::loadView('accounting_report/collection/pdf_payment_report', array('logo'=>$logo,'payment_list'=>$payment_list,'date_start'=>$date_start,'date_end'=>$date_end))->setOrientation('landscape');

        return $pdf->stream();
        // return $pdf->download('Export_Entry.pdf');

    }
 
}
