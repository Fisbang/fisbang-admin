<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Article extends Model
{
	use Sortable;
	
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'url', 'image',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'title', 'description', 'url', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'title' => 'string',
		'description' => 'string',
		'url' => 'string',
		'image' => 'string',
	];
		
	/**
     * Get the article recipients associated with the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'article_recipients', 'article_id', 'user_id');
    }
		
	/**
     * Get the article recipients associated with the user.
     */
    public function recipients()
    {
        return $this->hasMany('App\ArticleRecipient', 'article_id');
    }
}
