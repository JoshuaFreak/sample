<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassGradeApprovalRemarkRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'class_grade_approval_remark_name' => 'required|min:3|unique:class_grade_approval_remark',
		    'is_active' => 'required|min:3'
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
