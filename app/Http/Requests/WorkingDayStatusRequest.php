<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkingDayStatusRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'working_day_status_code' => 'required|min:1|unique:working_day_status',
            'working_day_status_name' => 'required|min:3|unique:working_day_status'
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
