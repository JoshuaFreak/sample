<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceEditRequest extends FormRequest {

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
           'province_name'=> 'required:province,province_name,'.$this->get('id'),
           'country_id'=> 'required:province,country_id,'.$this->get('id'),
           'is_default'=> 'required:province,is_default,'.$this->get('id')
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
