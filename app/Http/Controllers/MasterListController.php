<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\Enrollment;
use App\Models\EnrollmentSection;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class MasterListController extends TeachersPortalMainController {   
  
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function masterListData()
    {
        $classification_level_id = \Input::get('classification_level_id');
        $section_id = \Input::get('section_id');
        // $class_id = \Input::get('masterlist_id');
        // $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
        //     ->join('class', 'enrollment_class.class_id', '=', 'class.id')
        //     ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
        //     ->join('student', 'student_curriculum.student_id', '=', 'student.id')
        //     ->join('person', 'student.person_id', '=', 'person.id')
        //     ->where('class.id','=',$class_id)
        //     ->select(array('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
        //     ->groupBy('student.id');  
        
        // $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
        //     ->join('student','student_curriculum.student_id','=','student.id')
        //     ->join('person','student.person_id','=','person.id')
        //     ->join('classification_level','enrollment.classification_level_id','=','classification_level.id')
        //     ->join('classification','classification_level.classification_id','=','classification.id')
        //     ->join('section','enrollment.section_id','=','section.id')
        //     ->join('term','enrollment.term_id','=','term.id')
        //     ->where('enrollment.classification_level_id','=',$classification_level_id)
        //     ->where('enrollment.term_id','=',$term_id)
        //     ->select(array('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name','person.birthdate','student_curriculum.student_id','classification_level.level','section.section_name','term.term_name','classification.classification_name','enrollment.term_id', 'enrollment.section_id','classification_level.classification_id','enrollment.classification_level_id','student_curriculum.curriculum_id'))
        //     ->orderBy('person.last_name', 'ASC');



        $enrollment_list = EnrollmentSection::join('section','enrollment_section.section_id','=','section.id')
                    ->join('student','enrollment_section.student_id','=','student.id')
                    ->join('person','student.person_id','=','person.id')
                    ->join('classification_level','section.classification_level_id','=','classification_level.id')
                    ->join('classification','classification_level.classification_id','=','classification.id')
                    // ->join('enrollment','enrollment.section_id','=','section.id')
                    // ->join('term','enrollment.term_id','=','term.id')
                    ->where('section.classification_level_id','=',$classification_level_id)
                    ->where('enrollment_section.section_id','=',$section_id)
                    // ->where('enrollment.term_id','=',$term_id)
                    ->select(['enrollment_section.id','student.id as student_id','student.student_no','person.last_name','person.first_name','person.middle_name','classification_level.level','section.section_name','classification.classification_name','enrollment_section.section_id','classification_level.classification_id','section.classification_level_id']);

        return Datatables::of($enrollment_list)
                    // ->add_column('actions','<img class="button" src="{{{ asset("assets/site/images/teachers_portal/Conduct Remarks.png") }}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-section_name="{{{$section_name}}}" data-classification_id="{{{$classification_id}}}"  data-classification_level_id="{{{$classification_level_id}}}" data-toggle="modal" data-target="#conductremarksmodal">')
                    ->remove_column('id', 'student_id', 'level', 'section_name', 'section_id', 'classification_id', 'classification_name','classification_level_id')
                    ->make();  
    }
}