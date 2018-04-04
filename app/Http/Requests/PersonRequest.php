<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'prefix_id' => 'required|min:3',
		    'first_name' => 'required|min:3',
		    'last_name' => 'required|min:3',
		    'middle_name' => 'required|min:3',
		    'birthdate' => 'required|min:3',
		    'gender_id' => 'required|min:3',
		    'civil_status_id' => 'required|min:3',
		    'citizenship_id' => 'required|min:3'
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
