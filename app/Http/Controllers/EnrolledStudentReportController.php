<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\EnrollmentMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\SemesterLevel;
use App\Models\Term;
use Datatables;
use Config;
use Hash;
use Excel;
use Carbon\Carbon;

class EnrolledStudentReportController extends EnrollmentMainController {

  	public function index(){
  		$action= 0;
        $classification_list = Classification::all();
        $term_list = Term::all();
        $classification_level_list = ClassificationLevel::all();
        $semester_level_list = SemesterLevel::all();
        $section_list = Section::select('section.id','section.section_name')->where('is_active','=',1)->get();
        return view('enrollment_report.index',compact('action','classification_list','term_list','classification_level_list','semester_level_list','section_list'));
  	}



    public function getDetail()
    {

        $date_start = \Input::get('date_start');
        $date_from = date('Y-m-d',strtotime($date_start));
        $date_end = \Input::get('date_end');
        $date_to = date('Y-m-d',strtotime($date_end));
        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');



        if($date_from != "" && $date_from != null && $date_to != "" && $date_to != null && $classification_id != "" && $classification_id != null && $classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null) 
        {
            $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('payment','payment.student_id','=','student.id')
                ->where('student_curriculum.classification_id',$classification_id)
                ->where('enrollment.classification_level_id',$classification_level_id)
                ->where('enrollment.term_id',$term_id)
                ->whereBetween('enrollment.created_at',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number','enrollment.created_at','payment.amount_paid')
                ->orderBy('person.last_name','ASC')
                ->get();

        }
        elseif($date_from != "" && $date_from != null && $date_to != "" && $date_to != null ) 
        {
            $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('payment','payment.student_id','=','student.id')
                ->whereBetween('enrollment.created_at',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number','enrollment.created_at','payment.amount_paid')
                ->orderBy('person.last_name','ASC')
                ->get();
        }
        else
        {
            $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('payment','payment.student_id','=','student.id')
                ->where('student_curriculum.classification_id',$classification_id)
                ->where('enrollment.classification_level_id',$classification_level_id)
                ->where('enrollment.term_id',$term_id)
                ->whereBetween('enrollment.created_at',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number','enrollment.created_at','payment.amount_paid')
                ->orderBy('person.last_name','ASC')
                ->get();
        }

        $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
        $classification_level = ClassificationLevel::where('id',$classification_level_id)->select('classification_level.id','classification_level.level')->get()->last();
        $term = Term::where('id',$term_id)->select('term.id','term.term_name')->get()->last();
        
        return view('enrollment_report/detail', compact('enrollment_list', 'date_from', 'date_to', 'classification', 'classification_level', 'term', 'classification_id', 'classification_level_id', 'term_id')
      );

    }


  	public function pdfEnrolledStudentReport(){

        $logo = str_replace("\\","/",public_path())."/images/mzednewlogo.png";

        $date_start = \Input::get('date_start');
        $date_from = date('Y-m-d',strtotime($date_start));
        $date_end = \Input::get('date_end');
        $date_to = date('Y-m-d',strtotime($date_end));
        $classification_id = \Input::get('classification_id');
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');

        if($date_from != "" && $date_from != null && $date_to != "" && $date_to != null && $classification_id != "" && $classification_id != null && $classification_level_id != "" && $classification_level_id != null && $term_id != "" && $term_id != null) 
        {
            $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('payment','payment.student_id','=','student.id')
                ->where('student_curriculum.classification_id',$classification_id)
                ->where('enrollment.classification_level_id',$classification_level_id)
                ->where('enrollment.term_id',$term_id)
                ->whereBetween('enrollment.created_at',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number','enrollment.created_at','payment.amount_paid')
                ->orderBy('person.last_name','ASC')
                // ->groupBy('student.id')
                ->get();
        }
        elseif($date_from != "" && $date_from != null && $date_to != "" && $date_to != null ) 
        {
            $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('payment','payment.student_id','=','student.id')
                ->whereBetween('enrollment.created_at',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number','enrollment.created_at','payment.amount_paid')
                ->orderBy('person.last_name','ASC')
                ->get();
        }
        else
        {
            $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                ->join('student','student_curriculum.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->leftJoin('gender','person.gender_id','=','gender.id')
                ->join('payment','payment.student_id','=','student.id')
                ->where('student_curriculum.classification_id',$classification_id)
                ->where('enrollment.classification_level_id',$classification_level_id)
                ->where('enrollment.term_id',$term_id)
                ->whereBetween('enrollment.created_at',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name','person.birthdate','person.student_mobile_number','enrollment.created_at','payment.amount_paid')
                ->orderBy('person.last_name','ASC')
                ->get();
        }

        $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
        $classification_level = ClassificationLevel::where('id',$classification_level_id)->select('classification_level.id','classification_level.level')->get()->last();
        $term = Term::where('id',$term_id)->select('term.id','term.term_name')->get()->last();
	     
	    $pdf = \PDF::loadView('enrollment_report/pdf_enrolled_student_report', array('logo'=>$logo,'enrollment_list'=>$enrollment_list,'date_from'=>$date_from,'date_to'=>$date_to,'classification'=>$classification,'classification_level'=>$classification_level,'term'=>$term,'classification_id'=>$classification_id,'classification_level_id'=>$classification_level_id,'term_id'=>$term_id))->setOrientation('landscape');

	    return $pdf->stream();
	    // return $pdf->download('Export_Entry.pdf');

  	}



  	public function xlsEnrolledStudentReport()
    {

        Excel::create('EnrollmentReport', function($excel) {

            $excel->sheet('EnrolledStudentReport', function($sheet) {
                  
                // Manipulate first row as header
                $classification_id = \Input::get('classification_id');
		        $term_id = \Input::get('term_id');
		        $classification_level_id = \Input::get('classification_level_id');
		        $semester_level_id = \Input::get('semester_level_id');
		        $section_id = \Input::get('section_id');

		        $classification = Classification::where('id',$classification_id)->select('classification.id','classification.classification_name')->get()->last();
			    $term = Term::where('id',$term_id)->select('term.id','term.term_name')->get()->last();
			    $classification_level = ClassificationLevel::where('id',$classification_level_id)->select('classification_level.id','classification_level.level')->get()->last();
			    $semester_level = SemesterLevel::where('id',$semester_level_id)->select('semester_level.id','semester_level.semester_name')->get()->last();
			    $section = Section::where('id',$section_id)->select('section.id','section.section_name')->get()->last();

                $row = 1;
                $sheet->mergeCells('A1:B1');
                $sheet->row(1, function ($row) {  
		            // call cell manipulation methods
		            $row->setFontWeight('bold');
                });
                $sheet->getStyle('A1')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++,array(date("M d, Y (l) h:i A")));

                $sheet->row(2, function ($row) {  
                    // call cell manipulation methods
                    $row->setFontWeight('bold');
                });

                $sheet->mergeCells('A2:E2');
                $sheet->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++,array('Gakku School System'));

                $sheet->mergeCells('A3:E3');
                $sheet->getStyle('A3')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++,array('Department of Education'));
                $sheet->mergeCells('A4:E4');
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++,array('Cebu City, 600'));

                $sheet->row($row++,array());
                $sheet->mergeCells('A6:B6');
                if($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null && $classification_level_id != "" && $classification_level_id != null && $semester_level_id != "" && $semester_level_id != null && $section_id != "" && $section_id != null) 
                {
                    $sheet->row($row++, array(
                    'Classification',
                    '',
                    $classification->classification_name
                    ));
                    $sheet->mergeCells('A7:B7');
                    $sheet->row($row++, array(
                        'Term',
                        '',
                        $term->term_name
                    ));
                    $sheet->mergeCells('A8:B8');
                    $sheet->row($row++, array(
                        'Classification Level',
                        '',
                        $classification_level->level
                    ));
                    $sheet->mergeCells('A9:B9');
                    $sheet->row($row++, array(
                        'Semester Level',
                        '',
                        $semester_level->semester_name
                    ));
                    $sheet->mergeCells('A10:B10');
                    $sheet->row($row++, array(
                        'Section',
                        '',
                        $section->section_name
                    ));
                    $sheet->row($row++,array());

                    $sheet->row(12, function ($row) {
                        // call cell manipulation methods
                        $row->setFontWeight('bold');
                    });
                    $sheet->getStyle('A12:E12')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->row($row++, array(
                        'No',
                        'Student ID',
                        'Name',
                        'Address',
                        'Gender'
                    ));
                    $sheet->getStyle('A13:B1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->getStyle('E13:E1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                }
                elseif($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null && $classification_level_id != "" && $classification_level_id != null && $semester_level_id != "" && $semester_level_id != null) 
                {
                    $sheet->row($row++, array(
                    'Classification',
                    '',
                    $classification->classification_name
                    ));
                    $sheet->mergeCells('A7:B7');
                    $sheet->row($row++, array(
                        'Term',
                        '',
                        $term->term_name
                    ));
                    $sheet->mergeCells('A8:B8');
                    $sheet->row($row++, array(
                        'Classification Level',
                        '',
                        $classification_level->level
                    ));
                    $sheet->mergeCells('A9:B9');
                    $sheet->row($row++, array(
                        'Semester Level',
                        '',
                        $semester_level->semester_name
                    ));
                    $sheet->row($row++,array());

                    $sheet->row(11, function ($row) {
                        // call cell manipulation methods
                        $row->setFontWeight('bold');
                    });
                    $sheet->getStyle('A11:E11')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->row($row++, array(
                        'No',
                        'Student ID',
                        'Name',
                        'Address',
                        'Gender'
                    ));
                    $sheet->getStyle('A12:B1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->getStyle('E12:E1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                }
                elseif($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null && $classification_level_id != "" && $classification_level_id != null) 
                {
                    $sheet->row($row++, array(
                    'Classification',
                    '',
                    $classification->classification_name
                    ));
                    $sheet->mergeCells('A7:B7');
                    $sheet->row($row++, array(
                        'Term',
                        '',
                        $term->term_name
                    ));
                    $sheet->mergeCells('A8:B8');
                    $sheet->row($row++, array(
                        'Classification Level',
                        '',
                        $classification_level->level
                    ));
                    $sheet->row($row++,array());

                    $sheet->row(10, function ($row) {
                        // call cell manipulation methods
                        $row->setFontWeight('bold');
                    });
                    $sheet->getStyle('A10:E10')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->row($row++, array(
                        'No',
                        'Student ID',
                        'Name',
                        'Address',
                        'Gender'
                    ));
                    $sheet->getStyle('A11:B1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->getStyle('E11:E1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                }
                elseif($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null) 
                {
                    $sheet->row($row++, array(
                    'Classification',
                    '',
                    $classification->classification_name
                    ));
                    $sheet->mergeCells('A7:B7');
                    $sheet->row($row++, array(
                        'Term',
                        '',
                        $term->term_name
                    ));
                    $sheet->row($row++,array());

                    $sheet->row(9, function ($row) {
                        // call cell manipulation methods
                        $row->setFontWeight('bold');
                    });
                    $sheet->getStyle('A9:E9')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->row($row++, array(
                        'No',
                        'Student ID',
                        'Name',
                        'Address',
                        'Gender'
                    ));
                    $sheet->getStyle('A10:B1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->getStyle('E10:E1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                }
                elseif($classification_id != "" && $classification_id != null) 
                {
                    $sheet->row($row++, array(
                    'Classification',
                    '',
                    $classification->classification_name
                    ));

                    $sheet->row($row++,array());

                    $sheet->row(8, function ($row) {
                        // call cell manipulation methods
                        $row->setFontWeight('bold');
                    });
                    $sheet->getStyle('A8:E8')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->row($row++, array(
                        'No',
                        'Student ID',
                        'Name',
                        'Address',
                        'Gender'
                    ));
                    $sheet->getStyle('A9:B1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->getStyle('E9:E1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                }
                else
                {
                    $sheet->row($row++, array(
                    'Student List'
                    ));
                    $sheet->row($row++,array());

                    $sheet->row(8, function ($row) {
                        // call cell manipulation methods
                        $row->setFontWeight('bold');
                    });
                    $sheet->getStyle('A8:E8')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->row($row++, array(
                        'No',
                        'Student ID',
                        'Name',
                        'Address',
                        'Gender'
                    ));
                    $sheet->getStyle('A9:B1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                    $sheet->getStyle('E9:E1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                }
                
                

                //this line will extract import entry container entries from export_entry with its_tagging (hasMany) please refer to ItsImportEntryContainer Model
                //please review eloquent documentation from laravel site
                if($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null && $classification_level_id != "" && $classification_level_id != null && $semester_level_id != "" && $semester_level_id != null && $section_id != "" && $section_id != null) 
                {
                    $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->join('student','student_curriculum.student_id','=','student.id')
                        ->join('person','student.person_id','=','person.id')
                        ->leftJoin('gender','person.gender_id','=','gender.id')
                        ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
                        ->where('student_curriculum.classification_id',$classification_id)
                        ->where('enrollment.term_id',$term_id)
                        ->where('enrollment.classification_level_id',$classification_level_id)
                        ->where('enrollment.semester_level_id',$semester_level_id)
                        ->where('enrollment.section_id',$section_id)
                        ->orderBy('person.last_name','ASC')
                        ->get();
                }
                elseif($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null && $classification_level_id != "" && $classification_level_id != null && $semester_level_id != "" && $semester_level_id != null) 
                {
                    $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->join('student','student_curriculum.student_id','=','student.id')
                        ->join('person','student.person_id','=','person.id')
                        ->leftJoin('gender','person.gender_id','=','gender.id')
                        ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
                        ->where('student_curriculum.classification_id',$classification_id)
                        ->where('enrollment.term_id',$term_id)
                        ->where('enrollment.classification_level_id',$classification_level_id)
                        ->where('enrollment.semester_level_id',$semester_level_id)
                        ->orderBy('person.last_name','ASC')
                        ->get();
                }
                elseif($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null && $classification_level_id != "" && $classification_level_id != null) 
                {
                    $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->join('student','student_curriculum.student_id','=','student.id')
                        ->join('person','student.person_id','=','person.id')
                        ->leftJoin('gender','person.gender_id','=','gender.id')
                        ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
                        ->where('student_curriculum.classification_id',$classification_id)
                        ->where('enrollment.term_id',$term_id)
                        ->where('enrollment.classification_level_id',$classification_level_id)
                        ->orderBy('person.last_name','ASC')
                        ->get();
                }
                elseif($classification_id != "" && $classification_id != null && $term_id != "" && $term_id != null) 
                {
                    $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->join('student','student_curriculum.student_id','=','student.id')
                        ->join('person','student.person_id','=','person.id')
                        ->leftJoin('gender','person.gender_id','=','gender.id')
                        ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
                        ->where('student_curriculum.classification_id',$classification_id)
                        ->where('enrollment.term_id',$term_id)
                        ->orderBy('person.last_name','ASC')
                        ->get();
                }
                elseif($classification_id != "" && $classification_id != null) 
                {
                    $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->join('student','student_curriculum.student_id','=','student.id')
                        ->join('person','student.person_id','=','person.id')
                        ->leftJoin('gender','person.gender_id','=','gender.id')
                        ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
                        ->where('student_curriculum.classification_id',$classification_id)
                        ->orderBy('person.last_name','ASC')
                        ->get();
                }
                else
                {
                    $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->join('student','student_curriculum.student_id','=','student.id')
                        ->join('person','student.person_id','=','person.id')
                        ->leftJoin('gender','person.gender_id','=','gender.id')
                        ->select('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.address','gender.gender_name')
                        ->orderBy('student_curriculum.classification_id','ASC')
                        ->get();
                }
        
                $i=1;
                foreach($enrollment_list  as $enrollment)
                {
                    //ADD ROW WITH DATA TO EXCEL SHEET
                    $sheet->row( $row++, array(
                        $i++,
                        $enrollment->student_no,
                        $enrollment->last_name.",".$enrollment->first_name." ".$enrollment->middle_name,
                        $enrollment->address,
                        $enrollment->gender_name
                    ));
                } 
                $sheet->protect('gakkou', function(\PHPExcel_Worksheet_Protection $protection) {
                    $protection->setSort(true);
                });
            });
        })->export('xls');
    }

}