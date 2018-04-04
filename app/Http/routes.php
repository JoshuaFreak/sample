<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/  


    Route::get('/', 'HomeController@index'); 
    Route::pattern('id', '[0-9]+');

    Route::get('auth/login', 'Auth\AuthController@showLoginForm');
    Route::post('auth/login', 'Auth\AuthController@login');
    Route::get('auth/register', 'Auth\AuthController@showRegistrationForm');
    Route::post('auth/register', 'Auth\AuthController@register');
    Route::get('auth/logout', 'Auth\AuthController@logout');

    #Dashboard
    Route::get('learn_more', 'LearnMoreController@index');
    Route::get('module', 'DashboardHomepageController@index');

    #Course
    Route::get('course/', 'CourseController@index');
    Route::get('course/create', 'CourseController@getCreate');
    Route::post('course/create', 'CourseController@postCreate');
    Route::get('course/data', 'CourseController@data');

    #Program Course
    Route::get('program_course/', 'ProgramCourseController@index');
    Route::get('program_course/create', 'ProgramCourseController@getCreate');
    Route::post('program_course/create', 'ProgramCourseController@postCreate');
    Route::get('program_course/data', 'ProgramCourseController@data');

    #Program
    Route::get('program/', 'ProgramController@index');
    Route::get('program/create', 'ProgramController@getcreate');
    Route::post('program/create', 'ProgramController@postCreate');
    Route::get('program/data', 'ProgramController@data');

    Route::get('add_examination/', 'AddExaminationController@index');

    #Program Course
    Route::get('program_class_capacity/', 'ProgramClassCapacityController@index');
    Route::get('program_class_capacity/dataJson', 'ProgramClassCapacityController@dataJson');
    Route::post('program_class_capacity/postDataJson', 'ProgramClassCapacityController@postDataJson');

    #Position
    Route::get('position/', 'PositionController@index');
    Route::get('position/create', 'PositionController@create');
    Route::post('position/create', 'PositionController@postCreate');
    Route::get('position/{id}/edit', 'PositionController@edit');
    Route::post('position/{id}/edit', 'PositionController@postEdit');
    Route::get('position/{id}/delete', 'PositionController@delete');
    Route::post('position/{id}/delete', 'PositionController@postDelete');
    Route::get('position/data', 'PositionController@data');

    #Employee
    Route::get('hrms/', 'HrmsController@index');
    Route::get('hrms/report/employee_list', 'HrmsController@reportEmployeeList');
    Route::get('hrms/report/employee_list_excel', 'HrmsController@reportEmployeeListExcel');
    Route::get('employee/', 'EmployeeController@index');
    Route::get('employee/{id}', 'EmployeeController@indexId');
    Route::get('employee/full_detail', 'EmployeeController@fullDetail');
    Route::get('employee/printPDF', 'EmployeeController@printDetail');
    Route::get('employee/detail', 'EmployeeController@getDetail');
    Route::get('employee/dataJson', 'dataJson\EmployeeController@dataJson');
    Route::get('employee/create', 'EmployeeController@getCreate');
    Route::get('employee/getPosition', 'EmployeeController@getPosition');
    Route::post('employee/create', 'EmployeeController@postCreate');
    Route::post('employee/createJson', 'EmployeeController@createJson');
    Route::post('employee/savePosition', 'EmployeeController@savePosition');
    Route::post('employee/deleteRow', 'EmployeeController@deleteRow');
    Route::post('employee/delete_employee_data', 'EmployeeController@deleteEmployeeData');
    Route::get('employee/positionDataJson', 'EmployeeController@positionDataJson');
    Route::get('employee/data', 'EmployeeController@data');
    Route::get('employee/dataJsonTeacher', 'dataJson\EmployeeController@dataJsonTeacher');
    Route::get('employee/programCategoryDataJson', 'dataJson\EmployeeController@programCategoryDataJson');
    Route::get('employee/employee_type', 'EmployeeController@getEmployeeTypeList');
    Route::post('employee/set_to_active', 'EmployeeController@setToActive');
    Route::get('employee/employee201Pdf', 'dataJson\EmployeeController@employee201Pdf');

    #Employee Dependent
    Route::post('employee_dependent/postSaveEmployeeDependentJson', 'EmployeeDependentController@postSaveEmployeeDependentJson');
    Route::post('employee_dependent/postSaveEmployeeContactJson', 'EmployeeDependentController@postSaveEmployeeContactJson');
    Route::post('employee_dependent/postSaveGovernmentContributionJson', 'EmployeeDependentController@postSaveGovernmentContributionJson');
    Route::post('employee_dependent/postSaveEmployeeExperienceJson', 'EmployeeDependentController@postSaveEmployeeExperienceJson');
    Route::post('employee_dependent/postSaveEmployeeCertificateJson', 'EmployeeDependentController@postSaveEmployeeCertificateJson');
    Route::post('person_education/postSavePersonEducationJson', 'PersonEducationController@postSavePersonEducationJson');
    Route::post('person_seminar/postSavePersonSeminarJson', 'PersonSeminarController@postSavePersonSeminarJson');

    #Teacher 
    Route::get('teacher/', 'TeacherController@index');
    Route::get('teacher/create', 'TeacherController@getCreate');
    Route::post('teacher/create', 'TeacherController@postCreate');
    Route::get('teacher/{id}/edit', 'TeacherController@getEdit');
    Route::get('teacher/data', 'TeacherController@data');
    Route::get('teacher/dataJson', 'dataJson\TeacherController@dataJson');
    Route::get('teacher/teacherDataJson', 'dataJson\TeacherController@teacherDataJson');
    Route::get('teacher/defaultProgramDataJson', 'TeacherController@defaultProgramDataJson');
    Route::get('teacher/programdataJson', 'TeacherController@programdataJson');
    Route::get('teacher_subject/', 'TeacherSubjectController@index');
    Route::post('teacher_subject/dataJson', 'TeacherSubjectController@dataJson');
    Route::get('teacher_subject/getDataJson', 'TeacherSubjectController@getDataJson');

    #PercentageLevel
    Route::get('percentage_level/', 'PercentageLevelController@index');
    Route::get('percentage_level/create', 'PercentageLevelController@getCreate');
    Route::post('percentage_level/create', 'PercentageLevelController@postCreate');
    Route::get('percentage_level/data', 'PercentageLevelController@data');


