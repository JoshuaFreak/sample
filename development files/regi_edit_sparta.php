<?
	include_once("../include/lib.php");
	require_once("admin_chk.php");

	if($mode=='modify' || $mode=='delete') {
		$rs->clear();
		$rs->set_table($_table['regi']);
		$rs->add_where("num=$num");
		$rs->select();
		if($rs->num_rows()!=1) { // ������ �ùٸ��� �ʴٸ�
			rg_href('','������ ã���� �����ϴ�.','back');
		}
		$data=$rs->fetch();
	} else {
		$data=$rs->fetch();		
	}

//	3647 �������п�
	$now =  time() ;

//	echo $test_date = mktime(0,0,0,3,31,2015); //20150331 = 1427727600 
//	echo $test_date = mktime(0,0,0,6,30,2017); //20170630 = 1498748400 
//	echo "<br>";
//	echo time();

//	echo	$test_date = mktime(0,0,0,01,02,2014); => 1388588400

	$cost_st_date = mktime(0,0,0,10,18,2010);
	$elect_st_date = mktime(0,0,0,11,19,2010);

	//���ٽú� �к�(75����->80����)�� �����̾� 1�ν�(90����->120����) �ݾ׺��� 2013�� 1�� 1�Ϻ��� ������
	$old_comm_fee2013 = mktime(23,59,59,3,31,2013);

	//���ٽú� �к�(75����->80����)�� �����̾� 1�ν�(90����->120����) �ݾ׺��� 2013�� 1�� 1�Ϻ��� ������
	$old_intensive_esl_fee = mktime(23,59,59,12,31,2012);

	//���� �к�(5������ ����) �Ϲ�1�ν�(85����->90����) �ݾ׺��� 2014�� 1�� 1�Ϻ��� ������
	$old_toeic_fee_up = mktime(23,59,59,12,31,2013);

	//���� �긴��(75����->78����), ���� �긴��(75����->80����) �к�,  �ݾ׺��� 2014�� 5�� 2�Ϻ��� ������
	$bridge_fee_up = mktime(23,59,59,4,30,2014);

	//�Ϻ� 1��, 2��, 3�� ��� �л� �к� ����(1��:50%->40%,2��:70%->60%,3��:90%->80%), 2014�� 8�� 12�Ϻ��� ������
	$japan_123week_fee_down = mktime(23,59,59,8,11,2014);

	//�Ϻ� 2014�� 12�� ��ü �к� ����, 2015�� 1�� 1�Ϻ��� ������
	$japan_2014_class_fee_up = mktime(23,59,59,12,31,2014);

	//�Ϻ��� ������ ��Ÿ���� ������ ȣ�� 1100�޷� ����, 2015�� 1�� 23�Ϻ��� ������
	$etc_con_pre_room_fee_down = mktime(23,59,59,1,22,2015);

	//�ѱ� 2015�� 2�� ��ü �к� ����, 2015�� 2�� 1�Ϻ��� ������
	$korea_2015_class_fee_up = mktime(23,59,59,1,31,2015);

	//�븸,�߱�,��Ʈ�� 2015�� 4�� �����ڽ�, ���� 1�ν� �к� ����, 2015�� 4�� 10�Ϻ��� ������
	$inter_2015_class_fee_up = mktime(23,59,59,4,9,2015);

	//�ѱ� 2017�� 7�� ������ ����, 2017�� 7�� 1�Ϻ��� ������
	$korea_2017_room_fee_up = mktime(23,59,59,06,30,2017);

//	$cost_st_date = rg_date($cost_st_date);

	// ����
	if($mode=='delete') {
		// �б� ����
		$rs->clear();
		$rs->set_table($_table['regi']);
		$rs->add_where("num=$num");
		$rs->delete();
		$rs->commit();
		rg_href("regi_list.php?$_get_param[3]");
	}

	$rs_mem = new $rs_class($dbcon);
	$rs_mem->set_table($_table['member']);
	$rs_mem->add_where("mb_id='$data[agency_id]'");
	$no = $page_info['start_no'];
	$mem=$rs_mem->fetch();


	if($_SERVER['REQUEST_METHOD']=='POST') {

		//�л������������ ���°� ��Ͽ����̳� ȯ����� �����̸� ��Ϻ� ��� 0������ �����Ѵ�.
		if($state=='4' Or $state=='5' Or $state=='7') {
			$comm_regi_cost = 0;
			$regi_cost = 0;
		}

		//�ѱ��� ���� Ŀ�̼�
		if($international_money == 0){

			$curri_new_pro = mktime(0,0,0,05,01,2012);
			$now_time = time();

			//����� ���� ����
			$class_gigan = $class_gigan1+$class_gigan2+$class_gigan3;

			//�ڡڡڡڡڡڼ������ �νǿ� ���� ������ ���ڡڡڡڡڡ�
			if($class_type1 != ''){
			  $rs1 = new $rs_class($dbcon);
			  $rs1->clear();
			  $rs1->set_table($_table['cost']);

				//���Ͱ����� 2013�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ����� �����Ѵ�
				if($old_toeic_fee_up >= $regi_date){
//					20131231 >= 20170629
					$rs1->add_where("used=1");
//					echo "����1";
				}elseif($bridge_fee_up >= $regi_date){
//					20140430 >= 20170629
					$rs1->add_where("used=2");
//					echo "����2";
				}elseif($korea_2015_class_fee_up >= $regi_date){
//					20150131 >= 20170629
					$rs1->add_where("used=3");
//					echo "����3";
				}elseif($korea_2017_room_fee_up >= $regi_date){
//					20170630 >= 20170629
					$rs1->add_where("used=4");
//					echo "����4";
				}else{
					$rs1->add_where("used=5");
//					echo "����5";
				}
//exit;

			  $rs1->add_where("gubun=$class_type1");

			  $rs1->select();
			  $data1=$rs1->fetch();

				//���ٽú�ESL���� 2012�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ��� 75�������� �����Ѵ�
				if($old_intensive_esl_fee >= $regi_date){

					if($class_type1 == 22){

						//ȯ����Ҹ� ���⸦ ����
						if($state == 7){
							if($refund_percent == 1){
								$class_cost1 = ($class_gigan1 * (750000/4))*0.3;
							}elseif($refund_percent == 2){
								$class_cost1 = ($class_gigan1 * (750000/4))*0.5;
							}elseif($refund_percent == 3){
								$class_cost1 = ($class_gigan1 * (750000/4))*0.7;
							}elseif($refund_percent == 8){
								$class_cost_8_1 = $class_gigan1 * (750000/4);
								$class_cost_8_2 = 4 * (750000/4);
								$class_cost1 = $class_cost_8_1 - $class_cost_8_2;
							}else{
								$class_cost1 = $class_gigan1 * (750000/4);
							}
						}else{
							$class_cost1 = $class_gigan1 * (750000/4);
						}

					}else{

						//ȯ����Ҹ� ���⸦ ����
						if($state == 7){
							if($refund_percent == 1){
								$class_cost1 = ($class_gigan1 * ($data1[class_fee]/4))*0.3;
							}elseif($refund_percent == 2){
								$class_cost1 = ($class_gigan1 * ($data1[class_fee]/4))*0.5;
							}elseif($refund_percent == 3){
								$class_cost1 = ($class_gigan1 * ($data1[class_fee]/4))*0.7;
							}elseif($refund_percent == 8){
								$class_cost_8_1 = $class_gigan1 * ($data1[class_fee]/4);
								$class_cost_8_2 = 4 * ($data1[class_fee]/4);
								$class_cost1 = $class_cost_8_1 - $class_cost_8_2;
							}else{
								$class_cost1 = $class_gigan1 * ($data1[class_fee]/4);
							}
						}else{
							$class_cost1 = $class_gigan1 * ($data1[class_fee]/4);
						}

					}

				}else{

					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$class_cost1 = ($class_gigan1 * ($data1[class_fee]/4))*0.3;
						}elseif($refund_percent == 2){
							$class_cost1 = ($class_gigan1 * ($data1[class_fee]/4))*0.5;
						}elseif($refund_percent == 3){
							$class_cost1 = ($class_gigan1 * ($data1[class_fee]/4))*0.7;
						}elseif($refund_percent == 8){
							$class_cost_8_1 = $class_gigan1 * ($data1[class_fee]/4);
							$class_cost_8_2 = 4 * ($data1[class_fee]/4);
							$class_cost1 = $class_cost_8_1 - $class_cost_8_2;
						}else{
							$class_cost1 = $class_gigan1 * ($data1[class_fee]/4);
						}
					}else{

						//�׼ֶ����� ����ҽ�20140227
						if($class_type1 == 27 And $class_gigan1 == 8){
							$class_cost1 = ($class_gigan1 * ($data1[class_fee]/4))-500000;
						}else{
							//�̸������ �л� ���� ����� ������ ���� �ҽ�20140318
							if($num == '7373' Or $num == '7375'){
								$class_cost1 = $class_gigan1 * (920000/4);
							}else{
								$class_cost1 = $class_gigan1 * ($data1[class_fee]/4);
							}
						}

					}

				}
				//�����᳡
	

			  //�����
			  if($dorm_type1 == 1){

					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee1]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee1]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee1]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee1]/4;
							$refund_dorm_type1_cost=$data1[house_fee1]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee1]/4;
							$refund_dorm_type1_cost=$data1[house_fee1];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee1]/4;
							$refund_dorm_type1_cost=$data1[house_fee1];
						}else{
							$dorm_type1_cost=$data1[house_fee1]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee1]/4;
					}

			  }elseif($dorm_type1 == 2){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee2]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee2]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee2]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee2]/4;
							$refund_dorm_type1_cost=$data1[house_fee2]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee2]/4;
							$refund_dorm_type1_cost=$data1[house_fee2];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee2]/4;
							$refund_dorm_type1_cost=$data1[house_fee2];
						}else{
							$dorm_type1_cost=$data1[house_fee2]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee2]/4;
					}
			  }elseif($dorm_type1 == 3){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee3]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee3]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee3]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee3]/4;
							$refund_dorm_type1_cost=$data1[house_fee3]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee3]/4;
							$refund_dorm_type1_cost=$data1[house_fee3];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee3]/4;
							$refund_dorm_type1_cost=$data1[house_fee3];
						}else{
							$dorm_type1_cost=$data1[house_fee3]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee3]/4;
					}
			  }elseif($dorm_type1 == 4){

					if($old_intensive_esl_fee > $regi_date){

						//ȯ����Ҹ� ���⸦ ����
						if($state == 7){
							if($refund_percent == 1){
								$dorm_type1_cost=(900000/4)*0.3;
							}elseif($refund_percent == 2){
								$dorm_type1_cost=(900000/4)*0.5;
							}elseif($refund_percent == 3){
								$dorm_type1_cost=(900000/4)*0.7;
							}elseif($refund_percent == 6){
								$dorm_type1_cost=900000/4;
								$refund_dorm_type1_cost=900000/2;
							}elseif($refund_percent == 7){
								$dorm_type1_cost=900000/4;
								$refund_dorm_type1_cost=900000;
							}elseif($refund_percent == 8){
								$dorm_type1_cost=900000/4;
								$refund_dorm_type1_cost=900000;
							}else{
								$dorm_type1_cost=900000/4;
							}
						}else{
							$dorm_type1_cost=900000/4;
						}

					}else{
						//ȯ����Ҹ� ���⸦ ����
						if($state == 7){
							if($refund_percent == 1){
								$dorm_type1_cost=($data1[house_fee4]/4)*0.3;
							}elseif($refund_percent == 2){
								$dorm_type1_cost=($data1[house_fee4]/4)*0.5;
							}elseif($refund_percent == 3){
								$dorm_type1_cost=($data1[house_fee4]/4)*0.7;
							}elseif($refund_percent == 6){
								$dorm_type1_cost=$data1[house_fee4]/4;
								$refund_dorm_type1_cost=$data1[house_fee4]/2;
							}elseif($refund_percent == 7){
								$dorm_type1_cost=$data1[house_fee4]/4;
								$refund_dorm_type1_cost=$data1[house_fee4];
							}elseif($refund_percent == 8){
								$dorm_type1_cost=$data1[house_fee4]/4;
								$refund_dorm_type1_cost=$data1[house_fee4];
							}else{
								$dorm_type1_cost=$data1[house_fee4]/4;
							}
						}else{
							//20130819 ������ �л� ������ ��Ź���� ������ 1�ν��� 90�������� ���� ���߿� ���� ������
							if($num == '6288' Or $num == '6476' Or $num == '6489' Or $num == '6771' Or $num == '6879' Or $num == '7595'){
								$dorm_type1_cost=900000/4;
							}else{
								$dorm_type1_cost=$data1[house_fee4]/4;
							}
						}
					}

			  }elseif($dorm_type1 == 5){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee5]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee5]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee5]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee5]/4;
							$refund_dorm_type1_cost=$data1[house_fee5]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee5]/4;
							$refund_dorm_type1_cost=$data1[house_fee5];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee5]/4;
							$refund_dorm_type1_cost=$data1[house_fee5];
						}else{
							$dorm_type1_cost=$data1[house_fee5]/4;
						}
					}else{
						//20130819 ������ �л� ������ ��Ź���� ������ 1�ν��� 90�������� ���� ���߿� ���� ������
						if($num == '7595'){
							$dorm_type1_cost=900000/4;
						}else{
							$dorm_type1_cost=$data1[house_fee5]/4;
						}
					}
			  }elseif($dorm_type1 == 6){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee6]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee6]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee6]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee6]/4;
							$refund_dorm_type1_cost=$data1[house_fee6]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee6]/4;
							$refund_dorm_type1_cost=$data1[house_fee6];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee6]/4;
							$refund_dorm_type1_cost=$data1[house_fee6];
						}else{
							$dorm_type1_cost=$data1[house_fee6]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee6]/4;
					}
			  }elseif($dorm_type1 == 7){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee7]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee7]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee7]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee7]/4;
							$refund_dorm_type1_cost=$data1[house_fee7]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee7]/4;
							$refund_dorm_type1_cost=$data1[house_fee7];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee7]/4;
							$refund_dorm_type1_cost=$data1[house_fee7];
						}else{
							$dorm_type1_cost=$data1[house_fee7]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee7]/4;
					}
			  }elseif($dorm_type1 == 8){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee8]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee8]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee8]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee8]/4;
							$refund_dorm_type1_cost=$data1[house_fee8]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee8]/4;
							$refund_dorm_type1_cost=$data1[house_fee8];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee8]/4;
							$refund_dorm_type1_cost=$data1[house_fee8];
						}else{
							$dorm_type1_cost=$data1[house_fee8]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee8]/4;
					}
			  }elseif($dorm_type1 == 9){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee9]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee9]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee9]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee9]/4;
							$refund_dorm_type1_cost=$data1[house_fee9]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee9]/4;
							$refund_dorm_type1_cost=$data1[house_fee9];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee9]/4;
							$refund_dorm_type1_cost=$data1[house_fee9];
						}else{
							$dorm_type1_cost=$data1[house_fee9]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee9]/4;
					}
			  }elseif($dorm_type1 == 10){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee10]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee10]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee10]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee10]/4;
							$refund_dorm_type1_cost=$data1[house_fee10]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee10]/4;
							$refund_dorm_type1_cost=$data1[house_fee10];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee10]/4;
							$refund_dorm_type1_cost=$data1[house_fee10];
						}else{
							$dorm_type1_cost=$data1[house_fee10]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee10]/4;
					}
			  }elseif($dorm_type1 == 11){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee11]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee11]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee11]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee11]/4;
							$refund_dorm_type1_cost=$data1[house_fee11]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee11]/4;
							$refund_dorm_type1_cost=$data1[house_fee11];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee11]/4;
							$refund_dorm_type1_cost=$data1[house_fee11];
						}else{
							$dorm_type1_cost=$data1[house_fee11]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee11]/4;
					}
			  }elseif($dorm_type1 == 12){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee12]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee12]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee12]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee12]/4;
							$refund_dorm_type1_cost=$data1[house_fee12]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee12]/4;
							$refund_dorm_type1_cost=$data1[house_fee12];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee12]/4;
							$refund_dorm_type1_cost=$data1[house_fee12];
						}else{
							$dorm_type1_cost=$data1[house_fee12]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee12]/4;
					}
			  }elseif($dorm_type1 == 13){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee13]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee13]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee13]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee13]/4;
							$refund_dorm_type1_cost=$data1[house_fee13]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee13]/4;
							$refund_dorm_type1_cost=$data1[house_fee13];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee13]/4;
							$refund_dorm_type1_cost=$data1[house_fee13];
						}else{
							$dorm_type1_cost=$data1[house_fee13]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee13]/4;
					}
			  }elseif($dorm_type1 == 14){
					//ȯ����Ҹ� ���⸦ ����
					if($state == 7){
						if($refund_percent == 1){
							$dorm_type1_cost=($data1[house_fee14]/4)*0.3;
						}elseif($refund_percent == 2){
							$dorm_type1_cost=($data1[house_fee14]/4)*0.5;
						}elseif($refund_percent == 3){
							$dorm_type1_cost=($data1[house_fee14]/4)*0.7;
						}elseif($refund_percent == 6){
							$dorm_type1_cost=$data1[house_fee14]/4;
							$refund_dorm_type1_cost=$data1[house_fee14]/2;
						}elseif($refund_percent == 7){
							$dorm_type1_cost=$data1[house_fee14]/4;
							$refund_dorm_type1_cost=$data1[house_fee14];
						}elseif($refund_percent == 8){
							$dorm_type1_cost=$data1[house_fee14]/4;
							$refund_dorm_type1_cost=$data1[house_fee14];
						}else{
							$dorm_type1_cost=$data1[house_fee14]/4;
						}
					}else{
						$dorm_type1_cost=$data1[house_fee14]/4;
					}
			  }

			  //���⼼
			  if($dorm_type1 == 1){
				  $elct_fee1=$data1[elect_fee1]/4;
			  }elseif($dorm_type1 == 2){
				  $elct_fee1=$data1[elect_fee2]/4;
			  }elseif($dorm_type1 == 3){
				  $elct_fee1=$data1[elect_fee3]/4;
			  }elseif($dorm_type1 == 4){
				  $elct_fee1=$data1[elect_fee4]/4;
			  }elseif($dorm_type1 == 5){
				  $elct_fee1=$data1[elect_fee5]/4;
			  }elseif($dorm_type1 == 6){
				  $elct_fee1=$data1[elect_fee6]/4;
			  }elseif($dorm_type1 == 7){
				  $elct_fee1=$data1[elect_fee7]/4;
			  }elseif($dorm_type1 == 8){
				  $elct_fee1=$data1[elect_fee8]/4;
			  }elseif($dorm_type1 == 9){
				  $elct_fee1=$data1[elect_fee9]/4;
			  }elseif($dorm_type1 == 10){
				  $elct_fee1=$data1[elect_fee10]/4;
			  }elseif($dorm_type1 == 11){
				  $elct_fee1=$data1[elect_fee11]/4;
			  }elseif($dorm_type1 == 12){
				  $elct_fee1=$data1[elect_fee12]/4;
			  }elseif($dorm_type1 == 13){
				  $elct_fee1=$data1[elect_fee13]/4;
			  }elseif($dorm_type1 == 14){
				  $elct_fee1=$data1[elect_fee14]/4;
			  }
			}

			if($class_type2 != ''){
			  $rs2 = new $rs_class($dbcon);
			  $rs2->clear();
			  $rs2->set_table($_table['cost']);

				//���Ͱ����� 2013�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ����� �����Ѵ�
				if($old_toeic_fee_up >= $regi_date){
					$rs2->add_where("used=1");
//					echo "����1";
				}elseif($bridge_fee_up >= $regi_date){
					$rs2->add_where("used=2");
//					echo "����2";
				}elseif($korea_2015_class_fee_up >= $regi_date){
					$rs2->add_where("used=3");
//					echo "����3";
				}elseif($korea_2017_room_fee_up >= $regi_date){
					$rs2->add_where("used=4");
//					echo "����4";
				}else{
					$rs2->add_where("used=5");
//					echo "����5";
				}


			  $rs2->add_where("gubun=$class_type2");
			  $rs2->select();
			  $data2=$rs2->fetch();

				//���ٽú�ESL���� 2012�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ��� 75�������� �����Ѵ�
				if($old_intensive_esl_fee >= $regi_date){

					if($class_type2 == 22){
						$class_cost2 = $class_gigan2 * (750000/4);
					}else{
						$class_cost2 = $class_gigan2 * ($data2[class_fee]/4);
					}

				}else{

					//�׼ֶ����� ����ҽ�20140227
					if($class_type2 == 27 And $class_gigan2 == 8){
						$class_cost2 = ($class_gigan2 * ($data2[class_fee]/4))-500000;
					}else{
						$class_cost2 = $class_gigan2 * ($data2[class_fee]/4);
					}

				}

			  if($dorm_type2 == 1){
				  $dorm_type2_cost=$data2[house_fee1]/4;
			  }elseif($dorm_type2 == 2){
				  $dorm_type2_cost=$data2[house_fee2]/4;
			  }elseif($dorm_type2 == 3){
				  $dorm_type2_cost=$data2[house_fee3]/4;
			  }elseif($dorm_type2 == 4){

					if($old_intensive_esl_fee > $regi_date){
						$dorm_type2_cost=900000/4;
					}else{
						$dorm_type2_cost=$data2[house_fee4]/4;
					}
			  }elseif($dorm_type2 == 5){
				  $dorm_type2_cost=$data2[house_fee5]/4;
			  }elseif($dorm_type2 == 6){
				  $dorm_type2_cost=$data2[house_fee6]/4;
			  }elseif($dorm_type2 == 7){
				  $dorm_type2_cost=$data2[house_fee7]/4;
			  }elseif($dorm_type2 == 8){
				  $dorm_type2_cost=$data2[house_fee8]/4;
			  }elseif($dorm_type2 == 9){
				  $dorm_type2_cost=$data2[house_fee9]/4;
			  }elseif($dorm_type2 == 10){
				  $dorm_type2_cost=$data2[house_fee10]/4;
			  }elseif($dorm_type2 == 11){
				  $dorm_type2_cost=$data2[house_fee11]/4;
			  }elseif($dorm_type2 == 12){
				  $dorm_type2_cost=$data2[house_fee12]/4;
			  }elseif($dorm_type2 == 13){
				  $dorm_type2_cost=$data2[house_fee13]/4;
			  }elseif($dorm_type2 == 14){
				  $dorm_type2_cost=$data2[house_fee14]/4;
			  }

			  //���⼼
			  if($dorm_type2 == 1){
				  $elct_fee2=$data2[elect_fee1]/4;
			  }elseif($dorm_type2 == 2){
				  $elct_fee2=$data2[elect_fee2]/4;
			  }elseif($dorm_type2 == 3){
				  $elct_fee2=$data2[elect_fee3]/4;
			  }elseif($dorm_type2 == 4){
				  $elct_fee2=$data2[elect_fee4]/4;
			  }elseif($dorm_type2 == 5){
				  $elct_fee2=$data2[elect_fee5]/4;
			  }elseif($dorm_type2 == 6){
				  $elct_fee2=$data2[elect_fee6]/4;
			  }elseif($dorm_type2 == 7){
				  $elct_fee2=$data2[elect_fee7]/4;
			  }elseif($dorm_type2 == 8){
				  $elct_fee2=$data2[elect_fee8]/4;
			  }elseif($dorm_type2 == 9){
				  $elct_fee2=$data2[elect_fee9]/4;
			  }elseif($dorm_type2 == 10){
				  $elct_fee2=$data2[elect_fee10]/4;
			  }elseif($dorm_type2 == 11){
				  $elct_fee2=$data2[elect_fee11]/4;
			  }elseif($dorm_type2 == 12){
				  $elct_fee2=$data2[elect_fee12]/4;
			  }elseif($dorm_type2 == 13){
				  $elct_fee2=$data2[elect_fee13]/4;
			  }elseif($dorm_type2 == 14){
				  $elct_fee2=$data2[elect_fee14]/4;
			  }
			}

			if($class_type3 != ''){
			  $rs3 = new $rs_class($dbcon);
			  $rs3->clear();
			  $rs3->set_table($_table['cost']);

				//���Ͱ����� 2013�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ����� �����Ѵ�
				if($old_toeic_fee_up >= $regi_date){
					$rs3->add_where("used=1");
//					echo "����1";
				}elseif($bridge_fee_up >= $regi_date){
					$rs3->add_where("used=2");
//					echo "����2";
				}elseif($korea_2015_class_fee_up >= $regi_date){
					$rs3->add_where("used=3");
//					echo "����3";
				}elseif($korea_2017_room_fee_up >= $regi_date){
					$rs3->add_where("used=4");
//					echo "����4";
				}else{
					$rs3->add_where("used=5");
//					echo "����5";
				}

			  $rs3->add_where("gubun=$class_type3");
			  $rs3->select();
			  $data3=$rs3->fetch();

				//���ٽú�ESL���� 2012�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ��� 75�������� �����Ѵ�
				if($old_intensive_esl_fee >= $regi_date){

					if($class_type3 == 22){
						$class_cost3 = $class_gigan3 * (750000/4);
					}else{
						$class_cost3 = $class_gigan3 * ($data3[class_fee]/4);
					}

				}else{

					//�׼ֶ����� ����ҽ�20140227
					if($class_type3 == 27 And $class_gigan3 == 8){
						$class_cost3 = ($class_gigan3 * ($data3[class_fee]/4))-500000;
					}else{
						$class_cost3 = $class_gigan3 * ($data3[class_fee]/4);
					}

				}

			  if($dorm_type3 == 1){
				  $dorm_type3_cost=$data3[house_fee1]/4;
			  }elseif($dorm_type3 == 2){
				  $dorm_type3_cost=$data3[house_fee2]/4;
			  }elseif($dorm_type3 == 3){
				  $dorm_type3_cost=$data3[house_fee3]/4;
			  }elseif($dorm_type3 == 4){

					if($old_intensive_esl_fee > $regi_date){
						$dorm_type3_cost=900000/4;
					}else{
						$dorm_type3_cost=$data3[house_fee4]/4;
					}

			  }elseif($dorm_type3 == 5){
				  $dorm_type3_cost=$data3[house_fee5]/4;
			  }elseif($dorm_type3 == 6){
				  $dorm_type3_cost=$data3[house_fee6]/4;
			  }elseif($dorm_type3 == 7){
				  $dorm_type3_cost=$data3[house_fee7]/4;
			  }elseif($dorm_type3 == 8){
				  $dorm_type3_cost=$data3[house_fee8]/4;
			  }elseif($dorm_type3 == 9){
				  $dorm_type3_cost=$data3[house_fee9]/4;
			  }elseif($dorm_type3 == 10){
				  $dorm_type3_cost=$data3[house_fee10]/4;
			  }elseif($dorm_type3 == 11){
				  $dorm_type3_cost=$data3[house_fee11]/4;
			  }elseif($dorm_type3 == 12){
				  $dorm_type3_cost=$data3[house_fee12]/4;
			  }elseif($dorm_type3 == 13){
				  $dorm_type3_cost=$data3[house_fee13]/4;
			  }elseif($dorm_type3 == 14){
				  $dorm_type3_cost=$data3[house_fee14]/4;
			  }

			  if($dorm_type3 == 1){
				  $elct_fee3=$data3[elect_fee1]/4;
			  }elseif($dorm_type3 == 2){
				  $elct_fee3=$data3[elect_fee2]/4;
			  }if($dorm_type3 == 3){
				  $elct_fee3=$data3[elect_fee3]/4;
			  }if($dorm_type3 == 4){
				  $elct_fee3=$data3[elect_fee4]/4;
			  }if($dorm_type3 == 5){
				  $elct_fee3=$data3[elect_fee5]/4;
			  }if($dorm_type3 == 6){
				  $elct_fee3=$data3[elect_fee6]/4;
			  }if($dorm_type3 == 7){
				  $elct_fee3=$data3[elect_fee7]/4;
			  }if($dorm_type3 == 8){
				  $elct_fee3=$data3[elect_fee8]/4;
			  }if($dorm_type3 == 9){
				  $elct_fee3=$data3[elect_fee9]/4;
			  }if($dorm_type3 == 10){
				  $elct_fee3=$data3[elect_fee10]/4;
			  }if($dorm_type3 == 11){
				  $elct_fee3=$data3[elect_fee11]/4;
			  }if($dorm_type3 == 12){
				  $elct_fee3=$data3[elect_fee12]/4;
			  }if($dorm_type3 == 13){
				  $elct_fee3=$data3[elect_fee13]/4;
			  }if($dorm_type3 == 14){
				  $elct_fee3=$data3[elect_fee14]/4;
			  }
			}
			//�ڡڡڡڡڡڼ������ �νǿ� ���� ������ ���ڡڡڡڡڡ�
			  if($num == '7721'){
				  $class_cost = $class_cost1 + $class_cost2 + $class_cost3 - 1200000;
			  }else{
				  $class_cost = $class_cost1 + $class_cost2 + $class_cost3;
			  }


			  $dorm_cost1 = ($dorm_gigan1 * $dorm_type1_cost) - $refund_dorm_type1_cost;

