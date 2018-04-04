<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonCredentialEditRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		//var_dump($this);
		//return false;
		//return [
           // 'name'=> 'exists:gen_role,name,gen_role_id,'.$this->get('gen_role_id'),
		//];
		return [
           'person_id'=> 'unique:person_credential,person_id,'.$this->get('id'),
           'credential_id'=> 'unique:person_credential,credential_id,'.$this->get('id'),
           'file_id'=> 'unique:person_credential,file_id,'.$this->get('id'),
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
