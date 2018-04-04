<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\Campus;
use App\Models\CheckDetail;
use App\Models\ClassificationLevelFee;
use App\Models\Discount;
use App\Models\MiscellaneousFee;
use App\Models\MiscellaneousFeeDetail;
use App\Models\PaymentMode;
use App\Models\PaymentScheme;
use App\Models\Person;
use App\Models\PaymentType;
use App\Models\PaymentStatus;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentLedger;
use App\Models\StudentFeeRemove;
use App\Models\Term;
use App\Models\Training;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\PaymentDiscountRequest;
use App\Http\Requests\PaymentEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Config;    
use Hash;

class PaymentController extends AccountingMainController {
   
    /*
    return a list of country in json format based on a term
    **/

    public function search(){
      $term = Input::get('term');
      $result = Payment::where('or_no','LIKE','%$term%').orWhere('payment_name','LIKE','%$term%')->get();
      return Response::json($result);
    }

    public function dataJson(){
      $result = Payment::where('is_active',1)->get();
      return response()->json($result);
    }

 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index()
    {
        // Show the page
        return view('payment.index');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        $action = 0;
        $payment_scheme_list = PaymentScheme::orderBy('payment_scheme.payment_scheme_name', 'ASC')->get();
        $payment_mode_list = PaymentMode::orderBy('payment_mode.payment_mode_name', 'ASC')->get();
        $term_list = Term::orderBy('term.term_name', 'ASC')->get();
        $payment_type_list = PaymentType::where('payment_type.description','!=','Tuition')->where('payment_type.description','!=','Miscellaneous')->orderBy('payment_type.description', 'ASC')->get();
        $miscellaneous_fee_list = MiscellaneousFee::orderBy('miscellaneous_fee.description', 'ASC')->get();
        $miscellaneous_fee_detail_list = MiscellaneousFeeDetail::join('miscellaneous_fee','miscellaneous_fee_detail.miscellaneous_fee_id','=','miscellaneous_fee.id')->select('miscellaneous_fee_detail.id','miscellaneous_fee.description')->orderBy('miscellaneous_fee.description', 'ASC')->get();
        $discount_list = Discount::orderBy('discount.id')->get();
        $campus_list = Campus::orderBy('campus.id')->get();
        // Show the page
        return view('payment.create', compact('action','payment_scheme_list','payment_mode_list','term_list','payment_type_list','discount_list','miscellaneous_fee_list','miscellaneous_fee_detail_list'));
    }

    /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(PaymentRequest $payment_request) {

        $payment = new Payment();
        $payment ->student_id = $payment_request->student_id;
        $payment ->term_id = $payment_request->term_id;
        $payment ->ar_no = $payment_request->ar_no;
        $payment ->or_no = $payment_request->or_no;
        $payment ->payment_mode_id = $payment_request->payment_mode_id;
        $payment ->transaction_date = $payment_request->transaction_date;
        $payment ->cashier_id = Auth::user()->id;
        $payment ->cash_tendered = $payment_request->cash_tendered;
        $payment ->change = $payment_request->change;
        $payment ->amount_paid = $payment_request->amount_paid;
        $payment ->save();

        $ledger = new StudentLedger();
        $ledger ->student_id = $payment_request->student_id;
        $ledger ->term_id = $payment_request->term_id;
        $ledger ->credit = $payment_request->amount_paid;
        $ledger ->remark = 'Payment';
        $ledger ->payment_id = $payment->id;
        $ledger ->payment_type_id = 1;
        $ledger ->save();

        // $success = \Lang::get('payment.create_success').' for:'; 
        // return redirect('payment/create')->withSuccess($success,$payment);

        return response()->json($payment);
    }