//			  $dorm_cost1 = $dorm_gigan1 * $dorm_type1_cost;

			  $dorm_cost2 = $dorm_gigan2 * $dorm_type2_cost;
			  $dorm_cost3 = $dorm_gigan3 * $dorm_type3_cost;

			  if($num == '7321'){
				  $dorm_cost = $dorm_cost1 + $dorm_cost2 + $dorm_cost3 - 200000;
			  }else{
				  $dorm_cost = $dorm_cost1 + $dorm_cost2 + $dorm_cost3;
			  }

			  $elect_fee1 = $dorm_gigan1 * ($elct_fee1);
			  $elect_fee2 = $dorm_gigan2 * ($elct_fee2);
			  $elect_fee3 = $dorm_gigan3 * ($elct_fee3);

			  $elect_fee = $elect_fee1 + $elect_fee2 + $elect_fee3;
				
			  if($agency_level == 6 || $agency_level == 7 || $agency_level == 8 || $agency_level == 9 || $agency_level == 10 || $agency_level == 11 || $agency_level == 12 || $agency_level == 13 || $agency_level == 14 || $agency_level == 15 || $agency_level == 16){
				$comm_regi_cost=$comm_regi_cost;
			  }else{
				$comm_regi_cost=0;
			  }

//	echo $comm_regi_cost;
//	exit;

		  if($curri_new_pro > $data[regi_date]){



		  }else{

			  $rs_comm = new $rs_class($dbcon);
			  $rs_comm->clear();
			  $rs_comm->set_table($_table['comm']);
			  $rs_comm->add_where("used=1");
			  $rs_comm->select();
			  $data_comm=$rs_comm->fetch();	

			  if($agency_level == 6){
				$ag_comm_fee = $data_comm[agency_comm1]/4 ;
			  }elseif($agency_level == 7){
				$ag_comm_fee = $data_comm[agency_comm2]/4 ;
			  }elseif($agency_level == 8){
				$ag_comm_fee = $data_comm[agency_comm3]/4 ;
			  }elseif($agency_level == 9){
				$ag_comm_fee = $data_comm[agency_comm4]/4 ;
			  }elseif($agency_level == 10){
				$ag_comm_fee = $data_comm[agency_comm5]/4 ;
			  }elseif($agency_level == 11){
				$ag_comm_fee = $data_comm[agency_comm6]/4 ;
			  }elseif($agency_level == 12){
				$ag_comm_fee = $data_comm[agency_comm7]/4 ;
			  }elseif($agency_level == 13){
				$ag_comm_fee = $data_comm[agency_comm8]/4 ;
			  }elseif($agency_level == 14){
				$ag_comm_fee = $data_comm[agency_comm9]/4 ;
			  }elseif($agency_level == 15){
				$ag_comm_fee = $data_comm[agency_comm10]/4 ;
			  }elseif($agency_level == 16){
				$ag_comm_fee = $data_comm[agency_comm11]/4 ;
			  }

//			echo $data_comm[agency_comm4];
//			echo "<br>";
//			echo $agency_level;
//			echo "<br>";

				//��������350000, ������320000 Ŀ�̼� ������ ����ҽ� 20140110
				if($old_comm_fee2013 >= $regi_date){
					if($agency_id == 'cyh7601'){ //������Ŀ�̼� 320000�� ����
						$ag_comm_fee = 320000/4 ;
//						echo "�������";
					}elseif($agency_id == 'uhakpp'){
						$ag_comm_fee = 350000/4 ;
//						echo "�������";
					}
				}

		//	  echo $ag_comm_fee;
		//	  echo "s";
			  if($refund_yes_no == 1){
				$agency_comm = $agency_comm;
			  }else	{

				//�Է�Ƽ ���� �л����� ���ؼ� ���� �ҽ� ���߿� ������ ��
				if($num == '10937'){
					$agency_comm=0;
				}else{
					$agency_comm = $ag_comm_fee * $class_gigan ;
				}

			  }
		  }

