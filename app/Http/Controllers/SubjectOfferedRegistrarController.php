<?php namespace App\Http\Controllers;

use App\Http\Controllers\RegistrarMainController;
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

class SubjectOfferedRegistrarController extends RegistrarMainController {
   

//     public function index()
//     {
       
//         $classification_list = Classification::all();

//         return view('registrar/subject_offered.index',compact('classification_list'));
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return Response
//      */

//     public function getEdit($id) {
//         $action = 1;
//         $subject_offered = SubjectOffered::find($id);
//         $classification = Classification::find($subject_offered->classification_id);        
//         $program = Program::find($subject_offered->program_id);
//         $term = Term::find($subject_offered->term_id);
//         $subject = Subject::find($subject_offered->subject_id);

//         return view('registrar/subject_offered.edit', compact('subject_offered','classification','program','term','subject','action'));
      
//     }

//   /**
//    * Update the specified resource in storage.
//    *
//    * @param  int  $id
//    * @return Response
//    */
//       public function postEdit(SubjectOfferedEditRequest $subject_offered_request, $id) {
      
//             $subject_offered = SubjectOffered::find($id);
//             $subject_offered ->is_approved = $subject_offered_request->is_approved;
//             $subject_offered -> save();
     
        

//         return redirect('registrar/subject_offered');
//     }

  
// /**
//      * Remove the specified resource from storage.
//      *
//      * @param $id
//      * @return Response
//      */

//     public function data()
//     {
//         $classification_name = \Input::get('classification_name');

//         $subject_offered_list = SubjectOffered::join('classification', 'subject_offered.classification_id', '=', 'classification.id')
//                                             ->join('program', 'subject_offered.program_id', '=', 'program.id')
//                                             ->join('term', 'subject_offered.term_id', '=', 'term.id')
//                                             ->join('subject', 'subject_offered.subject_id', '=', 'subject.id')
//                                             ->select(array('subject_offered.id', 'program.program_code', 'term.term_name', 'subject.name','subject_offered.is_approved'))
//                                             ->where('classification.classification_name', '=', $classification_name)
//                                             ->orderBy('subject_offered.created_at', 'DESC');
    
//         return Datatables::of( $subject_offered_list)
//             // -> edit_column('is_approved', '@if ($is_approved=="1") <span class="glyphicon glyphicon-ok"></span> @else <span class=\'glyphicon glyphicon-remove\'></span> @endif')
//             ->add_column('actions', '<a href="{{{ URL::to(\'registrar/subject_offered/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>')
//             ->remove_column('id')
//             ->make();
//     }

}
