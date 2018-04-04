<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantEditRequest extends FormRequest {

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
           'person_id'=> 'unique:applicant,person_id,'.$this->get('id'),
           'position_desired'=> 'required:applicant,position_desired,'.$this->get('id'),
           'date_available_to_start'=> 'required:applicant,date_available_to_start,'.$this->get('id')
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
