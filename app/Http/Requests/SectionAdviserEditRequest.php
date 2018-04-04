<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionAdviserEditRequest extends FormRequest {

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
           //'building_name'=> 'unique:term_type,building_name,'.$this->get('id'),
           // 'campus_id'=> 'required:term_type,campus_id,'.$this->get('id'),
           // 'is_active'=> 'required:term_type,is_active,'.$this->get('id')
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