//		echo $ag_comm_fee;
//		echo "<br>";
//		echo $agency_comm;
//		exit;

		  //��ݺ��泯¥ 2010.10.18
		  if($cost_st_date <= $regi_date){
		 
			if($elect_st_date <= $regi_date){
			   $total_cost=$regi_cost+$class_cost+$dorm_cost-$event_sale+$addition_fee;
			}else{
			   $total_cost=$regi_cost+$class_cost+$dorm_cost+$elect_fee-$event_sale+$addition_fee;
			}

		  }else{
			$total_cost=$regi_cost+$class_cost+$dorm_cost+$ssp_cost+$elect_fee-$event_sale+$addition_fee;
		  }



			$agency_cost= $total_cost-$comm_regi_cost-$agency_comm-$ev_ag_comm+$ag_add_fee;


		//�ܱ��� ���� & Ŀ�̼�
		}elseif($international_money == 1){

			//����� ���� ����
			$class_gigan = $class_gigan1+$class_gigan2+$class_gigan3;

			if($class_type1 != ''){
				$rs1 = new $rs_class($dbcon);
				$rs1->clear();

				//�Ϻ��к�
				if($national == '7'){

					$rs1->set_table($_table['cost_i']);

					//���Ͱ����� 2013�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ����� �����Ѵ�
					if($old_toeic_fee_up >= $regi_date){
						$rs1->add_where("used=1");
//						echo "�Ϻ� ����1";
//						exit;
					//�Ϻ� �к� 2015�� 1�� 1�Ϻ��� ���� �����մϴ�.
					}elseif($japan_2014_class_fee_up >= $regi_date){
						$rs1->add_where("used=2");
//						echo "�Ϻ� ����2";
//						exit;
					}elseif($korea_2017_room_fee_up >= $regi_date){
						$rs1->add_where("used=3");
//						echo "�Ϻ� ����3";
//						exit;
					}else{
						$rs1->add_where("used=4");
//						echo "�Ϻ� ����4";
//						exit;
					}

				//�븸�к�
				}elseif($national == '2'){

					$rs1->set_table($_table['cost_ta']);
					$rs1->add_where("used=1");
//					echo "�븸 ����";
//					exit;

				//�߱��к�
				}elseif($national == '3'){

					$rs1->set_table($_table['cost_cn']);
					$rs1->add_where("used=1");
//					echo "�߱� ����";
//					exit;

				//��Ʈ���к�
				}elseif($national == '5'){

					$rs1->set_table($_table['cost_vn']);
					$rs1->add_where("used=1");
//					echo "��Ʈ�� ����";
//					exit;

				//�ƶ��к�
				}elseif($national == '8'){

					$rs1->set_table($_table['cost_ab']);
					$rs1->add_where("used=1");
//					echo "�ƶ� ����";
//					exit;

				}else{

					$rs1->set_table($_table['cost_ab']);
					$rs1->add_where("used=1");
//					echo "��Ÿ ����";
//					exit;

				}

				$rs1->add_where("gubun=$class_type1");
				$rs1->select();
				$data1=$rs1->fetch();

				//���ٽú�ESL���� 2012�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ��� 75�������� �����Ѵ�
				if($old_intensive_esl_fee >= $regi_date){

					if($class_type1 == 22){
						$class_cost1 = 850;
					}else{
						$class_cost1 = $data1[class_fee];
					}

				}else{

					$class_cost1 = $data1[class_fee];
//					echo "�����.";

				}

//			echo "�� �ȵ�";
//			echo $class_cost1;
//			exit;


				if($dorm_type1 == 1){
					$dorm_type1_cost = $data1[house_fee1];
				}elseif($dorm_type1 == 2){
					$dorm_type1_cost = $data1[house_fee2];
				}elseif($dorm_type1 == 3){
					$dorm_type1_cost = $data1[house_fee3];
				}elseif($dorm_type1 == 4){

					if($old_intensive_esl_fee > $regi_date){
						$dorm_type1_cost=900;
					}else{
						$dorm_type1_cost = $data1[house_fee4];
/*
echo $etc_con_pre_room_fee_down;
echo "<br>";
echo $regi_date;
echo "�����?";
exit;
*/
					}

				}elseif($dorm_type1 == 5){
					$dorm_type1_cost = $data1[house_fee5];
				}elseif($dorm_type1 == 6){
					$dorm_type1_cost = $data1[house_fee6];
				}elseif($dorm_type1 == 7){
					$dorm_type1_cost = $data1[house_fee7];
				}elseif($dorm_type1 == 8){
					$dorm_type1_cost = $data1[house_fee8];
				}elseif($dorm_type1 == 9){
					$dorm_type1_cost = $data1[house_fee9];
				}elseif($dorm_type1 == 10){
					$dorm_type1_cost = $data1[house_fee10];
				}elseif($dorm_type1 == 11){
					$dorm_type1_cost = $data1[house_fee11];
				}elseif($dorm_type1 == 12){
					$dorm_type1_cost = $data1[house_fee12];
				}elseif($dorm_type1 == 13){
					$dorm_type1_cost = $data1[house_fee13];
				}elseif($dorm_type1 == 14){
					$dorm_type1_cost = $data1[house_fee14];
				}
			}

			if($class_type2 != ''){
				$rs2 = new $rs_class($dbcon);
				$rs2->clear();

				//�Ϻ��к�
				if($national == '7'){

					$rs2->set_table($_table['cost_i']);

					//���Ͱ����� 2013�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ����� �����Ѵ�
					if($old_toeic_fee_up >= $regi_date){
						$rs2->add_where("used=1");
//						echo "�Ϻ� ����1";
//						exit;
					//�Ϻ� �к� 2015�� 1�� 1�Ϻ��� ���� �����մϴ�.
					}elseif($japan_2014_class_fee_up >= $regi_date){
						$rs2->add_where("used=2");
//						echo "�Ϻ� ����2";
//						exit;
					}elseif($korea_2017_room_fee_up >= $regi_date){
						$rs2->add_where("used=3");
//						echo "�Ϻ� ����3";
//						exit;
					}else{
						$rs2->add_where("used=4");
//						echo "�Ϻ� ����4";
//						exit;
					}

				//�븸�к�
				}elseif($national == '2'){

					$rs2->set_table($_table['cost_ta']);
					$rs2->add_where("used=1");
//					echo "�븸 ����";
//					exit;

				//�߱��к�
				}elseif($national == '3'){

					$rs2->set_table($_table['cost_cn']);
					$rs2->add_where("used=1");
//					echo "�߱� ����";
//					exit;

				//��Ʈ���к�
				}elseif($national == '5'){

					$rs2->set_table($_table['cost_vn']);
					$rs2->add_where("used=1");
//					echo "��Ʈ�� ����";
//					exit;

				//�ƶ��к�
				}elseif($national == '8'){

					$rs2->set_table($_table['cost_ab']);
					$rs2->add_where("used=1");
//					echo "�ƶ� ����";
//					exit;

				}else{

					$rs2->set_table($_table['cost_ab']);
					$rs2->add_where("used=1");
//					echo "��Ÿ ����";
//					exit;

				}

				$rs2->add_where("gubun=$class_type2");
				$rs2->select();
				$data2=$rs2->fetch();

				//���ٽú�ESL���� 2012�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ��� 75�������� �����Ѵ�
				if($old_intensive_esl_fee >= $regi_date){

					if($class_type2 == 22){
						$class_cost2 = 850;
					}else{
						$class_cost2 = $data2[class_fee];
					}

				}else{

					$class_cost2 = $data2[class_fee];

				}

				if($dorm_type2 == 1){
					$dorm_type2_cost = $data2[house_fee1];
				}elseif($dorm_type2 == 2){
					$dorm_type2_cost = $data2[house_fee2];
				}elseif($dorm_type2 == 3){
					$dorm_type2_cost = $data2[house_fee3];
				}elseif($dorm_type2 == 4){

					if($old_intensive_esl_fee > $regi_date){
						$dorm_type2_cost=900;
					}else{
						$dorm_type2_cost = $data2[house_fee4];
					}

				}elseif($dorm_type2 == 5){
					$dorm_type2_cost = $data2[house_fee5];
				}elseif($dorm_type2 == 6){
					$dorm_type2_cost = $data2[house_fee6];
				}elseif($dorm_type2 == 7){
					$dorm_type2_cost = $data2[house_fee7];
				}elseif($dorm_type2 == 8){
					$dorm_type2_cost = $data2[house_fee8];
				}elseif($dorm_type2 == 9){
					$dorm_type2_cost = $data2[house_fee9];
				}elseif($dorm_type2 == 10){
					$dorm_type2_cost = $data2[house_fee10];
				}elseif($dorm_type2 == 11){
					$dorm_type2_cost = $data2[house_fee11];
				}elseif($dorm_type2 == 12){
					$dorm_type2_cost = $data2[house_fee12];
				}elseif($dorm_type2 == 13){
					$dorm_type2_cost = $data2[house_fee13];
				}elseif($dorm_type2 == 14){
					$dorm_type2_cost = $data2[house_fee14];
				}
			}

			if($class_type3 != ''){
				$rs3 = new $rs_class($dbcon);
				$rs3->clear();

				//�Ϻ��к�
				if($national == '7'){

					$rs3->set_table($_table['cost_i']);

					//���Ͱ����� 2013�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ����� �����Ѵ�
					if($old_toeic_fee_up >= $regi_date){
						$rs3->add_where("used=1");
//						echo "�Ϻ� ����1";
//						exit;
					//�Ϻ� �к� 2015�� 1�� 1�Ϻ��� ���� �����մϴ�.
					}elseif($japan_2014_class_fee_up >= $regi_date){
						$rs3->add_where("used=2");
//						echo "�Ϻ� ����2";
//						exit;
					}elseif($korea_2017_room_fee_up >= $regi_date){
						$rs3->add_where("used=3");
//						echo "�Ϻ� ����3";
//						exit;
					}else{
						$rs3->add_where("used=4");
//						echo "�Ϻ� ����4";
//						exit;
					}

				//�븸�к�
				}elseif($national == '2'){

					$rs3->set_table($_table['cost_ta']);
					$rs3->add_where("used=1");
//					echo "�븸 ����";
//					exit;

				//�߱��к�
				}elseif($national == '3'){

					$rs3->set_table($_table['cost_cn']);
					$rs3->add_where("used=1");
//					echo "�߱� ����";
//					exit;

				//��Ʈ���к�
				}elseif($national == '5'){

					$rs3->set_table($_table['cost_vn']);
					$rs3->add_where("used=1");
//					echo "��Ʈ�� ����";
//					exit;

				//�ƶ��к�
				}elseif($national == '8'){

					$rs3->set_table($_table['cost_ab']);
					$rs3->add_where("used=1");
//					echo "�ƶ� ����";
//					exit;

				}else{

					$rs3->set_table($_table['cost_ab']);
					$rs3->add_where("used=1");
//					echo "��Ÿ ����";
//					exit;

				}

				$rs3->add_where("gubun=$class_type3");
				$rs3->select();
				$data3=$rs3->fetch();

				//���ٽú�ESL���� 2012�� 12�� 31�� ������ �ݾ��� �λ��� �ݾ��� 75�������� �����Ѵ�
				if($old_intensive_esl_fee >= $regi_date){

					if($class_type3 == 22){
						$class_cost3 = 850;
					}else{
						$class_cost3 = $data3[class_fee];
					}

				}else{

					$class_cost3 = $data3[class_fee];

				}

				if($dorm_type3 == 1){
					$dorm_type3_cost = $data3[house_fee1];
				}elseif($dorm_type3 == 2){
					$dorm_type3_cost = $data3[house_fee2];
				}elseif($dorm_type3 == 3){
					$dorm_type3_cost = $data3[house_fee3];
				}elseif($dorm_type3 == 4){

					if($old_intensive_esl_fee > $regi_date){
						$dorm_type3_cost=900;
					}else{
						$dorm_type3_cost = $data3[house_fee4];
					}

				}elseif($dorm_type3 == 5){
					$dorm_type3_cost = $data3[house_fee5];
				}elseif($dorm_type3 == 6){
					$dorm_type3_cost = $data3[house_fee6];
				}elseif($dorm_type3 == 7){
					$dorm_type3_cost = $data3[house_fee7];
				}elseif($dorm_type3 == 8){
					$dorm_type3_cost = $data3[house_fee8];
				}elseif($dorm_type3 == 9){
					$dorm_type3_cost = $data3[house_fee9];
				}elseif($dorm_type3 == 10){
					$dorm_type3_cost = $data3[house_fee10];
				}elseif($dorm_type3 == 11){
					$dorm_type3_cost = $data3[house_fee11];
				}elseif($dorm_type3 == 12){
					$dorm_type3_cost = $data3[house_fee12];
				}elseif($dorm_type3 == 13){
					$dorm_type3_cost = $data3[house_fee13];
				}elseif($dorm_type3 == 14){
					$dorm_type3_cost = $data3[house_fee14];
				}
			}

			//�ڡڡڡڡڡڼ������ �νǿ� ���� ������ ���ڡڡڡڡڡ�
			if($agency_level == 6 || $agency_level == 7 || $agency_level == 8 || $agency_level == 9 || $agency_level == 10 || $agency_level == 11 || $agency_level == 12 || $agency_level == 13 || $agency_level == 14 || $agency_level == 15 || $agency_level == 16){
				$comm_regi_cost=$comm_regi_cost;
			}else{
				$comm_regi_cost=0;
			}

			$rs_comm = new $rs_class($dbcon);
			$rs_comm->clear();
			$rs_comm->set_table($_table['comm_i']);
			$rs_comm->add_where("used=1");
			$rs_comm->select();
			$data_comm=$rs_comm->fetch();

			if($agency_level == 6){
				$ag_comm_fee = '0.'.$data_comm[agency_comm1] ;
			}elseif($agency_level == 7){
				$ag_comm_fee = '0.'.$data_comm[agency_comm2] ;
			}elseif($agency_level == 8){
				$ag_comm_fee = '0.'.$data_comm[agency_comm3] ;
			}elseif($agency_level == 9){
				$ag_comm_fee = '0.'.$data_comm[agency_comm4] ;
			}elseif($agency_level == 10){
				$ag_comm_fee = '0.'.$data_comm[agency_comm5] ;
			}elseif($agency_level == 11){
				$ag_comm_fee = '0.'.$data_comm[agency_comm6] ;
			}elseif($agency_level == 12){
				$ag_comm_fee = '0.'.$data_comm[agency_comm7] ;
			}elseif($agency_level == 13){
				$ag_comm_fee = '0.'.$data_comm[agency_comm8] ;
			}elseif($agency_level == 14){
				$ag_comm_fee = '0.'.$data_comm[agency_comm9] ;
			}elseif($agency_level == 15){
				$ag_comm_fee = '0.'.$data_comm[agency_comm10] ;
			}elseif($agency_level == 16){
				$ag_comm_fee = '0.'.$data_comm[agency_comm11] ;
			}

//			echo "������������?";
//			echo $ag_comm_fee;
//			exit;


			if($class_gigan == 1){

/*
	echo $class_cost1;
	echo "<br>";

	echo $dorm_type1_cost;
	echo "<br>";

	echo $event_sale;
	echo "<br>";

	echo $addition_fee;
	echo "<br>";

	echo $ag_comm_fee;
	echo "<br>";
20130812 >= 20140814
	exit;
*/

				if($japan_123week_fee_down >= $regi_date){

					$agency_comm1 = (((($class_cost1 + $dorm_type1_cost) * 0.5)-$event_sale+$addition_fee) * $ag_comm_fee);
					$agency_comm2 = ((($class_cost2 + $dorm_type2_cost) * 0.5) * $ag_comm_fee);
					$agency_comm3 = ((($class_cost3 + $dorm_type3_cost) * 0.5) * $ag_comm_fee);
					$class_cost1 = ($class_cost1) * 0.5;
					$dorm_cost1 = ($dorm_type1_cost) * 0.5;
					$class_cost2 = ($class_cost2) * 0.5;
					$dorm_cost2 = ($dorm_type2_cost) * 0.5;
					$class_cost3 = ($class_cost3) * 0.5;
					$dorm_cost3 = ($dorm_type3_cost) * 0.5;

				}else{

					//����� ���������� �к� %�� �ƴ� 1/4, 2/4, 3/4 ������� ��� �ȴ�20160225
					if($state == 4 Or $state== 5 ){

						$class_cost1 = $class_gigan1 * ($class_cost1/4);
						$dorm_cost1 = $class_gigan1 * ($dorm_type1_cost/4);
						$class_cost2 = $class_gigan2 * ($class_cost2/4);
						$dorm_cost2 = $class_gigan2 * ($dorm_type2_cost/4);
						$class_cost3 = $class_gigan3 * ($class_cost3/4);
						$dorm_cost3 = $class_gigan3 * ($dorm_type3_cost/4);

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
							$agency_comm1 = (((($class_cost1 + ($dorm_cost1-100)))-$event_sale+$addition_fee) * $ag_comm_fee);
						}else{
							$agency_comm1 = (((($class_cost1 + $dorm_cost1))-$event_sale+$addition_fee) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type2 != '0'){
							$agency_comm2 = ((($class_cost2 + ($dorm_cost2-100))) * $ag_comm_fee);
						}else{
							$agency_comm2 = ((($class_cost2 + $dorm_cost2)) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type3 != '0'){
							$agency_comm3 = ((($class_cost3 + ($dorm_cost3-100))) * $ag_comm_fee);
						}else{
							$agency_comm3 = ((($class_cost3 + $dorm_cost3)) * $ag_comm_fee);
						}

//echo "�������?";
//exit;

					}else{

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
							$agency_comm1 = (((($class_cost1 + ($dorm_type1_cost-100)) * 0.4)-$event_sale+$addition_fee) * $ag_comm_fee);
						}else{
							$agency_comm1 = (((($class_cost1 + $dorm_type1_cost) * 0.4)-$event_sale+$addition_fee) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type2 != '0'){
							$agency_comm2 = ((($class_cost2 + ($dorm_type2_cost-100)) * 0.4) * $ag_comm_fee);
						}else{
							$agency_comm2 = ((($class_cost2 + $dorm_type2_cost) * 0.4) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type3 != '0'){
							$agency_comm3 = ((($class_cost3 + ($dorm_type3_cost-100)) * 0.4) * $ag_comm_fee);
						}else{
							$agency_comm3 = ((($class_cost3 + $dorm_type3_cost) * 0.4) * $ag_comm_fee);
						}

						$class_cost1 = ($class_cost1) * 0.4;
						$dorm_cost1 = ($dorm_type1_cost) * 0.4;
						$class_cost2 = ($class_cost2) * 0.4;
						$dorm_cost2 = ($dorm_type2_cost) * 0.4;
						$class_cost3 = ($class_cost3) * 0.4;
						$dorm_cost3 = ($dorm_type3_cost) * 0.4;

/*
						$class_cost1 = (($class_gigan1 * ($class_cost1/4)) * 0.4);
						$dorm_cost1 = (($class_gigan1 * ($dorm_type1_cost/4)) * 0.4);

						$class_cost2 = (($class_gigan2 * ($class_cost2/4)) * 0.4);
						$dorm_cost2 = (($class_gigan2 * ($dorm_type2_cost/4)) * 0.4);

						$class_cost3 = (($class_gigan3 * ($class_cost3/4)) * 0.4);
						$dorm_cost3 = (($class_gigan3 * ($dorm_type3_cost/4)) * 0.4);

						$agency_comm1 = ((($class_cost1+$dorm_cost1)-$event_sale+$addition_fee) * $ag_comm_fee);
						$agency_comm2 = (($class_cost2+$dorm_cost2) * $ag_comm_fee);
						$agency_comm3 = (($class_cost3+$dorm_cost3) * $ag_comm_fee);
*/

//echo "�������";
//exit;

					}

				}

			}elseif($class_gigan == 2){

				if($japan_123week_fee_down >= $regi_date){

					$agency_comm1 = (((($class_cost1 + $dorm_type1_cost) * 0.7)-$event_sale+$addition_fee) * $ag_comm_fee);
					$agency_comm2 = ((($class_cost2 + $dorm_type2_cost) * 0.7) * $ag_comm_fee);
					$agency_comm3 = ((($class_cost3 + $dorm_type3_cost) * 0.7) * $ag_comm_fee);

					$class_cost1 = ($class_cost1) * 0.7;
					$dorm_cost1 = ($dorm_type1_cost) * 0.7;
					$class_cost2 = ($class_cost2) * 0.7;
					$dorm_cost2 = ($dorm_type2_cost) * 0.7;
					$class_cost3 = ($class_cost3) * 0.7;
					$dorm_cost3 = ($dorm_type3_cost) * 0.7;

				}else{

					//����� ���������� �к� %�� �ƴ� 1/4, 2/4, 3/4 ������� ��� �ȴ�20160225
					if($state == 4 Or $state== 5 ){

						$class_cost1 = $class_gigan1 * ($class_cost1/4);
						$dorm_cost1 = $class_gigan1 * ($dorm_type1_cost/4);
						$class_cost2 = $class_gigan2 * ($class_cost2/4);
						$dorm_cost2 = $class_gigan2 * ($dorm_type2_cost/4);
						$class_cost3 = $class_gigan3 * ($class_cost3/4);
						$dorm_cost3 = $class_gigan3 * ($dorm_type3_cost/4);

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
							$agency_comm1 = (((($class_cost1 + ($dorm_cost1-100)))-$event_sale+$addition_fee) * $ag_comm_fee);
						}else{
							$agency_comm1 = (((($class_cost1 + $dorm_cost1))-$event_sale+$addition_fee) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type2 != '0'){
							$agency_comm2 = ((($class_cost2 + ($dorm_cost2-100))) * $ag_comm_fee);
						}else{
							$agency_comm2 = ((($class_cost2 + $dorm_cost2)) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type3 != '0'){
							$agency_comm3 = ((($class_cost3 + ($dorm_cost3-100))) * $ag_comm_fee);
						}else{
							$agency_comm3 = ((($class_cost3 + $dorm_cost3)) * $ag_comm_fee);
						}

/*
	echo $class_cost1;
	echo "<br>";

	echo $dorm_cost1;
	echo "<br>";

	echo $class_cost2;
	echo "<br>";

	echo $dorm_cost2;
	echo "<br>";

	exit;
*/

//echo "�������?";
//exit;

					}else{

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
							$agency_comm1 = (((($class_cost1 + ($dorm_type1_cost-100)) * 0.6)-$event_sale+$addition_fee) * $ag_comm_fee);
						}else{
							$agency_comm1 = (((($class_cost1 + $dorm_type1_cost) * 0.6)-$event_sale+$addition_fee) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type2 != '0'){
							$agency_comm2 = ((($class_cost2 + ($dorm_type2_cost-100)) * 0.6) * $ag_comm_fee);
						}else{
							$agency_comm2 = ((($class_cost2 + $dorm_type2_cost) * 0.6) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type3 != '0'){
							$agency_comm3 = ((($class_cost3 + ($dorm_type3_cost-100)) * 0.6) * $ag_comm_fee);
						}else{
							$agency_comm3 = ((($class_cost3 + $dorm_type3_cost) * 0.6) * $ag_comm_fee);
						}

						$class_cost1 = ($class_cost1) * 0.6;
						$dorm_cost1 = ($dorm_type1_cost) * 0.6;
						$class_cost2 = ($class_cost2) * 0.6;
						$dorm_cost2 = ($dorm_type2_cost) * 0.6;
						$class_cost3 = ($class_cost3) * 0.6;
						$dorm_cost3 = ($dorm_type3_cost) * 0.6;

/*
						$class_cost1 = (($class_gigan1 * ($class_cost1/4)) * 0.6);
						$dorm_cost1 = (($class_gigan1 * ($dorm_type1_cost/4)) * 0.6);

						$class_cost2 = (($class_gigan2 * ($class_cost2/4)) * 0.6);
						$dorm_cost2 = (($class_gigan2 * ($dorm_type2_cost/4)) * 0.6);

						$class_cost3 = (($class_gigan3 * ($class_cost3/4)) * 0.6);
						$dorm_cost3 = (($class_gigan3 * ($dorm_type3_cost/4)) * 0.6);

						$agency_comm1 = ((($class_cost1+$dorm_cost1)-$event_sale+$addition_fee) * $ag_comm_fee);
						$agency_comm2 = (($class_cost2+$dorm_cost2) * $ag_comm_fee);
						$agency_comm3 = (($class_cost3+$dorm_cost3) * $ag_comm_fee);
*/

//echo "�������";
//exit;
					}

				}

			}elseif($class_gigan == 3){


				if($japan_123week_fee_down >= $regi_date){

					$agency_comm1 = (((($class_cost1 + $dorm_type1_cost) * 0.9)-$event_sale+$addition_fee) * $ag_comm_fee);
					$agency_comm2 = ((($class_cost2 + $dorm_type2_cost) * 0.9) * $ag_comm_fee);
					$agency_comm3 = ((($class_cost3 + $dorm_type3_cost) * 0.9) * $ag_comm_fee);
					$class_cost1 = ($class_cost1) * 0.9;
					$dorm_cost1 = ($dorm_type1_cost) * 0.9;
					$class_cost2 = ($class_cost2) * 0.9;
					$dorm_cost2 = ($dorm_type2_cost) * 0.9;
					$class_cost3 = ($class_cost3) * 0.9;
					$dorm_cost3 = ($dorm_type3_cost) * 0.9;

				}else{

					//����� ���������� �к� %�� �ƴ� 1/4, 2/4, 3/4 ������� ��� �ȴ�20160225
					if($state == 4 Or $state== 5 ){

						$class_cost1 = $class_gigan1 * ($class_cost1/4);
						$dorm_cost1 = $class_gigan1 * ($dorm_type1_cost/4);
						$class_cost2 = $class_gigan2 * ($class_cost2/4);
						$dorm_cost2 = $class_gigan2 * ($dorm_type2_cost/4);
						$class_cost3 = $class_gigan3 * ($class_cost3/4);
						$dorm_cost3 = $class_gigan3 * ($dorm_type3_cost/4);

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
							$agency_comm1 = (((($class_cost1 + ($dorm_cost1-100)))-$event_sale+$addition_fee) * $ag_comm_fee);
						}else{
							$agency_comm1 = (((($class_cost1 + $dorm_cost1))-$event_sale+$addition_fee) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type2 != '0'){
							$agency_comm2 = ((($class_cost2 + ($dorm_cost2-100))) * $ag_comm_fee);
						}else{
							$agency_comm2 = ((($class_cost2 + $dorm_cost2)) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type3 != '0'){
							$agency_comm3 = ((($class_cost3 + ($dorm_cost3-100))) * $ag_comm_fee);
						}else{
							$agency_comm3 = ((($class_cost3 + $dorm_cost3)) * $ag_comm_fee);
						}

//echo "�������?";
//exit;

					}else{

/*
	echo "class_cost1 : ";
	echo $class_cost1;
	echo "<br>";
*/

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
							$agency_comm1 = (((($class_cost1 + ($dorm_type1_cost-100)) * 0.8)-$event_sale+$addition_fee) * $ag_comm_fee);
						}else{
							$agency_comm1 = (((($class_cost1 + $dorm_type1_cost) * 0.8)-$event_sale+$addition_fee) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type2 != '0'){$
							$agency_comm2 = ((($class_cost2 + ($dorm_type2_cost-100)) * 0.8) * $ag_comm_fee);
						}else{
							$agency_comm2 = ((($class_cost2 + $dorm_type2_cost) * 0.8) * $ag_comm_fee);
						}

						if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type3 != '0'){
							$agency_comm3 = ((($class_cost3 + ($dorm_type3_cost-100)) * 0.8) * $ag_comm_fee);
						}else{
							$agency_comm3 = ((($class_cost3 + $dorm_type3_cost) * 0.8) * $ag_comm_fee);
						}

						$class_cost1 = ($class_cost1) * 0.8;
						$dorm_cost1 = ($dorm_type1_cost) * 0.8;
						$class_cost2 = ($class_cost2) * 0.8;
						$dorm_cost2 = ($dorm_type2_cost) * 0.8;
						$class_cost3 = ($class_cost3) * 0.8;
						$dorm_cost3 = ($dorm_type3_cost) * 0.8;

/*
	echo "class_cost1 : ";
	echo $class_cost1;
	echo "<br>";

	echo "dorm_cost1 : ";
	echo $dorm_cost1;
	echo "<br>";

	echo "class_cost2 : ";
	echo $class_cost2;
	echo "<br>";

	echo "dorm_cost2 : ";
	echo $dorm_cost2;
	echo "<br>";

	echo "class_cost3 : ";
	echo $class_cost3;
	echo "<br>";

	echo "dorm_cost3 : ";
	echo $dorm_cost3;
	echo "<br>";

	echo "dorm_type1_cost : ";
	echo $dorm_type1_cost;
	echo "<br>";

	echo "dorm_type2_cost : ";
	echo $dorm_type2_cost;
	echo "<br>";

	echo "event_sale : ";
	echo $event_sale;
	echo "<br>";

	echo "addition_fee :";
	echo $addition_fee;
	echo "<br>";

	echo "ag_comm_fee : ";
	echo $ag_comm_fee;
	echo "<br>";

	echo "agency_comm1 : ";
	echo $agency_comm1;
	echo "<br>";

	echo "agency_comm2 : ";
	echo $agency_comm2;
	echo "<br>";

	echo "agency_comm3 : ";
	echo $agency_comm3;
	echo "<br>";


	exit;
*/
					}

				}

			}else{

				//�׼ֶ����� ����ҽ�20140227
				if($class_type1 == 27 And $class_gigan1 == 8){
					$agency_comm1 = ((((($class_cost1 / 4) * $class_gigan1)-500) + ($dorm_type1_cost / 4) * $class_gigan1)-$event_sale+$addition_fee) * $ag_comm_fee;
				}else{
					if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
						$agency_comm1 = (((($class_cost1 + ($dorm_type1_cost-100)) / 4) * $class_gigan1)-$event_sale+$addition_fee) * $ag_comm_fee;
					}else{
						$agency_comm1 = (((($class_cost1 + $dorm_type1_cost) / 4) * $class_gigan1)-$event_sale+$addition_fee) * $ag_comm_fee;
					}
				}

				if($class_type2 == 27 And $class_gigan2 == 8){
					$agency_comm2 = ((((($class_cost2 / 4) * $class_gigan2)-500) + ($dorm_type2_cost / 4) * $class_gigan2)) * $ag_comm_fee;
				}else{
					if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
						$agency_comm2 = (($class_cost2 + ($dorm_type2_cost-100)) / 4) * $class_gigan2 * $ag_comm_fee;
					}else{
						$agency_comm2 = (($class_cost2 + $dorm_type2_cost) / 4) * $class_gigan2 * $ag_comm_fee;
					}
				}

				if($class_type3 == 27 And $class_gigan3 == 8){
					$agency_comm3 = ((((($class_cost3 / 4) * $class_gigan3)-500) + ($dorm_type3_cost / 4) * $class_gigan3)) * $ag_comm_fee;
				}else{
					if($national == '7' And ($dorm_type1 == '4' Or $dorm_type1 == '5' Or $dorm_type1 == '10' Or $dorm_type1 == '13') And $korea_2017_room_fee_up <= $regi_date And $class_type1 != '0'){
						$agency_comm3 = (($class_cost3 + ($dorm_type3_cost-100)) / 4) * $class_gigan3 * $ag_comm_fee;
					}else{
						$agency_comm3 = (($class_cost3 + $dorm_type3_cost) / 4) * $class_gigan3 * $ag_comm_fee;
					}
				}

				//�׼ֶ����� ����ҽ�20140227
				if($class_type1 == 27 And $class_gigan1 == 8){
					$class_cost1 = (($class_cost1 / 4) * $class_gigan1)-500;
				}else{
					$class_cost1 = ($class_cost1 / 4) * $class_gigan1;
				}

				$dorm_cost1 = ($dorm_type1_cost / 4) * $class_gigan1;

				if($class_type2 == 27 And $class_gigan2 == 8){
					$class_cost2 = (($class_cost2 / 4) * $class_gigan2)-500;
				}else{
					$class_cost2 = ($class_cost2 / 4) * $class_gigan2;
				}

				$dorm_cost2 = ($dorm_type2_cost / 4) * $class_gigan2;

				if($class_type3 == 27 And $class_gigan3 == 8){
					$class_cost3 = (($class_cost3 / 4) * $class_gigan3)-500;
				}else{
					$class_cost3 = ($class_cost3 / 4) * $class_gigan3;
				}

				$dorm_cost3 = ($dorm_type3_cost / 4) * $class_gigan3;


/*
	echo "class_cost1 : ";
	echo $class_cost1;
	echo "<br>";

	echo "dorm_cost1 : ";
	echo $dorm_cost1;
	echo "<br>";

	echo "class_cost2 : ";
	echo $class_cost2;
	echo "<br>";

	echo "dorm_cost2 : ";
	echo $dorm_cost2;
	echo "<br>";

	echo "class_cost3 : ";
	echo $class_cost3;
	echo "<br>";

	echo "dorm_cost3 : ";
	echo $dorm_cost3;
	echo "<br>";

	echo "dorm_type1_cost : ";
	echo $dorm_type1_cost;
	echo "<br>";

	echo "dorm_type2_cost : ";
	echo $dorm_type2_cost;
	echo "<br>";

	echo "event_sale : ";
	echo $event_sale;
	echo "<br>";

	echo "addition_fee :";
	echo $addition_fee;
	echo "<br>";

	echo "ag_comm_fee : ";
	echo $ag_comm_fee;
	echo "<br>";

	echo "agency_comm1 : ";
	echo $agency_comm1;
	echo "<br>";

	echo "agency_comm2 : ";
	echo $agency_comm2;
	echo "<br>";

	echo "agency_comm3 : ";
	echo $agency_comm3;
	echo "<br>";


	exit;
*/



			}

			$agency_comm = $agency_comm1 + $agency_comm2 + $agency_comm3;
			$class_cost = $class_cost1 + $class_cost2 + $class_cost3;
			$dorm_cost = $dorm_cost1 + $dorm_cost2 + $dorm_cost3;

			//�׽�Ʈ
//			$agency_comm = $ag_comm_fee * $class_gigan ;

//			if($class_gigan == 1){
//				$agency_comm = ($class_cost + $dorm_cost) * 0.5 * $ag_comm_fee ;
//			}elseif($class_gigan == 2){
//				$agency_comm = ($class_cost + $dorm_cost) * $ag_comm_fee ;
//			}elseif($class_gigan == 3){
//				$agency_comm = ($class_cost + $dorm_cost) * $ag_comm_fee ;
//			}else{
//				$agency_comm = ($class_cost + $dorm_cost) * $ag_comm_fee ;
//			}

			//��ݺ��泯¥ 2010.10.18
			if($cost_st_date <= $regi_date){

				if($elect_st_date <= $regi_date){
					$total_cost=$regi_cost+$class_cost+$dorm_cost-$event_sale+$addition_fee;		
				}else{
					$total_cost=$regi_cost+$class_cost+$dorm_cost+$elect_fee-$event_sale+$addition_fee;  
				}

			}else{

				$total_cost=$regi_cost+$class_cost+$dorm_cost+$ssp_cost+$elect_fee-$event_sale+$addition_fee;

			}

//			exit();

			$agency_cost= $total_cost-$comm_regi_cost-$agency_comm-$ev_ag_comm+$ag_add_fee;

			//�ڡڡڡڡڡڼ������ �νǿ� ���� ������ ���ڡڡڡڡڡ�
		}

		//20130716 ȯ������̸� ������ �������� -�Ⱓ�� ���ش�.
		if($state=='7'){

			//ȯ�ҳ�¥ �ڵ����� �Է��ϱ� ���� ������� �ҽ��̴�.
			if($auto_date=='1'){
			
				$end_date_0=explode("-",$end_date);
				$end_date1 = $end_date_0[0];
				$end_date2 = $end_date_0[1];
				$end_date3 = $end_date_0[2];   

				$end_date_str = mktime(0,0,0,$end_date2,$end_date3,$end_date1);

				$end_date = $end_date ;

				$abrod_date1 = date("Y", mktime(0,0,0,$end_date2,$end_date3-($class_gigan*7-1),$end_date1));		
				$abrod_date2 = date("m", mktime(0,0,0,$end_date2,$end_date3-($class_gigan*7-1),$end_date1));		
				$abrod_date3 = date("d", mktime(0,0,0,$end_date2,$end_date3-($class_gigan*7-1),$end_date1));			 
				$abrod_date = $abrod_date1."-".$abrod_date2."-".$abrod_date3;

				$abrod_date_str = mktime(0,0,0,$abrod_date2,$abrod_date3,$abrod_date1);

			}else{

				if($abrod_date){
					$abrod_date_0=explode("-",$abrod_date);
					$abrod_date1 = $abrod_date_0[0];
					$abrod_date2 = $abrod_date_0[1];
					$abrod_date3 = $abrod_date_0[2];   

					$abrod_date_str = mktime(0,0,0,$abrod_date2,$abrod_date3,$abrod_date1);
				}

				if($end_date){
					$end_date_0=explode("-",$end_date);
					$end_date1 = $end_date_0[0];
					$end_date2 = $end_date_0[1];
					$end_date3 = $end_date_0[2];

					$end_date_str = mktime(0,0,0,$end_date2,$end_date3,$end_date1);

					$end_date = $end_date ;
				}else{
					$end_date1 = date("Y", mktime(0,0,0,$abrod_date2,$abrod_date3+($class_gigan*7-1),$abrod_date1));		
					$end_date2 = date("m", mktime(0,0,0,$abrod_date2,$abrod_date3+($class_gigan*7-1),$abrod_date1));		
					$end_date3 = date("d", mktime(0,0,0,$abrod_date2,$abrod_date3+($class_gigan*7-1),$abrod_date1));			 
					$end_date = $end_date1."-".$end_date2."-".$end_date3;
				}

			}

		}else{

			if($abrod_date){
				$abrod_date_0=explode("-",$abrod_date);
				$abrod_date1 = $abrod_date_0[0];
				$abrod_date2 = $abrod_date_0[1];
				$abrod_date3 = $abrod_date_0[2];   

				$abrod_date_str = mktime(0,0,0,$abrod_date2,$abrod_date3,$abrod_date1);

			if($end_date){
				$end_date_0=explode("-",$end_date);
				$end_date1 = $end_date_0[0];
				$end_date2 = $end_date_0[1];
				$end_date3 = $end_date_0[2];

				$end_date_str = mktime(0,0,0,$end_date2,$end_date3,$end_date1);

				$end_date = $end_date ;
			}else{
				$end_date1 = date("Y", mktime(0,0,0,$abrod_date2,$abrod_date3+($class_gigan*7-1),$abrod_date1));		
				$end_date2 = date("m", mktime(0,0,0,$abrod_date2,$abrod_date3+($class_gigan*7-1),$abrod_date1));		
				$end_date3 = date("d", mktime(0,0,0,$abrod_date2,$abrod_date3+($class_gigan*7-1),$abrod_date1));			 
				$end_date = $end_date1."-".$end_date2."-".$end_date3;
			}
		}

	}

//		if($state == 3){
//			$paid_cost = $agency_cost;
//		}


		$rs->clear();
		$rs->set_table($_table['regi']);
		$rs->add_field("check","$check");
//		$rs->add_field("check","0");
		$rs->add_field("check_memo","$check_memo");	
		$rs->add_field("st_id","$st_id");	
		$rs->add_field("agency_id","$agency_id");
		$rs->add_field("sname","$sname");	
		$rs->add_field("sename","$sename");	
		$rs->add_field("jumin31","$jumin31");	
		$rs->add_field("jumin32","$jumin32");	
		$rs->add_field("semail","$semail");	
		$rs->add_field("stel","$stel");	
		$rs->add_field("stel2","$stel2");	
		$rs->add_field("stel3","$stel3");	
		$rs->add_field("shtel","$shtel");	
		$rs->add_field("shtel2","$shtel2");	
		$rs->add_field("shtel3","$shtel3");	
		$rs->add_field("mb_post1","$mb_post1");	
		$rs->add_field("mb_post2","$mb_post2");
		$rs->add_field("mb_post_new","$mb_post_new");
		$rs->add_field("mb_address1","$mb_address1");	
		$rs->add_field("mb_address2","$mb_address2");
		$rs->add_field("mb_address3","$mb_address3");
		$rs->add_field("job","$job");	
		$rs->add_field("insu","$insu");
		$rs->add_field("class_type1","$class_type1");
		$rs->add_field("class_type2","$class_type2");
		$rs->add_field("class_type3","$class_type3");
		$rs->add_field("class_gigan","$class_gigan");
		$rs->add_field("class_gigan1","$class_gigan1");		
		$rs->add_field("class_gigan2","$class_gigan2");	
		$rs->add_field("class_gigan3","$class_gigan3");	
		$rs->add_field("dorm_type1","$dorm_type1");
		$rs->add_field("dorm_type2","$dorm_type2");	
		$rs->add_field("dorm_type3","$dorm_type3");	
		$rs->add_field("dorm_gigan1","$dorm_gigan1");		
		$rs->add_field("dorm_gigan2","$dorm_gigan2");
		$rs->add_field("dorm_gigan3","$dorm_gigan3");
		$rs->add_field("abrod_date","$abrod_date");				
		$rs->add_field("abrod_date1","$abrod_date1");
		$rs->add_field("abrod_date2","$abrod_date2");	
		$rs->add_field("abrod_date3","$abrod_date3");	
		$rs->add_field("end_date","$end_date");	
		$rs->add_field("end_date1","$end_date1");	
		$rs->add_field("end_date2","$end_date2");	
		$rs->add_field("end_date3","$end_date3");
		$rs->add_field("passport","$passport");	
		$rs->add_field("airplane","$airplane");	
		$rs->add_field("air_type","$air_type");	
		$rs->add_field("air_text_input","$air_text_input");	
		$rs->add_field("starthh","$starthh");
		$rs->add_field("startmm","$startmm");	
		$rs->add_field("endhh","$endhh");
		$rs->add_field("endmm","$endmm");	
		$rs->add_field("xname","$xname");	
		$rs->add_field("xrelation","$xrelation");	
		$rs->add_field("xtel","$xtel");	
		$rs->add_field("xhp","$xhp");	
		$rs->add_field("consult","$consult");	
		$rs->add_field("fax","$fax");	
		$rs->add_field("memo","$memo");	
		$rs->add_field("class_cost","$class_cost");	
		$rs->add_field("dorm_cost","$dorm_cost");
		$rs->add_field("ssp_cost","$ssp_cost");			
		$rs->add_field("elect_fee","$elect_fee");	
		$rs->add_field("event_sale_name","$event_sale_name");	
		$rs->add_field("event_sale","$event_sale");	
		$rs->add_field("addition_fee_name","$addition_fee_name");	
		$rs->add_field("addition_fee","$addition_fee");	
		$rs->add_field("agency_comm","$agency_comm");
		$rs->add_field("ev_ag_co_name","$ev_ag_co_name");	
		$rs->add_field("ev_ag_comm","$ev_ag_comm");	

		$rs->add_field("ag_add_fee_name","$ag_add_fee_name");	
		$rs->add_field("ag_add_fee","$ag_add_fee");	

		$rs->add_field("total_cost","$total_cost");	
		$rs->add_field("agency_cost","$agency_cost");	

		$rs->add_field("agency_level","$agency_level");	
		$rs->add_field("state","$state");	
		$rs->add_field("ip_vb","$ip_vb");	
		$rs->add_field("pick_up","$pick_up");	

		$rs->add_field("national","$national");

		$rs->add_field("chain","$chain");
		$rs->add_field("part","$part");	
		$rs->add_field("cname","$cname");	
		$rs->add_field("tel","$chain_tel");	

		$rs->add_field("last_register","$_mb[mb_name]");	
		$rs->add_field("last_regi_date",time());
//		$rs->add_field("international_money","$international_money");

		$rs->add_field("speech_content","$speech_content");

		$rs->add_field("nick","$nick");	
		$rs->add_field("campus","$campus");	

		$rs->add_field("abrod_date_str","$abrod_date_str");
		$rs->add_field("end_date_str","$end_date_str");

		$rs->add_field("student_id","$student_id");

		$rs->add_field("pickup_yes_no","$pickup_yes_no");
		$rs->add_field("student_memo","$student_memo");


		$rs->add_field("certificate1","$certificate1");
		$rs->add_field("certificate1_view","$certificate1_view");
		$rs->add_field("certificate2","$certificate2");
		$rs->add_field("certificate2_view","$certificate2_view");
		$rs->add_field("certificate3","$certificate3");
		$rs->add_field("certificate3_view","$certificate3_view");
		$rs->add_field("certificate4","$certificate4");
		$rs->add_field("certificate4_view","$certificate4_view");
		$rs->add_field("certificate5","$certificate5");
		$rs->add_field("certificate5_view","$certificate5_view");
		$rs->add_field("certificate6","$certificate6");
		$rs->add_field("certificate6_view","$certificate6_view");
		$rs->add_field("certificate7","$certificate7");
		$rs->add_field("certificate7_view","$certificate7_view");

		$rs->add_field("refund_percent","$refund_percent");

		$rs->add_field("confirm_check","$confirm_check");

		$rs->add_field("admission_state","$admission_state");
		$rs->add_field("admission_fee","$admission_fee");
		$rs->add_field("school_expenses","$school_expenses");
		$rs->add_field("etc_expenses","$etc_expenses");
		$rs->add_field("admission_memo","$admission_memo");

		$rs->add_field("deposit_account","$deposit_account");
		$rs->add_field("bankin_date","$bankin_date");
		$rs->add_field("bankin_name","$bankin_name");
		$rs->add_field("bankin_expenses","$bankin_expenses");
		$rs->add_field("in_money","$in_money");
		$rs->add_field("deposit_memo","$deposit_memo");

		//��ũ�� Ŀ��ŧ�� �߰�
		$rs->add_field("class_type1_1","$class_type1_1");
		$rs->add_field("class_type2_1","$class_type2_1");
		$rs->add_field("class_type3_1","$class_type3_1");
		$rs->add_field("class_gigan1_1","$class_gigan1_1");
		$rs->add_field("class_gigan2_1","$class_gigan2_1");
		$rs->add_field("class_gigan3_1","$class_gigan3_1");
		$rs->add_field("dorm_type1_1","$dorm_type1_1");
		$rs->add_field("dorm_type2_1","$dorm_type2_1");
		$rs->add_field("dorm_type3_1","$dorm_type3_1");
		$rs->add_field("dorm_gigan1_1","$dorm_gigan1_1");
		$rs->add_field("dorm_gigan2_1","$dorm_gigan2_1");
		$rs->add_field("dorm_gigan3_1","$dorm_gigan3_1");
		//��ũ�� Ŀ��ŧ�� �߰�

		//�Ա����� �߰�
		$rs->add_field("bankout_expenses","$bankout_expenses");
		$rs->add_field("out_money","$out_money");
		$rs->add_field("bankout_date","$bankout_date");
		$rs->add_field("bankout_name","$bankout_name");
		$rs->add_field("bankout_memo","$bankout_memo");
		//�Ա����� �߰�

		$rs->add_field("regi_cost_name","$regi_cost_name");

		$rs->add_field("comm_regi_cost","$comm_regi_cost");

		$rs->add_field("check_out_date","$check_out_date");

		//������� ó�� ����ϱ�
		if($state=='4') {

			$rs->add_field("regi_cost","$regi_cost");
			$rs->add_field("extension_yes_no","$extension_yes_no");

			$rs->add_where("num=$num");
			$rs->update();

		//ȯ����Ұ��� ����ϱ�
		} elseif($state=='7') {

			$rs->add_field("regi_cost","$regi_cost");
			$rs->add_field("refund_yes_no","$refund_yes_no");

			$rs->add_where("num=$num");
			$rs->update();

		//�Ϲ� ��������ϱ�
		} elseif($mode=='modify') {

			if($state=='5') {
				$rs->add_field("regi_cost","$regi_cost");
			}else{
				//������ ��Ϻ� 0�� ����� //20160212
				if($num == '11929' Or $num == '11930' Or $num == '14232' Or $num == '14299'){
					$rs->add_field("regi_cost","0");
				}else{
					$rs->add_field("regi_cost","$regi_cost");
				}
			}

			$rs->add_where("num=$num");
			$rs->update();
		} else {
			$rs->insert();
		}

		$rs->commit();


	if($_mb[mb_level] < 9){

		$rs->clear();
		$rs->set_table($_table['member']);
		$rs->add_field("mb_level","$agency_level");	
		$rs->add_where("mb_num=$mb_num");
		$rs->update();

	}
		rg_href("regi_list.php?$_get_param[3]"."&regi1=".$regi1."&regi2=".$regi2."&abrod_date_str1=".$abrod_date_str1."&abrod_date_str2=".$abrod_date_str2."&arrival_date_str1=".$arrival_date_str1."&arrival_date_str2=".$arrival_date_str2."&search_student_dateT=".$search_student_dateT."&stradmission_state=".$stradmission_state."&agency_register=".$agency_register."&strconfirm_check=".$strconfirm_check);

//		rg_href("regi_edit_sparta.php?page=1&mode=modify&num=4097");

	}
	$MENU_L='m4';

   $curri_new = mktime(0,0,0,11,11,2010);
//	if($curri_new > $data[regi_date]){
//		$class_type=$_regi['class_type_s'];
//	}else{
//		$class_type=$_regi['class_type_s_new'];
//	}

	$class_type = $_regi['campus_sparta_budget'];
?>
<? include("_header.php"); ?>
<? include("admin.header_new.php"); ?>

<link href="<?=$_url['css']?>redmond/jquery-ui-1.8.20.custom.css" rel="stylesheet" type="text/css">

<script>

$(document).ready(function()
{	
	$("#startDate").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#endDate").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#bankin_date").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#bankout_date").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#check_out_date").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
	$("#speech_content").datepicker({ dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true });
});

</script>

<script language=JavaScript>
function check_userinfo(index) {

	if (index == 1) {

//		if (confirm("������ �մϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���.") == true){
			member_form.action='./regi_edit_sparta_cancel.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&international_money=<?=$international_money?>';
			member_form.submit();
//		}else{
//			return false;
//		}

	} else if(index == 2) {

//		if (confirm("������ �մϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���.") == true){
			member_form.action='./regi_edit_sparta_extention.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&international_money=<?=$international_money?>';
			member_form.submit();
//		}else{
//			return false;
//		}

	} else if(index == 3) {

		var m=document.member_form;

		//��������
		if (m.class_type1.value == "0") {
			alert("�л��� ��������϶��� ����1�� �ʼ����� �׸��Դϴ�.");
			m.class_type1.focus();
			return false;

		} else if(m.class_type1.value == "21") {
			var course="Regular ESL";
		} else if(m.class_type1.value == "22") {
			var course="Intensive ESL";
		} else if(m.class_type1.value == "23") {
			var course="TOEIC Bridge";
		} else if(m.class_type1.value == "25") {
			var course="IELTS Bridge";
		} else if(m.class_type1.value == "27") {
			var course="Certificated TESOL";
		} else if(m.class_type1.value == "28") {
			var course="Benedicto College";
		} else if(m.class_type1.value == "29") {
			var course="Budget Course";
		} else if(m.class_type1.value == "30") {
			var course="Working Holiday";
		} else if(m.class_type1.value == "31") {
			var course="Volunteer Program";
		} else if(m.class_type1.value == "32") {
			var course="TOEIC 600 Guarantee";
		} else if(m.class_type1.value == "33") {
			var course="TOEIC 700 Guarantee";
		} else if(m.class_type1.value == "34") {
			var course="TOEIC 850 Guarantee";
		} else if(m.class_type1.value == "35") {
			var course="IELTS 5.5 Guarantee";
		} else if(m.class_type1.value == "36") {
			var course="IELTS 6.0 Guarantee";
		} else if(m.class_type1.value == "37") {
			var course="IELTS 6.5 Guarantee";
		} else if(m.class_type1.value == "41") {
			var course="Regular TOEIC";
		} else if(m.class_type1.value == "42") {
			var course="Regular IELTS";
		}

		//��������
		var weeks=m.class_gigan1.value;

		//ȯ�ұݾ׼���
		if (m.refund_percent.value == "0") {
			alert("ȯ�� %�� �������ּ���.");
			m.refund_percent.focus();
			return false;

		} else if(m.refund_percent.value == "1") {
			var srefund_percent="30%";
		} else if(m.refund_percent.value == "2") {
			var srefund_percent="50%";
		} else if(m.refund_percent.value == "3") {
			var srefund_percent="70%";
//		} else if(m.refund_percent.value == "4") {
//			var srefund_percent="100%";
		} else if(m.refund_percent.value == "5") {
			var srefund_percent="��ϱ��� ������ �ܿ��к�";
		} else if(m.refund_percent.value == "6") {
			var srefund_percent="2�� ���� ������ ������ �ܿ��к�";
		} else if(m.refund_percent.value == "7") {
			var srefund_percent="4�� ���� ������ ������ �ܿ��к�";
		} else if(m.refund_percent.value == "8") {
			var srefund_percent="4�� ���� �к�� ������ ������ �ܿ��к�";
		}

		if (confirm(""+course+" ���� "+weeks+"�ֿ� ���� "+srefund_percent+"�� �ݾ��� ȯ����ҷ� ��� �˴ϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���.") == true){
			member_form.action='./regi_edit_sparta_refund.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&international_money=<?=$international_money?>';
			member_form.submit();
		}else{
			return false;
		}

	} else if(index == 4) {

		if (confirm("������ �մϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���.") == true){
			member_form.action='./regi_edit_sparta_move.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&international_money=<?=$international_money?>';
			member_form.submit();
		}else{
			return false;
		}

	} else if(index == 5) {

		member_form.action='./regi_edit_sparta_change.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&international_money=<?=$international_money?>';
		member_form.submit();

//		alert("���⸦ �����մϴ�(TEST)");

	}
}
</script>

<table border="0" cellspacing="0" cellpadding="0" width="800">

   <tr>
     <td>
		<a href="./regi_edit_sparta.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>"><img src="./images/on01.gif"></a>

		<a href="./student_remark.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>"><img src="./images/03.gif"></a>
	 </td>
   </tr>

   <tr>
     <td height="35"><b>Reg_<? if($mode=='modify') { ?>Edit<?}else{?>���<? } ?> <a href="#"><img src="images/print_regi.gif" border="0" onclick="window_open('./regi_print.php?num=<?=$num?>','print','scrollbars=no,width=660,height=600');" align="absmiddle"></a></td>
   </tr>
</table>

<form name="member_form" method="post" style="margin:0px" action="?<?=$_get_param[3]?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>&amp;strconfirm_check=<?=$strconfirm_check?>" enctype="multipart/form-data">

<input type="hidden" name="num" value="<?=$num?>" />
<input type="hidden" name="mode" value="<?=$mode?>" />
<input type="hidden" name="agency_id" value="<?=$data[agency_id]?>" />
<input type="hidden" name="campus" value="<?=$data[campus]?>" />
<input type="hidden" name="international_money" value="<?=$data[international_money]?>" />
<input type="hidden" name="extension_yes_no" value="<?=$data[extension_yes_no]?>" />
<input type="hidden" name="refund_yes_no" value="<?=$data[refund_yes_no]?>" />
<input type="hidden" name="agency_name" value="<?=$data[agency_name]?>" />

<input type="hidden" name="student_num" value="<?=$data[student_num]?>" />

<input type="hidden" name="class_fee" value="<?=$data[class_fee]?>" />
<input type="hidden" name="class_fee2" value="<?=$data[class_fee2]?>" />
<input type="hidden" name="class_fee3" value="<?=$data[class_fee3]?>" />
<input type="hidden" name="house_fee1" value="<?=$data[house_fee1]?>" />
<input type="hidden" name="house_fee2" value="<?=$data[house_fee2]?>" />
<input type="hidden" name="house_fee3" value="<?=$data[house_fee3]?>" />
<input type="hidden" name="ag_comm_fee" value="<?=$data[ag_comm_fee]?>" />

<input type="hidden" name="st_pic" value="<?=$data[st_pic]?>" />

<input type="hidden" name="check" value="<?=$data[check]?>" />

<input type="hidden" name="old_regi_date" value="<?=$data[regi_date]?>" />
<input type="hidden" name="old_abrod_date" value="<?=$data[abrod_date]?>" />

<input type="hidden" name="grade_gubun" value="<?=$data[grade_gubun]?>" />

<!--����,���,ȯ��,���� ������ ����
<input type="text" name="class_cost" value="<?=$data[class_cost]?>" />
<input type="text" name="dorm_cost" value="<?=$data[dorm_cost]?>" />
<input type="text" name="ssp_cost" value="<?=$data[ssp_cost]?>" />
<input type="text" name="elect_fee" value="<?=$data[elect_fee]?>" />
<input type="text" name="agency_comm" value="<?=$data[agency_comm]?>" />
<input type="text" name="total_cost" value="<?=$data[total_cost]?>" />
<input type="text" name="agency_cost" value="<?=$data[agency_cost]?>" />
<input type="text" name="abrod_date1" value="<?=$data[abrod_date1]?>" />
<input type="text" name="abrod_date2" value="<?=$data[abrod_date2]?>" />
<input type="text" name="abrod_date3" value="<?=$data[abrod_date3]?>" />
<input type="text" name="class_gigan" value="<?=$data[class_gigan]?>" />
<input type="text" name="class_fee" value="<?=$data[class_fee]?>" />
<input type="text" name="house_fee1" value="<?=$data[house_fee1]?>" />
<input type="text" name="house_fee2" value="<?=$data[house_fee2]?>" />
<input type="text" name="house_fee3" value="<?=$data[house_fee3]?>" />
<input type="text" name="ssp_fee" value="<?=$data[ssp_fee]?>" />
<input type="text" name="elect_fee1" value="<?=$data[elect_fee1]?>" />
<input type="text" name="elect_fee2" value="<?=$data[elect_fee2]?>" />
<input type="text" name="elect_fee3" value="<?=$data[elect_fee3]?>" />
<input type="text" name="ag_comm_fee" value="<?=$data[ag_comm_fee]?>" />
-->

<input type="hidden" name="agency_level" value="<?=$data[agency_level]?>" />
<input type="hidden" name="mb_num" value="<?=$mem[mb_num]?>" />
<input type="hidden" name="regi_date" value="<?=$data[regi_date]?>" />

<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
	<tr>
      <td width="110" bgcolor="#efefef" style="padding: 5 5 5 5;" class="tit11_rb">
		AGENCY
		<?// if($data[agency_register] == 1){ ?>
			<!--(KOREA)-->
		<?// }elseif($data[agency_register] == 2){ ?>
			<!--(JAPAN)-->
		<?// } ?>

	  </td>
      <td width="620" bgcolor="#FFFFFF" style="padding: 5 5 5 5;"><?=$mem[mb_name]?> <select name="agency_level" class="select2">
            <option value="">=SELECT=</option>
            <option value="7" <?if($data[agency_level] == 7){ echo "selected" ;}?>>SUPRIOR AGENCY</option>
            <option value="8" <?if($data[agency_level] == 8){ echo "selected" ;}?>>DELUXE AGENCY</option>
            <option value="11" <?if($data[agency_level] == 11){ echo "selected" ;}?>>EXTRA AGENCY</option>
            <option value="9" <?if($data[agency_level] == 9){ echo "selected" ;}?>>SPECIAL AGENCY</option>
            <option value="10" <?if($data[agency_level] == 10){ echo "selected" ;}?>>EXTRA SPECIAL AGENCY</option>
            <option value="12" <?if($data[agency_level] == 12){ echo "selected" ;}?>>VIP1 AGENCY</option>
            <option value="6" <?if($data[agency_level] == 6){ echo "selected" ;}?>>VIP2 AGENCY</option>
			<option value="13" <?if($data[agency_level] == 13){ echo "selected" ;}?>>VIP3 AGENCY</option>
			<option value="14" <?if($data[agency_level] == 14){ echo "selected" ;}?>>VIP4 AGENCY</option>
			<option value="16" <?if($data[agency_level] == 16){ echo "selected" ;}?>>VIP_PHIL</option>
			<option value="15" <?if($data[agency_level] == 15){ echo "selected" ;}?>>VIP5 AGENCY</option>
		  </select>

		-&nbsp;

		<select name="international_money" class="select2">
			<option value="0" <?if($data[international_money] == 0){ echo "selected" ;}?>>KOREAN</option>
            <option value="1" <?if($data[international_money] == 1){ echo "selected" ;}?>>FOREIGNER</option>
        </select>

		<? if($data[international_money] == 0){ ?>
			<a href="javascript:open_window('international_money', 'regi_edit_inter_update.php?page=<?=$page?>&mode=<?=$mode?>&num=<?=$data[num]?>&class_type1=<?=$data[class_type1]?>&class_type2=<?=$data[class_type2]?>&class_type3=<?=$data[class_type3]?>&class_gigan1=<?=$data[class_gigan1]?>&class_gigan2=<?=$data[class_gigan2]?>&class_gigan3=<?=$data[class_gigan3]?>&dorm_type1=<?=$data[dorm_type1]?>&dorm_type2=<?=$data[dorm_type2]?>&dorm_type3=<?=$data[dorm_type3]?>&dorm_gigan1=<?=$data[dorm_gigan1]?>&dorm_gigan2=<?=$data[dorm_gigan2]?>&dorm_gigan3=<?=$data[dorm_gigan3]?>&agency_level=<?=$data[agency_level]?>&regi_date=<?=$data[regi_date]?>&international_money=1&campus=2', 200, 546, 10, 10, 0, 0, 0, 0, 0);")><font color="blue"><b><img src="./images/price_change1.gif" /></b></font></a>
		<? }elseif($data[international_money] == 1){ ?>
			<a href="javascript:open_window('international_money', 'regi_edit_inter_update.php?page=<?=$page?>&mode=<?=$mode?>&num=<?=$data[num]?>&class_type1=<?=$data[class_type1]?>&class_type2=<?=$data[class_type2]?>&class_type3=<?=$data[class_type3]?>&class_gigan1=<?=$data[class_gigan1]?>&class_gigan2=<?=$data[class_gigan2]?>&class_gigan3=<?=$data[class_gigan3]?>&dorm_type1=<?=$data[dorm_type1]?>&dorm_type2=<?=$data[dorm_type2]?>&dorm_type3=<?=$data[dorm_type3]?>&dorm_gigan1=<?=$data[dorm_gigan1]?>&dorm_gigan2=<?=$data[dorm_gigan2]?>&dorm_gigan3=<?=$data[dorm_gigan3]?>&agency_level=<?=$data[agency_level]?>&regi_date=<?=$data[regi_date]?>&international_money=0&campus=2', 200, 546, 10, 10, 0, 0, 0, 0, 0);")><font color="red"><b><img src="./images/price_change2.gif" /></b></font></a>
		<? } ?>

		Commission(4w) : 
		<? if($data[international_money] == 0){ ?>

			<?
				$rs_comm = new $rs_class($dbcon);
				$rs_comm->clear();
				$rs_comm->set_table($_table['comm']);
				$rs_comm->add_where("used=1");
				$rs_comm->select();
				$data_comm=$rs_comm->fetch();	

				if($data[agency_level] == 6){
					$ag_comm_fee = $data_comm[agency_comm1] ;
				}elseif($data[agency_level] == 7){
					$ag_comm_fee = $data_comm[agency_comm2] ;
				}elseif($data[agency_level] == 8){
					$ag_comm_fee = $data_comm[agency_comm3] ;
				}elseif($data[agency_level] == 9){
					$ag_comm_fee = $data_comm[agency_comm4] ;
				}elseif($data[agency_level] == 10){
					$ag_comm_fee = $data_comm[agency_comm5] ;
				}elseif($data[agency_level] == 11){
					$ag_comm_fee = $data_comm[agency_comm6] ;
				}elseif($data[agency_level] == 12){
					$ag_comm_fee = $data_comm[agency_comm7] ;
				}elseif($data[agency_level] == 13){
					$ag_comm_fee = $data_comm[agency_comm8] ;
				}elseif($data[agency_level] == 14){
					$ag_comm_fee = $data_comm[agency_comm9] ;
				}elseif($data[agency_level] == 15){
					$ag_comm_fee = $data_comm[agency_comm10] ;
				}elseif($data[agency_level] == 16){
					$ag_comm_fee = $data_comm[agency_comm11] ;
				}
			?>

			\<?=number_format($ag_comm_fee)?>

		<? }elseif($data[international_money] == 1){ ?>

			<?
				$rs_comm = new $rs_class($dbcon);
				$rs_comm->clear();
				$rs_comm->set_table($_table['comm_i']);
				$rs_comm->add_where("used=1");
				$rs_comm->select();
				$data_comm=$rs_comm->fetch();

				if($data[agency_level] == 6){ // 25% : VIP2 AGENCY
					$ag_comm_fee = $data_comm[agency_comm1] ;
				}elseif($data[agency_level] == 7){ // 28% : SUPRIOR AGENCY
					$ag_comm_fee = $data_comm[agency_comm2] ;
				}elseif($data[agency_level] == 8){ // 30% : DELUXE AGENCY
					$ag_comm_fee = $data_comm[agency_comm3] ;
				}elseif($data[agency_level] == 9){ // 36% : SPECIAL AGENCY
					$ag_comm_fee = $data_comm[agency_comm4] ;
				}elseif($data[agency_level] == 10){ // 33% : EXTRA SPECIAL AGENCY
					$ag_comm_fee = $data_comm[agency_comm5] ;
				}elseif($data[agency_level] == 11){ // 32% : EXTRA AGENCY
					$ag_comm_fee = $data_comm[agency_comm6] ;
				}elseif($data[agency_level] == 12){ // 35% : VIP1 AGENCY
					$ag_comm_fee = $data_comm[agency_comm7] ;
				}elseif($data[agency_level] == 13){ // 37% : VIP3 AGENCY
					$ag_comm_fee = $data_comm[agency_comm8] ;
				}elseif($data[agency_level] == 14){ // 39% : VIP4 AGENCY
					$ag_comm_fee = $data_comm[agency_comm9] ;
				}elseif($data[agency_level] == 15){ // 0% : VIP5 AGENCY
					$ag_comm_fee = $data_comm[agency_comm10] ;
				}elseif($data[agency_level] == 16){ // 0% : 
					$ag_comm_fee = $data_comm[agency_comm11] ;
				}
			?>

			<?=$ag_comm_fee?>%

		<? } ?>


	  </td>
    </tr>	
