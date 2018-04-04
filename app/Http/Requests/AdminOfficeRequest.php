<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermTypeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'admin_id' => 'required|min:3|unique:admin_office',
		    'office_id' => 'required|min:3',
		    'is_active' => 'required|min:3',
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
