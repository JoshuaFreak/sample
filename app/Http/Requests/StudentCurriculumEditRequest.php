<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentCurriculumEditRequest extends FormRequest {

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
           'student_id'=> 'unique:student_curriculum,student_id,'.$this->get('id'),
           'curriculum_id'=> 'required:student_curriculum,curriculum_id,'.$this->get('id'),
           'date_assigned'=> 'required:student_curriculum,date_assigned,'.$this->get('id'),
           'is_active'=> 'required:student_curriculum,is_active,'.$this->get('id')
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
