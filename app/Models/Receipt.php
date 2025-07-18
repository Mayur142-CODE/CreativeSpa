<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Therapy;
use App\Models\Therapist;
use App\Models\Package;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'receipts';

    protected $fillable = [
        'customer_id',
        'service_type',
        'date',
        'total_amount',
        'payment_method',
        'package_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    // Relationship with Package
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function receiptTherapies()
    {
        return $this->hasMany(ReceiptTherapy::class);
    }

    // Relationship with multiple package therapies in a receipt
    public function receiptPackageTherapies()
    {
        return $this->hasMany(ReceiptPackageTherapy::class);
    }
}
