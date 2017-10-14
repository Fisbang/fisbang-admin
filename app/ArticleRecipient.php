<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ArticleRecipient extends Model
{
	use Sortable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'article_id',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'user_id', 'article_id', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'user_id' => 'integer',
		'article_id' => 'integer',
	];
	
	/**
     * Get the article associated with the recipient.
     */
    public function article()
    {
        return $this->belongsTo('App\Article', 'article_id');
    }
	
	/**
     * Get the user associated with the recipient.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
