<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolEditRequest extends FormRequest {

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
           // 'school_name'=> 'unique:school,school_no,'.$this->get('id'),
           // 'is_default'=> 'required:school,is_default,'.$this->get('id')
           // 'is_active'=> 'required:school,is_active,'.$this->get('id')
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