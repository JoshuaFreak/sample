<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeportmentRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'deportment_name' => 'required|min:3|unique:deportment',
			'is_active' => 'required|min:1',
			'pdg_sub_category_id' => 'required|min:1'
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
