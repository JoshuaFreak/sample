<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumCourseCategoryEditRequest extends FormRequest {

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
           'curriculum_course_category_name'=> 'unique:curriculum_course_category,curriculum_course_category_name,'.$this->get('id'),
           'program_id'=> 'required:curriculum_course_category,program_id,'.$this->get('id')
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
