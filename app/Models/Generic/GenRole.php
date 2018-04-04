<?php namespace App\Models\Generic;

use Illuminate\Database\Eloquent\Model;

class GenRole extends Model {
   /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_role';
	//protected $primaryKey ='gen_role_id';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'is_admin'];


}
