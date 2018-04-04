<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressEditRequest extends FormRequest {

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
           'house_no'=> 'unique:address,house_no,'.$this->get('id'),
           'block_no'=> 'unique:address,block_no,'.$this->get('id')
           'street'=> 'unique:address,street,'.$this->get('id')
           'other_sitio'=> 'unique:address,other_sitio,'.$this->get('id')
           'sitio_id'=> 'unique:address,sitio_id,'.$this->get('id')
           'other_barangay'=> 'unique:address,other_barangay,'.$this->get('id')
           'barangay_id'=> 'unique:address,barangay_id,'.$this->get('id')
           'province_id'=> 'unique:address,province_id,'.$this->get('id')
           'country_id'=> 'unique:address,country_id,'.$this->get('id')
           'address_type_id'=> 'unique:address,address_type_id,'.$this->get('id')
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
