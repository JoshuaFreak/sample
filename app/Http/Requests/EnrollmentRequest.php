<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
				
			'student_curriculum_id' => 'required',
			'term_id' => 'required',
			// 'payment_scheme_id' => 'required',
			// 'is_new' => 'required',
			// 'incoming_grade_level_id' => 'required',
			'classification_level_id' => 'required',
			// 'semester_level_id' => 'required',
			'section_id' => 'required'
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
