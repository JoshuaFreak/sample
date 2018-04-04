<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhilhealthRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'range_from' => 'required|min:3',
		    'range_to' => 'required|min:3',
		    'salary_base' => 'required|min:3',
		    'total_monthly_contribution' => 'required|min:3',
		    'semi_monthly_contribution' => 'required|min:3h',
		    'ph_ps' => 'required|min:3',
		    'ph_es' => 'required|min:3',
		    'semi_monthly_ps' => 'required|min:3',
		    'semi_monthly_es' => 'required|min:3'
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
