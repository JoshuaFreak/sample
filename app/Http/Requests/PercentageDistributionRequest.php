<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PercentageDistributionRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    // 'classification_id' => 'required',
		    // 'subject_id' => 'required',
		    // 'class_component_category_id' => 'required',
		    // 'percentage' => 'required',
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
