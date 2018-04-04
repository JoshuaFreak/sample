<?php namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\BaseController;
use App\Models\GradeScoreEquivalent;
use App\Models\Transmutation;
use Datatables;
use Config;    
use Hash;

class GradeScoreEquivalentController extends BaseController {
  
  public function gradeScoreEquivalent(){

      $score_equivalent  =  GradeScoreEquivalent::getScoreEquivalent(75, 38, 50, 100, 75, 50);

      return view('grade_score_equivalent/index', compact('score_equivalent'));

      // $transmutation  =  Transmutation::where('transmutation.perfect_score', 10)
      // 				->where('transmutation.score', 10)
      // 				->select('transmutation.equivalent')->get()->last();

      // return view('grade_score_equivalent/index', compact('transmutation'));
  }

}
