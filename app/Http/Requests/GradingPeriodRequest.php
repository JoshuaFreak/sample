<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradingPeriodRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    // 'classification_id' => 'required',
		    'grading_period_name' => 'required',
		    // 'term_id' => 'required',
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
