<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRemarkRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'enrollment_remark_name' => 'required|min:3|unique:enrollment_remark',
            'is_default' => 'required|min:1'
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
