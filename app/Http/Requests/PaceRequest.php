<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaceRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'classification_id' => 'required',
		    'classification_level_id' => 'required',
		    'subject_id' => 'required',
		    'pace_name' => 'required'
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
