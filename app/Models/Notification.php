<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'order_detail_id',
        'title',
        'description',
        'company_name',
        'type',
        'is_read',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'order_detail_id',
    ];

    /*public function getCreatedAtAttribute($date)
	{
	    return Carbon::parse($date)->diffForHumans();
	}*/
}
