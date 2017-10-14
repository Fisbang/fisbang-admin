<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Message extends Model
{
	use Sortable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'content', 'question_id',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'sender_id', 'question_id', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'content' => 'string',
		'sender_id' => 'integer',
		'question_id' => 'integer',
	];
		
	/**
     * Get the question associated with the message.
     */
    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id');
    }
		
	/**
     * Get the user associated with the message.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
	
	/**
     * Get the attachments associated with the message.
     */
    public function attachments()
    {
        return $this->hasMany('App\MessageAttachment', 'message_id');
    }
}
