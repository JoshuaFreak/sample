<?php namespace App\Models\Generic;

use Illuminate\Database\Eloquent\Model;

class GenPerson extends Model {
	 /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table='gen_person';

    protected $fillable = ['gen_person_id','last_name','first_name','gen_gender_id'];

}
