<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherEditRequest extends FormRequest {

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
           // 'program_id'=> 'required',
           // 'is_default'=> 'required'
           // 'person_id'=> 'required:teacher,person_id,'.$this->get('id'),
           // 'user_id'=> 'required:teacher,user_id,'.$this->get('id'),
           // 'is_active'=> 'required:teacher,is_active,'.$this->get('id')
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
