<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TuitionFeeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'tuition_fee_amount' => 'required|min:3|unique:tuition_fee',
            'classification_id' => 'required|min:1|unique:tuition_fee'
            'term_id' => 'required|min:1|unique:tuition_fee'
            'date' => 'required|min:1|unique:tuition_fee'
            'admin_id' => 'required|min:1|unique:tuition_fee'
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
