<?php namespace App\Http\Controllers;

use App\Http\Controllers\TeachersPortalMainController;
use App\Models\ClassStandingComponent;
use App\Models\ClassStandingComponentDetail;
use App\Models\ClassStandingScoreEntry;
use App\Models\Enrollment;
use App\Models\EnrollmentClass;
use App\Models\EnrollmentSection;
use App\Models\Grade;
use App\Models\GradingPeriod;
use App\Models\StudentAcademicProjection;
use App\Models\TEClass;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class BEGradeComputationController extends TeachersPortalMainController { 
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {      
        $class_id = \Input::get('class_id');
        $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->where('class.id','=',$class_id)
                ->select(array('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  

        return Datatables::of($enrollment_list)
                ->add_column('first','')
                ->add_column('second','')
                ->add_column('third','')
                ->add_column('fourth','')
                ->add_column('final','')
                ->add_column('is_editable','')
                ->add_column('is_graduating','')
                ->remove_column('id')
                ->make();

    }
    
    public function dataJson(){
        $condition = \Input::all();
        $query = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
                ->join('class', 'enrollment_class.class_id', '=', 'class.id')
                ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
                ->join('student', 'student_curriculum.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->select(array('enrollment.id','student.id as student_id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  

        foreach($condition as $column => $value)
        {
            $query->where('enrollment_class.'.$column, '=', $value);
        }
        $enrollment = $query->get();

        return response()->json($enrollment);
    }
    
    public function dataJsonGradeComputationPace(){

      $class_id = \Input::get('class_id');
      $term_id = \Input::get('term_id');
      $classification_id = \Input::get('classification_id');
      $subject_id = \Input::get('subject_id');

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";

      $grading_period_list = GradingPeriod::orderBy('id')->get();

      $column_no = 5;
      foreach ($grading_period_list as $grading_period) {
        # code...
        $arr[0][$column_no] = substr($grading_period->grading_period_name, 0,1);
        // $column_no = $column_no + 1;
        $column_no++;
      }

        $arr[0][$column_no] = "Final";

      // $enrollment_class_list = EnrollmentClass::where('class_id','=',$class_id)->join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
      //         ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
      //         ->join('student','student_curriculum.student_id','=','student.id')
      //         ->join('person','student.person_id','=','person.id')
      //         ->select('student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
      //         ->orderBy('person.last_name', 'ASC')
      //         ->orderBy('person.first_name', 'ASC')
      //         ->orderBy('person.middle_name', 'ASC')->get();
        $section = TEClass::find($class_id);

        $enrollment_class_list = EnrollmentSection::join('student', 'enrollment_section.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->where('enrollment_section.section_id','=',$section -> section_id)
                ->select(array('student.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id')->get();    

      $row_no = 1;
      $count = 1;
      foreach ($enrollment_class_list as $enrollment_class) {
        # code...
        $arr[$row_no][0] = $count;
        $arr[$row_no][1] = $enrollment_class->student_no;
        $arr[$row_no][2] = $enrollment_class->last_name;
        $arr[$row_no][3] = $enrollment_class->first_name;
        $arr[$row_no][4] = substr($enrollment_class->middle_name, 0,1);

          $total_final_grade = 0;
          $total_count = 0;
          $col_no = 5;
          foreach ($grading_period_list as $grading_period) {

            $student_academic_projection_list = StudentAcademicProjection::where('student_academic_projection.grading_period_id','=', $grading_period->id)
                        ->where('student_academic_projection.student_id','=', $enrollment_class->id)
                        ->where('student_academic_projection.term_id','=', $term_id)
                        ->where('student_academic_projection.subject_id','=', $subject_id)
                        ->select('student_academic_projection.id','student_academic_projection.grade')->get();
            # code...
            $total_grade = 0;
            $grading_count = 0;
            foreach ($student_academic_projection_list as $student_academic_projection) {

                if($student_academic_projection->grade != 0){
                    $total_grade = $total_grade + $student_academic_projection->grade;
                    $grading_count++;
                }
                
            }

            if($grading_count != 0){
                $grading_grade = $total_grade / $grading_count;
            }
            else{
                $grading_grade = 0;
            }


                $student_grade = Grade::where('grade.grading_period_id','=', $grading_period->id)
                        ->where('grade.student_id','=', $enrollment_class->id)
                        ->where('grade.term_id','=', $term_id)
                        ->where('grade.class_id','=', $class_id)->get()->last();

                  if($student_grade == null){
                    $grade = new Grade();
                    $grade->student_id = $enrollment_class->id;
                    $grade->grading_period_id = $grading_period->id;
                    $grade->term_id = $term_id;
                    $grade->class_id = $class_id;
                    $grade->computed_grade = $grading_grade;
                    $grade->save();
                  }else{
                    $grade = Grade::find($student_grade->id);
                    $grade->computed_grade = $grading_grade;
                    $grade->save();
                  }

            if($grading_grade != 0){
                $arr[$row_no][$col_no] = number_format($grading_grade,0);
                $total_final_grade = $total_final_grade + $grading_grade;
                $total_count++;
            }
            $col_no++;
          }

          $final_grade = 0;
          if($total_final_grade != 0 && $total_count != 0){
            $final_grade = $total_final_grade / $total_count;
          }

          if($final_grade != 0){
              $arr[$row_no][$column_no] = number_format($final_grade,0);
          }

          $arr[$row_no][$column_no+1] = "";
          $arr[$row_no][$column_no+2] = "";

        $row_no++;
        $count++;
      }

      $data = array($arr);
      return response()->json($data);
    }
    
    public function dataJsonGradeComputation(){

      $class_id = \Input::get('class_id');
      $term_id = \Input::get('term_id');
      $classification_id = \Input::get('classification_id');

      $arr[0][0] = "";
      $arr[0][1] = "Student ID";
      $arr[0][2] = "Last Name";
      $arr[0][3] = "First Name";
      $arr[0][4] = "MI";

      $grading_period_list = GradingPeriod::get();

      $column_no = 5;
      foreach ($grading_period_list as $grading_period) {
        # code...
        $arr[0][$column_no] = substr($grading_period->grading_period_name, 0,1);
        // $column_no = $column_no + 1;
        $column_no++;
      }

        $arr[0][$column_no] = "Final";


    //   Start Iterate Students by ClassId
      // $enrollment_class_list = EnrollmentClass::where('class_id','=',$class_id)->join('enrollment','enrollment_class.enrollment_id','=','enrollment.id')
      //         ->join('student_curriculum','enrollment.student_curriculum_id','=','student_curriculum.id')
      //         ->join('student','student_curriculum.student_id','=','student.id')
      //         ->join('person','student.person_id','=','person.id')
      //         ->select('student.id','student.student_no', 'person.last_name', 'person.first_name', 'person.middle_name')
      //         ->orderBy('person.last_name', 'ASC')
      //         ->orderBy('person.first_name', 'ASC')
      //         ->orderBy('person.middle_name', 'ASC')->get();\

        $section = TEClass::find($class_id);

        $enrollment_class_list = EnrollmentSection::join('student', 'enrollment_section.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->where('enrollment_section.section_id','=',$section -> section_id)
                ->select(array('student.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id')
                ->orderBy('person.last_name')
                ->get();  


      $row_no = 1;
      $count = 1;
      foreach ($enrollment_class_list as $enrollment_class) {
        # code...
        $arr[$row_no][0] = $count;
        $arr[$row_no][1] = $enrollment_class->student_no;
        $arr[$row_no][2] = $enrollment_class->last_name;
        $arr[$row_no][3] = $enrollment_class->first_name;
        $arr[$row_no][4] = substr($enrollment_class->middle_name, 0,1);
    
        $grading_count = 0; // set GradingCount = 0
        $total_grade = 0; // set TotalGrade = 0
        // start Iterate Grading Period
          $col_no = 5;
          foreach ($grading_period_list as $grading_period) {

            $class_standing_component_list = ClassStandingComponent::where('class_standing_component.class_id','=',$class_id)->where('class_standing_component.grading_period_id','=',$grading_period->id)->get();
            $grade = 0; // set Grade = 0
            // Start Iterate Component
                  $total_component = 0;
            foreach ($class_standing_component_list as $class_standing_component) {

                  $class_standing_score_entry_list = ClassStandingScoreEntry::join('class_standing_component_detail','class_standing_score_entry.class_standing_component_detail_id','=','class_standing_component_detail.id')
                  ->where('class_standing_component_detail.class_standing_component_id','=',$class_standing_component->id)->where('class_standing_score_entry.student_id','=',$enrollment_class->id)->get();
                
                 $score_entry = 0;
                  $perfect_score_entry = 0;
                  $score_count = 0;
                  $component_equivalent = 0;

                  // -- let the first component as Quiz
                  foreach ($class_standing_score_entry_list as $class_standing_score_entry) {

                    $score_entry = $score_entry + $class_standing_score_entry->score;
                    // $score_count++;
                  
                  }

                  foreach ($class_standing_score_entry_list as $class_standing_score_entry) {

                    $perfect_score_entry = $perfect_score_entry + $class_standing_score_entry->perfect_score;
                  
                  }

                  if($score_entry != 0){
                    // $component_equivalent = $score_entry / $score_count * $class_standing_component->component_weight / 100;
                    $component_equivalent = ($score_entry / $perfect_score_entry) * $class_standing_component->component_weight;
                    // set ComponentEquivalent = ( the sum of all quizes(Quiz1 , Quiz2, QUIZ3)   / the count of quizzes  )  * ComponentPercentage  / 100
                    $total_component = $total_component + $class_standing_component->component_weight;
                  }
                  $grade = $grade + $component_equivalent; // set Grade = Grade + ComponentEquivalent

            }  // end Iterate Component

            $student_grade = Grade::where('grade.grading_period_id','=', $grading_period->id)
                        ->where('grade.student_id','=', $enrollment_class->id)
                        ->where('grade.term_id','=', $term_id)
                        ->where('grade.class_id','=', $class_id)->get()->last();

                  if($student_grade == null){
                    $student_grade = new Grade();
                    $student_grade->student_id = $enrollment_class->id;
                    $student_grade->grading_period_id = $grading_period->id;
                    $student_grade->term_id = $term_id;
                    $student_grade->class_id = $class_id;
                    $student_grade->computed_grade = $grade;
                    $student_grade->save();
                  }else{
                    $student_grade = Grade::find($student_grade->id);
                    $student_grade->computed_grade = $grade;
                    $student_grade->save();
                  }

            if($total_component == 100){

              if($grade != 0){
                  $arr[$row_no][$col_no] =  number_format($grade,0);
                  $total_grade = $total_grade + $grade; // set TotalGrade = TotalGrade + Grade
                  // update student grade based on the grading period
                  $grading_count++; // set GradingCount = GradingCount + 1
              }

            }else{

              if($grade != 0){
                  $arr[$row_no][$col_no] =  0;
              }

            }

            $col_no++;

          } // end Iterate Grading Period
            // for final grade
            // take the average of all grading period grade
          // if($total_component == 100){
          if($total_grade != 0 && $grading_count != 0){
            $final_grade = $total_grade / $grading_count; // final grade = TotalGrade / GradingCount 
            $arr[$row_no][$col_no] =  number_format($final_grade,0);
          }
          // }else{
          //   $arr[$row_no][$col_no] = 0;
          // }

        $row_no++;
         $count++;
    } // end Iterate Student



      $data = array($arr);
      return response()->json($data);
    }
    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function gradeComputationData()
    {      
        $class_id = \Input::get('class_id');
        $section = TEClass::find($class_id);
        // $enrollment_list = Enrollment::join('enrollment_class', 'enrollment_class.enrollment_id', '=', 'enrollment.id')
        //         ->join('class', 'enrollment_class.class_id', '=', 'class.id')
        //         ->join('student_curriculum', 'enrollment.student_curriculum_id', '=', 'student_curriculum.id')
        //         ->join('student', 'student_curriculum.student_id', '=', 'student.id')
        //         ->join('person', 'student.person_id', '=', 'person.id')
        //         ->where('class.id','=',$class_id)
        //         ->select(array('enrollment.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
        //         ->groupBy('student.id');

        $enrollment_list = EnrollmentSection::join('student', 'enrollment_section.student_id', '=', 'student.id')
                ->join('person', 'student.person_id', '=', 'person.id')
                ->where('enrollment_section.section_id','=',$section -> section_id)
                ->select(array('enrollment_section.id','student.student_no','person.last_name','person.first_name','person.middle_name'))
                ->groupBy('student.id');  


        return Datatables::of($enrollment_list)
                ->add_column('prelim','')
                ->add_column('midterm','')
                ->add_column('final','')
                ->add_column('is_editable','')
                ->add_column('is_graduating','')
                ->remove_column('id')
                ->make();

    }
}
