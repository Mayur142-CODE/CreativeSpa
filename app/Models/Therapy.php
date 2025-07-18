<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapy extends Model
{
    use HasFactory;

    protected $table = 'therapies';

    protected $fillable = [
        'name',
        'detail',
        'price',
        'duration',
    ];

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function therapiesForPackages()
    {
        return $this->belongsToMany(Package::class, 'package_service');
    }

}
