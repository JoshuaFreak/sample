<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAcademicProjectionEditRequest extends FormRequest {

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
           // 'classification_name'=> 'unique:classification,classification_name,'.$this->get('id'),
           // 'order_no'=> 'unique:classification,order_no,'.$this->get('id')
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
