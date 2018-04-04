<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransmutationRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    // 'classification_name' => 'required|min:5|max:100',
		    // 'order_no' => 'required|min:1|unique:classification',
            
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
