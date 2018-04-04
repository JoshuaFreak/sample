<?php namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\AttendanceRemark;
use Illuminate\Support\Facades\Auth;
use Config;    
use Datatables;
use Hash;

class AttendanceRemarkController extends BaseController {

    public function dataJson(){

      $condition = \Input::all();
      $query = AttendanceRemark::select();

      foreach($condition as $column => $value)
      {
        $query->where($column, '=', $value);
      }
      $attendance_remark = $query->select(['attendance_remarks_code as text'])->get();

      return response()->json($attendance_remark);
    }

}
