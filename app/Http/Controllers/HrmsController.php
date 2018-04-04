<?php namespace App\Http\Controllers;

use App\Http\Controllers\HrmsMainController;
use App\Models\Classification;
use App\Models\ClassificationLevel;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\EmployeePosition;
use App\Models\EmployeeContributionNumber;
use App\Models\Person;
use App\Models\Program;
use App\Models\SubjectOffered;
use App\Models\Teacher;
use App\Models\TeacherCategory;
use App\Models\TeacherClassification;
use App\Models\TeacherDegree;
use App\Models\TeacherSubject;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\TeacherEditRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config; 
use Input;   
use Datatables;
use Hash;
use Excel;

class HrmsController extends Controller {
    
    public function index()
    {

        $employee_type_list = EmployeeType::all();
        $teacher_count = Teacher::leftJoin('employee', 'teacher.employee_id', '=', 'employee.id')
                    ->leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('gen_user', 'person.id', '=', 'gen_user.person_id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('teacher.id','!=',0)
                    ->where('gen_user_role.gen_role_id',1)
                    ->where('person.is_active',1)
                    // ->where('employee.employee_type_id','=',3)
                    ->count();

        $employee_count = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('gen_user', 'person.id', '=', 'gen_user.person_id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('person.is_active',1)
                    // ->where('teacher.id','!=',0)
                    // ->where('gen_user_role.gen_role_id',1)
                    // ->where('employee.employee_type_id','=',3)
                    ->count();

        $international_count = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('gen_user', 'person.id', '=', 'gen_user.person_id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('person.is_active',1)
                    ->where('employee.employee_type_id','=',1)
                    ->count();

        $admin_count = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('gen_user', 'person.id', '=', 'gen_user.person_id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('person.is_active',1)
                    ->where('employee.employee_type_id','=',2)
                    ->count();

        $acad_lead_count = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('gen_user', 'person.id', '=', 'gen_user.person_id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('person.is_active',1)
                    ->where('employee.employee_type_id','=',3)
                    ->where('person.last_name',"!=", "")
                    ->where('person.last_name',"!=", "Administrator")
                    ->where('position.position_name',"=", "Academic Leader")
                    ->orWhere('position.position_name',"=", "Academic Team Leader")
                    ->orWhere('position.position_name',"=", "IELTS/ Academic Leader")
                    ->count();

        $acad_sup_count = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('gen_user', 'person.id', '=', 'gen_user.person_id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('person.is_active',1)
                    ->where('employee.employee_type_id','=',3)
                    ->where('position.position_name',"=", "Academic Administrative Assistant")
                    ->count();

        $resigned_count = Employee::leftJoin('person', 'employee.person_id', '=', 'person.id')
                    ->leftJoin('gen_user', 'person.id', '=', 'gen_user.person_id')
                    ->leftJoin('gen_user_role', 'gen_user_role.gen_user_id', '=', 'gen_user.id')
                    ->leftJoin('position', 'employee.position_id', '=', 'position.id')
                    ->leftJoin('department', 'position.department_id', '=', 'department.id')
                    ->where('person.is_active',0)
                    ->count();

        return view('hrms.index',compact('employee_type_list','teacher_count','employee_count','international_count','admin_count','resigned_count','acad_lead_count','acad_sup_count'));
    }

    public function reportEmployeeList()
    {

        $employee_type_list = EmployeeType::all();
        return view('hrms/report/employee_list',compact('employee_type_list'));
    }

    public function Get_Date_Difference($start_date, $end_date)
    {
        $diff = abs(strtotime($end_date) - strtotime($start_date));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        // echo $years.' Years '.$months.' Month '.$days.' Days';
        return $years.' Years '.$months.' Months';
    }

    public function reportEmployeeListExcel()
    {

        Excel::create('Employee List', function($excel) {

            $employee_list = Employee::leftJoin('person','employee.person_id','=','person.id')
                            ->leftJoin('employee_type','employee.employee_type_id','=','employee_type.id')
                            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
                            ->leftJoin('position','employee.position_id','=','position.id')
                            ->where('person.last_name','!=','')
                            ->where('person.is_active','!=',0)
                            ->select(['employee.id','person.first_name','person.middle_name','person.last_name','employee.date_hired','employee.rate','person.nickname','person.address','person.birthdate','person.contact_no','employment_status.employment_status_name','employee_type.id as employee_type_id','employee.contract_from','employee.contract_to','position.department_id'])
                            ->orderBy('person.last_name','ASC')
                            ->get();

            $excel->sheet('Admin Staff', function($sheet) use($employee_list) {

                $row = 2;
                $sheet->setWidth('A', 5);
                $sheet->setAutoSize(array(
                    'B','C','D','E','F','K',
                ));
                $sheet->setWidth('G', 10);
                $sheet->setWidth('H', 10);
                $sheet->setWidth('I', 10);
                $sheet->setWidth('J', 10);
                $sheet->setWidth('L', 25);
                $sheet->setWidth('M', 25);
                $sheet->setWidth('N', 25);
                $sheet->setWidth('O', 25);
                $sheet->setWidth('P', 25);
                $sheet->setWidth('Q', 20);
                $sheet->setWidth('R', 20);
                $sheet->setWidth('S', 20);
                $sheet->setWidth('T', 20);
                $sheet->setWidth('U', 20);

                $sheet->setAllBorders('thin');
                $sheet->setHeight($row, 15);

                $sheet->row($row++, array(
                       'No.',
                       'FULL NAME',
                       'NICKNAME',
                       'ADDRESS',
                       'BIRTH DATE',
                       'CONTACT #',
                       'REQUIREMENTS','','','',
                       'DATE HIRED',
                       'LENGTH OF SERVICE',
                       'STATUS',
                       'CERTIFICATE/LICENSE',
                       'SALARY','CONTRACT',
                       '',
                       'GOVERNMENT CONTRIBUTION NUMBERS',
                       '','','',

                ));

                $sheet->setHeight($row, 25);

                $sheet->row($row++, array(
                       '',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'NSO','Diploma','TOR','NBI',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'PROBATIONARY','REGULAR',
                       'SSS','PHIC','HDMF',
                       'TIN',

                ));

                $count = 1;

                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:C3');
                $sheet->mergeCells('D2:D3');
                $sheet->mergeCells('E2:E3');
                $sheet->mergeCells('F2:F3');
                $sheet->mergeCells('G2:J2');
                $sheet->mergeCells('K2:K3');
                $sheet->mergeCells('L2:L3');
                $sheet->mergeCells('M2:M3');
                $sheet->mergeCells('N2:N3');
                $sheet->mergeCells('O2:O3');
                $sheet->mergeCells('P2:Q2');
                $sheet->mergeCells('R2:U2');

                $sheet->cells('A2:F2', function($cells) {
                    $cells->setBackground('#F8CBAD');
                });

                $sheet->cells('G2:J2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('G3:J3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('K2', function($cells) {
                    $cells->setBackground('#F4B084');
                });

                $sheet->cells('L2', function($cells) {
                    $cells->setBackground('#8EA9DB');
                });

                $sheet->cells('M2', function($cells) {
                    $cells->setBackground('#ED7D31');
                });

                $sheet->cells('N2', function($cells) {
                    $cells->setBackground('#0070C0');
                });

                $sheet->cells('O2', function($cells) {
                    $cells->setBackground('#CC99FF');
                });

                $sheet->cells('P2:Q2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('P3:Q3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('R3:U3', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('R2:U2', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('A2:Z2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->cells('A3:Z3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $department_list = Department::where('department.employee_type_id',2)
                        ->orderBy('department.department_name','ASC')
                        ->get();

                foreach ($department_list as $department) {

                  $count_employee = Employee::join('position','employee.position_id','=','position.id')
                            ->where('position.department_id',$department->id)
                            ->select('employee.person_id')
                            ->get()
                            ->count();


                  if($count_employee != 0)
                  {

                    $sheet->cells('A'.$row.':U'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground('#000000');
                        $cells->setFontColor('#ffffff');
                    });

                    $sheet->setHeight($row, 20);

                    $sheet->row($row++, array('',$department->department_name));
                  // if($count_employee)
                  // {
                  //   echo $count_employee->person_id."<br>";
                  // }
                  // else
                  // {
                  //   echo "0"."<br>";
                  // }
                    foreach ($employee_list as $employee) 
                    {

                        $employee_department = EmployeePosition::join('position','employee_position.position_id','=','position.id')
                                ->where('employee_position.employee_id',$employee -> id)
                                ->select(['position.department_id'])
                                ->get()
                                ->last();

                        $sss = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'SSS'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $phic = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'PHIC'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $hdmf = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'HDMF'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $tin = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'TIN'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        if($employee -> employee_type_id == 2)
                        {
                            $sheet->cells('A'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $length_of_service = $this -> Get_Date_Difference($employee -> date_hired,date('Y-m-d'));

                            if($employee -> rate > 200 && $employee -> rate < 600)
                            {
                              $rate = $employee -> rate."/day";
                            }
                            else if($employee -> rate < 200)
                            {
                              $rate = $employee -> rate."/hr";
                            }
                            else
                            {
                              $rate = $employee -> rate;
                            }

                            if($sss)
                            {
                              $sss = $sss -> employee_contribution_number;
                            }
                            else
                            {
                              $sss = "";
                            }

                            if($phic)
                            {
                              $phic = $phic -> employee_contribution_number;
                            }
                            else
                            {
                              $phic = "";
                            }

                            if($hdmf)
                            {
                              $hdmf = $hdmf -> employee_contribution_number;
                            }
                            else
                            {
                              $hdmf = "";
                            }

                            if($tin)
                            {
                              $tin = $tin -> employee_contribution_number;
                            }
                            else
                            {
                              $tin = "";
                            }

                            if($employee_department)
                            {
                              if($employee_department -> department_id == $department -> id)
                              {
                                $sheet->row($row++, array(
                                  $count++,
                                  $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                  $employee -> nickname,
                                  $employee -> address,
                                  $employee -> birthdate,
                                  $employee -> contact_no,'','','',
                                  '',
                                  $employee -> date_hired,
                                  $length_of_service,
                                  $employee -> employment_status_name,
                                  '',
                                  $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                  $employee -> contract_to,$sss,$phic,
                                  $hdmf,$tin,
                                ));
                              }
                            }
                            else
                            {
                              if($employee->department_id != "")
                              {
                                if($employee -> department_id == $department -> id)
                                {
                                  $sheet->row($row++, array(
                                    $count++,
                                    $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                    $employee -> nickname,
                                    $employee -> address,
                                    $employee -> birthdate,
                                    $employee -> contact_no,'','','',
                                    '',
                                    $employee -> date_hired,
                                    $length_of_service,
                                    $employee -> employment_status_name,
                                    '',
                                    $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                    $employee -> contract_to,$sss,$phic,
                                    $hdmf,$tin,
                                  ));
                                }
                              }
                            }
                        }

                    }
                  }
                }
            });
            
            // $excel->sheet('Academic Staff', function($sheet) use($employee_list) {

            //     $row = 2;
            //     $sheet->setWidth('A', 5);
            //     $sheet->setAutoSize(array(
            //         'B','C','D','E','F','K',
            //     ));
            //     $sheet->setWidth('G', 10);
            //     $sheet->setWidth('H', 10);
            //     $sheet->setWidth('I', 10);
            //     $sheet->setWidth('J', 10);
            //     $sheet->setWidth('L', 25);
            //     $sheet->setWidth('M', 25);
            //     $sheet->setWidth('N', 25);
            //     $sheet->setWidth('O', 25);
            //     $sheet->setWidth('P', 25);
            //     $sheet->setWidth('Q', 20);

            //     $sheet->setAllBorders('thin');
            //     $sheet->setHeight($row, 15);

            //     $sheet->row($row++, array(
            //            'No.',
            //            'FULL NAME',
            //            'NICKNAME',
            //            'ADDRESS',
            //            'BIRTH DATE',
            //            'CONTACT #',
            //            'REQUIREMENTS','','','',
            //            'DATE HIRED',
            //            'LENGTH OF SERVICE',
            //            'STATUS',
            //            'CERTIFICATE/LICENSE',
            //            'SALARY','CONTRACT',
            //            '','','','',
            //            'GOVERNMENT CONTRIBUTION NUMBERS',

            //     ));

            //     $sheet->setHeight($row, 25);

            //     $sheet->row($row++, array(
            //            '',
            //            '',
            //            '',
            //            '',
            //            '',
            //            '',
            //            'NSO','Diploma','TOR','NBI',
            //            '',
            //            '',
            //            '',
            //            '',
            //            '',
            //            'PROBATIONARY','REGULAR',
            //            '','','',
            //            '',

            //     ));

            //     $count = 1;

            //     $sheet->mergeCells('A2:A3');
            //     $sheet->mergeCells('B2:B3');
            //     $sheet->mergeCells('C2:C3');
            //     $sheet->mergeCells('D2:D3');
            //     $sheet->mergeCells('E2:E3');
            //     $sheet->mergeCells('F2:F3');
            //     $sheet->mergeCells('G2:J2');
            //     $sheet->mergeCells('K2:K3');
            //     $sheet->mergeCells('L2:L3');
            //     $sheet->mergeCells('M2:M3');
            //     $sheet->mergeCells('N2:N3');
            //     $sheet->mergeCells('O2:O3');
            //     $sheet->mergeCells('P2:Q2');

            //     $sheet->cells('A2:F2', function($cells) {
            //         $cells->setBackground('#F8CBAD');
            //     });

            //     $sheet->cells('G2:J2', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('G3:J3', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('K2', function($cells) {
            //         $cells->setBackground('#F4B084');
            //     });

            //     $sheet->cells('L2', function($cells) {
            //         $cells->setBackground('#8EA9DB');
            //     });

            //     $sheet->cells('M2', function($cells) {
            //         $cells->setBackground('#ED7D31');
            //     });

            //     $sheet->cells('N2', function($cells) {
            //         $cells->setBackground('#0070C0');
            //     });

            //     $sheet->cells('O2', function($cells) {
            //         $cells->setBackground('#CC99FF');
            //     });

            //     $sheet->cells('P2:Q2', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('P3:Q3', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('A2:Z2', function($cells) {
            //         $cells->setAlignment('center');
            //         $cells->setValignment('center');
            //     });

            //     $sheet->cells('A3:Z3', function($cells) {
            //         $cells->setAlignment('center');
            //         $cells->setValignment('center');
            //     });

            //     foreach ($employee_list as $employee) 
            //     {
            //         if($employee -> employee_type_id == 3)
            //         {

            //             $sheet->setHeight($row, 20);
            //             $sheet->cells('A'.$row.':F'.$row, function($cells) {
            //                 $cells->setAlignment('center');
            //                 $cells->setValignment('center');
            //             });

            //             $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
            //                 $cells->setAlignment('center');
            //                 $cells->setValignment('center');
            //             });


            //             $sheet->row($row++, array(
            //               $count++,
            //               $employee -> first_name." ".$employee -> middle_name." ".$employee -> last_name,
            //               $employee -> nickname,
            //               $employee -> address,
            //               $employee -> birthdate,
            //               $employee -> contact_no,'','','',
            //               '',
            //               $employee -> date_hired,
            //               '',
            //               $employee -> employment_status_name,
            //               '',
            //               '',$employee -> contract_from." - ".$employee -> contract_to,
            //               $employee -> contract_to,'','','',
            //               '',

            //             ));
            //         }
            //     }
            // });

            $excel->sheet('Academic Staff', function($sheet) use($employee_list) {

                $row = 2;
                $sheet->setWidth('A', 5);
                $sheet->setAutoSize(array(
                    'B','C','D','E','F','K',
                ));
                $sheet->setWidth('G', 10);
                $sheet->setWidth('H', 10);
                $sheet->setWidth('I', 10);
                $sheet->setWidth('J', 10);
                $sheet->setWidth('L', 25);
                $sheet->setWidth('M', 25);
                $sheet->setWidth('N', 25);
                $sheet->setWidth('O', 25);
                $sheet->setWidth('P', 25);
                $sheet->setWidth('Q', 20);
                $sheet->setWidth('R', 20);
                $sheet->setWidth('S', 20);
                $sheet->setWidth('T', 20);
                $sheet->setWidth('U', 20);

                $sheet->setAllBorders('thin');
                $sheet->setHeight($row, 15);

                $sheet->row($row++, array(
                       'No.',
                       'FULL NAME',
                       'NICKNAME',
                       'ADDRESS',
                       'BIRTH DATE',
                       'CONTACT #',
                       'REQUIREMENTS','','','',
                       'DATE HIRED',
                       'LENGTH OF SERVICE',
                       'STATUS',
                       'CERTIFICATE/LICENSE',
                       'SALARY','CONTRACT',
                       '',
                       'GOVERNMENT CONTRIBUTION NUMBERS',
                       '','','',

                ));

                $sheet->setHeight($row, 25);

                $sheet->row($row++, array(
                       '',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'NSO','Diploma','TOR','NBI',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'PROBATIONARY','REGULAR',
                       'SSS','PHIC','HDMF',
                       'TIN',

                ));

                $count = 1;

                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:C3');
                $sheet->mergeCells('D2:D3');
                $sheet->mergeCells('E2:E3');
                $sheet->mergeCells('F2:F3');
                $sheet->mergeCells('G2:J2');
                $sheet->mergeCells('K2:K3');
                $sheet->mergeCells('L2:L3');
                $sheet->mergeCells('M2:M3');
                $sheet->mergeCells('N2:N3');
                $sheet->mergeCells('O2:O3');
                $sheet->mergeCells('P2:Q2');
                $sheet->mergeCells('R2:U2');

                $sheet->cells('A2:F2', function($cells) {
                    $cells->setBackground('#F8CBAD');
                });

                $sheet->cells('G2:J2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('G3:J3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('K2', function($cells) {
                    $cells->setBackground('#F4B084');
                });

                $sheet->cells('L2', function($cells) {
                    $cells->setBackground('#8EA9DB');
                });

                $sheet->cells('M2', function($cells) {
                    $cells->setBackground('#ED7D31');
                });

                $sheet->cells('N2', function($cells) {
                    $cells->setBackground('#0070C0');
                });

                $sheet->cells('O2', function($cells) {
                    $cells->setBackground('#CC99FF');
                });

                $sheet->cells('P2:Q2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('P3:Q3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('R3:U3', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('R2:U2', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('A2:Z2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->cells('A3:Z3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $department_list = Department::where('department.employee_type_id',3)
                        ->orderBy('department.department_name','ASC')
                        ->get();

                foreach ($department_list as $department) {

                  $count_employee = Employee::join('position','employee.position_id','=','position.id')
                            ->where('position.department_id',$department->id)
                            ->select('employee.person_id')
                            ->get()
                            ->count();


                  if($count_employee != 0)
                  {
                    
                    $sheet->cells('A'.$row.':U'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground('#000000');
                        $cells->setFontColor('#ffffff');
                    });

                    $sheet->setHeight($row, 20);

                    $sheet->row($row++, array('',$department->department_name));
                  // if($count_employee)
                  // {
                  //   echo $count_employee->person_id."<br>";
                  // }
                  // else
                  // {
                  //   echo "0"."<br>";
                  // }
                    foreach ($employee_list as $employee) 
                    {

                        $employee_department = EmployeePosition::join('position','employee_position.position_id','=','position.id')
                                ->where('employee_position.employee_id',$employee -> id)
                                ->select(['position.department_id'])
                                ->get()
                                ->last();

                        $sss = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'SSS'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $phic = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'PHIC'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $hdmf = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'HDMF'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $tin = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'TIN'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        if($employee -> employee_type_id == 3)
                        {
                            $sheet->cells('A'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $length_of_service = $this -> Get_Date_Difference($employee -> date_hired,date('Y-m-d'));

                            if($employee -> rate > 200 && $employee -> rate < 600)
                            {
                              $rate = $employee -> rate."/day";
                            }
                            else if($employee -> rate < 200)
                            {
                              $rate = $employee -> rate."/hr";
                            }
                            else
                            {
                              $rate = $employee -> rate;
                            }

                            if($sss)
                            {
                              $sss = $sss -> employee_contribution_number;
                            }
                            else
                            {
                              $sss = "";
                            }

                            if($phic)
                            {
                              $phic = $phic -> employee_contribution_number;
                            }
                            else
                            {
                              $phic = "";
                            }

                            if($hdmf)
                            {
                              $hdmf = $hdmf -> employee_contribution_number;
                            }
                            else
                            {
                              $hdmf = "";
                            }

                            if($tin)
                            {
                              $tin = $tin -> employee_contribution_number;
                            }
                            else
                            {
                              $tin = "";
                            }

                            if($employee_department)
                            {
                              if($employee_department -> department_id == $department -> id)
                              {
                                $sheet->row($row++, array(
                                  $count++,
                                  $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                  $employee -> nickname,
                                  $employee -> address,
                                  $employee -> birthdate,
                                  $employee -> contact_no,'','','',
                                  '',
                                  $employee -> date_hired,
                                  $length_of_service,
                                  $employee -> employment_status_name,
                                  '',
                                  $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                  $employee -> contract_to,$sss,$phic,
                                  $hdmf,$tin,
                                ));
                              }
                            }
                            else
                            {
                              if($employee->department_id != "")
                              {
                                if($employee -> department_id == $department -> id)
                                {
                                  $sheet->row($row++, array(
                                    $count++,
                                    $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                    $employee -> nickname,
                                    $employee -> address,
                                    $employee -> birthdate,
                                    $employee -> contact_no,'','','',
                                    '',
                                    $employee -> date_hired,
                                    $length_of_service,
                                    $employee -> employment_status_name,
                                    '',
                                    $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                    $employee -> contract_to,$sss,$phic,
                                    $hdmf,$tin,
                                  ));
                                }
                              }
                            }
                        }

                    }
                  }
                }
            });

            $excel->sheet('Education', function($sheet) use($employee_list) {

                $row = 2;
                $sheet->setWidth('A', 5);
                $sheet->setAutoSize(array(
                    'B','C','D','E','F','K',
                ));
                $sheet->setWidth('G', 10);
                $sheet->setWidth('H', 10);
                $sheet->setWidth('I', 10);
                $sheet->setWidth('J', 10);
                $sheet->setWidth('L', 25);
                $sheet->setWidth('M', 25);
                $sheet->setWidth('N', 25);
                $sheet->setWidth('O', 25);
                $sheet->setWidth('P', 25);
                $sheet->setWidth('Q', 20);
                $sheet->setWidth('R', 20);
                $sheet->setWidth('S', 20);
                $sheet->setWidth('T', 20);
                $sheet->setWidth('U', 20);

                $sheet->setAllBorders('thin');
                $sheet->setHeight($row, 15);

                $sheet->row($row++, array(
                       'No.',
                       'FULL NAME',
                       'NICKNAME',
                       'ADDRESS',
                       'BIRTH DATE',
                       'CONTACT #',
                       'REQUIREMENTS','','','',
                       'DATE HIRED',
                       'LENGTH OF SERVICE',
                       'STATUS',
                       'CERTIFICATE/LICENSE',
                       'SALARY','CONTRACT',
                       '',
                       'GOVERNMENT CONTRIBUTION NUMBERS',
                       '','','',

                ));

                $sheet->setHeight($row, 25);

                $sheet->row($row++, array(
                       '',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'NSO','Diploma','TOR','NBI',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'PROBATIONARY','REGULAR',
                       'SSS','PHIC','HDMF',
                       'TIN',

                ));

                $count = 1;

                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:C3');
                $sheet->mergeCells('D2:D3');
                $sheet->mergeCells('E2:E3');
                $sheet->mergeCells('F2:F3');
                $sheet->mergeCells('G2:J2');
                $sheet->mergeCells('K2:K3');
                $sheet->mergeCells('L2:L3');
                $sheet->mergeCells('M2:M3');
                $sheet->mergeCells('N2:N3');
                $sheet->mergeCells('O2:O3');
                $sheet->mergeCells('P2:Q2');
                $sheet->mergeCells('R2:U2');

                $sheet->cells('A2:F2', function($cells) {
                    $cells->setBackground('#F8CBAD');
                });

                $sheet->cells('G2:J2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('G3:J3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('K2', function($cells) {
                    $cells->setBackground('#F4B084');
                });

                $sheet->cells('L2', function($cells) {
                    $cells->setBackground('#8EA9DB');
                });

                $sheet->cells('M2', function($cells) {
                    $cells->setBackground('#ED7D31');
                });

                $sheet->cells('N2', function($cells) {
                    $cells->setBackground('#0070C0');
                });

                $sheet->cells('O2', function($cells) {
                    $cells->setBackground('#CC99FF');
                });

                $sheet->cells('P2:Q2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('P3:Q3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('R3:U3', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('R2:U2', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('A2:Z2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->cells('A3:Z3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $department_list = Department::where('department.employee_type_id',4)
                        ->orderBy('department.department_name','ASC')
                        ->get();

                foreach ($department_list as $department) {

                  $count_employee = Employee::join('position','employee.position_id','=','position.id')
                            ->where('position.department_id',$department->id)
                            ->select('employee.person_id')
                            ->get()
                            ->count();


                  if($count_employee != 0)
                  {
                    
                    $sheet->cells('A'.$row.':U'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground('#000000');
                        $cells->setFontColor('#ffffff');
                    });

                    $sheet->setHeight($row, 20);

                    $sheet->row($row++, array('',$department->department_name));
                  // if($count_employee)
                  // {
                  //   echo $count_employee->person_id."<br>";
                  // }
                  // else
                  // {
                  //   echo "0"."<br>";
                  // }
                    foreach ($employee_list as $employee) 
                    {

                        $employee_department = EmployeePosition::join('position','employee_position.position_id','=','position.id')
                                ->where('employee_position.employee_id',$employee -> id)
                                ->select(['position.department_id'])
                                ->get()
                                ->last();

                        $sss = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'SSS'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $phic = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'PHIC'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $hdmf = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'HDMF'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $tin = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'TIN'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        if($employee -> employee_type_id == 4)
                        {
                            $sheet->cells('A'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $length_of_service = $this -> Get_Date_Difference($employee -> date_hired,date('Y-m-d'));

                            if($employee -> rate > 200 && $employee -> rate < 600)
                            {
                              $rate = $employee -> rate."/day";
                            }
                            else if($employee -> rate < 200)
                            {
                              $rate = $employee -> rate."/hr";
                            }
                            else
                            {
                              $rate = $employee -> rate;
                            }

                            if($sss)
                            {
                              $sss = $sss -> employee_contribution_number;
                            }
                            else
                            {
                              $sss = "";
                            }

                            if($phic)
                            {
                              $phic = $phic -> employee_contribution_number;
                            }
                            else
                            {
                              $phic = "";
                            }

                            if($hdmf)
                            {
                              $hdmf = $hdmf -> employee_contribution_number;
                            }
                            else
                            {
                              $hdmf = "";
                            }

                            if($tin)
                            {
                              $tin = $tin -> employee_contribution_number;
                            }
                            else
                            {
                              $tin = "";
                            }

                            if($employee_department)
                            {
                              if($employee_department -> department_id == $department -> id)
                              {
                                $sheet->row($row++, array(
                                  $count++,
                                  $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                  $employee -> nickname,
                                  $employee -> address,
                                  $employee -> birthdate,
                                  $employee -> contact_no,'','','',
                                  '',
                                  $employee -> date_hired,
                                  $length_of_service,
                                  $employee -> employment_status_name,
                                  '',
                                  $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                  $employee -> contract_to,$sss,$phic,
                                  $hdmf,$tin,
                                ));
                              }
                            }
                            else
                            {
                              if($employee->department_id != "")
                              {
                                if($employee -> department_id == $department -> id)
                                {
                                  $sheet->row($row++, array(
                                    $count++,
                                    $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                    $employee -> nickname,
                                    $employee -> address,
                                    $employee -> birthdate,
                                    $employee -> contact_no,'','','',
                                    '',
                                    $employee -> date_hired,
                                    $length_of_service,
                                    $employee -> employment_status_name,
                                    '',
                                    $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                    $employee -> contract_to,$sss,$phic,
                                    $hdmf,$tin,
                                  ));
                                }
                              }
                            }
                        }

                    }
                  }
                }
            });

            // $excel->sheet('INT\'L Staff', function($sheet) use($employee_list) {

            //     $row = 2;
            //     $sheet->setWidth('A', 5);
            //     $sheet->setAutoSize(array(
            //         'B','C','D','E','F','K',
            //     ));
            //     $sheet->setWidth('G', 10);
            //     $sheet->setWidth('H', 10);
            //     $sheet->setWidth('I', 10);
            //     $sheet->setWidth('J', 10);
            //     $sheet->setWidth('L', 25);
            //     $sheet->setWidth('M', 25);
            //     $sheet->setWidth('N', 25);
            //     $sheet->setWidth('O', 25);
            //     $sheet->setWidth('P', 25);
            //     $sheet->setWidth('Q', 20);

            //     $sheet->setAllBorders('thin');
            //     $sheet->setHeight($row, 15);

            //     $sheet->row($row++, array(
            //            'No.',
            //            'FULL NAME',
            //            'NICKNAME',
            //            'ADDRESS',
            //            'BIRTH DATE',
            //            'CONTACT #',
            //            'REQUIREMENTS','','','',
            //            'DATE HIRED',
            //            'LENGTH OF SERVICE',
            //            'STATUS',
            //            'CERTIFICATE/LICENSE',
            //            'SALARY','CONTRACT',
            //            '','','','',
            //            'GOVERNMENT CONTRIBUTION NUMBERS',

            //     ));

            //     $sheet->setHeight($row, 25);

            //     $sheet->row($row++, array(
            //            '',
            //            '',
            //            '',
            //            '',
            //            '',
            //            '',
            //            'NSO','Diploma','TOR','NBI',
            //            '',
            //            '',
            //            '',
            //            '',
            //            '',
            //            'PROBATIONARY','REGULAR',
            //            '','','',
            //            '',

            //     ));

            //     $count = 1;

            //     $sheet->mergeCells('A2:A3');
            //     $sheet->mergeCells('B2:B3');
            //     $sheet->mergeCells('C2:C3');
            //     $sheet->mergeCells('D2:D3');
            //     $sheet->mergeCells('E2:E3');
            //     $sheet->mergeCells('F2:F3');
            //     $sheet->mergeCells('G2:J2');
            //     $sheet->mergeCells('K2:K3');
            //     $sheet->mergeCells('L2:L3');
            //     $sheet->mergeCells('M2:M3');
            //     $sheet->mergeCells('N2:N3');
            //     $sheet->mergeCells('O2:O3');
            //     $sheet->mergeCells('P2:Q2');

            //     $sheet->cells('A2:F2', function($cells) {
            //         $cells->setBackground('#F8CBAD');
            //     });

            //     $sheet->cells('G2:J2', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('G3:J3', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('K2', function($cells) {
            //         $cells->setBackground('#F4B084');
            //     });

            //     $sheet->cells('L2', function($cells) {
            //         $cells->setBackground('#8EA9DB');
            //     });

            //     $sheet->cells('M2', function($cells) {
            //         $cells->setBackground('#ED7D31');
            //     });

            //     $sheet->cells('N2', function($cells) {
            //         $cells->setBackground('#0070C0');
            //     });

            //     $sheet->cells('O2', function($cells) {
            //         $cells->setBackground('#CC99FF');
            //     });

            //     $sheet->cells('P2:Q2', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('P3:Q3', function($cells) {
            //         $cells->setBackground('#FFE699');
            //     });

            //     $sheet->cells('A2:Z2', function($cells) {
            //         $cells->setAlignment('center');
            //         $cells->setValignment('center');
            //     });

            //     $sheet->cells('A3:Z3', function($cells) {
            //         $cells->setAlignment('center');
            //         $cells->setValignment('center');
            //     });

            //     foreach ($employee_list as $employee) 
            //     {
            //         if($employee -> employee_type_id == 1)
            //         {

            //             $sheet->setHeight($row, 20);
            //             $sheet->cells('A'.$row.':F'.$row, function($cells) {
            //                 $cells->setAlignment('center');
            //                 $cells->setValignment('center');
            //             });

            //             $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
            //                 $cells->setAlignment('center');
            //                 $cells->setValignment('center');
            //             });


            //             $sheet->row($row++, array(
            //               $count++,
            //               $employee -> first_name." ".$employee -> middle_name." ".$employee -> last_name,
            //               $employee -> nickname,
            //               $employee -> address,
            //               $employee -> birthdate,
            //               $employee -> contact_no,'','','',
            //               '',
            //               $employee -> date_hired,
            //               '',
            //               $employee -> employment_status_name,
            //               '',
            //               '',$employee -> contract_from." - ".$employee -> contract_to,
            //               $employee -> contract_to,'','','',
            //               '',

            //             ));
            //         }
            //     }

            // });
            
            $excel->sheet('INT\'L Staff', function($sheet) use($employee_list) {

                $row = 2;
                $sheet->setWidth('A', 5);
                $sheet->setAutoSize(array(
                    'B','C','D','E','F','K',
                ));
                $sheet->setWidth('G', 10);
                $sheet->setWidth('H', 10);
                $sheet->setWidth('I', 10);
                $sheet->setWidth('J', 10);
                $sheet->setWidth('L', 25);
                $sheet->setWidth('M', 25);
                $sheet->setWidth('N', 25);
                $sheet->setWidth('O', 25);
                $sheet->setWidth('P', 25);
                $sheet->setWidth('Q', 20);
                $sheet->setWidth('R', 20);
                $sheet->setWidth('S', 20);
                $sheet->setWidth('T', 20);
                $sheet->setWidth('U', 20);

                $sheet->setAllBorders('thin');
                $sheet->setHeight($row, 15);

                $sheet->row($row++, array(
                       'No.',
                       'FULL NAME',
                       'NICKNAME',
                       'ADDRESS',
                       'BIRTH DATE',
                       'CONTACT #',
                       'REQUIREMENTS','','','',
                       'DATE HIRED',
                       'LENGTH OF SERVICE',
                       'STATUS',
                       'CERTIFICATE/LICENSE',
                       'SALARY','CONTRACT',
                       '',
                       'GOVERNMENT CONTRIBUTION NUMBERS',
                       '','','',

                ));

                $sheet->setHeight($row, 25);

                $sheet->row($row++, array(
                       '',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'NSO','Diploma','TOR','NBI',
                       '',
                       '',
                       '',
                       '',
                       '',
                       'PROBATIONARY','REGULAR',
                       'SSS','PHIC','HDMF',
                       'TIN',

                ));

                $count = 1;

                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:C3');
                $sheet->mergeCells('D2:D3');
                $sheet->mergeCells('E2:E3');
                $sheet->mergeCells('F2:F3');
                $sheet->mergeCells('G2:J2');
                $sheet->mergeCells('K2:K3');
                $sheet->mergeCells('L2:L3');
                $sheet->mergeCells('M2:M3');
                $sheet->mergeCells('N2:N3');
                $sheet->mergeCells('O2:O3');
                $sheet->mergeCells('P2:Q2');
                $sheet->mergeCells('R2:U2');

                $sheet->cells('A2:F2', function($cells) {
                    $cells->setBackground('#F8CBAD');
                });

                $sheet->cells('G2:J2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('G3:J3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('K2', function($cells) {
                    $cells->setBackground('#F4B084');
                });

                $sheet->cells('L2', function($cells) {
                    $cells->setBackground('#8EA9DB');
                });

                $sheet->cells('M2', function($cells) {
                    $cells->setBackground('#ED7D31');
                });

                $sheet->cells('N2', function($cells) {
                    $cells->setBackground('#0070C0');
                });

                $sheet->cells('O2', function($cells) {
                    $cells->setBackground('#CC99FF');
                });

                $sheet->cells('P2:Q2', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('P3:Q3', function($cells) {
                    $cells->setBackground('#FFE699');
                });

                $sheet->cells('R3:U3', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('R2:U2', function($cells) {
                    $cells->setBackground('#AEAAAA');
                });

                $sheet->cells('A2:Z2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->cells('A3:Z3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $department_list = Department::where('department.employee_type_id',1)
                        ->orderBy('department.department_name','ASC')
                        ->get();

                foreach ($department_list as $department) {

                  $count_employee = Employee::join('position','employee.position_id','=','position.id')
                            ->where('position.department_id',$department->id)
                            ->select('employee.person_id')
                            ->get()
                            ->count();


                  if($count_employee != 0)
                  {
                    
                    $sheet->cells('A'.$row.':U'.$row, function($cells) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground('#000000');
                        $cells->setFontColor('#ffffff');
                    });

                    $sheet->setHeight($row, 20);

                    $sheet->row($row++, array('',$department->department_name));
                  // if($count_employee)
                  // {
                  //   echo $count_employee->person_id."<br>";
                  // }
                  // else
                  // {
                  //   echo "0"."<br>";
                  // }
                    foreach ($employee_list as $employee) 
                    {

                        $employee_department = EmployeePosition::join('position','employee_position.position_id','=','position.id')
                                ->where('employee_position.employee_id',$employee -> id)
                                ->select(['position.department_id'])
                                ->get()
                                ->last();

                        $sss = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'SSS'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $phic = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'PHIC'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $hdmf = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'HDMF'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $tin = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'TIN'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        if($employee -> employee_type_id == 1)
                        {
                            $sheet->cells('A'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $length_of_service = $this -> Get_Date_Difference($employee -> date_hired,date('Y-m-d'));

                            if($employee -> rate > 200 && $employee -> rate < 600)
                            {
                              $rate = $employee -> rate."/day";
                            }
                            else if($employee -> rate < 200)
                            {
                              $rate = $employee -> rate."/hr";
                            }
                            else
                            {
                              $rate = $employee -> rate;
                            }

                            if($sss)
                            {
                              $sss = $sss -> employee_contribution_number;
                            }
                            else
                            {
                              $sss = "";
                            }

                            if($phic)
                            {
                              $phic = $phic -> employee_contribution_number;
                            }
                            else
                            {
                              $phic = "";
                            }

                            if($hdmf)
                            {
                              $hdmf = $hdmf -> employee_contribution_number;
                            }
                            else
                            {
                              $hdmf = "";
                            }

                            if($tin)
                            {
                              $tin = $tin -> employee_contribution_number;
                            }
                            else
                            {
                              $tin = "";
                            }

                            if($employee_department)
                            {
                              if($employee_department -> department_id == $department -> id)
                              {
                                $sheet->row($row++, array(
                                  $count++,
                                  $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                  $employee -> nickname,
                                  $employee -> address,
                                  $employee -> birthdate,
                                  $employee -> contact_no,'','','',
                                  '',
                                  $employee -> date_hired,
                                  $length_of_service,
                                  $employee -> employment_status_name,
                                  '',
                                  $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                  $employee -> contract_to,$sss,$phic,
                                  $hdmf,$tin,
                                ));
                              }
                            }
                            else
                            {
                              if($employee->department_id != "")
                              {
                                if($employee -> department_id == $department -> id)
                                {
                                  $sheet->row($row++, array(
                                    $count++,
                                    $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                                    $employee -> nickname,
                                    $employee -> address,
                                    $employee -> birthdate,
                                    $employee -> contact_no,'','','',
                                    '',
                                    $employee -> date_hired,
                                    $length_of_service,
                                    $employee -> employment_status_name,
                                    '',
                                    $rate,$employee -> contract_from." - ".$employee -> contract_to,
                                    $employee -> contract_to,$sss,$phic,
                                    $hdmf,$tin,
                                  ));
                                }
                              }
                            }
                        }

                    }
                  }
                }
            });
            

            // $excel->sheet('Personal Staff', function($sheet) {  

            // });

            $employee_list = Employee::leftJoin('person','employee.person_id','=','person.id')
                            ->leftJoin('employee_type','employee.employee_type_id','=','employee_type.id')
                            ->leftJoin('employment_status','employee.employment_status_id','=','employment_status.id')
                            ->leftJoin('position','employee.position_id','=','position.id')
                            ->where('person.last_name','!=','')
                            ->where('person.is_active','=',0)
                            ->select(['employee.id','person.first_name','person.middle_name','person.last_name','employee.date_hired','employee.rate','person.nickname','person.address','person.birthdate','person.contact_no','employment_status.employment_status_name','employee_type.id as employee_type_id','employee.contract_from','employee.contract_to','position.department_id'])
                            ->orderBy('person.last_name','ASC')
                            ->get();

            
            $excel->sheet('Resigned Staff', function($sheet) use($employee_list) {

                $row = 2;
                $sheet->setWidth('A', 5);
                $sheet->setAutoSize(array(
                    'B','C','D','E','F','K',
                ));
                $sheet->setWidth('G', 10);
                $sheet->setWidth('H', 10);
                $sheet->setWidth('I', 10);
                $sheet->setWidth('J', 10);
                $sheet->setWidth('L', 25);
                $sheet->setWidth('M', 25);
                $sheet->setWidth('N', 25);
                $sheet->setWidth('O', 25);
                $sheet->setWidth('P', 25);
                $sheet->setWidth('Q', 20);
                $sheet->setWidth('R', 20);
                $sheet->setWidth('S', 20);
                $sheet->setWidth('T', 20);
                $sheet->setWidth('U', 20);

                $sheet->setAllBorders('thin');
                $sheet->setHeight($row, 15);

                $sheet->row($row++, array(
                       'No.',
                       'FULL NAME',
                       'NICKNAME',
                       'ADDRESS',
                       'BIRTH DATE',
                       'CONTACT #',

                ));

                $sheet->setHeight($row, 25);

                $sheet->row($row++, array(
                       '',
                       '',
                       '',
                       '',
                       '',
                       '',

                ));

                $count = 1;

                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:C3');
                $sheet->mergeCells('D2:D3');
                $sheet->mergeCells('E2:E3');
                $sheet->mergeCells('F2:F3');

                $sheet->cells('A2:F2', function($cells) {
                    $cells->setBackground('#F8CBAD');
                });

               

                $sheet->cells('A2:Z2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->cells('A3:Z3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                    foreach ($employee_list as $employee) 
                    {

                        $employee_department = EmployeePosition::join('position','employee_position.position_id','=','position.id')
                                ->where('employee_position.employee_id',$employee -> id)
                                ->select(['position.department_id'])
                                ->get()
                                ->last();

                        $sss = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'SSS'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $phic = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'PHIC'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $hdmf = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'HDMF'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $tin = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'TIN'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        if($employee -> employee_type_id != 4)
                        {
                            $sheet->cells('A'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->row($row++, array(
                              $count++,
                              $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                              $employee -> nickname,
                              $employee -> address,
                              $employee -> birthdate,
                              $employee -> contact_no,
                            ));
                        }

                    }
            });
            
            $excel->sheet('Resigned Teachers', function($sheet) use($employee_list) {

                $row = 2;
                $sheet->setWidth('A', 5);
                $sheet->setAutoSize(array(
                    'B','C','D','E','F','K',
                ));
                $sheet->setWidth('G', 10);
                $sheet->setWidth('H', 10);
                $sheet->setWidth('I', 10);
                $sheet->setWidth('J', 10);
                $sheet->setWidth('L', 25);
                $sheet->setWidth('M', 25);
                $sheet->setWidth('N', 25);
                $sheet->setWidth('O', 25);
                $sheet->setWidth('P', 25);
                $sheet->setWidth('Q', 20);
                $sheet->setWidth('R', 20);
                $sheet->setWidth('S', 20);
                $sheet->setWidth('T', 20);
                $sheet->setWidth('U', 20);

                $sheet->setAllBorders('thin');
                $sheet->setHeight($row, 15);

                $sheet->row($row++, array(
                       'No.',
                       'FULL NAME',
                       'NICKNAME',
                       'ADDRESS',
                       'BIRTH DATE',
                       'CONTACT #',

                ));

                $sheet->setHeight($row, 25);

                $sheet->row($row++, array(
                       '',
                       '',
                       '',
                       '',
                       '',
                       '',

                ));

                $count = 1;

                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:C3');
                $sheet->mergeCells('D2:D3');
                $sheet->mergeCells('E2:E3');
                $sheet->mergeCells('F2:F3');

                $sheet->cells('A2:F2', function($cells) {
                    $cells->setBackground('#F8CBAD');
                });

               

                $sheet->cells('A2:Z2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->cells('A3:Z3', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                    foreach ($employee_list as $employee) 
                    {

                        $employee_department = EmployeePosition::join('position','employee_position.position_id','=','position.id')
                                ->where('employee_position.employee_id',$employee -> id)
                                ->select(['position.department_id'])
                                ->get()
                                ->last();

                        $sss = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'SSS'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $phic = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'PHIC'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $hdmf = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'HDMF'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        $tin = EmployeeContributionNumber::where('employee_contribution_number.employee_id',$employee -> id)
                                ->where('employee_contribution_number.government_department','LIKE','%'.'TIN'.'%')
                                ->select(['employee_contribution_number.employee_contribution_number'])
                                ->get()
                                ->last();

                        if($employee -> employee_type_id == 4)
                        {
                            $sheet->cells('A'.$row.':F'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->cells('K'.$row.':FZ'.$row, function($cells) {
                                $cells->setAlignment('center');
                                $cells->setValignment('center');
                            });

                            $sheet->row($row++, array(
                              $count++,
                              $employee -> last_name.", ".$employee -> first_name." ".$employee -> middle_name,
                              $employee -> nickname,
                              $employee -> address,
                              $employee -> birthdate,
                              $employee -> contact_no,
                            ));
                        }

                    }
            });

        })->export('xls');
    }

}