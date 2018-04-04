<?php namespace App\Models\Generic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class GenLanguage extends Model {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_language';
	
	
	/**
	 * The attributes included in the model's JSON form.
	 *
	 * @var array
	 */
	protected $fillable = array('name', 'code', 'description', 'icon');
	
	/**
	 * The rules for email field, automatic validation.
	 *
	 * @var array
	*/
	private $rules = array(
			'name' => 'required|min:2',
			'code' => 'required|min:2'
	);
	
	public function getImageUrl( $withBaseUrl = false )
	{
		if(!$this->icon) return NULL;
		
		$imgDir = '/images/languages/' . $this->id;
		$url = $imgDir . '/' . $this->icon;
		
		return $withBaseUrl ? URL::asset( $url ) : $url;
	}
}