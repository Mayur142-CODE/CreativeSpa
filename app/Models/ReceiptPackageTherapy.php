<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptPackageTherapy extends Model
{
    use HasFactory;

    protected $table = 'receipt_package_therapies';

    protected $fillable = [
        'receipt_id',
        'package_id',
        'therapy_id',
        'therapist_id',
        'original_qty',
        'redeem_qty',
        'price',
        'time_in',
        'time_out',
        'date',
        'discount',
        'total',
    ];

    // Relationship with Receipt
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    // Relationship with Package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relationship with Therapy
    public function therapy()
    {
        return $this->belongsTo(Therapy::class);
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }

}