</table>
<br>
<table width="800" border="0" cellpadding="0" cellspacing="0" >
	<tr>
      <td><img src="../img/agency/regi_stitle1.gif" /></td>
    </tr>
</table>
<table width="800" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td bgcolor="#FFFFFF">
        <table width="800" border="0" cellspacing="0" cellpadding="0">
         <tr>

		  <td width="115" style="border: #cccccc solid; border-width: 1px 1px 1px 1px" align="center" >
			<? if($data[st_pic]){?>
				<? if($data[connect_num] != ''){?>
					<img src="../student/<?=$data[connect_num]?>/<?=$data[st_pic]?>" width="113" height="146" />
				<? }else{ ?>
					<img src="../student/<?=$data[num]?>/<?=$data[st_pic]?>" width="113" height="146" />
				<? } ?>
			<?}else{?>
				<img src="../img/img_ready.gif" width="113" height="146" />
			<?}?>
		  </td>

		  <td width="10" >&nbsp;</td>
          <td width="675" valign="top">
		    <table width="675" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
             <tr>
              <td width="15%" height="30" bgcolor="#f4f4f4" class="tit11_rb">NAME</td>
              <td width="35%" bgcolor="#FFFFFF">&nbsp;<input name="sname" type="text" class="cc" size="13" maxlength="50" value="<?=$data[sname]?>" style="ime-mode:active;" />
          Nick NAME: <input name="nick" type="text" class="cc" size="10" maxlength="10" value="<?=$data[nick]?>" /></td>
              <td width="15%" bgcolor="#f4f4f4" class="tit11_rb">Eng_NAME</td>
              <td width="35%" bgcolor="#FFFFFF">&nbsp;<input name="sename" type="text" class="cc" id="sename2" size="20" maxlength="50" value="<?=$data[sename]?>" style="ime-mode:inactive;" onblur="javascript:this.value=this.value.toUpperCase();" /><br />&nbsp;<span class="tit11_c">(ex. LIM MIN SUK)</span></td>
             </tr>
             <tr>
              <td height="30" bgcolor="#f4f4f4" class="tit11_rb">SOCIAL<br />NO.</td>
              <td bgcolor="#FFFFFF">&nbsp;<input type="text" name="jumin31" class="cc" size="6" maxlength="6"  value="<?=$data[jumin31]?>" onkeyup="return autoTab(this, 6, event);" />-<input type="text" name="jumin32" class="cc" size="7" maxlength="7"  value="<?=$data[jumin32]?>"/></td>
              <td bgcolor="#f4f4f4" class="tit11_rb">Email</td>
              <td bgcolor="#FFFFFF">&nbsp;<input name="semail" type="text" class="cc" id="semail2" size="30" maxlength="35"  value="<?=$data[semail]?>"/></td>
             </tr>
             <tr>
              <td height="30" bgcolor="#f4f4f4" class="tit11_rb">Telephone</td>
              <td bgcolor="#FFFFFF">&nbsp;<input name="stel" type="text" class="cc" id="stel4" size="3" maxlength="3"  value="<?=$data[stel]?>"/>-<input name="stel2" type="text" class="cc" id="stel5" size="4" maxlength="4"  value="<?=$data[stel2]?>"/>-<input name="stel3" type="text" class="cc" id="stel6" size="4" maxlength="4"  value="<?=$data[stel3]?>"/></td>
              <td bgcolor="#f4f4f4" class="tit11_rb">Cellphone</td>
              <td bgcolor="#FFFFFF">&nbsp;<input name="shtel" type="text" class="cc" id="shtel3" size="3" maxlength="3"  value="<?=$data[shtel]?>"/>-<input name="shtel2" type="text" class="cc" id="shtel4" size="4" maxlength="4"  value="<?=$data[shtel2]?>"/>-<input name="shtel3" type="text" class="cc" id="shtel4" size="4" maxlength="4"  value="<?=$data[shtel3]?>"/></td>
             </tr>

			 <tr>
              <td height="30" bgcolor="#f4f4f4" class="tit11_rb">Nationality</td>
              <td bgcolor="#FFFFFF" colspan="3">&nbsp;<select name="national" class="select2">
					<?=rg_html_option($national,$data['national'])?>
				</select>
			  </td>
             </tr>

