<?php namespace App\Http\Controllers;

use App\Http\Controllers\EnrollmentMainController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth; 
use App\Models\BloodType; 
use App\Models\Citizenship;
use App\Models\CivilStatus;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\ClassificationLevelFee;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\DesirableTraitDetail;
use App\Models\Enrollment;
use App\Models\EnrollmentClass;
use App\Models\EnrollmentSection;
use App\Models\Gender;
use App\Models\GradingPeriod;
use App\Models\Guardian;
use App\Models\LivingWith;
use App\Models\MiscellaneousFeeDetail;
use App\Models\Pace;
use App\Models\Payment;
use App\Models\PaymentScheme;
use App\Models\PaymentSchemeDetails;
use App\Models\ParentsMaritalStatus;
use App\Models\Person;
use App\Models\Program;
use App\Models\Religion;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\SemesterLevel;
use App\Models\Student;
use App\Models\StudentAcademicProjection;
use App\Models\StudentCurriculum;
use App\Models\StudentDesirableTrait;
use App\Models\StudentFamilyBackground;
use App\Models\StudentLedger;
use App\Models\SubjectOffered;
use App\Models\StudentSiblings;
use App\Models\StudentType;
use App\Models\Suffix;
use App\Models\Term;
use App\Models\TEClass;
use App\Http\Requests\EnrollmentRequest;
use App\Http\Requests\EnrollmentEditRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReorderRequest;
use Datatables;
use Config;
use Hash;

class EnrollStudentController extends EnrollmentMainController {
   
    public function search(){
        $term = Input::get('term');
        $result = Enrollment::where('name','LIKE','%$term%')->get();
        return Response::json($result);
    }

