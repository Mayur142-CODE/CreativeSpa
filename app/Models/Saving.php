<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $table = 'savings';

    protected $fillable = [
        'date',
        'amount',
        'branch_id',
        'who_made',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
