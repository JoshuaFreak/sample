<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisteredOldRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    // 'student_no' => 'required|min:2|unique:student',
		    // 'last_name' => 'required',
		    // 'first_name' => 'required',
		    // 'classification_id' => 'required',
		    // 'curriculum_id' => 'required',
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
