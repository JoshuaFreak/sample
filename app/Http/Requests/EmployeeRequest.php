<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
				'first_name' => 'required',
				// 'middle_name' => 'required',
				'last_name' => 'required',
    //          'date_employed' => 'required|min:1',
    //          'employment_status' => 'required|min:1',
    //          'corporate_entity' => 'required|min:1',
    // 			'is_active' => 'required|min:1'
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
