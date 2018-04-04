<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OffenseRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'offense_name' => 'required|min:3|unique:offense',
            'description' => 'required|min:1'
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
