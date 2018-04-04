<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferringGradeAverageRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    // 'average' => 'required|min:2',
		    // 'school_year' => 'required|min:2',
		    'student_name' => 'required',
		    // 'classification_level_id' => 'required',
		    // 'school_id' => 'required|min:3',
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
