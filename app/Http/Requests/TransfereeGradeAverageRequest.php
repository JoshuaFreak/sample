<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransfereeGradeAverageRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'average' => 'required',
		    'school_year' => 'required',
		    'student_id' => 'required',
		    'classification_level_id' => 'required',
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
