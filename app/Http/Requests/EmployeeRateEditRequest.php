<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRateEditRequest extends FormRequest {

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
           'employee_id'=> 'unique:employee_rate,employee_id,'.$this->get('id'),
           'employment_status_id'=> 'required:employee_rate,employment_status_id,'.$this->get('id'),
           'rate_type_id'=> 'required:employee_rate,rate_type_id,'.$this->get('id'),
           'is_active'=> 'required:employee_rate,is_active,'.$this->get('id')
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
