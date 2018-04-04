<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Section;
use App\Models\SemesterLevel;
use App\Models\StudentLedger;
use App\Models\Term;
use App\Models\Enrollment;
use App\Models\Generic\GenUser;
use Datatables;
use Config;    
use Hash;
use Excel;
use Carbon\Carbon;

class PaymentReportController extends AccountingMainController {

    public function index(){
        return view('accounting_report/collection.index');
    }

    public function Collectiondetail(){

        $date_start = \Input::get('date_start');
        $date_from = date('Y-m-d',strtotime($date_start));
        $date_end = \Input::get('date_end');
        $date_to = date('Y-m-d',strtotime($date_end));
        $is_or = \Input::get('is_or');
        $is_ar = \Input::get('is_ar');

        if($is_or){
            $payment_list = Payment::join('student','payment.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->select('payment.id','student.student_no','person.last_name','person.first_name','person.middle_name','payment.or_no','payment.ar_no','payment.cash_tendered','payment.amount_paid','payment.change')
                ->whereBetween('payment.transaction_date',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->where('payment.void','!=',1)
                ->where('or_no', '!=' ,'')
                ->orderBy('payment.transaction_date')->get();

        }elseif($is_ar){
            $payment_list = Payment::join('student','payment.student_id','=','student.id')
                ->join('person','student.person_id','=','person.id')
                ->select('payment.id','student.student_no','person.last_name','person.first_name','person.middle_name','payment.or_no','payment.ar_no','payment.cash_tendered','payment.amount_paid','payment.change')
                ->whereBetween('payment.transaction_date',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->where('payment.void','!=',1)
                ->where('ar_no', '!=','')
                ->orderBy('payment.transaction_date')->get();

        }else{
            $payment_list = Payment::leftJoin('student','payment.student_id','=','student.id')
                ->leftJoin('person','student.person_id','=','person.id')
                ->select('payment.id','student.student_no','person.last_name','person.first_name','person.middle_name','payment.or_no','payment.ar_no','payment.cash_tendered','payment.amount_paid','payment.change')
                ->whereBetween('payment.transaction_date',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                ->where('payment.void','!=',1)
                ->orderBy('payment.transaction_date')->get();
        }

        return view('accounting_report/collection.detail', compact('payment_list', 'date_start', 'date_end', 'is_or', 'is_ar'));
    }


    public function pdfPaymentReport(){

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



    public function xlsPaymentReport()
    {

        Excel::create('CollectionReport', function($excel) {

            $excel->sheet('CollectionReport', function($sheet) {
                  
                // Manipulate first row as header
                $date_start = \Input::get('date_start');
                $date_from = date('Y-m-d',strtotime($date_start));
                $date_end = \Input::get('date_end');
                $date_to = date('Y-m-d',strtotime($date_end));
              
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

                $sheet->mergeCells('A2:H2');
                $sheet->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++,array('Gakku School System'));

                $sheet->mergeCells('A3:H3');
                $sheet->getStyle('A3')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++,array('Department of Education'));
                $sheet->mergeCells('A4:H4');
                $sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++,array('Cebu City, 600'));

                $sheet->row($row++,array());
                $sheet->mergeCells('A6:C6');
                $sheet->row($row++, array(
                    'Payment Report'
                ));
                $sheet->mergeCells('A7:C7');
                $sheet->row($row++, array(
                    $date_start." - ".$date_end
                ));
                $sheet->row($row++,array());

                $sheet->row(9, function ($row) {
                    // call cell manipulation methods
                    $row->setFontWeight('bold');
                });
                $sheet->getStyle('A9:H9')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->row($row++, array(
                    'No',
                    'Student ID',
                    'Name',
                    'OR No',
                    'AR No',
                    'Cash Tendered',
                    'Amount Paid',
                    'Change',
                ));

                $sheet->getStyle('A10:E1000')->getAlignment()->applyFromArray(array('horizontal' => 'center'));
                $sheet->getStyle('F10:H1000')->getAlignment()->applyFromArray(array('horizontal' => 'right'));

                //this line will extract import entry container entries from export_entry with its_tagging (hasMany) please refer to ItsImportEntryContainer Model
                //please review eloquent documentation from laravel site
                $payment_list = Payment::join('student','payment.student_id','=','student.id')
                    ->join('person','student.person_id','=','person.id')
                    ->select('payment.id','student.student_no','person.last_name','person.first_name','person.middle_name','payment.or_no','payment.ar_no','payment.cash_tendered','payment.amount_paid','payment.change')
                    ->whereBetween('payment.created_at',array($date_from.' 00:00:00', $date_to.' 23:59:59'))
                    ->where('payment.void','!=',1)
                    ->get();

                $i= 1;
                $total_amount = 0;
                foreach($payment_list as $payment)
                {
                    $total_amount = $total_amount + $payment->amount_paid;
                    //ADD ROW WITH DATA TO EXCEL SHEET
                    $sheet->row( $row++, array(
                        $i++,
                        $payment->student_no,
                        $payment->last_name.",".$payment->first_name." ".$payment->middle_name,
                        $payment->or_no,
                        $payment->ar_no,
                        '₱ '.number_format($payment->cash_tendered, 2),
                        '₱ '.number_format($payment->amount_paid, 2),
                        '₱ '.number_format($payment->change, 2)
                    ));
                } 

                $sheet->row( $row++, array(
                        '-',
                        '-',
                        '-',
                        '-',
                        '-',
                        'TOTAL',
                        '₱ '.number_format($total_amount, 2),
                        '-'
                    ));
                $sheet->protect('gakkou', function(\PHPExcel_Worksheet_Protection $protection) {
                    $protection->setSort(true);
                });
            });
        })->export('xls');
    }

    public function GraphCollection(){
        $data=0;
        $term_list = Term::all()->sortByDesc('term_name');
        return view('accounting_report/graph_collection.index',compact('data','term_list'));
    }

    public function GraphCollectiondataJson(){

        $condition = \Input::all();
        $date_start = \Input::get('date_start');
        $date_end = \Input::get('date_end');
        $term_id = \Input::get('term_id');

        $month_arr = ['','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // $row_no = 0;
        // $classification_list = Classification::select('classification.id','classification.classification_name')->get();
        // foreach ($classification_list as $classification) {
        //     $classification_arr[$row_no] = array("label" => $classification->classification_name);
            
        //     $payment_list = Payment::
        //     // leftJoin('student','payment.student_id','=','student.id')
        //         // ->leftJoin('student_curriculum','student.student_id','=','student_curriculum.id')
        //         where('student_curriculum.classification_id','=',$classification->id)
        //         ->whereBetween('payment.created_at', array($date_start.' 00:00:00', $date_end.' 23:59:59'))
        //         ->where('payment.void','!=',1)
        //         ->select('payment.id','payment.amount_paid','payment.created_at')
        //         ->orderBy('payment.created_at', 'ASC')->get();

        $month_start = date('m',strtotime($date_start));
        $month_end = date('m',strtotime($date_end));
        $year_start = date('Y',strtotime($date_start));
        $year_end = date('Y',strtotime($date_end));
        $day_start = date('d',strtotime($date_start));
        $day_end = date('d',strtotime($date_end));

        $date_arr = array();

        $month_range_arr = array();

        if($year_end == $year_start)
        {
            $start_month = $month_start;
            for ($month_start; $month_start <= $month_end; $month_start++) { 
                
                if($month_start == $month_end)
                {
                    $month_start = sprintf("%02d", $month_start);
                    $last_date = date($year_start."-".$month_start."-".$day_end);
                    $date = $year_start."-".$month_start."-01";

                    $month_range_arr[] = substr($month_arr[intval($month_start)],0,3)."(01 - ".$day_end.")";

                    $date_arr[] = $arrayName = array('date_start' => $date,'date_end' => $last_date );
                }
                elseif($start_month == $month_start)
                {
                    $last_date = date("Y-m-t", strtotime($year_start."-".$month_start."-01"));
                    $date = $year_start."-".$month_start."-".$day_start;

                    $last_date_day = date('d',strtotime($last_date));

                    $month_range_arr[] = substr($month_arr[intval($month_start)],0,3)."(".$day_start." - ".$last_date_day.")";

                    $date_arr[] = $arrayName = array('date_start' => $date,'date_end' => $last_date );
                }   
                else
                {   
                    $month_start = sprintf("%02d", $month_start);
                    $last_date = date("Y-m-t", strtotime($year_start."-".$month_start."-01"));
                    $date = $year_start."-".$month_start."-01";

                    $last_date_day = date('d',strtotime($last_date));
                    $month_range_arr[] = substr($month_arr[intval($month_start)],0,3)."(01 - ".$last_date_day.")";

                    $date_arr[] = $arrayName = array('date_start' => $date,'date_end' => $last_date );
                }
            }
        }
        else
        {
            $start_month = $month_start;
            for ($month_start; $month_start <= 12 ; $month_start++) { 

                if($start_month == $month_start)
                {
                    $last_date = date("Y-m-t", strtotime($year_start."-".$month_start."-01"));
                    $date = $year_start."-".$month_start."-".$day_start;

                    $last_date_day = date('d',strtotime($last_date));

                    $month_range_arr[] = substr($month_arr[intval($month_start)],0,3)."(".$day_start." - ".$last_date_day.")";

                    $date_arr[] = $arrayName = array('date_start' => $date,'date_end' => $last_date );
                }   
                else
                {   
                    $month_start = sprintf("%02d", $month_start);
                    $last_date = date("Y-m-t", strtotime($year_start."-".$month_start."-01"));
                    $date = $year_start."-".$month_start."-01";

                    $last_date_day = date('d',strtotime($last_date));
                    $month_range_arr[] = substr($month_arr[intval($month_start)],0,3)."(01 - ".$last_date_day.")";

                    $date_arr[] = $arrayName = array('date_start' => $date,'date_end' => $last_date );
                }
            }

            for ($i = 1; $i <= $month_end ; $i++) { 

                if($i == $month_end)
                {
                    $month_start = sprintf("%02d", $month_start);
                    $last_date = date($year_start."-".$month_start."-".$day_end);
                    $date = $year_start."-".$month_start."-01";

                    $month_range_arr[] = substr($month_arr[intval($i)],0,3)."(01 - ".$day_end.")";

                    $date_arr[] = $arrayName = array('date_start' => $date,'date_end' => $last_date );
                }
                else
                {   
                    $month_start = sprintf("%02d", $month_start);
                    $last_date = date("Y-m-t", strtotime($year_start."-".$month_start."-01"));
                    $date = $year_start."-".$month_start."-01";

                    $last_date_day = date('d',strtotime($last_date));
                    $month_range_arr[] = substr($month_arr[intval($i)],0,3)."(01 - ".$last_date_day.")";

                    $date_arr[] = $arrayName = array('date_start' => $date,'date_end' => $last_date );
                }
            }
        }    
        $row_no = 0;
        $classification_list = Classification::select(['classification.id','classification.classification_name'])->get();
        

        foreach ($classification_list as $classification) {

            if($classification -> id != 0) 
            {
                $classification_arr[$row_no] = array("label" => $classification->classification_name);

                $payment = array();
                foreach ($date_arr as $date) {
                    $date_end_check = date("Y-m", strtotime($date['date_end']));
                    $day_end_check = date("d", strtotime($date['date_end']));
                    $day_end_check = $day_end_check + 1;
                    $date_end_check = $date_end_check."-".$day_end_check;

                    // echo $date_end_check;
                    $payment_list = Payment::leftJoin('student_ledger','payment.id','=','student_ledger.payment_id')
                        ->leftJoin('student','payment.student_id','=','student.id')
                        ->leftJoin('student_curriculum','student_curriculum.student_id','=','student.id')
                        ->where('student_curriculum.classification_id','=',$classification->id)
                        ->whereBetween('student_ledger.created_at', array($date['date_start'], $date_end_check))
                        ->where('payment.void','!=',1)
                        // ->select(['payment.id','payment.amount_paid','payment.created_at'])
                        // ->orderBy('payment.created_at', 'ASC')->get();
                        ->sum('payment.amount_paid');
                    $payment[] = $payment_list;
                    // $payment_count = Payment::join('student','payment.student_id','=','student.id')
                    //     ->leftJoin('student_curriculum','student_curriculum.student_id','=','student.id')
                    //     ->where('student_curriculum.classification_id','=',$classification->id)
                    //     ->whereBetween('payment.created_at', array($date['date_start'].' 00:00:00', $date['date_end'].' 23:59:59'))
                    //     ->where('payment.void','!=',1)->count();

                    // foreach ($payment_list as $payment) {

                  
                    //     $row_no++;
                    // }

                }
                $series[] = $payment;
            }
            
        }

        $classification_level_list = ClassificationLevel::select(['classification_level.id','classification_level.level'])->get();
        
        $classification_level_arr = array();
        $series1 = array();
        foreach ($classification_level_list as $classification_level) {

            if($classification_level -> id != 0) 
            {
                
                $enrolled_student = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                        ->where('enrollment.term_id',$term_id)
                        ->where('enrollment.classification_level_id',$classification_level -> id)
                        // ->select(['enrollment.classification_level_id'.'student_curriculum.student_id'])
                        ->count();


                $student_number[] = array('value' => $enrolled_student,'text' => $classification_level->level);

                if($enrolled_student != 0)
                {
                    $classification_level_arr[] = $classification_level->level;
                    $series1[] = $enrolled_student;    
                }
                
            }
        }


        

        $data[0] = array('labels' => $month_range_arr,'series' => $series);
        $data[1] = array('labels' => $classification_level_arr,'series' => $series1);
        $data[2] = $student_number;
        return response()->json($data);
    }

    public function CashierCollection(){
        return view('accounting_report/cashier_collection.index');
    }

    public function cashierCollectiondetail(){

        $cashier_id = \Input::get('cashier_id');
        $date_start = \Input::get('date_start');
        $date_end = \Input::get('date_end');

        $gen_user= GenUser::join('person','gen_user.person_id','=','person.id')
            ->where('gen_user.id',$cashier_id)
            ->select('gen_user.id','person.last_name','person.first_name','person.middle_name')->get()->last();

        $payment_list= Payment::join('student','payment.student_id','=','student.id')
            ->join('person','student.person_id','=','person.id')
            ->where('payment.cashier_id',$cashier_id)
            ->whereBetween('payment.created_at', array($date_start.' 01:00:00', $date_end.' 23:59:59'))
            ->where('payment.void','!=',1)
            ->select('payment.id','student.student_no','person.last_name','person.first_name','person.middle_name','payment.or_no','payment.cash_tendered','payment.amount_paid','payment.change','payment.created_at')->orderBy('payment.created_at')->get();
        if($gen_user == null || $payment_list == null){
            return view('accounting_report/cashier_collection.empty');
        }else{
            return view('accounting_report/cashier_collection.detail', compact('gen_user', 'payment_list'));
        }
    }

    public function cashierCollectionpdf(){

        $cashier_id = \Input::get('cashier_id');
        $year_start = \Input::get('year_start');
        $year_end = \Input::get('year_end');
        
        $date_from=date("M d, Y",strtotime($year_start));
        $date_to=date("M d, Y",strtotime($year_end));

        $logo = str_replace("\\","/",public_path())."/images/mzednewlogo.png";

        $gen_user= GenUser::join('person','gen_user.person_id','=','person.id')
            ->where('gen_user.id',$cashier_id)
            ->select('gen_user.id','person.last_name','person.first_name','person.middle_name')->get()->last();

        $payment_list= Payment::join('student','payment.student_id','=','student.id')
            ->join('person','student.person_id','=','person.id')
            ->where('payment.cashier_id',$cashier_id)
            ->whereBetween('payment.created_at', array($year_start.' 00:00:00', $year_end.' 23:59:59'))
            ->where('payment.void','!=',1)
            ->select('payment.id','student.student_no','person.last_name','person.first_name','person.middle_name','payment.or_no','payment.cash_tendered','payment.amount_paid','payment.change','payment.created_at')->orderBy('payment.created_at')->get();

        $pdf = \PDF::loadView('accounting_report/cashier_collection.cashier_collection_pdf', array('logo'=>$logo,'gen_user'=>$gen_user,'payment_list'=>$payment_list,'date_from'=>$date_from,'date_to'=>$date_to))->setOrientation('landscape');

        return $pdf->stream();
    }

}