    public function postCreateDiscount(PaymentRequest $payment_request) {
          
              // $payment_discount = new Payment();
              // $payment_discount ->student_id = $payment_request->student_id;
              // $payment_discount ->term_id = $payment_request->term_id;
              // $payment_discount ->ar_no = $payment_request->ar_no;
              // $payment_discount ->or_no = $payment_request->or_no;
              // $payment_discount ->payment_mode_id = $payment_request->payment_mode_id;
              // $payment_discount ->transaction_date = $payment_request->transaction_date;
              // $payment_discount ->cashier_id = Auth::user()->id;
              // $payment_discount ->cash_tendered = $payment_request->discount;
              // $payment_discount ->change = 0;
              // $payment_discount ->amount_paid = $payment_request->discount;
              // $payment_discount ->save();

              $discount_remark = Discount::find($payment_request->discount_remark);

              $ledger = new StudentLedger();
              $ledger ->student_id = $payment_request->student_id;
              $ledger ->term_id = $payment_request->term_id;
              $ledger ->credit = $payment_request->discount;
              // $ledger ->remark = 'Discount';
              $ledger ->remark = $discount_remark -> discount_name;
              $ledger ->payment_id = 0;
              $ledger ->payment_type_id = 1;
              $ledger ->save();

        return response()->json($ledger);

    }

     public function postTuition(PaymentRequest $payment_request) {

      $student_ledger_total_bal = StudentLedger::where('student_id','=',$payment_request ->student_id)->where('term_id','=',$payment_request ->term_id)->select('student_ledger.total_balance')->get()->last();
      $student_ledger_bal = StudentLedger::where('student_id','=',$payment_request ->student_id)->where('term_id','=',$payment_request ->term_id)->where('payment_type_id','=',1)->select('student_ledger.balance')->get()->last();

      $student_ledger_tuition = new StudentLedger();
      $student_ledger_tuition ->student_id = $payment_request ->student_id;
      $student_ledger_tuition ->credit = $payment_request ->tuition_val;
      // $student_ledger_tuition ->balance = $student_ledger_bal ->balance - $payment_request ->tuition_val;
      // $student_ledger_tuition ->total_balance = $student_ledger_total_bal ->total_balance - $payment_request ->tuition_val;
      $student_ledger_tuition ->remark = "Tuition";
      $student_ledger_tuition ->term_id = $payment_request ->term_id;
      $student_ledger_tuition ->payment_id = $payment_request ->payment_id;
      $student_ledger_tuition ->payment_type_id = 1;
      $student_ledger_tuition -> save();

      return response()->json($student_ledger_tuition);
    }

    public function postMiscellaneous(PaymentRequest $payment_request) {

      $student_ledger_total_bal = StudentLedger:: where('student_id','=',$payment_request ->student_id)->select('student_ledger.total_balance')->get()->last();
      $student_ledger_bal = StudentLedger:: where('student_id','=',$payment_request ->student_id)->where('payment_type_id','=',2)->select('student_ledger.balance')->get()->last();

      $student_ledger_misc = new StudentLedger();
      $student_ledger_misc ->student_id = $payment_request ->student_id;
      $student_ledger_misc ->credit = $payment_request ->misc_val;
      // $student_ledger_misc ->balance = $student_ledger_bal ->balance - $payment_request ->misc_val;
      // $student_ledger_misc ->total_balance = $student_ledger_total_bal ->total_balance - $payment_request ->misc_val;
      $student_ledger_misc ->remark = $payment_request ->remark;
      $student_ledger_misc ->term_id = $payment_request ->term_id;
      $student_ledger_misc ->payment_id = $payment_request ->payment_id;
      $student_ledger_misc ->payment_type_id = 2;
      $student_ledger_misc -> save();

      return response()->json($student_ledger_misc);
    }

