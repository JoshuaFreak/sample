<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonCredentialRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'person_id' => 'required|min:1|unique:person_credential',
            'credential_id' => 'required|min:1|unique:person_credential'
            'file_id' => 'required|min:1|unique:person_credential'
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
