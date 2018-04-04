<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficeEmployeeEditRequest extends FormRequest {

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
           'employee_id'=> 'unique:office_employee,employee_id,'.$this->get('id'),
           'person_id'=> 'required:office_employee,person_id,'.$this->get('id'),
           'user_id'=> 'required:office_employee,user_id,'.$this->get('id'),
           'is_active'=> 'required:office_employee,is_active,'.$this->get('id')
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