####ADMINISTRATOR############
    #Events
    // Route::get('admin/events', 'EventController@index');
    // Route::get('admin/events/data', 'EventController@data');
    // Route::get('admin/events/dataJson', 'dataJson\EventController@dataJson');
    // Route::get('admin/events/create', 'EventController@getCreate');
    // Route::post('admin/events/create', 'EventController@postCreate');
    // Route::get('admin/events/{id}/edit', 'EventController@getEdit');
    // Route::post('admin/events/{id}/edit', 'EventController@postEdit');
    // Route::get('admin/events/{id}/delete', 'EventController@getDelete');
    // Route::post('admin/events/{id}/delete', 'EventController@postDelete');
    // Route::get('admin/events/image/{id}', 'EventController@getImage');
    
    // #User
    // Route::get('admin/dashboard', 'DashboardAdminController@index');
    // Route::get('admin/chat_monitoring', 'DashboardAdminController@chatMonitoring');
    // Route::get('admin/online_teacher', 'DashboardAdminController@onlineTeacher');
    // Route::get('admin/onlineTeacherDataJson', 'DashboardAdminController@onlineTeacherDataJson');
    Route::get('gen_user/data', 'GenUserController@data');
    Route::get('gen_user', 'GenUserController@index');
    // Route::get('gen_user/create', 'GenUserController@getCreate');
    // Route::post('gen_user/create', 'GenUserController@postCreate');
    Route::get('gen_user/{id}/edit', 'GenUserController@getEdit');
    Route::post('gen_user/{id}/edit', 'GenUserController@postEdit');
    // Route::get('gen_user/{id}/delete', 'GenUserController@getDelete');
    // Route::post('gen_user/{id}/delete', 'GenUserController@postDelete');
    Route::post('gen_user_role/delete', 'GenUserController@postDeleteRole');
    
    // #Roles
    // Route::get('gen_role/data', 'GenRoleController@data');
    // Route::get('gen_role/', 'GenRoleController@index');
    // Route::get('gen_role/create', 'GenRoleController@getCreate');
    // Route::post('gen_role/create', 'GenRoleController@postCreate');
    // Route::get('gen_role/{id}/edit', 'GenRoleController@getEdit');
    // Route::post('gen_role/{id}/edit', 'GenRoleController@postEdit');
    // Route::get('gen_role/{id}/delete', 'GenRoleController@getDelete');
    // Route::post('gen_role/{id}/delete', 'GenRoleController@postDelete');

   #Admin Report
 

