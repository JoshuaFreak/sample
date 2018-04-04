<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraineeLedgerRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'term_type_name' => 'required|min:3|unique:term_type',
            'term_type_no' => 'required|min:1|unique:term_type'
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
