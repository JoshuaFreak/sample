<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\AccountingMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\ClassificationLevelFee;
use App\Models\StudentCurriculum;
use App\Models\FeeType;
use App\Models\MiscellaneousFee;
use App\Models\Program;
use App\Models\Term;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ClassificationLevelFeeRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;    
use Hash;

class ClassificationLevelFeeController extends AccountingMainController {
  


  public function feeDataJson(){
          $condition = \Input::all();
          $query = ClassificationLevelFee::join('fee_type','classification_level_fee.fee_type_id','=','fee_type.id')
                ->select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }
          $classification_level_fee = $query->select([ 'fee_type.fee_type_name','classification_level_fee.amount'])->get();

          return response()->json($classification_level_fee);
    }

   public function dataJson(){
          // $condition = \Input::all();
          // $query = ClassificationLevelFee::join('fee_type','classification_level_fee.fee_type_id','=','fee_type.id')
          //       ->select();

          // foreach($condition as $column => $value)
          // {
          //   $query->where($column, '=', $value);
          // }

          $student_curriculum_id = \Input::get('student_curriculum_id');
          $classification_level_id = \Input::get('classification_level_id');
          $payment_scheme_id = \Input::get('payment_scheme_id');

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

          $classification_level_fee = $amount; 
         // $classification_level_fee = $query->select([ 'fee_type.fee_type_name','classification_level_fee.amount'])->get();

          return response()->json($classification_level_fee);
    }

    public function paymentSchemeDataJson(){
          $condition = \Input::all();
          $query = ClassificationLevelFee::join('fee_type','classification_level_fee.fee_type_id','=','fee_type.id')
                ->select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }

         $classification_level_fee = $query->select([ 'fee_type.fee_type_name','classification_level_fee.amount'])->get();

          return response()->json($classification_level_fee);
    }

    public function feeTypeDataJson(){
          $condition = \Input::all();
          $query = ClassificationLevelFee::join('fee_type','classification_level_fee.fee_type_id','=','fee_type.id')
                ->select();

          foreach($condition as $column => $value)
          {
            $query->where($column, '=', $value);
          }

         $classification_level_fee = $query->select([ 'fee_type.id as fee_type_id','classification_level_fee.id','classification_level_fee.amount'])->get();

          return response()->json($classification_level_fee);
    }


    public function index()
    {
        // Show the page
        $program_list = Program::where('classification_id',5)->orderBy('program.id','ASC')->get();
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $term_list = Term::orderBy('term.id','DESC')->get();
        return view('classification_level_fee.index', compact('program_list', 'classification_list', 'term_list'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {
        // Get all the available permissions
        $action = 0;
        $classification_level_list = ClassificationLevel::all();
        $fee_type_list = FeeType::all();
        // Show the page
        return view('classification_level_fee.create',compact('action','classification_level_list', 'fee_type_list'));
    }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postCreate(ClassificationLevelFeeRequest $classification_level_fee_request) {

        $amount = [];

        if(is_array($classification_level_fee_request-> amount)){
          foreach ($classification_level_fee_request-> amount as $item) {

              $amount[] = $item;

          }
        }

        $classification_level_fee_id = [];

        if(is_array($classification_level_fee_request-> id)){
          foreach ($classification_level_fee_request-> id as $item) {

              $classification_level_fee_id[] = $item;

          }
        }

        $fee_type_id = [];

        if(is_array($classification_level_fee_request-> fee_type_id)){
          foreach ($classification_level_fee_request-> fee_type_id as $item) {

              $fee_type_id[] = $item;

          }
        }

        $amount_length = count($amount);
        for($i=0; $i < $amount_length; $i++)
        {

            $condition = $classification_level_fee_id[$i];
            $condition_amount = $amount[$i];

            if($condition != "" && $condition != null)
            {
              $classification_level_fee = ClassificationLevelFee::find($condition);
              $classification_level_fee -> amount = $amount[$i];
              $classification_level_fee -> save();
            }
            elseif($condition_amount != "" && $condition_amount != null)
            {
              $classification_level_fee = new ClassificationLevelFee();
              $classification_level_fee -> classification_level_id = $classification_level_fee_request->classification_level_id;
              $classification_level_fee -> fee_type_id = $fee_type_id[$i];
              $classification_level_fee -> amount = $amount[$i];;
              $classification_level_fee -> save();
            }
            else
            {

            }
        }

        // return response()->json($fee_type_id);
        

        // $query = MiscellaneousFee::find($classification_level_fee->miscellaneous_fee_id);
        $success = ''; 
        return redirect('classification_level_fee/create')->withSuccess($success);
    }


 public function data()
    {
      // $classification_level_fee_list = ClassificationLevelFee::join('fee_type','classification_level_fee.fee_type_id','=','fee_type.id')
      //                     ->select(['classification_level_fee.amount','fee_type.fee_type_name']);

      $classification_level_list = ClassificationLevel::where('classification_level.id','!=', 0)
                    ->select('classification_level.id','classification_level.level')->orderBy('id','ASC');

      return Datatables::of($classification_level_list)
          // ->add_column('actions', '<a href="{{{ URL::to(\'classification_level_fee/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
          //         <a href="{{{ URL::to(\'classification_level_fee/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
          //         <input type="hidden" name="row" value="{{$id}}" id="row">')
          // ->remove_column('id')
          ->make(true);
    }


}
