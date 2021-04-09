<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name',
		'slug',
		'is_default',
		'status',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class, 'template_id', 'id');
    }
}
