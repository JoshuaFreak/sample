<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassComponentCategoryEditRequest extends FormRequest {

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
           'class_component_category_name'=> 'unique:class_component_category,class_component_category_name,'.$this->get('id'),
           'class_component_category_code'=> 'unique:class_component_category,class_component_category_code,'.$this->get('id'),
           
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

}s