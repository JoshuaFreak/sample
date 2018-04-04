<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherDepartmentRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'teacher_id' => 'required|min:1|unique:teacher_department',
            'department_id' => 'required|min:1',
            'is_active' => 'required|min:1',
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
