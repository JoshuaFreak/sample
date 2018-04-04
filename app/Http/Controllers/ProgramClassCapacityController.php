<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCapacity;
use App\Models\ProgramClassCapacity;
use App\Models\Program;
use App\Http\Requests\ProgramCourseRequest;
use Datatables;
use DB;
use Illuminate\Http\Request;

class ProgramClassCapacityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$program_list = Program::orderBy('category','ASC')->get();
		$course_capacity_list = CourseCapacity::all();
		return view('program_class_capacity.index', compact('program_list', 'course_capacity_list'));
	}

	public function dataJson()
	{
		$program_id = \Input::get('program_id');

		$program_class_capacity = ProgramClassCapacity::join('course_capacity','program_class_capacity.course_capacity_id','=','course_capacity.id')->where('program_class_capacity.program_id',$program_id)
						->select(['program_class_capacity.id','course_capacity.capacity_name'])
						->get();

		
        return response()->json($program_class_capacity);
	}	

	public function postDataJson()
	{
		$program_capacity_arr = \Input::get('program_capacity_arr');
        $max = sizeof($program_capacity_arr);
        json_decode(serialize($program_capacity_arr));

        $program_capacity_data = [];
        for ($i=0; $i < $max; $i++) { 

        	$program_capacity_data[$i] = ['program_id' => $program_capacity_arr[$i][0],'course_capacity_id' => $program_capacity_arr[$i][1]];
        }

	    DB::table('program_class_capacity')->insert($program_capacity_data);
        return response()->json($program_capacity_data);
	}

}
