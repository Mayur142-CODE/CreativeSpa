<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Therapy;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'detail',
        'price',
        'validity_count',
        'validity_unit',
    ];

    public function therapies()
    {
        return $this->belongsToMany(Therapy::class, 'package_service')->withPivot('qty', 'total');;
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

}
