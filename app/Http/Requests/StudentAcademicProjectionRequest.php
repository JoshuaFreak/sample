<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAcademicProjectionRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [

		    // 'level' => 'required',
		    // 'semester_level_id' => 'required',
		    // 'classification_id' => 'required',
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
