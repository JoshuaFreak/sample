<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRemarkRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'grade_remark_name' => 'required|min:3|unique:grade_remark',
		    'grade_remark_code' => 'required|min:3',
		    'min_range' => 'required|min:3',
		    'max_range' => 'required|min:3',
		    'classification_id' => 'required|min:3'
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
