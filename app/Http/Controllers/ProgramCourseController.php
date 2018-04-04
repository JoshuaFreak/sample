<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TEClass;
use App\Models\Course;
use App\Models\CourseCapacity;
use App\Models\Program;
use App\Models\ProgramCourse;
use App\Http\Requests\ProgramCourseRequest;
use Datatables;
use Illuminate\Http\Request;

class ProgramCourseController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$program_list = Program::orderBy('category','ASC')->get();
		$class_list = TEClass::all();
		return view('program_course.index', compact('program_list', 'class_list'));
	}

	public function getCreate() {
		$program_list = Program::orderBy('category','ASC')->get();
		$course_capacity_list = CourseCapacity::where('is_active',1)->get();
		$class_list = TEClass::all();
		$course_list = Course::all();
		return view('program_course.create', compact('program_list', 'course_capacity_list', 'class_list', 'course_list'));
	}

	public function postCreate(ProgramCourseRequest $program_course_request) {
		foreach($program_course_request -> course_id as $course)
		{
			$new_program_course = new ProgramCourse();
			$new_program_course -> program_id = $program_course_request -> program_id;
			$new_program_course -> course_id = $course;
			$new_program_course -> course_capacity_id = $program_course_request -> course_capacity_id;
			$new_program_course -> class_id = $program_course_request -> class_id;
			$new_program_course -> is_active = 1;
			$new_program_course -> save();
		}

		return redirect('program_course/create'); 
	}

	public function data() {
		$program_id = \Input::get('program_id');
		$class_id = \Input::get('class_id');

		if($program_id != null && $class_id != null)
		{
			$program_course_list = ProgramCourse::join('program', 'program_course.program_id', '=', 'program.id')
									->join('course', 'program_course.course_id', '=', 'course.id')
									->join('course_capacity', 'program_course.course_capacity_id', '=', 'course_capacity.id')
									->join('class', 'program_course.class_id', '=', 'class.id')
									->where('program_course.program_id', '=', $program_id)
									->where('program_course.class_id', '=', $class_id)
									->select(['program_course.id', 'program.program_name', 'class.class_name', 'course_capacity.capacity_name', 'course.course_name'])
									->orderBy('program_course.class_id', 'ASC');
		}
		elseif ($program_id != null && $class_id == null) {
			$program_course_list = ProgramCourse::join('program', 'program_course.program_id', '=', 'program.id')
									->join('course', 'program_course.course_id', '=', 'course.id')
									->join('course_capacity', 'program_course.course_capacity_id', '=', 'course_capacity.id')
									->join('class', 'program_course.class_id', '=', 'class.id')
									->where('program_course.program_id', '=', $program_id)
									->select(['program_course.id', 'program.program_name', 'class.class_name', 'course_capacity.capacity_name', 'course.course_name'])
									->orderBy('program_course.class_id', 'ASC');
		}
		elseif ($program_id == null && $class_id != null) {
			$program_course_list = ProgramCourse::join('program', 'program_course.program_id', '=', 'program.id')
									->join('course', 'program_course.course_id', '=', 'course.id')
									->join('course_capacity', 'program_course.course_capacity_id', '=', 'course_capacity.id')
									->join('class', 'program_course.class_id', '=', 'class.id')
									->where('program_course.class_id', '=', $class_id)
									->select(['program_course.id', 'program.program_name', 'class.class_name', 'course_capacity.capacity_name', 'course.course_name'])
									->orderBy('program_course.class_id', 'ASC');
		}
		else {
			$program_course_list = ProgramCourse::join('program', 'program_course.program_id', '=', 'program.id')
									->join('course', 'program_course.course_id', '=', 'course.id')
									->join('course_capacity', 'program_course.course_capacity_id', '=', 'course_capacity.id')
									->join('class', 'program_course.class_id', '=', 'class.id')
									->select(['program_course.id', 'program.program_name', 'class.class_name', 'course_capacity.capacity_name', 'course.course_name'])
									->orderBy('program_course.class_id', 'ASC');
		}

		return Datatables::of($program_course_list)
			->remove_column('id')
			->make();
	}

}
