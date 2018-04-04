<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassComponentCategoryRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'class_component_category_name' => 'required|min:3|unique:class_component_category',
		    'class_component_category_code' => 'required|min:3|unique:class_component_category',
            'education_category_id' => 'required|min:1'
            'is_active' => 'required|min:1'
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