<!--�ּ� ��ũ��Ʈ-->
<script>
var win_zip = function(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
    if(typeof daum === 'undefined'){
        alert("���� �����ȣ postcode.v2.js ������ �ε���� �ʾҽ��ϴ�.");
        return false;
    }

    var zip_case = 1;   //0�̸� ���̾�, 1�̸� �������� ���� �ֱ�, 2�̸� ��â

    var complete_fn = function(data){
        // �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

        // �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
        // �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
        var fullAddr = ''; // ���� �ּ� ����
        var extraAddr = ''; // ������ �ּ� ����

        // ����ڰ� ������ �ּ� Ÿ�Կ� ���� �ش� �ּ� ���� �����´�.
        if (data.userSelectedType === 'R') { // ����ڰ� ���θ� �ּҸ� �������� ���
            fullAddr = data.roadAddress;

        } else { // ����ڰ� ���� �ּҸ� �������� ���(J)
            fullAddr = data.jibunAddress;
        }

        // ����ڰ� ������ �ּҰ� ���θ� Ÿ���϶� �����Ѵ�.
        if(data.userSelectedType === 'R'){
            //���������� ���� ��� �߰��Ѵ�.
            if(data.bname !== ''){
                extraAddr += data.bname;
            }
            // �ǹ����� ���� ��� �߰��Ѵ�.
            if(data.buildingName !== ''){
                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
            }
            // �������ּ��� ������ ���� ���ʿ� ��ȣ�� �߰��Ͽ� ���� �ּҸ� �����.
            extraAddr = (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
        }

        // �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ְ�, Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
        var of = document[frm_name];

        of[frm_zip].value = data.zonecode;

        of[frm_addr1].value = fullAddr;
        of[frm_addr3].value = extraAddr;

        if(of[frm_jibeon] !== undefined){
            of[frm_jibeon].value = data.userSelectedType;
        }

        of[frm_addr2].focus();
    };

    switch(zip_case) {
        case 1 :    //iframe�� �̿��Ͽ� �������� ���� �ֱ�
            var daum_pape_id = 'daum_juso_page'+frm_zip,
                element_wrap = document.getElementById(daum_pape_id),
                currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            if (element_wrap == null) {
                element_wrap = document.createElement("div");
                element_wrap.setAttribute("id", daum_pape_id);
                element_wrap.style.cssText = 'display:none;border:1px solid;left:0;width:100%;height:300px;margin:5px 0;position:relative;-webkit-overflow-scrolling:touch;';
                element_wrap.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-21px;z-index:1" class="close_daum_juso" alt="���� ��ư">';
                jQuery('form[name="'+frm_name+'"]').find('input[name="'+frm_addr1+'"]').before(element_wrap);
                jQuery("#"+daum_pape_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe�� ���� element�� �Ⱥ��̰� �Ѵ�.
                    element_wrap.style.display = 'none';
                    // �����ȣ ã�� ȭ���� ���̱� �������� scroll ��ġ�� �ǵ�����.
                    document.body.scrollTop = currentScroll;
                },
                // �����ȣ ã�� ȭ�� ũ�Ⱑ �����Ǿ����� ������ �ڵ带 �ۼ��ϴ� �κ�.
                // iframe�� ���� element�� ���̰��� �����Ѵ�.
                onresize : function(size) {
                    element_wrap.style.height = size.height + "px";
                },
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe�� ���� element�� ���̰� �Ѵ�.
            element_wrap.style.display = 'block';
            break;
        case 2 :    //��â���� ����
            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                }
            }).open();
            break;
        default :   //iframe�� �̿��Ͽ� ���̾� ����
            var rayer_id = 'daum_juso_rayer'+frm_zip,
                element_layer = document.getElementById(rayer_id);
            if (element_layer == null) {
                element_layer = document.createElement("div");
                element_layer.setAttribute("id", rayer_id);
                element_layer.style.cssText = 'display:none;border:5px solid;position:fixed;width:300px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10000';
                element_layer.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" class="close_daum_juso" alt="�ݱ� ��ư">';
                document.body.appendChild(element_layer);
                jQuery("#"+rayer_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe�� ���� element�� �Ⱥ��̰� �Ѵ�.
                    element_layer.style.display = 'none';
                },
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            // iframe�� ���� element�� ���̰� �Ѵ�.
            element_layer.style.display = 'block';
    }
}

