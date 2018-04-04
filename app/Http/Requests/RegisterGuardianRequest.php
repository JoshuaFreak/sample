<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterGuardianRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'last_name' => 'required',
		    'first_name' => 'required',
		    'username' => 'required',
		    'student' => 'required',
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
