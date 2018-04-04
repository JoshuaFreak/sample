<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasicEducationAttendanceTypeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'basic_education_attendance_type_name' => 'required|min:3|unique:basic_education_attendance_type',
		    'is_default' => 'required|min:1',
		    'weight' => 'required|min:1',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

}
