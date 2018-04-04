<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'student_no' => 'max:100|unique:student',
		    'last_name' => 'required|max:100',
		    'first_name' => 'required|max:100',
		    'middle_name' => 'required|max:100',
		    'birthdate' => 'required|max:100',
		    'gender' => 'required|min:3',
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