    public function index()
    {
        $classification_list = Classification::orderBy('classification.id','ASC')->get();
        $classification_level_list = ClassificationLevel::orderBy('classification_level.id','ASC')->get();
        $payment_scheme_list = PaymentScheme::all();
        $term_list = Term::all();
        return view('enroll_student.index', compact('classification_list', 'classification_level_list', 'payment_scheme_list', 'term_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getEnroll() {
        // Show the page
        $action = 0;
        $term_list = Term::all();
        $classification_level_list = ClassificationLevel::all();
        $semester_level_list = SemesterLevel::all();
        $school_year_list = SchoolYear::all();
        $payment_scheme_list = PaymentScheme::all();
        $section_list = Section::select('section.id','section.section_name')->where('is_active','=',1)->get();
        return view('enroll_student.create',compact('action', 'term_list','school_year_list','classification_level_list', 'semester_level_list', 'section_list','payment_scheme_list'));
    }

    public function getDetail()
    {

      $student_id = \Input::get('student_id');
      $student_siblings_list = StudentSiblings::where('student_siblings.student_id',$student_id)
            ->select('student_siblings.id','student_siblings.name')->get();

      return view('enroll_student/detail', compact('student_siblings_list')
      );

    }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
    public function postEnroll(EnrollmentRequest $enrollment_request) {
        
        $enrollment = new Enrollment();
        $enrollment->student_curriculum_id = $enrollment_request->student_curriculum_id;
        $enrollment->classification_level_id = $enrollment_request->classification_level_id;
        $enrollment->level_last_school_year_id = $enrollment_request->level_last_school_year;
        $enrollment->last_school_attended = $enrollment_request->last_school_attended;
        $enrollment->section_id = $enrollment_request->section_id;
        // $enrollment->is_new = $enrollment_request->is_new;
        $enrollment->payment_scheme_id = $enrollment_request->payment_scheme_id;
        $enrollment->term_id = $enrollment_request->term_id;
        $enrollment->academic_distinction = $enrollment_request->academic_distinction;
        $enrollment->extra_curricular = $enrollment_request->extra_curricular;
        $enrollment->save();

        $student_curriculum = StudentCurriculum::where('student_curriculum.id',$enrollment_request->student_curriculum_id)->select(['student_curriculum.is_sped'])->get()->last();
        if($student_curriculum->is_sped == 1)
        {
            $classification_level_fee_list = ClassificationLevelFee::where('classification_level_fee.classification_level_id',19)->select(['classification_level_fee.fee_type_id','classification_level_fee.amount'])->get();
        }
        else
        {
            $classification_level_fee_list = ClassificationLevelFee::where('classification_level_fee.classification_level_id',$enrollment_request->classification_level_id)->select(['classification_level_fee.fee_type_id','classification_level_fee.amount'])->get();
        }
       

        $amount = 0;
        foreach ($classification_level_fee_list as $classification_level_fee) {
            $amount = $amount + $classification_level_fee ->amount;
        }

        $discount = 0;
        $due_fee = 0; 

        // echo $enrollment_request->payment_scheme_id;
        // if($enrollment_request->payment_scheme_id == 1)
        // {
        //     foreach ($classification_level_fee_list as $classification_level_fee) {

        //         if($classification_level_fee -> fee_type_id == 4){
        //             $discount = $classification_level_fee ->amount * .05; 
        //         }

        //         // if($classification_level_fee -> fee_type_id == 5 || $classification_level_fee -> fee_type_id == 6){
        //         //     $due_fee = $due_fee + $classification_level_fee ->amount; 
        //         // }
        //     }

        //     $student_ledger = new StudentLedger();
        //     $student_ledger->student_id = $enrollment_request->student_id;
        //     $student_ledger->credit = $discount;
        //     $student_ledger->balance = 0;
        //     $student_ledger->total_balance = $discount;
        //     $student_ledger->remark = 'Scheme Discount';
        //     $student_ledger->term_id = $enrollment->term_id;
        //     $student_ledger->payment_id = 0;
        //     $student_ledger->payment_type_id = 1;
        //     $student_ledger->save();
        // } 
        // else
        // {
        //     foreach ($classification_level_fee_list as $classification_level_fee) {

        //         if($classification_level_fee -> fee_type_id == 1 || $classification_level_fee -> fee_type_id == 2|| $classification_level_fee -> fee_type_id == 3|| $classification_level_fee -> fee_type_id == 4){
        //             $due_fee = $due_fee + $classification_level_fee ->amount; 
        //         }
        //     } 

        //     $student_ledger = new StudentLedger();
        //     $student_ledger->student_id = $enrollment_request->student_id;
        //     $student_ledger->debit = ($due_fee * .03);
        //     $student_ledger->balance = 0;
        //     $student_ledger->total_balance = ($due_fee * .03);
        //     $student_ledger->remark = 'Scheme Due Fee';
        //     $student_ledger->term_id = $enrollment->term_id;
        //     $student_ledger->payment_id = 0;
        //     $student_ledger->payment_type_id = 1;
        //     $student_ledger->save();
        // }

        // $amount = $amount - $discount;
        // $amount = $amount + ($due_fee * .03);

        $student_ledger = new StudentLedger();
        $student_ledger->student_id = $enrollment_request->student_id;
        $student_ledger->debit = $amount;
        // $student_ledger->balance = $amount;
        // $student_ledger->total_balance = $amount;
        $student_ledger->remark = 'Enrollment';
        $student_ledger->term_id = $enrollment->term_id;
        $student_ledger->payment_id = 0;
        $student_ledger->payment_type_id = 1;
        $student_ledger->save();

        #school bond debit
        // $school_bond = 0;
        // if($enrollment_request->is_new == 1)
        // {
        //     $school_bond = 5000;
            
        //     $student_ledger = new StudentLedger();
        //     $student_ledger->student_id = $enrollment_request->student_id;
        //     $student_ledger->debit = $school_bond;
        //     $student_ledger->balance = $school_bond;
        //     $student_ledger->total_balance = $school_bond + $amount;
        //     $student_ledger->remark = 'School Bond';
        //     $student_ledger->term_id = $enrollment->term_id;
        //     $student_ledger->payment_id = 0;
        //     $student_ledger->payment_type_id = 1;
        //     $student_ledger->save();
        // }
        #end of school bond

        // $class_list = TEClass::join('subject_offered','class.subject_offered_id','=','subject_offered.id')
        //             ->where('class.term_id','=', $enrollment_request->term_id)
        //             ->where('class.section_id','=', $enrollment_request->section_id)
        //             ->where('class.classification_level_id','=', $enrollment_request->classification_level_id)
        //             ->select('class.id')->get();

        // foreach($class_list as $class)
        // {
        //     $enrollment_class = new EnrollmentClass();
        //     $enrollment_class->class_id = $class->id;
        //     $enrollment_class->enrollment_id = $enrollment->id;
        //     $enrollment_class->save();
        // }

        $enrollment_setion = new EnrollmentSection();
        $enrollment_setion->section_id = $enrollment->section_id;
        $enrollment_setion->student_id = $enrollment_request->student_id;
        $enrollment_setion->save();
     
        // $grading_period_list = GradingPeriod::select('grading_period.id')->get();

        // foreach($grading_period_list as $grading_period)
        // {
        //     $desirable_trait_detail_list = DesirableTraitDetail::select('desirable_trait_detail.id')->get();

        //     foreach($desirable_trait_detail_list as $desirable_trait_detail)
        //     {
        //         $student_desirable_trait = new StudentDesirableTrait();
        //         $student_desirable_trait->student_id = $enrollment_request->student_id;
        //         $student_desirable_trait->desirable_trait_detail_id = $desirable_trait_detail->id;
        //         $student_desirable_trait->desirable_trait_rating_id = 0;
        //         $student_desirable_trait->grading_period_id = $grading_period->id;
        //         $student_desirable_trait->term_id = $enrollment_request->term_id;
        //         $student_desirable_trait->classification_level_id = $enrollment_request->classification_level_id;
        //         $student_desirable_trait->save();
        //     }
            
        //     $pace_list = Pace::where('classification_level_id','=', $enrollment_request->classification_level_id)
        //             ->where('grading_period_id','=', $grading_period->id)->get();

        //     foreach($pace_list as $pace)
        //     {
        //         $student_academic_projection = new StudentAcademicProjection();
        //         $student_academic_projection->student_id = $enrollment_request->student_id;
        //         $student_academic_projection->classification_level_id = $enrollment_request->classification_level_id;
        //         $student_academic_projection->grading_period_id = $pace->grading_period_id;
        //         $student_academic_projection->subject_id = $pace->subject_id;
        //         $student_academic_projection->required_pace = $pace->pace_name;
        //         $student_academic_projection->term_id = $enrollment_request->term_id;
        //         $student_academic_projection->save();
        //     }
            
        //     $groupby_pace_list = Pace::where('classification_level_id','=', $enrollment_request->classification_level_id)
        //             ->where('grading_period_id','=', $grading_period->id)
        //             ->groupBy('pace.subject_id')->get();

        //     foreach($groupby_pace_list as $groupby_pace)
        //     {
        //         $student_academic_projection = new StudentAcademicProjection();
        //         $student_academic_projection->student_id = $enrollment_request->student_id;
        //         $student_academic_projection->classification_level_id = $enrollment_request->classification_level_id;
        //         $student_academic_projection->grading_period_id = $grading_period->id;
        //         $student_academic_projection->subject_id = $groupby_pace->subject_id;
        //         $student_academic_projection->required_pace = "";
        //         $student_academic_projection->term_id = $enrollment_request->term_id;
        //         $student_academic_projection->save();
            
        //         $student_academic_projection = new StudentAcademicProjection();
        //         $student_academic_projection->student_id = $enrollment_request->student_id;
        //         $student_academic_projection->classification_level_id = $enrollment_request->classification_level_id;
        //         $student_academic_projection->grading_period_id = $grading_period->id;
        //         $student_academic_projection->subject_id = $groupby_pace->subject_id;
        //         $student_academic_projection->required_pace = "";
        //         $student_academic_projection->term_id = $enrollment_request->term_id;
        //         $student_academic_projection->save();
            
        //         $student_academic_projection = new StudentAcademicProjection();
        //         $student_academic_projection->student_id = $enrollment_request->student_id;
        //         $student_academic_projection->classification_level_id = $enrollment_request->classification_level_id;
        //         $student_academic_projection->grading_period_id = $grading_period->id;
        //         $student_academic_projection->subject_id = $groupby_pace->subject_id;
        //         $student_academic_projection->required_pace = "Exam";
        //         $student_academic_projection->term_id = $enrollment_request->term_id;
        //         $student_academic_projection->save();
        //     }
        // }

        $create_success = true;
        //used for success display
        $student = Student::find($enrollment_request->student_id);
        $person = Person::find($student->person_id);
        $success = \Lang::get('student.create_success').' : '.$person->last_name. ", ".$person->first_name. " ".$person->middle_name; 
        return redirect('enroll_student/create')->withSuccess( $success);
        // exit();
        // return response()->json();
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getRead($id) {

        $action = 1;
        $enrollment = Enrollment::find($id);
        $student_curriculum = StudentCurriculum::find($enrollment->student_curriculum_id);
        $term = Term::find($enrollment->term_id);
        $classification_level = ClassificationLevel::find($enrollment->classification_level_id);
        $level_last_school_year = ClassificationLevel::find($enrollment->level_last_school_year_id);
        $section = Section::find($enrollment->section_id);

        $curriculum = Curriculum::find($student_curriculum->curriculum_id);
        $classification = Classification::find($curriculum->classification_id);
        $program = Program::find($curriculum->program_id);
        $student = Student::find($student_curriculum->student_id);
        $student_type = StudentType::find($student->student_type_id);

        $person = Person::find($student->person_id);
        $suffix = Suffix::find($person->suffix_id);
        $gender = Gender::find($person->gender_id);
        $citizenship = Citizenship::find($person->citizenship_id);
        $religion = Religion::find($person->religion_id);
        $civil_status = CivilStatus::find($person->civil_status_id);
        $blood_type = BloodType::find($person->blood_type_id);
        $living_with = LivingWith::find($person->living_with_id);


        $student_family_background = StudentFamilyBackground::find($person->student_family_background_id);
        $parents_marital_status = ParentsMaritalStatus::find($student_family_background->parents_marital_status_id);
        $student_siblings_list = StudentSiblings::all();

        return view('enroll_student/read',compact('action','enrollment','student_curriculum','term','classification_level','level_last_school_year','section','curriculum','classification','program','student','student_type','person','suffix','gender','citizenship','religion','civil_status','blood_type','living_with','student_family_background','parents_marital_status','student_siblings_list'));
      
    }

 /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
    public function getEdit($id) {

        $action = 1;
        $enrollment = Enrollment::find($id);
        $student_curriculum = StudentCurriculum::find($enrollment->student_curriculum_id);
        $term = Term::find($enrollment->term_id);
        $classification_level = ClassificationLevel::find($enrollment->classification_level_id);
        $section = Section::find($enrollment->section_id);

        $curriculum = Curriculum::find($student_curriculum->curriculum_id);
        $classification = Classification::find($curriculum->classification_id);
        $program = Program::find($curriculum->program_id);
        $student = Student::find($student_curriculum->student_id);
        $student_type = StudentType::find($student->student_type_id);

        $person = Person::find($student->person_id);
        $suffix = Suffix::find($person->suffix_id);
        $gender = Gender::find($person->gender_id);
        $citizenship = Citizenship::find($person->citizenship_id);
        $religion = Religion::find($person->religion_id);
        $civil_status = CivilStatus::find($person->civil_status_id);
        $blood_type = BloodType::find($person->blood_type_id);
        $living_with = LivingWith::find($person->living_with_id);


        $student_family_background = StudentFamilyBackground::find($person->student_family_background_id);
        $parents_marital_status = ParentsMaritalStatus::find($student_family_background->parents_marital_status_id);
        // $guardian = Guardian::find($student->guardian_id);
        // $guardian_person = Person::find($guardian->person_id);
        // $guardian_suffix = Suffix::find($guardian_person->suffix_id);

        $term_list = Term::all();
        $section_list = Section::all();
        $semester_level_list = SemesterLevel::all();
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $student_siblings_list = StudentSiblings::all();
        $payment_scheme_list = PaymentScheme::all();
        return view('enroll_student/edit',compact('action','enrollment','student_curriculum','term','classification_level','section','curriculum','classification','program','student','student_type','person','suffix','gender','citizenship','religion','civil_status','blood_type','living_with','student_family_background','parents_marital_status','term_list','section_list','semester_level_list','classification_level_list','classification_list','student_siblings_list','payment_scheme_list'));
      
    }

/**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
     

    public function getDelete($id)
    {
        $action = 1;
        $enrollment = Enrollment::find($id);
        $student_curriculum = StudentCurriculum::find($enrollment->student_curriculum_id);
        $term = Term::find($enrollment->term_id);
        $classification_level = ClassificationLevel::find($enrollment->classification_level_id);
        $section = Section::find($enrollment->section_id);

        $curriculum = Curriculum::find($student_curriculum->curriculum_id);
        $classification = Classification::find($curriculum->classification_id);
        $program = Program::find($curriculum->program_id);
        $student = Student::find($student_curriculum->student_id);
        $student_type = StudentType::find($student->student_type_id);

        $person = Person::find($student->person_id);
        $suffix = Suffix::find($person->suffix_id);
        $gender = Gender::find($person->gender_id);
        $citizenship = Citizenship::find($person->citizenship_id);
        $religion = Religion::find($person->religion_id);
        $civil_status = CivilStatus::find($person->civil_status_id);
        $blood_type = BloodType::find($person->blood_type_id);
        $living_with = LivingWith::find($person->living_with_id);


        $student_family_background = StudentFamilyBackground::find($person->student_family_background_id);
        $parents_marital_status = ParentsMaritalStatus::find($student_family_background->parents_marital_status_id);
        // $guardian = Guardian::find($student->guardian_id);
        // $guardian_person = Person::find($guardian->person_id);
        // $guardian_suffix = Suffix::find($guardian_person->suffix_id);

        $term_list = Term::all();
        $section_list = Section::all();
        $semester_level_list = SemesterLevel::all();
        $classification_level_list = ClassificationLevel::all();
        $classification_list = Classification::all();
        $student_siblings_list = StudentSiblings::all();
        $payment_scheme_list = PaymentScheme::all();

        return view('enroll_student/delete',compact('action','enrollment','payment_scheme_list' ,'student_curriculum','term','classification_level','section','curriculum','classification','program','student','student_type','person','suffix','gender','citizenship','religion','civil_status','blood_type','living_with','student_family_background','parents_marital_status','term_list','section_list','semester_level_list','classification_level_list','classification_list','student_siblings_list'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function postDelete(DeleteRequest $request,$id)
    {
        $student_ledger_list = StudentLedger::where('student_id','=',$request->student_id)->where('term_id','=',$request->term_id)->select(['student_ledger.id'])->get();
        
        foreach ($student_ledger_list as $student_ledger) {

            $data = StudentLedger::find($student_ledger -> id);
            $data -> delete();

        }

        $payment_list = Payment::where('student_id','=',$request->student_id)->where('term_id','=',$request->term_id)->select(['payment.id'])->get();
        
        foreach ($payment_list as $payment) {

            $data = Payment::find($payment -> id);
            $data -> delete();

        }

        $enrollment_section_list = EnrollmentSection::where('student_id','=',$request->student_id)->select(['enrollment_section.id'])->get();
        foreach ($enrollment_section_list as $enrollment_section) {

            $data = EnrollmentSection::find($enrollment_section -> id);
            $data -> delete();

        }

        $student_desirable_trait_list = StudentDesirableTrait::where('student_id','=',$request->student_id)->where('term_id','=',$request->term_id)->where('classification_level_id','=',$request->classification_level_id)->select(['student_desirable_trait.id'])->get();
        foreach ($student_desirable_trait_list as $student_desirable_trait) {

            $data = StudentDesirableTrait::find($student_desirable_trait -> id);
            $data -> delete();

        }

        $student_academic_projection_list = StudentAcademicProjection::where('student_id','=',$request->student_id)->where('term_id','=',$request->term_id)->where('classification_level_id','=',$request->classification_level_id)->select(['student_academic_projection.id'])->get();

        foreach ($student_academic_projection_list as $student_academic_projection) {

            $data = StudentDesirableTrait::find($student_academic_projection -> id);
            $data -> delete();

        }

        $enrollment_list = Enrollment::join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
                                ->where('student_curriculum.student_id','=',$request->student_id)
                                ->where('enrollment.term_id','=',$request->term_id)
                                ->select(['enrollment.id'])->get();
                                
        foreach ($enrollment_list as $enrollment) {

            $data = Enrollment::find($enrollment -> id);
            $data -> delete();

        }


        return redirect('enroll_student');
    }

    public function data()
    {
        $level = \Input::get('level');
        $term_id = \Input::get('term_id');


        if($level != 0 && $term_id != 0)
        {
            $enrollment_list = Enrollment::leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            // ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->leftJoin('student','student_curriculum.student_id','=','student.id')
            ->leftJoin('section','enrollment.section_id','=','section.id')
            ->leftJoin('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('term','enrollment.term_id','=','term.id')
            ->leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
            ->leftJoin('status','student.status_id','=','status.id')
            ->leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->where('enrollment.classification_level_id','=',$level)
            ->where('enrollment.term_id','=',$term_id)
            // ->where('student_curriculum.is_sped','=',0)
            ->select(array('student.student_no','person.last_name','person.first_name','person.middle_name','gender.gender_name','term.term_name','classification_level.level','status.status_name', 'enrollment.id', 'student.id as student_id','term.id as term_id', 'payment_scheme.id as payment_scheme_id'));
        }
        elseif($level != 0 && $term_id == 0){
            $enrollment_list = Enrollment::leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            // ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->leftJoin('student','student_curriculum.student_id','=','student.id')
            ->leftJoin('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('term','enrollment.term_id','=','term.id')
            ->leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
            ->leftJoin('status','student.status_id','=','status.id')
            ->leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->where('enrollment.classification_level_id','=',$level)
            // ->where('student_curriculum.is_sped','=',0)
            ->select(array('student.student_no','person.last_name','person.first_name','person.middle_name','gender.gender_name','term.term_name','classification_level.level','status.status_name', 'enrollment.id', 'student.id as student_id','term.id as term_id', 'payment_scheme.id as payment_scheme_id'));

        }
        else{

            $enrollment_list = Enrollment::leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            // ->join('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->leftJoin('student','student_curriculum.student_id','=','student.id')
            ->leftJoin('section','enrollment.section_id','=','section.id')
            ->leftJoin('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('term','enrollment.term_id','=','term.id')
            ->leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
            ->leftJoin('status','student.status_id','=','status.id')
            ->leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->where('enrollment.classification_level_id','=',$level)
            ->select(array('student.student_no','person.last_name','person.first_name','person.middle_name','gender.gender_name','term.term_name','classification_level.level','status.status_name', 'enrollment.id', 'student.id as student_id','term.id as term_id', 'payment_scheme.id as payment_scheme_id'));
   
        }
        
     
        return Datatables::of($enrollment_list)
        ->add_column('actions', '<a href="{{{ URL::to(\'enroll_student/\' . $id . \'/read\' ) }}}" class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-eye-open"></span> {{ Lang::get("form.profile") }}</a> 
                    <button type="button" class="btn btn-success btn-sm" data-enrollment_id="{{{$id}}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-payment_scheme_id="{{{$payment_scheme_id}}}" data-toggle="modal" data-target="#editEnrolledStudent">Edit</button>
                    <a href="{{{ URL::to(\'enroll_student/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
        ->edit_column('first_name', '{{{$last_name.", ".$first_name." ".$middle_name}}}')
        // ->edit_column('level', '@if($is_sped== "1") {{{$level." - Resource Class ".$section_name}}} @else {{{$level}}} @endif')
        ->remove_column('id', 'middle_name','last_name','section_name', 'term_id', 'student_id', 'payment_scheme_id')
        ->make();
    }


    public function dataAll()
    {       
        $term_id = \Input::get('term_id');

        if($term_id != 0)
        {
            $enrollment_list = Enrollment::leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->leftJoin('student','student_curriculum.student_id','=','student.id')
            ->leftJoin('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('term','enrollment.term_id','=','term.id')
            ->leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
            ->leftJoin('status','student.status_id','=','status.id')
            ->leftJoin('classification_level','.classification_level_id','=','classification_level.id')
            ->where('enrollment.term_id',$term_id)
            ->select(array('student.student_no','person.last_name','person.first_name','person.middle_name','gender.gender_name','term.term_name','classification_level.level','status.status_name', 'enrollment.id', 'student.id as student_id','term.id as term_id', 'payment_scheme.id as payment_scheme_id'));
        

  
        }

        elseif($term_id == 0){
            $enrollment_list = Enrollment::leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            ->leftJoin('student','student_curriculum.student_id','=','student.id')
            ->leftJoin('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('term','enrollment.term_id','=','term.id')
            ->leftJoin('status','student.status_id','=','status.id')
            ->leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
            ->leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->select(array('student.student_no','person.last_name','person.first_name','person.middle_name','gender.gender_name','term.term_name','classification_level.level','status.status_name', 'enrollment.id', 'student.id as student_id','term.id as term_id', 'payment_scheme.id as payment_scheme_id'));

        }

        else{
            $enrollment_list = Enrollment::leftJoin('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
            // ->leftJoin('curriculum','student_curriculum.curriculum_id','=','curriculum.id')
            ->leftJoin('student','student_curriculum.student_id','=','student.id')
            ->leftJoin('person','student.person_id','=','person.id')
            ->leftJoin('gender','person.gender_id','=','gender.id')
            ->leftJoin('term','enrollment.term_id','=','term.id')
            ->leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
            ->leftJoin('classification_level','enrollment.classification_level_id','=','classification_level.id')
            ->select(array('student.student_no','person.last_name','person.first_name','person.middle_name','gender.gender_name','term.term_name','classification_level.level','status.status_name', 'enrollment.id', 'student.id as student_id','term.id as term_id', 'payment_scheme.id as payment_scheme_id'));
        }



        return Datatables::of($enrollment_list)
        ->add_column('actions', '<a href="{{{ URL::to(\'enroll_student/\' . $id . \'/read\' ) }}}" class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-eye-open"></span> {{ Lang::get("form.profile") }}</a> 
                    
                    <a href="{{{ URL::to(\'enroll_student/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
                    // <button type="button" class="btn btn-success btn-sm edit_scheme" data-enrollment_id="{{{$id}}}" data-student_id="{{{$student_id}}}" data-student_no="{{{$student_no}}}" data-last_name="{{{$last_name}}}" data-first_name="{{{$first_name}}}" data-middle_name="{{{$middle_name}}}" data-level="{{{$level}}}" data-term_name="{{{$term_name}}}" data-term_id="{{{$term_id}}}" data-payment_scheme_id="{{{$payment_scheme_id}}}" data-toggle="modal" data-target="#editEnrolledStudent">Edit</button>
                    // <a href="{{{ URL::to(\'enroll_student/\' . $id . \'/edit\' ) }}}" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-pencil"></span> {{ Lang::get("form.edit") }}</a>
        ->edit_column('first_name', '{{{$last_name.", ".$first_name." ".$middle_name}}}')
        ->remove_column('id', 'middle_name','last_name','section_name', 'term_id', 'student_id', 'payment_scheme_id')
        ->make();
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
    // public function postEdit(EnrollmentEditRequest $enrollment_edit_request, $id) {
        
    //     $enrollment = Enrollment::find($id);
    //     $enrollment->payment_scheme_id = $enrollment_edit_request->payment_scheme_id;
    //     $enrollment->save();

    //     return redirect('enroll_student');
    // }

    public function editdataJson(){

      $condition = \Input::all();
      $query = Enrollment::leftJoin('payment_scheme','enrollment.payment_scheme_id','=','payment_scheme.id')
            ->select(array('enrollment.id',  'enrollment.payment_scheme_id'));

      foreach($condition as $column => $value)
      {
        $query->where('enrollment.id', '=', $value);    
      }

      $enrollment = $query->orderBy('enrollment.id','ASC')->get();

      return response()->json($enrollment);

      $result = Enrollment::get();
       return response()->json($result);
    }
    
    public function postEditdataJson() {

        $id=\Input::get('id');
        $enrollment_id=\Input::get('enrollment_id');
        $scheme_id=\Input::get('payment_scheme_id');
      
        $enrollment = Enrollment::find($id);
        $enrollment->payment_scheme_id = $scheme_id;
        $enrollment -> updated_by_id = Auth::id();

        $student_curriculum_id = $enrollment -> student_curriculum_id;
        $term_id = $enrollment -> term_id;
        $classification_level_id = $enrollment -> classification_level_id;
        // $scheme_id = $enrollment -> scheme_id;

        $enrollment -> save();

        $student_curriculum = StudentCurriculum::find($student_curriculum_id);
        $student_id = $student_curriculum -> student_id;

        $student_discount_ledger = StudentLedger::where('student_ledger.student_id',$student_id)
                        ->where('student_ledger.remark','Scheme Discount')->count();
        $student_discount_ledger_id = StudentLedger::where('student_ledger.student_id',$student_id)
                        ->where('student_ledger.remark','Scheme Discount')->select(['student_ledger.id'])->get()->last();
        $student_due_ledger = StudentLedger::where('student_ledger.student_id',$student_id)
                        ->where('student_ledger.remark','Scheme Due Fee')->count();
        $student_due_ledger_id = StudentLedger::where('student_ledger.student_id',$student_id)
                        ->where('student_ledger.remark','Scheme Due Fee')->select(['student_ledger.id'])->get()->last();


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
        if($scheme_id == 1)
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

        if($student_due_ledger == 0 && $student_discount_ledger == 0)
        {
            if($scheme_id == 1)
            {
                $ledger = new StudentLedger();
                $ledger -> student_id = $student_id; 
                $ledger -> term_id = $term_id; 
                $ledger -> credit = $discount; 
                $ledger -> remark = 'Scheme Discount'; 
                $ledger -> payment_type_id = 1; 
                $ledger -> save(); 
            }
            else
            {
                $ledger = new StudentLedger();
                $ledger -> student_id = $student_id;
                $ledger -> term_id = $term_id;  
                $ledger -> debit = $due_fee * .03; 
                $ledger -> remark = 'Scheme Due Fee'; 
                $ledger -> payment_type_id = 1; 
                $ledger -> save(); 
            }

        }
        else if($student_due_ledger == 0 && $student_discount_ledger !=0)
        {
            if($scheme_id == 1)
            {
                $ledger = StudentLedger::find($student_discount_ledger_id -> id);
                $ledger -> student_id = $student_id; 
                $ledger -> term_id = $term_id; 
                $ledger -> debit = 0; 
                $ledger -> credit = $discount; 
                $ledger -> remark = 'Scheme Discount'; 
                $ledger -> term_id = $term_id; 
                $ledger -> payment_type_id = 1; 
                $ledger -> save(); 
            }
            else
            {
                $ledger = StudentLedger::find($student_discount_ledger_id -> id);
                $ledger -> student_id = $student_id; 
                $ledger -> term_id = $term_id; 
                $ledger -> debit = $due_fee * .03; 
                $ledger -> credit = 0; 
                $ledger -> remark = 'Scheme Due Fee'; 
                $ledger -> term_id = $term_id;
                $ledger -> payment_type_id = 1; 
                $ledger -> save();
            }
                

        }
        else if($student_discount_ledger == 0 && $student_due_ledger !=0)
        {
            if($scheme_id == 1)
            {
                $ledger = StudentLedger::find($student_due_ledger_id -> id);
                $ledger -> student_id = $student_id; 
                $ledger -> term_id = $term_id; 
                $ledger -> debit = 0; 
                $ledger -> credit = $discount; 
                $ledger -> remark = 'Scheme Discount'; 
                $ledger -> term_id = $term_id;
                $ledger -> payment_type_id = 1; 
                $ledger -> save(); 
            }
            else
            {
                $ledger = StudentLedger::find($student_due_ledger_id -> id);
                $ledger -> student_id = $student_id;
                $ledger -> term_id = $term_id;  
                $ledger -> debit = $due_fee * .03; 
                $ledger -> credit = 0; 
                $ledger -> remark = 'Scheme Due Fee'; 
                $ledger -> term_id = $term_id;
                $ledger -> payment_type_id = 1; 
                $ledger -> save();
            }
        }

        return response()->json($ledger);

    }

  

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    
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
                Enrollment::where('id', '=', $value) -> update(array('student_curriculum_id' => $order));
                $order++;
            }
        }
        return $list;
    }


}
