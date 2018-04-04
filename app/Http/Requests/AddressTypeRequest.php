<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressTypeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'address_type_name' => 'required|min:3|unique:address_type|max:100',
            
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
