<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    // 'receipt_no' => 'required|min:3|unique:payment',
		    // 'cash_tendered' => 'required|min:3',
		    // 'amount' => 'required|min:1',
		    // 'payment_description' => 'required|min:5',
		    // 'payment_mode_id' => 'required|min:1',
		    // 'payment_type_id' => 'required|min:1',
		    // 'payment_status_id' => 'required|min:1'
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
