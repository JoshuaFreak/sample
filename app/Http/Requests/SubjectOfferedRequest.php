<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectOfferedRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    // 'program_name' => 'required|min:3',
      //       'program_code' => 'required|min:1',
      //       'classification_id' => 'required|min:1',
      //       'is_active' => 'required|min:1',
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
