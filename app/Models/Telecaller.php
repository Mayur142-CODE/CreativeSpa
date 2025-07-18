<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telecaller extends Model
{
    use HasFactory;

    protected $table = 'telecaller';
    
    protected $fillable = [
        'name',
        'phone_number',
        'branch_id'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
