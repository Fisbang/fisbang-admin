<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class MessageAttachment extends Model
{
	use Sortable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path', 'message_id',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'path', 'message_id', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'path' => 'string',
		'message_id' => 'integer',
	];
	
	/**
     * Get the message associated with the attachment.
     */
    public function message()
    {
        return $this->belongsTo('App\Message', 'message_id');
    }
}
