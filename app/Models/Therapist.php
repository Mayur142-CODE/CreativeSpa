<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    use HasFactory;

    protected $table = 'therapists';

    protected $fillable = [
        'name',
        'email',
        'dob',
        'contact',
        'gender',
        'address',
        'designation',
        'hourly_rate',
        'salary_type',
        'working_hours_type',
        'payroll_calculation',
        'profile_picture',
        'branch_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
