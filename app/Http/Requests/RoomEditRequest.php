<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomEditRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		//var_dump($this);
		//return false;
		//return [
           // 'name'=> 'exists:gen_role,name,gen_role_id,'.$this->get('gen_role_id'),
		//];
		return [
           // 'room_type_id'=> 'required:room,room_type_id,'.$this->get('id'),
           // 'room_name'=> 'required:room,room_name,'.$this->get('id')
           // 'building_id'=> 'required:room,building_id,'.$this->get('id')
           // 'is_active'=> 'required:room,is_active,'.$this->get('id')
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
