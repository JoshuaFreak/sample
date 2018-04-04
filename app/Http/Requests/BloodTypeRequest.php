<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BloodTypeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'blood_type_name' => 'required|min:1|unique:blood_type|max:5',
            
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