</script>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<!--�ּ� ��ũ��Ʈ-->

			<tr>
				<td height="30" bgcolor="#f4f4f4" class="tit11_rb" rowspan="2">Address</td>
				<td colspan="3" bgcolor="#FFFFFF">
					&nbsp;<input type="text" class="cc" style="height:20px; font-size:12px;"  name="mb_post_new" size="6" value="<?=$data['mb_post_new']?>" maxlength="6" readonly value="" span="2" hname="�����ȣ">

					<img src="../img/mypage/bttn_zip.gif" align="absmiddle" style="cursor:hand" onClick="win_zip('member_form', 'mb_post_new', 'mb_address1', 'mb_address2', 'mb_address3', 'mb_addr_jibeon');" value='�����ȣ �˻�'>
				</td>
			</tr>

			<tr>
				<td colspan="3" bgcolor="#FFFFFF" height="22">
					&nbsp;<input name="mb_address1" style="height:20px; font-size:12px;"  type="text" class="cc" id="mb_address1" value="<?=$data['mb_address1']?>" size="40" hname="�⺻�ּ�"> �⺻�ּ�<br/>
					&nbsp;<input name="mb_address2" style="height:20px; font-size:12px;"  type="text" class="cc" id="mb_address2" value="<?=$data['mb_address2']?>" size="40" hname="���ּ�"> ���ּ�<br />
					&nbsp;<input name="mb_address3" style="height:20px; font-size:12px;"  type="text" class="cc" id="mb_address3" value="<?=$data['mb_address3']?>" size="40" hname="�����׸�"> �����׸�
				</td>
			</tr>

			 <!--tr>
              <td height="30" bgcolor="#f4f4f4" class="tit11_rb" rowspan="2">Address</td>
              <td bgcolor="#FFFFFF">&nbsp;<input type="text" class="cc" name="mb_post1" size="3" maxlength="3" readonly="readonly" value="<?=$data['mb_post1']?>" span="2" hname="�����ȣ" <?=$required['mb_address']?> />-<input type="text" class="cc" name="mb_post2" size="3" maxlength="3" readonly="readonly" value="<?=$data['mb_post2']?>" /> <img src="../img/agency/btn_join_addcode.gif" align="absmiddle" onclick="search_post('<?=$_url['member']?>','member_form|mb_post1|mb_post2|mb_address1|mb_address2')" style="CURSOR:hand" /></td>
              <td bgcolor="#f4f4f4" class="tit11_rb">Nationality</td>
              <td bgcolor="#FFFFFF">&nbsp;<select name="national" class="select2">
					<?=rg_html_option($national,$data['national'])?>
				</select>
			  </td>
             </tr>
			 <tr>
              <td height="30" colspan="3" bgcolor="#FFFFFF" >&nbsp;<input name="mb_address1" type="text" class="cc" id="mb_address3" value="<?=$data['mb_address1']?>" size="40" readonly="readonly" hname="�ּ�" <?=$required['mb_address']?> /> <input name="mb_address2" type="text" class="cc" id="mb_address4" value="<?=$data['mb_address2']?>" size="35" hname="���ּ�" <?=$required['mb_address']?> /></td>
             </tr-->

			  <?if($cost_st_date >= $data[regi_date]){?>
			  <?}?>
			  <?if($elect_st_date >= $data[regi_date]){?>
			  <?}?>
		    </table>
          </td>
         </tr>
        </table>
      </td>
   </tr>
</table>
<div id="pic" style="position:absolute; left:201px; top:410px; z-index:10; visibility:default; OVERFLOW: auto; layer-background-color:rgb(255,255,255); "><a href="javascript:open_window('regi', 'st_pic_sparta.php?num=<?=$data[num]?>', 200, 546, 380, 90, 0, 0, 0, 0, 0);")>
	<img src="../img/btn/btn_st_pic.gif" border="0" />
<? if($data[st_pic] == ''){ ?>
<? } ?>

</a></div>
<br>
<table width="800" border="0" cellpadding="0"  cellspacing="1" bgcolor="#cccccc">
   <tr>
      <td width="80" bgcolor="#f4f4f4" class="tit11_rb">Occupation</td>
      <td width="183" bgcolor="#FFFFFF">&nbsp;<select name="job" class="select2">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['job_type1'],$data['job'])?>
          </select></td>
      <td width="80" bgcolor="#f4f4f4" class="tit11_rb">Insurance</td>
      <td width="143" bgcolor="#FFFFFF">&nbsp;<select name="insu" class="select2">
            <?=rg_html_option($_regi['insurance_j'],$data['insu'])?>
          </select>
      </td>
      <td width="80" bgcolor="#f4f4f4" class="tit11_rb">ID</td>
      <td width="164" bgcolor="#FFFFFF">&nbsp;<input name="student_id" type="text" class="cc" id="student_id" size="20" maxlength="30" value="<?=$data['student_id']?>"/></td>
   </tr>
   <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Em_contact</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="xname" type="text" class="cc" id="xname" size="10" maxlength="25" value="<?=$data['xname']?>"/></td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Relation</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="xrelation" type="text" class="cc" id="xrelation" size="10" maxlength="10" value="<?=$data['xrelation']?>" /></td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Contact NO</td>
      <td  bgcolor="#FFFFFF">&nbsp;<input name="xhtel" type="text" class="cc" id="xhtel" size="20" maxlength="30" value="<?=$data['xhtel']?>"/></td>
   </tr>

   <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Pickup</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="pickup_yes_no" class="select2">
            <option>=SELECT=</option>
			<?=rg_html_option($_regi['pickup_yes_no'],$data['pickup_yes_no'])?>
          </select>
	  </td>

	  <td bgcolor="#f4f4f4" class="tit11_rb">Room Number</td>

	  <?
		if($data['dorm_type1'] == 1 Or $data['dorm_type1'] == 4 Or $data['dorm_type1'] == 5){
			$re_dorm_trpe1 = 1;
	    }elseif($data['dorm_type1'] == 2 Or $data['dorm_type1'] == 6 Or $data['dorm_type1'] == 7){
			$re_dorm_trpe1 = 2;
	    }elseif($data['dorm_type1'] == 3){
			$re_dorm_trpe1 = 3;
		}

		if($data['dorm_type2'] == 1 Or $data['dorm_type2'] == 4 Or $data['dorm_type2'] == 5){
			$re_dorm_trpe2 = 1;
	    }elseif($data['dorm_type2'] == 2 Or $data['dorm_type2'] == 6 Or $data['dorm_type2'] == 7){
			$re_dorm_trpe2 = 2;
	    }elseif($data['dorm_type2'] == 3){
			$re_dorm_trpe2 = 3;
		}

		if($$data['dorm_type3'] == 1 Or $$data['dorm_type3'] == 4 Or $$data['dorm_type3'] == 5){
			$re_dorm_trpe3 = 1;
	    }elseif($$data['dorm_type3'] == 2 Or $$data['dorm_type3'] == 6 Or $$data['dorm_type3'] == 7){
			$re_dorm_trpe3 = 2;
	    }elseif($$data['dorm_type3'] == 3){
			$re_dorm_trpe3 = 3;
		}
	  ?>

	  <td bgcolor="#FFFFFF">

		<? if($data[room_number] != ''){ ?>
			&nbsp;<?=$data[room_number]?>
		<? }else{ ?>
			&nbsp;<a href="#" onclick="window_open('./seat_report_list_popup.php?campusSeq=1&sexSeq=4&num=<?=$data[num]?>&roomSeats[0]=<?=$re_dorm_trpe1?>&roomSeats[1]=<?=$re_dorm_trpe2?>&roomSeats[2]=<?=$re_dorm_trpe3?>&startDate=<?=$data['abrod_date']?>&endDate=<?=$data['end_date']?>&periodWeek=<?=$data[class_gigan]?>','seat_report_list_popup','scrollbars=yes,width=550,height=700');">INSERT</a>
		<? } ?>

	  </td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Speech Content</td>
      <td  bgcolor="#FFFFFF">&nbsp;<input name="speech_content" type="text" class="cc" id="speech_content" size="10" maxlength="10" value="<?=$data['speech_content']?>" /></td>
   </tr>
</table>

<? if($data[student_chk] == 1){ ?>

<br>
<table width="800" border="0" cellpadding="0"  cellspacing="1" bgcolor="#cccccc">
   <tr>
      <td width="90" bgcolor="#f4f4f4" class="tit11_rb">���谡�� ��/��</td>
      <td width="275" bgcolor="#FFFFFF">

		<? if($data[insu_check] == 1){ ?>
			&nbsp;��
		<? }elseif($data[insu_check] == 2){ ?>
			&nbsp;��
		<? } ?>

	  </td>
      <td width="110" bgcolor="#f4f4f4" class="tit11_rb">CIA����Ȯ�� ��/��</td>
      <td width="255" bgcolor="#FFFFFF">

		<? if($data[rule_check] == 1){ ?>
			&nbsp;��
		<? }elseif($data[rule_check] == 2){ ?>
			&nbsp;��
		<? } ?>

	  </td>
   </tr>
   <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Ư�̻���</td>
      <td bgcolor="#FFFFFF" colspan="3">
		&nbsp;<?=$data[message_text]?>
	  </td>

   </tr>
</table>

<? } ?>

<script>
	function report_insert(sname,class_type,class_gigan) {

//		if(confirm(""+sname+" �л��� "+class_type+" / "+class_gigan+"�� ���� ����ǥ�� �ڵ����� �����˴ϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���.")) 

		if(confirm(""+sname+" �л��� Student Progress Report�� �����˴ϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���."))
		open_window('report_insert', 'report_insert.php?page=<?=$page?>&mode=<?=$mode?>&connect_num=<?=$num?>&campus_gubun=sparta', 200, 546, 380, 90, 0, 0, 0, 0, 0);
	}

	//���ο� ����ǥ �Է��ϱ�
	function report_insert_new(sname,class_type,class_gigan,student_id,state) {

		if(confirm(""+sname+" �л��� Student Progress Report("+class_type+")�� �����˴ϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���.")) 
		open_window('report_insert_new', 'report_insert_new.php?page=<?=$page?>&mode=<?=$mode?>&connect_num=<?=$num?>&student_id='+student_id+'&class_type='+class_type+'&state='+state+'&campus_gubun=sparta', 200, 546, 380, 90, 0, 0, 0, 0, 0);
	}

	function final_deposit_admin(sname,final_deposit_admin) {
		if(confirm(""+sname+" �л��� �к���� ������ �����մϴ�.\n\n��� �����Ϸ��� Ȯ�� ��ư�� �����ּ���.")) 
		open_window('final_deposit_admin', 'final_deposit_memo.php?final_deposit_admin='+final_deposit_admin+'&connect_num=<?=$num?>&mode=admin_input', 200, 546, 380, 90, 0, 0, 0, 0, 0);
	}

	function reg_confirm(sname,agency_net,reg_confirm_admin) {
		if(confirm("Did you check student "+sname+" registeration and NET fee "+agency_net+"?\n\nIf you want to continue please click to YES")) 
		open_window('reg_confirm', 'reg_confirm.php?reg_confirm_admin='+reg_confirm_admin+'&connect_num=<?=$num?>&mode=admin_input', 200, 546, 380, 90, 0, 0, 0, 0, 0);
	}
</script>

<table width="800" border="0" cellpadding="0" cellspacing="0" >
	<tr>
      <td colspan="3" height="10"></td>
    </tr>
	<tr>
      <td width="450">

		<? if($data[state] != 4){ ?>

			<? if($data['grade_gubun'] != ''){ ?>
				<a href="./student_report_new.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>&student_id=<?=$data['student_id']?>&state=2"><img src="./images/report_insert2.gif"></a>
			<? }else{ ?>
				<img onClick="report_insert_new('<?=$data[sname]?>','SESL','<?=$data[class_gigan]?>','<?=$data[student_id]?>','2')" src="./images/report_insert.gif" onFocus="this.blur()" style="cursor:hand" border="0">
			<? } ?>

			<? if($data['grade_gubun1'] != ''){ ?>
				<a href="./student_report_toeic.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>&student_id=<?=$data['student_id']?>&state=2"><img src="./images/report_insert_toeic2.gif"></a>
			<? }else{ ?>
				<img onClick="report_insert_new('<?=$data[sname]?>','TOEIC','<?=$data[class_gigan]?>','<?=$data[student_id]?>','2')" src="./images/report_insert_toeic.gif" onFocus="this.blur()" style="cursor:hand" border="0">
			<? } ?>

			<? if($data['grade_gubun2'] != ''){ ?>
				<a href="./student_report_ielts.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>&student_id=<?=$data['student_id']?>&state=2"><img src="./images/report_insert_ielts2.gif"></a>
			<? }else{ ?>
				<img onClick="report_insert_new('<?=$data[sname]?>','IELTS','<?=$data[class_gigan]?>','<?=$data[student_id]?>','2')" src="./images/report_insert_ielts.gif" onFocus="this.blur()" style="cursor:hand" border="0">
			<? } ?>

		<? }else{ ?>

			<? if($data['grade_gubun'] != ''){ ?>
				<a href="./student_report_new.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>&student_id=<?=$data['student_id']?>&state=4"><img src="./images/report_insert2.gif"></a>
			<? }else{ ?>
				<img onClick="report_insert_new('<?=$data[sname]?>','SESL','<?=$data[class_gigan]?>','<?=$data[student_id]?>','4')" src="./images/report_insert.gif" onFocus="this.blur()" style="cursor:hand" border="0">
			<? } ?>

			<? if($data['grade_gubun1'] != ''){ ?>
				<a href="./student_report_toeic.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>&student_id=<?=$data['student_id']?>&state=4"><img src="./images/report_insert_toeic2.gif"></a>
			<? }else{ ?>
				<img onClick="report_insert_new('<?=$data[sname]?>','TOEIC','<?=$data[class_gigan]?>','<?=$data[student_id]?>','4')" src="./images/report_insert_toeic.gif" onFocus="this.blur()" style="cursor:hand" border="0">
			<? } ?>

			<? if($data['grade_gubun2'] != ''){ ?>
				<a href="./student_report_ielts.php?<?=$_get_param[3]?>&regi1=<?=$regi1?>&regi2=<?=$regi2?>&abrod_date_str1=<?=$abrod_date_str1?>&abrod_date_str2=<?=$abrod_date_str2?>&arrival_date_str1=<?=$arrival_date_str1?>&arrival_date_str2=<?=$arrival_date_str2?>&search_student_dateT=<?=$search_student_dateT?>&stradmission_state=<?=$stradmission_state?>&agency_register=<?=$agency_register?>&strconfirm_check=<?=$strconfirm_check?>&mode=<?=$mode?>&num=<?=$num?>&student_id=<?=$data['student_id']?>&state=4"><img src="./images/report_insert_ielts2.gif"></a>
			<? }else{ ?>
				<img onClick="report_insert_new('<?=$data[sname]?>','IELTS','<?=$data[class_gigan]?>','<?=$data[student_id]?>','4')" src="./images/report_insert_ielts.gif" onFocus="this.blur()" style="cursor:hand" border="0">
			<? } ?>

		<? } ?>

	  </td>
      <td width="350" align="right">Checked by : <?=$data['last_register']?> (<?=rg_date($data['last_regi_date'])?>)</td>
      <!--td width="150" align="right"><img src="./images/btn_regi_modi.gif" /></td-->
    </tr>
</table>

<br>
<table width="800" border="0" cellpadding="0" cellspacing="0" >
	<tr>
      <td width="150"><a href="#" onclick="window_open('../invoice/invoice.php?inv_type=st&num=<?=$data[num]?>','invoice_st','scrollbars=no,width=700,height=852');"><img src="./images/btn_st_inv.gif" /></a></td>
      <td width="7">&nbsp;</td>
      <td width="150"><a href="#" onclick="window_open('../invoice/invoice.php?inv_type=ag&num=<?=$data[num]?>','invoice_ag','scrollbars=no,width=700,height=965');"><img src="./images/btn_ag_inv.gif" /></a></td>
      <td width="7">&nbsp;</td>
      <td width="125"><a href="#" onclick="window_open('../invoice/certi.php?num=<?=$data[num]?>','certi','scrollbars=no,width=700,height=965');"><img src="./images/btn_certi.gif" /></a></td>
	  <td width="7">&nbsp;</td>
	  <td width="125"><a href="#" onclick="window_open('../invoice/bc_certi.php?num=<?=$data[num]?>','bc_certi','scrollbars=no,width=700,height=965');"><img src="./images/btn_certi_01.gif" /></a></td>
	  <td width="96"><!--img src="./images/report_insert.gif" /></td-->
      <td width="7">&nbsp;</td>
      <td width="166" align="right">


	  </td>
    </tr>
	<tr>
      <td colspan="4" height="10"></td>
    </tr>
</table>
<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
    <tr>
      <td colspan="4" bgcolor="#FFFFFF" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle2.gif" />
		  
		  <!--select name="campus" class="select2" onchange="location.href='regi_edit_campus.php?num=<?=$data[num]?>&mode=modify&page=<?=$_get_param[3]?>&campus='+this.value;"><?=rg_html_option($_regi['campus'],$data['campus'])?></select-->

	  </td>
    </tr>

	<!--����1�׷� ����-->
	<tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Course1</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="class_type1" class="select2">
			<?=rg_html_option($class_type,$data['class_type1'])?>
          </select> / 

			<select name="class_type1_1" class="select4">
				<?=rg_html_option($class_type,$data['class_type1_1'])?>
			</select>
		</td>

	  <td bgcolor="#f4f4f4" class="tit11_rb">Period1</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="class_gigan1" class="select2">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['class_gigan1'])?>
          </select> / 

			<select name="class_gigan1_1" class="select4">
				<option>=SELECT=</option>
				<?=rg_html_option($_regi['week_admin'],$data['class_gigan1_1'])?>
			</select>

	  </td>
    </tr>

	<tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Course2</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="class_type2" class="select2">
			<?=rg_html_option($class_type,$data['class_type2'])?>
          </select> / 

			<select name="class_type2_1" class="select4">
			<?=rg_html_option($class_type,$data['class_type2_1'])?>
          </select>

		  </td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Period2</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="class_gigan2" class="select2">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['class_gigan2'])?>
          </select> / 

			<select name="class_gigan2_1" class="select4">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['class_gigan2_1'])?>
          </select>
	  </td>
    </tr>

	<tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Course3</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="class_type3" class="select2">
			<?=rg_html_option($class_type,$data['class_type3'])?>
          </select> / 

		<select name="class_type3_1" class="select4">
			<?=rg_html_option($class_type,$data['class_type3_1'])?>
          </select>
		</td>

	  <td bgcolor="#f4f4f4" class="tit11_rb">Period3</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="class_gigan3" class="select2">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['class_gigan3'])?>
          </select> / 

			<select name="class_gigan3_1" class="select4">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['class_gigan3_1'])?>
          </select>
	  </td>
    </tr>

	<tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Dor_Type1</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="dorm_type1" class="select2">
		  <option>=SELECT=</option>
            <?=rg_html_option($_regi['dorm_type_list'],$data['dorm_type1'])?>
          </select> / 

		<select name="dorm_type1_1" class="select4">
		  <option>=SELECT=</option>
            <?=rg_html_option($_regi['dorm_type_list'],$data['dorm_type1_1'])?>
          </select>

		  </td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Period1</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="dorm_gigan1" class="select2">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['dorm_gigan1'])?>
          </select> / 

		<select name="dorm_gigan1_1" class="select4">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['dorm_gigan1_1'])?>
          </select>

		</td>
    </tr>
	<!--����1�׷� ��-->

	<!--����2�׷� ����-->
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Dor_Type2</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="dorm_type2" class="select2">
           <option>=SELECT=</option>
			<?=rg_html_option($_regi['dorm_type_list'],$data['dorm_type2'])?>
          </select> / 

		<select name="dorm_type2_1" class="select4">
           <option>=SELECT=</option>
			<?=rg_html_option($_regi['dorm_type_list'],$data['dorm_type2_1'])?>
          </select>

		</td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Period2</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="dorm_gigan2" class="select2">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['dorm_gigan2'])?>
          </select> / 

		<select name="dorm_gigan2_1" class="select4">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['dorm_gigan2_1'])?>
          </select>

		</td>
    </tr>
	<!--����2�׷� ��-->

	<!--����3�׷� ����-->

    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Dor_Type3</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="dorm_type3" class="select2">
           <option>=SELECT=</option>
			<?=rg_html_option($_regi['dorm_type_list'],$data['dorm_type3'])?>
          </select> / 

		<select name="dorm_type3_1" class="select4">
           <option>=SELECT=</option>
			<?=rg_html_option($_regi['dorm_type_list'],$data['dorm_type3_1'])?>
          </select>

		</td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Period3</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="dorm_gigan3" class="select2">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['dorm_gigan3'])?>
          </select> / 

		<select name="dorm_gigan3_1" class="select4">
            <option>=SELECT=</option>
            <?=rg_html_option($_regi['week_admin'],$data['dorm_gigan3_1'])?>
          </select>

		</td>
    </tr>
	<!--����3�׷� ��-->

	<!--����������-->
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Certification</td>
      <td bgcolor="#FFFFFF" valign="top">

		&nbsp;<input type="checkbox" name="certificate1" value="1" <? if($data['certificate1'] == 1){ ?>checked<? } ?> />Graduation Certificate<? if($data['certificate1'] == 1){ ?>&nbsp;<<?=$data['certificate1_view']?> view><? } ?><br />
		&nbsp;<input type="checkbox" name="certificate2" value="1" <? if($data['certificate2'] == 1){ ?>checked<? } ?> />Level Test Certificate<? if($data['certificate2'] == 1){ ?>&nbsp;<<?=$data['certificate2_view']?> view><? } ?><br />
		&nbsp;<input type="checkbox" name="certificate3" value="1" <? if($data['certificate3'] == 1){ ?>checked<? } ?> />Speech Contest Certificate<? if($data['certificate3'] == 1){ ?>&nbsp;<<?=$data['certificate3_view']?> view><? } ?><br />
		&nbsp;<input type="checkbox" name="certificate4" value="1" <? if($data['certificate4'] == 1){ ?>checked<? } ?> />TOEIC Certificate<? if($data['certificate4'] == 1){ ?>&nbsp;<<?=$data['certificate4_view']?> view><? } ?>

	 </td>

	 <td bgcolor="#f4f4f4" class="tit11_rb">Certification</td>
     <td bgcolor="#FFFFFF" valign="top">

		&nbsp;<input type="checkbox" name="certificate5" value="1" <? if($data['certificate5'] == 1){ ?>checked<? } ?> />IELTS Certificate<? if($data['certificate5'] == 1){ ?>&nbsp;<<?=$data['certificate5_view']?> view><? } ?><br />
		&nbsp;<input type="checkbox" name="certificate6" value="1" <? if($data['certificate6'] == 1){ ?>checked<? } ?> />TESOL Certificate<? if($data['certificate6'] == 1){ ?>&nbsp;<<?=$data['certificate6_view']?> view><? } ?><br />
		&nbsp;<input type="checkbox" name="certificate7" value="1" <? if($data['certificate7'] == 1){ ?>checked<? } ?> />Voluntary Service Certificate<? if($data['certificate7'] == 1){ ?>&nbsp;<<?=$data['certificate7_view']?> view><? } ?>

	  </td>
    </tr>
	<!--������ ��-->

	<tr>
      <td colspan="4" bgcolor="#FFFFFF" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle3.gif" /></td>
    </tr>
    <tr>
