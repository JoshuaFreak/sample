<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhilhealthEditRequest extends FormRequest {

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
           'range_from'=> 'unique:philhealth,range_from,'.$this->get('id'),
           'range_to' => 'required:philhealth,range_to,'.$this->get('id'),
		    'salary_base' => 'required:philhealth,salary_base,'.$this->get('id'),
		    'total_monthly_contribution' => 'required:philhealth,total_monthly_contribution,'.$this->get('id'),
		    'semi_monthly_contribution' => 'required:philhealth,semi_monthly_contribution,'.$this->get('id'),
		    'ph_ps' => 'required:philhealth,ph_ps,'.$this->get('id'),
		    'ph_es' => 'required:philhealth,ph_es,'.$this->get('id'),
		    'semi_monthly_ps' => 'required:philhealth,semi_monthly_ps,'.$this->get('id'),
		    'semi_monthly_es' => 'required:philhealth,semi_monthly_es,'.$this->get('id')
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
