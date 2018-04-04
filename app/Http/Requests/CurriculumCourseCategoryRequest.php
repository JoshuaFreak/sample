<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumCourseCategoryRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'curriculum_course_category_name' => 'required|min:3|unique:curriculum_course_category',
            'program_id' => 'required|min:1'
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
