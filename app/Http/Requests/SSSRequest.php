<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SSSRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'range_from' => 'required|min:3',
            'range_to' => 'required|min:1'
            'salary_base' => 'required|min:1'
            'sss_er' => 'required|min:1'
            'sss_ee' => 'required|min:1'
            'sss_total' => 'required|min:1'
            'ec_er' => 'required|min:1'
            'er_total_contribution' => 'required|min:1'
            'ee_total_contribution' => 'required|min:1'
            'er_ee_total_contribution' => 'required|min:1'
            'se_vm_ofw_total_contribution' => 'required|min:1'
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
