<?php namespace App\Models\Generic;

use Illuminate\Database\Eloquent\Model;

class GenGender extends Model {
	protected $table="gen_gender";

    protected $fillable = ['name','code'];
}
