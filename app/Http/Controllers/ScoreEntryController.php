<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Course;
use App\Models\Examination;
use App\Models\Student;
use App\Models\StudentExamination;
use App\Models\StudentExaminationCourse;
use App\Models\Person;
use App\Http\Requests\ScoreEntryRequest;
use Illuminate\Support\Facades\Auth;

use Config;    
use Datatables;
use Hash;
use Excel;

class ScoreEntryController extends BaseController {
   
    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
       
        $examination_list = Examination::all();
        return view('score_entry.index',compact('examination_list'));
    }


    public function dataJson(){
      $condition = \Input::all();
      $query = Student::join('person','student.person_id','=','person.id')
                        ->join('student_examination','student.id','=','student_examination.student_id')
                        ->where('student.is_active', '=', 1)
                        ->select('student.id','person.first_name','person.middle_name','person.last_name','person.student_english_name','student_examination.examination_id');

     // print $condition["query"];
      //$query->where('last_name', 'LIKE', "%get%");


      foreach($condition as $column => $value)
      {
        if($column == 'query')
        {
          $query->where('first_name', 'LIKE', "%$value%")
                ->orWhere('last_name', 'LIKE', "%$value%")
                ->orWhere('middle_name', 'LIKE', "%$value%")
                ->orWhere('student_english_name', 'LIKE', "%$value%");
        }
        else
        {
          $query->where($column, '=', $value);
        }
      }

      $student = $query->orderBy('last_name','ASC')->get();

      return response()->json($student);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate() {

        $examination_list = Examination::all();
        return view('score_entry.index',compact('examination_list'));
    }

    public function studentNoScore(){
      $condition = \Input::all();
      $query = StudentExamination::join('student','student_examination.student_id','=','student.id')
                                ->join('examination','student_examination.examination_id','=','examination.id')
                                ->join('examination_course','examination.id','=','examination_course.examination_id')
                                ->join('course','examination_course.course_id','=','course.id');



      foreach($condition as $column => $value)
      {
         $query->where($column, '=', $value);
      }

      // $query->where('student_examination.is_active', '=', 1);
      $student_examination = $query->select(['student_examination.id as student_examination_id','course.course_name','course.id as course_id','examination_course.id as examination_course_id','examination_course.perfect_score','examination.id as examination_id'])
                                 ->get();

      // $student_examination = $query ->

      return response()->json($student_examination);
   }
   public function studentScore(){
      $condition = \Input::all();
      $query = StudentExaminationCourse::leftJoin('student','student_examination_course.student_id','=','student.id')
                                ->leftJoin('examination_course','student_examination_course.examination_course_id','=','examination_course.id')
                                ->leftJoin('examination','examination_course.examination_id','=','examination.id')
                                ->leftJoin('course','examination_course.course_id','=','course.id');



      foreach($condition as $column => $value)
      {
         $query->where($column, '=', $value);
      }

      // $query->where('student_examination.is_active', '=', 1);
      $student_examination = $query->select(['student_examination_course.id as student_examination_course_id','course.course_name','course.id as course_id','examination_course.id as examination_course_id','examination_course.perfect_score','examination.id as examination_id','student_examination_course.score','student_examination_course.rating'])
                                 ->get();

      // $student_examination = $query ->

      return response()->json($student_examination);
   }



    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function createScore() {
        $score_entry = \Input::get('score_entry');
        $rating = \Input::get('rating');
        $examination_course_id = \Input::get('examination_course_id');
        $student_id = \Input::get('student_id');
      

        $score_exist = StudentExaminationCourse::where('student_examination_course.student_id',$student_id)
                                                ->where('student_examination_course.examination_course_id',$examination_course_id)
                                                ->count();      
        // echo $score_exist;
        // exit();

        if($score_exist == 0){
        $student_examination_course = new StudentExaminationCourse();
        $student_examination_course -> student_id = $student_id;
        $student_examination_course -> score = $score_entry;
        $student_examination_course -> rating = $rating;
        $student_examination_course -> examination_course_id = $examination_course_id;
        $student_examination_course -> save();
        } 
        else{
        $student_examination_course_id = StudentExaminationCourse::where('student_examination_course.student_id',$student_id)
                                            ->where('student_examination_course.examination_course_id',$examination_course_id)
                                            ->select(['student_examination_course.id'])->get()->last();

        $student_examination_course = StudentExaminationCourse::find($student_examination_course_id->id);
        $student_examination_course -> student_id = $student_id;
        $student_examination_course -> score = $score_entry;
        $student_examination_course -> rating = $rating;
        $student_examination_course -> examination_course_id = $examination_course_id;
        $student_examination_course -> save();
        }
        // $success = \Lang::get('school.create_success').' : '.$school->school_name ; 
        return response()->json($student_examination_course);
    }
    
    public function scoreResultExcel(){
        $student_id = \Input::get('student_id');
        $examination_id = \Input::get('examination_id');

        $student_name = Student::join('person','student.person_id','=','person.id')
                        ->join('student_examination','student.id','=','student_examination.student_id')
                        ->where('student.id',$student_id)
                        ->select('student.id','person.first_name','person.middle_name','person.last_name','person.student_english_name','student_examination.examination_id')->get()->last();
        $nationality_name = Person::join('nationality','person.nationality_id','=','nationality.id')
                                    ->join('student','person.id','=','student.person_id')
                                    ->where('student.id',$student_id)
                                    ->select('nationality.nationality_name')->get()->last();

        $student_examination_score = StudentExaminationCourse::leftJoin('student','student_examination_course.student_id','=','student.id')
                                ->leftJoin('examination_course','student_examination_course.examination_course_id','=','examination_course.id')
                                ->leftJoin('examination','examination_course.examination_id','=','examination.id')
                                ->leftJoin('course','examination_course.course_id','=','course.id')
                                ->where('student_examination_course.student_id',$student_id)
                                ->where('examination.id',$examination_id)
                                ->select(['course.course_name','examination_course.perfect_score','student_examination_course.score','student_examination_course.rating'])
                                             ->get();
        $level_acquired = StudentExaminationCourse::leftJoin('student','student_examination_course.student_id','=','student.id')
                                ->leftJoin('examination_course','student_examination_course.examination_course_id','=','examination_course.id')
                                ->leftJoin('examination','examination_course.examination_id','=','examination.id')
                                ->leftJoin('course','examination_course.course_id','=','course.id')
                                ->where('student_examination_course.student_id',$student_id)
                                ->where('examination.id',$examination_id)
                                ->sum('student_examination_course.rating');
        $level_acquired = $level_acquired /5;
            // echo $level_acquired;
            // exit();
            Excel::selectSheets('Sheet1')->load('storage/temp_report/monthly_result_card_template.xlsx', function($excelSheetReader) use($student_examination_score, $student_name, $nationality_name,$level_acquired)
            {

                
                   $excelSheetReader->sheet('Sheet1', function($sheet) use($student_examination_score, $student_name, $nationality_name,$level_acquired){
                    $row =7;

                       // $sheet->fromArray($student_examination_score, null, 'A7', true);
                       $sheet->setAllBorders('thin');
                       $sheet->setSize('A7', 20, 30);
                       $sheet->setFontFamily('Roboto Medium');
                      
                          // $cells->setFontFamily('Roboto Medium');

                        $sheet->cell('C4', function($cell)use($student_name){

                            $cell->setValue($student_name->first_name.' '.$student_name->middle_name.' '.$student_name->last_name);

                        });
                        $sheet->cell('H4', function($cell)use($student_name){

                            $cell->setValue($student_name->student_english_name);

                        });
                        $sheet->cell('C5', function($cell)use($nationality_name){

                            $cell->setValue($nationality_name->nationality_name);

                        });
                        $sheet->cell('H5', function($cell)use($level_acquired){
                            if( (91 <= $level_acquired) && ($level_acquired <= 100)){
                               $cell->setValue('L10');
                               $cell->setFontColor('#B92722');
                            }
                            elseif((81 <= $level_acquired) && ($level_acquired <= 90)){
                                $cell->setValue('L9');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((71 <= $level_acquired) && ($level_acquired <= 80)){
                                $cell->setValue('L8');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((61 <= $level_acquired) && ($level_acquired <= 70)){
                                $cell->setValue('L7');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((51 <= $level_acquired) && ($level_acquired <= 60)){
                                $cell->setValue('L6');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((41 <= $level_acquired) && ($level_acquired <= 50)){
                                $cell->setValue('L5');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((31 <= $level_acquired) && ($level_acquired <= 40)){
                                $cell->setValue('L4');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((21 <= $level_acquired) && ($level_acquired <= 30)){
                                $cell->setValue('L3');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((11 <= $level_acquired) && ($level_acquired <= 20)){
                                $cell->setValue('L2');
                                $cell->setFontColor('#B92722');
                            }
                            elseif((0 <= $level_acquired) && ($level_acquired <= 10)){
                                $cell->setValue('L1');
                                $cell->setFontColor('#B92722');
                            }
                        });

                        
                            


                     

                    $row++;
                    foreach($student_examination_score as $data)
                    {
                         $sheet->row($row++, array(
                           $data->course_name,
                           $data->perfect_score,
                           '',
                           $data->score,
                           '',
                           // $data->Rating
                         ));


                    }

          //                     $sheet->fromArray(
          // array(
          // array('', 2010, 2011, 2012),
          // array('Q1', 12, 15, 21),
          // array('Q2', 56, 73, 86),
          // array('Q3', 52, 61, 69),
          // array('Q4', 30, 32, 0),
          // )
          // );
                $dataSeriesLabels = array(
                new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$8', NULL, 1), // 2010
                new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$9', NULL, 1), // 2011
                new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$10', NULL, 1), // 2012
                new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$11', NULL, 1), // 2012
                new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$12', NULL, 1), // 2012
                );
                $xAxisTickValues = array(
                new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$8:$A$12', NULL, 1), // Q1 to Q4
                );

                $dataSeriesValues = array(
                  new \PHPExcel_Chart_DataSeriesValues('Number', 'Sheet1!$F$8', NULL, 1),
                  new \PHPExcel_Chart_DataSeriesValues('Number', 'Sheet1!$F$9', NULL, 1),
                  new \PHPExcel_Chart_DataSeriesValues('Number', 'Sheet1!$F$10', NULL, 1),
                  new \PHPExcel_Chart_DataSeriesValues('Number', 'Sheet1!$F$11', NULL, 1),
                  new \PHPExcel_Chart_DataSeriesValues('Number', 'Sheet1!$F$12', NULL, 1),
                );

                $series = new \PHPExcel_Chart_DataSeries(
                  \PHPExcel_Chart_DataSeries::TYPE_BARCHART,       // plotType
                  \PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,  // plotGrouping
                  range(0, count($dataSeriesValues)-1),           // plotOrder
                  $dataSeriesLabels,                              // plotLabel
                  $xAxisTickValues,                               // plotCategory
                  $dataSeriesValues                               // plotValues
                );
                $series->setPlotDirection(\PHPExcel_Chart_DataSeries::DIRECTION_COL);

                $plotArea = new \PHPExcel_Chart_PlotArea(NULL, array($series));
                $legend = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
                $title = new \PHPExcel_Chart_Title('Score Graph');
                $yAxisLabel = new \PHPExcel_Chart_Title('');
                //    Create the chart
                $chart = new \PHPExcel_Chart(
                  'chart1',       // name
                  $title,         // title
                  $legend,        // legend
                  $plotArea,      // plotArea
                  true,           // plotVisibleOnly
                  0,              // displayBlanksAs
                  NULL,           // xAxisLabel
                  $yAxisLabel     // yAxisLabel
                );

                //    Set the position where the chart should appear in the Sheet1
                $chart->setTopLeftPosition('A15');
                $chart->setBottomRightPosition('D29');



// RADAR CHART


          // $dataSeriesLabels = array(
          //   new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$8', NULL, 1), //  2011
          //   new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$9', NULL, 1), //  2012
          // );
          // //  Set the X-Axis Labels
          // //    Datatype
          // //    Cell reference for data
          // //    Format Code
          // //    Number of datapoints in series
          // //    Data values
          // //    Data Marker
          // $xAxisTickValues = array(
          //   new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$8:$A$12', NULL, 12),  //  Jan to Dec
          //   new \PHPExcel_Chart_DataSeriesValues('String', 'Sheet1!$A$8:$A$12', NULL, 12),  //  Jan to Dec
          // );
          // //  Set the Data values for each data series we want to plot
          // //    Datatype
          // //    Cell reference for data
          // //    Format Code
          // //    Number of datapoints in series
          // //    Data values
          // //    Data Marker
          // $dataSeriesValues = array(
          //   new \PHPExcel_Chart_DataSeriesValues('Number', 'Sheet1!$F$8:$F$12', NULL, 12),
          //   new \PHPExcel_Chart_DataSeriesValues('Number', 'Sheet1!$F$8:$F$12', NULL, 12),
          // );

          // //  Build the dataseries
          // $series = new \PHPExcel_Chart_DataSeries(\
          //   PHPExcel_Chart_DataSeries::TYPE_RADARCHART,       // plotType
          //   NULL,                         // plotGrouping (Radar charts don't have any grouping)
          //   range(0, count($dataSeriesValues)-1),         // plotOrder
          //   $dataSeriesLabels,                    // plotLabel
          //   $xAxisTickValues,                   // plotCategory
          //   $dataSeriesValues,                    // plotValues
          //   NULL,                         // smooth line
          //   \PHPExcel_Chart_DataSeries::STYLE_MARKER         // plotStyle
          // );

          // //  Set up a layout object for the Pie chart
          // $layout = new \PHPExcel_Chart_Layout();

          // //  Set the series in the plot area
          // $plotArea = new \PHPExcel_Chart_PlotArea($layout, array($series));
          // //  Set the chart legend
          // $legend = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

          // $title = new \PHPExcel_Chart_Title('Test Radar Chart');


          // //  Create the chart
          // $chart = new \PHPExcel_Chart(
          //   'chart1',   // name
          //   $title,     // title
          //   $legend,    // legend
          //   $plotArea,    // plotArea
          //   true,     // plotVisibleOnly
          //   0,        // displayBlanksAs
          //   NULL,     // xAxisLabel
          //   NULL      // yAxisLabel   - Radar charts don't have a Y-Axis
          // );

          // //  Set the position where the chart should appear in the Sheet1
          // $chart->setTopLeftPosition('D13');
          // $chart->setBottomRightPosition('H29');


                //    Add the chart to the Sheet1
                $sheet->addChart($chart);



                              
            });


    })->setFilename('monthly_result_card')->export('xlsx');
}

}
