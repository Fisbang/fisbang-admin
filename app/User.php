<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use App\UserRole;

class User extends Authenticatable
{
    use Notifiable;
	use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'avatar',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'name', 'email', 'role_id', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'string',
		'name' => 'string',
		'email' => 'string',
		'role_id' => 'integer',
		'avatar' => 'string',
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	/**
     * Get gravatar url
     *
     * $return string
     */
	 public function getGravatarUrl()
	{
		$hash = md5(strtolower(trim($this->attributes['email'])));
		return "http://www.gravatar.com/avatar/$hash";
	}
	
	/**
     * Find out if user has a specific role
     *
     * $return boolean
     */
    public function hasRole($check)
    {
		$role = UserRole::find($this->role_id);
        return !is_null($role) && strcasecmp($check, $role->role_name) == 0;
    }
	
	/**
     * Check whether user is admin or not.
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }
	
	/**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo('App\UserRole', 'role_id');
    }
		
	/**
     * Get the questions associated with the user.
     */
    public function questions()
    {
        return $this->hasMany('App\Question', 'owner_id');
    }
		
	/**
     * Get the messages associated with the user.
     */
    public function messages()
    {
        return $this->hasMany('App\Message', 'sender_id');
    }
		
	/**
     * Get the buildings associated with the user.
     */
    public function buildings()
    {
        return $this->hasMany('App\Building', 'owner_id');
    }
		
	/**
     * Get the billings associated with the user.
     */
    public function billings()
    {
        return $this->hasMany('App\Billing', 'owner_id');
    }
		
	/**
     * Get the article recipients associated with the user.
     */
    public function articles()
    {
        return $this->belongsToMany('App\Article', 'article_recipients', 'user_id', 'article_id');
    }
}
