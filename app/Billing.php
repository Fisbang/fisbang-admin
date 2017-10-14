<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Billing extends Model
{
	use Sortable;
	
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total', 'power_consumption', 'owner_id', 'billing_date',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'owner_id', 'billing_date', 'total', 'power_consumption', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'owner_id' => 'integer',
		'billing_date' => 'date',
		'total' => 'integer',
		'power_consumption' => 'integer',
	];
	
	/**
     * Get the owner associated with the billing.
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }
}
