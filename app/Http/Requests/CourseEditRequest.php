<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseEditRequest extends FormRequest {

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
           'course_code'=> 'unique:course,course_code,'.$this->get('id'),
           'description'=> 'unique:course,description,'.$this->get('id'),
           'day_from'=> 'required:course,day_from,'.$this->get('id'),
           'day_to'=> 'required:course,day_to,'.$this->get('id'),
           'default_training_fee'=> 'required:course,default_training_fee,'.$this->get('id'),
           'minimum_capacity'=> 'required:course,minimum_capacity,'.$this->get('id'),
           'maximum_capacity'=> 'required:course,maximum_capacity,'.$this->get('id'),
           'maximum_batch'=> 'required:course,maximum_batch,'.$this->get('id'),
           'is_active'=> 'required:course,is_active,'.$this->get('id')
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
