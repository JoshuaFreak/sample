<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramSectionGroupRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'program_group_id' => 'required|min:3|unique:program_section_group',
		    'section_id' => 'required|min:3|unique:program_section_group',
            'teacher_id' => 'required|min:1|unique:program_section_group'
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
