<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id',
		'building_number', 
		'pincode',
		'street',
		'city',
		'country_id',
		'address_line_2',
		'address_type',
		'use_residential_address',
		'use_existing_address',
		'existing_address',
		'address_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'address_id',
    ];
}
