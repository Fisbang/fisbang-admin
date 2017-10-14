<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Question extends Model
{
	use Sortable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id', 'is_premium', 'is_answered', 'billing', 'hours',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'owner_id', 'is_answered', 'is_premium', 'billing', 'hours', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'is_answered' => 'boolean',
		'is_premium' => 'boolean',
		'owner_id' => 'integer',
		'billing' => 'integer',
		'hours' => 'integer',
	];
	
	/**
     * Get the user associated with the question.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }
	
	/**
     * Get the messages associated with the question.
     */
    public function messages()
    {
        return $this->hasMany('App\Message', 'question_id');
    }
}
