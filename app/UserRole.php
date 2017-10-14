<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_name',
    ];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'role_name' => 'string',
	];
	
    public $timestamps = false;
	
	/**
     * Get the users of this role
     */
    public function users()
    {
        return $this->hasMany('App\User', 'role_id');
    }
}
