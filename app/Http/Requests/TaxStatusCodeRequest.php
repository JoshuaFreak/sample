<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxStatusCodeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'tax_status_code_name' => 'required|min:3|unique:tax_status_code'
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