####HRMS############


    // Route::get('school', 'SchoolController@index');
    // Route::get('school/', 'SchoolController@index');
    // Route::get('school/data', 'SchoolController@data');
    // Route::get('school/create', 'SchoolController@getCreate');
    // Route::post('school/create', 'SchoolController@postCreate');
    // Route::get('school/{id}/edit', 'SchoolController@getEdit');
    // Route::post('school/{id}/edit', 'SchoolController@postEdit');
    // Route::get('school/{id}/delete', 'SchoolController@getDelete');
    // Route::post('school/{id}/delete', 'SchoolController@postDelete');
    // Route::get('school/dataJson', 'dataJson\SchoolController@dataJson');

    
    #Subject
    // Route::get('Subject', 'SubjectController@index');
    // Route::get('subject/data', 'SubjectController@data');
    // Route::get('subject/', 'SubjectController@index');
    // Route::get('subject/create', 'SubjectController@getCreate');
    // Route::post('subject/create', 'SubjectController@postCreate');
    // Route::get('subject/{id}/edit', 'SubjectController@getEdit');
    // Route::post('subject/{id}/edit', 'SubjectController@postEdit');
    // Route::get('subject/{id}/delete', 'SubjectController@getDelete');
    // Route::post('subject/{id}/delete', 'SubjectController@postDelete');
    // Route::get('subject/dataJson', 'dataJson\SubjectController@dataJson');
    // Route::get('subject/subjectOfferedDataJson', 'dataJson\SubjectController@subjectOfferedDataJson');
    // Route::get('subject/dataJsonPrerequisite', 'dataJson\SubjectController@dataJsonPrerequisite');