<?
	//���� ���� ���� ��
	$class_end_date1 = date("Y", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan']*7-1),$data['abrod_date1']));
	$class_end_date2 = date("m", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan']*7-1),$data['abrod_date1']));
	$class_end_date3 = date("d", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan']*7-1),$data['abrod_date1']));
?>

	  <td bgcolor="#f4f4f4" class="tit11_rb">* Departure</td>
      <td bgcolor="#FFFFFF" class="tit11">&nbsp;<input type="text" name="abrod_date" class="cc" id="startDate" size="13" value="<?=$data['abrod_date']?>" readonly="readonly"/> ~ <input type="text" name="end_date" class="cc" size="13" id="endDate" value="<?=$data['end_date']?>"/> (<?=$data['class_gigan']?>w, <?=$class_end_date1?>.<?=$class_end_date2?>.<?=$class_end_date3?>) (<input type="checkbox" name="auto_date" value="1" />Auto)

		<br>
<?

//�л� 3���� Ŀ��ŧ�� ����Ҷ� ��¥ üũ�ϴ� ���

	if($data['class_gigan1'] != 0){
		$class_end_date1_1 = date("Y", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1']*7-1),$data['abrod_date1']));
		$class_end_date1_2 = date("m", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1']*7-1),$data['abrod_date1']));
		$class_end_date1_3 = date("d", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1']*7-1),$data['abrod_date1']));

echo	$str_class_start_date1 = $data['abrod_date1']."-".$data['abrod_date2']."-".$data['abrod_date3'];
echo "~";
echo	$str_class_end_date1 = $class_end_date1_1."-".$class_end_date1_2."-".$class_end_date1_3;
echo "<br>";
	}

	if($data['class_gigan2'] != 0){
		$class_start_date2_1 = date("Y", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1']*7),$data['abrod_date1']));
		$class_start_date2_2 = date("m", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1']*7),$data['abrod_date1']));
		$class_start_date2_3 = date("d", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1']*7),$data['abrod_date1']));

		$class_end_date2_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2']*7-1),$class_start_date2_1));
		$class_end_date2_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2']*7-1),$class_start_date2_1));
		$class_end_date2_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2']*7-1),$class_start_date2_1));

echo	$str_class_start_date2 = $class_start_date2_1."-".$class_start_date2_2."-".$class_start_date2_3;
echo "~";
echo	$str_class_end_date2 = $class_end_date2_1."-".$class_end_date2_2."-".$class_end_date2_3;
echo "<br>";
	}

	if($data['class_gigan3'] != 0){
		$class_start_date3_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2']*7),$class_start_date2_1));
		$class_start_date3_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2']*7),$class_start_date2_1));
		$class_start_date3_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2']*7),$class_start_date2_1));

		$class_end_date3_1 = date("Y", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($data['class_gigan3']*7-1),$class_start_date3_1));
		$class_end_date3_2 = date("m", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($data['class_gigan3']*7-1),$class_start_date3_1));
		$class_end_date3_3 = date("d", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($data['class_gigan3']*7-1),$class_start_date3_1));

echo	$str_class_start_date3 = $class_start_date3_1."-".$class_start_date3_2."-".$class_start_date3_3;
echo "~";
echo	$str_class_end_date3 = $class_end_date3_1."-".$class_end_date3_2."-".$class_end_date3_3;
echo "<br />";
	}

//�л����� ������ �ӽñⰣ 2
	if($data['class_gigan1_1'] != 0){
echo "-----------------------------------";

echo "<br />";
		$class_end_date1_1 = date("Y", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1_1']*7-1),$data['abrod_date1']));
		$class_end_date1_2 = date("m", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1_1']*7-1),$data['abrod_date1']));
		$class_end_date1_3 = date("d", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1_1']*7-1),$data['abrod_date1']));

echo	$str_class_start_date1 = $data['abrod_date1']."-".$data['abrod_date2']."-".$data['abrod_date3'];
echo "~";
echo	$str_class_end_date1 = $class_end_date1_1."-".$class_end_date1_2."-".$class_end_date1_3;
echo "<br>";
	}

	if($data['class_gigan2_1'] != 0){
		$class_start_date2_1 = date("Y", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1_1']*7),$data['abrod_date1']));
		$class_start_date2_2 = date("m", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1_1']*7),$data['abrod_date1']));
		$class_start_date2_3 = date("d", mktime(0,0,0,$data['abrod_date2'],$data['abrod_date3']+($data['class_gigan1_1']*7),$data['abrod_date1']));

		$class_end_date2_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2_1']*7-1),$class_start_date2_1));
		$class_end_date2_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2_1']*7-1),$class_start_date2_1));
		$class_end_date2_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2_1']*7-1),$class_start_date2_1));

echo	$str_class_start_date2 = $class_start_date2_1."-".$class_start_date2_2."-".$class_start_date2_3;
echo "~";
echo	$str_class_end_date2 = $class_end_date2_1."-".$class_end_date2_2."-".$class_end_date2_3;
echo "<br>";
	}

	if($data['class_gigan3_1'] != 0){
		$class_start_date3_1 = date("Y", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2_1']*7),$class_start_date2_1));
		$class_start_date3_2 = date("m", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2_1']*7),$class_start_date2_1));
		$class_start_date3_3 = date("d", mktime(0,0,0,$class_start_date2_2,$class_start_date2_3+($data['class_gigan2_1']*7),$class_start_date2_1));

		$class_end_date3_1 = date("Y", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($data['class_gigan3_1']*7-1),$class_start_date3_1));
		$class_end_date3_2 = date("m", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($data['class_gigan3_1']*7-1),$class_start_date3_1));
		$class_end_date3_3 = date("d", mktime(0,0,0,$class_start_date3_2,$class_start_date3_3+($data['class_gigan3_1']*7-1),$class_start_date3_1));

echo	$str_class_start_date3 = $class_start_date3_1."-".$class_start_date3_2."-".$class_start_date3_3;
echo "~";
echo	$str_class_end_date3 = $class_end_date3_1."-".$class_end_date3_2."-".$class_end_date3_3;
echo "<br />";
	}










?>
	  </td>

      <td bgcolor="#f4f4f4" class="tit11_rb">Passport No</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="passport" type="text" class="cc" size="20" maxlength="25" value="<?=$data['passport']?>"/></td>
	</tr>
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Air line</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="airplane" class="select2">
            <?=rg_html_option($_regi['airplane1'],$data['airplane'])?>
          </select>
	  </td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Flight No</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="air_text_input" type="text" class="cc" id="chain" size="20" maxlength="25" value="<?=$data['air_text_input']?>"/></td>
    </tr>
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Time of Departure</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="starthh" class="select2">
            <?=rg_html_option($_const['time_h'],$data['starthh'])?>
          </select> : <select name="startmm"  class="select2">
            <? for ($i=0; $i<=59; $i++){?>
				<option value="<?=$i?>" <? if($data['startmm'] == $i) { ?>selected<? } ?>><?if($i<10){?>0<?}?><?=$i?></option>
            <?}?>
          </select>
      </td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Time of Arrival</td>
      <td bgcolor="#FFFFFF">&nbsp;<select name="endhh" class="select2">
            <?=rg_html_option($_const['time_h'],$data['endhh'])?>
          </select> : <select name="endmm" class="select2">
            <? for ($j=0; $j<=59; $j++){?>
			<option value="<?=$j?>" <? if($data['endmm'] == $j) { ?>selected<? } ?>><?if($j<10){?>0<?}?><?=$j?></option>
            <?}?>
          </select></td>
    </tr>

	<? if($_mb['mb_id'] == 'cbsangel'){ ?>
		<tr>
		  <td bgcolor="#f4f4f4" class="tit11_rb">Check Out</td>
		  <td bgcolor="#FFFFFF">&nbsp;<input type="text" name="check_out_date" class="cc" id="check_out_date" size="10" value="<?=$data['check_out_date']?>" />
		  </td>
		  <td bgcolor="#f4f4f4" class="tit11_rb"</td>
		  <td bgcolor="#FFFFFF">&nbsp;</td>
		</tr>
	<? } ?>

	<tr>
      <td colspan="4" bgcolor="#FFFFFF" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle5.gif" /></td>
    </tr>
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Branch</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="chain" type="text" class="cc" id="chain" size="10" maxlength="25" value="<?=$data['chain']?>"/></td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Name</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="cname" type="text" class="cc" id="consult" size="10" maxlength="10" value="<?=$data['cname']?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Position</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="part" type="text" class="cc" id="part" size="20" maxlength="30" value="<?=$data['part']?>"/></td>
      <td bgcolor="#f4f4f4" class="tit11_rb">Contact No</td>
      <td bgcolor="#FFFFFF">&nbsp;<input name="chain_tel" type="text" class="cc" id="consult" size="20" maxlength="30" value="<?=$data['tel']?>"/></td>
    </tr>
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Fax No</td>
      <td bgcolor="#FFFFFF" colspan="3" >&nbsp;<input name="fax" type="text" class="cc" id="fax" size="20" maxlength="30" value="<?=$data['fax']?>"/>
      </td>
    </tr>
    <tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">Memo</td>
      <td colspan="3" bgcolor="#FFFFFF">&nbsp;<textarea class="text" rows="4" name="memo" cols="80"><?=$data['memo']?></textarea>
		<input type="checkbox" name="confirm_check" value="1" <? if($data['confirm_check'] == 1){ ?>checked<? } ?> />Check Point
      </td>
    </tr>

    <!--tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">�л�<br />Ư�̻���</td>
      <td colspan="3" bgcolor="#FFFFFF">&nbsp;<textarea class="text" rows="4" name="student_memo" cols="80"><?=$data['student_memo']?></textarea>
      </td>
    </tr-->

	<tr>
      <td bgcolor="#f4f4f4" class="tit11_rb">State</td>
      <td colspan="3" bgcolor="#FFFFFF">&nbsp;<!--select name="state" class="select2">
            <?=rg_html_option($_regi['state'],$data['state'])?>
          </select--><input type="hidden" name="state" value="<?=$data[state]?>" />

		  Rate of Refund&nbsp;
			<select name="refund_percent" class="select2">
				<?=rg_html_option($_regi['refund_percent'],$data['refund_percent'])?>
            </select>

		  <a href="#" onclick="window_open('./confirm_mail.php?agency_id=<?=$data['agency_id']?>&num=<?=$num?>&mode=admission','confirm_mail1','scrollbars=no,width=10,height=10');"><u>Admission License</u></a>&nbsp;

			<select name="ip_vb" class="select2">
				<?=rg_html_option($_regi['ip_vb'],$data['ip_vb'])?>
            </select>

		  <a href="#" onclick="window_open('./confirm_mail.php?agency_id=<?=$data['agency_id']?>&num=<?=$num?>&mode=confirmation','confirm_mail2','scrollbars=no,width=10,height=10');"><u>Confirmation of Pickup</u></a>&nbsp;
			<select name="pick_up" class="select2">
				<?=rg_html_option($_regi['pick_up'],$data['pick_up'])?>
            </select>

		</td>
    </tr>

	<tr>
		<td colspan="4" bgcolor="#FFFFFF" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle6.gif" /></td>
	</tr>

	<tr>
      <td colspan="2" class="tit11_cb" bgcolor="#d0d0d0">Student</td>
      <td colspan="2" class="tit11_cb" bgcolor="#d0d0d0">Agency</td>
    </tr>

	<tr>
      <td colspan="2" bgcolor="#FFFFFF" valign="top">
	     <table border="0" cellspacing="0" cellspacing="1" width="100%" align="center" bgcolor="#CCCCCC">

		   <tr>
            <td bgcolor="#f4f4f4" width="15%" class="tit11_rb">Registration<br />fee</td>
            <td bgcolor="#FFFFFF" width="35%" class="tit11">&nbsp;<input name="regi_cost_name" type="text" class="cc" size="10" maxlength="30" value="<?=$data['regi_cost_name']?>"/> <?if($data[international_money] == 1){?>$<?}?><input name="regi_cost" type="text" class="cc" size="10" maxlength="30"  value="<?=$data['regi_cost']?>"/><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>
           <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>
           <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Tuition fee</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['class_cost'])?><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>	
           <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>			   
           <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Dormitory fee</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['dorm_cost'])?><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>

		    <?if($cost_st_date >= $data[regi_date]){?>
			   <tr>
				 <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
			   </tr>

			   <tr>
				<td bgcolor="#f4f4f4" class="tit11_rb">SSP</td>
				<td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['ssp_cost'])?><?if($data[international_money] != 1){?>��<?}?></td>
			   </tr>
			   <tr>
				 <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
			   </tr>
		   <?}?>

		   <?if($elect_st_date >= $data[regi_date]){?>
			   <tr>
				<td bgcolor="#f4f4f4" class="tit11_rb">���⼼</td>
				<td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['elect_fee'])?><?if($data[international_money] != 1){?>��<?}?></td>
			   </tr>	
		   <?}?>

		   <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>	
		   <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Discount</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<input name="event_sale_name" type="text" class="cc" size="10" maxlength="30"  value="<?=$data['event_sale_name']?>"/> <?if($data[international_money] == 1){?>$<?}?><input name="event_sale" type="text" class="cc" size="7" maxlength="30" value="<?=$data[event_sale]?>"/><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>

		   <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>	
		   <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Addition</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<input name="addition_fee_name" type="text" class="cc" size="10" maxlength="30"  value="<?=$data['addition_fee_name']?>"/> <?if($data[international_money] == 1){?>$<?}?><input name="addition_fee" type="text" class="cc" size="7" maxlength="30" value="<?=$data[addition_fee]?>"/><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>

		   <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>
		   <tr>
            <td bgcolor="#f4f4f4" height="42" class="tit11_rb">Total</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data[total_cost])?><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>		   
		</table>	  
	  </td>

	  <td colspan="2" bgcolor="#FFFFFF">
	     <table border="0" cellspacing="0" cellspacing="1" width="100%" align="center" bgcolor="#CCCCCC">  
	       <!--tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">��Ϻ�</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<?=number_format($data['comm_regi_cost'])?>��</td>
           </tr-->
	       <tr>
            <td bgcolor="#f4f4f4" width="15%" class="tit11_rb">Registration fee</td>
            <td bgcolor="#FFFFFF" width="35%" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><input name="comm_regi_cost" type="text" class="cc" size="10" maxlength="30"  value="<?=$data['comm_regi_cost']?>"/><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>
		   <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>		   
           <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Commission</td>
            <td bgcolor="#FFFFFF" class="tit11">
				<?if($data[state] == 7){?>
					&nbsp;<?if($data[international_money] == 1){?>$<?}?><input name="agency_comm" type="text" class="cc" size="10" maxlength="30" value="<?=$data['agency_comm']?>"/><?if($data[international_money] != 1){?>��<?}?></td>
				<?}else{?>
					<input type="hidden" name="agency_comm" value="<?=$data[agency_comm]?>" />
					&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['agency_comm'])?><?if($data[international_money] != 1){?>��<?}?></td>
				<?}?>
		   </tr>	
	       <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>		   
           <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">EX_Commission</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<input name="ev_ag_co_name" type="text" class="cc" size="10" maxlength="30"  value="<?=$data['ev_ag_co_name']?>"/> <?if($data[international_money] == 1){?>$<?}?><input name="ev_ag_comm" type="text" class="cc" size="7" maxlength="30"  value="<?=$data['ev_ag_comm']?>"/><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>	
           <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>	
	       <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Price of NET</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['agency_cost'])?><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>
           <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>

	       <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Additional Fee</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<input name="ag_add_fee_name" type="text" class="cc" size="10" maxlength="30"  value="<?=$data['ag_add_fee_name']?>"/> <?if($data[international_money] == 1){?>$<?}?><input name="ag_add_fee" type="text" class="cc" size="7" maxlength="30"  value="<?=$data['ag_add_fee']?>"/><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>
           <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>

		   <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Payment</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['bankin_expenses'])?><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>	
           <tr>
             <td height="1" colspan="2" bgcolor="#D0D0D0"></td>
           </tr>	
		   <tr>
            <td bgcolor="#f4f4f4" class="tit11_rb">Unpaid balance</td>
            <td bgcolor="#FFFFFF" class="tit11">&nbsp;<?if($data[international_money] == 1){?>$<?}?><?=number_format($data['agency_cost']-$data['bankin_expenses'])?><?if($data[international_money] != 1){?>��<?}?></td>
           </tr>
        </table>
	  </td>
    </tr>
</table>

<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#ffffff">

	<tr><td height="5"></td></tr>

	<tr>
		<td align="right">
			<? if($data['reg_confirm_admin'] != ''){ ?>
				<strong>Confirmation Of Registration: <?=rg_date($data[reg_confirm_date],'%y.%m.%d')?> / <?=$data['reg_confirm_admin']?></strong>
			<? }else{ ?>
				<img onClick="reg_confirm('<?=$data[sname]?>','<?=number_format($data['agency_cost'])?>','<?=$_mb[mb_name]?>')" src="./images/reconfirm_btn.gif" onFocus="this.blur()" style="cursor:hand" border="0">
			<? } ?>
		</td>
	</tr>
</table>
<br>

<!--�Ա�Ȯ��:�ְ���, �����, �׷��̽�, ������, ũ����, ������, ���̸�, �����,������ ���� �ο�-->
<? if($_mb['mb_id'] == 'cbsangel' Or $_mb['mb_id'] == 'webadmin2' Or $_mb['mb_id'] == 'ciagrace' Or $_mb['mb_id'] == 'ciakohei' Or $_mb['mb_id'] == 'ciachrisyi' Or $_mb['mb_id'] == 'asbible' Or $_mb['mb_id'] == 'ciasimon' Or $_mb['mb_id'] == 'cebuciatw' Or $_mb['mb_id'] == 'cebucia' Or $_mb['mb_id'] == 'ciaibrahim' Or $_mb['mb_id'] == 'fairyge1529' Or $_mb['mb_id'] == 'cia_account' Or $_mb['mb_id'] == 'jjozzange' Or $_mb['mb_id'] == 'jimlyaudrey' Or $_mb['mb_id'] == 'iriscia' Or $_mb['mb_id'] == 'alanhuang' Or $_mb['mb_id'] == 'ciaweb2'){ ?>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<tr>
			<td colspan="4" bgcolor="#FFFFFF" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle8.png" /></td>
		</tr>

		<tr>
			<td bgcolor="#f4f4f4" width="30%" class="tit11_rb">State of Deposit&nbsp;&nbsp;

				<select name="admission_state" class="select2">
					<?=rg_html_option($_regi['admission_state'],$data['admission_state'])?>
				</select>

			</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Reg_fee&nbsp;
				<select name="admission_fee" class="select2">
					<?=rg_html_option($_regi['admission_fee'],$data['admission_fee'])?>
				</select>&nbsp;&nbsp;&nbsp;

				&nbsp;School Expenses&nbsp;
				<select name="school_expenses" class="select2">
					<?=rg_html_option($_regi['admission_fee'],$data['school_expenses'])?>
				</select>&nbsp;&nbsp;&nbsp;

				&nbsp;Others&nbsp;
				<select name="etc_expenses" class="select2">
					<?=rg_html_option($_regi['etc_expenses'],$data['etc_expenses'])?>
				</select>

				<!--&nbsp;<textarea class="text" rows="4" name="admission_memo" cols="40"><?=$data['admission_memo']?></textarea-->

			</td>
		</tr>

	</table><br>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<tr>
			<td colspan="4" bgcolor="#6ce5c7" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle9.png" /></td>
		</tr>

		<tr>
			<td bgcolor="#f4f4f4" class="tit11_rb" width="30%">Tuition fee sent from Agency</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Amount&nbsp;
				<input name="bankout_expenses" value="<?=$data['bankout_expenses']?>" type="text" class="cc" size="10" maxlength="10">

				<select name="out_money" class="select2">
					<?=rg_html_option($_regi['in_money'],$data['out_money'])?>
				</select>&nbsp;&nbsp;

				&nbsp;Date&nbsp;
				<input name="bankout_date" value="<?=$data['bankout_date']?>" type="text" class="cc"  size="10" maxlength="10" id="bankout_date">&nbsp;&nbsp;

				&nbsp;Depositor&nbsp;
				<input name="bankout_name" value="<?=$data['bankout_name']?>" type="text" class="cc" size="10" maxlength="10"><br />

				&nbsp;<textarea class="text" rows="3" name="bankout_memo" cols="80"><?=$data['bankout_memo']?></textarea>

			</td>
		</tr>

	</table><br>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<tr>
			<td colspan="4" bgcolor="#708aec" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle7.png" /></td>
		</tr>

		<tr>
			<td bgcolor="#f4f4f4" class="tit11_rb" width="30%">Tuition fee received at CIA</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Amount&nbsp;
				<input name="bankin_expenses" value="<?=$data['bankin_expenses']?>" type="text" class="cc" size="10" maxlength="10">

				<select name="in_money" class="select2">
					<?=rg_html_option($_regi['in_money'],$data['in_money'])?>
				</select>&nbsp;&nbsp;

				&nbsp;Account&nbsp;
				<select name="deposit_account" class="select2">
					<?=rg_html_option($_regi['deposit_account'],$data['deposit_account'])?>
				</select>&nbsp;&nbsp;

				&nbsp;Date&nbsp;
				<input name="bankin_date" value="<?=$data[bankin_date]?>" type="text" class="cc"  size="10" maxlength="10" id="bankin_date">&nbsp;&nbsp;

				<!--&nbsp;Depositor&nbsp;
				<input name="bankin_name" value="<?=$data[bankin_name]?>" type="text" class="cc" size="10" maxlength="10">&nbsp;&nbsp;-->

				&nbsp;<textarea class="text" rows="3" name="deposit_memo" cols="80"><?=$data['deposit_memo']?></textarea>

			</td>
		</tr>

	</table><br>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<tr>
			<td bgcolor="#f4f4f4" class="tit11_rb" width="30%">Money differences (Commission)</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Amount
				<?
					$bankin_expenses_ext = str_replace(",","",$data['bankin_expenses']);
					$bankout_expenses_ext = str_replace(",","",$data['bankout_expenses']);
				?>
				<?=number_format($bankin_expenses_ext-$bankout_expenses_ext)?>

				<?if($data[in_money] != 0){ ?>
					<?=$_regi['in_money'][$data['in_money']]?>
				<? } ?>
			</td>
		</tr>

	</table>

<? }else{ ?>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<input type="hidden" name="admission_state" value='<?=$data['admission_state']?>'>
		<input type="hidden" name="admission_fee" value='<?=$data['admission_fee']?>'>
		<input type="hidden" name="school_expenses" value='<?=$data['school_expenses']?>'>
		<input type="hidden" name="etc_expenses" value='<?=$data['etc_expenses']?>'>
		<!--input type="hidden" name="admission_memo" value='<?=$data['admission_memo']?>'-->

		<input type="hidden" name="deposit_account" value='<?=$data['deposit_account']?>'>
		<input type="hidden" name="bankin_date" value='<?=$data['bankin_date']?>'>
		<input type="hidden" name="bankin_name" value='<?=$data['bankin_name']?>'>
		<input type="hidden" name="bankin_expenses" value='<?=$data['bankin_expenses']?>'>
		<input type="hidden" name="in_money" value='<?=$data['in_money']?>'>
		<!--input type="hidden" name="deposit_memo" value='<?=$data['deposit_memo']?>'-->

		<input type="hidden" name="bankout_expenses" value='<?=$data['bankout_expenses']?>'>
		<input type="hidden" name="out_money" value='<?=$data['out_money']?>'>
		<input type="hidden" name="bankout_date" value='<?=$data['bankout_date']?>'>
		<input type="hidden" name="bankout_name" value='<?=$data['bankout_name']?>'>
		<input type="hidden" name="bankout_memo" value='<?=$data['bankout_memo']?>'>

		<tr>
			<td colspan="4" bgcolor="#e2e2e2" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle8.png" /></td>
		</tr>

		<tr>
			<td bgcolor="#f4f4f4" width="30%" class="tit11_rb">State of Deposit : 
				<?=$_regi['admission_state'][$data[admission_state]]?>
			</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Reg_fee : 
				<?=$_regi['admission_fee'][$data['admission_fee']]?>&nbsp;&nbsp;&nbsp;

				&nbsp;School Expenses : 
				<?=$_regi['admission_fee'][$data['school_expenses']]?>&nbsp;&nbsp;&nbsp;

				&nbsp;Others : 
				<?=$_regi['etc_expenses'][$data['etc_expenses']]?>

				<!--&nbsp;<textarea class="text" rows="4" name="admission_memo" cols="40"><?=$data['admission_memo']?></textarea-->

			</td>
		</tr>

	</table><br>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<tr>
			<td colspan="4" bgcolor="#6ce5c7" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle9.png" /></td>
		</tr>

		<tr>
			<td bgcolor="#f4f4f4" class="tit11_rb" width="30%">Tuition fee sent from Ageny</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Amount :
				<? if($data['bankout_expenses'] == ''){ ?>
					no data
				<? }else{ ?>
					<? if($data['out_money'] == '1'){ ?>
						<?=number_format($data['bankout_expenses'])?>
					<? }else{ ?>
						<?=$data['bankout_expenses']?>
					<? } ?>
				<? } ?>

				<? if($data['out_money'] == '0'){ ?>
				,&nbsp;Currency : no data,&nbsp;&nbsp;
				<? }else{ ?>
					<?=$_regi['out_money'][$data[out_money]]?>,&nbsp;&nbsp;
				<? } ?>

				&nbsp;Date : 
				<? if($data['bankout_date'] == ''){ ?>
					no data,&nbsp;&nbsp;
				<? }else{ ?>
					<?=$data['bankout_date']?>,&nbsp;&nbsp;
				<? } ?>

				&nbsp;Depositor :
				<? if($data['bankout_name'] == ''){ ?>
					no data<br />
				<? }else{ ?>
					<?=$data['bankout_name']?><br />
				<? } ?>

				&nbsp;<textarea class="text" rows="3" name="bankout_memo" cols="80" readonly><?=$data['bankout_memo']?></textarea>

			</td>
		</tr>

	</table><br>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<tr>
			<td colspan="4" bgcolor="#708aec" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle7.png" /></td>
		</tr>

		<tr>
			<td bgcolor="#f4f4f4" class="tit11_rb" width="30%">Tuition fee received at CIA</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Amount :
				<? if($data[bankin_expenses] == ''){ ?>
					no data
				<? }else{ ?>
					<? if($data[in_money] == '1'){ ?>
						<?=number_format($data['bankin_expenses'])?>
					<? }else{ ?>
						<?=$data['bankin_expenses']?>
					<? } ?>
				<? } ?>

				<? if($data[in_money] == '0'){ ?>
				,&nbsp;Currency : no data,&nbsp;&nbsp;
				<? }else{ ?>
					<?=$_regi['in_money'][$data[in_money]]?>,&nbsp;&nbsp;
				<? } ?>

				&nbsp;Account : 
				<? if($data['deposit_account'] == '0'){ ?>&nbsp;&nbsp;
					no data<br />
				<? }else{ ?>
					<?=$_regi['deposit_account'][$data['deposit_account']]?>&nbsp;&nbsp;
				<? } ?>

				&nbsp;Date : 
				<? if($data['bankin_date'] == ''){ ?>
					no data<br>
				<? }else{ ?>
					<?=$data['bankin_date']?><br>
				<? } ?>

				&nbsp;<textarea class="text" rows="3" name="deposit_memo" cols="80" readonly><?=$data['deposit_memo']?></textarea>

			</td>
		</tr>

	</table><br>

	<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">

		<tr>
			<td bgcolor="#f4f4f4" class="tit11_rb" width="30%">Money differences (Commission)</td>

			<td bgcolor="#FFFFFF" width="70%">

				&nbsp;Amount
				<?
					$bankin_expenses_ext = str_replace(",","",$data['bankin_expenses']);
					$bankout_expenses_ext = str_replace(",","",$data['bankout_expenses']);
				?>
				<?=number_format($bankin_expenses_ext-$bankout_expenses_ext)?>

				<?if($data[in_money] != 0){ ?>
					<?=$_regi['in_money'][$data['in_money']]?>
				<? } ?>
			</td>
		</tr>

	</table>

<? } ?>
<!--���� ����� �Ա�Ȯ��-->

<!--ĵ��, ����,ȯ��, ü����, ����, �� ��ư-->
<table width="800" border="0" cellpadding="0" cellspacing="0">

	<tr><td height="5"></td></tr>

	<tr>
		<td bgcolor="#FFFFFF" align="left" width="51%">
			<!--input type="image" src="./images/cancel_btn.gif" onClick="check_userinfo(1); return false;">
			<input type="image" src="./images/extention_btn.gif" onClick="check_userinfo(2); return false;">
			<input type="image" src="./images/refund_btn.gif" onClick="check_userinfo(3); return false;"-->

			<img src="./images/cancel_btn.gif" onClick="window.open('./student_remark_popup.php?num=<?=$num?>&mode=cancel','cancel_popup','width=700 height=280 menubar=no status=no')" style="cursor:hand">
			<img src="./images/extention_btn.gif" onClick="window.open('./student_remark_popup.php?num=<?=$num?>&mode=extention','extention_popup','width=700 height=280 menubar=no status=no')" style="cursor:hand">
			<img src="./images/refund_btn.gif" onClick="window.open('./student_remark_popup.php?num=<?=$num?>&mode=refund','refund_popup','width=700 height=280 menubar=no status=no')" style="cursor:hand">
			<img src="./images/change_btn.gif" onClick="window.open('./student_remark_popup.php?num=<?=$num?>&mode=change','change_popup','width=700 height=280 menubar=no status=no')" style="cursor:hand">

	<? if($_mb['mb_id'] == 'cbsangel'){ ?>
			<!--MOVE ������ ����-->
			<!--input type="image" src="./images/change_btn.gif" onClick="check_userinfo(4); return false;"-->
	<? } ?>
			<!--input type="image" src="./images/refund_btn.gif" onClick="check_userinfo(5); return false;"-->
		</td>

		<td bgcolor="#FFFFFF" align="right" width="49%">
			<input border="0" src="./images/confirm_btn.gif" type="image" />&nbsp;<a href="javascript:history.back();"><img src="./images/back_btn.gif" border="0" id="I1" /></a>
		</td>

	</tr>
</table>
<br>

</form>
<!--ĵ��, ����,ȯ��, ü����, ����, �� ��ư-->

<!--�к�۱�����-->
<br>
<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
<form name="form_comment" method="post" action='regi_deposit_memo.php' autocomplete=off enctype="multipart/form-data">
<input type="hidden" name="num" value='<?=$num?>'>
<input type="hidden" name="cmt_id" value='<?=$_mb[mb_id]?>'>
<input type="hidden" name="cmt_name" value='<?=$_mb[mb_name]?>'>
<input type="hidden" name="cmt_name" value='<?=$_mb[mb_name]?>'>
<input type="hidden" name="student_id" value='<?=$data[student_id]?>'>
<input type="hidden" name="insert_gubun" value='2'>


<tr>
	<td colspan="2" bgcolor="#6ce5c7" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle10.png" /></td>
</tr>

<tr>
	<td bgcolor="#FFFFFF" width="720">
		<textarea rows="2" name="final_deposit_memo" style="width:100%" required itemname="content" style="border-width:1; border-color:rgb(136,136,136); border-style:solid;"></textarea>

		<input name="deposit_photo" type="file" class="cc" style="width:100%;">
	</td>

	<td width="80" height="100%" bgcolor="#FFFFFF">
		<input type="submit" value='INPUT' style="font-style:normal; font-size:12px; color:white; background-color:#404040; border-width:1px; border-color:rgb(221,221,221); border-style:solid; height:100%;width:100%">
	</td>
</tr>

</form>
</table> 

<?
	$ou_list = new $rs_class($dbcon);	
	$ou_list->clear();
	$ou_list->set_table($_table['deposit_memo']);
    $ou_list->add_where("cmt_num = '$num'"); 
	$ou_list->add_where("insert_gubun = 2");
	$ou_list->select();
	$ou_data=$ou_list->fetch();

	$rs_list = new $rs_class($dbcon);
	$rs_list->clear();
	$rs_list->set_table($_table['deposit_memo']);
	$rs_list->add_where("cmt_num = '$num'");
	$rs_list->add_where("insert_gubun = 2");
	$rs_list->add_order("num DESC");

	$page_info=$rs_list->select_list($page,20,10);
?>
<table width="800" border=1 cellpadding=0 cellspacing=0 bordercolorlight="#E1E1E1" bordercolordark="white">
<?	
	$rs_list->set_table($_table['deposit_memo']);	
	$no = $page_info['start_no'];
	$re_no = $no-1;
	while($cdata=$rs_list->fetch()) {
	$no--;
?> 
	<tr> 
		<td width="10%" bgcolor="#c8cbe5" style='padding:3px; padding-left:10px; padding-right:10px;'> 
			[<?=rg_date($cdata[reg_date],'%Y/%m/%d')?>]<br>
			<?=$cdata[cmt_name]?>
		</td>

		<td width="80%" bgcolor="#FFFFFF" class=bbs style='padding:3px; padding-left:10px; padding-right:10px;'>

			<?if($cdata[deposit_photo] != ''){?>
				<a href="../file/deposit_photo/<?=$num?>/<?=$cdata[deposit_photo]?>" target="_blank" ><img src="../file/deposit_photo/<?=$num?>/<?=$cdata[deposit_photo]?>" style="border:1 solid #cfcfcf" width="60" height="40"></a>
			<?}?>

			<?=$cdata[final_deposit_memo]?> &nbsp;&nbsp;
			<?if($_mb[mb_level] > "10"){?>
			<a href="./regi_deposit_memo.php?&mode=delete&cmt_num=<?=$cdata[num]?>&deposit_photo=<?=$cdata[deposit_photo]?>&num=<?=$num?>"><img src="../img/sbt_del.gif" alt="����" border="0"></a> <br> <div align="right"><? } ?></div>

		</td>

		<? if($re_no == $no){ ?>
			<td width="10%" style='padding:3px; padding-left:10px; padding-right:10px;' rowspan="<?=$re_no?>" bgcolor="#d1ffeb"> 

				<? if($ou_data[reg_date] != ''){ ?>
					<? if($data['final_deposit_admin'] == ''){ ?>
						<img onClick="final_deposit_admin('<?=$data[sname]?>','<?=$_mb[mb_name]?>')" src="./images/final_confirm.gif" onFocus="this.blur()" style="cursor:hand" border="0">
					<? }elseif($data['final_deposit_admin'] != ''){ ?>
						[<?=rg_date($data[final_deposit_date],'%Y/%m/%d')?>]<br /><?=$data[final_deposit_admin]?>)
					<? } ?>
				<? } ?>

			</td>
		<? } ?>
	</tr>
<? }?>
</table>
<!--�к�۱ݳ���-->

<!--�к��Ա�����-->
<br>
<table width="800" border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
<form name="form_comment" method="post" action='regi_deposit_memo.php' autocomplete=off enctype="multipart/form-data">
<input type="hidden" name="num" value='<?=$num?>'>
<input type="hidden" name="cmt_id" value='<?=$_mb[mb_id]?>'>
<input type="hidden" name="cmt_name" value='<?=$_mb[mb_name]?>'>
<input type="hidden" name="cmt_name" value='<?=$_mb[mb_name]?>'>
<input type="hidden" name="student_id" value='<?=$data[student_id]?>'>
<input type="hidden" name="insert_gubun" value='1'>

<tr>
	<td colspan="2" bgcolor="#708aec" style="padding: 5 5 5 5;"><img src="../img/agency/regi_stitle12.png" /></td>
</tr>

<tr>
	<td bgcolor="#FFFFFF" width="720">
		<textarea rows="2" name="final_deposit_memo" style='width:100%' required itemname='content' style='border-width:1; border-color:rgb(136,136,136); border-style:solid;'></textarea>

		<input name='deposit_photo' type="file" class="cc" style="width:100%;">
	</td>

	<td width="80" height="100%" bgcolor="#FFFFFF">
		<input type=submit value='INPUT' style="font-style:normal; font-size:12px; color:white; background-color:#404040; border-width:1px; border-color:rgb(221,221,221); border-style:solid; height:100%;width:100%">
	</td>
</tr>

</form>
</table> 

<?
	$de_list = new $rs_class($dbcon);	
	$de_list->clear();
	$de_list->set_table($_table['deposit_memo']);
    $de_list->add_where("cmt_num = '$num'"); 
	$de_list->add_where("insert_gubun = 1");
	$de_list->select();
	$de_data=$de_list->fetch();

	$rs_list = new $rs_class($dbcon);
	$rs_list->clear();
	$rs_list->set_table($_table['deposit_memo']);
	$rs_list->add_where("cmt_num = '$num'");
	$rs_list->add_where("insert_gubun = 1");
	$rs_list->add_order("num DESC");

	$page_info=$rs_list->select_list($page,20,10);
?>
<table width="800" border=1 cellpadding=0 cellspacing=0 bordercolorlight="#E1E1E1" bordercolordark="white">
<?	
	$rs_list->set_table($_table['deposit_memo']);	
	$no = $page_info['start_no'];
	$re_no = $no-1;
	while($cdata=$rs_list->fetch()) {
	$no--;
?> 
	<tr> 
		<td width="10%" bgcolor="#c8cbe5" style='padding:3px; padding-left:10px; padding-right:10px;'> 
			[<?=rg_date($cdata[reg_date],'%Y/%m/%d')?>]<br>
			<?=$cdata[cmt_name]?>
		</td>

		<td width="80%" bgcolor="#FFFFFF" class=bbs style='padding:3px; padding-left:10px; padding-right:10px;'>

			<?if($cdata[deposit_photo] != ''){?>
				<a href="../file/deposit_photo/<?=$num?>/<?=$cdata[deposit_photo]?>" target="_blank" ><img src="../file/deposit_photo/<?=$num?>/<?=$cdata[deposit_photo]?>" style="border:1 solid #cfcfcf" width="60" height="40"></a>
			<?}?>

			<?=$cdata[final_deposit_memo]?> &nbsp;&nbsp;
			<?if($_mb[mb_level] > "10"){?>
			<a href="./regi_deposit_memo.php?&mode=delete&cmt_num=<?=$cdata[num]?>&deposit_photo=<?=$cdata[deposit_photo]?>&num=<?=$num?>"><img src="../img/sbt_del.gif" alt="����" border="0"></a> <br> <div align="right"><? } ?></div>

		</td>

		<? if($re_no == $no){ ?>
			<td width="10%" style='padding:3px; padding-left:10px; padding-right:10px;' rowspan="<?=$re_no?>" bgcolor="#d1ffeb"> 

				<? if($de_data[reg_date] != ''){ ?>
					<? if($data['final_deposit_admin'] == ''){ ?>
						<img onClick="final_deposit_admin('<?=$data[sname]?>','<?=$_mb[mb_name]?>')" src="./images/final_confirm.gif" onFocus="this.blur()" style="cursor:hand" border="0">
					<? }elseif($data['final_deposit_admin'] != ''){ ?>
						[<?=rg_date($data[final_deposit_date],'%Y/%m/%d')?>]<br /><?=$data[final_deposit_admin]?>)
					<? } ?>
				<? } ?>

			</td>
		<? } ?>
	</tr>
<? }?>
</table>
<!--�к��Աݳ���-->

<? if($data[connect_num] == 1){ ?>
<br>
<table width="800" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="site_content">

	<tr>
		<td colspan="2" align="center"><b>Student History (Extension & Refund)</b></td>
	</tr>

	<tr>
		<td align="left">

			<?
				$rs_list = new $rs_class($dbcon);
				$rs_list->clear();
				$rs_list->set_table($_table['regi']);
				$rs_list->add_where("connect_num = '$data[connect_num]'"); 
				$rs_list->add_order("num DESC");

				$page_info=$rs_list->select_list($page,20,10);
			?>

			<?
				$no = $page_info['start_no'];
				while($cdata=$rs_list->fetch()) {
				$no--;
			?>

				&nbsp;<? if($cdata[num] == $num){ ?><b><? } ?><a href="./regi_edit_sparta.php?<?=$_get_param[3]?>&mode=modify&num=<?=$cdata[num]?>&amp;regi1=<?=$regi1?>&amp;regi2=<?=$regi2?>&amp;abrod_date_str1=<?=$abrod_date_str1?>&amp;abrod_date_str2=<?=$abrod_date_str2?>&amp;arrival_date_str1=<?=$arrival_date_str1?>&amp;arrival_date_str2=<?=$arrival_date_str2?>&amp;stradmission_state=<?=$stradmission_state?>&amp;agency_register=<?=$agency_register?>"><?=$no?>. ����� : <?=rg_date($cdata[regi_date],'%y-%m-%d')?> / <?=$_regi['state'][$cdata[state]]?> / <?=$cdata[sname]?></a><? if($cdata[num] == $num){ ?></b><? } ?><br />

			<? }?>

		</td>
	</tr>
</table>
<? } ?>
<br>

<?
	$rs_list = new $rs_class($dbcon);	
	$rs_list->clear();
	$rs_list->set_table($_table['regi_comment']);
    $rs_list->add_where("cmt_num = '$num'"); 
	$rs_list->add_order("num DESC");
?>
<table width="800" border="1" cellpadding="0" cellspacing=0 bordercolorlight="#E1E1E1" bordercolordark="white">
<?	
	$rs_list->set_table($_table['regi_comment']);
	$no = $page_info['start_no'];
	while($cdata=$rs_list->fetch()) {
	$no--;
    $cdata[reg_date] = rg_date($cdata[reg_date],'%Y/%m/%d');
?> 
	<tr> 
		<td width="19%" bgcolor="#FFFFFF" style='padding:3px; padding-left:10px; padding-right:10px;'> 
			[<?=$cdata[reg_date]?>]<br>
			<?=$cdata[cmt_name]?>
		</td>

		<td bgcolor="#FFFFFF" class=bbs style='padding:3px; padding-left:10px; padding-right:10px;'>
			<?=$cdata[cmt_comment]?> &nbsp;&nbsp; 
			<?if($mb[mb_level] == "10"){?>
			<a href="./regi_comment_edit.php?&mode=del&cmt_num=<?=$cdata[num]?>&num=<?=$num?>"><img src=<?=$skin_site_path."img/del.gif"?> alt="����" border="0"></a> <br> <div align="right"><? } ?></div>
		</td>
  </tr> 
<? }?>
</table>
<? include("admin.footer_new.php"); ?>
<? include("_footer.php"); ?>