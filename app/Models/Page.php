<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'title',
        'slug',
        'content',
        'image',
        'meta_description',
        'meta_keyword',
        'status',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }
}
