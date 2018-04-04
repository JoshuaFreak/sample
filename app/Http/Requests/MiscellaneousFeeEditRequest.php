<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MiscellaneousFeeEditRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
           'fee_type_name' => 'unique:fee_type,fee_type_name,'.$this->get('id')
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
