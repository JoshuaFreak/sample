<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentCurriculumRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'student_id' => 'required|min:1|unique:student_curriculum',
		    'curriculum_id' => 'required|min:1',
		    'date_assigned' => 'required|min:1',
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
