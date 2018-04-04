<?php namespace App\Http\Controllers;

// use App\Http\Controllers\SchedulerMainController;
use App\Models\EmployeeType;
use App\Models\Department;
use App\Models\Position;
use App\Http\Requests\PositionRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class PositionController extends BaseController {
   

    public function dataJson(){
      $condition = \Input::all();
      $query = Position::select();
      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $building = $query->select([ 'id as value','building_name as text'])->get();

      return response()->json($building);
    }

    public function index()
    {
        $employee_type_list = EmployeeType::all();
        return view('position.index',compact('employee_type_list'));
    }

    public function create()
    {
        $employee_type_list = EmployeeType::all();
        $department_list = Department::all();
        $action = 0;
        return view('position.create',compact('employee_type_list','department_list','action'));
    }

    public function postCreate(PositionRequest $request)
    {
        $position = new Position();
        $position -> position_name = $request -> position_name;
        $position -> department_id = $request -> department_id;
        $position -> employee_type_id = $request -> employee_type_id;
        $position -> save();

        $success = \Lang::get('room.create_success').' : '.$position->position_name ; 
        return redirect('position/create')->withSuccess( $success);
    }

    public function edit($id)
    {
        $employee_type_list = EmployeeType::all();
        $department_list = Department::all();

        $action = 1;
        $position = Position::find($id);
        return view('position.edit',compact('employee_type_list','department_list','position','action'));
    }

    public function postEdit(PositionRequest $request,$id)
    {
        $position = Position::find($id);
        $position -> position_name = $request -> position_name;
        $position -> department_id = $request -> department_id;
        $position -> employee_type_id = $request -> employee_type_id;
        $position -> save();
        return redirect('position');
    }

    public function delete($id)
    {
        $employee_type_list = EmployeeType::all();
        $department_list = Department::all();

        $action = 1;
        $position = Position::find($id);
        return view('position.delete',compact('employee_type_list','department_list','position','action'));
    }

    public function postDelete(DeleteRequest $request,$id)
    {   
        $room = Position::find($id);
        $room->delete();
        return redirect('position');
    }


    public function data()
    {
        $position_list = Position::join('department','position.department_id','=','department.id')
                    ->join('employee_type','position.employee_type_id','=','employee_type.id')
                    ->select(['position.id','position.position_name','employee_type.employee_type_name','department.department_name']);
        return Datatables::of($position_list)
            ->add_column('actions', '<a href="{{{ URL::to(\'position/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span>  {{ Lang::get("form.edit") }}</a>
                    <a href="{{{ URL::to(\'position/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ Lang::get("form.delete") }}</a>
                ')            
            ->remove_column('id','employee_type_name','department_name')
            ->make();
    }

}
