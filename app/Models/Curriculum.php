<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Curriculum extends Model {
   /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'curriculum';

	public function program() 
	{
        return $this->belongsTo('App\Models\Program');
    }
	
	public function term()
	{
		return $this->belongsTo('App\Models\Term');
	}

	public function classification()
    {
        return $this->hasManyThrough('classification', 'Progam');
    }
}
