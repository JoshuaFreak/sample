<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumCourseRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'curriculum_id' => 'required|min:1',
            'semester_level_id' => 'required|min:1',
            'classification_level_id' => 'required|min:1',
            'curriculum_course_category_id' => 'required|min:1',
            'term_type_id' => 'required|min:1',
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
