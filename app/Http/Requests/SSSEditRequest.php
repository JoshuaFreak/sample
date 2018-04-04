<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SSSEditRequest extends FormRequest {

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
           'range_from'=> 'unique:sss,range_from,'.$this->get('id'),
           'range_to'=> 'unique:sss,range_to,'.$this->get('id'),
           'salary_base'=> 'unique:sss,salary_base,'.$this->get('id'),
           'sss_er'=> 'unique:sss,sss_er,'.$this->get('id'),
           'sss_ee'=> 'unique:sss,sss_ee,'.$this->get('id'),
           'sss_total'=> 'unique:sss,sss_total,'.$this->get('id'),
           'ec_er'=> 'required:sss,ec_er,'.$this->get('id'),
           'er_total_contribution'=> 'unique:sss,er_total_contribution,'.$this->get('id'),
           'ee_total_contribution'=> 'unique:sss,ee_total_contribution,'.$this->get('id'),
           'er_ee_total_contribution'=> 'unique:sss,er_ee_total_contribution,'.$this->get('id'),
           'se_vm_ofw_total_contribution'=> 'unique:sss,se_vm_ofw_total_contribution,'.$this->get('id')
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
