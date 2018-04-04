<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkingDayStatusEditRequest extends FormRequest {

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
           'working_day_status_code'=> 'unique:working_day_status,working_day_status_code,'.$this->get('id'),
           'working_day_status_name'=> 'unique:working_day_status,working_day_status_name,'.$this->get('id')
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
