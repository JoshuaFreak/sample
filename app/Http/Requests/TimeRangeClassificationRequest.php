<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeRangeClassificationRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'classification_id' => 'required|min:1',
            'time_range_id' => 'required|min:1',
            'term_id' => 'required|min:1',
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