    public function postOther(PaymentRequest $payment_request) {

      $student_ledger_misc = new StudentLedger();
      $student_ledger_misc ->student_id = $payment_request ->student_id;
      $student_ledger_misc ->debit = $payment_request ->price;
      // $student_ledger_misc ->balance = $student_ledger_bal ->balance - $payment_request ->misc_val;
      // $student_ledger_misc ->total_balance = $student_ledger_total_bal ->total_balance - $payment_request ->misc_val;
      $student_ledger_misc ->remark = $payment_request ->remark;
      $student_ledger_misc ->term_id = $payment_request ->term_id;
      // $student_ledger_misc ->payment_id = $payment_request ->payment_id;
      $student_ledger_misc ->payment_type_id = 0;
      $student_ledger_misc -> save();

      return response()->json($student_ledger_misc);
    }
  


 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {
        $payment = Payment::find($id);
        $student = student::find($payment->student_id);
        $person = Person::find($student->person_id);
        $payment_type_list = PaymentType::orderBy('payment_type.description', 'ASC')->get();
        $payment_mode_list = PaymentMode::orderBy('payment_mode.payment_mode_name', 'ASC')->get();
        $payment_status_list = PaymentStatus::orderBy('payment_status.payment_status_name', 'ASC')->get();
        $action = 1;

        $check_detail_list = CheckDetail::where('check_detail.payment_id',$id)->get(); 
       //var_dump($its_customs_office);
        return view('payment/edit',compact('payment','person','student','action','payment_mode_list','payment_type_list','payment_status_list','check_detail_list'));
      
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */

    public function postEdit(PaymentEditRequest $payment_edit_request) {

        $id = \Input::get('payment_id');
        $or_no = \Input::get('or_no');
        $ar_no = \Input::get('ar_no');
        $amount_paid = \Input::get('amount_paid');
        $created_at = \Input::get('created_at');
        $cash_tendered = \Input::get('cash_tendered');
        $change = \Input::get('change');
      
        $payment = Payment::find($id);
        $payment ->or_no = $or_no;        
        $payment ->ar_no = $ar_no;        
        // $payment ->amount_paid = $amount_paid;
        // $payment ->cash_tendered = $cash_tendered;
        // $payment ->change = $change;
        $payment ->transaction_date = $created_at;
        $payment ->save();

        // $ledger= StudentLedger::where('student_ledger.payment_id',$id)->select(['student_ledger.id'])->get()->last();

        // $student_ledger = StudentLedger::find($ledger -> id);
        // $student_id = $student_ledger -> student_id;
        // $student_ledger -> credit = $amount_paid;
        // $data = StudentLedger::where('student_ledger.student_id',$student_id)->select(['student_ledger.total_balance'])->get()->last();
        // $student_ledger ->  
        return response()->json($payment);
    }

    public function postCheckDetail(PaymentEditRequest $payment_edit_request) {

        $check_detail_id = \Input::get('check_detail_id');
        $payment_id = \Input::get('payment_id');
        $account_no = \Input::get('account_no');
        $bank_name = \Input::get('bank_name');
        $check_date = \Input::get('check_date');
        $check_amount = \Input::get('check_amount');

        $check_detail = CheckDetail::find($check_detail_id);
        $check_detail -> bank_name = $bank_name;
        $check_detail -> account_no = $account_no;
        $check_detail -> check_date = $check_date;
        $check_detail -> check_amount = $check_amount;
        $check_detail -> save();

        return response()->json($check_detail);
    }

    public function postRemoveFeeType() {

        $student_id = \Input::get('student_id');
        $fee_type_id = \Input::get('fee_type_id');
        $term_id = \Input::get('term_id');

        $remove = new StudentFeeRemove();
        $remove -> student_id = $student_id;
        $remove -> fee_type_id = $fee_type_id;
        $remove -> term_id = $term_id;
        $remove -> save();

        $fee_type = ClassificationLevelFee::where('classification_level_fee.fee_type_id',$fee_type_id)->select(['classification_level_fee.amount'])->get()->last();

        $student_ledger = StudentLedger::where('student_ledger.student_id',$student_id)
                              ->where('student_ledger.term_id',$term_id)
                              ->where('student_ledger.remark','Enrollment')
                              ->select(['student_ledger.id'])->get()->first();

        $student_ledger = StudentLedger::find($student_ledger -> id);
        $debit = $student_ledger -> debit;
        $student_ledger -> debit = $debit - $fee_type -> amount;
        $student_ledger -> save();

        return response()->json($fee_type -> amount);
    }

  
    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getVoid($id)
     {
        $payment = Payment::find($id);
        // Show the page
        return view('payment/void', compact('payment'));
    }

 /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postVoid(DeleteRequest $request,$id)
    {
        $payment = Payment::find($id);
        $payment -> void = 1;
        $payment -> save();

        // $ledger_list = StudentLedger::where('payment_id','=',$payment->id)->select(['student_ledger.student_id','student_ledger.credit','student_ledger.payment_type_id','student_ledger.term_id'])->get();

        // foreach ($ledger_list as $ledger) {

        //   $void = new StudentLedger();
        //   $void ->student_id = $payment ->student_id;
        //   $void ->payment_type_id = $ledger->payment_type_id;
        //   $void ->debit = $ledger->credit;
        //   $balance = StudentLedger::where('student_id','=',$ledger ->student_id)->where('student_ledger.student_id','=',$payment ->student_id)->select(['student_ledger.balance'])->get()->last();
        //   $balance = $void ->debit + $balance ->balance;
        //   $void ->balance = $balance;
        //   $total_balance = StudentLedger::where('student_id','=',$payment ->student_id)->select(['student_ledger.total_balance'])->get()->last();
        //   $total_balance = $void ->debit + $total_balance ->total_balance;
        //   $void ->student_id = $ledger ->student_id;
        //   $void ->total_balance = $total_balance;
        //   $void ->term_id = $ledger ->term_id;
        //   $void ->remark = "Void";
        //   $void -> save();

        // }
        
         return redirect('payment');
        // return response()->json($payment);
    }
/**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
 public function data()
    {   

      $date_start = \Input::get('date_start');
      $date_end = \Input::get('date_end');

        if($date_start == "" && $date_start == null && $date_end == "" && $date_end == null)
        {
              $payment_list = Payment::join('student','payment.student_id','=','student.id')
                  ->join('person','student.person_id','=','person.id')
                  ->leftJoin('suffix','person.suffix_id','=','suffix.id')
                  ->join('gen_user','payment.cashier_id','=','gen_user.id')
                  ->join('person as cashier','gen_user.person_id','=','cashier.id')
                  ->leftJoin('payment_mode','payment.payment_mode_id','=','payment_mode.id')
                  ->whereBetween('payment.created_at', array($date_start.' 00:00:00', $date_end.' 23:59:59'))
                  ->where('payment.void','!=',1)
                  ->select(array('payment.or_no','student.student_no','person.first_name','person.middle_name','person.last_name','suffix.suffix_name','cashier.first_name as cashier','payment.ar_no','payment.amount_paid','payment.cash_tendered','payment.change','payment_mode.payment_mode_name','payment.void_by_id','payment.void_remark_id','payment.date_time_void', 'payment.id','payment.created_at','payment.transaction_date'))
                  ->orderBy('payment.created_at','DESC'); 
        }
        else
        {
              $payment_list = Payment::join('student','payment.student_id','=','student.id')
                  ->join('person','student.person_id','=','person.id')
                  ->leftJoin('suffix','person.suffix_id','=','suffix.id')
                  ->join('gen_user','payment.cashier_id','=','gen_user.id')
                  ->join('person as cashier','gen_user.person_id','=','cashier.id')
                  ->leftJoin('payment_mode','payment.payment_mode_id','=','payment_mode.id')
                  ->whereBetween('payment.created_at', array($date_start.' 00:00:00', $date_end.' 23:59:59'))
                  ->where('payment.void','!=',1)
                  ->select(array('payment.or_no','student.student_no','person.first_name','person.middle_name','person.last_name','suffix.suffix_name','cashier.first_name as cashier','payment.ar_no','payment.amount_paid','payment.cash_tendered','payment.change','payment_mode.payment_mode_name','payment.void_by_id','payment.void_remark_id','payment.date_time_void', 'payment.id','payment.created_at','payment.transaction_date'))
                  ->orderBy('payment.created_at','DESC'); 
        }

        return Datatables::of($payment_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'payment/\' . $id . \'/edit\' ) }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'payment/\' . $id . \'/void\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.void") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->editColumn('first_name','{{ ucwords(strtolower($first_name." ".$middle_name." ".$last_name." ".$suffix_name)) }}')
            ->editColumn('created_at','{{date("M d,Y - h:i A", strtotime($created_at)) }}')
            // ->remove_column('id')
            ->make(true);
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
                Payment::where('id', '=', $value) -> update(array('name' => $order));
                $order++;
            }
        }
        return $list;
    }


}
