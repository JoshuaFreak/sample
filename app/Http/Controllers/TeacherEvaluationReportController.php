<?php namespace App\Http\Controllers;

use App\Http\Controllers\DeansPortalMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Enrollment;
use App\Models\EnrollmentSection;
use App\Models\EnrollmentClass;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\GradingPeriod;
use App\Models\Person;
use App\Models\Section;
use App\Models\SectionMonitor;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Term;
use App\Models\TEClass;
use App\Models\Teacher;
use App\Http\Requests\SectionMonitorRequest;
use App\Http\Requests\SectionMonitorEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class TeacherEvaluationReportController extends DeansPortalMainController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

        $classification_level_list = ClassificationLevel::all();
        $term_list = Term::all();
        $grading_period_list = GradingPeriod::all();

        return view('teacher_evaluation.index',compact('classification_level_list', 'term_list','grading_period_list'));
    }
    public function teacherEvaluationDetail()
    {
        $classification_level_id = \Input::get('classification_level_id');
        $term_id = \Input::get('term_id');
        $grading_period_id = \Input::get('grading_period_id');

        $teacher_evaluation_list = 0;
        $teacher_grade_list = [];
        // if($date_start != "" && $date_end != "" && $classification_level_id != "" && $term_id != "" && $section_id != "" ){
        if($classification_level_id != "" && $term_id != ""){

            
            $teacher_evaluation_list = Teacher::join('employee','teacher.employee_id','=','employee.id')
                                            ->join('person','employee.person_id','=','person.id')
                                            ->select('teacher.id','person.first_name','person.last_name')
                                            ->get();

            
             
            foreach ($teacher_evaluation_list as $teacher_evaluation) {
                $class_list = TEClass::where('class.teacher_id',$teacher_evaluation -> id)
                            ->where('class.term_id',$term_id)
                            ->select(['class.id','class.section_id'])
                            ->groupBy('class.teacher_id')
                            ->get();

                        
                        foreach ($class_list as $class) {
                            $student_list = EnrollmentSection::where('enrollment_section.section_id',$class -> section_id)
                                        ->select(['enrollment_section.student_id'])
                                        ->get();
                                    $total_student_grade = 0;
                                    $student_count = 0;
                                    foreach ($student_list as $student) {
                                        $student_count++;
                                        $grade_list = Grade::where('grade.student_id',$student -> student_id)
                                                    ->where('grade.term_id',$term_id)
                                                    ->where('grade.class_id',$class->id)
                                                    ->where('grade.grading_period_id',$grading_period_id)
                                                    ->select(['grade.computed_grade'])
                                                    ->get();
                                       
                                        $student_grade_count = 0;
                                        $student_grade = 0;
                                        foreach ($grade_list as $grade) {
                                                $student_grade_count++;
                                                $student_grade = $student_grade + $grade -> computed_grade;
                                                  
                                        }

                                        if($student_grade_count != 0)
                                        {
                                            $student_grade_all= (floatval($student_grade) / floatval($student_grade_count));
                                         
                                            $total_student_grade = $student_grade_all + $total_student_grade;
                                        }
                                        
                                    }
                                    $total_student_grade =floatval($total_student_grade) / floatval($student_count);

                                    $teacher_grade_list[] = ['teacher_id' => $teacher_evaluation ->id,'evaluation_grade' => $total_student_grade,'teacher_first_name' => $teacher_evaluation->first_name, 'teacher_last_name' => $teacher_evaluation -> last_name];
                        }
                        

                
            }



        }



        return response()->json($teacher_grade_list);

    }
 
}
