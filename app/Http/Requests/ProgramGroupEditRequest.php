<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramGroupEditRequest extends FormRequest {

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
           'program_id'=> 'required:program_group,program_id,'.$this->get('id'),
           'classification_level_id'=> 'required:program_group,classification_level_id,'.$this->get('id'),
           'semester_level_id'=> 'required:program_group,semester_level_id,'.$this->get('id'),
           'term_id'=> 'required:program_group,term_id,'.$this->get('id')
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
