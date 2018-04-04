<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdjustmentEditRequest extends FormRequest {

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
           'term_type_name'=> 'required:term_type,term_type_name,'.$this->get('id'),
           'is_deduction'=> 'required:term_type,is_deduction,'.$this->get('id'),
           'is_active'=> 'required:term_type,is_active,'.$this->get('id')
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
