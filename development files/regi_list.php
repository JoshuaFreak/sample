
<?
 	include_once("../include/lib.php");
	require_once("admin_chk.php");

	//현재날짜 구하기 시작
	$now_start_year = date(Y);
	$now_start_month = date(m);
	$now_start_day = date(d);

//	echo	$now_start_year;
//	echo	$now_start_month;
//	echo	$now_start_day;

	$now_today = $now_start_year."".$now_start_month."".$now_start_day;
	//현재날짜 구하기 끝

	/*왼쪽에 통계시작 부분*/
	$rs_curr = new $rs_class($dbcon);
	$rs_curr->clear();
	$rs_curr->set_table($_table['regi']);

	$now_start_curr_yearT = date(Y);
	$now_start_curr_monthT = date(m);
	$now_start_curr_dayT = date(d);

	$search_student_curr_dateT = $now_start_curr_yearT."".$now_start_curr_monthT."".$now_start_curr_dayT;

	$rs_curr->add_where("replace(substring(abrod_date, 1,10),'-','') <= $search_student_curr_dateT And replace(substring(end_date, 1,10),'-','') >= $search_student_curr_dateT");
	$rs_curr->add_where("state != 1 And state != 3 And state != 5 And state != 6 And state != 7 And state != 8");

	while($curr_tot=$rs_curr->fetch()) {

		//---------코스통계
		if($curr_tot['class_gigan1_1'] != 0){

//			테스트 학생들 보기
//			echo $curr_tot['sname'];

			$class_end_date1_1 = date("Y", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1_1']*7-1),$curr_tot['abrod_date1']));
			$class_end_date1_2 = date("m", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1_1']*7-1),$curr_tot['abrod_date1']));
			$class_end_date1_3 = date("d", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1_1']*7-1),$curr_tot['abrod_date1']));

			if($curr_tot['abrod_date2'] < 10){
				$re_abrod_date1_2 = "0".$curr_tot['abrod_date2'];
			}else{
				$re_abrod_date1_2 = $curr_tot['abrod_date2'];
			}

			if($curr_tot['abrod_date3'] < 10){
				$re_abrod_date1_3 = "0".$curr_tot['abrod_date3'];
			}else{
				$re_abrod_date1_3 = $curr_tot['abrod_date3'];
			}

			$str_class_start_date1 = $curr_tot['abrod_date1']."".$re_abrod_date1_2."".$re_abrod_date1_3;
//	echo " <= ";
//	echo	$now_today;
//	echo " And ";
			$str_class_end_date1 = $class_end_date1_1."".$class_end_date1_2."".$class_end_date1_3;
//	echo " >= ";
//	echo	$now_today;
//	echo " <br> ";

//	20140112 <= 20140625 And 20140628 >= 20140625

			if($str_class_start_date1 <= $now_today And $str_class_end_date1 >= $now_today){
				if($curr_tot['class_type1_1']==21){ //RE
					$regular_esl_sum1++;
				}elseif($curr_tot['class_type1_1']==22){ //IE
					$intensive_esl_sum1++;
				}elseif($curr_tot['class_type1_1']==23){ //TB
					$toeic_bridge_sum1++;
				}elseif($curr_tot['class_type1_1']==25){ //IB
					$ielts_bridge_sum1++;
				}elseif($curr_tot['class_type1_1']==27){ //CeT
					$certificated_tesol_sum1++;
				}elseif($curr_tot['class_type1_1']==28){ //TC
					$benedicto_college_sum1++;
				}elseif($curr_tot['class_type1_1']==29){ //BC
					$budget_cours_sum1++;
				}elseif($curr_tot['class_type1_1']==30){ //WH
					$working_holiday_sum1++;
				}elseif($curr_tot['class_type1_1']==31){ //VA
					$volunteer_program_sum1++;
				}elseif($curr_tot['class_type1_1']==32){ //T600
					$guarantee_toeic600_sum1++;
				}elseif($curr_tot['class_type1_1']==33){ //T700
					$guarantee_toeic700_sum1++;
				}elseif($curr_tot['class_type1_1']==34){ //T850
					$guarantee_toeic850_sum1++;
				}elseif($curr_tot['class_type1_1']==35){ //I5.5
					$guarantee_ielts55_sum1++;
				}elseif($curr_tot['class_type1_1']==36){ //I6.0
					$guarantee_ielts60_sum1++;
				}elseif($curr_tot['class_type1_1']==37){ //I6.5
					$guarantee_ielts65_sum1++;
				}elseif($curr_tot['class_type1_1']==38){ //PI
					$power_intensive_esl_sum1++;
				}elseif($curr_tot['class_type1_1']==39){ //SE
					$sparta_esl_sum1++;
				}elseif($curr_tot['class_type1_1']==40){ //BC
					$business_program_sum1++;
				}elseif($curr_tot['class_type1_1']==41){ //RT
					$regular_toeic_sum1++;
				}elseif($curr_tot['class_type1_1']==42){ //RI
					$regular_ielts_sum1++;
				}
			}

		}else{

//			테스트 학생들 보기
//			echo $curr_tot['sname'];

			$class_end_date1_1 = date("Y", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1']*7-1),$curr_tot['abrod_date1']));
			$class_end_date1_2 = date("m", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1']*7-1),$curr_tot['abrod_date1']));
			$class_end_date1_3 = date("d", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1']*7-1),$curr_tot['abrod_date1']));

			if($curr_tot['abrod_date2'] < 10){
				$re_abrod_date1_2 = "0".$curr_tot['abrod_date2'];
			}else{
				$re_abrod_date1_2 = $curr_tot['abrod_date2'];
			}

			if($curr_tot['abrod_date3'] < 10){
				$re_abrod_date1_3 = "0".$curr_tot['abrod_date3'];
			}else{
				$re_abrod_date1_3 = $curr_tot['abrod_date3'];
			}

			$str_class_start_date1 = $curr_tot['abrod_date1']."".$re_abrod_date1_2."".$re_abrod_date1_3;
//	echo " <= ";
//	echo	$now_today;
//	echo " And ";
			$str_class_end_date1 = $class_end_date1_1."".$class_end_date1_2."".$class_end_date1_3;
//	echo " >= ";
//	echo	$now_today;
//	echo " <br> ";

//	20140112 <= 20140625 And 20140628 >= 20140625

			if($str_class_start_date1 <= $now_today And $str_class_end_date1 >= $now_today){
				if($curr_tot['class_type1']==21){ //RE
					$regular_esl_sum1++;
				}elseif($curr_tot['class_type1']==22){ //IE
					$intensive_esl_sum1++;
				}elseif($curr_tot['class_type1']==23){ //TB
					$toeic_bridge_sum1++;
				}elseif($curr_tot['class_type1']==25){ //IB
					$ielts_bridge_sum1++;
				}elseif($curr_tot['class_type1']==27){ //CeT
					$certificated_tesol_sum1++;
				}elseif($curr_tot['class_type1']==28){ //TC
					$benedicto_college_sum1++;
				}elseif($curr_tot['class_type1']==29){ //BC
					$budget_cours_sum1++;
				}elseif($curr_tot['class_type1']==30){ //WH
					$working_holiday_sum1++;
				}elseif($curr_tot['class_type1']==31){ //VA
					$volunteer_program_sum1++;
				}elseif($curr_tot['class_type1']==32){ //T600
					$guarantee_toeic600_sum1++;
				}elseif($curr_tot['class_type1']==33){ //T700
					$guarantee_toeic700_sum1++;
				}elseif($curr_tot['class_type1']==34){ //T850
					$guarantee_toeic850_sum1++;
				}elseif($curr_tot['class_type1']==35){ //I5.5
					$guarantee_ielts55_sum1++;
				}elseif($curr_tot['class_type1']==36){ //I6.0
					$guarantee_ielts60_sum1++;
				}elseif($curr_tot['class_type1']==37){ //I6.5
					$guarantee_ielts65_sum1++;
				}elseif($curr_tot['class_type1']==38){ //PI
					$power_intensive_esl_sum1++;
				}elseif($curr_tot['class_type1']==39){ //SE
					$sparta_esl_sum1++;
				}elseif($curr_tot['class_type1']==40){ //BC
					$business_program_sum1++;
				}elseif($curr_tot['class_type1']==41){ //RT
					$regular_toeic_sum1++;
				}elseif($curr_tot['class_type1']==42){ //RI
					$regular_ielts_sum1++;
				}
			}

		}


		if($curr_tot['class_gigan2_1'] != 0){
			$class_start_date2_1 = date("Y", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1_1']*7),$curr_tot['abrod_date1']));
			$class_start_date2_2 = date("m", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1_1']*7),$curr_tot['abrod_date1']));
			$class_start_date2_3 = date("d", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1_1']*7),$curr_tot['abrod_date1']));

			$class_end_date2_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2_1']*7-1),$class_start_date2_1));
			$class_end_date2_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2_1']*7-1),$class_start_date2_1));
			$class_end_date2_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2_1']*7-1),$class_start_date2_1));

			$str_class_start_date2 = $class_start_date2_1."".$class_start_date2_2."".$class_start_date2_3;
//	echo "~";
			$str_class_end_date2 = $class_end_date2_1."".$class_end_date2_2."".$class_end_date2_3;
//	echo " <br> ";

			if($str_class_start_date2 <= $now_today And $str_class_end_date2 >= $now_today){
				if($curr_tot['class_gigan2_1']==21){ //RE
					$regular_esl_sum2++;
				}elseif($curr_tot['class_gigan2_1']==22){ //IE
					$intensive_esl_sum2++;
				}elseif($curr_tot['class_gigan2_1']==23){ //TB
					$toeic_bridge_sum2++;
				}elseif($curr_tot['class_gigan2_1']==25){ //IB
					$ielts_bridge_sum2++;
				}elseif($curr_tot['class_gigan2_1']==27){ //CeT
					$certificated_tesol_sum2++;
				}elseif($curr_tot['class_gigan2_1']==28){ //TC
					$benedicto_college_sum2++;
				}elseif($curr_tot['class_gigan2_1']==29){ //BC
					$budget_cours_sum2++;
				}elseif($curr_tot['class_gigan2_1']==30){ //WH
					$working_holiday_sum2++;
				}elseif($curr_tot['class_gigan2_1']==31){ //VA
					$volunteer_program_sum2++;
				}elseif($curr_tot['class_gigan2_1']==32){ //T600
					$guarantee_toeic600_sum2++;
				}elseif($curr_tot['class_gigan2_1']==33){ //T700
					$guarantee_toeic700_sum2++;
				}elseif($curr_tot['class_gigan2_1']==34){ //T850
					$guarantee_toeic850_sum2++;
				}elseif($curr_tot['class_gigan2_1']==35){ //I5.5
					$guarantee_ielts55_sum2++;
				}elseif($curr_tot['class_gigan2_1']==36){ //I6.0
					$guarantee_ielts60_sum2++;
				}elseif($curr_tot['class_gigan2_1']==37){ //I6.5
					$guarantee_ielts65_sum2++;
				}elseif($curr_tot['class_gigan2_1']==38){ //PI
					$power_intensive_esl_sum2++;
				}elseif($curr_tot['class_gigan2_1']==39){ //SE
					$sparta_esl_sum2++;
				}elseif($curr_tot['class_gigan2_1']==40){ //BC
					$business_program_sum2++;
				}elseif($curr_tot['class_gigan2_1']==41){ //RT
					$regular_toeic_sum2++;
				}elseif($curr_tot['class_gigan2_1']==42){ //RI
					$regular_ielts_sum2++;
				}
			}
		}else{
			$class_start_date2_1 = date("Y", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1']*7),$curr_tot['abrod_date1']));
			$class_start_date2_2 = date("m", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1']*7),$curr_tot['abrod_date1']));
			$class_start_date2_3 = date("d", mktime(0,0,0,$curr_tot['abrod_date2'],$curr_tot['abrod_date3']+($curr_tot['class_gigan1']*7),$curr_tot['abrod_date1']));

			$class_end_date2_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2']*7-1),$class_start_date2_1));
			$class_end_date2_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2']*7-1),$class_start_date2_1));
			$class_end_date2_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2']*7-1),$class_start_date2_1));

			$str_class_start_date2 = $class_start_date2_1."".$class_start_date2_2."".$class_start_date2_3;
//	echo "~";
			$str_class_end_date2 = $class_end_date2_1."".$class_end_date2_2."".$class_end_date2_3;
//	echo " <br> ";

			if($str_class_start_date2 <= $now_today And $str_class_end_date2 >= $now_today){
				if($curr_tot['class_type2']==21){ //RE
					$regular_esl_sum2++;
				}elseif($curr_tot['class_type2']==22){ //IE
					$intensive_esl_sum2++;
				}elseif($curr_tot['class_type2']==23){ //TB
					$toeic_bridge_sum2++;
				}elseif($curr_tot['class_type2']==25){ //IB
					$ielts_bridge_sum2++;
				}elseif($curr_tot['class_type2']==27){ //CeT
					$certificated_tesol_sum2++;
				}elseif($curr_tot['class_type2']==28){ //TC
					$benedicto_college_sum2++;
				}elseif($curr_tot['class_type2']==29){ //BC
					$budget_cours_sum2++;
				}elseif($curr_tot['class_type2']==30){ //WH
					$working_holiday_sum2++;
				}elseif($curr_tot['class_type2']==31){ //VA
					$volunteer_program_sum2++;
				}elseif($curr_tot['class_type2']==32){ //T600
					$guarantee_toeic600_sum2++;
				}elseif($curr_tot['class_type2']==33){ //T700
					$guarantee_toeic700_sum2++;
				}elseif($curr_tot['class_type2']==34){ //T850
					$guarantee_toeic850_sum2++;
				}elseif($curr_tot['class_type2']==35){ //I5.5
					$guarantee_ielts55_sum2++;
				}elseif($curr_tot['class_type2']==36){ //I6.0
					$guarantee_ielts60_sum2++;
				}elseif($curr_tot['class_type2']==37){ //I6.5
					$guarantee_ielts65_sum2++;
				}elseif($curr_tot['class_type2']==38){ //PI
					$power_intensive_esl_sum2++;
				}elseif($curr_tot['class_type2']==39){ //SE
					$sparta_esl_sum2++;
				}elseif($curr_tot['class_type2']==40){ //BC
					$business_program_sum2++;
				}elseif($curr_tot['class_type2']==41){ //RT
					$regular_toeic_sum2++;
				}elseif($curr_tot['class_type2']==42){ //RI
					$regular_ielts_sum2++;
				}
			}
		}

		if($curr_tot['class_gigan3_1'] != 0){
			$class_start_date3_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2_1']*7),$class_start_date2_1));
			$class_start_date3_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2_1']*7),$class_start_date2_1));
			$class_start_date3_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2_1']*7),$class_start_date2_1));

			$class_end_date3_1 = date("Y", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($curr_tot['class_gigan3_1']*7-1),$class_start_date3_1));
			$class_end_date3_2 = date("m", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($curr_tot['class_gigan3_1']*7-1),$class_start_date3_1));
			$class_end_date3_3 = date("d", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($curr_tot['class_gigan3_1']*7-1),$class_start_date3_1));

			$str_class_start_date3 = $class_start_date3_1."".$class_start_date3_2."".$class_start_date3_3;
//	echo "~";
			$str_class_end_date3 = $class_end_date3_1."".$class_end_date3_2."".$class_end_date3_3;
//	echo "<br>";

			if($str_class_start_date3 <= $now_today And $str_class_end_date3 >= $now_today){
				if($curr_tot['class_gigan3_1']==21){ //RE
					$regular_esl_sum3++;
				}elseif($curr_tot['class_gigan3_1']==22){ //IE
					$intensive_esl_sum3++;
				}elseif($curr_tot['class_gigan3_1']==23){ //TB
					$toeic_bridge_sum3++;
				}elseif($curr_tot['class_gigan3_1']==25){ //IB
					$ielts_bridge_sum3++;
				}elseif($curr_tot['class_gigan3_1']==27){ //CeT
					$certificated_tesol_sum3++;
				}elseif($curr_tot['class_gigan3_1']==28){ //TC
					$benedicto_college_sum3++;
				}elseif($curr_tot['class_gigan3_1']==29){ //BC
					$budget_cours_sum3++;
				}elseif($curr_tot['class_gigan3_1']==30){ //WH
					$working_holiday_sum3++;
				}elseif($curr_tot['class_gigan3_1']==31){ //VA
					$volunteer_program_sum3++;
				}elseif($curr_tot['class_gigan3_1']==32){ //T600
					$guarantee_toeic600_sum3++;
				}elseif($curr_tot['class_gigan3_1']==33){ //T700
					$guarantee_toeic700_sum3++;
				}elseif($curr_tot['class_gigan3_1']==34){ //T850
					$guarantee_toeic850_sum3++;
				}elseif($curr_tot['class_gigan3_1']==35){ //I5.5
					$guarantee_ielts55_sum3++;
				}elseif($curr_tot['class_gigan3_1']==36){ //I6.0
					$guarantee_ielts60_sum3++;
				}elseif($curr_tot['class_gigan3_1']==37){ //I6.5
					$guarantee_ielts65_sum3++;
				}elseif($curr_tot['class_gigan3_1']==38){ //PI
					$power_intensive_esl_sum3++;
				}elseif($curr_tot['class_gigan3_1']==39){ //SE
					$sparta_esl_sum3++;
				}elseif($curr_tot['class_gigan3_1']==40){ //BC
					$business_program_sum3++;
				}elseif($curr_tot['class_gigan3_1']==41){ //RT
					$regular_toeic_sum3++;
				}elseif($curr_tot['class_gigan3_1']==42){ //RI
					$regular_ielts_sum3++;
				}
			}
		}else{
			$class_start_date3_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2']*7),$class_start_date2_1));
			$class_start_date3_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2']*7),$class_start_date2_1));
			$class_start_date3_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($curr_tot['class_gigan2']*7),$class_start_date2_1));

			$class_end_date3_1 = date("Y", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($curr_tot['class_gigan3']*7-1),$class_start_date3_1));
			$class_end_date3_2 = date("m", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($curr_tot['class_gigan3']*7-1),$class_start_date3_1));
			$class_end_date3_3 = date("d", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($curr_tot['class_gigan3']*7-1),$class_start_date3_1));

			$str_class_start_date3 = $class_start_date3_1."".$class_start_date3_2."".$class_start_date3_3;
//	echo "~";
			$str_class_end_date3 = $class_end_date3_1."".$class_end_date3_2."".$class_end_date3_3;
//	echo "<br>";

			if($str_class_start_date3 <= $now_today And $str_class_end_date3 >= $now_today){
				if($curr_tot['class_gigan3']==21){ //RE
					$regular_esl_sum3++;
				}elseif($curr_tot['class_gigan3']==22){ //IE
					$intensive_esl_sum3++;
				}elseif($curr_tot['class_gigan3']==23){ //TB
					$toeic_bridge_sum3++;
				}elseif($curr_tot['class_gigan3']==25){ //IB
					$ielts_bridge_sum3++;
				}elseif($curr_tot['class_gigan3']==27){ //CeT
					$certificated_tesol_sum3++;
				}elseif($curr_tot['class_gigan3']==28){ //TC
					$benedicto_college_sum3++;
				}elseif($curr_tot['class_gigan3']==29){ //BC
					$budget_cours_sum3++;
				}elseif($curr_tot['class_gigan3']==30){ //WH
					$working_holiday_sum3++;
				}elseif($curr_tot['class_gigan3']==31){ //VA
					$volunteer_program_sum3++;
				}elseif($curr_tot['class_gigan3']==32){ //T600
					$guarantee_toeic600_sum3++;
				}elseif($curr_tot['class_gigan3']==33){ //T700
					$guarantee_toeic700_sum3++;
				}elseif($curr_tot['class_gigan3']==34){ //T850
					$guarantee_toeic850_sum3++;
				}elseif($curr_tot['class_gigan3']==35){ //I5.5
					$guarantee_ielts55_sum3++;
				}elseif($curr_tot['class_gigan3']==36){ //I6.0
					$guarantee_ielts60_sum3++;
				}elseif($curr_tot['class_gigan3']==37){ //I6.5
					$guarantee_ielts65_sum3++;
				}elseif($curr_tot['class_gigan3']==38){ //PI
					$power_intensive_esl_sum3++;
				}elseif($curr_tot['class_gigan3']==39){ //SE
					$sparta_esl_sum3++;
				}elseif($curr_tot['class_gigan3']==40){ //BC
					$business_program_sum3++;
				}elseif($curr_tot['class_gigan3']==41){ //RT
					$regular_toeic_sum3++;
				}elseif($curr_tot['class_gigan3']==42){ //RI
					$regular_ielts_sum3++;
				}
			}
		}

		//---------코스통계

		$total_anum++;
		$total_aprice = $total_aprice + $curr_tot[class_gigan] ;

		// 1=>'한국',7=>'일본',2=>'타이완',3=>'중국',8=>'아랍',9=>'태국',10=>'러시아',4=>'기타'
		if($curr_tot[national] == 1){
			$national_korea++;
			$total_national_korea = $total_national_korea + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 2){
			$national_taiwan++;
			$total_national_taiwan = $total_national_taiwan + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 3){
			$national_china++;
			$total_national_china = $total_national_china + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 4){
			$national_etc++;
			$total_national_etc = $total_national_etc + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 5){
			$national_vietnam++;
			$total_national_vietnam = $total_national_vietnam + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 7){
			$national_japan++;
			$total_national_japan = $total_national_japan + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 8){
			$national_arab++;
			$total_national_arab = $total_national_arab + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 9){
			$national_thailand++;
			$total_national_thailand = $total_national_thailand + $curr_tot[class_gigan] ;
		}elseif($curr_tot[national] == 10){
			$national_russia++;
			$total_national_russia = $total_national_russia + $curr_tot[class_gigan] ;
		}

	}
	/*왼쪽에 통계시작 부분*/

	/*학생정보 자동업데이트 부분 시작*/
	$do_update_year = date(Y);
	$do_update_month = date(m);
	$do_update_day = date(d);

	$search_do_update_date = $do_update_year."".$do_update_month."".$do_update_day;

	$rs_do_update = new $rs_class($dbcon);
	$rs_do_update->clear();
	$rs_do_update->set_table($_table['do_update']);
	$rs_do_update->add_where("do_update = $search_do_update_date");

	if($rs_do_update->num_rows()<1) {

		require_once("student_info_auto_update.php");
//		echo "아무것도 없울때 require_once 를 실행한다";

	}
	/*학생정보 자동업데이트 부분 끝*/


	//실질적인 데이터
    //total
	$rs_ch = new $rs_class($dbcon);
	$rs_ch->clear();
	$rs_ch->set_table($_table['regi']);

//	if(!$ss[1]) {
//		$rs_ch->add_where("state < 6");
//	}

	if($course_select) {
		$rs_ch->add_where("(class_type1 = $course_select Or class_type2 = $course_select Or class_type3 = $course_select)");
	}

	if($strconfirm_check) {
		$rs_ch->add_where("confirm_check = $strconfirm_check");
	}

    if($agency_register) { 
		$rs_ch->add_where("national = $agency_register");
	}

    if($stradmission_state) {
		$rs_ch->add_where("admission_state = $stradmission_state");

		if(!$ss[1]) {
			$rs_ch->add_where("state != 6 And state != 7 And state != 8");
		}

	}

    if($search_student_dateT) { 
		$now_start_yearT = date(Y);
		$now_start_monthT = date(m);
		$now_start_dayT = date(d);

		$search_student_dateT = $now_start_yearT."".$now_start_monthT."".$now_start_dayT;
		$rs_ch->add_where("replace(substring(abrod_date, 1,10),'-','') <= $search_student_dateT And replace(substring(end_date, 1,10),'-','') >= $search_student_dateT");

//		$rs_ch->add_where("state < 6");
		$rs_ch->add_where("state != 1 And state != 3 And state != 5 And state != 6 And state != 7 And state != 8");

	}

    if($abrod_date_str1) { 

		$abrod_date_str10=explode("-",$abrod_date_str1);
		$abrod_date_11 = $abrod_date_str10[0];
		$abrod_date_12 = $abrod_date_str10[1];
		$abrod_date_13 = $abrod_date_str10[2];   

//		$start_abrod_date = mktime(0,0,0,$abrod_date_12,$abrod_date_13,$abrod_date_11);
		$start_abrod_date = $abrod_date_11."".$abrod_date_12."".$abrod_date_13;

		$abrod_date_str20=explode("-",$abrod_date_str2);
		$abrod_date_str_21 = $abrod_date_str20[0];
		$abrod_date_str_22 = $abrod_date_str20[1];
		$abrod_date_str_23 = $abrod_date_str20[2];

//		$end_abrod_date = mktime(23,59,59,$abrod_date_str_22,$abrod_date_str_23,$abrod_date_str_21);
		$end_abrod_date = $abrod_date_str_21."".$abrod_date_str_22."".$abrod_date_str_23;

		$rs_ch->add_where("replace(substring(abrod_date, 1,10),'-','') >= $start_abrod_date and replace(substring(abrod_date, 1,10),'-','') <= $end_abrod_date");
//		$rs_ch->add_where("abrod_date_str >= $start_abrod_date and abrod_date_str <= $end_abrod_date");
	}

    if($arrival_date_str1) { 

		$arrival_date_str10=explode("-",$arrival_date_str1);
		$arrival_date_11 = $arrival_date_str10[0];
		$arrival_date_12 = $arrival_date_str10[1];
		$arrival_date_13 = $arrival_date_str10[2];   
		  
//		$start_arrival_date = mktime(0,0,0,$arrival_date_12,$arrival_date_13,$arrival_date_11);
		$start_arrival_date = $arrival_date_11."".$arrival_date_12."".$arrival_date_13;

		$arrival_date_str20=explode("-",$arrival_date_str2);
		$arrival_date_str_21 = $arrival_date_str20[0];
		$arrival_date_str_22 = $arrival_date_str20[1];
		$arrival_date_str_23 = $arrival_date_str20[2];   

//		$end_arrival_date = mktime(23,59,59,$arrival_date_str_22,$arrival_date_str_23,$arrival_date_str_21);
		$end_arrival_date = $arrival_date_str_21."".$arrival_date_str_22."".$arrival_date_str_23;

		$rs_ch->add_where("replace(substring(end_date, 1,10),'-','') >= $start_arrival_date and replace(substring(end_date, 1,10),'-','') <= $end_arrival_date");
	}

    if($regi1) { 

		$regi10=explode("-",$regi1);
		$regi_date11 = $regi10[0];
		$regi_date12 = $regi10[1];
		$regi_date13 = $regi10[2];   

		$start_date = mktime(0,0,0,$regi_date12,$regi_date13,$regi_date11);		

//		echo $start_date;

		$regi20=explode("-",$regi2);
		$regi_date21 = $regi20[0];
		$regi_date22 = $regi20[1];
		$regi_date23 = $regi20[2];   

		$end_date = mktime(23,59,59,$regi_date22,$regi_date23,$regi_date21);

//		echo "&nbsp;";

//		echo date("Y-m-d H:i:s", mktime(23, 59, 59, $regi_date22, $regi_date23, $regi_date21));

//		echo "&nbsp;";
//		echo $end_date;
//		echo $end_date1;

//		echo date("Y-m-d H:i:s", mktime(0, 0, 0, 12, 31, 2011));

		$rs_ch->add_where("regi_date >= $start_date and regi_date <= $end_date");
	}

//	echo "머지<br>";
//	echo $ss;

	if(is_array($ss)) {
		foreach($ss as $__k => $__v) {
			switch ($__k) {
				case '0' : 
					if($kw!='' && $__v!='') {
						$ss_kw=$dbcon->escape_string($kw,DB_LIKE);
						switch ($__v) {
							case '1' : $rs_ch->add_where("sname LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
							case '2' : $rs_ch->add_where("agency_name LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
							case '3' : $rs_ch->add_where("sename LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
							case '4' : $rs_ch->add_where("student_id LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
						}
						unset($ss_kw);
					}
					break;
   				/***********************************************************************/
				// 필터 조건에 의한 필터링
				case '1' : // 캠프차수
					//이 부분이 등록사항 검색부분
					if($__v == 9) {
						$rs_ch->add_where("state != 4 And state != 6 And state != 7 And state != 8"); break;
					}elseif($__v == 10) {
						$rs_ch->add_where("state != 6 And state != 7 And state != 8"); break;
					}elseif($__v == 11) {
						$rs_ch->add_where("state != 1 And state != 6 And state != 7 And state != 8"); break;
					}elseif($__v == 7) {
						$rs_ch->add_where("state = 7"); break;
//					} elseif($__v == '') {
//						$rs_ch->add_where("state < 6"); break;
					} elseif($__v != '') {
						$rs_ch->add_where("state = $__v"); break;
					}
				case '2' : // 코스
					if($__v != '') { $rs_ch->add_where("$__v =  state"); } break;
			}
		}
	}

	$rs_list = new $rs_class($dbcon);
	$rs_list->clear();
	$rs_list->set_table($_table['regi']);

//	if(!$ss[1]) {
//		$rs_list->add_where("state < 6");
//	}

	if($course_select) {
		$rs_list->add_where("(class_type1 = $course_select Or class_type2 = $course_select Or class_type3 = $course_select)");
	}

	if($strconfirm_check) {
		$rs_list->add_where("confirm_check = $strconfirm_check");
	}

	if($agency_register) { 
		$rs_list->add_where("national = $agency_register");
	}

    if($stradmission_state) {
		$rs_list->add_where("admission_state = $stradmission_state");

		if(!$ss[1]) {
			$rs_list->add_where("state != 6 And state != 7 And state != 8");
		}

	}

    if($search_student_dateT) { 
	  $now_start_year = date(Y);
	  $now_start_month = date(m);
	  $now_start_day = date(d);

	  $search_student_dateT = $now_start_year."".$now_start_month."".$now_start_day;
	  $rs_list->add_where("replace(substring(abrod_date, 1,10),'-','') <= $search_student_dateT And replace(substring(end_date, 1,10),'-','') >= $search_student_dateT");

//	  $rs_list->add_where("state < 6");
	  $rs_list->add_where("state != 1 And state != 3 And state != 5 And state != 6 And state != 7 And state != 8");
	}

    if($abrod_date_str1) { 

	  $abrod_date_str10=explode("-",$abrod_date_str1);
      $abrod_date_11 = $abrod_date_str10[0];
      $abrod_date_12 = $abrod_date_str10[1];
      $abrod_date_13 = $abrod_date_str10[2];   

//	  $start_abrod_date = mktime(0,0,0,$abrod_date_12,$abrod_date_13,$abrod_date_11);
	  $start_abrod_date = $abrod_date_11."".$abrod_date_12."".$abrod_date_13;

	  $abrod_date_str20=explode("-",$abrod_date_str2);
      $abrod_date_str_21 = $abrod_date_str20[0];
      $abrod_date_str_22 = $abrod_date_str20[1];
      $abrod_date_str_23 = $abrod_date_str20[2];   

//	  $end_abrod_date = mktime(23,59,59,$abrod_date_str_22,$abrod_date_str_23,$abrod_date_str_21);
	  $end_abrod_date = $abrod_date_str_21."".$abrod_date_str_22."".$abrod_date_str_23;

      $rs_list->add_where("replace(substring(abrod_date, 1,10),'-','') >= $start_abrod_date and replace(substring(abrod_date, 1,10),'-','') <= $end_abrod_date");
//	  $rs_list->add_where("abrod_date_str >= $start_abrod_date and abrod_date_str <= $end_abrod_date");
	}

    if($arrival_date_str1) { 

	  $arrival_date_str10=explode("-",$arrival_date_str1);
      $arrival_date_11 = $arrival_date_str10[0];
      $arrival_date_12 = $arrival_date_str10[1];
      $arrival_date_13 = $arrival_date_str10[2];   
	  
//	  $start_arrival_date = mktime(0,0,0,$arrival_date_12,$arrival_date_13,$arrival_date_11);
	  $start_arrival_date = $arrival_date_11."".$arrival_date_12."".$arrival_date_13;

	  $arrival_date_str20=explode("-",$arrival_date_str2);
      $arrival_date_str_21 = $arrival_date_str20[0];
      $arrival_date_str_22 = $arrival_date_str20[1];
      $arrival_date_str_23 = $arrival_date_str20[2];   

//	  $end_arrival_date = mktime(23,59,59,$arrival_date_str_22,$arrival_date_str_23,$arrival_date_str_21);
	  $end_arrival_date = $arrival_date_str_21."".$arrival_date_str_22."".$arrival_date_str_23;

      $rs_list->add_where("replace(substring(end_date, 1,10),'-','') >= $start_arrival_date and replace(substring(end_date, 1,10),'-','') <= $end_arrival_date");
	}

    if($regi1) { 

	  $regi10=explode("-",$regi1);
      $regi_date11 = $regi10[0];
      $regi_date12 = $regi10[1];
      $regi_date13 = $regi10[2];   

	  $start_date = mktime(0,0,0,$regi_date12,$regi_date13,$regi_date11);		

	  $regi20=explode("-",$regi2);
      $regi_date21 = $regi20[0];
      $regi_date22 = $regi20[1];
      $regi_date23 = $regi20[2];   

	  $end_date = mktime(23,59,59,$regi_date22,$regi_date23,$regi_date21);	

      $rs_list->add_where("regi_date >= $start_date and regi_date <= $end_date");
	}

	if(is_array($ss)) {
		foreach($ss as $__k => $__v) {
			switch ($__k) {
				case '0' : 
					if($kw!='' && $__v!='') {
						$ss_kw=$dbcon->escape_string($kw,DB_LIKE);
						switch ($__v) {
							case '1' : $rs_list->add_where("sname LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
							case '2' : $rs_list->add_where("agency_name LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
							case '3' : $rs_list->add_where("sename LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
							case '4' : $rs_list->add_where("student_id LIKE '%$ss_kw%' escape '".$dbcon->escape_ch."'"); break;
						}
						unset($ss_kw);
					}
					break; 
				/***********************************************************************/
				// 필터 조건에 의한 필터링
				case '1' : // 캠프차수
					//이 부분이 등록사항 검색부분
					if($__v == 9) {
						$rs_list->add_where("state != 4 And state != 6 And state != 7 And state != 8"); break;
					}elseif($__v == 10) {
						$rs_list->add_where("state != 6 And state != 7 And state != 8"); break;
					}elseif($__v == 11) {
						$rs_list->add_where("state != 1 And state != 6 And state != 7 And state != 8"); break;
					}elseif($__v == 7) {
						$rs_list->add_where("state = 7"); break;
//					} elseif($__v == '') {
//						$rs_list->add_where("state < 6"); break;
					} elseif($__v != '') {
						$rs_list->add_where("state = $__v"); break;
					}
				case '2' : // 코스
					if($__v != '') { $rs_list->add_where("$__v =  state"); } break;
			}
		}
	}

	$rs_list->add_order("num DESC");

	if($cty_order == 'desc'){
		$rs_list->add_order("num DESC");
	}elseif($regi_date_order == 'asc'){
		$rs_list->add_order("num ASC");
	}

	switch ($ot) {
		case 10 : $page_info=$rs_list->select_list($page,40,10);	break;
		case 11 : $page_info=$rs_list->select_list($page,60,10);	break;
		case 12 : $page_info=$rs_list->select_list($page,100,10);	break;
		case 13 : $page_info=$rs_list->select_list($page,500,10);	break;
		default : $page_info=$rs_list->select_list($page,20,10);	break;
	}

	$MENU_L='m4';

?>

<? include("_header.php"); ?>
<? include("admin.header_new.php"); ?>

<script>
function member_mail(){
	if(!chk_checkbox(list_form,'chk_nums[]',true)){
		alert('한명이상 선택 하세요.');
		return;
	}
	list_form.mode.value='check';
	list_form.action='member_mail.php';
	list_form.submit();
}
function group_del(){
	if(!chk_checkbox(list_form,'chk_nums[]',true)){
		alert('한명이상 선택 하세요.');
		return;
	}
	list_form.mode.value='delete';
	list_form.action='?<?=$p_str?>';
	list_form.submit();
}
</script>

<script language=JavaScript>
function check_searchform() {
var m=document.search_form;

	if (m.regi2.value == "") {

		if (m.regi1.value != "") {
			alert("등록일 조회 끝 날짜를 입력해주세요.");
			m.regi2.focus();
			return false;
		}

	}

	if (m.abrod_date_str2.value == "") {

		if (m.abrod_date_str1.value != "") {
			alert("출발일 조회 끝 날짜를 입력해주세요.");
			m.abrod_date_str2.focus();
			return false;
		}

	}

	if (m.arrival_date_str2.value == "") {

		if (m.arrival_date_str1.value != "") {
			alert("퇴실일 조회 끝 날짜를 입력해주세요.");
			m.arrival_date_str2.focus();
			return false;
		}
	}

}
</script>



<?
//등록학생 명수 퍼센테이지 구하기
if($total_anum) { 
	$sub_national_korea = round(($national_korea/$total_anum)*100, 1);
	$sub_national_japan = round(($national_japan/$total_anum)*100, 1);
	$sub_national_taiwan = round(($national_taiwan/$total_anum)*100, 1);
	$sub_national_china = round(($national_china/$total_anum)*100, 1);
	$sub_national_vietnam = round(($national_vietnam/$total_anum)*100, 1);
	$sub_national_arab = round(($national_arab/$total_anum)*100, 1);
	$sub_national_thailand = round(($national_thailand/$total_anum)*100, 1);
	$sub_national_russia = round(($national_russia/$total_anum)*100, 1);
	$sub_national_etc = round(($national_etc/$total_anum)*100, 1);
}

//등록학생 주차 퍼센테이지 구하기
/*
	if($total_aprice) { 
		$sub_total_national_korea = round(($total_national_korea/$total_aprice)*100, 1);
		$sub_total_national_japan = round(($total_national_japan/$total_aprice)*100, 1);
		$sub_total_national_taiwan = round(($total_national_taiwan/$total_aprice)*100, 1);
		$sub_total_national_china = round(($total_national_china/$total_aprice)*100, 1);
		$sub_total_national_vietnam = round(($total_national_vietnam/$total_aprice)*100, 1);
		$sub_total_national_etc = round(($total_national_etc/$total_aprice)*100, 1);
	}

if(!$sub_total_national_korea) { $sub_total_national_korea = 0; }
if(!$sub_total_national_japan) { $sub_total_national_japan = 0; }
if(!$sub_total_national_taiwan) { $sub_total_national_taiwan = 0; }
if(!$sub_total_national_china) { $sub_total_national_china = 0; }
if(!$sub_total_national_vietnam) { $sub_total_national_vietnam = 0; }
if(!$sub_total_national_etc) { $sub_total_national_etc = 0; }
*/

if(!$sub_national_korea) { $sub_national_korea = 0; }
if(!$sub_national_japan) { $sub_national_japan = 0; }
if(!$sub_national_taiwan) { $sub_national_taiwan = 0; }
if(!$sub_national_china) { $sub_national_china = 0; }
if(!$sub_national_vietnam) { $sub_national_vietnam = 0; }
if(!$sub_national_arab) { $sub_national_arab = 0; }
if(!$sub_national_thailand) { $sub_national_thailand = 0; }
if(!$sub_national_russia) { $sub_national_russia = 0; }
if(!$sub_national_etc) { $sub_national_etc = 0; }
?>

<table border="0" cellspacing="0" cellpadding="0" width="1645">
	<tr>
		<td>
			- Current Students : TOTAL <a href="./regi_list.php?search_student_dateT=yes"><?=number_format($total_anum)?>P (<?=number_format($total_aprice/$total_anum, 1)?>W)</a>
			<a href="./regi_list.php?search_student_dateT=yes&agency_register=1"><img src="./images/national_korea.gif"> <?=number_format($national_korea)?>P (<?=$sub_national_korea?>%/<?if($total_national_korea != 0){?><?=number_format($total_national_korea/$national_korea, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=7"><img src="./images/national_japan.gif"> <?=number_format($national_japan)?>P (<?=$sub_national_japan?>%/<?if($total_national_japan != 0){?><?=number_format($total_national_japan/$national_japan, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=2"><img src="./images/national_taiwan.gif"> <?=number_format($national_taiwan)?>P (<?=$sub_national_taiwan?>%/<?if($total_national_taiwan != 0){?><?=number_format($total_national_taiwan/$national_taiwan, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=3"><img src="./images/national_china.gif"> <?=number_format($national_china)?>P (<?=$sub_national_china?>%/<?if($total_national_china != 0){?><?=number_format($total_national_china/$national_china, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=5"><img src="./images/national_vietnam.gif"> <?=number_format($national_vietnam)?>P (<?=$sub_national_vietnam?>%/<?if($total_national_vietnam != 0){?><?=number_format($total_national_vietnam/$national_vietnam, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=8"><img src="./images/national_arab.png"> <?=number_format($national_arab)?>P (<?=$sub_national_arab?>%/<?if($total_national_arab != 0){?><?=number_format($total_national_arab/$national_arab, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=9"><img src="./images/national_thailand.gif"> <?=number_format($national_thailand)?>P (<?=$sub_national_thailand?>%/<?if($total_national_thailand != 0){?><?=number_format($total_national_thailand/$national_thailand, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=10"><img src="./images/national_russia.gif"> <?=number_format($national_russia)?>P (<?=$sub_national_russia?>%/<?if($total_national_russia != 0){?><?=number_format($total_national_russia/$national_russia, 1)?>W)<?}else{?>0W)<?}?></a>,

			<a href="./regi_list.php?search_student_dateT=yes&agency_register=4"><img src="./images/national_etc.gif"> <?=number_format($national_etc)?>P (<?=$sub_national_etc?>%/<?if($total_national_etc != 0){?><?=number_format($total_national_etc/$national_etc, 1)?>W)<?}else{?>0W)<?}?></a> : Number of students(Percentage/Average Weeks)
		</td>
	</tr>

	<tr>
		<td>
			- Number of students per Class : 
			RE : <?=number_format($regular_esl_sum1+$regular_esl_sum2+$regular_esl_sum3)?>P,
			IE : <?=number_format($intensive_esl_sum1+$intensive_esl_sum2+$intensive_esl_sum3)?>P,
			BP : <?=number_format($business_program_sum1+$business_program_sum2+$business_program_sum3)?>P,
			SE : <?=number_format($sparta_esl_sum1+$sparta_esl_sum2+$sparta_esl_sum3)?>P,
			TB : <?=number_format($toeic_bridge_sum1+$toeic_bridge_sum2+$toeic_bridge_sum3)?>P,

			RT : <?=number_format($regular_toeic_sum1+$regular_toeic_sum2+$regular_toeic_sum3)?>P,

			T600 : <?=number_format($guarantee_toeic600_sum1+$guarantee_toeic600_sum2+$guarantee_toeic600_sum3)?>P,
			T700 : <?=number_format($guarantee_toeic700_sum1+$guarantee_toeic700_sum2+$guarantee_toeic700_sum3)?>P,
			T850 : <?=number_format($guarantee_toeic850_sum1+$guarantee_toeic850_sum2+$guarantee_toeic850_sum3)?>P,
			IB : <?=number_format($ielts_bridge_sum1+$ielts_bridge_sum2+$ielts_bridge_sum3)?>P,

			RI : <?=number_format($regular_ielts_sum1+$regular_ielts_sum2+$regular_ielts_sum3)?>P,

			I5.5 : <?=number_format($guarantee_ielts55_sum1+$guarantee_ielts55_sum2+$guarantee_ielts55_sum3)?>P,
			I6.0 : <?=number_format($guarantee_ielts60_sum1+$guarantee_ielts60_sum2+$guarantee_ielts60_sum3)?>P,
			I6.5 : <?=number_format($guarantee_ielts65_sum1+$guarantee_ielts65_sum2+$guarantee_ielts65_sum3)?>P,
			CeT : <?=number_format($certificated_tesol_sum1+$certificated_tesol_sum2+$certificated_tesol_sum3)?>P,
			WH : <?=number_format($working_holiday_sum1+$working_holiday_sum2+$working_holiday_sum3)?>P,
			VA : <?=number_format($volunteer_program_sum1+$volunteer_program_sum2+$volunteer_program_sum3)?>P,
			TC : <?=number_format($benedicto_college_sum1+$benedicto_college_sum2+$benedicto_college_sum3)?>P,
			BC : <?=number_format($budget_cours_sum1+$budget_cours_sum2+$budget_cours_sum3)?>P,
			PI : <?=number_format($power_intensive_esl_sum1+$power_intensive_esl_sum2+$power_intensive_esl_sum3)?>P

		</td>
	</tr>
</table>

<br>

<table width="1645" cellspacing="0" style="border-collapse:collapse;table-layout:auto">

<form name="search_form" method="get" enctype="multipart/form-data" onsubmit="return check_searchform();">
<input type="hidden" name="search_student_dateT" value="<?=$search_student_dateT?>">

	<tr>
		<td>
			<select name="ss[1]" onChange="search_form.submit()" class="select2">
			<option value="">=DATE OF REGISTRATION=</option>
				<?=rg_html_option($_regi['state'],"$ss[1]")?>
			</select>

			<select name="course_select" onChange="search_form.submit()" class="select2">
				<option value="">=Course Select=</option>
				<?=rg_html_option($_regi['campus_habselect_search'],"$course_select")?>
			</select>

			<select name="ot" onChange="search_form.submit()" class="select2">
			<option value="">=Order List=</option>
			<option value="10" <? if($ot == 10){ ?>selected<? } ?>>40 Low</option>
			<option value="11" <? if($ot == 11){ ?>selected<? } ?>>60 Low</option>
			<option value="12" <? if($ot == 12){ ?>selected<? } ?>>100 Low</option>
			<option value="13" <? if($ot == 13){ ?>selected<? } ?>>500 Low</option>
			</select>

			<select name="agency_register" onChange="search_form.submit()" class="select2">
			<option value="">=Country=</option>
			<? $ss_list2 = array(1=>'KOREA',7=>'JAPAN',2=>'TAIWAN',3=>'CHINA',5=>'VIETNAM',8=>'ARAB',4=>'OTHERS'); ?>
			<?=rg_html_option($ss_list2,"$agency_register")?>
			</select>

			<select name="stradmission_state" onChange="search_form.submit()" class="select2">
				<option value="">=Fee=</option>
				<?=rg_html_option($_regi['admission_state'],"$stradmission_state")?>
			</select>

			<!--캠퍼스 : <select name="campus" onChange="search_form.submit()">
			<option value="">=전체=</option>
			<?=rg_html_option($_regi['campus'],"$campus")?>
			</select-->

			<select name="strconfirm_check" onChange="search_form.submit()" class="select2">
			<option value="">=Memo=</option>
				<?=rg_html_option($_regi['confirm_check'],"$strconfirm_check")?>
			</select>

			<select name="ss[0]" class="select2">
			<? $ss_list = array(1=>'Name',2=>'Agency',3=>'Eng_Name',4=>'ID of Student'); ?>
			<?=rg_html_option($ss_list,"$ss[0]")?>
			</select>
			<input name="kw" type="text" id="kw" value="<?=$kw?>" size="14" class="input">

			<input type="submit" name="검색" value="SEARCH" class="button">
			<input type="button" value="RESET" onclick="location.href='?'" class="button">

		</td>
	</tr>

	<tr><td height="5"></td></tr>

<link href="<?=$_url['css']?>redmond/jquery-ui-1.8.20.custom.css" rel="stylesheet" type="text/css">

<script>

$(document).ready(function()
{	
	$("#regi1").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#regi2").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#abrod_date_str1").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#abrod_date_str2").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#arrival_date_str1").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#arrival_date_str2").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
});

</script>

	<tr>
		<td>
			DATE OF REGISTRATION
			<input type="text" name="regi1" class="cc" size="10" readonly="readonly" id="regi1" value="<?=$regi1?>"/>
			~ <input type="text" name="regi2" class="cc" size="10" readonly="readonly" id="regi2" value="<?=$regi2?>"/>
|
			CHECK IN
			<input type="text" name="abrod_date_str1" class="cc" size="10" readonly="readonly" id="abrod_date_str1" value="<?=$abrod_date_str1?>"/>
			~ <input type="text" name="abrod_date_str2" class="cc" size="10" readonly="readonly" id="abrod_date_str2" value="<?=$abrod_date_str2?>"/>
|
			CHECK OUT
			<input type="text" name="arrival_date_str1" class="cc" size="10" readonly="readonly" id="arrival_date_str1" value="<?=$arrival_date_str1?>"/>
			~ <input type="text" name="arrival_date_str2" class="cc" size="10" readonly="readonly" id="arrival_date_str2" value="<?=$arrival_date_str2?>"/>

|

			<a href="#" onclick="window_open('./group_mail.php?<?=$p_str?>&course_select=<?=$course_select?>&search_student_dateT=<?=$search_student_dateT?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>&amp;page=<?=$page?>&amp;mode=modify&amp;num=<?=$R[num]?>','group_mail','scrollbars=no,width=700,height=400');">[Bulk Mail]</a>

|

			<a href="#" onclick="window_open('./group_sms.php?<?=$p_str?>&course_select=<?=$course_select?>&search_student_dateT=<?=$search_student_dateT?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>&amp;page=<?=$page?>&amp;mode=modify&amp;num=<?=$R[num]?>','group_sms','scrollbars=no,width=400,height=320');">[Bulk SMS]</a>

|

			<a href="#" onclick="window_open('./regi_list_excel.php?<?=$p_str?>&course_select=<?=$course_select?>&search_student_dateT=<?=$search_student_dateT?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>&amp;page=<?=$page?>&amp;mode=modify&amp;num=<?=$R[num]?>','group_excel','scrollbars=no,width=400,height=320');">[Excel]</a>


|

			<a href="#" onclick="window_open('./regi_list_print.php?<?=$p_str?>&course_select=<?=$course_select?>&search_student_dateT=<?=$search_student_dateT?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>&amp;page=<?=$page?>&amp;mode=modify&amp;num=<?=$R[num]?>','group_print','scrollbars=yes,width=900,height=700');">[Print]</a>

			<!--td align="right" class="tit20_b" style="border: #000000 solid; border-width: 1px 1px 1px 1px; padding: 0 10px 0 10px"><strong>Total : 
			<?=number_format($total_anum)?>명
			(<?=number_format($total_aprice)?>주)</strong></td-->
		</td>
	</tr>

	<tr><td height="5"></td></tr>

</form>
</table>

<form name="list_form" method="post" enctype="multipart/form-data" action="?<?=$p_str?>">
<input name="mode" type="hidden" value="">
<!--table border="0" cellpadding="0" cellspacing="0" width="1425" class="site_list" onmouseover="list_over_color(event,'#FFE6E6',1)" onmouseout="list_out_color(event)"-->
<table border="0" cellpadding="0" cellspacing="0" width="1645" class="site_list">
  <tr align="center" bgcolor="#F0F0F4">

	<td width="35" height="26" class="tit11_cb">No</td>
	<td width="35" class="tit11_cb">Edit</td>
	<td width="70" class="tit11_cb">Stu-No.</td>
	<td width="70" class="tit11_cb">Status</td>
	<td width="70" class="tit11_cb">Date</td>
	<td width="70" class="tit11_cb">Room</td>
	<td width="70" class="tit11_cb">Period</td>
	<td width="35" class="tit11_cb">
		<? if($regi_date_order == 'asc'){ ?>
			<a href="./regi_list.php?regi_date_order=desc"><u>Cty</u></a>
		<? }else{ ?>
			<a href="./regi_list.php?regi_date_order=asc"><u>Cty</u></a>
		<? } ?>
	</td>

	<td width="250" class="tit11_cb">Eng Name</td>

    <td width="70" class="tit11_cb">Birth</td>
	<td width="40" class="tit11_cb">Age</td>

	<td width="40" class="tit11_cb">Gen.</td>
    <td width="200" class="tit11_cb">Agency</td>

    <td width="70" class="tit11_cb">Dep.</td>
    <td width="70" class="tit11_cb">Ret.</td>

    <td width="70" class="tit11_cb">Flight</td>
	<td width="70" class="tit11_cb">Pickup</td>

	<td width="150" class="tit11_cb">Name</td>

	<td width="90" class="tit11_cb">Course</td>
	<td width="70" class="tit11_cb">Net</td>

	<td width="70" class="tit11_cb">Paid</td>

	<td width="70" class="tit11_cb">En-C</td>

	<? if($_mb['mb_id'] == 'cbsangel'){ ?>
		<td width="45" class="tit11_cb">D_btn</td>
	<? } ?>

  </tr>
<?
	$curri_new_pro = mktime(0,0,0,04,13,2012);

	if($rs_list->num_rows()<1) {
		echo "
	<tr height=\"100\">
		<td align=\"center\" colspan=\"24\"><B>NO DATA</td>
	</tr>";
	}

	$rs_list->set_table($_table['regi']);	
	$no = $page_info['start_no'];
	while($R=$rs_list->fetch()) {
	$no--;



		$rs_new_comment = new $rs_class($dbcon);
		$rs_new_comment->clear();
		$rs_new_comment->set_table($_table['regi_comment']);
		$rs_new_comment->add_where("cmt_num = '$R[num]'");
		$rs_new_comment->add_order("num DESC");	
		$rs_new_comment->limit ="1";	
		$new_write=$rs_new_comment->fetch();

		//$camp_new_date1 = date("Y",$new[reg_date]); 
		//$camp_new_date2 = date("m",$new[reg_date]); 
		//$camp_new_date3 = date("d",$new[reg_date]); 

		//if($new_date1 == $camp_new_date1 and $new_date2 == $camp_new_date2 and $new_date3 == $camp_new_date3){  
		if(time() < ($new_write[reg_date]+86400)){
			$new_notice = " <img src='./images/ico_new.gif' align='absmiddle'>";
		}else{
			$new_notice = "";
		}


	//임시 net금액 구하기
	if($stradmission_state == 1 And $agency_register != ''){
		$total_net_price = $total_net_price+$R[agency_cost];
	}

	$sex_no=substr($R[jumin32],0,1);
	if($sex_no==1 || $sex_no==3){
		$sex="M";
	}elseif($sex_no==2 || $sex_no==4){
		$sex="F";
	}

   $curri_new = mktime(0,0,0,11,12,2010);

	if($curri_new > $R[regi_date]){
		$class_type=$_regi['class_type_s'];
	}else{
		$class_type=$_regi['class_type_s_new'];
	}

	$jumin1 = substr($R[jumin31],0,1);

	if($jumin1 == '0'){
	$agey = substr($R[jumin31],0,2);
	$agey = "20".$agey ;
	}else{
	$agey = substr($R[jumin31],0,2);
	$agey = "19".$agey ;
	}

	$age_year = date(Y);
	$age = $age_year - $agey + 1 ;

	switch($R['campus']) {
		case 0 :
			$campus_str = "-";
			break;
		case 1 :
			$campus_str = "1Cam(Classic)";
			break;
		case 2 : 
			$campus_str = "2Cam(Sparta)";
			break;
	}

	$rs_mb = new $rs_class($dbcon);
	$rs_mb->clear();
	$rs_mb->set_table($_table['member']);
	$rs_mb->add_where("mb_id='$R[agency_id]'");
    $Rmb=$rs_mb->fetch() ;
?>
  <tr height="25"
	<? if($R[cancel_yes_no] == 1){ ?>
		bgcolor="#e5e5e5"
	<? }elseif($R[cancel_yes_no] == 2){?>
		bgcolor="#FFE6E6"
	<? }elseif($R[extension_yes_no] == 1){?>
		bgcolor="#dcdbfe"
	<? }elseif($R[extension_yes_no] == 2){?>
		bgcolor="#dcdbfe"
	<? }elseif($R[refund_yes_no] == 1){?>
		bgcolor="#fef7b7"
	<? }elseif($R[refund_yes_no] == 2){?>
		bgcolor="#fef7b7"
	<? }elseif($R[change_yes_no] == 1){?>
		bgcolor="#e5e5e5"
	<? }elseif($R[change_yes_no] == 2){?>
		bgcolor="#d3f7f2"
	<?}?>>

    <td align="center" class="tit11_c"><?=$no?></td>

	<?if($curri_new_pro < $R[regi_date]){?>

		<?if($campus_str == '1Cam(Classic)'){?>
			<td align="center" class="tit11_c"><a href="./regi_edit_classic.php?<?=$p_str?>&amp;course_select=$course_select&amp;search_student_dateT=<?=$search_student_dateT?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>&amp;page=<?=$page?>&amp;mode=modify&amp;num=<?=$R[num]?>"><img src="../img/sbt_modify.gif" border="0" /></a></td>
		<?}elseif($campus_str == '2Cam(Sparta)'){?>
			<td align="center" class="tit11_c"><a href="./regi_edit_sparta.php?<?=$p_str?>&amp;course_select=$course_select&amp;search_student_dateT=<?=$search_student_dateT?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>&amp;page=<?=$page?>&amp;mode=modify&amp;num=<?=$R[num]?>"><img src="../img/sbt_modify.gif" border="0" /></a></td>
		<?}?>

	<?}else{?>

		<td align="center" class="tit11_c"><a href="./regi_edit.php?<?=$p_str?>&amp;course_select=$course_select&amp;search_student_dateT=<?=$search_student_dateT?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>&amp;page=<?=$page?>&amp;mode=modify&amp;num=<?=$R[num]?>"><img src="../img/sbt_modify.gif" border="0" /></a></td>

	<?}?>


	<? if($R['student_id'] != 0){ ?>
		<td align="center" class="tit11_c"><?=$R[student_id]?></td>
	<? }else{ ?>
		<td align="center" class="tit11_c">-</td>
	<? } ?>

	<td align="center" class="tit11_c"><?=$_regi['state_2str1'][$R[state]]?></td>

	<td align="center" class="tit11_c"><?=rg_date($R[regi_date],'%y/%m/%d')?></td>

	<td align="center" class="tit11_c"><?=$_regi['dorm_type_list'][$R[dorm_type1]]?>

		<?if($R[dorm_type2] != 0){ ?>
			, <?=$_regi['dorm_type_list'][$R[dorm_type2]]?>
		<? } ?>

		<? if($R[dorm_type3] != 0){ ?>
			, <?=$_regi['dorm_type_list'][$R[dorm_type3]]?>
		<? } ?>

	</td>

	<td align="center" class="tit11_c">
		<? if($R[state] == 8){ ?>
			<? if($R[cancel_yes_no] == 1){ ?>
				<?=$R[class_gigan]?>W
			<? }elseif($R[cancel_yes_no] == 2){?>
				-<?=$R[class_gigan]?>W
			<? } ?>
		<? }elseif($R[state] == 7){ ?>
			-<?=$R[class_gigan]?>W
		<? }else{ ?>
			<?=$R[class_gigan]?>W
		<? } ?>
	</td>

	<td align="center" class="tit11_c">
		<?//=$national[$R['national']]?>
		<? if($R['national'] == 1){ ?>
			<img src="./images/national_korea.gif">
		<? }elseif($R['national'] == 2){ ?>
			<img src="./images/national_taiwan.gif">
		<? }elseif($R['national'] == 3){ ?>
			<img src="./images/national_china.gif">
		<? }elseif($R['national'] == 4){ ?>
			<img src="./images/national_etc.gif">
		<? }elseif($R['national'] == 5){ ?>
			<img src="./images/national_vietnam.gif">
		<? }elseif($R['national'] == 7){ ?>
			<img src="./images/national_japan.gif">
		<? }elseif($R['national'] == 8){ ?>
			<img src="./images/national_arab.png">
		<? }elseif($R['national'] == 9){ ?>
			<img src="./images/national_thailand.gif">
		<? }elseif($R['national'] == 10){ ?>
			<img src="./images/national_russia.gif">
		<? } ?>
	</td>

	<td align="center" class="tit11_c"
		<? if($R[check] == 1){ ?>
			bgcolor="#ea0000"
		<?}?>>

		<? if($R[refund_yes_no] == 2){ ?>
			<a href="#" onclick="window_open('../invoice/refund_invoice.php?inv_type=st&num=<?=$R[num]?>','refund_invoice','scrollbars=no,width=700,height=922');"><?=$R[sename]?></a>
		<? }else{ ?>
			<a href="#" onclick="window_open('../invoice/invoice.php?inv_type=st&num=<?=$R[num]?>','invoice','scrollbars=no,width=700,height=922');"><?=$R[sename]?></a>
		<? } ?>

		<? if($R[confirm_check] == '1'){ ?><img src="./images/confirm_check.gif" title="<?=$R[memo]?>"><? } ?>
	</td>

	<td align="center" class="tit11_c"><?=$R[jumin31]?>-<?=substr("$R[jumin32]",0,1)?></td>
	<td align="center" class="tit11_c"><?=$age?></td>
    <td align="center" class="tit11_c"><?=$sex?></td>

	<td align="center" class="tit11_c">

		<? if($R[refund_yes_no] == 2){ ?>
			<a href="#" onclick="window_open('../invoice/refund_invoice.php?inv_type=ag&num=<?=$R[num]?>','refund_invoice','scrollbars=no,width=700,height=965');"><? echo $mb_name_length=rg_cut_string($Rmb[mb_name],20,$suffix='..'); ?>
		<? }else{ ?>
			<a href="#" onclick="window_open('../invoice/invoice.php?inv_type=ag&num=<?=$R[num]?>','invoice','scrollbars=no,width=700,height=965');"><? echo $mb_name_length=rg_cut_string($Rmb[mb_name],20,$suffix='..'); ?>
		<? } ?>

		<? if($R[agency_id] == 'uhakpp' Or $R[agency_id] == 'uhak' Or $R[agency_id] == 'chongro' Or $R[agency_id] == 'phil' Or $R[agency_id] == 'uhakwizw' Or $R[agency_id] == 'hparker'){ ?>
			(<?=$R[chain]?>)
		<? } ?><?=$new_notice?>

		</a>

	</td>

    <td align="center" class="tit11_c">
		<?
			if($R[abrod_date2] < 10){
				$abrod_date2_str = "0".$R[abrod_date2];
			}else{
				$abrod_date2_str = $R[abrod_date2];
			}

			if($R[abrod_date3] < 10){
				$abrod_date3_str = "0".$R[abrod_date3];
			}else{
				$abrod_date3_str = $R[abrod_date3];
			}
		?>
		<?=$R[abrod_date1]?>.<?=$abrod_date2_str?>.<?=$abrod_date3_str?>
	</td>
    <td align="center" class="tit11_c">
		<?
			if($R[end_date2] < 10){
				$end_date2_str = "0".$R[end_date2];
			}else{
				$end_date2_str = $R[end_date2];
			}

			if($R[end_date3] < 10){
				$end_date3_str = "0".$R[end_date3];
			}else{
				$end_date3_str = $R[end_date3];
			}
		?>
		<?=$R[end_date1]?>.<?=$end_date2_str?>.<?=$end_date3_str?>
	</td>

    <td align="center" class="tit11_c"><?=$_regi['airplane_text'][$R[airplane]]?></td>

    <td align="center" class="tit11_c" ><?if($R[pick_up]==2){?><a href="#" onclick="window_open('./pick_up.php?num=<?=$R[num]?>','pick_up','scrollbars=no,width=700,height=975');">Y</a><?}else{?>N<?}?></td>

	<td align="center" class="tit11_c">
		<? if($R['reg_confirm_admin'] != ''){ ?>
			<a href="#" onclick="window_open('../invoice/st_receipt.php?inv_type=st&num=<?=$R[num]?>','st_receipt','scrollbars=no,width=700,height=922');"><?=$R[sname]?></a>
		<? }else{ ?>
			<?=$R[sname]?>
		<? } ?>



	</td>

	<?if($curri_new_pro < $R[regi_date]){?>

		<td align="center" class="tit11_c" <? if($R['class_type1_1'] != 0 Or $R['class_type2_1'] != 0 Or $R['class_type3_1'] != 0){ ?>bgcolor="#feaaaa"<? } ?>>

			<? if($R['class_type1_1'] != 0){ ?>
				<?=$_regi['campus_habselect_str'][$R['class_type1_1']]?>
			<? }else{ ?>
				<?=$_regi['campus_habselect_str'][$R['class_type1']]?>
			<? } ?>

			<? if($R['class_type2_1'] != 0){ ?>
				, <?=$_regi['campus_habselect_str'][$R['class_type2_1']]?>
			<? }elseif($R['class_type2'] != 0){ ?>
				, <?=$_regi['campus_habselect_str'][$R['class_type2']]?>
			<? } ?>

			<? if($R['class_type3_1'] != 0){ ?>
				, <?=$_regi['campus_habselect_str'][$R['class_type3_1']]?>
			<? }elseif($R['class_type3'] != 0){ ?>
				, <?=$_regi['campus_habselect_str'][$R['class_type3']]?>
			<? } ?>

		</td>
	
	<?}else{?>

		<td align="center" class="tit11_c"><?=$class_type[$R[class_type1]]?></td>

	<?}?>

	<td align="center" class="tit11_c">

		<? if($R[international_money] == 0){ ?>

			<? if($R[refund_yes_no] == 2){ ?>
				-\<?=number_format($R[class_cost]+$R[dorm_cost])?>
			<? }else{ ?>
				\<?=number_format($R[agency_cost])?>
			<? } ?>

		<? }elseif($R[international_money] == 1){ ?>

			<? if($R[refund_yes_no] == 2){ ?>
				-$<?=number_format($R[class_cost]+$R[dorm_cost])?>
			<? }else{ ?>
				$<?=number_format($R[agency_cost])?>
			<? } ?>

		<? } ?>
		
	</td>

	<? if($R['final_deposit_admin'] != ''){ ?>
		<td align="center" class="tit11_c" bgcolor="#d1ffeb"><a href="#" onclick="window_open('./regi_deposit_memo_popup.php?num=<?=$R[num]?>','regi_deposit_memo_popup','scrollbars=yes,width=820,height=300');"><?=$_regi['admission_state'][$R[admission_state]]?></a></td>
	<? }else{ ?>
		<td align="center" class="tit11_c"><?=$_regi['admission_state'][$R[admission_state]]?></td>
	<? } ?>

    <td align="center" class="tit11_c" >

		<? if($R['reg_confirm_admin'] != ''){ ?>
			Y
		<? }else{ ?>
			N
		<? } ?>

		<!--색생변경-->
		<? if($_mb['mb_id'] == 'cbsangel'){ ?>

			<a href="#" onclick="confirm_cancel('regi_delete.php?<?=$p_str?>&course_select=<?=$course_select?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&campus=<?=$campus?>&amp;page=<?=$page?>&amp;mode=color&amp;num=<?=$R[num]?>')">색상</a>

		<? } ?>

	</td>

	<? if($_mb['mb_id'] == 'cbsangel'){ ?>

		<td align="center" class="tit11_c"><a href="#" onclick="confirm_del('regi_delete.php?<?=$p_str?>&course_select=<?=$course_select?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&campus=<?=$campus?>&amp;page=<?=$page?>&amp;mode=delete&amp;num=<?=$R[num]?>')"><img src="../img/sbt_del.gif" border="0" /></a></td>

	<? } ?>

  </tr>
<?
}
?>
</table>
</form>
<table width="1645">
	<tr>
		<td align="left"><img src="../images/explain_list.gif">
		<? if($stradmission_state == 1 And $agency_register != ''){ ?>
			Net Total : <?=number_format($total_net_price)?></td>
		<? } ?>
	</tr>
</table>

<table width="1645">
	<tr>
		<td align="center">
			<?=rg_navi_display1($page_info,$ex_page,$_get_param[2]."&course_select=".$course_select."&regi1=".$regi1."&regi2=".$regi2."&abrod_date_str1=".$abrod_date_str1."&abrod_date_str2=".$abrod_date_str2."&arrival_date_str1=".$arrival_date_str1."&arrival_date_str2=".$arrival_date_str2."&search_student_dateT=".$search_student_dateT."&stradmission_state=".$stradmission_state."&agency_register=".$agency_register."&strconfirm_check=".$strconfirm_check."&campus=".$campus); ?>
		</td>
	</tr>
</table>

<br />

<? include("admin.footer_new.php"); ?>
<? include("_footer.php"); ?>