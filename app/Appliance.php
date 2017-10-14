<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Appliance extends Model
{
	use Sortable;
	
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'building_id', 'brand', 'brand_type', 'power',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = ['id', 'building_id', 'name', 'type', 'power', 'brand', 'brand_type', 'created_at', 'updated_at'];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'building_id' => 'integer',
		'name' => 'string',
		'type' => 'string',
		'power' => 'integer',
		'brand' => 'string',
		'brand_type' => 'string',
	];
	
	/**
     * Get the building associated with the appliance.
     */
    public function building()
    {
        return $this->belongsTo('App\Building', 'building_id');
    }
}
