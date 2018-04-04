<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferringGradeAverageEditRequest extends FormRequest {

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
           // 'grade_remark_name'=> 'required:grade_remark,grade_remark_name,'.$this->get('id'),
           // 'grade_remark_code'=> 'required:grade_remark,grade_remark_name,'.$this->get('id'),
           // 'min_range'=> 'required:grade_remark,grade_remark_name,'.$this->get('id'),
           // 'max_range'=> 'required:grade_remark,grade_remark_name,'.$this->get('id'),
           // 'classification_id'=> 'required:grade_remark,grade_remark_name,'.$this->get('id')
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
