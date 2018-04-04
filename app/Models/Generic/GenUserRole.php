<?php namespace App\Models\Generic;

use Illuminate\Database\Eloquent\Model;

class GenUserRole extends Model {
    protected $guarded = array();

    public static $rules = array();
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_user_role';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['gen_user_id', 'gen_role_id'];

}


