<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'house_no' => 'required|min:1|unique:address',
            'block_no' => 'required|min:1|unique:address',
            'street' => 'required|min:3|unique:address',
            'other_sitio' => 'required|min:3|unique:address',
            'sitio_id' => 'required|min:1|unique:address',
            'other_barangay' => 'required|min:3|unique:address',
            'barangay_id' => 'required|min:1|unique:address',
            'municipality_city_id' => 'required|min:1|unique:address',
            'province_id' => 'required|min:1|unique:address',
            'country_id' => 'required|min:1|unique:address',
            'address_type_id' => 'required|min:1|unique:address',
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
