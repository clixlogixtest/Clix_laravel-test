<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id',
		'title', 
		'first_name',
		'last_name',
		'job_title',
		'nationality',
		'date_of_birth',
		'town_of_birth',
		'phone_number',
    ];
}
