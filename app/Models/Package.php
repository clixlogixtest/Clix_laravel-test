<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'image',
        'period_type',
        'period_value',
        'status',
    ];

    public function package_details()
    {
        return $this->hasMany(PackageDetail::class, 'package_id', 'id');
    }

    public function services_includes()
    {
        return $this->hasMany(PackageDetail::class, 'package_id', 'id');
    }
}