####SCHEDULER############

    Route::get('scheduler/', 'SchedulerController@index');
    Route::get('scheduler/course_student', 'SchedulerController@courseStudent');
    Route::get('scheduler/course_student/data', 'SchedulerController@courseStudentData');
    Route::get('scheduler/import_class', 'SchedulerController@importClass');
    Route::post('scheduler/import_class', 'SchedulerController@postImportClass');
    Route::get('scheduler/create', 'SchedulerController@create');
    Route::get('scheduler/create_schedule', 'SchedulerController@createSchedule');
    Route::post('scheduler/deleteClass', 'SchedulerController@deleteClass');
    Route::post('scheduler/deleteStudentClassSchedule', 'SchedulerController@deleteStudentClassSchedule');
    Route::post('scheduler/deleteClassAll', 'SchedulerController@deleteClassAll');
    Route::post('scheduler/deleteClassDates', 'SchedulerController@deleteClassDates');
    Route::get('scheduler/group_class', 'SchedulerController@groupClass');
    Route::get('scheduler/activity_class', 'SchedulerController@activityClass');
    Route::get('scheduler/men_to_men', 'SchedulerController@menToMen');
    Route::get('scheduler/books_per_room', 'BookController@index');
    Route::get('scheduler/books_per_room_1on1', 'BookController@index1on1');
    Route::get('scheduler/ieltsBook1on1', 'BookController@ieltsBook1on1');
    Route::get('scheduler/toeicBook1on1', 'BookController@toeicBook1on1');
    Route::get('scheduler/businessBook1on1', 'BookController@businessBook1on1');
    Route::get('scheduler/workingBook1on1', 'BookController@workingBook1on1');
    Route::get('scheduler/teachers_per_room', 'BookController@indexTeacher');
    Route::get('scheduler/teacher_report', 'dataJson\TeacherController@indexReport');
    Route::get('scheduler/get_room_book', 'BookController@getRoomBook');
    Route::get('scheduler/get_esl_book', 'BookController@getESLBook');
    Route::get('scheduler/get_ielts_book', 'BookController@getIELTSBook');
    Route::get('scheduler/get_toeic_book', 'BookController@getTOEICBook');
    Route::get('scheduler/get_business_book', 'BookController@getBusinessBook');
    Route::get('scheduler/get_working_book', 'BookController@getWorkingBook');
    Route::get('scheduler/get_room_teacher', 'BookController@getRoomTeacher');
    Route::post('scheduler/save_room_book', 'BookController@saveRoomBook');
    Route::post('scheduler/save_esl_book', 'BookController@saveESLbook');
    Route::post('scheduler/save_ielts_book', 'BookController@saveIELTSbook');
    Route::post('scheduler/save_toeic_book', 'BookController@saveTOEICbook');
    Route::post('scheduler/save_business_book', 'BookController@saveBusinessbook');
    Route::post('scheduler/save_working_book', 'BookController@saveWorkingbook');
    Route::post('scheduler/save_room_teacher', 'BookController@saveRoomTeacher');
    Route::post('scheduler/create', 'SchedulerController@postCreate');
    Route::post('scheduler/createStudent', 'SchedulerController@postCreateStudent');
    Route::post('scheduler/edit', 'SchedulerController@postEdit');
    Route::post('scheduler/update_teacher_class', 'SchedulerController@updateTeacherClass');
    Route::get('scheduler/get_teacher_class', 'SchedulerController@getTeacherClass');
    Route::get('scheduler/programCourseDataJson', 'dataJson\ProgramController@programCourseDataJson');
    Route::get('scheduler/courseCapacityRoomDataJson', 'dataJson\ProgramController@courseCapacityRoomDataJson');
    Route::get('scheduler/courseCapacityRoomTeacherDataJson', 'dataJson\ProgramController@courseCapacityRoomTeacherDataJson');
    Route::get('scheduler/studentListLocal', 'dataJson\SchedulerController@studentListLocal');
    Route::get('scheduler/checkVacantSchedule', 'dataJson\SchedulerController@checkVacantSchedule');
    Route::get('scheduler/getRoomTeacher', 'dataJson\SchedulerController@getRoomTeacher');
    Route::get('scheduler/getScheduleStudentByTeacher', 'dataJson\SchedulerController@getScheduleStudentByTeacher');
    Route::get('scheduler/getScheduleDataJson', 'SchedulerController@getScheduleDataJson');
    Route::get('scheduler/getScheduleByGroupDataJson', 'SchedulerController@getScheduleByGroupDataJson');
    Route::get('scheduler/getScheduleDataJsonStudent', 'SchedulerController@getScheduleDataJsonStudent');
    Route::get('scheduler/getStudentScheduleToday', 'SchedulerController@getStudentScheduleToday');
    Route::get('scheduler/getStudentScheduleByCourse', 'SchedulerController@getStudentScheduleByCourse');
    Route::get('scheduler/getScheduleGroupDataJson', 'SchedulerController@getScheduleGroupDataJson');
    Route::get('scheduler/getScheduleSelfDataJson', 'SchedulerController@getScheduleSelfDataJson');
    Route::get('scheduler/getBatchStudentDataJson', 'SchedulerController@getBatchStudentDataJson');
    Route::get('scheduler/getScheduleDataJsonByTeacher', 'SchedulerController@getScheduleDataJsonByTeacher');
    Route::get('scheduler/getTimeDataJson', 'SchedulerController@getTimeDataJson');
    Route::get('scheduler/report/daily_schedules', 'SchedulerReportController@dailySchedules');
    Route::get('scheduler/report/daily_schedules_excel', 'SchedulerReportController@dailySchedulesExcel');
    Route::get('scheduler/dataJson/teacherSchedulePdf', 'dataJson\TeacherController@teacherSchedulePdf');


    Route::get('room', 'RoomController@index');
    Route::get('room/create', 'RoomController@create');
    Route::post('room/create', 'RoomController@postCreate');
    Route::get('room/{id}/edit', 'RoomController@getEdit');
    Route::post('room/{id}/edit', 'RoomController@postEdit');
    Route::get('room/data', 'RoomController@data');
   #Building
    // Route::get('building', 'BuildingController@index');
    // Route::get('building/', 'BuildingController@index');
    // Route::get('building/data', 'BuildingController@data');
    // Route::get('building/create', 'BuildingController@getCreate');
    // Route::post('building/create', 'BuildingController@postCreate');
    // Route::get('building/{id}/edit', 'BuildingController@getEdit');
    // Route::post('building/{id}/edit', 'BuildingController@postEdit');
    // Route::get('building/{id}/delete', 'BuildingController@getDelete');
    // Route::post('building/{id}/delete', 'BuildingController@postDelete');
    // Route::get('building/dataJson', 'BuildingController@dataJson');

    Route::get('campus', 'CampusController@index');
    Route::get('campus/', 'CampusController@index');
    Route::get('campus/data', 'CampusController@data');
    Route::get('campus/create', 'CampusController@getCreate');
    Route::post('campus/create', 'CampusController@postCreate');
    Route::get('campus/{id}/edit', 'CampusController@getEdit');
    Route::post('campus/{id}/edit', 'CampusController@postEdit');
    Route::get('campus/{id}/delete', 'CampusController@getDelete');
    Route::post('campus/{id}/delete', 'CampusController@postDelete');
    Route::get('campus/dataJson', 'CampusController@dataJson');
   
   // #RoomType
   //  Route::get('room_type', 'RoomTypeController@index');
   //  Route::get('room_type/', 'RoomTypeController@index');
   //  Route::get('room_type/data', 'RoomTypeController@data');
   //  Route::get('room_type/create', 'RoomTypeController@getCreate');
   //  Route::post('room_type/create', 'RoomTypeController@postCreate');
   //  Route::get('room_type/{id}/edit', 'RoomTypeController@getEdit');
   //  Route::post('room_type/{id}/edit', 'RoomTypeController@postEdit');
   //  Route::get('room_type/{id}/delete', 'RoomTypeController@getDelete');
   //  Route::post('room_type/{id}/delete', 'RoomTypeController@postDelete');
   //  Route::get('room_type/dataJson', 'RoomTypeController@dataJson');
   
   #Room

    // Route::get('register', 'RegisterController@index');
    Route::get('register/', 'RegisterController@index');
    Route::get('register/import', 'RegisterController@import');
    Route::post('register/import', 'RegisterController@postImport');
    Route::get('register/data', 'RegisterController@data');
    Route::get('register/create', 'RegisterController@getCreate');
    Route::post('register/create', 'RegisterController@postCreate');
    Route::get('register/student_list', 'RegisterController@studentList');
    // Route::get('register/{id}/edit', 'RegisterController@getEdit');
    // Route::post('register/{id}/edit', 'RegisterController@postEdit');
    // Route::get('register/{id}/delete', 'Register@getDelete');
    // Route::post('register/{id}/delete', 'Register@postDelete');
    // Route::get('register/dataJson', 'Register@dataJson');
    // Route::get('register/scheduleDataJson', 'dataJson\Register@scheduleDataJson'); 


    Route::get('registrar/import_student_score/', 'RegisterController@importStudentScore');
    Route::post('registrar/import_student_score/', 'RegisterController@postImportStudentScore');
    
    Route::get('scoreEntry', 'ScoreEntryController@index');
    Route::get('scoreEntry/', 'ScoreEntryController@index');
    Route::get('scoreEntry/dataJson', 'ScoreEntryController@dataJson');
    Route::get('scoreEntry/studentScore', 'ScoreEntryController@studentScore');
    Route::get('scoreEntry/studentNoScore', 'ScoreEntryController@studentNoScore');
    Route::post('scoreEntry/create', 'ScoreEntryController@createScore');
    Route::get('scoreEntry/getChart', 'ScoreEntryController@getChart');

    Route::get('scoreEntry/scoreResultExcel','ScoreEntryController@scoreResultExcel');


    Route::get('examination/dataJson', 'dataJson\ExaminationController@dataJson');

    Route::get('student', 'StudentController@index');
    Route::get('student/list', 'StudentController@studentList');
    Route::get('student/getStudentList', 'StudentController@getStudentList');
    Route::get('student/view_data', 'StudentController@viewData');
    Route::get('student/studentToDepart', 'StudentController@studentToDepart');
    Route::get('student/studentToArrive', 'StudentController@studentToArrive');
    Route::get('student/getStudentProgram', 'StudentController@getStudentProgram');
    Route::get('student/getStudentSchedule', 'StudentController@getStudentSchedule');
    Route::get('student/studentSchedulePdf', 'StudentController@studentSchedulePdf');
    Route::post('student/saveAllStudentData', 'StudentController@saveAllStudentData');
    Route::get('kiosk', 'KioskController@index');

    Route::get('teacher_portal', 'TeacherPortalController@index');
    Route::post('teacher_portal/createJson', 'TeacherPortalController@createJson');
    Route::get('teacher_portal/schedule', 'TeacherPortalController@schedule');
    Route::get('teacher_portal/dataJson', 'TeacherPortalController@dataJson');
    Route::get('teacher_portal/teacherSchedulePdf', 'TeacherPortalController@teacherSchedulePdf');

    //payroll routes
    Route::get('payroll', 'PayrollController@index');

    // scheduler viewing
    Route::get('/view_module', 'WelcomeController@index');
    Route::get('/student_view', 'SchedulerViewController@index');
    Route::get('/teacher_view', 'SchedulerViewController@teacher');
    Route::get('/group_class_view', 'SchedulerViewController@groupClass');
    Route::get('/activity_class_view', 'SchedulerViewController@activityClass');
    Route::get('scheduler_view/getTimeDataJson', 'SchedulerViewController@getTimeDataJson');
    Route::get('scheduler_view/studentListLocal', 'SchedulerViewController@studentListLocal');
    Route::get('scheduler_view/getScheduleDataJsonStudent', 'SchedulerViewController@getScheduleDataJsonStudent');
    Route::get('scheduler_view/getScheduleByGroupDataJson', 'SchedulerViewController@getScheduleByGroupDataJson');
    Route::get('scheduler_view/getScheduleGroupDataJson', 'SchedulerViewController@getScheduleGroupDataJson');
    Route::get('scheduler_view/getScheduleSelfDataJson', 'SchedulerViewController@getScheduleSelfDataJson');
    Route::get('scheduler_view/get_teacher_class', 'SchedulerViewController@getTimeDataJson');


    // header('Access-Control-Allow-Origin:*');

    // header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');

    // header('Access-Control-Allow-Headers:Origin, Content-Type, Accept, Authorization, X-Requested-With');

    // Route::get('/api/', 'ApiController@index'); 
    // Route::get('/api/login', 'ApiController@login'); 
    // Route::post('/api/login', 'ApiController@postLogin'); 