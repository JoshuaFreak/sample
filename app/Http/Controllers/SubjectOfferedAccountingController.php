<?php namespace App\Http\Controllers;

use App\Http\Controllers\AccountingMainController;
use App\Models\Classification;
use App\Models\Program;
use App\Models\Subject;
use App\Models\SubjectOffered;
use App\Models\Term;
use App\Http\Requests\SubjectOfferedRequest;
use App\Http\Requests\SubjectOfferedEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class SubjectOfferedAccountingController extends AccountingMainController {
   
 //    public function index()
 //    {
       
 //        $classification_list = Classification::all();

 //        return view('accounting/subject_offered.index',compact('classification_list'));
 //    }


 // /**
 //   * Show the form for editing the specified resource.
 //   *
 //   * @param  int  $id
 //   * @return Response
 //   */
 //    public function getEdit($id) {
 //        $action = 1;
 //        $subject_offered = SubjectOffered::find($id);
 //        $subject = Subject::find($subject_offered->subject_id);
 //        $classification = Classification::find($subject_offered->classification_id);
 //        $program = Program::find($subject_offered->program_id);
 //        $term = Term::find($subject_offered->term_id);

 //        return view('accounting/subject_offered.edit', compact('subject_offered','classification','program','term','subject','subject_offered_list', 'action'));
      
 //    }

 //  /**
 //   * Update the specified resource in storage.
 //   *
 //   * @param  int  $id
 //   * @return Response
 //   */
 //    public function postEdit(SubjectOfferedEditRequest $subject_offered_edit_request, $id) {
      
 //        $subject_offered = SubjectOffered::find($id);
 //        $subject_offered ->lec_fee_per_unit = $subject_offered_edit_request->lec_fee_per_unit;
 //        $subject_offered ->lab_fee_per_unit = $subject_offered_edit_request->lab_fee_per_unit;
 //        $subject_offered ->updated_by_id = Auth::id();
 //        $subject_offered -> save();  

 //        return redirect('accounting/subject_offered');
 //    }

 //    /**
 //     * Show a list of all the languages posts formatted for Datatables.
 //     *
 //     * @return Datatables JSON
 //     */
 //    public function data()
 //    {
 //        $classification_name = \Input::get('classification_name');
 //        $subject_offered_list = SubjectOffered::join('program', 'subject_offered.program_id', '=', 'program.id')
 //                                            ->join('term', 'subject_offered.term_id', '=', 'term.id')
 //                                            ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
 //                                            ->join('classification', 'subject_offered.classification_id', '=', 'classification.id')
 //                                            ->select(array('subject_offered.id','subject.name', 'program.program_code', 'term.term_name','subject_offered.is_approved','subject_offered.lec_fee_per_unit','subject_offered.lab_fee_per_unit'))
 //                                            ->where('classification.classification_name', '=', $classification_name)
 //                                            ->orderBy('subject_offered.created_at', 'DESC');
    
 //        return Datatables::of( $subject_offered_list)
 //            ->edit_column('is_approved', '@if ($is_approved=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
 //            ->add_column('actions', '<a href="{{{ URL::to(\'accounting/subject_offered/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>')

 //            ->add_column('actions', '@if($is_approved==1)<a href="{{{ URL::to(\'accounting/subject_offered/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>@else<a href="#" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>@endif')
 //            ->remove_column('id')
 //            ->make();
 //    }

}
