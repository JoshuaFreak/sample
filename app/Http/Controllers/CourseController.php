<?php namespace App\Http\Controllers;

use App\Http\Requests;
// use App\Http\Controllers\Controller;
use App\Models\TEClass;
use App\Models\Course;
use App\Models\CourseCapacity;
use App\Models\Program;
use App\Models\ProgramCourse;
use App\Http\Requests\CourseRequest;
use Datatables;

use Illuminate\Http\Request;

class CourseController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('registrar');
    }

	public function index() {
		return view('course.index');
	}

	public function getCreate() {
		return view('course.create');
	}

	public function postCreate(CourseRequest $course_request) {
		$new_course = new Course();
		$new_course -> course_name = $course_request -> course_name;
		$new_course -> save();

		return redirect('course/');
	}

	public function data() {
		$program_course_list = Course::select(['course.id', 'course.course_name'])
									->orderBy('course.id', 'ASC');

		return Datatables::of($program_course_list)
			->remove_column('id')
			->make();
	}


}
