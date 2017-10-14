<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Building extends Model
{
	use Sortable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'max_power', 'owner_id',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'owner_id', 'max_power', 'name', 'type', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'owner_id' => 'integer',
		'max_power' => 'integer',
		'name' => 'string',
		'type' => 'string',
	];
	
	/**
     * Get the user associated with the building.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }
		
	/**
     * Get the appliances associated with the building.
     */
    public function appliances()
    {
        return $this->hasMany('App\Appliance', 'building_id');
    }
}
