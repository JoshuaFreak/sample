<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleTypeRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'schedule_type_code' => 'required|min:1|unique:schedule_type',
            'schedule_type_name' => 'required|min:3|unique:schedule_type'
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
