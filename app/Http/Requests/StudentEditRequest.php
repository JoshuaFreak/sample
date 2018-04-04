<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentEditRequest extends FormRequest {

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
           'student_no'=> 'unique:student,term_type_no,'.$this->get('id'),
           'last_name'=> 'required:student,last_name,'.$this->get('id'),
           'first_name'=> 'required:student,first_name,'.$this->get('id'),
           'middle_name'=> 'required:student,middle_name,'.$this->get('id'),
           'birthdate'=> 'required:student,birthdate,'.$this->get('id'),
           'gender'=> 'required:student,gender,'.$this->get('id'),
           'is_active'=> 'required:student,is_active,'.$this->get('id')
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
