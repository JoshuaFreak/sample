<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\ClassificationLevelFee;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\PaymentInstallment;
use App\Models\PaymentSchemeDetails;
use App\Models\StudentCurriculum;
use App\Models\StudentLedger;
use App\Models\StudentFeeRemove;
use App\Models\Term;
use App\Http\Requests\StudentLedgerRequest;
use App\Http\Requests\StudentLedgerEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;
use DB;

class StudentLedgerController extends AccountingMainController {
   

    /*
    return a list of country in json format based on a term
    **/
    
    public function search(){
      $term = Input::get('term');
      $result = StudentLedger::where('type','LIKE','%$term%').orWhere('credit','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function totalBalanceDataJson(){

      $student_id = \Input::get('student_id');
      $term_id = \Input::get('term_id');

      $debit_list = StudentLedger::where('student_ledger.student_id','=',$student_id)
                    ->where('student_ledger.term_id','=',$term_id)
                    ->select(['student_ledger.debit'])->get();

      $credit_list = StudentLedger::where('student_ledger.term_id','=',$term_id)
                    ->where('student_ledger.student_id','=',$student_id)
                    ->where('student_ledger.payment_type_id','=',1)
                    ->where('student_ledger.debit','=',0)
                    // ->where('payment.void','!=', 1)
                    ->select('student_ledger.credit')->get();

      $value = StudentLedger::select(['student_ledger.id'])->get()->last();

      $total_debit = 0;
      $total_credit = 0;
      foreach ($debit_list as $debit) {
          $total_debit = $total_debit + $debit -> debit;
      }
      foreach ($credit_list as $credit) {
          $total_credit = $total_credit + $credit -> credit;
      }

      $total_balance = $total_debit - $total_credit;

      return response()->json($total_balance);
    }

   public function dataJson(){

      $student_id = \Input::get('student_id');
      $term_id = \Input::get('term_id');


      $classification_level = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                                  ->where('student_curriculum.student_id',$student_id)
                                  ->select(['enrollment.classification_level_id as id'])->get()->last();

      $classification_level_fee_list = ClassificationLevelFee::join('fee_type','classification_level_fee.fee_type_id','=','fee_type.id')
                                  ->where('classification_level_fee.classification_level_id',$classification_level -> id)
                                  ->select(['fee_type.id','fee_type.fee_type_name','classification_level_fee.amount'])
                                  ->get();

      $student_fee_remove_list = StudentFeeRemove::where('student_fee_remove.student_id',$student_id)
                                  ->where('student_fee_remove.term_id',$term_id)
                                  ->select(['student_fee_remove.fee_type_id'])->get();

      $debit_list = StudentLedger::where('student_ledger.student_id','=',$student_id)
                    ->where('student_ledger.term_id','=',$term_id)
                    ->select(['student_ledger.debit'])->get();

      $credit_list = StudentLedger::join('payment','student_ledger.payment_id','=','payment.id')
                    ->where('student_ledger.student_id','=',$student_id)
                    ->where('student_ledger.payment_type_id','=',1)
                    ->where('student_ledger.debit','=',0)
                    ->where('payment.void','!=', 1)
                    ->where('student_ledger.term_id','=',$term_id)
                    ->select('student_ledger.credit')->get();

      $value = StudentLedger::select(['student_ledger.id'])->get()->last();

      $total_debit = 0;
      $total_credit = 0;
      foreach ($debit_list as $debit) {
          $total_debit = $total_debit + $debit -> debit;
      }
      foreach ($credit_list as $credit) {
          $total_credit = $total_credit + $credit -> credit;
      }

      $total_balance = $total_debit - $total_credit;

      $ledger_list = StudentLedger::where('student_ledger.student_id','=',$student_id)
                    ->where('student_ledger.remark','!=','Scheme Due Fee')
                    ->where('student_ledger.remark','!=','Enrollment')
                    ->where('student_ledger.debit','!=',0)
                    ->where('student_ledger.term_id','=',$term_id)
                    ->select('student_ledger.remark','student_ledger.debit')->get();

      # ----------------------------- start of code 2016-09-06
      // // $student_ledger_list = StudentLedger::join('payment','student_ledger.payment_id','=','payment.id')
      // //               ->where('student_ledger.student_id','=',$student_id)
      // //               ->where('student_ledger.term_id','=',$term_id)
      // //               ->where('student_ledger.payment_type_id','=',1)
      // //               // ->where('payment.void','!=', 1)
      // //               ->select(DB::raw('max(student_ledger.id) as value'))
      // //               ->groupBy('student_ledger.student_id')->orderBy('student_ledger.id','ASC')->get();

      // $debit_list = StudentLedger::where('student_ledger.student_id','=',$student_id)
      //               ->select(['student_ledger.debit'])->get();

      // $credit_list = StudentLedger::join('payment','student_ledger.payment_id','=','payment.id')
      //               ->where('student_ledger.student_id','=',$student_id)
      //               ->where('student_ledger.payment_type_id','=',1)
      //               ->where('student_ledger.debit','=',0)
      //               ->where('payment.void','!=', 1)
      //               ->select('student_ledger.credit')->get();

      // $value = StudentLedger::select(['student_ledger.id'])->get()->last();

      // $total_debit = 0;
      // $total_credit = 0;
      // foreach ($debit_list as $debit) {
      //     $total_debit = $total_debit + $debit -> debit;
      // }
      // foreach ($credit_list as $credit) {
      //     $total_credit = $total_credit + $credit -> credit;
      // }

      // $total_balance = $total_debit - $total_credit;

      // $student = array();
      // $student[] = array('value' => $value->id, 'balance' => $total_debit);     

      // // foreach ($student_ledger_list as $student_balance) {
      // //   $student_bal = $student_balance -> value;

      // //     $value = StudentLedger::where('student_ledger.id','=',$student_bal)->select('student_ledger.id')->get()->first();
      // //     $balance = StudentLedger::where('student_ledger.id','=',$student_bal)->select('student_ledger.balance')->get()->first();

      // //     $student[] = array('value' => $value->id, 'balance' => $balance->balance); 
      // // }
      # ------------------------------------ end of code 2016-09-06
      $data[0] = $classification_level_fee_list;
      $data[1] = $student_fee_remove_list;
      $data[2] = $total_balance;
      $data[3] = $ledger_list;
      
      return response()->json($data);
    }


    public function balanceDataJson(){
          $condition = \Input::all();
          $query = StudentLedger::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          $student_ledger= $query->select(['student_ledger.id as value','student_ledger.balance as text'])->get()->last();

          return response()->json($student_ledger);
    }

    public function debitDataJson(){
          $condition = \Input::all();
          $query = StudentLedger::select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          $student_ledger= $query->sum('credit');

          return response()->json($student_ledger);
    }

    public function orArDataJson(){
          $condition = \Input::get('column');
          $query[] = StudentLedger::where('student_ledger.'.$condition.'','!=','0')->select([$condition])->orderBy('student_ledger.updated_at')->get()->last();

          return response()->json($query);
    }
 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        $term_list = Term::orderBy('term.id','ASC')->get();
        $payment_installment_list = PaymentInstallment::orderBy('payment_installment.id','ASC')->get(); 
        // $payment_installment_list = PaymentSchemeDetails::join('payment_installment', 'payment_scheme_details.payment_installment_id', '=', 'payment_installment.id')
        //                       ->select(['payment_scheme_details.id','payment_scheme_details.payment_installment_id','payment_scheme_details.amount', 'payment_installment.name' ])
        //                       ->groupBy('payment_scheme_details.payment_installment_id')
        //                       ->get();
        // Show the page
        return view('student_ledger.index',compact('term_list', 'payment_installment_list'));
    }

/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {
        $student_id = \Input::get('student_id');
        $term_id = \Input::get('term_id');
     
        $student_ledger_list = StudentLedger::join('term','student_ledger.term_id','=','term.id')
              ->join('payment_type','student_ledger.payment_type_id','=','payment_type.id')
              ->leftJoin('payment','student_ledger.payment_id','=','payment.id')
              ->where('student_ledger.student_id',$student_id)
              ->where('student_ledger.term_id',$term_id)
              ->select(array('student_ledger.id','payment.transaction_date','payment.or_no','student_ledger.remark','student_ledger.debit','student_ledger.credit','payment.void'))
              ->orderBy('student_ledger.id', 'ASC');

        return Datatables::of($student_ledger_list)
            ->editColumn('transaction_date','{{date("m/d/y", strtotime($transaction_date)) }}')
            ->editColumn('void','
              @if($void == 1)
                  Voided
              @endif
            ')
            ->remove_column('id')
            ->make();
    }



    public function ledgerReport()
    {
      
      $student_id = \Input::get('student_id');
      $classification_level_id = \Input::get('classification_level_id');
      $term_id = \Input::get('term_id');
      $date = \Input::get('date');
      $bond_rate = \Input::get('bond_rate');
      $note = \Input::get('note');
      $pages = \Input::get('pages');
      $payment_breakdown = \Input::get('payment_breakdown');

      $logo = str_replace("\\","/",public_path())."/images/mzednewlogo.png";

      $enrollment= Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->join('section','enrollment.section_id','=','section.id')
            ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->where('student_id',$student_id)
            ->select(['enrollment.id','section.section_name','classification_level.level'])->get()->last();

      $term = Term::where('id', $term_id)->get()->last();

      $student = Student::join('person','student.person_id','=','person.id')
            ->where('student.id',$student_id)
            ->select(['student.id','student.student_no','person.last_name','person.first_name','person.middle_name'])->get()->last();

      $classification_level_fee_name_list = ClassificationLevelFee::leftJoin('fee_type', 'classification_level_fee.fee_type_id', '=', 'fee_type.id')
            ->leftJoin('classification_level', 'classification_level_fee.classification_level_id', '=', 'classification_level.id')
            ->join('section', 'section.classification_level_id', '=', 'classification_level.id')
            ->join('enrollment_section', 'enrollment_section.section_id', '=', 'section.id')
            // ->leftJoin('student_fee_remove', 'student_fee_remove.fee_type_id', '=', 'fee_type.id')
            ->where('classification_level_fee.classification_level_id',$classification_level_id)
            ->where('enrollment_section.student_id',$student_id)
            ->select(['classification_level_fee.id', 'classification_level_fee.amount', 'classification_level_fee.fee_type_id', 'fee_type.fee_type_name', 'classification_level_fee.classification_level_id', 'enrollment_section.student_id'])
            ->get();

     $student_fee_remove_list = StudentFeeRemove::leftJoin('fee_type', 'student_fee_remove.fee_type_id', '=','fee_type.id')
            ->where('student_fee_remove.student_id',$student_id)
            ->where('student_fee_remove.term_id',$term_id)
            ->select(['student_fee_remove.id','student_fee_remove.fee_type_id','student_fee_remove.student_id'])->get();

      $student_ledger_list = StudentLedger::join('term','student_ledger.term_id','=','term.id')
              ->join('payment_type','student_ledger.payment_type_id','=','payment_type.id')
              ->join('payment','student_ledger.payment_id','=','payment.id')
              ->where('student_ledger.student_id',$student_id)
              ->where('student_ledger.term_id',$term_id)
              ->where('payment.void','!=', 1)
              ->select(['student_ledger.id','student_ledger.created_at','payment.or_no','student_ledger.remark','student_ledger.credit','payment.transaction_date','student_ledger.debit'])
              ->get();

      $student_ledger_enrollment = StudentLedger::where('student_ledger.student_id',$student_id)
              ->where('student_ledger.term_id',$term_id)
              ->where('student_ledger.remark','=', 'Enrollment')
              ->select('student_ledger.id', 'student_ledger.debit')->get()->last();


      $student_ledger_due_fee = StudentLedger::where('student_ledger.student_id',$student_id)
              ->where('student_ledger.term_id',$term_id)
              ->where('student_ledger.remark','=', 'Scheme Due Fee')
              ->select(['student_ledger.id', 'student_ledger.remark', 'student_ledger.debit'])->get()->last();

                      
      $student_ledger_discount_list = StudentLedger::where('student_ledger.student_id',$student_id)
              ->where('student_ledger.term_id',$term_id)
              ->where('student_ledger.remark','=', 'Siblings Discount')
              ->orWhere(function($query) use($term_id, $student_id){
                          $query->where('student_ledger.remark','=', 'Employee Discount')
                            ->where('student_ledger.term_id',$term_id)
                            ->where('student_ledger.student_id',$student_id);
              })
              ->orWhere(function($query) use($term_id, $student_id){
                          $query->where('student_ledger.remark','=', 'Special Discretion Discount')
                            ->where('student_ledger.term_id',$term_id)
                            ->where('student_ledger.student_id',$student_id);
              })
              ->orWhere(function($query) use($term_id, $student_id){
                          $query->where('student_ledger.remark','=', 'Full Term Payment')
                            ->where('student_ledger.term_id',$term_id)
                            ->where('student_ledger.student_id',$student_id);
              })
              ->orWhere(function($query) use($term_id, $student_id){
                          $query->where('student_ledger.remark','=', 'Half Discount')
                            ->where('student_ledger.term_id',$term_id)
                            ->where('student_ledger.student_id',$student_id);
              })
              ->orWhere(function($query) use($term_id, $student_id){
                          $query->where('student_ledger.remark','=', 'Free')
                            ->where('student_ledger.term_id',$term_id)
                            ->where('student_ledger.student_id',$student_id);
              })
              ->select(['student_ledger.id', 'student_ledger.remark','student_ledger.student_id', 'student_ledger.credit'])->get();


      $student_ledger = StudentLedger::where('student_ledger.student_id',$student_id)
              ->where('student_ledger.term_id',$term_id)
              ->select(['student_ledger.id',  'student_ledger.student_id',  'student_ledger.remark'])->get()->last();        


      $payment_scheme_details_list = PaymentSchemeDetails::join('payment_installment', 'payment_scheme_details.payment_installment_id', '=', 'payment_installment.id')
              // ->where('payment_scheme_details.payment_scheme_id',$payment_scheme_id)
              ->where('payment_scheme_details.classification_level_id',$classification_level_id)
              ->select(['payment_scheme_details.id','payment_scheme_details.amount', 'payment_scheme_details.payment_installment_id', 'payment_installment.name', 'payment_scheme_details.classification_level_id'])
              // ->groupBy('payment_scheme_details.classification_level_id')
              ->get();    


      $student_curriculum_id = \Input::get('student_curriculum_id');
      $classification_level_id = \Input::get('classification_level_id');
      $payment_scheme_id = \Input::get('payment_scheme_id');
      $payment_installment_id = \Input::get('payment_installment_id');

      $student_curriculum = StudentCurriculum::where('student_curriculum.id',$student_curriculum_id)->select(['student_curriculum.is_sped'])->get()->last();
      if($student_curriculum->is_sped == 1)
      {
          $classification_level_fee_list = ClassificationLevelFee::where('classification_level_fee.classification_level_id',19)->select(['classification_level_fee.fee_type_id','classification_level_fee.amount'])->get();
      }
      else
      {
          $classification_level_fee_list = ClassificationLevelFee::where('classification_level_fee.classification_level_id',$classification_level_id)->select(['classification_level_fee.fee_type_id','classification_level_fee.amount'])->get();
      }
     

      $amount = 0;
      foreach ($classification_level_fee_list as $classification_level_fee) {
          $amount = $amount + $classification_level_fee ->amount;
      }

      $discount = 0;
      $due_fee = 0;

      if($payment_scheme_id == 1)
      {
          foreach ($classification_level_fee_list as $classification_level_fee) {

              if($classification_level_fee -> fee_type_id == 4){
                  $discount = $classification_level_fee ->amount * .05; 
              }
              // if($classification_level_fee -> fee_type_id == 5 || $classification_level_fee -> fee_type_id == 6){
              //     $due_fee = $due_fee + $classification_level_fee ->amount; 
              // }
          }
      } 
      else
      {
          foreach ($classification_level_fee_list as $classification_level_fee) {

              if($classification_level_fee -> fee_type_id == 1 || $classification_level_fee -> fee_type_id == 2|| $classification_level_fee -> fee_type_id == 3|| $classification_level_fee -> fee_type_id == 4){
                  $due_fee = $due_fee + $classification_level_fee ->amount; 
              }
          }
      }

      $amount = $amount - $discount;
      $amount = $amount + ($due_fee * .03);
      $due_fee = $due_fee * .03;

      $payment_scheme_details = PaymentSchemeDetails::join('payment_installment', 'payment_scheme_details.payment_installment_id', '=', 'payment_installment.id')
                              ->where('payment_scheme_details.payment_scheme_id',$payment_scheme_id)
                              ->where('payment_scheme_details.classification_level_id',$classification_level_id)
                              ->select(['payment_scheme_details.id','payment_scheme_details.amount', 'payment_scheme_details.payment_installment_id', 'payment_installment.name' ])->get();

      $payment_scheme_details_amount = 0;
      $billing_amount = 0;
      $first_installment = 0;
      $second_installment = 0;
      $third_installment = 0;

      foreach ($payment_scheme_details as $payment_scheme)
      {
          if($payment_scheme -> payment_installment_id == 1)
          {
            $first_installment = $payment_scheme -> amount;
          }
          if($payment_scheme -> payment_installment_id == 2)
          {
            $second_installment = $payment_scheme -> amount;
          }
          if($payment_scheme -> payment_installment_id == 3)
          {
            $third_installment = $payment_scheme -> amount;
          }
              if($payment_scheme -> payment_installment_id == $payment_installment_id)
              {
                      $billing_amount = $payment_scheme -> amount;

              }
          $payment_scheme_details_amount = $payment_scheme_details_amount + $payment_scheme -> amount;
      }

      $enrollment_fee = $amount - $payment_scheme_details_amount;

      $debit = StudentLedger::where('student_ledger.student_id',$student_id)->where('student_ledger.term_id',$term_id)->sum('credit');

      $previous_balance = 0;
      $previous_installment_balance = 0;

      if($payment_installment_id == 1)
      {
          if($enrollment_fee > $debit)
          {
              $previous_balance = $enrollment_fee - $debit;
              $previous_installment_balance = 0;
              $debit = 0;
          }
          else
          {
              $previous_installment_balance = 0;
              $debit = $debit - $enrollment_fee;
          }
      }
      else if($payment_installment_id == 2)
      {
          $enrollment_fee = $enrollment_fee + $first_installment;
          if($enrollment_fee > $debit)
          {
              $previous_balance = $enrollment_fee - $debit - $first_installment;
              $previous_installment_balance = $enrollment_fee - $debit - $previous_balance;
              $debit = 0;
          }
          else
          {
              $debit = $debit - $enrollment_fee;
              $previous_installment_balance = 0;
          }
      }
      else if($payment_installment_id == 3)
      {
          $enrollment_fee = $enrollment_fee + $first_installment + $second_installment;
          if($enrollment_fee > $debit)
          {
              $previous_balance = $enrollment_fee - $debit - $first_installment - $second_installment;
              $previous_installment_balance = $enrollment_fee - $debit - $previous_balance;
              $debit = 0;
          }
          else
          {
              $debit = $debit - $enrollment_fee;
              // $previous_installment_balance = $enrollment_fee - $debit;
              $previous_installment_balance = 0;
          }
      }
      else
      {
          $enrollment_fee = $enrollment_fee + $first_installment + $second_installment+ $third_installment;
          if($enrollment_fee > $debit)
          {
              $previous_balance = $enrollment_fee - $debit - $first_installment - $second_installment - $third_installment;
              $previous_installment_balance = $enrollment_fee - $debit - $previous_balance;
              $debit = 0;
          }
          else
          {
              $debit = $debit - $enrollment_fee;
              $previous_installment_balance = 0;
          }
      }
      

      $pdf = \PDF::loadView('student_ledger/student_ledger_report', array('logo'=>$logo,'date'=>$date,'enrollment'=>$enrollment,'term'=>$term,'student_ledger_list'=>$student_ledger_list,'student'=>$student,'student_ledger_enrollment'=>$student_ledger_enrollment,'student_ledger'=>$student_ledger,'billing_amount' => $billing_amount,'previous_balance' => $previous_balance,'previous_installment_balance' => $previous_installment_balance,'due_fee' => $due_fee,'debit' => $debit,'bond_rate' => $bond_rate,'note' => $note,'payment_breakdown' => $payment_breakdown,'payment_scheme_details' => $payment_scheme_details,'pages' => $pages,'payment_installment_id' => $payment_installment_id,'student_id' => $student_id,'classification_level_id' => $classification_level_id,'classification_level_fee_name_list' => $classification_level_fee_name_list,'student_ledger_due_fee' => $student_ledger_due_fee,'student_ledger_discount_list' => $student_ledger_discount_list,'payment_scheme_details_list' => $payment_scheme_details_list,'enrollment_fee' => $enrollment_fee,'payment_scheme_details_amount' => $payment_scheme_details_amount,'student_fee_remove_list' => $student_fee_remove_list));

      return $pdf->stream();

      // return response()->json($student_fee_remove_list);
    }

   /**
     * Reorder items
     *
     * @param items list
     * @return items from @param
     */
    public function getReorder(ReorderRequest $request) {
        $list = $request->list;
        $items = explode(",", $list);
        $order = 1;                 
        foreach ($items as $value) {
            if ($value != '') {
                StudentLedger::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
