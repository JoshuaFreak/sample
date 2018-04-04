<?php namespace App\Models;

class GradeScoreEquivalent {

	public static function getScoreEquivalent ( $passing_rate, $score, $perfect_score , $perfect_eq, $passing_eq, $failed_eq) {
		$passing_score = 0;


		//$perfect_eq = 1.0;
		//$passing_eq = 3.0;
		//$failed_eq = 5.0;
		


		$e2 = 0;
		$e4 = 0;
		$c7 = 0;
		$g7 = 0;
		$e7 = 0;
		$i7 = 0;
		$equivalent = 0;
		$hundred_divisor = 100;


		$passing_score = ($perfect_score * ($passing_rate / $hundred_divisor));
		$e2 = $perfect_eq - $passing_eq;
		$e4 = $passing_eq - $failed_eq;
		$c7 = $e2 / (($perfect_score - ($perfect_score * ($passing_rate / $hundred_divisor))));
		$g7 = $e4 / ($passing_score);
		$e7 = ($e2 / ($perfect_score - ($passing_score)) * ($perfect_score * -1)) + $perfect_eq;
		$i7 = ($g7 * - ($passing_score)) + $passing_eq;



		//If the score is greater than the passing rate but less than the perfect score
		if (($score >= $passing_score) && ($score <= $perfect_score)){
			
			$equivalent = (($score * $c7) + $e7);
		}
		//If the score is less than the passing score but greater than 0
		elseif (($score < $passing_score) && ($score >= 0)) {
			
			$equivalent = ($g7 * $score) + $i7;
		}

		elseif (($score >= 0) && ($score > $passing_score)) {
			
			$equivalent = ($perfect_score * $c7) + $e7;
		}
		else{
			
			$equivalent = $failed_eq;
		}

		return number_format($equivalent,5, '.', '');


	}
		
}