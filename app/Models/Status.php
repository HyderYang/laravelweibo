<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert($toArray)
 */
class Status extends Model {

	protected $fillable = ['content'];
	
	public function user() {
		return $this->belongsTo(User::class);
	}
}
