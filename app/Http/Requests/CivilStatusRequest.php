<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CivilStatusRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'civil_status_name' => 'required|min:3|unique:civil_status',
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
