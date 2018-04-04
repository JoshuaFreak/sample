<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeportmentEditRequest extends FormRequest {

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
           'deportment_name'=> 'unique:deportment,deportment_name,'.$this->get('id'),
           'is_active'=> 'unique:deportment,is_active,'.$this->get('id'),
           'pdg_sub_category_id'=> 'unique:deportment,pdg_sub_category_id,'.$this->get('id')
           
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
