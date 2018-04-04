<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRateRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'employee_id' => 'required|min:3|unique:employee_rate',
            'employment_status_id' => 'required|min:1',
            'rate_type_id' => 'required|min:1',
            'is_active' => 'required|min:1'
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
