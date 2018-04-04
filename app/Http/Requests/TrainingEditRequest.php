<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingEditRequest extends FormRequest {

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
           'course_id'=> 'unique:training,course_id,'.$this->get('id'),
           'batch_id'=> 'unique:training,batch_id,'.$this->get('id'),
           'instructor_id'=> 'unique:training,instructor_id,'.$this->get('id'),
           'assessor_id'=> 'unique:training,assessor_id,'.$this->get('id'),
           'capacity'=> 'unique:training,capacity,'.$this->get('id'),
           'date_from'=> 'unique:training,date_from,'.$this->get('id'),
           'date_to'=> 'unique:training,date_to,'.$this->get('id'),
           'training_fee'=> 'unique:training,training_fee,'.$this->get('id'),
           'is_active'=> 'unique:training,is_active,'.$this->get('id')
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
